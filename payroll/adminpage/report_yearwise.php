<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';
?>

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
                    <h5><a href="./dashboard.php">Dashboard</a></h5>
                    <span> > </span>
                    <h5><a href="./report_yearwise.php">Report-Yearwise</a></h5>
                </div>
                <hr>
            </div>
            <div class="main-content">
                <div class="sub-content">
                    <div class="selection_div">
                        <p class="label">Year Wise Report</p>
                        <div class="search-bar">
                            <form method="GET" action="">
                                <input type="text" id="searchInput" placeholder="Search employee..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" />
                            </form>
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
                            <tbody id="showdata">
                                <?php
                                // Search logic
                                $search_query = isset($_GET['query']) ? $_GET['query'] : '';
                                $search_query_escaped = $conn->real_escape_string($search_query);

                                $sql = "
    SELECT e.emp_id, e.lastname, e.firstname, e.middlename, i.rate,
        COALESCE(SUM(a.hours_overtime), 0) AS total_overtime,
        COALESCE(SUM(a.hours_present), 0) AS total_worked,
        COALESCE(SUM(a.present_days), 0) AS total_present_days,
        COALESCE(SUM(d.pagibig_deduction + d.philhealth_deduction + d.sss_deduction + d.other_deduction), 0) AS total_deductions
    FROM tbl_emp_acc e
    LEFT JOIN tbl_emp_info i ON e.emp_id = i.emp_id
    LEFT JOIN tbl_attendance a ON e.emp_id = a.emp_id
    LEFT JOIN tbl_deduction d ON e.emp_id = d.emp_id
";

                                if (!empty($search_query_escaped)) {
                                    $sql .= "
        WHERE e.emp_id LIKE '%$search_query_escaped%'
        OR e.firstname LIKE '%$search_query_escaped%'
        OR e.lastname LIKE '%$search_query_escaped%'
        OR e.middlename LIKE '%$search_query_escaped%'
    ";
                                }

                                $sql .= " GROUP BY e.emp_id";

                                $result = $conn->query($sql);

                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $empId = $row['emp_id'];
                                        $empName = $row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['middlename'];
                                        $overtimeHours = $row['total_overtime'] . 'h';
                                        $workedHours = $row['total_worked'] . 'h';
                                        $totalDeductions = '₱' . number_format($row['total_deductions'], 2);
                                        $totalWage = '₱' . number_format($row['rate'] * $row['total_present_days'], 2);

                                        echo "<tr>
            <td>{$empId}</td>
            <td>{$empName}</td>
            <td>{$overtimeHours}</td>
            <td>{$workedHours}</td>
            <td>{$totalDeductions}</td>
            <td>{$totalWage}</td>
            <td class='td-text'>
                <div class='action-buttons'>
                    <button class='view-btn' onclick='openModal(\"{$empId}\")'>View Info</button>
                </div>
            </td>
        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' style='text-align:center;'>No data found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <br>
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

                <!-- MODAL -->
                <div id="infoModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="title">ANNUAL SUMMARY REPORT</h2>
                            <h2 class="year">2024</h2>
                        </div>
                        <span class="close-btn" onclick="closeModal()">&times;</span>
                        <hr>
                        <div class="employee-info">
                            <span>Employee Name: </span> <!-- leave empty to fill dynamically -->
                        </div>
                        <div class="employee-stats">
                            <div class="stat"><span class="worked-hours">Total Worked Hours: 0h</span></div>
                            <div class="stat"><span class="total-deductions">Total Deductions: ₱0</span></div>
                            <div class="stat"><span class="overtime-hours">Total Overtime Hours: 0h</span></div>
                            <div class="stat"><span class="total-wage">Total Wage: ₱0</span></div>
                        </div>
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
                            <tbody id="showdata">
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

            </div> <!-- .main-content -->
        </div> <!-- #mainContent -->
    </div> <!-- .container -->

    <script>
        function openModal(empId) {
            fetch('fetch_employee_info.php?emp_id=' + encodeURIComponent(empId))
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector('.employee-info span').textContent = `Employee Name: ${data.fullname}`;
                        document.querySelector('.worked-hours').textContent = `Total Worked Hours: ${data.total_worked_hours}h`;
                        document.querySelector('.overtime-hours').textContent = `Total Overtime Hours: ${data.total_overtime_hours}h`;
                        document.querySelector('.total-deductions').textContent = `Total Deductions: ₱${Number(data.total_deductions).toFixed(2)}`;
                        document.querySelector('.total-wage').textContent = `Total Wage: ₱${Number(data.total_wage).toFixed(2)}`;
                        document.getElementById('infoModal').style.display = 'block';
                    } else {
                        alert(data.message || 'Employee info not found.');
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    alert('Failed to fetch employee info.');
                });
                
        }

        function closeModal() {
            document.getElementById('infoModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('infoModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };

        document.getElementById('searchInput').addEventListener('keyup', function() {
            const query = this.value;

            fetch('search_yearwise.php?query=' + encodeURIComponent(query))
                .then(response => response.text())
                .then(data => {
                    document.getElementById('showdata').innerHTML = data;
                });
        });
    </script>
</body>

</html>