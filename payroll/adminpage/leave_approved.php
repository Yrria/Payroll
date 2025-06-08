<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

$records_per_page = 7; // Number of records to display per page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page number, default to 1

// Calculate the limit clause for SQL query
$start_from = ($current_page - 1) * $records_per_page;

// Initialize variables
$sql = "SELECT * FROM tbl_leave WHERE status = 'Approved'";

// Check if search query is provided
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/leave.css">
    <script src="https://kit.fontawesome.com/3b07bc6295.js" crossorigin="anonymous"></script>
  <title>Leave - Approved</title>
  <style>
    #toastBox {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .toast {
        background-color: #1BCD80;
        color: white;
        padding: 12px 18px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        min-width: 200px;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn 0.3s ease, fadeOut 0.3s ease 2.7s forwards;
    }

    .toast.error {
        background-color: #e74c3c;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
  </style>
</head>

<body>
  <?php include 'sidenav.php'; ?>
    <div id="toastBox"></div>
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

            <!-- Title and search-bar -->
            <div class="search">
              <h3>Approved</h3>
              <div class="search-bar">
                <input type="text" id="search_emp_input" class="search_emp_input" placeholder="Search employee..." />
              </div>
            </div>
            <!-- Leave Table -->
            <table>
              <thead>
                <tr>
                  <th>Employee ID</th>
                  <th>Full Name</th> <!-- Changed column to Full Name -->
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
                    <tr>
                      <td><?php echo htmlspecialchars($row['emp_id']); ?></td>
                      <td><?php echo htmlspecialchars("$first $middle $last"); ?></td> <!-- Full Name in one cell -->
                      <td><?php echo htmlspecialchars($row['subject']); ?></td>
                      <td><?php echo htmlspecialchars($row['date_applied']); ?></td>
                      <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                      <td><?php echo htmlspecialchars($row['message']); ?></td>
                      <td class="td-text 
                        <?php
                        if ($row['status'] === 'Approved')
                          echo 'status-approved';
                        elseif ($row['status'] === 'Declined')
                          echo 'status-declined';
                        elseif ($row['status'] === 'Pending')
                          echo 'status-pending';
                        ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                      </td>
                      <td class="td-text">
                        <div class="action-buttons" style="display:flex; gap: 5px;">
                          <button class="view-btn btn-view-info"><i class="fa-solid fa-eye"></i></button>
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
            <br>
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
  <!-- modals & scripts unchanged -->
  <script src="./javascript/main.js"></script>
</body>

</html>

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



<script>
  // Get modal elements
  const infoModal = document.getElementById("infoModal");
  const viewButtons = document.querySelectorAll(".view-btn");
  const closeModalButtons = document.querySelectorAll(".close, .close-info");

  // Close buttons for info modal
  document.querySelectorAll(".close-info").forEach(el => {
    el.addEventListener("click", () => {
      infoModal.style.display = "none";
    });
  });

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

  // FOR Search
  document.getElementById('search_emp_input').addEventListener('keyup', function () {
    let query = this.value;
    let status = 'Approved'; // This will be for the approved page.

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "search_leave.php?query=" + encodeURIComponent(query) + "&status=" + encodeURIComponent(status), true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        document.getElementById('showdata').innerHTML = xhr.responseText;
      }
    };
    xhr.send();
  });

  //toast notification
  let toastBox = document.getElementById('toastBox');
  let successMess = '<i class="fa-solid fa-circle-check"></i>Leave approved successful!';

  function showToast(msg) {
      let toast = document.createElement('div'); 
      toast.classList.add('toast');
      toast.innerHTML = msg;
      toastBox.appendChild(toast); 

      if (msg.includes('error')) {
          toast.classList.add('error');
      }

      // Play notification sound
      const sound = document.getElementById('notifySound');
      if (sound) sound.play();

      setTimeout(() => {
          toast.remove();
      }, 3000);
  }
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('status') === 'success') {
      showToast(successMess);
      window.history.replaceState(null, null, window.location.pathname);
  }
</script>