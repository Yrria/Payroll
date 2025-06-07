<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';


$records_per_page = 1; // Number of records to display per page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page number, default to 1

// Calculate the limit clause for SQL query
$start_from = ($current_page - 1) * $records_per_page;

// Initialize variables
$sql = "SELECT * FROM tbl_leave WHERE status = 'Pending'";

// Check if search query is provided

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_btn'])) {
    $leave_id = $conn->real_escape_string($_POST['approve_leave_id']);

    // Update leave status to Approved
    $update_sql = "UPDATE tbl_leave SET status = 'Approved' WHERE leave_id = '$leave_id'";
    if ($conn->query($update_sql)) {
        header("Location: leave_approved.php");
        exit();
    } else {
        echo "<script>alert('Failed to approve leave request.');</script>";
    }
}

if (isset($_GET['query']) && !empty($_GET['query'])) {
    $search_query = $_GET['query'];
    // Modify SQL query to include search filter
    $sql .= " AND (
        emp_id LIKE '%$search_query%' 
        OR leave_id LIKE '%$search_query%' 
        OR subject LIKE '%$search_query%' 
        OR date_applied LIKE '%$search_query%' 
        OR start_date LIKE '%$search_query%' 
        OR end_date LIKE '%$search_query%' 
        OR status LIKE '%$search_query%' 
        OR leave_type LIKE '%$search_query%' 
        OR message LIKE '%$search_query%' 
        OR rejection_reason LIKE '%$search_query%' 
        OR remaining_leave LIKE '%$search_query%' 
        OR no_of_leave LIKE '%$search_query%' 
        OR total_leaves LIKE '%$search_query%' 
        OR emp_id IN (
            SELECT emp_id FROM tbl_emp_acc
            WHERE firstname LIKE '%$search_query%' 
               OR middlename LIKE '%$search_query%' 
               OR lastname LIKE '%$search_query%'
        )
    )";
}

$sql .= " LIMIT $start_from, $records_per_page";

$result = $conn->query($sql);

// Count total number of records
$total_records_query = "SELECT COUNT(*) FROM tbl_leave WHERE status = 'Pending'";
if (!empty($search_query)) {
    $total_records_query .= " AND ( ... same search conditions ... )";
}

if (isset($_GET['query']) && !empty($_GET['query'])) {
    $total_records_query .= " AND (
        emp_id LIKE '%$search_query%' 
        OR leave_id LIKE '%$search_query%' 
        OR subject LIKE '%$search_query%' 
        OR date_applied LIKE '%$search_query%' 
        OR start_date LIKE '%$search_query%' 
        OR end_date LIKE '%$search_query%' 
        OR status LIKE '%$search_query%' 
        OR leave_type LIKE '%$search_query%' 
        OR message LIKE '%$search_query%' 
        OR rejection_reason LIKE '%$search_query%' 
        OR remaining_leave LIKE '%$search_query%' 
        OR no_of_leave LIKE '%$search_query%' 
        OR total_leaves LIKE '%$search_query%' 
        OR emp_id IN (
            SELECT emp_id FROM tbl_emp_acc
            WHERE firstname LIKE '%$search_query%' 
               OR middlename LIKE '%$search_query%' 
               OR lastname LIKE '%$search_query%'
        )
    )";
}

$total_records_result = $conn->query($total_records_query);
$total_records_row = $total_records_result->fetch_row();
$total_records = $total_records_row[0];

$total_pages = ceil($total_records / $records_per_page);

// preserve query in pagination links
$qp = !empty($_GET['query']) ? '&query=' . urlencode($_GET['query']) : '';



// Count active employees
$activeEmpQuery = "SELECT COUNT(*) AS total_active FROM tbl_emp_acc WHERE status = 'Active'";
$activeEmpResult = $conn->query($activeEmpQuery);
$activeEmpCount = 0;
if ($activeEmpResult && $row = $activeEmpResult->fetch_assoc()) {
    $activeEmpCount = $row['total_active'];
}

// Count pending leaves
$pendingLeavesQuery = "SELECT COUNT(*) AS total_pending FROM tbl_leave WHERE status = 'Pending'";
$pendingLeavesResult = $conn->query($pendingLeavesQuery);
$pendingLeaveCount = 0;
if ($pendingLeavesResult && $row = $pendingLeavesResult->fetch_assoc()) {
    $pendingLeaveCount = $row['total_pending'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <script src="https://kit.fontawesome.com/3b07bc6295.js" crossorigin="anonymous"></script>
    <title>Dashboard</title>
    <style>
        /* Add the CSS styles from your code */


        .content {
            display: flex;
            flex-direction: column;
        }

        /* Responsive stacking for smaller screens */
        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background-color: #ffff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: -200px;
            height: 100px;
            width: 200px;
            margin-right: 100px;
        }

        /* Payroll Report Styles */
        .payroll-report {
            background-color: #ffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
            margin-top: -210px;
            width: 720px;
        }

        /* Salary Distribution Styles */
        .salary-distribution {
            background-color: #ffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
            margin-top: 20px;
            width: 720px;
        }

        /* Upcoming and Holiday Event Styles */
        .Event{
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 16px;
            max-width: 400px;
            margin: 20px auto;
        }

        .Event-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .Event-header h3 {
            margin: 0;
            color: #333333;
            font-size: 18px;
            font-weight: bold;
        }

        .view-all {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            margin-top: 70px;
        }

        .event-item {
            border-top: 1px solid #e0e0e0;
            padding: 12px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .event-item:first-child {
            border-top: none;
        }

        .event-details {
            display: flex;
            flex-direction: column;
        }

        .event-title {
            color: #00a7e1;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .event-type {
            color: #666666;
            font-size: 12px;
        }

        .event-day {
            color: #333333;
            font-size: 12px;
            margin-top: 4px;
        }

        .event-date {
            background: #e0e0e0;
            color: #333333;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            flex-shrink: 0;
        }

        .event-date span {
            display: block;
            font-size: 10px;
            color: #888888;
        }








        /* Calendar Styles */
        .calendar-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 12px;
            color: #333;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }
        .calendar-box {
            grid-column: 3;
            grid-row: 2;
            border-radius: 10px;
            background-color: #ffffff;
            padding: 30px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
            flex: 1;
            min-width: 280px;
            max-width: 400px;
            margin-top: 80px;
            margin-left: 90px;
        }
        
        .calendar-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        
        .calendar-controls select,
        .calendar-controls button {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: white;
        }
        
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            text-align: center;
            font-size: 14px;
        }
        
        .calendar div {
            padding: 10px;
            border-radius: 50%;
            transition: background-color 0.3s;
        }
        
        .calendar div.today {
            background-color: #e0f7fa;
            color: #000;
            font-weight: bold;
        }


        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6); 
        }

        .modal-content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 500px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-radius: 10px;
            margin-top: 150px;
            margin-left: 450px;
        }

        #addNoteButton {
            align-self: center;
            border: none; 
            border-radius: 5px; 
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 900px;
        }

        #addNoteButton:hover {
            background-color: #45a049;
        }

        #selectedDate {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        #noteInput {
            margin-bottom: 10px;
            resize: none;
        }

        #notesList {
            overflow-y: auto;
            max-height: 200px;
            margin: 10px 0;
            padding: 0;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
        }

        /* Notes Styles */
        .notes {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .notes textarea {
            width: 100%;
            height: 60px;
            margin-bottom: 10px;
        }

        .notes ul {
            list-style: none;
            padding: 0;
        }

        .notes li {
            margin-top: 10px;
            padding: 5px;
            background-color: #e7f3fe;
            border-left: 4px solid #2196F3;
        }

        /* Employee Distribution */
        .employee-distribution {
            background-color: #ffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
            margin-top: 10px;
            height: 500px;
            width: 400px;
            margin-left: 20px;
        }

        /* Leave Requests Table */
        #view-all-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            padding: 8px 16px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            background-color: #4b6bfa;
            color: white;
            transition: all 0.5s ease;
        }

        #view-all-btn a {
            color: white;
            text-decoration: none;
            display: block;
        }
        #view-all-btn:hover {
            background-color: #1f8af5;
            box-shadow: 0 0 8px rgb(98, 184, 235);
        }

        .leave-requests {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 1px 0px 5px rgba(0, 0, 0, 0.3);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: stretch;
            margin: 10px;
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border: 2px solid #ddd;
            text-align: left;
        }

        /* Center the Action column heading */
        th:nth-child(8) {
            text-align: center;
        }

        .indicator_div{
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }


    </style>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Welcome Admin!</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                </div>
                <hr>
            </div>
            <div class="main-content">
                <div class="sub-content">
                    <div class="content">
                        <!-- Separate boxes for statistics -->
                        <div style="display: flex; justify-content: space-evenly; flex-direction: row;">
                            <div class="indicator_div">
                                <div class="card active-employees">
                                    <div class="info">
                                        <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between; width: 180px;">
                                            <i class="fa-solid fa-users" style="font-size: 1.5rem;"></i>
                                            <p>Active Employees</p>
                                        </div>
                                        <hr>
                                        <h2 style="margin: 0;"><?php echo $activeEmpCount; ?></h2>
                                    </div>
                                </div>

                                <div class="card pending-leaves">
                                    <div class="info">
                                        <div style="display: flex; flex-direction: row; align-items: center; justify-content: space-between; width: 180px;">
                                            <i class="fa-solid fa-spinner" style="font-size: 1.5rem;"></i>
                                            <p>Pending Leaves</p>
                                        </div>
                                        <hr>
                                        <h2 style="margin: 0;"><?php echo $pendingLeaveCount; ?></h2>
                                    </div>
                                </div>
                            </div>
                            <!-- calendar -->
                            <div class="calendar-box">
                                <div class="calendar-title">Calendar</div>
                                <div class="calendar-controls">
                                    <button id="prev-month">◀</button>
                                    <select id="month-select"></select>
                                    <select id="year-select"></select>
                                    <button id="next-month">▶</button>
                                </div>
                                <div class="calendar" id="calendar-grid">
                                    <!-- Calendar grid will be injected here -->
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: row;">
                            <div style="display: flex; flex-direction: column;">
                                <div class="payroll-report">
                                    <h3>Payroll Report</h3>
                                    <canvas id="payrollChart"></canvas>
                                </div>
                                <div class="salary-distribution">
                                    <h3>Salary Distribution</h3>
                                    <canvas id="salaryChart"></canvas>
                                </div>
                            </div>
                            <div class="card employee-distribution">
                                <h3>Employee Distribution by Position</h3>
                                <canvas id="positionChart"></canvas>
                            </div>
                        </div>
                        
                        <div style="display: flex; flex-direction: row;">
                            
                            <div class="leave-requests">
                                <h3>Leave Requests</h3>

                                <!-- View All Button placed above the Action column -->
                                <button id="view-all-btn" class="view-all-btn"><a href="leave_pending.php">View All</a></button>

                                <table id="leave-table">
                                    <thead>
                                        <tr>
                                            <th>Employee ID</th>
                                            <th>Full Name</th>
                                            <th>Subject</th>
                                            <th>Date Applied</th>
                                            <th>Leave Type</th>
                                            <th>Status</th>

                                        </tr>
                                    </thead>
                                    <tbody id="showdata">
                                        <?php if ($result && $result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()):
                                                // pull names
                                                $e_res = $conn->query(
                                                    "SELECT lastname, firstname, middlename
                                                    FROM tbl_emp_acc
                                                    WHERE emp_id = '{$conn->real_escape_string($row['emp_id'])}'
                                                    LIMIT 1"
                                                    );
                                                $last = $first = $middle = '';
                                                if ($e_res && $e_res->num_rows) {
                                                    $e = $e_res->fetch_assoc();
                                                    $last = $e['lastname'];
                                                    $first = $e['firstname'];
                                                    $middle = $e['middlename'];
                                                }
                                                $_SESSION['fullname'] = trim("$first $middle $last"); // First, Middle, Last
                                            ?>
                                                <tr data-start-date="<?php echo htmlspecialchars($row['start_date']); ?>"
                                                    data-end-date="<?php echo htmlspecialchars($row['end_date']); ?>">

                                                    <td><?php echo htmlspecialchars($row['emp_id']); ?></td>
                                                    <td><?php echo htmlspecialchars("$first $middle $last"); ?></td> <!-- Full Name in one cell -->
                                                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['date_applied']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                                                    <td class="td-text" style="<?php echo ($row['status'] === 'Pending') ? 'color: red;' : ''; ?>">
                                                        <?php echo htmlspecialchars($row['status']); ?>
                                                    </td>


                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" style="text-align:center;">No records found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        
                        
                        

                        
                    </div>
                </div>
                <?php
                $positionCounts = [];
                $positionLabels = [];

                // Get employee distribution from tbl_emp_info (assuming it has a `position` column)
                $query = "SELECT position, COUNT(*) AS count FROM tbl_emp_info GROUP BY position";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $positionLabels[] = $row['position'];
                        $positionCounts[] = $row['count'];
                    }
                }

                // Convert to JSON for JavaScript
                $positionLabelsJSON = json_encode($positionLabels);
                $positionCountsJSON = json_encode($positionCounts);
                ?>
                <?php
                // Initialize arrays to hold month names and amounts
                $months = [];
                $paidData = [];
                $unpaidData = [];

                // Initialize months to ensure full set
                for ($i = 1; $i <= 12; $i++) {
                    $monthName = date('F', mktime(0, 0, 0, $i, 1));
                    $months[$i] = $monthName;
                    $paidData[$i] = 0;
                    $unpaidData[$i] = 0;
                }

                // Fetch salaries grouped by month and status
                $query = "SELECT month, status, SUM(basic_pay + holiday_pay + ot_pay) AS total 
          FROM tbl_salary 
          GROUP BY month, status";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $month = (int)$row['month'];
                        $status = strtolower($row['status']);
                        $total = (float)$row['total'];

                        if ($status == 'paid') {
                            $paidData[$month] = $total;
                        } elseif ($status == 'unpaid') {
                            $unpaidData[$month] = $total;
                        }
                    }
                }

                // Use only up to the current month
                $currentMonth = (int)date('n');
                $finalMonths = array_slice($months, 0, $currentMonth, true);
                $finalPaid = array_slice($paidData, 0, $currentMonth, true);
                $finalUnpaid = array_slice($unpaidData, 0, $currentMonth, true);

                // Convert to JSON
                $monthsJSON = json_encode(array_values($finalMonths));
                $paidJSON = json_encode(array_values($finalPaid));
                $unpaidJSON = json_encode(array_values($finalUnpaid));
                ?>


                <!-- SCRIPT -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    // Payroll Chart (Line Chart)
                    const ctxPayroll = document.getElementById('payrollChart').getContext('2d');
                    const payrollChart = new Chart(ctxPayroll, {
                        type: 'line', // Keep as line chart
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May'],
                            datasets: [{
                                    label: 'Paid',
                                    data: [10000, 12000, 15000, 18000, 20000],
                                    borderColor: '#4caf50',
                                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                                    fill: true,
                                    tension: 0.1 // Smooth curves
                                },
                                {
                                    label: 'Unpaid',
                                    data: [5000, 7000, 8000, 6000, 10000],
                                    borderColor: '#f44336',
                                    backgroundColor: 'rgba(244, 67, 54, 0.2)',
                                    fill: true,
                                    tension: 0.1
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return `${tooltipItem.dataset.label}: $${tooltipItem.raw}`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Amount ($)'
                                    },
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Months'
                                    },
                                }
                            }
                        },
                    });
                </script>
                <script>
                    // Salary Distribution Chart (Line Chart)
                    const ctxSalary = document.getElementById('salaryChart').getContext('2d');
                    const salaryChart = new Chart(ctxSalary, {
                        type: 'line',
                        data: {
                            labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                            datasets: [{
                                label: 'Salary (in thousands)',
                                data: [30000, 40000, 35000, 45000],
                                borderColor: '#2196f3',
                                backgroundColor: 'rgba(33, 150, 243, 0.2)',
                                fill: true,
                                tension: 0.1,
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return `${tooltipItem.dataset.label}: $${tooltipItem.raw}k`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Salary (k $)'
                                    },
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Quarters'
                                    },
                                }
                            }
                        },
                    });

                    // Employee Distribution by Position Chart (Pie Chart)

                    const positionLabels = <?php echo $positionLabelsJSON; ?>;
                    const positionData = <?php echo $positionCountsJSON; ?>;

                    const ctxEmployee = document.getElementById('positionChart').getContext('2d');
                    const employeeChart = new Chart(ctxEmployee, {
                        type: 'pie',
                        data: {
                            labels: positionLabels,
                            datasets: [{
                                label: 'Employee Distribution',
                                data: positionData,
                                backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#9c27b0', '#e91e63'], // Add more colors if needed
                                borderColor: '#fff',
                                borderWidth: 2,
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return `${tooltipItem.label}: ${tooltipItem.raw}`;
                                        }
                                    }
                                }
                            }
                        },
                    });
                </script>
                <script>
                    const calendarGrid = document.getElementById("calendar-grid");
                    const monthSelect = document.getElementById("month-select");
                    const yearSelect = document.getElementById("year-select");

                    const prevMonthBtn = document.getElementById("prev-month");
                    const nextMonthBtn = document.getElementById("next-month");

                    const months = [
                        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                    ];

                    const today = new Date();
                    let currentMonth = today.getMonth();
                    let currentYear = today.getFullYear();

                    function populateSelectors() {
                        monthSelect.innerHTML = "";
                        yearSelect.innerHTML = "";
                        months.forEach((m, i) => {
                            let opt = new Option(m, i);
                            if (i === currentMonth) opt.selected = true;
                            monthSelect.add(opt);
                        });

                        for (let y = currentYear - 10; y <= currentYear + 10; y++) {
                            let opt = new Option(y, y);
                            if (y === currentYear) opt.selected = true;
                            yearSelect.add(opt);
                        }
                    }

                    function renderCalendar(month, year) {
                        calendarGrid.innerHTML = "";

                        const days = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];
                        days.forEach(day => {
                            const el = document.createElement("div");
                            el.textContent = day;
                            el.style.fontWeight = "bold";
                            calendarGrid.appendChild(el);
                        });

                        const firstDay = new Date(year, month, 1).getDay();
                        const daysInMonth = new Date(year, month + 1, 0).getDate();

                        for (let i = 0; i < firstDay; i++) {
                            calendarGrid.appendChild(document.createElement("div"));
                        }

                        for (let day = 1; day <= daysInMonth; day++) {
                            const el = document.createElement("div");
                            el.textContent = day;

                            if (
                                day === today.getDate() &&
                                month === today.getMonth() &&
                                year === today.getFullYear()
                            ) {
                                el.classList.add("today");
                            }

                            calendarGrid.appendChild(el);
                        }
                    }

                    function updateCalendar() {
                        currentMonth = parseInt(monthSelect.value);
                        currentYear = parseInt(yearSelect.value);
                        renderCalendar(currentMonth, currentYear);
                    }

                    monthSelect.addEventListener("change", updateCalendar);
                    yearSelect.addEventListener("change", updateCalendar);

                    prevMonthBtn.addEventListener("click", () => {
                        currentMonth--;
                        if (currentMonth < 0) {
                            currentMonth = 11;
                            currentYear--;
                        }
                        monthSelect.value = currentMonth;
                        yearSelect.value = currentYear;
                        updateCalendar();
                    });

                    nextMonthBtn.addEventListener("click", () => {
                        currentMonth++;
                        if (currentMonth > 11) {
                            currentMonth = 0;
                            currentYear++;
                        }
                        monthSelect.value = currentMonth;
                        yearSelect.value = currentYear;
                        updateCalendar();
                    });

                    populateSelectors();
                    renderCalendar(currentMonth, currentYear);
                </script>
                <!-- SCRIPT -->
                <script src="./javascript/main.js"></script>
</body>

</html>