<?php
session_start();
include '../assets/databse/connection.php';

$emp_id = $_SESSION['emp_id'] ?? null;

if (!$emp_id) {
    exit('Unauthorized access');
}

$year = $_GET['year'] ?? '';
$month = $_GET['month'] ?? '';
$cutoff = $_GET['cutoff'] ?? '';

// Always enforce status as 'Paid'
$status = 'Paid';

$query = "SELECT * FROM tbl_salary WHERE emp_id = '$emp_id' AND status = '$status'";

if (!empty($year))   $query .= " AND year = '$year'";
if (!empty($month))  $query .= " AND month = '$month'";
if (!empty($cutoff)) $query .= " AND cutoff = '$cutoff'";

$result = mysqli_query($conn, $query);

// Check if any rows returned
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td>{$row['year']}</td>
            <td>{$row['month']}</td>
            <td>{$row['cutoff']}</td>
            <td>{$row['status']}</td>
            <td>₱ " . number_format($row['total_salary'], 2) . "</td>
            <td>
                <button id='viewBtn' class='view-btn' style='cursor: pointer;'>
                    <img src='../assets/view.png' alt='View' style='width: 24px; height: 24px; padding: 0;' />
                </button>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='6' style='text-align: center;'>No record found</td></tr>";
}
?>
