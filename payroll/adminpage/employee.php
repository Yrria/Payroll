<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

// Query to get max employee number
$sql = "SELECT MAX(CAST(emp_id AS UNSIGNED)) AS max_emp_num FROM tbl_emp_acc";

$result = $conn->query($sql);
$next_emp_id = "1";  // default if no employees yet

if ($result && $row = $result->fetch_assoc()) {
    $max_num = (int)$row['max_emp_num'];
    $next_num = $max_num + 1;
    // No padding, just the number itself
    $next_emp_id = (string)$next_num;
}

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="https://kit.fontawesome.com/3b07bc6295.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
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
                        <div class="search-filters" style="display: flex; align-items: center; margin-bottom: 20px;">
                            <form method="GET" action="" style="margin: 0;">
                                <input
                                    type="text"
                                    name="query"
                                    placeholder="Search employee..."
                                    class="search-box"
                                    style="height: 36px;"
                                    value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                            </form>
                            <button id="voiceSearchBtn" title="Speak to search"
                                style="margin-left: 8px; background: none; cursor: pointer; border: 0;">
                                <i class="bi bi-mic-fill" style="font-size: 1.35rem; color: #20242C;"></i>
                            </button>
                        </div>


                        <button class="add-new-btn" onclick="openAddModal()">
                            <span class="plus-icon">+</span>Add New
                        </button>

                        <table id="leave-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Shift</th>
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
                                        $info_sql = "SELECT shift, position, rate, pay_type FROM tbl_emp_info WHERE emp_id = '$emp_id' LIMIT 1";
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
                                            <td hidden><?php echo $row['gender'] ?></td>
                                            <td hidden><?php echo $row['email'] ?></td>
                                            <td hidden><?php echo $row['phone_no'] ?></td>
                                            <td hidden><?php echo $row['address'] ?></td>
                                            <td><?php echo $rate ?></td>
                                            <td hidden><?php echo $row['date_join'] ?></td>
                                            <td hidden><?php echo $info['pay_type'] ?></td>
                                            <td class="td-text" style="color: <?php echo (strtolower(trim($row['status'])) === 'active') ? 'green' : 'red'; ?>; font-weight: 500;">
                                                <?php echo ucfirst(strtolower(trim($row['status']))); ?>
                                            </td>
                                            <td class="td-text">
                                                <div class="action-buttons">
                                                    <button class="view-btn"
                                                        data-empid="<?php echo $row['emp_id']; ?>"
                                                        data-paytype="<?php echo htmlspecialchars($info['pay_type']); ?>"
                                                        data-rate="<?php echo htmlspecialchars($rate); ?>"
                                                        data-position="<?php echo htmlspecialchars($position); ?>"
                                                        data-gender="<?php echo htmlspecialchars($row['gender']); ?>"
                                                        data-shift="<?php echo htmlspecialchars($shift); ?>"
                                                        data-phone="<?php echo htmlspecialchars($row['phone_no']); ?>"
                                                        data-address="<?php echo htmlspecialchars($row['address']); ?>"
                                                        data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                                        data-joindate="<?php echo htmlspecialchars(date("F j, Y", strtotime($row['date_join']))); ?>"
                                                        data-fullname="<?php echo htmlspecialchars($fullname); ?>"
                                                        data-status="<?php echo htmlspecialchars($row['status']); ?>"
                                                        onclick="openModal(this)">View Info</button>

                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='att-cell' style='text-align:center;'>No attendance records found.</td></tr>";
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

    <!-- Modal -->
    <div class="modal-overlay" id="employeeModal">
        <div class="modal">
            <div class="modal-header">
                <img src="../assets/avatar.png" alt="Avatar" class="profile-img" />
                <div class="profile-name" id="modal-fullname">Marc Andrei A. Toledo</div>
            </div>

            <div class="modal-grid">
                <div class="form-group">
                    <label>Employee Id</label>
                    <input type="text" disabled id="modal-empid" value="EMP0001">
                </div>
                <div class="form-group">
                    <label>Pay Type</label>
                    <input type="text" disabled id="modal-paytype" value="Cash">
                </div>

                <div class="form-group">
                    <label>Rate</label>
                    <input type="text" readonly id="modal-rate" value="">
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <select id="modal-position">
                        <option>Crew</option>
                        <option>Server Crew</option>
                        <option>Manager</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <input type="text" disabled id="modal-gender" value="Male">
                </div>
                <div class="form-group">
                    <label>Shift</label>
                    <select id="modal-shift">
                        <option>Morning</option>
                        <option>Night</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" disabled id="modal-phone" value="09483746672">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" disabled id="modal-address" value="10039856754894">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="text" disabled id="modal-email" value="johndoe@gmail.com">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <input type="text" disabled id="modal-status" value="June 15, 2020">
                </div>
                <div class="form-group">
                    <label>Joining Date</label>
                    <input type="text" disabled id="modal-joindate" value="June 15, 2020">
                </div>
            </div>

            <div class="modal-actions">
                <button class="btn-save" onclick="saveInfo()">Save</button>
                <button class="btn-back" onclick="closeModal()">Back</button>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div class="modal-overlay" id="addEmployeeModal">
        <div class="modal">
            <div class="modal-header">
                <img src="../assets/avatar.png" alt="Avatar" class="profile-img" />
                <div class="profile-name">Add New Employee</div>
            </div>

            <div class="name-row">
                <div class="name-group">
                    <label>First Name</label>
                    <input type="text" id="add-fname" placeholder="Enter Firstname" required>
                </div>
                <div class="name-group">
                    <label>Middle Name</label>
                    <input type="text" id="add-mname" placeholder="Enter Middlename" required>
                </div>
                <div class="name-group">
                    <label>Last Name</label>
                    <input type="text" id="add-lname" placeholder="Enter Lastname" required>
                </div>
            </div>
            <div class="modal-grid">
                <div class="form-group">
                    <label>Employee Id</label>
                    <input type="text" id="add-empid" disabled value="<?php echo htmlspecialchars($next_emp_id); ?>">
                </div>
                <div class="form-group">
                    <label>Pay Type</label>
                    <input type="text" id="add-paytype" value="Cash" disabled>
                </div>
                <div class="form-group">
                    <label>Rate</label>
                    <input type="text" id="add-rate" placeholder="- - -" disabled>
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <select id="add-position" onchange="updateRate()" required>
                        <option value="">Select Position</option>
                        <option value="Crew">Crew</option>
                        <option value="Server Crew">Server Crew</option>
                        <option value="Manager">Manager</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <select id="add-gender">
                        <option value="" required>Select Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Shift</label>
                    <select id="add-shift" onchange="updateRate()" required>
                        <option value="">Select Shift</option>
                        <option value="Morning">Morning</option>
                        <option value="Night">Night</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" id="add-phone" maxlength="11" placeholder="e.g. 09XXXXXXXXX" oninput="validatePhone(this)" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" id="add-address" placeholder="Enter full address" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" id="add-email" placeholder="e.g. email@example.com" required>
                </div>
                <div class="form-group">
                    <label>Joining Date</label>
                    <input type="text" id="add-joindate" disabled>
                </div>
            </div>

            <div class="modal-actions">
                <button class="btn-save" onclick="addNewEmployee()">Add</button>
                <button class="btn-back" onclick="closeAddModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Rate mapping
        const rates = {
            "Crew": 520,
            "Server Crew": 570,
            "Manager": 720
        };

        const positionSelect = document.getElementById("modal-position");
        const shiftSelect = document.getElementById("modal-shift");
        const rateInput = document.getElementById("modal-rate");

        let originalValues = {
            position: '',
            shift: '',
            rate: ''
        };

        // Calculate rate based on position and shift
        function calculateRate(position, shift) {
            let baseRate = rates[position] || 0;
            if (shift === "Night") {
                baseRate *= 1.10;
            }
            return baseRate.toFixed(2);
        }

        // Update rate field when position or shift changes
        function viewupdateRate() {
            const position = positionSelect.value;
            const shift = shiftSelect.value;
            rateInput.value = calculateRate(position, shift);
        }

        // Store original modal values when it's opened
        function storeOriginalValues() {
            originalValues = {
                position: positionSelect.value,
                shift: shiftSelect.value,
                rate: rateInput.value
            };
        }

        // Add listeners to update rate on dropdown change
        document.addEventListener("DOMContentLoaded", () => {
            positionSelect.addEventListener("change", viewupdateRate);
            shiftSelect.addEventListener("change", viewupdateRate);
        });

        // Call this after opening modal and filling in data
        function openEmployeeModal() {
            viewupdateRate(); // Recalculate rate
            storeOriginalValues(); // Capture initial state
            document.getElementById("employeeModal").style.display = "block";
        }

        // Save button handler
        function saveInfo() {
            const newPosition = positionSelect.value;
            const newShift = shiftSelect.value;
            const newRate = rateInput.value;

            // Compare current with original values
            const hasChanged =
                newPosition !== originalValues.position ||
                newShift !== originalValues.shift ||
                newRate !== originalValues.rate;

            if (!hasChanged) {
                alert("No changes detected.");
                return;
            }

            const confirmed = confirm(`You're about to update the employee's info:\n\nPosition: ${newPosition}\nShift: ${newShift}\nRate: ${newRate}\n\nDo you want to continue?`);
            if (!confirmed) return;

            const empId = document.getElementById("modal-empid").value;

            fetch("update_employee.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        empId: empId,
                        position: newPosition,
                        shift: newShift,
                        rate: newRate
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("Employee record updated successfully.");
                        location.reload(); // Refresh to reflect changes
                    } else {
                        alert("Update failed: " + data.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("An error occurred while updating.");
                });
        }

        // Optional: modal close
        function closeModal() {
            document.getElementById("employeeModal").style.display = "none";
        }


        // EMP ADD EMP_ID
        function generateNextEmpId() {
            fetch("employee.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        getNextEmpId: '1'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById("modal-empid").value = data.nextEmpId;
                    } else {
                        alert("Failed to generate new Employee ID.");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Error fetching new Employee ID.");
                });
        }

        // ADD EMP SCRIPT
        // Auto-set today's date in joining date field
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const formattedDate = today.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('add-joindate').value = formattedDate;
        });

        // Position → Base Rate mapping
        const baseRates = {
            "Crew": 520,
            "Server Crew": 570,
            "Manager": 720
        };

        // Update rate when position or shift changes
        function updateRate() {
            const position = document.getElementById('add-position').value;
            const shift = document.getElementById('add-shift').value;
            const rateInput = document.getElementById('add-rate');

            if (baseRates[position]) {
                let rate = baseRates[position];
                if (shift === "Night") {
                    rate += rate * 0.10; // Add 10%
                }
                rateInput.value = rate.toFixed(2);
            } else {
                rateInput.value = '';
            }
        }

        // Validate contact number
        function validatePhone(input) {
            input.value = input.value.replace(/[^0-9]/g, '').slice(0, 11);
        }

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

        //    LIVE SEARCH
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

        // VIEW Modal
        function openModal() {
            document.getElementById('employeeModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('employeeModal').style.display = 'none';
        }

        // function saveInfo() {
        //     alert('Information saved!');
        //     closeModal();
        // }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('employeeModal');
            if (e.target === modal) {
                closeAddModal();
            }
        });

        // MODAL INPUT VIEW DETAILS
        function openModal(button) {
            document.getElementById('modal-fullname').textContent = button.getAttribute('data-fullname');
            document.getElementById('modal-empid').value = button.getAttribute('data-empid');
            document.getElementById('modal-paytype').value = button.getAttribute('data-paytype');
            document.getElementById('modal-rate').value = button.getAttribute('data-rate');
            document.getElementById('modal-position').value = button.getAttribute('data-position');
            document.getElementById('modal-gender').value = button.getAttribute('data-gender');
            document.getElementById('modal-shift').value = button.getAttribute('data-shift');
            document.getElementById('modal-phone').value = button.getAttribute('data-phone');
            document.getElementById('modal-address').value = button.getAttribute('data-address');
            document.getElementById('modal-email').value = button.getAttribute('data-email');
            document.getElementById('modal-joindate').value = button.getAttribute('data-joindate');
            document.getElementById('modal-status').value = button.getAttribute('data-status');

            // Show modal with flex for centering
            document.getElementById('employeeModal').style.display = 'flex';
        }


        function closeModal() {
            document.getElementById('employeeModal').style.display = 'none';
        }


        // ADD NEW EMP
        function openAddModal() {
            document.getElementById('addEmployeeModal').style.display = 'flex';
        }

        function closeAddModal() {
            document.getElementById('addEmployeeModal').style.display = 'none';
        }
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('addEmployeeModal');
            if (e.target === modal) {
                closeAddModal();
            }
        });
    </script>

    <!-- FOR INSERT DATA -->
    <script>
        function addNewEmployee() {
            // Get values
            const fname = document.getElementById('add-fname').value.trim();
            const mname = document.getElementById('add-mname').value.trim();
            const lname = document.getElementById('add-lname').value.trim();
            const email = document.getElementById('add-email').value.trim();
            const address = document.getElementById('add-address').value.trim();
            const phone = document.getElementById('add-phone').value.trim();
            const gender = document.getElementById('add-gender').value;
            const rate = document.getElementById('add-rate').value.trim();
            const position = document.getElementById('add-position').value;
            const shift = document.getElementById('add-shift').value;

            // Confirmation
            if (!fname || !lname || !email || !address || !phone || !gender || !position || !shift) {
                alert("Please fill all required fields.");
                return;
            }

            if (confirm("Are you sure you want to add this employee?")) {
                // Send AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "insert_employee.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                const params = `fname=${encodeURIComponent(fname)}&mname=${encodeURIComponent(mname)}&lname=${encodeURIComponent(lname)}&email=${encodeURIComponent(email)}&address=${encodeURIComponent(address)}&phone=${encodeURIComponent(phone)}&gender=${encodeURIComponent(gender)}&rate=${encodeURIComponent(rate)}&position=${encodeURIComponent(position)}&shift=${encodeURIComponent(shift)}`;

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(xhr.responseText);
                        closeAddModal(); // Optional: close modal on success
                        location.reload(); // Optional: reload to reflect changes
                    }
                };
                xhr.send(params);
            }
        }


        // sweetalert sa voice search
        document.addEventListener("DOMContentLoaded", () => {
            const voiceBtn = document.getElementById("voiceSearchBtn"); // use id= "voiceSearchBtn"
            const searchInput = document.querySelector(".search-box"); // use class= "search-box"

            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            if (SpeechRecognition && voiceBtn && searchInput) {
                const recognition = new SpeechRecognition();
                recognition.continuous = false;
                recognition.lang = "en-US";

                voiceBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    recognition.start();
                    voiceBtn.innerHTML = `<i class="bi bi-mic-mute-fill" style="color: red;"></i>`;

                    Swal.fire({
                        title: 'Listening...',
                        text: 'Please speak now',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });
                });

                recognition.onresult = function(event) {
                    const transcript = event.results[0][0].transcript;
                    searchInput.value = transcript;
                    // Submit the form after voice input
                    if (searchInput.form) {
                        searchInput.form.submit();
                    }
                    recognition.stop();
                    voiceBtn.innerHTML = `<i class="bi bi-mic-fill"></i>`;
                    Swal.close();
                };

                recognition.onerror = function(event) {
                    console.error("Voice recognition error:", event.error);
                    recognition.stop();
                    voiceBtn.innerHTML = `<i class="bi bi-mic-fill"></i>`;
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Voice recognition error: ' + event.error,
                        timer: 2000,
                        showConfirmButton: false,
                    });
                };

                recognition.onend = function() {
                    voiceBtn.innerHTML = `<i class="bi bi-mic-fill"></i>`;
                    Swal.close();
                };
            } else if (!SpeechRecognition) {
                alert("Sorry, your browser does not support voice recognition.");
            }
        });
    </script>


    <script src="./javascript/main.js"></script>
</body>

</html>