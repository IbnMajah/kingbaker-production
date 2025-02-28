<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Product Revenue Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4F46E5;
            color: white;
        }

        .total {
            font-weight: bold;
        }

        .date {
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Product Revenue Report</h1>
        <p class="date">Generated on {{ now()->format('F d, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th style="text-align: right">Revenue (GMD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['labels'] as $index => $product)
            <tr>
                <td>{{ $product }}</td>
                <td style="text-align: right">{{ number_format($data['data'][$index], 2) }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td>Total</td>
                <td style="text-align: right">{{ number_format($data['data']->sum(), 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>