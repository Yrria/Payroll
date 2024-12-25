<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/dashboard.css">
    <title>Dashboard</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Welcome Admin!</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                </div>
                <hr>
            </div>
            <div class="main-content">
                <div class="sub-content">
                    <div class="content">
                        <!-- Separate boxes for statistics -->
                        <div class="card active-employees" style="margin-top: 130px;">
                            <div class="info">
                                <p>Active Employees</p>
                                <hr>
                                <h2>14</h2>
                            </div>
                        </div>

                        <div class="card pending-leaves" style="margin-top: 130px;">
                            <div class="info">
                                <p>Pending Leaves</p>
                                <hr>
                                <h2>14</h2>
                            </div>
                        </div>

                        <div class="payroll-report">
                            <h3>Payroll Report</h3>
                            <canvas id="payrollChart"></canvas>
                        </div>

                        <div class="salary-distribution">
                            <h3>Salary Distribution</h3>
                            <canvas id="salaryChart"></canvas>
                        </div>

                        <!-- Upcoming Holidays and Event Section -->
                        <div class="Event">
                            <div class="Event-header">
                                <h3>Upcoming Holidays and Event</h3>
                                <a href="#" class="view-all">View All</a>
                            </div>

                            <!-- Event 1 -->
                            <div class="event-item">
                                <div class="event-details">
                                    <span class="event-title">NINOY AQUINO DAY</span>
                                    <span class="event-type">National : Special Holiday</span>
                                    <span class="event-day">Wednesday</span>
                                </div>
                                <div class="event-date">
                                    14<span>Nov</span>
                                </div>
                            </div>

                            <!-- Event 2 -->
                            <div class="event-item">
                                <div class="event-details">
                                    <span class="event-title">NINOY AQUINO DAY</span>
                                    <span class="event-type">National : Regular Holiday</span>
                                    <span class="event-day">Thursday</span>
                                </div>
                                <div class="event-date">
                                    08<span>Feb</span>
                                </div>
                            </div>

                            <!-- Event 3 -->
                            <div class="event-item">
                                <div class="event-details">
                                    <span class="event-title" style="color: #007bff;">COMPANY HOLIDAY</span>
                                    <span class="event-type">National : Regular Holiday</span>
                                </div>
                                <div class="event-date">
                                    23<span>Jan</span>
                                </div>
                            </div>
                        </div>

                        <div class="calendar">
                            <h3>Calendar</h3>
                            <div class="calendar-content">
                                <div class="calendar-header">
                                    <select id="monthSelect"></select>
                                    <select id="yearSelect"></select>
                                </div>
                                <div class="calendar">
                                    <div class="calendar-content">
                                        <div class="calendar-days">
                                            <div>Sun</div>
                                            <div>Mon</div>
                                            <div>Tue</div>
                                            <div>Wed</div>
                                            <div>Thu</div>
                                            <div>Fri</div>
                                            <div>Sat</div>
                                        </div>
                                        <div class="calendar-dates" id="dates"></div>
                                    </div>
                                </div>
                                <div class="calendar-dates" id="dates"></div>
                            </div>

                            <!-- Modal for Adding Reminders -->
                            <div id="reminderModal" class="modal">
                                <div class="modal-content">
                                    <span class="close" id="closeModal">&times;</span>
                                    <span id="selectedDate"></span>
                                    <textarea id="noteInput" placeholder="Add a reminder..."></textarea>
                                    <button id="addNoteButton"><span>Post</span></button>
                                    <ul id="notesList"></ul>
                                </div>
                            </div>
                        </div>

                        <div class="card employee-distribution">
                            <h3>Employee Distribution by Position</h3>
                            <canvas id="positionChart"></canvas>
                        </div>

                        <div class="leave-requests">
                            <h3>Leave Requests</h3>

                            <!-- View All Button placed above the Action column -->
                            <button id="view-all-btn" class="view-all-btn">View All</button>

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
                                            <button class="approve">Approve</button>
                                            <button class="reject">Reject</button>
                                        </td>
                                    </tr>
                                    <!-- Hidden rows that will appear when View All is clicked -->
                                    <tr class="pending-leave" style="display:none;">
                                        <td>423492</td>
                                        <td>John</td>
                                        <td>Vacation Leave</td>
                                        <td>16/03/2024</td>
                                        <td>20/03/2024</td>
                                        <td>4</td>
                                        <td>Pending</td>
                                        <td>
                                            <button class="approve">Approve</button>
                                            <button class="reject">Reject</button>
                                        </td>
                                    </tr>
                                    <tr class="pending-leave" style="display:none;">
                                        <td>423493</td>
                                        <td>Jane</td>
                                        <td>Casual Leave</td>
                                        <td>18/03/2024</td>
                                        <td>20/03/2024</td>
                                        <td>2</td>
                                        <td>Pending</td>
                                        <td>
                                            <button class="approve">Approve</button>
                                            <button class="reject">Reject</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- SCRIPT -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    // Payroll Chart (Line Chart)
                    const ctxPayroll = document.getElementById('payrollChart').getContext('2d');
                    const payrollChart = new Chart(ctxPayroll, {
                        type: 'line', // Keep as line chart
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May'],
                            datasets: [{
                                    label: 'Paid',
                                    data: [10000, 12000, 15000, 18000, 20000],
                                    borderColor: '#4caf50',
                                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                                    fill: true,
                                    tension: 0.1 // Smooth curves
                                },
                                {
                                    label: 'Unpaid',
                                    data: [5000, 7000, 8000, 6000, 10000],
                                    borderColor: '#f44336',
                                    backgroundColor: 'rgba(244, 67, 54, 0.2)',
                                    fill: true,
                                    tension: 0.1
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return `${tooltipItem.dataset.label}: $${tooltipItem.raw}`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Amount ($)'
                                    },
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Months'
                                    },
                                }
                            }
                        },
                    });

                    // Salary Distribution Chart (Line Chart)
                    const ctxSalary = document.getElementById('salaryChart').getContext('2d');
                    const salaryChart = new Chart(ctxSalary, {
                        type: 'line',
                        data: {
                            labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                            datasets: [{
                                label: 'Salary (in thousands)',
                                data: [30000, 40000, 35000, 45000],
                                borderColor: '#2196f3',
                                backgroundColor: 'rgba(33, 150, 243, 0.2)',
                                fill: true,
                                tension: 0.1,
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return `${tooltipItem.dataset.label}: $${tooltipItem.raw}k`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Salary (k $)'
                                    },
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Quarters'
                                    },
                                }
                            }
                        },
                    });

                    // Employee Distribution by Position Chart (Pie Chart)
                    const ctxEmployee = document.getElementById('positionChart').getContext('2d');
                    const employeeChart = new Chart(ctxEmployee, {
                        type: 'pie', // Pie chart for distribution
                        data: {
                            labels: ['Manager', 'Crew Member', 'Executive'],
                            datasets: [{
                                label: 'Employee Distribution',
                                data: [25, 50, 15], // Example data
                                backgroundColor: ['#4caf50', '#2196f3', '#ff9800'],
                                borderColor: '#fff',
                                borderWidth: 2,
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return `${tooltipItem.label}: ${tooltipItem.raw}`;
                                        }
                                    }
                                }
                            }
                        },
                    });
                </script>
                <script>
                    const monthNames = [
                        "January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"
                    ];

                    const notesList = document.getElementById('notesList');
                    const noteInput = document.getElementById('noteInput');
                    const addNoteButton = document.getElementById('addNoteButton');
                    const datesContainer = document.getElementById('dates');
                    const monthSelect = document.getElementById('monthSelect');
                    const yearSelect = document.getElementById('yearSelect');
                    const reminderModal = document.getElementById('reminderModal');
                    const selectedDateElement = document.getElementById('selectedDate');
                    const closeModal = document.getElementById('closeModal');

                    let currentYear = new Date().getFullYear();
                    let currentMonth = new Date().getMonth(); // Start with current month
                    let clickedDate = null;

                    function populateYearSelect() {
                        for (let year = 2020; year <= 2030; year++) { // Adjust the range of years as needed
                            const option = document.createElement('option');
                            option.value = year;
                            option.innerText = year;
                            if (year === currentYear) {
                                option.selected = true;
                            }
                            yearSelect.appendChild(option);
                        }
                    }

                    function populateMonthSelect() {
                        monthNames.forEach((month, index) => {
                            const option = document.createElement('option');
                            option.value = index;
                            option.innerText = month;
                            if (index === currentMonth) {
                                option.selected = true;
                            }
                            monthSelect.appendChild(option);
                        });
                    }

                    function generateCalendar(month, year) {
                        datesContainer.innerHTML = "";
                        const daysInMonth = new Date(year, month + 1, 0).getDate();
                        const firstDay = new Date(year, month, 1).getDay();

                        // Fill empty divs for days before the first day of the month
                        for (let i = 0; i < firstDay; i++) {
                            const emptyDiv = document.createElement('div');
                            datesContainer.appendChild(emptyDiv);
                        }

                        // Fill in the actual days
                        for (let day = 1; day <= daysInMonth; day++) {
                            const dateDiv = document.createElement('div');
                            dateDiv.className = 'date';
                            dateDiv.innerText = day;

                            // Highlight today's date
                            if (day === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear()) {
                                dateDiv.classList.add('today');
                            }

                            // Weekend styling
                            const dateObj = new Date(year, month, day);
                            const dayOfWeek = dateObj.getDay();
                            if (dayOfWeek === 0 || dayOfWeek === 6) { // Sunday or Saturday
                                dateDiv.classList.add('weekend');
                            }

                            // Example: Highlighting holidays (you can adjust the conditions for holidays)
                            if ((month === 10 && day === 14) || (month === 1 && day === 23)) { // Add conditions for holidays
                                dateDiv.classList.add('holiday');
                            }

                            // Make the date clickable
                            dateDiv.addEventListener('click', function() {
                                clickedDate = day; // Store the clicked date
                                selectedDateElement.innerText = `${day} ${monthNames[month]} ${year}`; // Show selected date in modal
                                reminderModal.style.display = "block"; // Show the modal
                            });

                            datesContainer.appendChild(dateDiv);
                        }
                    }

                    function addNote() {
                        const noteText = noteInput.value.trim();
                        if (noteText) {
                            const listItem = document.createElement('li');
                            listItem.innerText = `${selectedDateElement.innerText}: ${noteText}`;
                            notesList.appendChild(listItem);
                            noteInput.value = ""; // Clear the input
                        }
                    }

                    addNoteButton.addEventListener('click', addNote);

                    closeModal.addEventListener('click', function() {
                        reminderModal.style.display = "none"; // Hide the modal
                    });

                    window.addEventListener('click', function(event) {
                        if (event.target === reminderModal) {
                            reminderModal.style.display = "none"; // Hide modal if click outside
                        }
                    });

                    monthSelect.addEventListener('change', function() {
                        currentMonth = parseInt(monthSelect.value);
                        currentYear = parseInt(yearSelect.value);
                        generateCalendar(currentMonth, currentYear);
                    });

                    yearSelect.addEventListener('change', function() {
                        currentYear = parseInt(yearSelect.value);
                        currentMonth = parseInt(monthSelect.value);
                        generateCalendar(currentMonth, currentYear);
                    });

                    // Populate dropdowns and generate the initial calendar
                    populateMonthSelect();
                    populateYearSelect();
                    generateCalendar(currentMonth, currentYear);




                    const eventsContainer = document.querySelector(".Event"); // Container for events

                    function addReminderToEvents(date, reminder) {
                        // Format the date for the event display
                        const eventDate = new Date(date);
                        const day = eventDate.getDate();
                        const month = monthNames[eventDate.getMonth()].slice(0, 3); // Get abbreviated month
                        const weekday = eventDate.toLocaleDateString("en-US", {
                            weekday: "long"
                        });

                        // Create the event item
                        const eventItem = document.createElement("div");
                        eventItem.classList.add("event-item");

                        eventItem.innerHTML = `
        <div class="event-details">
            <span class="event-title">${reminder}</span>
            <span class="event-type">Personal Reminder</span>
            <span class="event-day">${weekday}</span>
        </div>
        <div class="event-date">
            ${day}<span>${month}</span>
        </div>
    `;

                        // Append the event to the events section
                        eventsContainer.appendChild(eventItem);
                    }

                    function addNote() {
                        const noteText = noteInput.value.trim();
                        if (noteText) {
                            // Get the selected date
                            const [day, monthName, year] = selectedDateElement.innerText.split(" ");
                            const monthIndex = monthNames.indexOf(monthName);
                            const fullDate = `${year}-${monthIndex + 1}-${day}`;

                            // Add the note to the notes list
                            const listItem = document.createElement("li");
                            listItem.innerText = `${selectedDateElement.innerText}: ${noteText}`;
                            notesList.appendChild(listItem);

                            // Add the note to the "Upcoming Holidays and Event" section
                            addReminderToEvents(fullDate, noteText);

                            noteInput.value = ""; // Clear the input
                        }
                    }

                    // Select all approve and reject buttons
                    const approveButtons = document.querySelectorAll('.approve');
                    const rejectButtons = document.querySelectorAll('.reject');

                    // Function to handle update status
                    function updateStatus(button, status) {
                        const row = button.closest('tr'); // Get the closest row to the button clicked
                        const statusCell = row.querySelector('td:nth-child(7)'); // Target the status cell (7th column)

                        // Update status
                        statusCell.textContent = status;
                        statusCell.style.color = status === 'Approved' ? '#4CAF50' : '#f44336';

                        // Hide approve/reject buttons after action
                        row.querySelector('.approve')?.classList.add('hidden');
                        row.querySelector('.reject')?.classList.add('hidden');

                        // Optionally add a class to center the remaining button
                        row.querySelector(status === 'Approved' ? '.approve' : '.reject')?.classList.add('single-button');
                    }

                    // Approve Button in Main Table
                    approveButtons.forEach((button) => {
                        button.addEventListener('click', () => {
                            updateStatus(button, 'Approved');
                        });
                    });

                    // Reject Button in Main Table
                    rejectButtons.forEach((button) => {
                        button.addEventListener('click', () => {
                            updateStatus(button, 'Rejected');
                        });
                    });

                    // Handle View All Button Click
                    document.getElementById('view-all-btn').addEventListener('click', function() {
                        // Reveal all hidden rows
                        const hiddenRows = document.querySelectorAll('.pending-leave[style="display:none;"]');
                        hiddenRows.forEach(function(row) {
                            row.style.display = 'table-row'; // Reveal hidden rows
                        });

                        // Hide the "View All" button after it's clicked
                        this.style.display = 'none';
                    });
                </script>
                <!-- SCRIPT -->
                <script src="./javascript/main.js"></script>
</body>

</html>