<?php
include '../assets/databse/connection.php'; // Ensure correct connection file

if (isset($_POST['name']) || isset($_POST['month']) || isset($_POST['year']) || isset($_POST['cutoff'])) {
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $month = isset($_POST['month']) ? mysqli_real_escape_string($conn, $_POST['month']) : '';
    $year = isset($_POST['year']) ? mysqli_real_escape_string($conn, $_POST['year']) : '';
    $cutoff = isset($_POST['cutoff']) ? mysqli_real_escape_string($conn, $_POST['cutoff']) : '';

    // Base SQL query
    $sql = "SELECT * FROM tbl_salary WHERE (f_name LIKE '%$name%' OR m_name LIKE '%$name%' OR l_name LIKE '%$name%')";

    // Add filters if selected
    if (!empty($month)) {
        $sql .= " AND month = '$month'";
    }
    if (!empty($year)) {
        $sql .= " AND year = '$year'";
    }
    if (!empty($cutoff)) {
        $sql .= " AND cutoff = '$cutoff'";
    }

    $query = mysqli_query($conn, $sql);

    $data = '';
    
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $fullname = !empty($row['m_name']) ? $row['l_name'] . " , " . $row['f_name'] . " , " . $row['m_name'] . "." : $row['l_name'] . " , " . $row['f_name'];

            $data .= "<tr>
                        <td>{$row['emp_id']}</td>
                        <td>{$fullname}</td>
                        <td>{$row['position_name']}</td>
                        <td>{$row['emp_shift']}</td>
                        <td>{$row['basic_pay']}</td>
                        <td class='td-text'>{$row['status']}</td>
                        <td class='td-text'>
                            <div class='action-buttons'>
                                <a href='./create_payslip.php'><button class='slip-btn'>Generate Slip</button></a>
                                <button class='view-btn'>Summary</button>
                            </div>
                        </td>
                    </tr>";
        }
    } else {
        $data = "<tr><td colspan='7' class='no-data'>No records found</td></tr>";
    }

    echo $data;
}
?>
