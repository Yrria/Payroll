<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

$records_per_page = 7; // Number of records to display per page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page number, default to 1

// Calculate the limit clause for SQL query
$start_from = ($current_page - 1) * $records_per_page;

// Initialize variables
$sql = "SELECT * FROM tbl_salary WHERE status = 'Unpaid'";

// Check if search query is provided
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $search_query = $_GET['query'];
    // Modify SQL query to include search filter
    $sql .= "WHERE emp_id LIKE '%$search_query%' 
            OR f_name LIKE '%$search_query%' 
            OR m_name LIKE '%$search_query%' 
            OR l_name LIKE '%$search_query%'
            OR emp_position LIKE '%$search_query%'
            OR emp_shift LIKE '%$search_query%'
            OR total_wage LIKE '%$search_query%'
            OR emp_status LIKE '%$search_query%'";
}

$sql .= "LIMIT $start_from, $records_per_page";

$result = $conn->query($sql);

// Count total number of records
$total_records_query = "SELECT COUNT(*) FROM tbl_salary";
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $total_records_query .= " WHERE emp_id LIKE '%$search_query%' 
                            OR f_name LIKE '%$search_query%' 
                            OR m_name LIKE '%$search_query%' 
                            OR l_name LIKE '%$search_query%'
                            OR emp_position LIKE '%$search_query%'
                            OR emp_shift LIKE '%$search_query%'
                            OR total_wage LIKE '%$search_query%'
                            OR emp_status LIKE '%$search_query%'";
}

$total_records_result = mysqli_query($conn, $total_records_query);
$total_records_row = mysqli_fetch_array($total_records_result);
$total_records = $total_records_row[0];

$total_pages = ceil($total_records / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/payroll.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/3b07bc6295.js" crossorigin="anonymous"></script>
    <title>Payroll</title>
    <style>
        #toastBox {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            background-color: #1BCD80;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            min-width: 200px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease, fadeOut 0.3s ease 2.7s forwards;
        }

        .toast.error {
            background-color: #e74c3c;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }

    </style>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div id="toastBox"></div>
    <audio id="notifySound" src="../assets/sound/Success.mp3" preload="auto"></audio>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Payroll</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                    <span> > </span>
                    <h5><a href="./payroll.php">Create Payslip </a></h5>
                </div>
                <hr>
            </div>

            <div class="main-content">
                <div class="sub-content">
                    <div class="selection_div">
                        <p style="margin: 0;font-weight: 500;">Employee Salary List</p>
                        <div style="display: flex;align-items: center;width:60%;justify-content:right;margin-right:-4%;">
                            <div class="dropdown month-dropdown" style="margin-left: 20%;">
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
                            </div>
                            <div class="dropdown year-dropdown" style="width:25%;">
                                <div class="dropdown-wrapper">
                                    <input type="text" class="dropdown-input" style="width:75%;" readonly placeholder="Select Year" value="" autocomplete="off" />
                                    <div class="dropdown-indicator" style="right:47px;">&#9662;</div>
                                    <div class="dropdown-content">
                                        <div class="dropdown-item clear-selection" data-value="" style="opacity: 0.5;">Select Year</div>
                                        <div class="dropdown-item" data-value="2025" style="font-size: 14px;">2025</div>
                                        <div class="dropdown-item" data-value="2024" style="font-size: 14px;">2024</div>
                                        <div class="dropdown-item" data-value="2023" style="font-size: 14px;">2023</div>
                                        <div class="dropdown-item" data-value="2022" style="font-size: 14px;">2022</div>
                                        <div class="dropdown-item" data-value="2021" style="font-size: 14px;">2021</div>
                                        <div class="dropdown-item" data-value="2020" style="font-size: 14px;">2020</div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown cutoff-dropdown">
                                <div class="dropdown-wrapper">
                                    <input type="text" class="dropdown-input" readonly placeholder="Select Cutoff" />
                                    <div class="dropdown-indicator">&#9662;</div>
                                    <div class="dropdown-content">
                                        <div class="dropdown-item clear-selection" data-value="" style="opacity: 0.5;">Select Cutoff</div>
                                        <div class="dropdown-item" data-value="F" style="font-size: 14px;">First Cutoff</div>
                                        <div class="dropdown-item" data-value="S" style="font-size: 14px;">Second Cutoff</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="payroll_contents">
                        <!-- Title and search-bar -->
                        <div class="search" style="display: flex; align-items: center; width: 320px;">
                            <input type="text" id="search_emp_input" class="search_emp_input" placeholder="Search employee..." />
                            <button id="voiceSearchBtn" title="Speak to search" style="
                                margin-left: 5px;
                                background: none;
                                cursor: pointer;
                                border: 0;
                            ">
                                <i class="bi bi-mic-fill" style="font-size: 1.35rem; color:#20242C;"></i>
                            </button>
                        </div>


                        <!-- Leave Table -->
                        <table>
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Shift</th>
                                    <!-- <th>Total Wage</th> -->
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="showdata">
                                <?php 
                                    if($result->num_rows > 0){
                                        while($row = $result->fetch_assoc()){
                                            if(!empty($row['m_name'])){
                                                $fullname = $row['l_name'] . " , ". $row['f_name'] . " , " . $row['m_name'] . ".";
                                                $_SESSION['fullname'] =$fullname;
                                            }
                                            else{
                                                $fullname = $row['l_name'] . " , ". $row['f_name'];
                                                $_SESSION['fullname'] =$fullname;
                                            }
                                ?>
                                    <tr>
                                        <td><?php echo $row['emp_id'] ?></td>
                                        <td><?php echo htmlspecialchars($fullname)?></td>
                                        <td><?php echo $row['position_name'] ?></td>
                                        <td><?php echo $row['emp_shift'] ?></td>
                                        
                                        <td class="td-text"><?php echo $row['status'] ?></td>
                                        <td class="td-text">
                                            <div class="action-buttons">
                                                <a href='./create_payslip.php?id=<?php echo $row["emp_id"]; ?>'><button class="slip-btn">Generate Slip</button></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <!-- Pagination -->
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-right: 1.5%; padding-left: 1.5%;">
                            <p style="margin: 0;">Page <?= $current_page ?> out of <?= $total_pages ?></p>
                            <div class="pagination" id="content">
                                <?php if ($current_page > 1) : ?>
                                    <a href="?page=<?= ($current_page - 1); ?>&query=<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>" class="prev" style="border-radius:4px;background-color:#368DB8;color:white;margin-bottom:13px; padding: 10px;">&laquo; Previous</a>
                                <?php endif; ?>

                                <?php if ($current_page < $total_pages) : ?>
                                    <a href="?page=<?= ($current_page + 1); ?>&query=<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>" class="next" style="border-radius:4px;background-color:#368DB8;color:white;margin-bottom:13px; padding: 10px;">Next &raquo;</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="./javascript/main.js"></script>
    <script src="./javascript/payroll.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const now = new Date();

            // Get current month (01 to 12)
            const month = String(now.getMonth() + 1).padStart(2, '0');
            // Get current year (e.g., 2025)
            const year = now.getFullYear();
            // Default cutoff is "F" (First Cutoff)
            const cutoff = "F";

            // Function to set default in a dropdown
            function setDefault(dropdownClass, valueToSelect) {
                const dropdown = document.querySelector(`.${dropdownClass}`);
                const input = dropdown.querySelector(".dropdown-input");
                const items = dropdown.querySelectorAll(".dropdown-item");

                items.forEach(item => {
                    if (item.dataset.value === valueToSelect) {
                        input.value = item.textContent;
                        input.setAttribute('data-selected', valueToSelect);
                    }
                });
            }

            // Set default values
            setDefault("month-dropdown", month);
            setDefault("year-dropdown", String(year));
            setDefault("cutoff-dropdown", cutoff);
        });
       $(document).ready(function () {
        
            fetchPayroll(); // Initial fetch when page loads

            // Trigger fetch when any filter changes
            $("#filter_cutoff, #filter_status, #sort_option, #filter_salary_type").on("change", function () {
                fetchPayroll();
            });

            // ✅ Add the voice/keyboard input event trigger here:
            $("#search_emp_input").on("input", function () {
                fetchPayroll();
            });

            function fetchPayroll() {
                var name = $("#search_emp_input").val();
                var month = $(".month-dropdown .dropdown-input").val();
                var year = $(".year-dropdown .dropdown-input").val();
                var cutoff = $(".cutoff-dropdown .dropdown-input").val();

                console.log("Sending Data:", { name, month, year, cutoff }); // Debugging

                $.ajax({
                    method: "POST",
                    url: "search_payroll.php",
                    data: { name: name, month: month, year: year, cutoff: cutoff },
                    success: function (response) {
                        console.log("Response:", response); // Debugging output
                        $("#showdata").html(response);
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });
            }

            $("#search_emp_input").on("keyup", fetchPayroll);

            var cutoffMap = {
                "First Cutoff": "1",
                "Second Cutoff": "2"
            };

            $(".dropdown-item").on("click", function () {
                var dropdownType = $(this).closest(".dropdown").hasClass("month-dropdown")
                    ? "month"
                    : $(this).closest(".dropdown").hasClass("year-dropdown")
                    ? "year"
                    : "cutoff";

                var selectedValue = $(this).data("value"); // Get the actual data-value

                // Convert cutoff text to numeric value
                if (dropdownType === "cutoff" && selectedValue !== "") {
                    selectedValue = cutoffMap[$(this).text().trim()] || "";
                }

                // If "Clear Selection" is clicked, reset the input field
                if (selectedValue === "") {
                    $("." + dropdownType + "-dropdown .dropdown-input").val("").attr("placeholder", "Select " + dropdownType.charAt(0).toUpperCase() + dropdownType.slice(1));
                } else {
                    $("." + dropdownType + "-dropdown .dropdown-input").val($(this).text());
                }

                fetchPayroll(); // Update the payroll list based on the new selection
            });

        });

        let toastBox = document.getElementById('toastBox');
        let successMess = '<i class="fa-solid fa-circle-check"></i> Payslip created successfully!';

        function showToast(msg) {
            let toast = document.createElement('div'); 
            toast.classList.add('toast');
            toast.innerHTML = msg;
            toastBox.appendChild(toast); 

            if (msg.includes('error')) {
                toast.classList.add('error');
            }

            // Play notification sound
            const sound = document.getElementById('notifySound');
            if (sound) sound.play();

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status') === 'success') {
            showToast(successMess);
            window.history.replaceState(null, null, window.location.pathname);
        }

    // sweetalert sa voice search
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition = new SpeechRecognition();

    // Select the voice button and search input elements
    const voiceBtn = document.getElementById('voiceSearchBtn');
    const searchInput = document.getElementById('search_emp_input');

    if (voiceBtn && searchInput) {
        voiceBtn.addEventListener('click', () => {
            recognition.start();
            voiceBtn.innerHTML = `<i class="bi bi-mic-mute-fill" style="color: red;"></i>`;

            Swal.fire({
                title: 'Listening...',
                text: 'Please speak now',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Stop',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });
        });

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            searchInput.value = transcript;
            searchInput.dispatchEvent(new Event('input')); // Trigger input event to fire search
            recognition.stop();
            voiceBtn.innerHTML = `<i class="bi bi-mic-fill"></i>`;
            Swal.close();
        };

        recognition.onerror = function(event) {
            console.error('Speech recognition error:', event.error);
            recognition.stop();
            voiceBtn.innerHTML = `<i class="bi bi-mic-fill"></i>`;
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Voice recognition error: ' + event.error,
                timer: 2000,
                showConfirmButton: false,
            });
        };

        recognition.onend = function () {
            voiceBtn.innerHTML = `<i class="bi bi-mic-fill"></i>`;
            Swal.close();
        };
    }

    </script>
</body>

</html>