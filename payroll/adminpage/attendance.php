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
    <link rel="stylesheet" href="./css/attendance.css">
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
                        <h3>Records</h3>
                        <!-- Search Bar and Filters -->
                        <div class="search-filters">
                            <input type="text" placeholder="Search employee..." class="search-box">
                            <button class="search-btn">Search</button>
                        </div>

                        <select class="add-new-btn1">
                            <option>Cut Off 1</option>
                            <option>Cut Off 2</option>
                        </select>

                        <select class="add-new-btn2">
                            <option>Month</option>
                            <option>January</option>
                            <option>February</option>
                            <option>March</option>
                            <option>April</option>
                            <option>May</option>
                            <option>June</option>
                            <option>July</option>
                            <option>August</option>
                            <option>September</option>
                            <option>October</option>
                            <option>November</option>
                            <option>December</option>
                        </select>

                        <select class="add-new-btn3">
                            <option>Year</option>
                            <option>2022</option>
                            <option>2023</option>
                            <option>2024</option>
                            <option>2025</option>
                        </select>

                        <table id="leave-table">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Shift</th>
                                    <th>Total Present</th>
                                    <th>Total Hours</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="pending-leave">
                                    <td>Emp 001</td>
                                    <td>Michael</td>
                                    <td>Crew</td>
                                    <td>Day</td>
                                    <td>20</td>
                                    <td>160</td>
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
                                <input type="number" class="page-number" value="1" min="1" max="10">
                                <button class="next-btn">Next</button>
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
    <script src="./javascript/main.js"></script>
</body>

</html>