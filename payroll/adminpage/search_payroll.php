<?php
include '../assets/databse/connection.php'; // Ensure correct connection file

if (isset($_POST['name'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    
    $query = mysqli_query($conn, "SELECT * FROM tbl_salary WHERE f_name LIKE '%$name%'OR m_name LIKE '%$name%'OR l_name LIKE '%$name%'");
    
    $data = '';
    
    while ($row = mysqli_fetch_assoc($query)) {
        if (!empty($row['m_name'])) {
            $fullname = $row['l_name'] . " , " . $row['f_name'] . " , " . $row['m_name'] . ".";
        } else {
            $fullname = $row['l_name'] . " , " . $row['f_name'];
        }
    
        $data .= "<tr><td>" . $row['emp_id']. "</td><td>" . $fullname . "</td><td>" . $row['position_name'] . "</td><td>" . $row['emp_shift'] . "</td><td>" . $row['basic_pay'] . "</td><td class='td-text'>" . $row['status'] ."</td><td class='td-text'>" . '<div class="action-buttons">
                                                <a href="./create_payslip.php"><button class="slip-btn">Generate Slip</button></a>
                                                <button class="view-btn">Summary</button>
                                            </div>'. "</td></tr>"; 

    }

    echo $data;

} else {
    echo "<tr><td colspan='6'>No records found</td></tr>";
}
?>
