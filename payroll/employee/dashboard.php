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
    <link rel="stylesheet" href="./css/main.css" />
    <link rel="stylesheet" href="./css/dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Dashboard</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="main-content">
        <h1 style="margin-top: 70px; margin-left:-800px;">Welcome Employee!</h1>
        <p class="section-header">Dashboard</p>
        <hr />

        <div class="card-container">
            <div class="card card-blue" style="margin-left:-50px;">
                <h3>📅 Upcoming Salary Date</h3>
                <hr />
                <div class="highlight">November 16, 2024</div>
            </div>
            <div class="card card-blue">
                <h3>💰 Salary Report</h3>
                <hr />
                <div class="highlight">₱ 15,000</div>
                <div style="font-size: 14px; margin-left: 115px; margin-top:10px;">Last Salary ₱ 10,000</div>
            </div>
            <div class="card">
    <h3><i class="fas fa-check-circle" style="margin-right: 8px; color: green;"></i>Remaining Leaves</h3>
    <hr />
    <div style="font-size: 30px;font-weight: bold; text-align:center; margin-top:30px;">
        <?php
        // Fetch employee ID from session
        $emp_id = $_SESSION['emp_id']; // Adjust this key if it's different in your session

        // Fetch remaining leaves from tbl_leave
        $leaveQuery = "SELECT remaining_leave FROM tbl_leave WHERE emp_id = '$emp_id' ORDER BY leave_id DESC LIMIT 1";
        $leaveResult = mysqli_query($conn, $leaveQuery);

        if ($leaveResult && mysqli_num_rows($leaveResult) > 0) {
            $leaveRow = mysqli_fetch_assoc($leaveResult);
            echo $leaveRow['remaining_leave'];
        } else {
            echo "0"; // fallback if no leave record
        }
        ?>
    </div>
</div>
        </div>

        <!-- Calendar and Attendance beside each other -->
        <div class="card-row" style="margin-left: -10px;">
            <div class="card half-width">
                <h3>Attendance & Leaves</h3>
                <hr />
                <div class="stats-container">
                    <div class="stats-box">
                        <div class="highlight">10</div>
                        <div>Total Leaves</div>
                    </div>
                    <div class="stats-box">
                        <div class="highlight">3</div>
                        <div>Leaves Taken</div>
                    </div>
                    <div class="stats-box">
                        <div class="highlight">1</div>
                        <div>Leaves Absent</div>
                    </div>
                    <div class="stats-box">
                        <div class="highlight">1</div>
                        <div>Pending Approval</div>
                    </div>
                </div>
                <button class="btn">
                    <a href="leave.php" class="btn-link">Apply Leave</a>
                </button>
            </div>

            <div class="calendar-box" >
                <div class="calendar-title">Calendar</div>
                <div class="calendar-controls">
                    <button id="prev-month">◀</button>
                    <select id="month-select"></select>
                    <select id="year-select"></select>
                    <button id="next-month">▶</button>
                </div>
                <div class="calendar" id="calendar-grid">
                    <!-- Calendar grid will be injected here -->
                </div>
            </div>
        </div>

        <div class="holiday-section">
            <div class="holiday-container">
                <h3>Upcoming Holidays and Events</h3>
                <hr />
                <div class="holiday-card">
                    <span class="holiday-title">NINOY AQUINO DAY</span><br />
                    <small>National : Special Holiday</small>
                    <span class="holiday-date">14 Nov</span>
                </div>
                <div class="holiday-card">
                    <span class="holiday-title">NINOY AQUINO DAY</span><br />
                    <small>National : Regular Holiday</small>
                    <span class="holiday-date">08 Feb</span>
                </div>
                <div class="holiday-card">
                    <span class="holiday-title">COMPANY HOLIDAY</span><br />
                    <small>National : Regular Holiday</small>
                    <span class="holiday-date">23 Jan</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        const calendarGrid = document.getElementById("calendar-grid");
        const monthSelect = document.getElementById("month-select");
        const yearSelect = document.getElementById("year-select");

        const prevMonthBtn = document.getElementById("prev-month");
        const nextMonthBtn = document.getElementById("next-month");

        const months = [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

        const today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();

        function populateSelectors() {
            monthSelect.innerHTML = "";
            yearSelect.innerHTML = "";
            months.forEach((m, i) => {
                let opt = new Option(m, i);
                if (i === currentMonth) opt.selected = true;
                monthSelect.add(opt);
            });

            for (let y = currentYear - 10; y <= currentYear + 10; y++) {
                let opt = new Option(y, y);
                if (y === currentYear) opt.selected = true;
                yearSelect.add(opt);
            }
        }

        function renderCalendar(month, year) {
            calendarGrid.innerHTML = "";

            const days = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];
            days.forEach(day => {
                const el = document.createElement("div");
                el.textContent = day;
                el.style.fontWeight = "bold";
                calendarGrid.appendChild(el);
            });

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) {
                calendarGrid.appendChild(document.createElement("div"));
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const el = document.createElement("div");
                el.textContent = day;

                if (
                    day === today.getDate() &&
                    month === today.getMonth() &&
                    year === today.getFullYear()
                ) {
                    el.classList.add("today");
                }

                calendarGrid.appendChild(el);
            }
        }

        function updateCalendar() {
            currentMonth = parseInt(monthSelect.value);
            currentYear = parseInt(yearSelect.value);
            renderCalendar(currentMonth, currentYear);
        }

        monthSelect.addEventListener("change", updateCalendar);
        yearSelect.addEventListener("change", updateCalendar);

        prevMonthBtn.addEventListener("click", () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            monthSelect.value = currentMonth;
            yearSelect.value = currentYear;
            updateCalendar();
        });

        nextMonthBtn.addEventListener("click", () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            monthSelect.value = currentMonth;
            yearSelect.value = currentYear;
            updateCalendar();
        });

        populateSelectors();
        renderCalendar(currentMonth, currentYear);
    </script>
</body>

</html>