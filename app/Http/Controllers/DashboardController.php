<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Product;
use App\Models\Category;
use App\Models\DailyRecord;
use App\Models\DailyRecordProduct;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard/Index', [
            'stats' => $this->getStats(),
        ]);
    }

    // stats: [
    //     {
    //       title: "Total Products",
    //       numValue: "156",
    //       icon: ShoppingCartIcon,
    //       change: "+12% from last month",
    //     },
    //     {
    //       title: "Today's Sales",
    //       numValue: "$45,231",
    //       icon: CurrencyDollarIcon,
    //       change: "+8% from last month",
    //     },
    //     {
    //       title: "Monthly Revenue",
    //       numValue: "89",
    //       icon: ChartBarIcon,
    //       change: "+3% from last month",
    //     },
    //     {
    //       title: "Active Categories",
    //       numValue: "12",
    //       icon: TagIcon,
    //       change: "+3% from last month",
    //     }
    //   ],

    private function getStats()
    {
        $totalProducts = $this->getTotalProducts();
        $changeProductsFromLastMonth = $this->changeProductsFromLastMonth();
        $todaySales = $this->getTodaySales();
        $changeTodaySalesFromLastMonth = $this->changeTodaySalesFromLastMonth();
        $monthlyRevenue = $this->getMonthlyRevenue();
        $changeMonthlyRevenueFromLastMonth = $this->changeMonthlyRevenueFromLastMonth();
        $activeCategories = $this->getActiveCategories();
        $changeActiveCategoriesFromLastMonth = $this->changeActiveCategoriesFromLastMonth();

        return [
            'totalProducts' => $totalProducts,
            'changeProductsFromLastMonth' => $changeProductsFromLastMonth,
            'todaySales' => $todaySales,
            'changeTodaySalesFromLastMonth' => $changeTodaySalesFromLastMonth,
            'monthlyRevenue' => $monthlyRevenue,
            'changeMonthlyRevenueFromLastMonth' => $changeMonthlyRevenueFromLastMonth,
            'activeCategories' => $activeCategories,
            'changeActiveCategoriesFromLastMonth' => $changeActiveCategoriesFromLastMonth,
        ];
    }

    private function getTotalProducts()
    {
        return Product::count();
    }

    private function changeProductsFromLastMonth()
    {
        $lastMonth = Carbon::now()->subMonth();
        $currentMonth = Carbon::now();
        $lastMonthProducts = Product::whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])->count();
        $currentMonthProducts = Product::whereBetween('created_at', [$currentMonth->startOfMonth(), $currentMonth->endOfMonth()])->count();
        $change = $currentMonthProducts - $lastMonthProducts;
        $percentage = $lastMonthProducts > 0 ? round(($change / $lastMonthProducts) * 100, 1) : 0;
        return $change . ' (' . $percentage . '%)';
    }

    private function getTodaySales()
    {
        $today = Carbon::today();
        return DailyRecord::whereDate('record_date', $today)->sum('total_revenue');
    }

    private function changeTodaySalesFromLastMonth()
    {
        $yesterday = Carbon::yesterday();
        $today = Carbon::today();
        
        $yesterdaySales = DailyRecord::whereDate('record_date', $yesterday)->sum('total_revenue');
        $todaySales = DailyRecord::whereDate('record_date', $today)->sum('total_revenue');
        
        $change = $todaySales - $yesterdaySales;
        $percentage = $yesterdaySales > 0 ? round(($change / $yesterdaySales) * 100, 1) : 0;
        
        return number_format($change, 2) . ' (' . $percentage . '%)';
    }

    private function getMonthlyRevenue()
    {
        $currentMonth = Carbon::now();
        return DailyRecord::whereMonth('record_date', $currentMonth->month)
            ->whereYear('record_date', $currentMonth->year)
            ->sum('total_revenue');
    }

    private function changeMonthlyRevenueFromLastMonth()
    {
        $lastMonth = Carbon::now()->subMonth();
        $currentMonth = Carbon::now();

        $lastMonthRevenue = DailyRecord::whereMonth('record_date', $lastMonth->month)
            ->whereYear('record_date', $lastMonth->year)
            ->sum('total_revenue');

        $currentMonthRevenue = DailyRecord::whereMonth('record_date', $currentMonth->month)
            ->whereYear('record_date', $currentMonth->year)
            ->sum('total_revenue');

        $change = $currentMonthRevenue - $lastMonthRevenue;
        $percentage = $lastMonthRevenue > 0 ? round(($change / $lastMonthRevenue) * 100, 1) : 0;

        return number_format($change, 2) . ' (' . $percentage . '%)';
    }

    private function getActiveCategories()
    {
        return Category::count();
    }

    private function changeActiveCategoriesFromLastMonth()
    {
        $lastMonth = Carbon::now()->subMonth();
        $currentMonth = Carbon::now();
        
        $lastMonthCategories = Category::whereBetween('created_at', [
            $lastMonth->startOfMonth(),
            $lastMonth->endOfMonth()
        ])->count();
        
        $currentMonthCategories = Category::whereBetween('created_at', [
            $currentMonth->startOfMonth(),
            $currentMonth->endOfMonth()
        ])->count();
        
        $change = $currentMonthCategories - $lastMonthCategories;
        $percentage = $lastMonthCategories > 0 ? round(($change / $lastMonthCategories) * 100, 1) : 0;
        
        return $change . ' (' . $percentage . '%)';
    }
}
