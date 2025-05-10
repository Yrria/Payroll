<?php
include '../assets/databse/connection.php';

$search = isset($_POST['search']) ? trim($_POST['search']) : '';
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';
$limit = isset($_POST['show_entries']) ? (int)$_POST['show_entries'] : 10;
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT 
            l.emp_id AS EmployeeID, 
            l.subject AS Subject, 
            CONCAT(a.lastname, ', ', a.firstname, ' ', a.middlename) AS Name, 
            l.leave_type AS LeaveType, 
            l.date_applied AS DateFiled, 
            l.no_of_leave AS NoOfLeave, 
            l.remaining_leave AS RemainingLeave, 
            l.total_leaves AS TotalLeave
        FROM tbl_leave l
        JOIN tbl_emp_acc a ON l.emp_id = a.emp_id
        WHERE l.status IN ('Approved', 'Declined')";

if (!empty($search)) {
    $sql .= " AND (a.lastname LIKE '%$search%' OR a.firstname LIKE '%$search%' OR a.middlename LIKE '%$search%' OR l.emp_id LIKE '%$search%')";
}

if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND l.date_applied BETWEEN '$from_date' AND '$to_date'";
}

$sql .= " ORDER BY l.date_applied DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $formatted_date = date("F-d-Y", strtotime($row['DateFiled']));
        echo "<tr>
                <td>{$row['EmployeeID']}</td>
                <td>{$row['Subject']}</td>
                <td>{$row['Name']}</td>
                <td>{$row['LeaveType']}</td>
                <td>" . htmlspecialchars($formatted_date) . "</td>
                <td>{$row['NoOfLeave']}</td>
                <td>{$row['RemainingLeave']}</td>
                <td>{$row['TotalLeave']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='8' style='text-align: center;'>No records found</td></tr>";
}
