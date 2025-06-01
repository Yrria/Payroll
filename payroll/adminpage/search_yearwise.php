<?php
include '../assets/databse/connection.php';

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

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

if (!empty($query)) {
    $sql .= "
        WHERE e.emp_id LIKE '%$query%'
        OR e.firstname LIKE '%$query%'
        OR e.lastname LIKE '%$query%'
        OR e.middlename LIKE '%$query%'
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
    echo "<tr><td colspan='7' style='text-align:center; vertical-align: middle; height: 150px;'>No data found.</td></tr>";
}
