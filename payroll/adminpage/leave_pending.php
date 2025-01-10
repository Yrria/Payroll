<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/leave.css">
    <title>Leave - Pending</title>
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
                    <h5><a href="./leave_pending.php">Leave-Pending </a></h5>
                </div>
                <hr>
            </div>
            <div class="main-content">
                <div class="sub-content">
                    <div class="content">

                        <!-- Title and search-bar -->
                        <div class="search">
                            <h3>Pending</h3>
                            <div class="search-bar">
                                <button class="search-btn">Search</button>
                                <input type="text" placeholder="Search employee..." />
                            </div>
                        </div>

                        <!-- Leave Table -->
                        <table>
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Subject</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Leave Type</th>
                                    <th>Message</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>001</td>
                                    <td>Manager</td>
                                    <td>Willy Wonka</td>
                                    <td>01-06-24</td>
                                    <td>Emergency Leave</td>
                                    <td class="td-text">I am feeling unwell and have been advised by my doctor to rest and recover at home
                                    </td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn">View Info</button>
                                            <button class="view-btn">Approve</button>
                                            <button class="view-btn">Decline</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>002</td>
                                    <td>Crew</td>
                                    <td>Ken Flerlage</td>
                                    <td>01-06-24</td>
                                    <td>Annual Leave</td>
                                    <td class="td-text">I am feeling unwell and have been advised by my doctor to rest and recover at home
                                    </td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn">View Info</button>
                                            <button class="view-btn">Approve</button>
                                            <button class="view-btn">Decline</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>003</td>
                                    <td>Crew</td>
                                    <td>George Orwell</td>
                                    <td>01-06-24</td>
                                    <td>Bereavement Leave</td>
                                    <td class="td-text">I am feeling unwell and have been advised by my doctor to rest and recover at home
                                    </td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn">View Info</button>
                                            <button class="view-btn">Approve</button>
                                            <button class="view-btn">Decline</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>004</td>
                                    <td>Crew</td>
                                    <td>Natalie Maines</td>
                                    <td>01-06-24</td>
                                    <td>Maternity Leave</td>
                                    <td class="td-text">I am feeling unwell and have been advised by my doctor to rest and recover at home
                                    </td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn">View Info</button>
                                            <button class="view-btn">Approve</button>
                                            <button class="view-btn">Decline</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>005</td>
                                    <td>Crew</td>
                                    <td>Hellen Mirren</td>
                                    <td>01-06-24</td>
                                    <td>Vacation Leave</td>
                                    <td class="td-text">I am feeling unwell and have been advised by my doctor to rest and recover at home
                                    </td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn">View Info</button>
                                            <button class="view-btn">Approve</button>
                                            <button class="view-btn">Decline</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <!-- Pagination -->
                        <div class="pagination">
                            <p>Showing 1 / 100 Results</p>
                            <div class="pagination">
                                <button>Prev</button>
                                <input type="text" class="perpage" value="1" readonly />
                                <button>Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
</body>

</html>

<div id="infoModal" class="modal">
  <div class="modal-content">
    <h2>Leave Info</h2>
    <hr>
    <div class="modal-details">
      <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
        <div style="width: 48%;">
          <label><strong>Leave Subject</strong></label>
          <input
            type="text"
            value="Leave for Medical Concerns"
            readonly
            style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;"
          />
        </div>
        <div style="width: 48%;">
          <label><strong>Status</strong></label>
          <input
            type="text"
            value="Pending"
            readonly
            style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;"
          />
        </div>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
        <div style="width: 48%;">
          <label><strong>Leave Date (MM/DD/YYYY)</strong></label>
          <input
            type="text"
            value="01/06/2024"
            readonly
            style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;"
          />
        </div>
        <div style="width: 48%;">
          <label><strong>Leave Type</strong></label>
          <input
            type="text"
            value="Medical Leave"
            readonly
            style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;"
          />
        </div>
      </div>
      <div style="margin-bottom: 10px;">
        <label><strong>Message</strong></label>
        <textarea
          readonly
          style="width: 100%; padding: 5px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; resize: none; height: 80px;"
        >I won't be able to come to work due to my medical concerns.
        </textarea>
      </div>
    </div>
    <!-- Back button -->
    <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
      <button class="close" style="padding: 8px 12px; background-color: black; color: white; border: none; border-radius: 4px; cursor: pointer;">
        Back
      </button>
    </div>
  </div>
</div>


<!-- Approve Modal -->
<div class="modal-overlay" id="approveModal">
    <div class="modal">
        <h2>APPROVE</h2>
        <p>Are you sure you want to Approve?</p>
        <div class="modal-buttons">
            <button class="yes-btn" onclick="approveAction()">YES</button>
            <button class="cancel-btn" onclick="closeApproveModal()">CANCEL</button>
        </div>
    </div>
</div>

<script>
    // Approve Modal Logic
    const approveModal = document.getElementById("approveModal");
    const approveButtons = document.querySelectorAll(".view-btn");

    approveButtons.forEach((button) => {
        if (button.textContent === "Approve") {
            button.addEventListener("click", () => {
                approveModal.style.display = "flex";
            });
        }
    });

    function closeApproveModal() {
        approveModal.style.display = "none";
    }

    function approveAction() {
        alert("Leave approved!");
        closeApproveModal();
    }
</script>


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
