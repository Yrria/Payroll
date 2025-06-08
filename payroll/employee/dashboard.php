<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

$emp_id = $_SESSION['emp_id'];

$salaryQuery = "SELECT * FROM tbl_salary WHERE emp_id = '$emp_id' ORDER BY salary_id DESC LIMIT 2";
$salaryResult = mysqli_query($conn, $salaryQuery);

$latestSalary = 0;
$lastSalary = 0;
$upcomingDate = date("F d, Y", strtotime('+1 month'));

if ($salaryResult && mysqli_num_rows($salaryResult) > 0) {
    $salaryRecords = [];
    while ($row = mysqli_fetch_assoc($salaryResult)) {
        $salaryRecords[] = $row;
    }

    $latestSalary = $salaryRecords[0]['total_salary'];
    if (count($salaryRecords) > 1) {
        $lastSalary = $salaryRecords[1]['total_salary'];
    }

    $upcomingDate = date("F d, Y", strtotime("first day of next month"));
}

$totalLeaves = 10;
$leavesTaken = 0;
$pendingLeaves = 0;
$leavesAbsent = 0;

$approvedQuery = "SELECT SUM(no_of_leave) AS total_taken FROM tbl_leave WHERE emp_id = '$emp_id' AND status = 'Approved'";
$approvedResult = mysqli_query($conn, $approvedQuery);
if ($approvedResult && $row = mysqli_fetch_assoc($approvedResult)) {
    $leavesTaken = $row['total_taken'] ?? 0;
}

$pendingQuery = "SELECT SUM(no_of_leave) AS pending FROM tbl_leave WHERE emp_id = '$emp_id' AND status = 'Pending'";
$pendingResult = mysqli_query($conn, $pendingQuery);
if ($pendingResult && $row = mysqli_fetch_assoc($pendingResult)) {
    $pendingLeaves = $row['pending'] ?? 0;
}
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
        <div class="head-title" style="margin: 20px 40px 10px -10px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="margin-top: 50px; margin-right: 730px; font-size: 28px;">Welcome Employee!</h1>
                    <p style="font-size: 14px; color: #666; margin-left: -1px;">Dashboard</p>
                </div>
            </div>
            <hr style="margin-top: 10px;">
        </div>

        <div class="card-container">
            <div class="card card-blue" style="margin-left:-50px;">
                <h3>📅 Upcoming Salary Date</h3>
                <hr />
                <div class="highlight"><?php echo $upcomingDate; ?></div>
            </div>
            <div class="card card-blue">
                <h3>💰 Salary Report</h3>
                <hr />
                <div class="highlight">₱ <?php echo number_format($latestSalary, 0); ?></div>
                <div style="font-size: 14px; margin-left: 115px; margin-top:10px;">
                    Last Salary ₱ <?php echo number_format($lastSalary, 0); ?>
                </div>
            </div>
            <div class="card">
                <h3><i class="fas fa-check-circle" style="margin-right: 8px; color: green;"></i>Remaining Leaves</h3>
                <hr />
                <div style="font-size: 30px;font-weight: bold; text-align:center; margin-top:30px;">
                    <?php
                    $leaveQuery = "SELECT remaining_leave FROM tbl_leave WHERE emp_id = '$emp_id' ORDER BY leave_id DESC LIMIT 1";
                    $leaveResult = mysqli_query($conn, $leaveQuery);
                    echo ($leaveResult && mysqli_num_rows($leaveResult) > 0)
                        ? mysqli_fetch_assoc($leaveResult)['remaining_leave']
                        : "0";
                    ?>
                </div>
            </div>
        </div>

        <div class="card-row" style="margin-left: -10px;">
            <div class="card half-width">
                <h3>Attendance & Leaves</h3>
                <hr />
                <div class="stats-container">
                    <div class="stats-box">
                        <div class="highlight"><?php echo $totalLeaves; ?></div>
                        <div>Total Leaves</div>
                    </div>
                    <div class="stats-box">
                        <div class="highlight"><?php echo $leavesTaken; ?></div>
                        <div>Leaves Taken</div>
                    </div>
                    <div class="stats-box">
                        <div class="highlight"><?php echo $leavesAbsent; ?></div>
                        <div>Leaves Absent</div>
                    </div>
                    <div class="stats-box">
                        <div class="highlight"><?php echo $pendingLeaves; ?></div>
                        <div>Pending Approval</div>
                    </div>
                </div>

                <button class="btn apply-leave-btn" title="Apply Leave">
                    <a href="leave.php" class="btn-link">
                        <i class="fa-solid fa-diagram-next"></i>
                    </a>
                </button>
            </div>

            <div class="calendar-box">
                <div class="calendar-title">Calendar</div>
                <div class="calendar-controls">
                    <button id="prev-month">◀</button>
                    <select id="month-select"></select>
                    <select id="year-select"></select>
                    <button id="next-month">▶</button>
                </div>
                <div class="calendar" id="calendar-grid"></div>
            </div>
        </div>

        <!-- DYNAMIC HOLIDAY SECTION FROM DATABASE -->
        <div class="holiday-section">
            <!-- View All icon (Font Awesome) -->
            <a href="#" class="view-all-icon" onclick="openViewAllModal()" title="View All Events">
                <i class="fa-solid fa-expand"></i>
            </a>

            <div class="holiday-container">
                <h3>Upcoming Holidays and Events</h3>
                <hr />
                <div id="inlineEventContainer">
                    <?php
                    include '../assets/databse/connection.php';

                    $eventQuery = "SELECT * FROM tbl_events WHERE event_date >= CURDATE() ORDER BY event_date ASC";
                    $eventResult = mysqli_query($conn, $eventQuery);

                    $allEvents = [];
                    if ($eventResult && mysqli_num_rows($eventResult) > 0) {
                        $index = 0;
                        while ($event = mysqli_fetch_assoc($eventResult)) {
                            $allEvents[] = $event;
                            if ($index < 3) {
                                $formattedDate = date("d M", strtotime($event['event_date']));
                                echo '<div class="holiday-card event-card">';
                                echo '<span class="holiday-title">' . htmlspecialchars($event['title']) . '</span><br />';
                                echo '<small>' . htmlspecialchars($event['type']) . '</small>';
                                echo '<span class="holiday-date">' . $formattedDate . '</span>';
                                echo '</div>';
                            }
                            $index++;
                        }
                    } else {
                        echo "<p>No upcoming events.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- View All Modal -->
        <div id="viewAllModal" class="modal">
            <div class="modal-content styled-modal">
                <span class="modal-close" onclick="closeViewAllModal()">&times;</span>
                <h3 class="modal-title">All Events</h3>
                <div id="allEventsContainer" class="events-wrapper"></div>
            </div>
        </div>


    </div>

    <script>
        const calendarGrid = document.getElementById("calendar-grid");
        const monthSelect = document.getElementById("month-select");
        const yearSelect = document.getElementById("year-select");

        const prevMonthBtn = document.getElementById("prev-month");
        const nextMonthBtn = document.getElementById("next-month");

        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
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
    <script>
        // ... your existing calendar script ...

        // Toggle view all/show less for events
        const toggleBtn = document.getElementById("toggleEvents");
        if (toggleBtn) {
            toggleBtn.addEventListener("click", function() {
                const cards = document.querySelectorAll(".event-card");
                const hiddenCards = Array.from(cards).filter((c, i) => i >= 3);
                const showingAll = hiddenCards[0].style.display === "block";

                hiddenCards.forEach(card => {
                    card.style.display = showingAll ? "none" : "block";
                });

                this.textContent = showingAll ? "View All" : "Show Less";
            });
        }
    </script>

    <script>
        const allEvents = <?php echo json_encode($allEvents); ?>;

        function openViewAllModal() {
            document.getElementById("viewAllModal").style.display = "flex";
            renderAllEvents();
        }

        function closeViewAllModal() {
            document.getElementById("viewAllModal").style.display = "none";
        }

        function renderAllEvents() {
            const container = document.getElementById("allEventsContainer");
            container.innerHTML = "";

            const sorted = allEvents
                .filter(e => new Date(e.event_date) >= today)
                .sort((a, b) => new Date(a.event_date) - new Date(b.event_date));

            sorted.forEach(event => {
                const d = new Date(event.event_date);
                const day = d.getDate();
                const month = d.toLocaleString("default", {
                    month: "short"
                });
                const weekday = d.toLocaleDateString("en-US", {
                    weekday: "long"
                });

                container.innerHTML += `
      <div class="event-item-box">
        <div class="event-left">
          <span class="event-type-text">${event.title}</span>
          <span class="event-title-text">${event.type}</span>
          <span class="event-weekday-text">${weekday}</span>
        </div>
        <div class="event-date-circle">
          <span class="event-day">${day}</span>
          <span class="event-month">${month}</span>
        </div>
      </div>
    `;
            });
        }

        function closeViewAllModal() {
            document.getElementById("viewAllModal").style.display = "none";
        }
    </script>

</body>

</html>