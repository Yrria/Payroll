    <?php
    session_start();
    include '../assets/databse/connection.php';
    include './database/session.php';

    $records_per_page = 5;
    $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $start_from = ($current_page - 1) * $records_per_page;

    // Filters
    $month = isset($_GET['month']) ? $_GET['month'] : date('m');
    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
    $cutoff = isset($_GET['cutoff']) ? $_GET['cutoff'] : ((date('d') <= 15) ? 'F' : 'S');

    // Define cutoff range
    if ($cutoff === 'F') {
        $start_day = '01';
        $end_day = '15';
    } else {
        $start_day = '16';
        $end_day = date('t', strtotime("$year-$month-01")); // last day of the month
    }

    $start_date = "$year-$month-$start_day";
    $end_date = "$year-$month-$end_day";

    // Handle search
    $search_query = '';
    $where_clause = "WHERE attendance_date BETWEEN '$start_date' AND '$end_date'";

    if (!empty($_GET['query'])) {
        $q = $conn->real_escape_string($_GET['query']);
        $search_query = "&query=" . urlencode($_GET['query']);

        $where_clause .= " AND (
            tbl_attendance.emp_id LIKE '%{$q}%'
            OR tbl_attendance.attendance_id LIKE '%{$q}%'
            OR CONCAT(tbl_emp_acc.firstname, ' ', tbl_emp_acc.middlename, ' ', tbl_emp_acc.lastname) LIKE '%{$q}%'
            OR CONCAT(tbl_emp_acc.firstname, ' ', LEFT(tbl_emp_acc.middlename, 1), '. ', tbl_emp_acc.lastname) LIKE '%{$q}%'
            OR tbl_emp_info.position LIKE '%{$q}%'
            OR tbl_emp_info.shift LIKE '%{$q}%'
        )";
    }

    // Fetch filtered attendance records with JOINs
    $sql = "SELECT tbl_attendance.*, 
                tbl_emp_acc.firstname, tbl_emp_acc.middlename, tbl_emp_acc.lastname,
                tbl_emp_info.position, tbl_emp_info.shift
            FROM tbl_attendance
            LEFT JOIN tbl_emp_acc ON tbl_attendance.emp_id = tbl_emp_acc.emp_id
            LEFT JOIN tbl_emp_info ON tbl_attendance.emp_id = tbl_emp_info.emp_id
            $where_clause
            LIMIT $start_from, $records_per_page";

    $result = $conn->query($sql);

    // Count total records for pagination
    $count_sql = "SELECT COUNT(*) as total
                FROM tbl_attendance
                LEFT JOIN tbl_emp_acc ON tbl_attendance.emp_id = tbl_emp_acc.emp_id
                LEFT JOIN tbl_emp_info ON tbl_attendance.emp_id = tbl_emp_info.emp_id
                $where_clause";

    $count_result = mysqli_query($conn, $count_sql);
    $total_records = mysqli_fetch_array($count_result)['total'];
    $total_pages = ceil($total_records / $records_per_page);

    // Pagination links
    $pagination_links = '';
    if ($total_pages > 1) {
        for ($i = 1; $i <= $total_pages; $i++) {
            $pagination_links .= "<a href='?page=$i&month=$month&year=$year&cutoff=$cutoff$search_query'>$i</a> ";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="./css/main.css">
        <link rel="stylesheet" href="./css/attendance.css">
        <!-- <link rel="stylesheet" href="./css/payroll.css"> -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://kit.fontawesome.com/3b07bc6295.js" crossorigin="anonymous"></script>
        <title>Attendance</title>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const now = new Date();
                const monthInput = document.querySelector(".month-dropdown .dropdown-input");
                const yearInput = document.querySelector(".year-dropdown .dropdown-input");
                const cutoffInput = document.querySelector(".cutoff-dropdown .dropdown-input");

                const urlParams = new URLSearchParams(window.location.search);
                const selectedMonth = urlParams.get('month') || String(now.getMonth() + 1).padStart(2, '0');
                const selectedYear = urlParams.get('year') || now.getFullYear();
                const selectedCutoff = urlParams.get('cutoff') || (now.getDate() <= 15 ? 'F' : 'S');

                const months = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                // Set dropdowns
                document.getElementById("month-options").innerHTML = months.map((m, i) =>
                    `<div class='dropdown-item' data-value='${String(i+1).padStart(2,"0")}'>${m}</div>`).join("");

                let yearOptions = "";
                for (let y = now.getFullYear(); y >= 1900; y--) {
                    yearOptions += `<div class='dropdown-item' data-value='${y}'>${y}</div>`;
                }
                document.getElementById("year-options").innerHTML = yearOptions;

                // Set default input values
                monthInput.value = months[parseInt(selectedMonth) - 1];
                monthInput.setAttribute("data-value", selectedMonth);
                yearInput.value = selectedYear;
                yearInput.setAttribute("data-value", selectedYear);
                cutoffInput.value = selectedCutoff === "F" ? "First Cutoff" : "Second Cutoff";
                cutoffInput.setAttribute("data-value", selectedCutoff);

                // Handle selection
                document.querySelectorAll(".dropdown-item").forEach(item => {
                    item.addEventListener("click", function() {
                        const dropdown = this.closest(".dropdown");
                        const input = dropdown.querySelector(".dropdown-input");
                        input.value = this.textContent.trim();
                        input.setAttribute("data-value", this.dataset.value);
                        applyFilter();
                    });
                });

                function applyFilter() {
                    const month = document.querySelector(".month-dropdown .dropdown-input").dataset.value;
                    const year = document.querySelector(".year-dropdown .dropdown-input").dataset.value;
                    const cutoff = document.querySelector(".cutoff-dropdown .dropdown-input").dataset.value;
                    window.location.href = `?month=${month}&year=${year}&cutoff=${cutoff}`;
                }
            });
        </script>
        <style>
            .dropdown-content {
                max-height: 200px;
                overflow-y: auto;
            }
        </style>
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
                        <!-- Records Section -->
                        <div class="leave-requests">
                            <div class="selection_div">
                                <div class="search-filters" style="margin: 0;">
                                    <form method="GET" action="">
                                        <input class="search-box" type="text" name="query" placeholder="Search employee..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
                                        <input type="hidden" name="month" value="<?php echo $month; ?>">
                                        <input type="hidden" name="year" value="<?php echo $year; ?>">
                                        <input type="hidden" name="cutoff" value="<?php echo $cutoff; ?>">
                                    </form>
                                    <button id="voiceSearchBtn" title="Speak to search" style="
                                                margin-left: 5px;
                                                background: none;
                                                cursor: pointer;
                                                border: 0;
                                            ">
                                        <i class="bi bi-mic-fill" style="font-size: 1.35rem; color:#20242C;"></i>
                                    </button>
                                </div>
                                <div style="display: flex; align-items: center; width: 60%; justify-content: right; margin-right: -4%;">
                                    <!-- Month Dropdown -->
                                    <div class="dropdown month-dropdown" style="margin-left: 20%;">
                                        <div class="dropdown-wrapper">
                                            <input type="text" class="dropdown-input" readonly placeholder="Select Month" style="width:75%;" />
                                            <div class="dropdown-indicator">&#9662;</div>
                                            <div class="dropdown-content" id="month-options"></div>
                                        </div>
                                    </div>

                                    <!-- Year Dropdown -->
                                    <div class="dropdown year-dropdown" style="width:25%;">
                                        <div class="dropdown-wrapper">
                                            <input type="text" class="dropdown-input" readonly placeholder="Select Year" style="width:75%;" />
                                            <div class="dropdown-indicator" style="right:47px;">&#9662;</div>
                                            <div class="dropdown-content" id="year-options"></div>
                                        </div>
                                    </div>

                                    <!-- Cutoff Dropdown -->
                                    <div class="dropdown cutoff-dropdown">
                                        <div class="dropdown-wrapper">
                                            <input type="text" class="dropdown-input" readonly placeholder="Select Cutoff" />
                                            <div class="dropdown-indicator">&#9662;</div>
                                            <div class="dropdown-content">
                                                <div class="dropdown-item" data-value="F" style="font-size: 14px;">First Cutoff</div>
                                                <div class="dropdown-item" data-value="S" style="font-size: 14px;">Second Cutoff</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <table id="leave-table" class="att-table">
                                <thead>
                                    <tr>
                                        <th class="att-header">Employee ID</th>
                                        <th class="att-header">Name</th>
                                        <th class="att-header">Position</th>
                                        <th class="att-header">Shift</th>
                                        <th class="att-header">Total Present</th>
                                        <th class="att-header">Total Hours</th>
                                        <th class="att-header">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="showdata">
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $emp_id = $row['emp_id'];

                                            $info_sql = "SELECT shift, position, rate FROM tbl_emp_info WHERE emp_id = '$emp_id' LIMIT 1";
                                            $info_result = $conn->query($info_sql);
                                            $info = $info_result->fetch_assoc();

                                            $shift = $info['shift'] ?? 'N/A';
                                            $position = $info['position'] ?? 'N/A';

                                            $acc_sql = "SELECT * FROM tbl_emp_acc WHERE emp_id = '$emp_id' LIMIT 1";
                                            $acc_result = $conn->query($acc_sql);
                                            $account = $acc_result->fetch_assoc();

                                            $leave_sql = "SELECT * FROM tbl_leave WHERE emp_id = '$emp_id' LIMIT 1";
                                            $leave_result = $conn->query($leave_sql);
                                            $leave_acc = $leave_result->fetch_assoc();

                                            if (!empty($account['middlename'])) {
                                                $fullname = $account['firstname'] . " " . $account['middlename'][0] . ". " . $account['lastname'];
                                            } else {
                                                $fullname = $account['firstname'] . " " . $account['lastname'];
                                            }

                                            $_SESSION['fullname'] = $fullname;
                                    ?>
                                            <tr>
                                                <td class="att-cell"><?= $row['emp_id'] ?></td>
                                                <td hidden><?= $row['attendance_id'] ?></td>
                                                <td hidden><?= $row['absent_days'] ?></td>
                                                <td hidden><?= $row['hours_late'] ?></td>
                                                <td hidden><?= $row['holiday'] ?></td>
                                                <td hidden><?= $row['hours_overtime'] ?></td>
                                                <td hidden><?= $leave_acc['remaining_leave'] ?></td>
                                                <td class="att-cell emp-name"><?= htmlspecialchars($fullname) ?></td>
                                                <td class="att-cell"><?= $position ?></td>
                                                <td class="att-cell"><?= $shift ?></td>
                                                <td class="att-cell"><?= $row['present_days'] ?></td>
                                                <td class="att-cell"><?= $row['hours_present'] ?></td>
                                                <td class="att-cell td-text">
                                                    <div class="action-buttons" style="max-width: 100%;">
                                                        <button
                                                            class="slip-btn btn-view"
                                                            style="width: 100px;height:40px;"
                                                            onclick="openEmployeeModal(this)"
                                                            data-empid="<?= $row['emp_id'] ?>"
                                                            data-fullname="<?= htmlspecialchars($fullname) ?>"
                                                            data-position="<?= $position ?>"
                                                            data-shift="<?= $shift ?>"
                                                            data-present="<?= $row['present_days'] ?>"
                                                            data-hours="<?= $row['hours_present'] ?>"
                                                            data-late="<?= $row['hours_late'] ?>"
                                                            data-leave="<?= $leave_acc['remaining_leave'] ?? 0 ?>"
                                                            data-holiday="<?= $row['holiday'] ?>"
                                                            data-overtime="<?= $row['hours_overtime'] ?>"
                                                            data-absent="<?= $row['absent_days'] ?>">
                                                            View info
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='att-cell' style='text-align:center;'>No attendance records found.</td></tr>";
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
                                        <a href="?page=<?= $current_page - 1 ?>&month=<?= $month ?>&year=<?= $year ?>&cutoff=<?= $cutoff ?>" class="prev" style="border-radius:4px;background-color:#368DB8;color:white;margin-bottom:13px; padding: 10px;">&laquo; Previous</a>
                                    <?php endif; ?>

                                    <?php if ($current_page < $total_pages) : ?>
                                        <a href="?page=<?= $current_page + 1 ?>&month=<?= $month ?>&year=<?= $year ?>&cutoff=<?= $cutoff ?>" class="next" style="border-radius:4px;background-color:#368DB8;color:white;margin-bottom:13px; padding: 10px;">Next &raquo;</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL VIEW INFO -->
        <div class="modal-container" id="employeeModal">
            <div class="modal-box">
                <table class="custom-table">
                    <tr>
                        <th class="profile-cell custom-th" rowspan="2">
                            <div class="profile-box">
                                <img src="../assets/avatar.png" alt="Avatar" class="profile-img" />
                                <div class="profile-name">Marc Andrei A. Toledo</div>
                            </div>
                        </th>
                        <th class="custom-th">Employee Id</th>
                        <th class="custom-th">Position</th>
                        <th class="custom-th">Shift</th>
                        <th class="custom-th">Total Present</th>
                    </tr>
                    <tr>
                        <td class="custom-td">1</td>
                        <td class="custom-td">Crew</td>
                        <td class="custom-td">Morning</td>
                        <td class="custom-td">20</td>
                    </tr>
                </table>

                <table class="custom-table">
                    <tr>
                        <th class="custom-th">Total Hours</th>
                        <th class="custom-th">Hours Late</th>
                        <th class="custom-th">Remaining Leave</th>
                        <th class="custom-th">Holiday</th>
                        <th class="custom-th">Overtime Hours</th>
                        <th class="custom-th">Absent</th>
                    </tr>
                    <tr>
                        <td class="custom-td">120</td>
                        <td class="custom-td">5</td>
                        <td class="custom-td">0</td>
                        <td class="custom-td">0</td>
                        <td class="custom-td">0</td>
                        <td class="custom-td">0</td>
                    </tr>
                </table>

                <button class="btn" onclick="toggleModal(false)">Back</button>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script>
            // sweetalert sa voice search
            document.addEventListener("DOMContentLoaded", () => {
                const voiceBtn = document.getElementById("voiceSearchBtn");
                const searchInput = document.querySelector(".search-box");

                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

                if (SpeechRecognition && voiceBtn && searchInput) {
                    const recognition = new SpeechRecognition();
                    recognition.continuous = false;
                    recognition.lang = "en-US";

                    voiceBtn.addEventListener("click", (e) => {
                        e.preventDefault();
                        recognition.start();
                        voiceBtn.innerHTML = `<i class="bi bi-mic-fill" style="color: red;"></i>`;

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

            // VIEW INFO
            function openEmployeeModal(button) {
                const modal = document.getElementById("employeeModal");

                // Get data from the clicked button
                const empId = button.getAttribute("data-empid");
                const fullname = button.getAttribute("data-fullname");
                const position = button.getAttribute("data-position");
                const shift = button.getAttribute("data-shift");
                const present = button.getAttribute("data-present");
                const hours = button.getAttribute("data-hours");
                const late = button.getAttribute("data-late");
                const leave = button.getAttribute("data-leave");
                const holiday = button.getAttribute("data-holiday");
                const overtime = button.getAttribute("data-overtime");
                const absent = button.getAttribute("data-absent");

                // Update modal content
                modal.querySelector(".profile-name").textContent = fullname;

                const firstRow = modal.querySelectorAll(".custom-table")[0].rows[1].cells;
                firstRow[0].textContent = empId;
                firstRow[1].textContent = position;
                firstRow[2].textContent = shift;
                firstRow[3].textContent = present;

                const secondRow = modal.querySelectorAll(".custom-table")[1].rows[1].cells;
                secondRow[0].textContent = hours;
                secondRow[1].textContent = late;
                secondRow[2].textContent = leave;
                secondRow[3].textContent = holiday;
                secondRow[4].textContent = overtime;
                secondRow[5].textContent = absent;

                // Show modal
                modal.style.display = "flex";
            }

            function toggleModal(show) {
                document.getElementById("employeeModal").style.display = show ? "flex" : "none";
            }

            // Close modal when clicking outside modal box
            window.addEventListener("click", function(event) {
                const modal = document.getElementById("employeeModal");
                const modalBox = document.querySelector(".modal-box");

                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        </script>
        <script src="./javascript/main.js"></script>
        <script src="./javascript/payroll.js"></script>

    </body>

    </html>