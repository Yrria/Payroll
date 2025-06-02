<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

$records_per_page = 5;
$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

$search_query = '';
$where_clause = '';

// Search query logic
if (!empty($_GET['query'])) {
    $q = $conn->real_escape_string($_GET['query']);
    $search_query = "&query=" . urlencode($_GET['query']);

    $where_clause = "WHERE (
        emp_id       LIKE '%{$q}%'
        OR firstname  LIKE '%{$q}%'
        OR middlename LIKE '%{$q}%'
        OR lastname   LIKE '%{$q}%'
        OR email      LIKE '%{$q}%'
        OR phone_no   LIKE '%{$q}%'
        OR gender     LIKE '%{$q}%'
        OR status     LIKE '%{$q}%'
    )";
}

// Fetch additional info from tbl_emp_info outside the table loop
$emp_info = [];
$sql_info = "SELECT emp_id, shift, position, rate FROM tbl_emp_info";
$info_result = $conn->query($sql_info);

// Store the info in an associative array using emp_id as the key
while ($info = $info_result->fetch_assoc()) {
    $emp_info[$info['emp_id']] = [
        'shift' => $info['shift'] ?? 'N/A',
        'position' => $info['position'] ?? 'N/A',
        'rate' => $info['rate'] ?? 'N/A'
    ];
}

// Fetch employee data and display in the table
$sql = "SELECT * FROM tbl_emp_acc $where_clause LIMIT $start_from, $records_per_page";
$result = $conn->query($sql);

// Pagination count
$total_sql = "SELECT COUNT(*) FROM tbl_emp_acc $where_clause";
$total_rows = $conn->query($total_sql)->fetch_row()[0];
$total_pages = ceil($total_rows / $records_per_page);

// Pagination links
$pagination_links = '';
if ($total_pages > 1) {
    for ($i = 1; $i <= $total_pages; $i++) {
        $pagination_links .= "<a href='?page=$i$search_query'>$i</a> ";
    }
}

// Count all employees
$employee_total_result = $conn->query("SELECT COUNT(*) as total FROM tbl_emp_acc");
$total_employees = $employee_total_result->fetch_assoc()['total'] ?? 0;

// Count positions from tbl_emp_info
$position_counts = [
    'Manager' => 0,
    'Crew' => 0,
    'Server Crew' => 0
];

$position_query = "SELECT position, COUNT(*) as count FROM tbl_emp_info GROUP BY position";
$position_result = $conn->query($position_query);

while ($row = $position_result->fetch_assoc()) {
    $position = $row['position'];
    $count = $row['count'];

    if (array_key_exists($position, $position_counts)) {
        $position_counts[$position] = $count;
    }
}
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
                            <input type="text" id="total-employees" value="<?= $total_employees ?>" class="search-box1 employee-count-box" readonly>
                        </div>
                        <div class="department1-count-container">
                            <label for="position-manager" class="department-label" style="font-weight: 600;">Positions: </label>
                            <label for="position-manager" class="department-label">Manager:</label>
                            <input type="text" id="position-manager" value="<?= $position_counts['Manager'] ?>" class="search-box1 department-count-box" readonly>
                        </div>
                        <div class="department2-count-container">
                            <label for="crew-count" class="department-label">Crew:</label>
                            <input type="text" id="crew-count" value="<?= $position_counts['Crew'] ?>" class="search-box1 department-count-box" readonly>
                        </div>
                        <div class="department3-count-container">
                            <label for="server-crew-count" class="department-label">Server Crew:</label>
                            <input type="text" id="server-crew-count" value="<?= $position_counts['Server Crew'] ?>" class="search-box1 department-count-box" readonly>
                        </div>
                    </div>


                    <div class="leave-requests">
                        <h3>Records</h3>
                        <div class="search-filters">
                            <input type="text" placeholder="Search employee..." class="search-box">
                        </div>

                        <button class="add-new-btn"><span class="plus-icon">+</span>Add New</button>

                        <table id="leave-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Shift</th>
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
                                        $emp_id = $row['emp_id'];

                                        // Fetch additional info from tbl_emp_info using emp_id
                                        $info_sql = "SELECT shift, position, rate FROM tbl_emp_info WHERE emp_id = '$emp_id' LIMIT 1";
                                        $info_result = $conn->query($info_sql);
                                        $info = $info_result->fetch_assoc();

                                        $shift = $info['shift'] ?? 'N/A';
                                        $position = $info['position'] ?? 'N/A';
                                        $rate = $info['rate'] ?? 'N/A';

                                        if (!empty($row['m_name'])) {
                                            $fullname = $row['Firstname'] . " " . $row['middlename'] . ". " . $row['lastnamename'] . "";
                                        } else {
                                            $fullname = $row['firstname'] . " " . $row['lastname'];
                                        }

                                        $_SESSION['fullname'] = $fullname;
                                ?>
                                        <tr>
                                            <td><?php echo $row['emp_id'] ?></td>
                                            <td><?php echo htmlspecialchars($fullname) ?></td>
                                            <td><?php echo $position ?></td>
                                            <td><?php echo $shift ?></td>
                                            <td><?php echo $row['gender'] ?></td>
                                            <td><?php echo $row['email'] ?></td>
                                            <td><?php echo $row['phone_no'] ?></td>
                                            <td><?php echo $row['address'] ?></td>
                                            <td><?php echo $rate ?></td>
                                            <td class="td-text" style="color: <?php echo (strtolower(trim($row['status'])) === 'active') ? 'green' : 'red'; ?>; font-weight: 500;">
                                                <?php echo htmlspecialchars($row['status']); ?>
                                            </td>
                                            <td class="td-text">
                                                <div class="action-buttons">
                                                    <button class="view-btn">View Info</button>
                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

                        <br>
                        <!-- Pagination -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-right: 1.5%; padding-left: 1.5%;">
                            <p style="margin: 0;">Page <?= $current_page ?> out of <?= $total_pages ?></p>
                            <div class="pagination" id="content">
                                <?php if ($current_page > 1) : ?>
                                    <a href="?page=<?= ($current_page - 1) . $search_query; ?>" class="prev" style="border-radius:4px;background-color:#368DB8;color:white;margin-bottom:13px; padding: 10px;">&laquo; Previous</a>
                                <?php endif; ?>

                                <?php if ($current_page < $total_pages) : ?>
                                    <a href="?page=<?= ($current_page + 1) . $search_query; ?>" class="next" style="border-radius:4px;background-color:#368DB8;color:white;margin-bottom:13px; padding: 10px;">Next &raquo;</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- view-info Modal -->
    <div class="modal2-info-backdrop" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99;"></div>

    <div class="modal2-info" style="display: none;">
        <div class="profile-container2">
            <div class="employee-details4">
                <h3>First Name:</h3>
                <input type="text" class="search-boxs" readonly>
            </div>

            <div class="employee-details5">
                <h3>Middle Name:</h3>
                <input type="text" class="search-boxs" readonly>
            </div>

            <div class="employee-details6">
                <h3>Lastname:</h3>
                <input type="text" class="search-boxs" readonly>
            </div>
        </div>

        <div class="detail-container">
            <div class="detail-left">
                <h3>Contact Number:</h3>
                <input type="text" class="search-boxes" readonly>

                <h3>Bank Name:</h3>
                <input type="text" class="search-boxes" readonly>

                <h3>Email Address:</h3>
                <input type="text" class="search-boxes" readonly>
            </div>
            <div class="detail-right">
                <h3>Position:</h3>
                <select class="search-boxes" disabled>
                    <option>Crew</option>
                    <option>Manager</option>
                </select>

                <h3>Bank Account:</h3>
                <input type="text" class="search-boxes" readonly>

                <div class="inline-inputs">
                    <div class="pay-type-container">
                        <h3>Pay Type:</h3>
                        <input type="text" class="search-boxes pay-type-input" readonly>
                    </div>
                    <div class="rate-container">
                        <h3>Rate:</h3>
                        <input type="text" class="search-boxes rate-input" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="button-container">
            <button class="save_btn" id="add-employee">Save</button>
            <button class="back-btn" id="cancel-add">Back</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // When the user types in the search input
            $(".search-box").keyup(function() {
                let query = $(this).val(); // Get the search input value

                // Perform AJAX request based on search input
                $.ajax({
                    url: "emp_search.php",
                    method: "GET",
                    data: {
                        query: query
                    }, // Send the query to search
                    success: function(data) {
                        // Update the table with the search results or default data
                        $("#showdata").html(data);
                    }
                });
            });
        });
    </script>

    <!-- VIEW INFO -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const viewButtons = document.querySelectorAll(".view-btn");
            const modal = document.querySelector(".modal2-info");
            const modalBackdrop = document.querySelector(".modal2-info-backdrop");
            const backBtn = document.querySelector(".modal2-info .back-btn");

            viewButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const row = this.closest("tr");
                    const cells = row.querySelectorAll("td");

                    // Populate modal fields
                    const firstName = cells[0].textContent.trim();
                    const middleName = cells[1].textContent.trim();
                    const lastName = cells[2].textContent.trim();
                    const contact = cells[3].textContent.trim();
                    const bankName = cells[4].textContent.trim();
                    const email = cells[5].textContent.trim();
                    const position = cells[6].textContent.trim();
                    const bankAccount = cells[7].textContent.trim();
                    const payType = cells[8].textContent.trim();
                    const rate = cells[9].textContent.trim();

                    const inputs = modal.querySelectorAll("input.search-boxs, input.search-boxes, input.pay-type-input, input.rate-input");
                    const selects = modal.querySelectorAll("select.search-boxes");

                    inputs[0].value = firstName;
                    inputs[1].value = middleName;
                    inputs[2].value = lastName;
                    inputs[3].value = contact;
                    inputs[4].value = bankName;
                    inputs[5].value = email;
                    selects[0].value = position;
                    inputs[6].value = bankAccount;
                    inputs[7].value = payType;
                    inputs[8].value = rate;

                    // Show modal and backdrop
                    modal.style.display = "block";
                    modalBackdrop.style.display = "block";
                });
            });

            // Back button closes the modal
            backBtn.addEventListener("click", () => {
                modal.style.display = "none";
                modalBackdrop.style.display = "none";
            });

            // Click outside modal to close
            modalBackdrop.addEventListener("click", () => {
                modal.style.display = "none";
                modalBackdrop.style.display = "none";
            });
        });
    </script>


    <script src="./javascript/main.js"></script>
</body>

</html>