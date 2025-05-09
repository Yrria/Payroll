<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

$records_per_page = 5;
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

// Build the base SELECT
$sql = "SELECT * FROM tbl_leave WHERE status = 'Approved'";


// If there's a search term, extend WHERE to include tbl_leave fields AND names from tbl_emp_acc
if (!empty($_GET['query'])) {
  $q = $conn->real_escape_string($_GET['query']);

  $sql .= " WHERE (
        emp_id           LIKE '%{$q}%'
     OR leave_id         LIKE '%{$q}%'
     OR subject          LIKE '%{$q}%'
     OR date_applied     LIKE '%{$q}%'
     OR start_date       LIKE '%{$q}%'
     OR end_date         LIKE '%{$q}%'
     OR status           LIKE '%{$q}%'
     OR leave_type       LIKE '%{$q}%'
     OR message          LIKE '%{$q}%'
     OR rejection_reason LIKE '%{$q}%'
     OR remaining_leave  LIKE '%{$q}%'
     OR no_of_leave      LIKE '%{$q}%'
     OR total_leaves     LIKE '%{$q}%'
     -- search names in tbl_emp_acc
     OR emp_id IN (
        SELECT emp_id FROM tbl_emp_acc
        WHERE firstname  LIKE '%{$q}%'
           OR middlename LIKE '%{$q}%'
           OR lastname   LIKE '%{$q}%'
     )
    )";
}

$sql .= " LIMIT $start_from, $records_per_page";
$result = $conn->query($sql);

// Count for pagination (same WHERE clause)
$total_sql = "SELECT COUNT(*) FROM tbl_leave";
if (!empty($_GET['query'])) {
  $total_sql .= " WHERE (
        emp_id           LIKE '%{$q}%'
     OR leave_id         LIKE '%{$q}%'
     OR subject          LIKE '%{$q}%'
     OR date_applied     LIKE '%{$q}%'
     OR start_date       LIKE '%{$q}%'
     OR end_date         LIKE '%{$q}%'
     OR status           LIKE '%{$q}%'
     OR leave_type       LIKE '%{$q}%'
     OR message          LIKE '%{$q}%'
     OR rejection_reason LIKE '%{$q}%'
     OR remaining_leave  LIKE '%{$q}%'
     OR no_of_leave      LIKE '%{$q}%'
     OR total_leaves     LIKE '%{$q}%'
     OR emp_id IN (
        SELECT emp_id FROM tbl_emp_acc
        WHERE firstname  LIKE '%{$q}%'
           OR middlename LIKE '%{$q}%'
           OR lastname   LIKE '%{$q}%'
     )
    )";
}
$total_rows = $conn->query($total_sql)->fetch_row()[0];
$total_pages = ceil($total_rows / $records_per_page);

// preserve query in pagination links
$qp = !empty($_GET['query']) ? '&query=' . urlencode($_GET['query']) : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/leave.css">
    <title>Leave - Approved</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Leave</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                    <span> > </span>
                    <h5><a href="./leave_approved.php">Leave-Approved </a></h5>
                </div>
                <hr>
            </div>
            <div class="main-content">
                <div class="sub-content">
                    <div class="content">

                        <!-- Search Form -->
                        <div class="search">
                            <h3>Approved</h3>
                            <form method="get" action="">
                                <div class="search-bar">
                                    <input type="text" name="query" placeholder="Search..."
                                        value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" />
                                    <button type="submit" class="search-btn">Search</button>
                                </div>
                            </form>
                        </div>

                        <!-- Leave Table -->
                        <table>
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Subject</th>
                                    <th>Date Applied</th>
                                    <th>Leave Type</th>
                                    <th>Message</th>
                                    <th>Action</th>
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
                    $_SESSION['fullname'] = trim("$last, $first $middle");
                    ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['emp_id']); ?></td>
                                    <td><?php echo htmlspecialchars($last); ?></td>
                                    <td><?php echo htmlspecialchars($first); ?></td>
                                    <td><?php echo htmlspecialchars($middle); ?></td>
                                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date_applied']); ?></td>
                                    <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                                    <td class="td-text"><?php echo htmlspecialchars($row['message']); ?></td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn">View Info</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="9" style="text-align:center;">No records found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="pagination">
                            <p>Showing <?php echo min($records_per_page, $total_rows - $start_from); ?> /
                                <?php echo $total_rows; ?>
                                Results</p>
                            <div class="pagination-controls" style="display: flex; align-items: center; gap: 10px;">
                                <form method="get" style="display: flex; gap: 10px;">
                                    <?php if (!empty($_GET['query'])): ?>
                                    <input type="hidden" name="query"
                                        value="<?php echo htmlspecialchars($_GET['query']); ?>">
                                    <?php endif; ?>

                                    <button type="submit" name="page" value="<?php echo max(1, $current_page - 1); ?>"
                                        <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>>
                                        Prev
                                    </button>
                                    <span style="font-weight: bold;"><?php echo $current_page; ?></span>
                                    <button type="submit" name="page"
                                        value="<?php echo min($total_pages, $current_page + 1); ?>"
                                        <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>>
                                        Next
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <!-- modals & scripts unchanged -->
  <script src="./javascript/main.js"></script>
</body>

</html>

<!-- Modal -->
<div id="infoModal" class="modal">
  <div class="modal-content">
    <h2>Leave Info</h2>
    <hr>
    <div class="modal-details">
      <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
        <div style="width: 48%;">
          <label><strong>Leave Subject</strong></label>
          <input type="text" value="Leave for Medical Concerns" readonly
            style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" />
        </div>
        <div style="width: 48%;">
          <label><strong>Status</strong></label>
          <input type="text" value="Approved" readonly
            style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" />
        </div>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
        <div style="width: 48%;">
          <label><strong>Leave Date (MM/DD/YYYY)</strong></label>
          <input type="text" value="01/06/2024" readonly
            style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" />
        </div>
        <div style="width: 48%;">
          <label><strong>Leave Type</strong></label>
          <input type="text" value="Medical Leave" readonly
            style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" />
        </div>
      </div>
      <div style="margin-bottom: 10px;">
        <label><strong>Message</strong></label>
        <textarea readonly
          style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; resize: none; height: 80px;">I won't be able to come to work due to my medical concerns.
        </textarea>
      </div>
    </div>
    <!-- Back button -->
    <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
      <button class="close"
        style="padding: 8px 12px; background-color: black; color: white; border: none; border-radius: 4px; cursor: pointer;">
        Back
      </button>
    </div>
  </div>
</div>



<script>
  // Get modal elements
  const modal = document.getElementById("infoModal");
  const viewButtons = document.querySelectorAll(".view-btn");
  const closeModalButtons = document.querySelectorAll(".close, .close-modal-btn");

  // Show modal on "View Info" button click
  viewButtons.forEach((button) => {
    button.addEventListener("click", () => {
      modal.style.display = "block";
    });
  });

  // Hide modal on close button click
  closeModalButtons.forEach((button) => {
    button.addEventListener("click", () => {
      modal.style.display = "none";
    });
  });

  // Close modal when clicking outside the modal content
  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
</script>