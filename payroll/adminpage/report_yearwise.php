<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/report_yearwise.css">
    <title>Report - Year Wise</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Report</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard</a></h5>
                    <span> > </span>
                    <h5><a href="./report_yearwise.php">Report-Yearwise</a></h5>
                </div>
                <hr>
            </div>
            <div class="main-content">
                <div class="sub-content">
                    <div class="selection_div">
                        <p class="label">Year Wise Report</p>
                        <div class="search-bar">
                            <form method="GET" action="">
                                <button id="voiceSearchBtn" title="Speak to search" style="margin-right: 5px; background: none; cursor: pointer; border: 0;">
                                    <i class="bi bi-mic-fill" style="font-size: 1.35rem; color:#20242C;"></i>
                                </button>
                                <input type="text" id="searchInput" class="search-box" name="query" placeholder="Search employee..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" />
                            </form>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <div class="table-container">
                        <?php
                        // Pagination variables
                        $limit = 10; // rows per page
                        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;

                        $search_query = isset($_GET['query']) ? $_GET['query'] : '';
                        $search_query_escaped = $conn->real_escape_string($search_query);

                        // Count total matching rows
                        $count_sql = "
            SELECT COUNT(DISTINCT e.emp_id) AS total
            FROM tbl_emp_acc e
        ";

                        if (!empty($search_query_escaped)) {
                            $count_sql .= "
            WHERE (
                e.emp_id LIKE '%$search_query_escaped%'
                OR e.firstname LIKE '%$search_query_escaped%'
                OR e.lastname LIKE '%$search_query_escaped%'
                OR e.middlename LIKE '%$search_query_escaped%'
            )";
                        }

                        $count_result = $conn->query($count_sql);
                        $total_rows = 0;
                        if ($count_result && $row = $count_result->fetch_assoc()) {
                            $total_rows = (int)$row['total'];
                        }

                        // Main query with GROUP BY and pagination
                        $sql = "
            SELECT e.emp_id, e.lastname, e.firstname, e.middlename, i.rate,
                COALESCE(SUM(a.hours_overtime), 0) AS total_overtime,
                COALESCE(SUM(a.hours_present), 0) AS total_worked,
                COALESCE(SUM(a.present_days), 0) AS total_present_days,
                COALESCE(SUM(d.pagibig_deduction + d.philhealth_deduction + d.sss_deduction + d.other_deduction), 0) AS total_deductions,
                COALESCE(SUM(s.total_salary), 0) AS total_wage
            FROM tbl_emp_acc e
            LEFT JOIN tbl_emp_info i ON e.emp_id = i.emp_id
            LEFT JOIN tbl_attendance a ON e.emp_id = a.emp_id
            LEFT JOIN tbl_deduction d ON e.emp_id = d.emp_id
            LEFT JOIN tbl_salary s ON e.emp_id = s.emp_id
        ";

                        if (!empty($search_query_escaped)) {
                            $sql .= "
            WHERE (
                e.emp_id LIKE '%$search_query_escaped%'
                OR e.firstname LIKE '%$search_query_escaped%'
                OR e.lastname LIKE '%$search_query_escaped%'
                OR e.middlename LIKE '%$search_query_escaped%'
            )
            ";
                        }

                        $sql .= " GROUP BY e.emp_id
                  LIMIT $limit OFFSET $offset";

                        $result = $conn->query($sql);

                        // Calculate showing range
                        $start = ($total_rows > 0) ? $offset + 1 : 0;
                        $end = min($offset + $limit, $total_rows);
                        ?>

                        <table>
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Overtime Hours</th>
                                    <th>Worked Hours</th>
                                    <th>Total Deductions</th>
                                    <th>Total Wage</th>
                                    <th>View Details</th>
                                </tr>
                            </thead>
                            <tbody id="showdata">
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $empId = htmlspecialchars($row['emp_id']);
                                        $empName = htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['middlename']);
                                        $overtimeHours = number_format((float)$row['total_overtime'], 2) . 'h';
                                        $workedHours = number_format((float)$row['total_worked'], 2) . 'h';
                                        $totalDeductions = '₱' . number_format($row['total_deductions'], 2);
                                        $totalWage = '₱' . number_format($row['total_wage'], 2);

                                        echo "<tr>
                            <td>{$empId}</td>
                            <td>{$empName}</td>
                            <td>{$overtimeHours}</td>
                            <td>{$workedHours}</td>
                            <td>{$totalDeductions}</td>
                            <td>{$totalWage}</td>
                            <td class='td-text'>
                                <div class='action-buttons'>
                                    <button class='view-btn' onclick='openModal(\"{$empId}\")'>View Info</button>
                                </div>
                            </td>
                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' style='text-align:center;'>No data found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>

                        <br>
                        <div class="pagination">
                            <?php
                            $limit = 10; // or your current LIMIT per page
                            $page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;

                            $start = ($page - 1) * $limit + 1;
                            $end = min($start + $limit - 1, $total_rows);
                            ?>

                            <p>
                                Showing <?php echo ($total_rows > 0) ? "$end / $total_rows" : "0"; ?> Results
                            </p>
                            <!-- <div>
                                <button <?php if ($page <= 1) echo 'disabled'; ?>
                                    onclick="window.location='?page=<?php echo $page - 1; ?>&query=<?php echo urlencode($search_query); ?>'">Prev</button>

                                <input type="text" class="perpage" value="<?php echo $page; ?>" readonly />

                                <button <?php if ($page * $limit >= $total_rows) echo 'disabled'; ?>
                                    onclick="window.location='?page=<?php echo $page + 1; ?>&query=<?php echo urlencode($search_query); ?>'">Next</button>
                            </div> -->
                        </div>
                    </div>
                </div>


                <!-- MODAL -->
                <div id="infoModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="title">ANNUAL SUMMARY REPORT</h2>
                            <h2 class="year" id="year"></h2> <!-- added id="year" -->
                        </div>
                        <span class="close-btn" onclick="closeModal()">&times;</span>
                        <hr>
                        <div class="employee-info">
                            <span>Employee Name: </span> <!-- leave empty to fill dynamically -->
                        </div>
                        <div class="employee-stats">
                            <div class="stat"><span class="worked-hours">Total Worked Hours: 0h</span></div>
                            <div class="stat"><span class="total-deductions">Total Deductions: ₱0</span></div>
                            <div class="stat"><span class="overtime-hours">Total Overtime Hours: 0h</span></div>
                            <div class="stat"><span class="total-wage">Total Wage: ₱0</span></div>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Worked Hours</th>
                                    <th>Overtime Hours</th>
                                    <th>Deductions</th>
                                    <th>Total Wage</th>
                                </tr>
                            </thead>
                            <tbody id="monthlyDataBody">
                                <!-- monthly rows will be injected here -->
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button class="back-btn" onclick="closeModal()">Back</button>
                            <div class="pagination">
                                <button>Prev</button>
                                <input type="text" class="perpage" value="1" readonly />
                                <button>Next</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- .main-content -->
        </div> <!-- #mainContent -->
    </div> <!-- .container -->


    <!-- Bootstrap Icons for mic icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (optional, in case used elsewhere) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script>
        function openModal(empId) {
            fetch('fetch_employee_info.php?emp_id=' + encodeURIComponent(empId))
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector('.employee-info span').textContent = `Employee Name: ${data.fullname}`;
                        document.querySelector('.worked-hours').textContent = `Total Worked Hours: ${data.total_worked_hours}h`;
                        document.querySelector('.overtime-hours').textContent = `Total Overtime Hours: ${data.total_overtime_hours}h`;
                        document.querySelector('.total-deductions').textContent = `Total Deductions: ₱${Number(data.total_deductions).toFixed(2)}`;
                        document.querySelector('.total-wage').textContent = `Total Wage: ₱${Number(data.total_wage).toFixed(2)}`;

                        const tbody = document.getElementById('monthlyDataBody');
                        if (!tbody) {
                            console.error('Table body element not found!');
                            return;
                        }
                        tbody.innerHTML = '';

                        if (!Array.isArray(data.monthly_data)) {
                            data.monthly_data = [];
                        }

                        let totalWorked = 0;
                        let totalOvertime = 0;
                        let totalDeductions = 0;
                        let totalWage = 0;

                        data.monthly_data.forEach(month => {
                            const worked = Number(month.worked_hours) || 0;
                            const overtime = Number(month.overtime_hours) || 0;
                            const deductions = Number(month.deductions) || 0;
                            const wage = Number(month.total_wage) || 0;

                            totalWorked += worked;
                            totalOvertime += overtime;
                            totalDeductions += deductions;
                            totalWage += wage;

                            const row = document.createElement('tr');
                            row.innerHTML = `
                        <td>${month.month} ${month.year}</td>
                        <td>${worked}</td>
                        <td>${overtime}</td>
                        <td>₱${deductions.toFixed(2)}</td>
                        <td>₱${wage.toFixed(2)}</td>
                    `;
                            tbody.appendChild(row);
                        });

                        const totalRow = document.createElement('tr');
                        totalRow.classList.add('total-row');
                        totalRow.innerHTML = `
                    <td>Total</td>
                    <td>${totalWorked}</td>
                    <td>${totalOvertime}</td>
                    <td>₱${totalDeductions.toFixed(2)}</td>
                    <td>₱${totalWage.toFixed(2)}</td>
                `;
                        tbody.appendChild(totalRow);

                        document.getElementById('infoModal').style.display = 'block';
                    } else {
                        alert(data.message || 'Employee info not found.');
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    alert('Failed to fetch employee info.');
                });
        }

        function closeModal() {
            document.getElementById('infoModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('infoModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };

        document.getElementById('searchInput').addEventListener('keyup', function() {
            const query = this.value;

            fetch('search_yearwise.php?query=' + encodeURIComponent(query))
                .then(response => response.text())
                .then(data => {
                    document.getElementById('showdata').innerHTML = data;
                });
        });

        document.addEventListener("DOMContentLoaded", () => {
            const yearElement = document.getElementById("year");
            const currentYear = new Date().getFullYear();
            yearElement.textContent = currentYear;
        });

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