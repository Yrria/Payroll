<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earnings and Deductions Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .table-wrapper {
            width: 48%;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background-color: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        td:last-child {
            text-align: right;
        }
        tr:nth-child(even) td {
            background-color: #fff;
        }
        tr:nth-child(odd) td {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Earnings Table -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Earnings</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Basic Salary</td>
                        <td>$3000</td>
                    </tr>
                    <tr>
                        <td>House Rent Allowance (H.R.A.)</td>
                        <td>$1000</td>
                    </tr>
                    <tr>
                        <td>Conveyance</td>
                        <td>$200</td>
                    </tr>
                    <tr>
                        <td>Other Allowance</td>
                        <td>$100</td>
                    </tr>
                    <tr>
                        <td><b>Total Earnings</b></td>
                        <td><b>$4300</b></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Deductions Table -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Deductions</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tax Deducted at Source (T.D.S.)</td>
                        <td>$200</td>
                    </tr>
                    <tr>
                        <td>Provident Fund</td>
                        <td>$300</td>
                    </tr>
                    <tr>
                        <td>ESI</td>
                        <td>$150</td>
                    </tr>
                    <tr>
                        <td>Loan</td>
                        <td>$50</td>
                    </tr>
                    <tr>
                        <td><b>Total Deductions</b></td>
                        <td><b>$700</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
