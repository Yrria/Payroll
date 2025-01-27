<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/report_leave.css">
    <title>Report - Leave</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Report</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                    <span> > </span>
                    <h5><a href="./report_leave.php">Report-Leave </a></h5>
                </div>
                <hr>
            </div>
            <div class="main-content">
                <div class="sub-content">
                    <div class="selection_div">
                        <p style="margin: 0;font-weight: 500;">Leave Report</p>
                        <div style="display: flex;align-items: center;width:60%;justify-content:right;margin-right:-4%;">
                            <div class="search-bar">
                                <button class="search-btn">Search</button>
                                <input type="text" placeholder="Search employee..." />
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="controls">
                            <label for="show-entries">Show
                                <select id="show-entries">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                entries
                            </label>
                            <div class="date-range">
                                <label for="from-date">From:
                                    <input type="date" id="from-date" />
                                </label>
                                <label for="to-date">To:
                                    <input type="date" id="to-date" />
                                </label>
                            </div>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Subject</th>
                                    <th>Name</th>
                                    <th>Leave Type</th>
                                    <th>No. Of Leave</th>
                                    <th>Remaining Leave</th>
                                    <th>Total Leaves</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>001</td>
                                    <td>Manager</td>
                                    <td>Willy Wonka</td>
                                    <td>Emergency Leave</td>
                                    <td>08</td>
                                    <td>20</td>
                                    <td>20</td>
                                </tr>
                                <tr>
                                    <td>002</td>
                                    <td>Crew</td>
                                    <td>Ken Flerlage</td>
                                    <td>Annual Leave</td>
                                    <td>08</td>
                                    <td>20</td>
                                    <td>20</td>
                                </tr>
                                <tr>
                                    <td>003</td>
                                    <td>Crew</td>
                                    <td>George Orwell</td>
                                    <td>Bereavement Leave</td>
                                    <td>08</td>
                                    <td>20</td>
                                    <td>20</td>
                                </tr>
                                <tr>
                                    <td>004</td>
                                    <td>Crew</td>
                                    <td>Natalie Maines</td>
                                    <td>Maternity Leave</td>
                                    <td>08</td>
                                    <td>20</td>
                                    <td>20</td>
                                </tr>
                                <tr>
                                    <td>005</td>
                                    <td>Crew</td>
                                    <td>Hellen Mirren</td>
                                    <td>Vacation Leave</td>
                                    <td>20</td>
                                    <td>20</td>
                                    <td>20</td>
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
                <!-- <p>This is a simple responsive page with a header and a side navigation bar that can be minimized.</p>
                    <br>
                    <p>This is a simple responsive page with a header and a side navigation bar that can be minimized.</p>
                    <br>
                    <p>This is a simple responsive page with a header and a side navigation bar that can be minimized.</p>
                    <br>
                    <p>This is a simple responsive page with a header and a side navigation bar that can be minimized.</p>
                    <br>
                    <p>This is a simple responsive page with a header and a side navigation bar that can be minimized.</p> -->
            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
</body>

</html>