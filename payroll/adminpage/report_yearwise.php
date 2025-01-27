<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/report_yearwise.css">
    <title>Report - Year Wise</title>
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
                    <h5><a href="./report_yearwise.php">Report-Yearwise </a></h5>
                </div>
                <hr>
            </div>
            <div class="main-content">
                <div class="sub-content">
                    <div class="selection_div">
                        <p class="label">Year Wise Report</p>
                        <div class="search-bar">
                            <button class="search-btn">Search</button>
                            <input type="text" placeholder="Search employee..." />
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Overtime Hours</th>
                                    <th>Worked Hours</th>
                                    <th>Total Deductions</th>
                                    <th>Total Wage</th>
                                    <th>View Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Emp 000</td>
                                    <td>Willy Wonka</td>
                                    <td>160h</td>
                                    <td>1820h</td>
                                    <td>₱500</td>
                                    <td>₱13,242</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn" onclick="openModal()">View Info</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Emp 000</td>
                                    <td>Willy Wonka</td>
                                    <td>160h</td>
                                    <td>1820h</td>
                                    <td>₱500</td>
                                    <td>₱13,242</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn" onclick="openModal()">View Info</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Emp 000</td>
                                    <td>Willy Wonka</td>
                                    <td>160h</td>
                                    <td>1820h</td>
                                    <td>₱500</td>
                                    <td>₱13,242</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn" onclick="openModal()">View Info</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Emp 000</td>
                                    <td>Willy Wonka</td>
                                    <td>160h</td>
                                    <td>1820h</td>
                                    <td>₱500</td>
                                    <td>₱13,242</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn" onclick="openModal()">View Info</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Emp 000</td>
                                    <td>Willy Wonka</td>
                                    <td>160h</td>
                                    <td>1820h</td>
                                    <td>₱500</td>
                                    <td>₱13,242</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn" onclick="openModal()">View Info</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Emp 000</td>
                                    <td>Willy Wonka</td>
                                    <td>160h</td>
                                    <td>1820h</td>
                                    <td>₱500</td>
                                    <td>₱13,242</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn" onclick="openModal()">View Info</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <!-- Pagination -->
                        <div class="pagination">
                            <p>Showing 1 / 100 Results</p>
                            <div>
                                <button>Prev</button>
                                <input type="text" class="perpage" value="1" readonly />
                                <button>Next</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="infoModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="title">ANNUAL SUMMARY REPORT</h2>
                            <h2 class="year">2024</h2>
                        </div>
                        <span class="close-btn" onclick="closeModal()">&times;</span>
                        <hr>
                        <!-- Employee Information -->
                        <div class="employee-info">
                            <span>Employee Name: Willy Wonka</span>
                        </div>
                        <div class="employee-stats">
                            <div class="stat">
                                <span>Total Worked Hours: 1820h</span>
                            </div>
                            <div class="stat">
                                <span>Total Deductions: ₱500</span>
                            </div>
                            <div class="stat">
                                <span>Total Overtime Hours: 160h</span>
                            </div>
                            <div class="stat">
                                <span>Total Wage: ₱13,242</span>
                            </div>
                        </div>

                        <!-- Table -->
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Worked Hours</th>
                                    <th>Overtime Hours</th>
                                    <th>Deductions</th>
                                    <th>Total Wage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>January</td>
                                    <td>150</td>
                                    <td>20</td>
                                    <td>₱50</td>
                                    <td>₱1,200</td>
                                </tr>
                                <tr>
                                    <td>February</td>
                                    <td>140</td>
                                    <td>15</td>
                                    <td>₱40</td>
                                    <td>₱1,100</td>
                                </tr>
                                <tr>
                                    <td>March</td>
                                    <td>160</td>
                                    <td>25</td>
                                    <td>₱60</td>
                                    <td>₱1,400</td>
                                </tr>
                                <tr>
                                    <td>April</td>
                                    <td>155</td>
                                    <td>30</td>
                                    <td>₱55</td>
                                    <td>₱1,300</td>
                                </tr>
                                <tr>
                                    <td>May</td>
                                    <td>170</td>
                                    <td>35</td>
                                    <td>₱65</td>
                                    <td>₱1,500</td>
                                </tr>
                                <tr>
                                    <td>June</td>
                                    <td>175</td>
                                    <td>40</td>
                                    <td>₱70</td>
                                    <td>₱1,600</td>
                                </tr>
                                <tr>
                                    <td>July</td>
                                    <td>150</td>
                                    <td>20</td>
                                    <td>₱50</td>
                                    <td>₱1,200</td>
                                </tr>
                                <tr>
                                    <td>August</td>
                                    <td>140</td>
                                    <td>15</td>
                                    <td>₱40</td>
                                    <td>₱1,100</td>
                                </tr>
                                <tr>
                                    <td>September</td>
                                    <td>160</td>
                                    <td>25</td>
                                    <td>₱60</td>
                                    <td>₱1,400</td>
                                </tr>
                                <tr>
                                    <td>October</td>
                                    <td>155</td>
                                    <td>30</td>
                                    <td>₱55</td>
                                    <td>₱1,300</td>
                                </tr>
                                <tr>
                                    <td>November</td>
                                    <td>170</td>
                                    <td>35</td>
                                    <td>₱65</td>
                                    <td>₱1,500</td>
                                </tr>
                                <tr>
                                    <td>December</td>
                                    <td>175</td>
                                    <td>40</td>
                                    <td>₱70</td>
                                    <td>₱1,600</td>
                                </tr>
                                <!-- Total Row -->
                                <tr class="total-row">
                                    <td>Total</td>
                                    <td>1,030</td>
                                    <td>165</td>
                                    <td>₱375</td>
                                    <td>₱8,100</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button class="back-btn" onclick="closeModal()">Back</button>
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
    <script>
        // Modal functionality
        function openModal() {
            document.getElementById('infoModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('infoModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('infoModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>

</html>