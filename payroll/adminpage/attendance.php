<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

$records_per_page = 5; // Number of records to display per page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page number, default to 1

// Calculate the limit clause for SQL query
$start_from = ($current_page - 1) * $records_per_page;

// Initialize variables
$sql = "SELECT * FROM tbl_attendance ";

// Check if search query is provided
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $search_query = $_GET['query'];
    // Modify SQL query to include search filter
    $sql .= "WHERE emp_id LIKE '%$search_query%' 
            OR emp_id LIKE '%$search_query%' 
            OR attendance_id LIKE '%$search_query%' 
            OR emp_name LIKE '%$search_query%'
            OR emp_shift LIKE '%$search_query%'
            OR position_name LIKE '%$search_query%'";
}

$sql .= "LIMIT $start_from, $records_per_page";

$result = $conn->query($sql);

// Count total number of records
$total_records_query = "SELECT COUNT(*) FROM tbl_attendance";
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $total_records_query .= "WHERE emp_id LIKE '%$search_query%' 
            OR emp_id LIKE '%$search_query%' 
            OR attendance_id LIKE '%$search_query%' 
            OR emp_name LIKE '%$search_query%'
            OR emp_shift LIKE '%$search_query%'
            OR position_name LIKE '%$search_query%'";
}

$total_records_result = mysqli_query($conn, $total_records_query);
$total_records_row = mysqli_fetch_array($total_records_result);
$total_records = $total_records_row[0];

$total_pages = ceil($total_records / $records_per_page);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/attendance.css">
    <link rel="stylesheet" href="./css/payroll.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <title>Attendance</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Attendance</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                    <span> > </span>
                    <h5><a href="./attendance.php">Attendance </a></h5>
                </div>
                <hr>
            </div>

            <div class="main-content">
                <div class="sub-content">
                    <!-- Separate Container for Choose File and Import Button
                    <div class="file-import-container">
                        <input type="text" placeholder="No File chosen..." class="search-box">
                        <label for="file-upload" class="file-upload">
                            Choose File
                            <input type="file" id="file-upload" class="file-input">
                        </label>

                        <button class="import-btn">
                            <span class="plus-icon">+</span> Import
                        </button>
                    </div> -->

                    <!-- Records Section -->
                    <div class="leave-requests">
                        <div class="selection_div">
                            <p style="margin: 0;font-weight: 500;">Records</p>
                            <div style="display: flex;align-items: center;width:60%;justify-content:right;margin-right:-4%;">
                                <div class="dropdown month-dropdown" style="margin-left: 20%;">
                                    <div class="dropdown-wrapper">
                                        <input type="text" class="dropdown-input" style="width:75%;" readonly placeholder="Select Month" />
                                        <div class="dropdown-indicator">&#9662;</div>
                                        <div class="dropdown-content">
                                            <div class="dropdown-item clear-selection" data-value="" style="opacity: 0.5;">Select Month</div>
                                            <div class="dropdown-item" data-value="01" style="font-size: 14px;">January</div>
                                            <div class="dropdown-item" data-value="02" style="font-size: 14px;">February</div>
                                            <div class="dropdown-item" data-value="03" style="font-size: 14px;">March</div>
                                            <div class="dropdown-item" data-value="04" style="font-size: 14px;">April</div>
                                            <div class="dropdown-item" data-value="05" style="font-size: 14px;">May</div>
                                            <div class="dropdown-item" data-value="06" style="font-size: 14px;">June</div>
                                            <div class="dropdown-item" data-value="07" style="font-size: 14px;">July</div>
                                            <div class="dropdown-item" data-value="08" style="font-size: 14px;">August</div>
                                            <div class="dropdown-item" data-value="09" style="font-size: 14px;">September</div>
                                            <div class="dropdown-item" data-value="10" style="font-size: 14px;">October</div>
                                            <div class="dropdown-item" data-value="11" style="font-size: 14px;">November</div>
                                            <div class="dropdown-item" data-value="12" style="font-size: 14px;">December</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown year-dropdown" style="width:25%;">
                                    <div class="dropdown-wrapper">
                                        <input type="text" class="dropdown-input" style="width:75%;" readonly placeholder="Select Year" />
                                        <div class="dropdown-indicator" style="right:47px;">&#9662;</div>
                                        <div class="dropdown-content">
                                            <div class="dropdown-item clear-selection" data-value="" style="opacity: 0.5;">Select Year</div>
                                            <div class="dropdown-item" data-value="2024" style="font-size: 14px;">2024</div>
                                            <div class="dropdown-item" data-value="2023" style="font-size: 14px;">2023</div>
                                            <div class="dropdown-item" data-value="2022" style="font-size: 14px;">2022</div>
                                            <div class="dropdown-item" data-value="2021" style="font-size: 14px;">2021</div>
                                            <div class="dropdown-item" data-value="2020" style="font-size: 14px;">2020</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown cutoff-dropdown">
                                    <div class="dropdown-wrapper">
                                        <input type="text" class="dropdown-input" readonly placeholder="Select Cutoff" />
                                        <div class="dropdown-indicator">&#9662;</div>
                                        <div class="dropdown-content">
                                            <div class="dropdown-item clear-selection" data-value="" style="opacity: 0.5;">Select Cutoff</div>
                                            <div class="dropdown-item" data-value="F" style="font-size: 14px;">First Cutoff</div>
                                            <div class="dropdown-item" data-value="S" style="font-size: 14px;">Second Cutoff</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table id="leave-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Attendance ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Shift</th>
                                    <th>Total Present</th>
                                    <th>Total Hours</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="showdata">
                                <!-- Add more rows as needed for demonstration -->
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

                                        $acc_sql = "SELECT * FROM tbl_emp_acc WHERE emp_id = '$emp_id' LIMIT 1";
                                        $acc_result = $conn->query($acc_sql);
                                        $account = $acc_result->fetch_assoc();

                                        if (!empty($account['m_name'])) {
                                            $fullname = $account['Firstname'] . " " . $account['middlename'] . ". " . $account['lastnamename'] . "";
                                        } else {
                                            $fullname = $account['firstname'] . " " . $account['lastname'];
                                        }

                                        $_SESSION['fullname'] = $fullname;
                                ?>
                                        <tr>
                                            <td><?php echo $row['emp_id'] ?></td>
                                            <td><?php echo $row['attendance_id'] ?></td>
                                            <td><?php echo htmlspecialchars($fullname) ?></td>
                                            <td><?php echo $position ?></td>
                                            <td><?php echo $shift ?></td>
                                            <td><?php echo $row['present_days'] ?></td>
                                            <td><?php echo $row['hours_present'] ?></td>
                                            <td class="td-text">
                                                <div class="action-buttons" style="max-width: 100%;">
                                                    <a href="#"><button class="slip-btn" style="width: 100px;height:40px;">View info</button></a>
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
                                    <a href="?page=<?= ($current_page - 1); ?>&query=<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>" class="prev" style="border-radius:4px;background-color:#368DB8;color:white;margin-bottom:13px; padding: 10px;">&laquo; Previous</a>
                                <?php endif; ?>

                                <?php if ($current_page < $total_pages) : ?>
                                    <a href="?page=<?= ($current_page + 1); ?>&query=<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>" class="next" style="border-radius:4px;background-color:#368DB8;color:white;margin-bottom:13px; padding: 10px;">Next &raquo;</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Backdrop -->
    <div class="modal-backdrop"></div>
    <div class="modal">
        <div class="count-container" class="search-box">
            <label for="total-employees" class="employee-label">Cutt Off 2:</label>
            <input type="text" id="total-employees" value="November 20, 2024" readonly>
        </div>

        <div class="profile-container">
            <img src="https://images.rawpixel.com/image_png_800/cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDIzLTAxL3JtNjA5LXNvbGlkaWNvbi13LTAwMi1wLnBuZw.png" alt="Profile Picture">
            <h1>Michael Tan</h1>

            <!-- Info Table beside the Profile -->
            <div class="info-table">
                <table id="leave-table">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Position</th>
                            <th>Shift</th>
                            <th>Total Present</th>
                            <th>Total Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="pending-leave">
                            <td>Emp 001</td>
                            <td>Crew</td>
                            <td>Day</td>
                            <td>20</td>
                            <td>160</td>
                        </tr>
                        <!-- Add more rows as needed for demonstration -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="table-container">
            <table id="leave-summary">
                <thead>
                    <tr>
                        <th>Total Days</th>
                        <th>Working Days</th>
                        <th>Leave</th>
                        <th>Holiday</th>
                        <th>Present But Late</th>
                        <th>Absent</th>
                        <th>Total Late Hour</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="pending-leave">
                        <td>20</td>
                        <td>20</td>
                        <td>2</td>
                        <td>3</td>
                        <td>5</td>
                        <td>5</td>
                        <td>5</td>
                    </tr>
                    <!-- Add more rows as needed for demonstration -->
                </tbody>
            </table>
        </div>

        <button class="back-btn" id="close-modal">Back</button>
    </div>

    <!-- SCRIPT -->
    <script>
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        const pageNumberInput = document.querySelector('.page-number');
        const resultsInfo = document.querySelector('.results-info');
        const viewInfoButtons = document.querySelectorAll('.view-info');
        const modal = document.querySelector('.modal');
        const modalBackdrop = document.querySelector('.modal-backdrop');
        const closeModalButton = document.getElementById('close-modal');

        let currentPage = 1;
        const totalPages = 10; // Replace this with the actual number of pages

        // Function to update results display
        function updateResultsDisplay() {
            resultsInfo.textContent = `Showing ${(currentPage - 1) * 1 + 1}/100 Results`; // Example adjusting based on items per page
            pageNumberInput.value = currentPage;
        }

        // Event listeners for pagination buttons
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

        // Show modal when "View Info" button is clicked
        viewInfoButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.style.display = 'flex'; // Show the modal
                modalBackdrop.style.display = 'block'; // Show the backdrop
            });
        });

        // Close modal when "Back" button or backdrop is clicked
        closeModalButton.addEventListener('click', closeModal);
        modalBackdrop.addEventListener('click', closeModal);

        function closeModal() {
            modal.style.display = 'none'; // Hide the modal
            modalBackdrop.style.display = 'none'; // Hide the backdrop
        }

        // Initial display
        updateResultsDisplay();
    </script>
    <script>
        $(document).ready(function() {
            function fetchPayroll() {
                var name = $("#search_emp_input").val();
                var month = $(".month-dropdown .dropdown-input").val();
                var year = $(".year-dropdown .dropdown-input").val();
                var cutoff = $(".cutoff-dropdown .dropdown-input").val();

                console.log("Sending Data:", {
                    name,
                    month,
                    year,
                    cutoff
                }); // Debugging

                $.ajax({
                    method: "POST",
                    url: "search_payroll.php",
                    data: {
                        name: name,
                        month: month,
                        year: year,
                        cutoff: cutoff
                    },
                    success: function(response) {
                        console.log("Response:", response); // Debugging output
                        $("#showdata").html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });
            }

            $("#search_emp_input").on("keyup", fetchPayroll);

            var cutoffMap = {
                "First Cutoff": "1",
                "Second Cutoff": "2"
            };

            $(".dropdown-item").on("click", function() {
                var dropdownType = $(this).closest(".dropdown").hasClass("month-dropdown") ?
                    "month" :
                    $(this).closest(".dropdown").hasClass("year-dropdown") ?
                    "year" :
                    "cutoff";

                var selectedValue = $(this).data("value"); // Get the actual data-value

                // Convert cutoff text to numeric value
                if (dropdownType === "cutoff" && selectedValue !== "") {
                    selectedValue = cutoffMap[$(this).text().trim()] || "";
                }

                // If "Clear Selection" is clicked, reset the input field
                if (selectedValue === "") {
                    $("." + dropdownType + "-dropdown .dropdown-input").val("").attr("placeholder", "Select " + dropdownType.charAt(0).toUpperCase() + dropdownType.slice(1));
                } else {
                    $("." + dropdownType + "-dropdown .dropdown-input").val($(this).text());
                }

                fetchPayroll(); // Update the payroll list based on the new selection
            });

        });
    </script>
    <script src="./javascript/main.js"></script>
    <script src="./javascript/payroll.js"></script>

</body>

</html>