<?php
include '../assets/databse/connection.php';

$name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
$month = isset($_POST['month']) ? mysqli_real_escape_string($conn, $_POST['month']) : '';
$year = isset($_POST['year']) ? mysqli_real_escape_string($conn, $_POST['year']) : '';
$cutoff = isset($_POST['cutoff']) ? mysqli_real_escape_string($conn, $_POST['cutoff']) : '';

$sql = "SELECT * FROM tbl_salary WHERE status = 'Paid'";

if (!empty($name)) {
    $sql .= " AND (emp_id LIKE '%$name%' OR f_name LIKE '%$name%' OR m_name LIKE '%$name%' OR l_name LIKE '%$name%')";
}
if (!empty($month)) {
    $sql .= " AND month = '$month'";
}
if (!empty($year)) {
    $sql .= " AND year = '$year'";
}
if (!empty($cutoff)) {
    $sql .= " AND cutoff = '$cutoff'";
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $fullname = !empty($row['m_name']) ? $row['l_name'] . " , " . $row['f_name'] . " , " . $row['m_name'] . "." : $row['l_name'] . " , " . $row['f_name'];
        echo "<tr>
            <td>{$row['emp_id']}</td>
            <td>" . htmlspecialchars($fullname) . "</td>
            <td>{$row['position_name']}</td>
            <td>{$row['emp_shift']}</td>
            <td>{$row['basic_pay']}</td>
            <td class='td-text'>{$row['status']}</td>
            <td class='td-text'>
                <div class='action-buttons'>
                    <a href='./view_slip.php?id={$row['emp_id']}'><button class='view-btn'>View Slip</button></a>
                </div>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='7' style='text-align:center; color: red;'>Oops! No records found.</td></tr>";
}
?>
