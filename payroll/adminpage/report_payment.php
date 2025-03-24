<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

// Fetch payment data from the database
$query = "SELECT month, cutoff, total FROM tbl_payment ORDER BY FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
$result = mysqli_query($conn, $query);

// Get total results count
$total_query = "SELECT COUNT(*) AS total_results FROM tbl_payment";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_results = $total_row['total_results'];

// Fetch employee payment details
$view_query = "
    SELECT 
        e.emp_id,
        CONCAT(e.lastname, ', ', e.firstname, ' ', e.middlename) AS name,
        i.position,
        p.cutoff AS cutoff1,
        p.cutoff AS cutoff2,
        p.total
    FROM tbl_emp_acc e
    JOIN tbl_emp_info i ON e.emp_id = i.emp_id
    JOIN tbl_payment p ON e.emp_id = p.emp_id
";
$view_result = mysqli_query($conn, $view_query);

// Fetch total payment from tbl_payment
$total_modal_query = "SELECT SUM(total) AS total_payment FROM tbl_payment";
$total_modal_result = mysqli_query($conn, $total_modal_query);
$total_modal_row = mysqli_fetch_assoc($total_modal_result);
$total_modal_payment = $total_modal_row['total_payment'] ? number_format($total_modal_row['total_payment'], 2) : "0.00";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/report_payment.css">
    <title>Report - Payment</title>
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
                    <h5><a href="./report_payment.php">Report-Payment </a></h5>
                </div>
                <hr>
            </div>
            <div class="main-content">
                <div class="sub-content">
                    <div class="selection_div">
                        <p class="label">Yearly Payment</p>
                        <div class="payment_details">
                            <label for="year-picker" class="year_label">Year:</label>
                            <select id="year-picker" class="year-picker">
                                <!-- Year options will be populated by JS -->
                            </select>
                            <p class="total_payment">Total Payment: <span>&#8369;
                                    <?php
                                    $total_query = "SELECT SUM(total) AS total_payment FROM tbl_payment";
                                    $total_result = mysqli_query($conn, $total_query);
                                    $total_row = mysqli_fetch_assoc($total_result);
                                    echo number_format($total_row['total_payment'], 2);
                                    ?>
                                </span></p>
                        </div>
                    </div>
                    <div class="content">
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Cut off 1</th>
                                    <th>Cut off 2</th>
                                    <th>Total</th>
                                    <th>View Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?php echo $row['month']; ?></td>
                                        <td>₱<?php echo number_format($row['cutoff'], 2); ?></td>
                                        <td>₱<?php echo number_format($row['cutoff'], 2); ?></td>
                                        <td>₱<?php echo number_format($row['total'], 2); ?></td>
                                        <td class="td-text">
                                            <div class="action-buttons">
                                                <button class="view-btn" onclick="openModal()">View Info</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination">
                            <p id="pagination-text">Showing 1 / <?php echo $total_results; ?> Results</p>
                            <div class="pagination">
                                <button id="prevPage">Prev</button>
                                <input type="text" class="perpage" id="currentPage" value="1" readonly />
                                <button id="nextPage">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="infoModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h2>RECORDS</h2>
                <div class="modal-header">
                    <div class="search-container">
                        <input type="text" class="search-box" placeholder="Search Employee...">
                        <button class="search-btn">Search</button>
                    </div>
                    <p class="results-count">Showing All Results</p>
                    <p class="total-payment">Total Payment: <span>₱<?php echo $total_modal_payment; ?></span></p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Shift</th>
                            <th>Cut off 1</th>
                            <th>Cut off 2</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($view_result) > 0) { ?>
                            <?php while ($row = mysqli_fetch_assoc($view_result)) { ?>
                                <tr>
                                    <td><?php echo $row['emp_id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['position']; ?></td>
                                    <td></td> <!-- Shift is empty for now -->
                                    <td>₱<?php echo number_format($row['cutoff1'], 2); ?></td>
                                    <td>₱<?php echo number_format($row['cutoff2'], 2); ?></td>
                                    <td>₱<?php echo number_format($row['total'], 2); ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="7" style="text-align: center; font-weight: bold;">No Records Found</td>
                            </tr>
                        <?php } ?>
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

        <!-- SCRIPT -->
        <script>
            // Populate year-picker dropdown
            const yearPicker = document.getElementById('year-picker');
            const currentYear = new Date().getFullYear();
            const startYear = 2000;

            for (let year = startYear; year <= currentYear; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearPicker.appendChild(option);
            }

            // Modal functionality
            function openModal() {
                document.getElementById('infoModal').style.display = 'block';
            }

            function closeModal() {
                document.getElementById('infoModal').style.display = 'none';
            }

            // Close modal when clicking outside
            window.onclick = function(event) {
                const modal = document.getElementById('infoModal');
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            };
            let currentPage = 1;
            const totalResults = <?php echo $total_results; ?>;
            const resultsPerPage = 10;
            let totalPages = Math.max(1, Math.ceil(totalResults / resultsPerPage));

            function updatePagination() {
                const paginationText = document.getElementById('pagination-text');

                if (totalResults === 1) {
                    paginationText.textContent = `Showing 1 / 1 Results`;
                    document.getElementById('prevPage').disabled = true;
                    document.getElementById('nextPage').disabled = true;
                    document.getElementById('currentPage').value = 1;
                } else {
                    paginationText.textContent = `Showing ${Math.min((currentPage - 1) * resultsPerPage + 1, totalResults)} / ${totalResults} Results`;
                    document.getElementById('prevPage').disabled = currentPage === 1;
                    document.getElementById('nextPage').disabled = currentPage === totalPages;
                    document.getElementById('currentPage').value = currentPage;
                }
            }

            document.getElementById('prevPage').addEventListener('click', function() {
                if (currentPage > 1 && totalResults > 1) {
                    currentPage--;
                    updatePagination();
                }
            });

            document.getElementById('nextPage').addEventListener('click', function() {
                if (currentPage < totalPages && totalResults > 1) {
                    currentPage++;
                    updatePagination();
                }
            });

            updatePagination();

            document.querySelector('.search-btn').addEventListener('click', function() {
                const searchBox = document.querySelector('.search-box');
                let searchValue = searchBox.value.toLowerCase().trim();
                const tableRows = document.querySelectorAll('#infoModal tbody tr');
                let found = false;

                // Regular expression to detect special characters
                const specialCharPattern = /[^a-zA-Z0-9\s]/g;
                let isSpecialChar = specialCharPattern.test(searchValue);

                tableRows.forEach(row => {
                    const name = row.cells[1].textContent.toLowerCase(); // Employee Name column
                    if (searchValue === '') {
                        row.style.display = ''; // Show all rows when search box is empty
                        found = true;
                    } else if (!isSpecialChar && name.includes(searchValue)) {
                        row.style.display = ''; // Show matching rows
                        found = true;
                    } else {
                        row.style.display = 'none'; // Hide non-matching rows or when special characters are present
                    }
                });

                // Get or create "No Records Found" row
                let noRecordsRow = document.getElementById('noRecordsRow');
                if (!found) {
                    if (!noRecordsRow) {
                        noRecordsRow = document.createElement('tr');
                        noRecordsRow.id = 'noRecordsRow';
                        noRecordsRow.innerHTML = `<td colspan="7" style="text-align: center; font-weight: bold;">No Records Found</td>`;
                        document.querySelector('#infoModal tbody').appendChild(noRecordsRow);
                    }
                } else if (noRecordsRow) {
                    noRecordsRow.remove(); // Remove "No Records Found" row when results are found or search box is empty
                }
            });

            // Ensure the "No Records Found" message disappears when clearing the search box
            document.querySelector('.search-box').addEventListener('input', function() {
                if (this.value.trim() === '') {
                    document.querySelectorAll('#infoModal tbody tr').forEach(row => row.style.display = '');
                    let noRecordsRow = document.getElementById('noRecordsRow');
                    if (noRecordsRow) noRecordsRow.remove();
                } else {
                    document.querySelector('.search-btn').click();
                }
            });
        </script>

</body>

</html>