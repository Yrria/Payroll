<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

$employee_id = $_SESSION['emp_id'];

$sql = "SELECT subject, start_date, end_date, message, leave_type, status 
        FROM tbl_leave 
        WHERE emp_id = ?
        ORDER BY date_applied ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/leave.css">
    <title>Leave</title>
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
                    <h5><a href="./leave.php">Leave </a></h5>
                </div>
                <hr>
            </div>

            <div class="main-content">
                <div class="sub-content">
                        <!-- Leave Table -->
                        <div id="maindiv">
                            <div class="grid-item">
                                <button class="button" onclick="alter()" style="width: 30%;">+ File a Leave</button>
                            </div>
                            <div class="grid-item">
                                <select name="leavefilter" id="leavefilter" class="textbox">
                                        <option value="">Leave Type</option>
                                        <option value="Sick Leave">Sick Leave</option>
                                        <option value="Medical Leave">Medical Leave</option>
                                        <option value="Annual Leave">Annual Leave</option>
                                        <option value="Bereavement Leave">Bereavement Leave</option>
                                        <option value="Paternity Leave">Paternity Leave</option>
                                        <option value="Casual Leave">Casual Leave</option>
                                </select>
                                <select name="statusfilter" id="statusfilter" class="textbox">
                                        <option value="">Status</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="grid-item">
                                <table id="leaveTable">
                                    <tr>
                                        <th>Leave #</th>
                                        <th>Subject</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Message</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    <?php
                                    $leaveCount = 1;
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $statusColor = match (strtolower($row['status'])) {
                                                'approved' => 'rgb(0, 255, 0)',
                                                'rejected' => 'rgb(255, 0, 0)',
                                                default => 'rgb(97, 97, 97)'
                                            };
                                            echo "<tr>
                                                    <td style='text-align: center;'>{$leaveCount}</td>
                                                    <td>{$row['subject']}</td>
                                                    <td>" . date("m/d/Y", strtotime($row['start_date'])) . "</td>
                                                    <td>" . date("m/d/Y", strtotime($row['end_date'])) . "</td>
                                                    <td>" . htmlspecialchars($row['message']) . "</td>
                                                    <td>{$row['leave_type']}</td>
                                                    <td style='color: $statusColor'>{$row['status']}</td>
                                                    <td style='text-align: center;'>
                                                        <button class='view'
                                                            data-subject='{$row['subject']}'
                                                            data-status='{$row['status']}'
                                                            data-start='" . date("d/m/Y", strtotime($row['start_date'])) . "'
                                                            data-end='" . date("d/m/Y", strtotime($row['end_date'])) . "'
                                                            data-type='{$row['leave_type']}'
                                                            data-message=\"" . htmlspecialchars($row['message'], ENT_QUOTES) . "\">View Info</button>
                                                    </td>
                                                </tr>";
                                            $leaveCount++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' style='text-align:center;'>No data was found.</td></tr>";
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="grid-item">
                                <Button id="prevBtn" class="button" style="width: 7%;">Prev</Button>
                                <span id="paginationNumbers" class="pagination"></span>
                                <button id="nextBtn" class="button" style="width: 7%;">Next</button>
                            </div>
                        </div>

                        <!-- File Leave -->
                        <form action="submit_leave.php" method="POST" id="leaveForm">
                            <div id="alter" style="display: none;">
                                <div class="grid-item">
                                    <h3>Leave Application</h3>
                                    <label for="subject">Leave Subject</label>
                                    <input type="text" name="subject" class="textbox" id="subject">
                                </div>
                                <div class="grid-item">
                                    <label for="startdate">Start Date (DD/MM/YYYY)</label>
                                    <input type="date" name="startdate" class="textbox" id="startdate">
                                </div>
                                <div class="grid-item">
                                    <label for="enddate">End Date (DD/MM/YYYY)</label>
                                    <input type="date" name="enddate" class="textbox" id="enddate" disabled>
                                </div>
                                <div class="grid-item">
                                    <label for="leavetype">Leave Type</label>
                                    <select name="leavetype" id="leavetype" class="textbox">
                                        <option value=""></option>
                                        <option value="Sick Leave">Sick Leave</option>
                                        <option value="Medical Leave">Medical Leave</option>
                                        <option value="Annual Leave">Annual Leave</option>
                                        <option value="Bereavement Leave">Bereavement Leave</option>
                                        <option value="Paternity Leave">Paternity Leave</option>
                                        <option value="Casual Leave">Casual Leave</option>
                                    </select>
                                </div>
                                <div class="grid-item">
                                    <label for="message">Leave Message</label>
                                    <textarea type="text" name="message" class="textbox" id="message" style="height: 200px;"></textarea>
                                </div>
                                <div class="grid-item">
                                    <input type="submit" class="button" value="Submit" id="submit">
                                </div>
                            </div>
                        </form>

                        <!-- view leave approved-->
                        <div class="overlay" id="overlay1">
                            <div class="info-container">
                                <h1>Leave Info</h1>
                                <hr>
                                <div class="info-box">
                                    <div class="info-grid"><h4>Subject</h4><p></p></div>
                                    <div class="info-grid"><h4>Status</h4><p></p></div>
                                    <div class="info-grid"><h4>Start Date (DD/MM/YYYY)</h4><p></p></div>
                                    <div class="info-grid"><h4>End Date (DD/MM/YYYY)</h4><p></p></div>
                                    <div class="info-grid"><h4>Leave Type</h4><p></p></div>
                                    <div class="info-grid"><h4>Message</h4><p></p></div>
                                    <div class="info-grid"><button class="button">Back</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
    <script src="./javascript/leave.js"></script>
    <script src="./javascript/date.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- LEAVE APPLICATION AJAX -->
    <script>
        $('#leaveForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: 'submit_leave.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    Swal.fire({
                        icon: response.status,
                        title: response.status === 'success' ? 'Leave Application Submitted!' : 'Error',
                        text: response.message,
                        confirmButtonColor: '#20242C'
                    }).then(() => {
                        if (response.status === 'success') {
                            window.location.href = 'leave.php';
                        }
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Submission Failed',
                        text: 'Please fill in all required fields.',
                        confirmButtonColor: '#20242C'
                    });
                }
            });
        });
    </script>

    <!-- VIEW INFO OVERLAY -->
    <script>
        document.querySelectorAll('.view').forEach(button => {
            button.addEventListener('click', function () {
                const overlay = document.getElementById('overlay1');
                const infoContainer = overlay.querySelector('.info-container');
                const infoBox = infoContainer.querySelector('.info-box');
                const infoGrids = infoBox.querySelectorAll('.info-grid p');

                // Get data from button
                const subject = this.getAttribute('data-subject');
                const status = this.getAttribute('data-status').toLowerCase();
                const start = this.getAttribute('data-start');
                const end = this.getAttribute('data-end');
                const type = this.getAttribute('data-type');
                const message = this.getAttribute('data-message');

                // Fill data into <p> tags in order
                infoGrids[0].textContent = subject;
                infoGrids[1].textContent = status.charAt(0).toUpperCase() + status.slice(1);
                infoGrids[2].textContent = start;
                infoGrids[3].textContent = end;
                infoGrids[4].textContent = type;
                infoGrids[5].textContent = message;

                // Reset previous status classes
                infoContainer.classList.remove('approved', 'rejected', 'pending');
                if (['approved', 'rejected', 'pending'].includes(status)) {
                    infoContainer.classList.add(status);
                }

                // Show overlay
                overlay.style.display = 'flex';
            });
        });

        // Hide overlay when "Back" button is clicked
        document.querySelector('.info-box .button').addEventListener('click', function () {
            document.getElementById('overlay1').style.display = 'none';
        });
    </script>

    <!-- FILTER -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const leaveFilter = document.getElementById("leavefilter");
            const statusFilter = document.getElementById("statusfilter");
            const table = document.getElementById("leaveTable");
            const rows = Array.from(table.querySelectorAll("tr")).slice(1); // skip header
            const rowsPerPage = 5;
            let currentPage = 1;

            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");
            const paginationNumbers = document.getElementById("paginationNumbers");

            function displayRows(filteredRows) {
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                rows.forEach(row => row.style.display = "none");
                filteredRows.slice(start, end).forEach(row => row.style.display = "");
            }

            function updatePagination(filteredRows) {
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                paginationNumbers.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.className = 'pagination';
                    pageBtn.style.width = '40px';
                    pageBtn.style.gap = '5px';
                    pageBtn.style.backgroundColor = '#FFFFFF';
                    pageBtn.textContent = i;
                    if (i === currentPage) {
                        pageBtn.style.backgroundColor = '#FFFFFF';
                    }
                    pageBtn.addEventListener('click', () => {
                        currentPage = i;
                        displayRows(filteredRows);
                        updatePagination(filteredRows);
                    });
                    paginationNumbers.appendChild(pageBtn);
                }

                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages;
            }

            function getFilteredRows() {
                const leaveValue = leaveFilter.value.toLowerCase();
                const statusValue = statusFilter.value.toLowerCase();

                return rows.filter(row => {
                    const typeCell = row.cells[5].textContent.toLowerCase();
                    const statusCell = row.cells[6].textContent.toLowerCase();

                    const typeMatch = leaveValue === "" || typeCell === leaveValue;
                    const statusMatch = statusValue === "" || statusCell === statusValue;

                    return typeMatch && statusMatch;
                });
            }

            function render() {
                const filteredRows = getFilteredRows();
                currentPage = 1;
                displayRows(filteredRows);
                updatePagination(filteredRows);
            }

            leaveFilter.addEventListener("change", render);
            statusFilter.addEventListener("change", render);

            prevBtn.addEventListener("click", () => {
                if (currentPage > 1) {
                    currentPage--;
                    const filteredRows = getFilteredRows();
                    displayRows(filteredRows);
                    updatePagination(filteredRows);
                }
            });

            nextBtn.addEventListener("click", () => {
                const filteredRows = getFilteredRows();
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    displayRows(filteredRows);
                    updatePagination(filteredRows);
                }
            });

            // Initial render
            render();
        });
    </script>

</body>

</html>