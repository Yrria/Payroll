<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/view_slips.css">
    <title>Payroll</title>
    <style>


    </style>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Payroll</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                    <span> > </span>
                    <h5><a href="./payslips.php">Payslip List </a></h5>
                    <span> > </span>
                    <h5>Payslip</h5>
                </div>
                <div class="download-container">
                        <a href="path/to/download" class="download-btn">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                <hr>
            </div>

            <div class="selection_div">
                <table>
                    <tr>
                        <td> 
                            <div style="display:flex; align-items:center;">
                                <img src="../assets/logo.png" alt="logo" style="height:40px; margin-right:2%;"><img src="../assets/title.png" alt="ExPense" style="height:15px;">
                            </div>
                        </td>
                        <td style="text-align:right;">Payslip No: 023123</td>
                    </tr>
                    <tr>
                        <td>First Cutoff</td>
                        <td style="text-align:right;">Salary Month: October 2024</td>
                    </tr>
                </table>
                <hr style="opacity:0.5;">
                <table class="comp_infos">
                    <thead>
                        <tr>
                            <td style="font-size: 13px;font-weight:700;">From</td>
                            <td style="font-size: 13px;font-weight:700;">To</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-size: 15px;font-weight:600;">Expense</td>
                            <td style="font-size: 15px;font-weight:600;">Juan Dela Cruz</td>
                        </tr>
                        <tr>
                            <td><span>CVSu 9632</span></td>
                            <td><span>Service Crew</span></td>
                        </tr>
                        <tr>
                            <td><span >Email:</span>expensep@email.com</td>
                            <td><span>Email:</span> juandc@email.com</td>
                        </tr>
                        <tr>
                            <td><span>Phone:</span> +1 936 281 832</td>
                            <td><span>Phone:</span> 0937262822</td>
                        </tr>
                    </tbody>
                </table>    
                <hr style="opacity:0.5;">
                <p>Payslip of the Month October</p>
                <div class="earn_deduc_div">
                    <div class="earnings-deductions-table">
                        <table class="earnings">
                            <thead>
                                <tr>
                                    <th>Earnings</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Basic Pay</td>
                                    <td>₱3000</td>
                                </tr>
                                <tr>
                                    <td>Holiday Pay</td>
                                    <td>₱1000</td>
                                </tr>
                                <tr>
                                    <td>Ot Pay</td>
                                    <td>$200</td>
                                </tr>
                                <tr>
                                    <td>Total Earnings</td>
                                    <td>₱4000</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="deductions">
                            <thead>
                                <tr>
                                    <th>Deductions</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>PAGIBIG</td>
                                    <td>₱743</td>
                                </tr>
                                <tr>
                                    <td>SSS</td>
                                    <td>₱573</td>
                                </tr>
                                <tr>
                                    <td>PhilHealth</td>
                                    <td>₱673</td>
                                </tr>
                                <tr>
                                    <td>Others</td>
                                    <td>₱100</td>
                                </tr>
                                <tr>
                                    <td>Total Deductions</td>
                                    <td>$700</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
    <script src="./javascript/payroll.js"></script>
</body>

</html>