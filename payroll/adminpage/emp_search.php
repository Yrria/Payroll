<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

$records_per_page = 5;
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

$search_query = '';
$where_clause = '';

// Handle search
if (!empty($_GET['query'])) {
    $q = $conn->real_escape_string($_GET['query']);
    $search_query = "&query=" . urlencode($_GET['query']);

    $where_clause = "WHERE (
        a.emp_id LIKE '%{$q}%' OR
        a.firstname LIKE '%{$q}%' OR
        a.middlename LIKE '%{$q}%' OR
        a.lastname LIKE '%{$q}%' OR
        a.email LIKE '%{$q}%' OR
        a.phone_no LIKE '%{$q}%' OR
        a.gender LIKE '%{$q}%' OR
        a.status LIKE '%{$q}%' OR
        b.position LIKE '%{$q}%' OR
        b.shift LIKE '%{$q}%'
    )";
}

// Join query for both tables to fetch paginated results
$sql = "SELECT a.*, b.shift, b.position, b.rate 
        FROM tbl_emp_acc a 
        LEFT JOIN tbl_emp_info b ON a.emp_id = b.emp_id 
        $where_clause 
        LIMIT $start_from, $records_per_page";

$result = $conn->query($sql);

// Count total matching records for pagination
$count_sql = "SELECT COUNT(*) AS total 
              FROM tbl_emp_acc a 
              LEFT JOIN tbl_emp_info b ON a.emp_id = b.emp_id 
              $where_clause";

$count_result = $conn->query($count_sql);
$total_rows = 0;
if ($count_result && $row = $count_result->fetch_assoc()) {
    $total_rows = (int) $row['total'];
}

$total_pages = ceil($total_rows / $records_per_page);
?>

<table id="leave-table">
    <tbody id="showdata">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $fullname = !empty($row['middlename'])
                    ? $row['firstname'] . " " . strtoupper(substr($row['middlename'], 0, 1)) . ". " . $row['lastname']
                    : $row['firstname'] . " " . $row['lastname'];

                $_SESSION['fullname'] = $fullname;
        ?>
                <tr>
                    <td><?= htmlspecialchars($row['emp_id']); ?></td>
                    <td><?= htmlspecialchars($fullname); ?></td>
                    <td><?= htmlspecialchars($row['position'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['shift'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['gender']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['phone_no']); ?></td>
                    <td><?= htmlspecialchars($row['address']); ?></td>
                    <td><?= htmlspecialchars($row['rate'] ?? 'N/A'); ?></td>
                    <td style="color: <?= (strtolower(trim($row['status'])) === 'active') ? 'green' : 'red'; ?>; font-weight: 500;">
                        <?= htmlspecialchars($row['status']); ?>
                    </td>
                    <td class="td-text">
                        <div class="action-buttons">
                            <button class="view-btn">View Info</button>
                        </div>
                    </td>
                </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='11' style='text-align: center;'>No results found</td></tr>";
        }
        ?>
    </tbody>
</table>
