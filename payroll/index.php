<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Dashboard</title>
    <link rel="stylesheet" href="./assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div>
        <header>
            <div class="header-content">
                <span class="app-title">BytePayroll</span>
                <span class="current-date-time">
                    <span id="current-date"></span> <span id="current-time"></span>
                </span>
                <button class="profile-btn"> Admin</button>
            </div>
        </header>
    </div>
    
    <div class="sidebar">
        <h2>
            <span class="toggle-arrow">←</span>
        </h2>
        <div class="sidenav-content">
            <ul>
                <li>
                    <a href="attendance.html">
                        <i class="fas fa-calendar-alt"></i> <span class="sidenav-text">Attendance</span>
                    </a>
                </li>
                <li>
                    <a href="employees.html">
                        <i class="fas fa-users"></i> <span class="sidenav-text">Employees</span>
                    </a>
                </li>
                <li>
                    <a href="payroll.html">
                        <i class="fas fa-money-check-alt"></i> <span class="sidenav-text">Payroll</span>
                    </a>
                </li>
                <li>
                    <a href="leave.html">
                        <i class="fas fa-plane"></i> <span class="sidenav-text">Leave</span>
                    </a>
                </li>
                <li>
                    <a href="report.html">
                        <i class="fas fa-file-alt"></i> <span class="sidenav-text">Report</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- <div class="admin-section">
            </div> -->
    </div>

    <div class="main-content">
        <div class="content">
            <!-- Separate boxes for statistics -->
            <div class="card active-employees" style="width: 300px;">
                <div class="info">
                    <p>Active Employees</p>
                    <br>
                    <h2>14</h2>
                </div>
            </div>

            <div class="card pending-leaves" style="width: 300px;">
                <div class="info">
                    <p>Pending Leaves</p>
                    <br>
                    <h2>14</h2>
                </div>
            </div>
            <!-- Payroll Report Section -->
            <div class="card payroll-report">
                <h3>Payroll Report</h3>
                <canvas id="payrollChart"></canvas>
            </div>

            <!-- Salary Distribution Section -->
            <div class="card salary-distribution" style="width: 700px;">
                <h3>Salary Distribution</h3>
                <canvas id="salaryChart"></canvas>
            </div>

            <!-- Holiday and Events Section -->
            <div class="card holiday-events">
                <h3>Upcoming Holidays and Events</h3>
                <hr>
                <br>
                <ul>
                    <li>National holiday - 12th Jan</li>
                    <li>Annual event - 20th Jan</li>
                </ul>
            </div>

            <!-- Calendar Section -->
            <div class="card calendar">
                <h3>Calendar</h3>
                <div class="calendar-content">
                    <div class="calendar-days">Sun Mon Tue Wed Thu Fri Sat</div>
                    <div class="calendar-dates">
                        <!-- Add actual date cells here -->
                    </div>
                </div>
            </div>

            <!-- Employee Distribution by Position -->
            <div class="card employee-distribution">
                <h3>Employee Distribution by Position</h3>
                <canvas id="positionChart"></canvas>
            </div>

            <!-- Leave Requests Table -->
            <div class="card leave-requests">
                <h3>Leave Requests</h3>
                <table>
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
                        <tr>
                            <td>423491</td>
                            <td>Ravi</td>
                            <td>Sick Leave</td>
                            <td>12/03/2024</td>
                            <td>15/03/2024</td>
                            <td>3</td>
                            <td>Pending</td>
                            <td><button class="approve">Approve</button> <button class="reject">Reject</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Functionality for toggling sidebar and displaying the correct icon
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.querySelector(".sidebar");
            const mainContent = document.querySelector(".main-content");
            const toggleArrow = document.querySelector(".toggle-arrow");

            toggleArrow.addEventListener("click", function() {
                sidebar.classList.toggle("minimized");
                mainContent.classList.toggle("minimized");
                toggleArrow.textContent = sidebar.classList.contains("minimized") ? "→" : "←";
            });

            // Function to update date and time
            function updateDateTime() {
                const now = new Date();

                // Format date
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const dateString = now.toLocaleDateString(undefined, options);

                // Format time
                const timeString = now.toLocaleTimeString();

                // Insert date and time into the header
                document.getElementById('current-date').innerText = dateString;
                document.getElementById('current-time').innerText = timeString;
            }

            // Call the function once when the page loads
            updateDateTime();
        });

        // Payroll Report Chart
        const payrollCtx = document.getElementById('payrollChart').getContext('2d');
        const payrollChart = new Chart(payrollCtx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May'],
                datasets: [{
                    label: 'Payroll',
                    data: [10000, 15000, 13000, 17000, 16000],
                    backgroundColor: '#3b82f6'
                }]
            }
        });

        // Salary Distribution Chart
        const salaryCtx = document.getElementById('salaryChart').getContext('2d');
        const salaryChart = new Chart(salaryCtx, {
            type: 'bar',
            data: {
                labels: ['<20k', '20k-50k', '50k-80k', '>80k'],
                datasets: [{
                    label: 'Salary Distribution',
                    data: [5, 12, 8, 4],
                    backgroundColor: '#6366f1'
                }]
            }
        });

        // Employee Distribution by Position Chart
        const positionCtx = document.getElementById('positionChart').getContext('2d');
        const positionChart = new Chart(positionCtx, {
            type: 'pie',
            data: {
                labels: ['Manager', 'Crew Member', 'Executive'],
                datasets: [{
                    label: 'Position Distribution',
                    data: [5, 8, 1],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b']
                }]
            }
        });
    </script>
</body>

</html>