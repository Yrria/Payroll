<?php
include '../assets/databse/connection.php';

// Get the search query and status dynamically
$search_query = isset($_GET['query']) ? $_GET['query'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : ''; // Default will be empty (no status fallback)

$records_per_page = 7;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

// Escape the search query and status
$search_query_escaped = $conn->real_escape_string($search_query);
$status_escaped = $conn->real_escape_string($status);

// Build the base query
$sql = "SELECT * FROM tbl_leave";

// If the status is set (from the page like pending, approved, etc.), filter by status
if (!empty($status_escaped)) {
    $sql .= " WHERE status = '$status_escaped'";
}

// If a search query is provided, add the search filters
if (!empty($search_query_escaped)) {
    $sql .= " AND (
        emp_id LIKE '%$search_query_escaped%' 
        OR leave_id LIKE '%$search_query_escaped%' 
        OR subject LIKE '%$search_query_escaped%' 
        OR date_applied LIKE '%$search_query_escaped%' 
        OR start_date LIKE '%$search_query_escaped%' 
        OR end_date LIKE '%$search_query_escaped%' 
        OR status LIKE '%$search_query_escaped%' 
        OR leave_type LIKE '%$search_query_escaped%' 
        OR message LIKE '%$search_query_escaped%' 
        OR rejection_reason LIKE '%$search_query_escaped%' 
        OR remaining_leave LIKE '%$search_query_escaped%' 
        OR no_of_leave LIKE '%$search_query_escaped%' 
        OR total_leaves LIKE '%$search_query_escaped%' 
        OR emp_id IN (
            SELECT emp_id FROM tbl_emp_acc
            WHERE firstname LIKE '%$search_query_escaped%' 
               OR middlename LIKE '%$search_query_escaped%' 
               OR lastname LIKE '%$search_query_escaped%'
        )
    )";
}

$sql .= " ORDER BY date_applied DESC LIMIT $start_from, $records_per_page";
$result = $conn->query($sql);

// Fetch and display data
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $e_res = $conn->query("SELECT lastname, firstname, middlename FROM tbl_emp_acc WHERE emp_id = '{$conn->real_escape_string($row['emp_id'])}' LIMIT 1");
        $last = $first = $middle = '';
        if ($e_res && $e_res->num_rows) {
            $e = $e_res->fetch_assoc();
            $last = $e['lastname'];
            $first = $e['firstname'];
            $middle = $e['middlename'];
        }

        echo "<tr>
            <td>" . htmlspecialchars($row['emp_id']) . "</td>
            <td>" . htmlspecialchars("$first $middle $last") . "</td>
            <td>" . htmlspecialchars($row['subject']) . "</td>
            <td>" . htmlspecialchars($row['date_applied']) . "</td>
            <td>" . htmlspecialchars($row['leave_type']) . "</td>
            <td>" . htmlspecialchars($row['message']) . "</td>
            <td class='td-text " . ($row['status'] === 'Approved' ? 'status-approved' : ($row['status'] === 'Declined' ? 'status-declined' : 'status-pending')) . "'>" . htmlspecialchars($row['status']) . "</td>
            <td class='td-text'>
                <div class='action-buttons'>
                    <button class='view-btn'>View Info</button>
                    <button class='view-btn'>Approve</button>
                    <button class='view-btn'>Decline</button>
                </div>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='8' style='text-align:center;'>No records found.</td></tr>";
}
?>
