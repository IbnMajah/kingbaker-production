<?php

namespace App\Http\Controllers;

use App\Models\DailyRecord;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
// use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Reports/Index', [
            'productStats' => $this->getProductStats(),
            'dailyStats' => $this->getDailyStats(),
            'summaryStats' => [
                'weekly' => $this->getSummaryStats('week'),
                'monthly' => $this->getSummaryStats('month'),
                'yearly' => $this->getSummaryStats('year'),
            ],
        ]);
    }

    private function getProductStats()
    {
        // Get data for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        
        $stats = Product::select('products.name')
            ->selectRaw('SUM(daily_record_products.quantity) as total_quantity')
            ->selectRaw('SUM(daily_record_products.revenue) as total_revenue')
            ->leftJoin('daily_record_products', 'products.id', '=', 'daily_record_products.product_id')
            ->leftJoin('daily_records', 'daily_record_products.daily_record_id', '=', 'daily_records.id')
            ->where('daily_records.record_date', '>=', $startDate)
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(10) // Top 10 products
            ->get();

        return [
            'labels' => $stats->pluck('name'),
            'data' => $stats->pluck('total_revenue'),
        ];
    }

    private function getDailyStats()
    {
        // Get data for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        
        $stats = DailyRecord::select('record_date', 'total_revenue')
            ->where('record_date', '>=', $startDate)
            ->orderBy('record_date')
            ->get();

        return [
            'labels' => $stats->pluck('record_date')->map(fn($date) => Carbon::parse($date)->format('M d')),
            'data' => $stats->pluck('total_revenue'),
        ];
    }

    private function getSummaryStats($groupBy)
    {
        // Normalize the groupBy parameter
        $groupBy = match($groupBy) {
            'weekly' => 'week',
            'monthly' => 'month',
            'yearly' => 'year',
            default => $groupBy
        };

        // Default to last 12 months if no date range is specified
        $endDate = Carbon::now();
        $startDate = match($groupBy) {
            'week' => $endDate->copy()->subWeeks(11), // Last 12 weeks
            'month' => $endDate->copy()->subMonths(11), // Last 12 months
            'year' => $endDate->copy()->subYears(4), // Last 5 years
        };

        $periodFormat = match($groupBy) {
            'week' => 'DATE_FORMAT(record_date, "%Y-%u")',
            'month' => 'DATE_FORMAT(record_date, "%Y-%m")',
            'year' => 'YEAR(record_date)',
        };

        $stats = DailyRecord::select(
            DB::raw("$periodFormat as period"),
            DB::raw('SUM(total_revenue) as total_revenue')
        )
        ->whereBetween('record_date', [$startDate, $endDate])
        ->groupBy('period')
        ->orderBy('period')
        ->get();

        $format = match($groupBy) {
            'week' => function($date) {
                $parts = explode('-', $date);
                $date = Carbon::now()->setISODate($parts[0], $parts[1]);
                return 'Week ' . $date->weekOfYear . ', ' . $date->year;
            },
            'month' => function($date) {
                return Carbon::createFromFormat('Y-m', $date)->format('M Y');
            },
            'year' => function($date) {
                return $date;
            },
        };

        return [
            'labels' => $stats->pluck('period')->map(fn($date) => is_callable($format) ? $format($date) : $date),
            'data' => $stats->pluck('total_revenue'),
        ];
    }

    public function generate($type, $summaryType = 'monthly', $format = 'pdf')
    {
        $data = match($type) {
            'products' => $this->getProductStats(),
            'daily' => $this->getDailyStats(),
            'summary' => $this->getSummaryStats($summaryType),
        };

        if ($format === 'pdf') {
            $pdf = PDF::loadView("reports.$type", [
                'data' => $data,
                'type' => $type,
                'summaryType' => $summaryType
            ]);
            return $pdf->download("$type-report.pdf");
        }

        return Excel::download(new ReportExport($data, $type), "$type-report.xlsx");
    }
}
