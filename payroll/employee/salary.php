<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/salary.css">
    <title>Salary</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Salary</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                    <span> > </span>
                    <h5><a href="./salary.php">Salary</a></h5>
                </div>
                <hr>
            </div>

            <div class="main-content">
                <div class="sub-content">
                    <div class="content">
                        <table>
                            <tr>
                                <th>Month</th>
                                <th>Cut Off 1</th>
                                <th>Cut Off 2</th>
                                <th>Total Wage</th>
                                <th>View</th>
                            </tr>
                            <tr>
                                <td>January</td>
                                <td>₱10,000</td>
                                <td>₱10,000</td>
                                <td>₱20,000</td>
                                <td>
                                    <button onclick="show()">Cut Off 1</button>
                                    <button>Cut Off 2</button>
                                </td>
                            </tr>
                            <tr>
                                <td>February</td>
                                <td>₱10,000</td>
                                <td>₱10,000</td>
                                <td>₱20,000</td>
                                <td>
                                    <button>Cut Off 1</button>
                                    <button>Cut Off 2</button>
                                </td>
                            </tr>
                            <tr>
                                <td>March</td>
                                <td>₱10,000</td>
                                <td>₱10,000</td>
                                <td>₱20,000</td>
                                <td>
                                    <button>Cut Off 1</button>
                                    <button>Cut Off 2</button>
                                </td>
                            </tr>
                            <tr>
                                <td>April</td>
                                <td>₱10,000</td>
                                <td>₱10,000</td>
                                <td>₱20,000</td>
                                <td>
                                    <button>Cut Off 1</button>
                                    <button>Cut Off 2</button>
                                </td>
                            </tr>
                            <tr>
                                <td>May</td>
                                <td>₱10,000</td>
                                <td>₱10,000</td>
                                <td>₱20,000</td>
                                <td>
                                    <button>Cut Off 1</button>
                                    <button>Cut Off 2</button>
                                    </th>
                            </tr>
                            <tr>
                                <td>June</td>
                                <td>₱10,000</td>
                                <td>₱10,000</td>
                                <td>₱20,000</td>
                                <td>
                                    <button>Cut Off 1</button>
                                    <button>Cut Off 2</button>
                                </td>
                            </tr>
                            <tr>
                                <td>July</td>
                                <td>₱10,000</td>
                                <td>₱10,000</td>
                                <td>₱20,000</td>
                                <td>
                                    <button>Cut Off 1</button>
                                    <button>Cut Off 2</button>
                                </td>
                            </tr>
                            <tr>
                                <td>August</td>
                                <td>₱10,000</td>
                                <td>₱10,000</td>
                                <td>₱20,000</td>
                                <td>
                                    <button>Cut Off 1</button>
                                    <button>Cut Off 2</button>
                                </td>
                            </tr>
                            <tr>
                                <td>September</td>
                                <td>₱10,000</td>
                                <td>₱10,000</td>
                                <td>₱20,000</td>
                                <td>
                                    <button>Cut Off 1</button>
                                    <button>Cut Off 2</button>
                                </td>
                            </tr>
                            <tr>
                                <td>October</td>
                                <td>₱10,000</td>
                                <td>₱10,000</td>
                                <td>₱20,000</td>
                                <td>
                                    <button>Cut Off 1</button>
                                    <button>Cut Off 2</button>
                                </td>
                            </tr>
                            <tr>
                                <td>November</td>
                                <td>₱10,000</td>
                                <td>₱10,000</td>
                                <td>₱20,000</td>
                                <td>
                                    <button>Cut Off 1</button>
                                    <button>Cut Off 2</button>
                                    </th>
                            </tr>
                            <tr>
                                <td>December</td>
                                <td>₱10,000</td>
                                <td>₱10,000</td>
                                <td>₱20,000</td>
                                <td>
                                    <button>Cut Off 1</button>
                                    <button>Cut Off 2</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>₱120,000</td>
                                <td>₱120,000</td>
                                <td>₱240,000</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="overlay" id="overlay">
                        <div class="info-container">
                            <h2>More Info</h2>
                            <hr>
                            <div class="con">
                                <div class="info-grid">
                                    <img src="" alt="">
                                    <h1>Michael Tan</h1>
                                </div>
                                <div class="info-grid">
                                    <p>Cut Off 1 - 2024</p>
                                </div>
                                <div class="info-grid">
                                    <p><strong>Avg Daily Hours:</strong>       4hrs 5m</p>
                                </div>
                                <div class="info-grid">
                                    <p><strong>Pay Type:</strong>                 Hourly</p>
                                </div>
                                <div class="info-grid">
                                    <p><strong>Total Regular Hours Worked:</strong>                       1820hrs</p>
                                </div>
                                <div class="info-grid">
                                    <p><strong>Rate:</strong>                       ₱67/hr</p>
                                </div>
                                <div class="info-grid">
                                    <p><strong>Total Overtime Hours Worked:</strong>                  387hrs</p>
                                </div>
                                <div class="info-grid">
                                    <p><strong>Total Wage:</strong>                           ₱13,244</p>
                                </div>
                                <div class="info-grid">
                                    <table>
                                        <tr>
                                            <th>Month</th>
                                            <th>Regular Hours</th>
                                            <th>Overtime Hours</th>
                                            <th>Total Worked Hours</th>
                                            <th>Total Wage</th>
                                        </tr>
                                        <tr>
                                            <td>January</td>
                                            <td>159h 33m</td>
                                            <td>9h 35m</td>
                                            <td>168h 58m</td>
                                            <td>₱10,080</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="info-grid">
                                    <button class="btn">Pay Slip</button>
                                    <button class="btn">More Info</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
    <script src="./javascript/salary.js"></script>
</body>

</html>