<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

$records_per_page = 5; // Number of records to display per page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page number, default to 1

// Calculate the limit clause for SQL query
$start_from = ($current_page - 1) * $records_per_page;

// Initialize variables
$sql = "SELECT * FROM tbl_leave WHERE status = 'Pending'";

// Check if search query is provided

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decline_leave_id'])) {
  $leave_id = $conn->real_escape_string($_POST['decline_leave_id']);
  $reason = isset($_POST['rejection_reason']) ? trim($_POST['rejection_reason']) : '';

  if (empty($reason)) {
    echo "<script>alert('Please provide a reason for declining the leave request.'); window.history.back();</script>";
    exit();
  }

  $reason = $conn->real_escape_string($reason);
  $update_sql = "UPDATE tbl_leave SET status = 'Declined', rejection_reason = '$reason' WHERE leave_id = '$leave_id'";

  if ($conn->query($update_sql)) {
    header("Location: leave_declined.php?status=success");
    exit();
  } else {
    echo "<script>alert('Failed to decline leave request.');</script>";
  }
}

// Approve logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_btn'])) {
  $leave_id = $conn->real_escape_string($_POST['approve_leave_id']);
  $update_sql = "UPDATE tbl_leave SET status = 'Approved' WHERE leave_id = '$leave_id'";
  if ($conn->query($update_sql)) {
    header("Location: leave_approved.php?status=success");
    exit();
  } else {
    echo "<script>alert('Failed to approve leave request.');</script>";
  }
}


if (isset($_GET['query']) && !empty($_GET['query'])) {
  $search_query = $_GET['query'];
  // Modify SQL query to include search filter
  $sql .= " AND (
        emp_id LIKE '%$search_query%' 
        OR leave_id LIKE '%$search_query%' 
        OR subject LIKE '%$search_query%' 
        OR date_applied LIKE '%$search_query%' 
        OR start_date LIKE '%$search_query%' 
        OR end_date LIKE '%$search_query%' 
        OR status LIKE '%$search_query%' 
        OR leave_type LIKE '%$search_query%' 
        OR message LIKE '%$search_query%' 
        OR rejection_reason LIKE '%$search_query%' 
        OR remaining_leave LIKE '%$search_query%' 
        OR no_of_leave LIKE '%$search_query%' 
        OR total_leaves LIKE '%$search_query%' 
        OR emp_id IN (
            SELECT emp_id FROM tbl_emp_acc
            WHERE firstname LIKE '%$search_query%' 
               OR middlename LIKE '%$search_query%' 
               OR lastname LIKE '%$search_query%'
        )
    )";
}

$sql .= " LIMIT $start_from, $records_per_page";

$result = $conn->query($sql);

// Count total number of records
$total_records_query = "SELECT COUNT(*) FROM tbl_leave WHERE status = 'Pending'";

if (isset($_GET['query']) && !empty($_GET['query'])) {
  $total_records_query .= " AND (
        emp_id LIKE '%$search_query%' 
        OR leave_id LIKE '%$search_query%' 
        OR subject LIKE '%$search_query%' 
        OR date_applied LIKE '%$search_query%' 
        OR start_date LIKE '%$search_query%' 
        OR end_date LIKE '%$search_query%' 
        OR status LIKE '%$search_query%' 
        OR leave_type LIKE '%$search_query%' 
        OR message LIKE '%$search_query%' 
        OR rejection_reason LIKE '%$search_query%' 
        OR remaining_leave LIKE '%$search_query%' 
        OR no_of_leave LIKE '%$search_query%' 
        OR total_leaves LIKE '%$search_query%' 
        OR emp_id IN (
            SELECT emp_id FROM tbl_emp_acc
            WHERE firstname LIKE '%$search_query%' 
               OR middlename LIKE '%$search_query%' 
               OR lastname LIKE '%$search_query%'
        )
    )";
}

$total_records_result = $conn->query($total_records_query);
$total_records_row = $total_records_result->fetch_row();
$total_records = $total_records_row[0];

$total_pages = ceil($total_records / $records_per_page);

// preserve query in pagination links
$qp = !empty($_GET['query']) ? '&query=' . urlencode($_GET['query']) : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml" />
  <link rel="stylesheet" href="./css/main.css" />
  <link rel="stylesheet" href="./css/leave.css" />
  <script src="https://kit.fontawesome.com/3b07bc6295.js" crossorigin="anonymous"></script>
  <title>Leave - Pending</title>
  <style>
    /* Basic modal styling, adjust or replace with your CSS */
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 10% auto;
      padding: 20px;
      border-radius: 10px;
      width: 50%;
      position: relative;
    }

    .close {
      color: #aaa;
      font-size: 28px;
      font-weight: bold;
      position: absolute;
      right: 15px;
      top: 10px;
      cursor: pointer;
    }

    .close:hover {
      color: black;
    }
  </style>
</head>

<body>
  <?php include 'sidenav.php'; ?>

  <div class="container">
    <div id="mainContent" class="main">
      <div class="head-title">
        <h1>Leave</h1>
        <div class="breadcrumb">
          <h5><a href="./dashboard.php">Dashboard</a></h5>
          <span> &gt; </span>
          <h5><a href="./leave_pending.php">Leave-Pending</a></h5>
        </div>
        <hr />
      </div>

      <div class="main-content">
        <div class="sub-content">
          <div class="content">
            <!-- Title and search-bar -->
            <div class="search">
              <h3>Pending</h3>
              <div class="search-bar">
                <input type="text" id="search_emp_input" class="search_emp_input" placeholder="Search employee..." />
              </div>
            </div>
            <!-- Leave Table -->
            <table>
              <thead>
                <tr>
                  <th>Employee ID</th>
                  <th>Full Name</th>
                  <th>Subject</th>
                  <th>Date Applied</th>
                  <th>Leave Type</th>
                  <th>Message</th>
                  <th>Status</th>
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
                    $_SESSION['fullname'] = trim("$first $middle $last"); // First, Middle, Last
                    ?>
                    <tr data-start-date="<?php echo htmlspecialchars($row['start_date']); ?>"
                      data-end-date="<?php echo htmlspecialchars($row['end_date']); ?>">

                      <td><?php echo htmlspecialchars($row['emp_id']); ?></td>
                      <td><?php echo htmlspecialchars("$first $middle $last"); ?></td>
                      <td><?php echo htmlspecialchars($row['subject']); ?></td>
                      <td><?php echo htmlspecialchars($row['date_applied']); ?></td>
                      <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                      <td><?php echo htmlspecialchars($row['message']); ?></td>
                      <td class="td-text" style="<?php echo ($row['status'] === 'Pending') ? 'color: orange;' : ''; ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                      </td>

                      <td class="td-text">
                        <div class="action-buttons" style="display:flex; gap: 5px;">
                          <button class="view-btn btn-view-info"><i class="fa-solid fa-eye"></i></button>

                          <form method="post"
                            onsubmit="return confirm('Are you sure you want to approve this leave request?');"
                            style="margin: 0;">
                            <input type="hidden" name="approve_leave_id"
                              value="<?php echo htmlspecialchars($row['leave_id']); ?>" />
                            <button type="submit" name="approve_btn" class="view-btn btn-approve"><i class="fa-solid fa-thumbs-up"></i></button>
                          </form>

                          <button class="view-btn btn-decline"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" style="text-align:center;">No records found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
            <br />
            <!-- Pagination -->
            <div
              style="display: flex; justify-content: space-between; align-items: center; padding-right: 1.5%; padding-left: 1.5%;">
              <p style="margin: 0;">Page <?= $current_page ?> out of <?= $total_pages ?></p>
              <div class="pagination" id="content">
                <?php if ($current_page > 1): ?>
                  <a href="?page=<?= ($current_page - 1); ?>&query=<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>"
                    class="prev"
                    style="border-radius:4px;background-color:#368DB8;color:white;margin-bottom:13px; padding: 10px;">&laquo;
                    Previous</a>
                <?php endif; ?>
                <?php if ($current_page < $total_pages): ?>
                  <a href="?page=<?= ($current_page + 1); ?>&query=<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>"
                    class="next"
                    style="border-radius:4px;background-color:#368DB8;color:white;margin-bottom:13px; padding: 10px;">Next
                    &raquo;</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- View Info Modal -->
  <div id="infoModal" class="modal">
    <div class="modal-content">
      <span class="close close-info"></span>
      <h2>Leave Information</h2>
      <hr />
      <div class="modal-details">
        <p><strong>Employee ID:</strong> <span id="modalEmpId"></span></p>
        <p><strong>Full Name:</strong> <span id="modalFullName"></span></p>
        <p><strong>Subject:</strong> <span id="modalSubject"></span></p>
        <p><strong>Date Applied:</strong> <span id="modalDateApplied"></span></p>
        <p><strong>Leave Type:</strong> <span id="modalLeaveType"></span></p>
        <p><strong>Start Date:</strong> <span id="modalStartDate"></span></p>
        <p><strong>End Date:</strong> <span id="modalEndDate"></span></p>
        <p><strong>Message:</strong> <span id="modalMessage"></span></p>
      </div>
      <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
        <button class="close-info"
          style="padding: 8px 16px; background-color: black; color: white; border: none; border-radius: 4px; cursor: pointer;">
          Close
        </button>
      </div>
    </div>
  </div>

  <!-- Decline Reason Modal -->
  <div id="declineModal" class="modal">
    <div class="modal-content">
      <span class="close close-decline">&times;</span>
      <h2>Decline Leave Request</h2>
      <hr />

      <!-- ✅ Added form here -->
      <form method="post" id="declineForm">
        <!-- ✅ Hidden input for leave_id -->
        <input type="hidden" name="decline_leave_id" id="declineLeaveId" />

        <div class="modal-details" style="margin-bottom: 100px;">
          <label for="declineReason"><strong>Reason for Declining:</strong></label><br />
          <textarea id="declineReason" name="rejection_reason" rows="4" cols="50"
            placeholder="Enter reason here..."></textarea>
        </div>
        <div style="display: flex; justify-content: flex-end; gap: 10px;">
          <button type="button" id="cancelDecline"
            style="padding: 8px 16px; background-color: #5483B3; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Cancel
          </button>
          <button type="submit" id="submitDecline" name="decline_btn"
            style="padding: 8px 16px; background-color: black; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>


  <script>
    // Modal references
    const infoModal = document.getElementById("infoModal");
    const declineModal = document.getElementById("declineModal");

    // Close buttons for info modal
    document.querySelectorAll(".close-info").forEach(el => {
      el.addEventListener("click", () => {
        infoModal.style.display = "none";
      });
    });

    // Close buttons for decline modal
    document.querySelectorAll(".close-decline, #cancelDecline").forEach(el => {
      el.addEventListener("click", () => {
        declineModal.style.display = "none";
      });
    });

    // Close modals if clicked outside modal content
    window.addEventListener("click", (event) => {
      if (event.target == infoModal) {
        infoModal.style.display = "none";
      }
      if (event.target == declineModal) {
        declineModal.style.display = "none";
      }
    });

    // View Info buttons
    document.querySelectorAll(".btn-view-info").forEach((btn) => {
      btn.addEventListener("click", function () {
        const row = this.closest("tr");
        const empId = row.cells[0].textContent.trim();
        const fullName = row.cells[1].textContent.trim();
        const subject = row.cells[2].textContent.trim();
        const dateApplied = row.cells[3].textContent.trim();
        const leaveType = row.cells[4].textContent.trim();
        const message = row.cells[5].textContent.trim();
        const startDate = row.getAttribute("data-start-date") || "N/A";
        const endDate = row.getAttribute("data-end-date") || "N/A";

        document.getElementById("modalEmpId").textContent = empId;
        document.getElementById("modalFullName").textContent = fullName;
        document.getElementById("modalSubject").textContent = subject;
        document.getElementById("modalDateApplied").textContent = dateApplied;
        document.getElementById("modalLeaveType").textContent = leaveType;
        document.getElementById("modalMessage").textContent = message;
        document.getElementById("modalStartDate").textContent = startDate;
        document.getElementById("modalEndDate").textContent = endDate;

        infoModal.style.display = "block";
      });
    });

    // Decline buttons
    document.querySelectorAll(".btn-decline").forEach(btn => {
      btn.addEventListener("click", () => {
        declineModal.style.display = "block";
      });
    });

    document.querySelectorAll(".btn-decline").forEach(btn => {
      btn.addEventListener("click", function () {
        const row = this.closest("tr");
        const leaveId = row.querySelector("input[name='approve_leave_id']").value;
        document.getElementById("declineLeaveId").value = leaveId;
        declineModal.style.display = "block";
      });
    });

    document.getElementById("submitDecline").addEventListener("click", function (e) {
  const reasonField = document.getElementById("declineReason");
  if (reasonField.value.trim() === "") {
    alert("Please provide a reason for declining the leave request.");
    e.preventDefault();
  }
});



    // Search filter
    const searchInput = document.getElementById("search_emp_input");
    searchInput.addEventListener("input", () => {
      const filter = searchInput.value.toLowerCase();
      const rows = document.querySelectorAll("#showdata tr");
      rows.forEach(row => {
        const fullName = row.cells[1].textContent.toLowerCase();
        if (fullName.includes(filter)) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
  </script>
</body>

</html>