<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

// Fetch total number of records from report_leave table
$query_count = "SELECT COUNT(*) AS total FROM report_leave";
$result_count = mysqli_query($conn, $query_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_records = $row_count['total'];

$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Handle search and date filters
$search = "";
$from_date = "";
$to_date = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search'])) {
        $search = trim($_POST['search']);
    }
    if (isset($_POST['from_date'])) {
        $from_date = $_POST['from_date'];
    }
    if (isset($_POST['to_date'])) {
        $to_date = $_POST['to_date'];
    }
}

// Build query dynamically based on filters
$query = "SELECT emp_id, subject, name, leave_type, date_filed, no_of_leave, remaining_leave, total_leave 
          FROM report_leave 
          WHERE (emp_id LIKE '%$search%' OR name LIKE '%$search%')";

if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND date_filed BETWEEN '$from_date' AND '$to_date'";
}

$query .= " LIMIT $limit OFFSET $offset"; // Apply pagination
$result = mysqli_query($conn, $query);

$total_pages = ceil($total_records / $limit);
?>

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
                                <form method="POST" style="display: flex; align-items: center;">
                                    <button class="search-btn">Search</button>
                                    <input type="text" name="search" placeholder="Search employee..." value="<?php echo htmlspecialchars($search); ?>" />
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="controls">
                            <label for="show-entries">Show
                                <select id="show-entries">
                                    <option value="10">10</option>
                                </select>
                                entries
                            </label>
                            <div class="date-range">
                                <form method="POST" style="display: flex; align-items: center;">
                                    <label for="from-date">From:
                                        <input type="date" id="from-date" name="from_date" value="<?php echo $from_date; ?>" />
                                    </label>
                                    <label for="to-date">To:
                                        <input type="date" id="to-date" name="to_date" value="<?php echo $to_date; ?>" />
                                    </label>
                                    <button class="search-btn">Search</button>
                                </form>
                            </div>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Subject</th>
                                    <th>Name</th>
                                    <th>Leave Type</th>
                                    <th>Date Filed</th>
                                    <th>No. Of Leave</th>
                                    <th>Remaining Leave</th>
                                    <th>Total Leave</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $formatted_date = date("F-d-Y", strtotime($row['date_filed']));
                                        echo "<tr>
                                                <td>" . htmlspecialchars($row['emp_id']) . "</td>
                                                <td>" . htmlspecialchars($row['subject']) . "</td>
                                                <td>" . htmlspecialchars($row['name']) . "</td>
                                                <td>" . htmlspecialchars($row['leave_type']) . "</td>
                                                <td>" . htmlspecialchars($formatted_date) . "</td>
                                                <td>" . htmlspecialchars($row['no_of_leave']) . "</td>
                                                <td>" . htmlspecialchars($row['remaining_leave']) . "</td>
                                                <td>" . htmlspecialchars($row['total_leave']) . "</td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' style='text-align: center;'>No records found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <!-- Pagination -->
                        <div class="pagination">
                            <p>Showing <?php echo min($limit, $total_records - $offset); ?> / <?php echo $total_records; ?> Results</p>
                            <div class="pagination">
                                <button id="prevPage" <?php echo ($page <= 1) ? 'disabled' : ''; ?>>Prev</button>
                                <input type="text" class="perpage" value="<?php echo $page; ?>" readonly />
                                <button id="nextPage" <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>>Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Pagination -->
    <script>
        document.getElementById("prevPage").addEventListener("click", function() {
            let currentPage = <?php echo $page; ?>;
            if (currentPage > 1) {
                window.location.href = "?page=" + (currentPage - 1);
            }
        });

        document.getElementById("nextPage").addEventListener("click", function() {
            let currentPage = <?php echo $page; ?>;
            let totalPages = <?php echo $total_pages; ?>;
            if (currentPage < totalPages) {
                window.location.href = "?page=" + (currentPage + 1);
            }
        });
    </script>

</body>

</html>