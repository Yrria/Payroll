<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

$records_per_page = 5;
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

// Build the base SELECT
$sql = "SELECT * FROM tbl_emp_acc";

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
    <link rel="stylesheet" href="./css/employee.css">
    <title>Employee</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Employee</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                    <span> > </span>
                    <h5><a href="./attendance.php">Attendance </a></h5>
                </div>
                <hr>
            </div>

            <div class="main-content">
                <div class="sub-content">
                    <div class="file-import-container">
                        <!-- Statistics Display -->
                        <div class="departments-count-container">
                            <label for="total-employees" class="employee-label">Total Employees:</label>
                            <input type="text" id="total-employees" value="43" class="search-box1 employee-count-box" readonly>
                        </div>
                        <div class="department1-count-container">
                            <label for="position-manager" class="department-label">Position Manager:</label>
                            <input type="text" id="position-manager" value="5" class="search-box1 department-count-box" readonly>
                        </div>
                        <div class="department2-count-container">
                            <label for="crew-count" class="department-label">Crew:</label>
                            <input type="text" id="crew-count" value="5" class="search-box1 department-count-box" readonly>
                        </div>
                        <div class="department3-count-container">
                            <label for="server-crew-count" class="department-label">Server Crew:</label>
                            <input type="text" id="server-crew-count" value="5" class="search-box1 department-count-box" readonly>
                        </div>
                    </div>

                    <div class="leave-requests">
                        <h3>Records</h3>
                        <div class="search-filters">
                            <input type="text" placeholder="Search employee..." class="search-box">
                            <button class="search-btn">Search</button>
                        </div>

                        <button class="add-new-btn"><span class="plus-icon">+</span>Add New</button>

                        <table id="leave-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Phone No.</th>
                                    <th>Address</th>
                                    <th>Rate Per Day</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="showdata">
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        if (!empty($row['m_name'])) {
                                            $fullname = $row['l_name'] . " , " . $row['f_name'] . " , " . $row['m_name'] . ".";
                                            $_SESSION['fullname'] = $fullname;
                                        } else {
                                            $fullname = $row['l_name'] . " , " . $row['f_name'];
                                            $_SESSION['fullname'] = $fullname;
                                        }
                                ?>
                                        <tr>
                                            <td><?php echo $row['emp_id'] ?></td>
                                            <td><?php echo htmlspecialchars($fullname) ?></td>
                                            <td><?php echo $row['position'] ?></td>
                                            <td><?php echo $row['emp_shift'] ?></td>
                                            <td><?php echo $row['basic_pay'] ?></td>
                                            <td class="td-text"><?php echo $row['status'] ?></td>
                                            <td class="td-text">
                                                <div class="action-buttons">
                                                    <a href='./create_payslip.php?id=<?php echo $row["emp_id"]; ?>'><button class="slip-btn">Generate Slip</button></a>
                                                    <button class="view-btn">Summary</button>
                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

                        <div class="pagination">
                            <p class="results-info">1/100 Results</p>
                            <div class="pagination-controls">
                                <button class="prev-btn">Prev</button>
                                <input type="number" class="page-number" value="1" min="1" max="10">
                                <button class="next-btn">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Backdrop for Viewing Info -->
    <div class="modal-backdrop"></div>
    <div class="modal">
        <div class="profile-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTSLU5_eUUGBfxfxRd4IquPiEwLbt4E_6RYMw&s" alt="Profile Picture">
            <div class="profile-details">
                <h1 id="modal-employee-name">Michael Tan</h1>
            </div>
            <div class="employee-details1">
                <h3>Employee Id:</h3>
                <input type="text" placeholder="Input ID..." class="search-box">
            </div>
            <div class="employee-details2">
                <h3>Pay Type:</h3>
                <input type="text" placeholder="Input Pay Type..." class="search-box">
            </div>
            <div class="employee-details3">
                <h3>Rate:</h3>
                <input type="text" placeholder="Input Rate..." class="search-box">
            </div>
        </div>

        <div class="details-container">
            <div class="details-left">
                <h3>Contact Number:</h3>
                <input type="text" placeholder="Input Number..." class="search-box">

                <h3>Bank Name:</h3>
                <input type="text" placeholder="Input Name..." class="search-box">

                <h3>Email Address:</h3>
                <input type="text" placeholder="Input Your Email..." class="search-box">
            </div>
            <div class="details-right">
                <h3>Position:</h3>
                <select class="search-box">
                    <option>Crew</option>
                    <option>Manager</option>
                </select>

                <h3>Bank Account:</h3>
                <input type="text" placeholder="Input Account Number..." class="search-box">

                <h3>Joining Date:</h3>
                <input type="date" placeholder="Input Date..." class="search-box">
            </div>
        </div>

        <div class="button-container">
            <button class="back-btn" id="save-employee">Save</button>
            <button class="back-btn" id="close-view-modal">Back</button>
        </div>
    </div>


    <!-- Confirmation Modal -->
    <div class="confirm-modal2">
        <div class="confirm-content">
            <h2 class="confirm-title">CONFIRM</h2>
            <p>Are you sure you want to update employee info?</p>
            <div class="confirm-button-container">
                <button class="yes-btn" id="save-employee">Yes</button>
                <button class="cancel-btn" id="close-view-modal">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="success-modal2">
        <div class="success-content">
            <img src="https://img.icons8.com/color/48/000000/checkmark.png" alt="Success Icon" class="success-icon" />
            <p class="success-message">Employee info updated successfully!</p>
            <div class="success-button-container">
                <button class="close-btn" id="close-success">Close</button>
            </div>
        </div>
    </div>



    <!-- Modal Backdrop for Adding Employee -->
    <div class="modal-backdrop2"></div>
    <!-- Add New Modal -->
    <div class="modal2">
        <div class="profile-container2">
            <div class="employee-details4">
                <h3>First Name:</h3>
                <input type="text" placeholder="Input ID..." class="search-boxs">
            </div>

            <div class="employee-details5">
                <h3>Middle Name:</h3>
                <input type="text" placeholder="Input Pay Type..." class="search-boxs">
            </div>

            <div class="employee-details6">
                <h3>Lastname:</h3>
                <input type="text" placeholder="Input Rate..." class="search-boxs">
            </div>
        </div>
        <div class="detail-container">
            <div class="detail-left">
                <h3>Contact Number:</h3>
                <input type="text" placeholder="Input Number..." class="search-boxes">

                <h3>Bank Name:</h3>
                <input type="text" placeholder="Input Name..." class="search-boxes">

                <h3>Email Address:</h3>
                <input type="text" placeholder="Input Your Email..." class="search-boxes">
            </div>
            <div class="detail-right">
                <h3>Position:</h3>

                <select class="search-boxes">
                    <option>Crew</option>
                    <option>Manager</option>
                </select>

                <h3>Bank Account:</h3>
                <input type="text" placeholder="Input Account Number..." class="search-boxes">

                <div class="inline-inputs">
                    <div class="pay-type-container">
                        <h3>Pay Type:</h3>
                        <input type="text" placeholder="Input Pay Type..." class="search-boxes pay-type-input">
                    </div>
                    <div class="rate-container">
                        <h3>Rate:</h3>
                        <input type="text" placeholder="Input Rate..." class="search-boxes rate-input">
                    </div>
                </div>
            </div>
        </div>

        <div class="button-container">
            <button class="back-btn" id="add-employee">Add</button>
            <button class="back-btn" id="cancel-add">Cancel</button>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="confirm-modal">
        <div class="confirm-content">
            <h2 class="confirm-title">CONFIRM</h2>
            <p>Are you sure you want to add a new employee?</p>
            <div class="confirm-button-container">
                <button class="yes-btn" id="confirm-add">Yes</button>
                <button class="cancel-btn" id="cancel-confirm">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="success-modal">
        <div class="success-content">
            <img src="https://img.icons8.com/color/48/000000/checkmark.png" alt="Success Icon" class="success-icon" />
            <p class="success-message">Employee added successfully!</p>
            <div class="success-button-container">
                <button class="close-btn" id="closes-success">Close</button>
            </div>
        </div>
    </div>


    <!-- SCRIPT -->
    <script>
        const addNewButton = document.querySelector('.add-new-btn');
        const modal2 = document.querySelector('.modal2');
        const backdrop2 = document.querySelector('.modal-backdrop2');
        const cancelAddButton = document.getElementById('cancel-add');

        const viewInfoButtons = document.querySelectorAll('.view-info');
        const modal = document.querySelector('.modal');
        const modalBackdrop = document.querySelector('.modal-backdrop');

        // Show modal2 when "Add New" button is clicked
        addNewButton.addEventListener('click', () => {
            modal2.style.display = 'flex';
            backdrop2.style.display = 'block';
        });

        // Close modal2 when "Cancel" button is clicked
        cancelAddButton.addEventListener('click', () => {
            modal2.style.display = 'none';
            backdrop2.style.display = 'none';
        });

        // Close modal2 on backdrop click
        backdrop2.addEventListener('click', () => {
            modal2.style.display = 'none';
            backdrop2.style.display = 'none';
        });

        // Show modal when "View Info" button is clicked
        viewInfoButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.style.display = 'flex';
                modalBackdrop.style.display = 'block';
            });
        });

        // Close modal when "Back" button is clicked for the modal
        const closeModalButtons = document.querySelectorAll('#close-view-modal, #save-employee');
        closeModalButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.style.display = 'none';
                modalBackdrop.style.display = 'none';
            });
        });

        // Close modal on backdrop click
        modalBackdrop.addEventListener('click', () => {
            modal.style.display = 'none';
            modalBackdrop.style.display = 'none';
        });

        // Pagination Logic
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        const resultsInfo = document.querySelector('.results-info');
        let currentPage = 1;
        const totalPages = 10; // Replace this with the actual number of pages

        function updateResultsDisplay() {
            resultsInfo.textContent = `Showing ${(currentPage - 1) * 1 + 1}/100 Results`; // Example
            document.querySelector('.page-number').value = currentPage;
        }

        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updateResultsDisplay();
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                updateResultsDisplay();
            }
        });

        // Initial display
        updateResultsDisplay();
    </script>
    <script>
        // Your existing JavaScript logic
        const confirmModal = document.querySelector('.confirm-modal');
        const confirmAddButton = document.getElementById('confirm-add');
        const cancelConfirmButton = document.getElementById('cancel-confirm');
        const successModal = document.querySelector('.success-modal');
        const closeSuccessButton = document.getElementById('closes-success');


        document.getElementById('add-employee').addEventListener('click', () => {
            confirmModal.style.display = 'flex';
        });

        confirmAddButton.addEventListener('click', () => {
            confirmModal.style.display = 'none';
            successModal.style.display = 'flex'; // Show success modal
        });

        cancelConfirmButton.addEventListener('click', () => {
            confirmModal.style.display = 'none';
        });

        closeSuccessButton.addEventListener('click', () => {
            successModal.style.display = 'none'; // Close success modal
        });

        const confirmModal2 = document.querySelector('.confirm-modal2'); // Confirm modal for updating employee info
        const successModal2 = document.querySelector('.success-modal2'); // Success modal for employee update
        const saveEmployeeButton = document.getElementById('save-employee'); // Save button

        // Trigger confirmation modal when the Save button is clicked
        saveEmployeeButton.addEventListener('click', () => {
            confirmModal2.style.display = 'flex'; // Show confirmation modal
        });

        // If the user confirms the update
        document.querySelector('.yes-btn').addEventListener('click', () => {
            confirmModal2.style.display = 'none'; // Close the confirmation modal
            successModal2.style.display = 'flex'; // Show success modal
        });

        // If the user cancels the update
        document.querySelector('.cancel-btn').addEventListener('click', () => {
            confirmModal2.style.display = 'none'; // Close the confirmation modal
        });

        // Close the success modal
        document.getElementById('close-success').addEventListener('click', () => {
            successModal2.style.display = 'none'; // Close success modal
        });
    </script>


    <script src="./javascript/main.js"></script>
</body>

</html>