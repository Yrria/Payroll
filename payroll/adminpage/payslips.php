<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/payslips.css">
    <title>Payroll</title>
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
                    <h5>Payslip List</h5>
                </div>
                <hr>
            </div>

            <div class="main-content">
                <div class="sub-content">
                    <div class="selection_div">
                        <p style="margin: 0;font-weight: 500;">Employee Salary List</p>
                        <div style="display: flex;align-items: center;width:60%;justify-content:right;margin-right:-4%;">
                            <div class="dropdown month-dropdown" style="margin-left: 20%;">
                                <div class="dropdown-wrapper">
                                    <input type="text" class="dropdown-input" style="width:75%;" readonly placeholder="Select Month" />
                                    <div class="dropdown-indicator">&#9662;</div>
                                    <div class="dropdown-content">
                                        <div class="dropdown-item" data-value="01" style="font-size: 14px;">January</div>
                                        <div class="dropdown-item" data-value="02" style="font-size: 14px;">February</div>
                                        <div class="dropdown-item" data-value="03" style="font-size: 14px;">March</div>
                                        <div class="dropdown-item" data-value="04" style="font-size: 14px;">April</div>
                                        <div class="dropdown-item" data-value="05" style="font-size: 14px;">May</div>
                                        <div class="dropdown-item" data-value="06" style="font-size: 14px;">June</div>
                                        <div class="dropdown-item" data-value="07" style="font-size: 14px;">July</div>
                                        <div class="dropdown-item" data-value="08" style="font-size: 14px;">August</div>
                                        <div class="dropdown-item" data-value="09" style="font-size: 14px;">September</div>
                                        <div class="dropdown-item" data-value="10" style="font-size: 14px;">October</div>
                                        <div class="dropdown-item" data-value="11" style="font-size: 14px;">November</div>
                                        <div class="dropdown-item" data-value="12" style="font-size: 14px;">December</div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown year-dropdown" style="width:25%;">
                                <div class="dropdown-wrapper">
                                    <input type="text" class="dropdown-input" style="width:75%;" readonly placeholder="Select Year" />
                                    <div class="dropdown-indicator" style="right:47px;">&#9662;</div>
                                    <div class="dropdown-content">
                                        <div class="dropdown-item" data-value="2024" style="font-size: 14px;">2024</div>
                                        <div class="dropdown-item" data-value="2023" style="font-size: 14px;">2023</div>
                                        <div class="dropdown-item" data-value="2022" style="font-size: 14px;">2022</div>
                                        <div class="dropdown-item" data-value="2021" style="font-size: 14px;">2021</div>
                                        <div class="dropdown-item" data-value="2020" style="font-size: 14px;">2020</div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown cutoff-dropdown">
                                <div class="dropdown-wrapper">
                                    <input type="text" class="dropdown-input" readonly placeholder="Select Cutoff" />
                                    <div class="dropdown-indicator">&#9662;</div>
                                    <div class="dropdown-content">
                                        <div class="dropdown-item" style="font-size: 14px;">First Cutoff</div>
                                        <div class="dropdown-item" style="font-size: 14px;">Second Cutoff</div>
                                        <div class="dropdown-item" style="font-size: 14px;">Full Month</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="payroll_contents">
                        <!-- Title and search-bar -->
                        <div class="search">
                            <div class="search-bar">
                                <input type="text" class="search_emp_input" placeholder="Search employee..." />
                                <button class="search-btn">Search</button>
                            </div>
                        </div>

                        <!-- Leave Table -->
                        <table>
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Shift</th>
                                    <th>Total Wage</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>001</td>
                                    <td>Marc Rodney Toledo</td>
                                    <td>Service Crew</td>
                                    <td>Night</td>
                                    <td>₱10,000</td>
                                    <td class="td-text">Paid</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                           <a href="./view_slip.php"><button class="view-btn">View Slip</button></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>001</td>
                                    <td>Marc Rodney Toledo</td>
                                    <td>Service Crew</td>
                                    <td>Night</td>
                                    <td>₱10,000</td>
                                    <td class="td-text">Paid</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn">View Slip</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>001</td>
                                    <td>Marc Rodney Toledo</td>
                                    <td>Service Crew</td>
                                    <td>Night</td>
                                    <td>₱10,000</td>
                                    <td class="td-text">Paid</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn">View Slip</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>001</td>
                                    <td>Marc Rodney Toledo</td>
                                    <td>Service Crew</td>
                                    <td>Night</td>
                                    <td>₱10,000</td>
                                    <td class="td-text">Paid</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn">View Slip</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>001</td>
                                    <td>Marc Rodney Toledo</td>
                                    <td>Service Crew</td>
                                    <td>Night</td>
                                    <td>₱10,000</td>
                                    <td class="td-text">Paid</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn">View Slip</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <!-- Pagination -->
                        <div class="pagination">
                            <p>Showing 1 / 100 Results</p>
                            <div class="pagination">
                                <button>Prev</button>
                                <input type="text" class="perpage" value="1" readonly />
                                <button>Next</button>
                            </div>
                        </div>
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