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
                        <p class="label">Annual dues</p>
                        <div class="payment_details">
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
                            <div class="dropdown-wrapper">
                                <input type="text" class="dropdown-input" style="width:75%;" readonly placeholder="Select Cutoff" />
                                <div class="dropdown-indicator">&#9662;</div>
                                <div class="dropdown-content">
                                    <div class="dropdown-item clear-selection" data-value="" style="opacity: 0.5;">Select CutOff</div>
                                    <div class="dropdown-item" data-value="01" style="font-size: 14px;">CutOff1</div>
                                    <div class="dropdown-item" data-value="02" style="font-size: 14px;">CutOff2</div>
                                </div>
                            </div>
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
                            <thead >
                                <tr >
                                    <th>Month</th>
                                    <th>Cut off 1</th>
                                    <th>Cut off 2</th>
                                    <th>Total</th>
                                    <th>View Details</th>
                                </tr>
                            </thead>
                            <tbody id="showdata">
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                        <tr data-month="<?php echo strtolower($row['month']); ?>">
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
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; font-weight: bold;">No Records Found</td>
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
                    <tbody id="showdata">
                        <?php
                        if (mysqli_num_rows($view_result) > 0) {
                            while ($row = mysqli_fetch_assoc($view_result)) {
                                if (!empty($row['m_name'])) {
                                    $fullname = $row['l_name'] . " , " . $row['f_name'] . " , " . $row['m_name'] . ".";
                                    $_SESSION['fullname'] = $fullname;
                                } else {
                                    $fullname = $row['l_name'] . " , " . $row['f_name'];
                                    $_SESSION['fullname'] = $fullname;
                                }
                        ?>
                                <tr>
                                    <td><?php echo $row['emp_id']; ?></td>
                                    <td><?php echo htmlspecialchars($fullname); ?></td>
                                    <td><?php echo $row['position']; ?></td>
                                    <td><?php echo $row['emp_shift']; ?></td>
                                    <td>₱<?php echo number_format($row['cutoff1'], 2); ?></td>
                                    <td>₱<?php echo number_format($row['cutoff2'], 2); ?></td>
                                    <td>₱<?php echo number_format($row['total'], 2); ?></td>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>
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
            document.querySelectorAll('.dropdown-wrapper').forEach(wrapper => {
                const input = wrapper.querySelector('.dropdown-input');
                const content = wrapper.querySelector('.dropdown-content');
                const indicator = wrapper.querySelector('.dropdown-indicator');
                const items = wrapper.querySelectorAll('.dropdown-item');

                input.addEventListener('click', () => content.classList.toggle('show'));
                indicator.addEventListener('click', () => content.classList.toggle('show'));

                items.forEach(item => {
                    item.addEventListener('click', () => {
                        const selectedMonth = item.textContent.trim().toLowerCase();
                        const tableRows = document.querySelectorAll('table tbody tr');
                        let visibleCount = 0;

                        // If "Select Month" is clicked, reset everything to show all rows
                        if (selectedMonth === '' || selectedMonth === 'select month') {
                            input.value = 'Select Month';
                            input.setAttribute('data-value', '');
                            content.classList.remove('show');

                            // Reset all rows to show
                            tableRows.forEach(row => {
                                row.style.display = ''; // Show all rows
                            });

                            // Handle "No Records Found" row
                            let noRow = document.getElementById('noRecordsFound');
                            if (noRow) noRow.remove(); // Remove "No Records Found" if it exists

                            // Reset any other states like pagination if needed here
                            return; // Do not proceed further if "Select Month" is clicked
                        }

                        input.value = item.textContent.trim();
                        input.setAttribute('data-value', item.getAttribute('data-value'));
                        content.classList.remove('show');

                        // Filter rows based on the selected month
                        tableRows.forEach(row => {
                            const rowMonth = row.getAttribute('data-month');
                            if (!selectedMonth || rowMonth === selectedMonth) {
                                row.style.display = ''; // Show matching rows
                                visibleCount++;
                            } else {
                                row.style.display = 'none'; // Hide non-matching rows
                            }
                        });

                        // Handle "No Records Found" message
                        let noRow = document.getElementById('noRecordsFound');
                        if (visibleCount === 0) {
                            if (!noRow) {
                                noRow = document.createElement('tr');
                                noRow.id = 'noRecordsFound';
                                noRow.innerHTML = `<td colspan="5" style="text-align:center; font-weight:bold;">No Records Found</td>`;
                                document.querySelector('table tbody').appendChild(noRow);
                            }
                        } else if (noRow) {
                            noRow.remove();
                        }
                    });
                });

                document.addEventListener('click', e => {
                    if (!wrapper.contains(e.target)) content.classList.remove('show');
                });
            });

            document.querySelector('.search-box').addEventListener('input', function() {
                const searchValue = this.value.toLowerCase().trim();
                const tableRows = document.querySelectorAll('#infoModal tbody tr');
                let found = false;
                const specialCharPattern = /[^a-zA-Z0-9\s]/g;
                let isSpecialChar = specialCharPattern.test(searchValue);

                tableRows.forEach(row => {
                    const name = row.cells[1].textContent.toLowerCase();
                    if (searchValue === '') {
                        row.style.display = '';
                        found = true;
                    } else if (!isSpecialChar && name.includes(searchValue)) {
                        row.style.display = '';
                        found = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                let noRecordsRow = document.getElementById('noRecordsRow');
                if (!found) {
                    if (!noRecordsRow) {
                        noRecordsRow = document.createElement('tr');
                        noRecordsRow.id = 'noRecordsRow';
                        noRecordsRow.innerHTML = `<td colspan="7" style="text-align: center; font-weight: bold;">No Records Found</td>`;
                        document.querySelector('#infoModal tbody').appendChild(noRecordsRow);
                    }
                } else if (noRecordsRow) {
                    noRecordsRow.remove();
                }
            });

            // Modal functionality
            function openModal() {
                document.getElementById('infoModal').style.display = 'block';
            }

            function closeModal() {
                document.getElementById('infoModal').style.display = 'none';
            }

            // Close modal when clicking outside of it
            window.onclick = function(event) {
                const modal = document.getElementById('infoModal');
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            }

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

            function updatePagination() {
                const paginationText = document.getElementById('pagination-text');
                const tableRows = document.querySelectorAll('.content table tbody tr');

                // Hide all rows initially
                tableRows.forEach(row => row.style.display = 'none');

                const startIndex = (currentPage - 1) * resultsPerPage;
                const endIndex = startIndex + resultsPerPage;

                let visibleCount = 0;
                for (let i = 0; i < tableRows.length; i++) {
                    if (i >= startIndex && i < endIndex) {
                        tableRows[i].style.display = '';
                        visibleCount++;
                    }
                }

                paginationText.textContent = `Showing ${startIndex + 1} - ${startIndex + visibleCount} of ${totalResults} Results`;
                document.getElementById('currentPage').value = currentPage;
            }

            document.getElementById('prevPage').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    updatePagination();
                }
            });

            document.getElementById('nextPage').addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    updatePagination();
                }
            });

            // Call once on load
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

            document.querySelectorAll('.dropdown-item.clear-selection').forEach(item => {
                item.addEventListener('click', function() {
                    const label = this.textContent.trim().toLowerCase();
                    if (label === 'select year' || label === 'select cutoff') {
                        location.reload(); // This will refresh the page
                    }
                });
            });
        </script>

</body>

</html>