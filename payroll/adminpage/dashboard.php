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
    <link rel="stylesheet" href="./css/dashboard.css">
    <title>Dashboard</title>
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
                        <div class="card active-employees" style="margin-top: 130px;">
                            <div class="info">
                                <p>Active Employees</p>
                                <hr>
                                <h2><?php echo $activeEmpCount; ?></h2>
                            </div>
                        </div>

                        <div class="card pending-leaves" style="margin-top: 130px;">
                            <div class="info">
                                <p>Pending Leaves</p>
                                <hr>
                                <h2><?php echo $pendingLeaveCount; ?></h2>
                            </div>
                        </div>

                        <div class="payroll-report">
                            <h3>Payroll Report</h3>
                            <canvas id="payrollChart"></canvas>
                        </div>

                        <div class="salary-distribution">
                            <h3>Salary Distribution</h3>
                            <canvas id="salaryChart"></canvas>
                        </div>

                        <!-- Upcoming Holidays and Event Section -->
                        <div class="Event">
                            <div class="Event-header">
                                <h3>Upcoming Holidays and Event</h3>
                                <a href="#" class="view-all">View All</a>
                            </div>

                            <!-- Event 1 -->
                            <div class="event-item">
                                <div class="event-details">
                                    <span class="event-title">NINOY AQUINO DAY</span>
                                    <span class="event-type">National : Special Holiday</span>
                                    <span class="event-day">Wednesday</span>
                                </div>
                                <div class="event-date">
                                    14<span>Nov</span>
                                </div>
                            </div>

                            <!-- Event 2 -->
                            <div class="event-item">
                                <div class="event-details">
                                    <span class="event-title">NINOY AQUINO DAY</span>
                                    <span class="event-type">National : Regular Holiday</span>
                                    <span class="event-day">Thursday</span>
                                </div>
                                <div class="event-date">
                                    08<span>Feb</span>
                                </div>
                            </div>

                            <!-- Event 3 -->
                            <div class="event-item">
                                <div class="event-details">
                                    <span class="event-title" style="color: #007bff;">COMPANY HOLIDAY</span>
                                    <span class="event-type">National : Regular Holiday</span>
                                </div>
                                <div class="event-date">
                                    23<span>Jan</span>
                                </div>
                            </div>
                        </div>

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

                        <div class="card employee-distribution">
                            <h3>Employee Distribution by Position</h3>
                            <canvas id="positionChart"></canvas>
                        </div>

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
                    const ctxEmployee = document.getElementById('positionChart').getContext('2d');
                    const employeeChart = new Chart(ctxEmployee, {
                        type: 'pie', // Pie chart for distribution
                        data: {
                            labels: ['Manager', 'Crew Member', 'Executive'],
                            datasets: [{
                                label: 'Employee Distribution',
                                data: [25, 50, 15], // Example data
                                backgroundColor: ['#4caf50', '#2196f3', '#ff9800'],
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