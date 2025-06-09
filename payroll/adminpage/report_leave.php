<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

// Initialize search, date, and show entries filter values
$search = isset($_POST['search']) ? trim($_POST['search']) : '';
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';
$limit = isset($_POST['show_entries']) ? (int)$_POST['show_entries'] : (isset($_GET['show_entries']) ? (int)$_GET['show_entries'] : 10);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// SQL query to fetch data with filters
$sql = "SELECT 
            l.emp_id AS EmployeeID, 
            l.subject AS Subject, 
            CONCAT(a.lastname, ', ', a.firstname, ' ', a.middlename) AS Name, 
            l.leave_type AS LeaveType, 
            l.date_applied AS DateFiled, 
            l.no_of_leave AS NoOfLeave, 
            l.remaining_leave AS RemainingLeave, 
            l.total_leaves AS TotalLeave
        FROM tbl_leave l
        JOIN tbl_emp_acc a ON l.emp_id = a.emp_id
        WHERE l.status IN ('Approved', 'Declined')";

// Apply search filter
if (!empty($search)) {
    $sql .= " AND (a.lastname LIKE '%$search%' OR a.firstname LIKE '%$search%' OR a.middlename LIKE '%$search%' OR l.emp_id LIKE '%$search%')";
}

// Apply date range filter
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND l.date_applied BETWEEN '$from_date' AND '$to_date'";
}

// Count total records for pagination
$total_result = mysqli_query($conn, $sql);
$total_records = mysqli_num_rows($total_result);
$total_pages = ceil($total_records / $limit);

// Apply pagination limits
$sql .= " ORDER BY l.date_applied DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/report_leave.css">
    <title>Report - Leave</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Report</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                    <span> > </span>
                    <h5><a href="./report_leave.php">Report-Leave </a></h5>
                </div>
                <hr>
            </div>
            <div class="main-content">
                <div class="sub-content">
                    <div class="selection_div">
                        <p style="margin: 0;font-weight: 500;">Leave Report</p>
                        <div style="display: flex;align-items: center;width:60%;justify-content:right;">
                            <div class="search-bar">
                                <button id="voiceSearchBtn" title="Speak to search" style="margin-left: 10px; background: none; cursor: pointer; border: 0;">
                                    <i class="bi bi-mic-fill" style="font-size: 1.35rem; color:#20242C;"></i>
                                </button>
                                <form method="POST" action="report_leave.php" id="searchForm" style="display: flex; align-items: center;">
                                    <input type="text" name="search" id="searchInput" class="search-box" placeholder="Search employee..." value="<?php echo htmlspecialchars($search); ?>" />
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="controls">
                            <form method="POST" id="entriesForm" style=" align-items: center;">
                                <!-- <label for="show-entries">Show
                                    <select id="show-entries" name="show_entries">
                                        <option value="10" <?php if ($limit == 10) echo "selected"; ?>>10</option>
                                        <option value="25" <?php if ($limit == 25) echo "selected"; ?>>25</option>
                                        <option value="50" <?php if ($limit == 50) echo "selected"; ?>>50</option>
                                        <option value="100" <?php if ($limit == 100) echo "selected"; ?>>100</option>
                                        <option value="<?php echo $total_records; ?>" <?php if ($limit == $total_records) echo "selected"; ?>>
                                            All (<?php echo $total_records; ?> records)
                                        </option>
                                    </select>
                                    entries
                                </label> -->
                            </form>
                            <div class="date-range">
                                <form method="POST" action="report_leave.php" style="display: flex; align-items: center;">
                                    <label for="from-date">From:
                                        <input type="date" id="from-date" name="from_date" value="<?php echo $from_date; ?>" />
                                    </label>
                                    <label for="to-date">To:
                                        <input type="date" id="to-date" name="to_date" value="<?php echo $to_date; ?>" />
                                    </label>
                                    <button type="submit" class="search-btn">Search Date</button>
                                </form>
                            </div>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Subject</th>
                                    <th>Name</th>
                                    <th>Leave Type</th>
                                    <th>Date Filed</th>
                                    <th>No. Of Leave</th>
                                    <th>Remaining Leave</th>
                                    <th>Total Leave</th>
                                </tr>
                            </thead>
                            <tbody id="showdata">
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $formatted_date = date("F-d-Y", strtotime($row['DateFiled']));
                                ?>
                                        <tr>
                                            <td><?php echo $row['EmployeeID']; ?></td>
                                            <td><?php echo $row['Subject']; ?></td>
                                            <td><?php echo $row['Name']; ?></td>
                                            <td><?php echo $row['LeaveType']; ?></td>
                                            <td><?php echo htmlspecialchars($formatted_date); ?></td>
                                            <td><?php echo $row['NoOfLeave']; ?></td>
                                            <td><?php echo $row['RemainingLeave']; ?></td>
                                            <td><?php echo $row['TotalLeave']; ?></td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="8" style="text-align: center; font-weight: bold;">No Records Found</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <br>
                        <!-- Pagination -->
                        <div class="pagination">
                            <p>Showing <?php echo $total_records; ?> / <?php echo $total_records; ?> results</p>
                            <div class="pagination">
                                <button id="prevPage" <?php if ($page <= 1) echo "disabled"; ?>>Prev</button>
                                <input type="text" class="perpage" value="<?php echo $page; ?>" readonly />
                                <button id="nextPage" <?php if ($page >= $total_pages) echo "disabled"; ?>>Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Pagination -->
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById("show-entries").addEventListener("change", function() {
            document.getElementById("entriesForm").submit();
        });

        document.getElementById("prevPage").addEventListener("click", function() {
            let currentPage = <?php echo $page; ?>;
            let showEntries = document.getElementById("show-entries").value;
            if (currentPage > 1) {
                window.location.href = "?page=" + (currentPage - 1) + "&show_entries=" + showEntries;
            }
        });

        document.getElementById("nextPage").addEventListener("click", function() {
            let currentPage = <?php echo $page; ?>;
            let totalPages = <?php echo $total_pages; ?>;
            let showEntries = document.getElementById("show-entries").value;
            if (currentPage < totalPages) {
                window.location.href = "?page=" + (currentPage + 1) + "&show_entries=" + showEntries;
            }
        });

        let searchInput = document.getElementById("searchInput");
        let fromDate = document.getElementById("from-date");
        let toDate = document.getElementById("to-date");
        let showEntries = document.getElementById("show-entries");
        let tableBody = document.querySelector("table tbody");
        let typingTimer;

        searchInput.addEventListener("input", function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchSearchResults, 300);
        });

        function fetchSearchResults() {
            const formData = new FormData();
            formData.append("search", searchInput.value);
            formData.append("from_date", fromDate.value);
            formData.append("to_date", toDate.value);
            formData.append("show_entries", showEntries.value);
            formData.append("page", 1); // You can later make this dynamic if needed

            fetch("search_reportleave.php", {
                    method: "POST",
                    body: formData
                })
                .then((res) => res.text())
                .then((data) => {
                    tableBody.innerHTML = data;
                })
                .catch((err) => {
                    console.error("Error:", err);
                });
        }

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
                    voiceBtn.innerHTML = `<i class="bi bi-mic-mute-fill" style="color: red;"></i>`;

                    Swal.fire({
                        title: 'Listening...',
                        text: 'Please speak now',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        didOpen: () => Swal.showLoading()
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            recognition.abort(); // Stop listening if canceled
                            voiceBtn.innerHTML = `<i class="bi bi-mic-fill"></i>`;
                        }
                    });
                });

                recognition.onresult = function(event) {
                    const transcript = event.results[0][0].transcript;
                    searchInput.value = transcript;
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


</body>

</html>