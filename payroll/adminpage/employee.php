<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/employee.css">
    <title>Employee</title>
</head>

<body>
    <?php include 'header.php'; ?>
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
                    <!-- Separate Container for Statistics -->
                    <div class="file-import-container">
                        <div class="departments-count-container">
                            <label for="total-employees" class="employee-label">Total Employees:</label>
                            <input type="text" id="total-employees" value="43" class="employee-count-box" readonly>
                        </div>
                        <div class="department-count-container">
                            <label for="position-manager" class="department-label">Position Manager:</label>
                            <input type="text" id="position-manager" value="5" class="department-count-box" readonly>
                        </div>
                        <div class="department-count-container">
                            <label for="crew-count" class="department-label">Crew:</label>
                            <input type="text" id="crew-count" value="5" class="department-count-box" readonly>
                        </div>
                        <div class="server-count-container">
                            <label for="server-crew-count" class="department-label">Server Crew:</label>
                            <input type="text" id="server-crew-count" value="5" class="department-count-box" readonly>
                        </div>
                    </div>

                    <div class="leave-requests">
                        <h3>Records</h3>
                        <div class="search-filters">
                            <input type="text" placeholder="Search employee..." class="search-box">
                            <button class="search-btn">Search</button>
                        </div>

                        <!-- View All Button placed above the Action column -->
                        <button class="add-new-btn"><span class="plus-icon">+</span>Add New</button>

                        <table id="leave-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Leave From</th>
                                    <th>Leave To</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="pending-leave">
                                    <td>423491</td>
                                    <td>Ravi</td>
                                    <td>Sick Leave</td>
                                    <td>12/03/2024</td>
                                    <td>15/03/2024</td>
                                    <td>3</td>
                                    <td>Pending</td>
                                    <td>
                                        <button class="view-info">View Info</button>
                                    </td>
                                </tr>
                                <!-- Add more rows as needed for demonstration -->
                            </tbody>
                        </table>

                        <!-- Pagination Section -->
                        <div class="pagination">
                            <p class="results-info">1/100 Results</p>
                            <div class="pagination-controls">
                                <button class="prev-btn">Prev</button>
                                <input type="number" class="page-number" value="1" min="1" max="10"> <!-- Adjust max based on your data -->
                                <button class="next-btn">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SCRIPT -->
    <script>
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        const pageNumberInput = document.querySelector('.page-number');
        const resultsInfo = document.querySelector('.results-info');

        let currentPage = 1;
        const totalPages = 10; // Replace this with the actual number of pages

        // Function to update results display
        function updateResultsDisplay() {
            resultsInfo.textContent = `Showing ${(currentPage - 1) * 10 + 1}/100 Results`; // Example adjusting based on items per page
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

        // Initial display
        updateResultsDisplay();
    </script>
    <script src="./javascript/main.js"></script>
</body>

</html>