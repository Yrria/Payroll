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
                            <p class="total_payment">Total Payment: <span>&#8369;3,120,000</span></p>
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
                                <tr>
                                    <td>January</td>
                                    <td>₱120,000</td>
                                    <td>₱120,000</td>
                                    <td>₱240,000</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn" onclick="openModal()">View Info</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>February</td>
                                    <td>₱120,000</td>
                                    <td>₱120,000</td>
                                    <td>₱240,000</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn" onclick="openModal()">View Info</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>March</td>
                                    <td>₱120,000</td>
                                    <td>₱120,000</td>
                                    <td>₱240,000</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn" onclick="openModal()">View Info</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>April</td>
                                    <td>₱120,000</td>
                                    <td>₱120,000</td>
                                    <td>₱240,000</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn" onclick="openModal()">View Info</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>May</td>
                                    <td>₱120,000</td>
                                    <td>₱120,000</td>
                                    <td>₱240,000</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn" onclick="openModal()">View Info</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>June</td>
                                    <td>₱120,000</td>
                                    <td>₱120,000</td>
                                    <td>₱240,000</td>
                                    <td class="td-text">
                                        <div class="action-buttons">
                                            <button class="view-btn" onclick="openModal()">View Info</button>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <br>
                        <!-- Pagination -->
                        <div class="pagination">
                            <p>Showing 1 / 100 Results</p>
                            <div class="pagination">
                                <button>Prev</button>
                                <input type="text" class="perpage" value="1" readonly />
                                <button>Next</button>
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
                    <p class="total-payment">Total Payment: <span>&#8369;120,000</span></p>
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
                        <tr>
                            <td>1001</td>
                            <td>John Doe</td>
                            <td>Manager</td>
                            <td>Day</td>
                            <td>₱60,000</td>
                            <td>₱60,000</td>
                            <td>₱120,000</td>
                        </tr>
                        <tr>
                            <td>1001</td>
                            <td>John Doe</td>
                            <td>Manager</td>
                            <td>Day</td>
                            <td>₱60,000</td>
                            <td>₱60,000</td>
                            <td>₱120,000</td>
                        </tr>
                        <tr>
                            <td>1001</td>
                            <td>John Doe</td>
                            <td>Manager</td>
                            <td>Day</td>
                            <td>₱60,000</td>
                            <td>₱60,000</td>
                            <td>₱120,000</td>
                        </tr>
                        <tr>
                            <td>1001</td>
                            <td>John Doe</td>
                            <td>Manager</td>
                            <td>Day</td>
                            <td>₱60,000</td>
                            <td>₱60,000</td>
                            <td>₱120,000</td>
                        </tr>
                        <tr>
                            <td>1001</td>
                            <td>John Doe</td>
                            <td>Manager</td>
                            <td>Day</td>
                            <td>₱60,000</td>
                            <td>₱60,000</td>
                            <td>₱120,000</td>
                        </tr>
                        <tr>
                            <td>1001</td>
                            <td>John Doe</td>
                            <td>Manager</td>
                            <td>Day</td>
                            <td>₱60,000</td>
                            <td>₱60,000</td>
                            <td>₱120,000</td>
                        </tr>
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
        </script>

</body>

</html>