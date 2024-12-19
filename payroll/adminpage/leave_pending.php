<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/leave.css">
    <title>Leave - Pending</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <?php include 'sidenav.php'; ?>
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Leave</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                    <span> > </span>
                    <h5><a href="./leave_pending.php">Leave-Pending </a></h5>
                </div>
                <hr>
            </div>
            <div class="content">
                <div class="search">
                    <h3>Pending</h3>
                    <div class="search-bar">
                        <button class="view-btn">Search</button>
                        <input type="text" placeholder="Search employee..." />
                    </div>
                </div>

                <!-- Leave Table -->
                <table>
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Subject</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Leave Type</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>Manager</td>
                            <td>Willy Wonka</td>
                            <td>01-06-24</td>
                            <td>Emergency Leave</td>
                            <td class="td-text">I am feeling unwell and have been advised by my doctor to rest and recover at home
                            </td>
                            <td class="action-tb">
                                <div class="action-buttons">
                                    <button class="view-btn">View Info</button>
                                    <button class="view-btn">Approve</button>
                                    <button class="view-btn">Decline</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>Crew</td>
                            <td>Ken Flerlage</td>
                            <td>01-06-24</td>
                            <td>Annual Leave</td>
                            <td class="td-text">I am feeling unwell and have been advised by my doctor to rest and recover at home
                            </td>
                            <td class="action-tb">
                                <div class="action-buttons">
                                    <button class="view-btn">View Info</button>
                                    <button class="view-btn">Approve</button>
                                    <button class="view-btn">Decline</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>003</td>
                            <td>Crew</td>
                            <td>George Orwell</td>
                            <td>01-06-24</td>
                            <td>Bereavement Leave</td>
                            <td class="td-text">I am feeling unwell and have been advised by my doctor to rest and recover at home
                            </td>
                            <td class="action-tb">
                                <div class="action-buttons">
                                    <button class="view-btn">View Info</button>
                                    <button class="view-btn">Approve</button>
                                    <button class="view-btn">Decline</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>004</td>
                            <td>Crew</td>
                            <td>Natalie Maines</td>
                            <td>01-06-24</td>
                            <td>Maternity Leave</td>
                            <td class="td-text">I am feeling unwell and have been advised by my doctor to rest and recover at home
                            </td>
                            <td class="action-tb">
                                <div class="action-buttons">
                                    <button class="view-btn">View Info</button>
                                    <button class="view-btn">Approve</button>
                                    <button class="view-btn">Decline</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>005</td>
                            <td>Crew</td>
                            <td>Hellen Mirren</td>
                            <td>01-06-24</td>
                            <td>Vacation Leave</td>
                            <td class="td-text">I am feeling unwell and have been advised by my doctor to rest and recover at home
                            </td>
                            <td class="action-tb">
                                <div class="action-buttons">
                                    <button class="view-btn">View Info</button>
                                    <button class="view-btn">Approve</button>
                                    <button class="view-btn">Decline</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br></br>
                <!-- Pagination -->
                <div class="pagination">
                    <p>Showing 1 / 100 Results</p>
                    <div class="pagination">
                        <button>Prev</button>
                        <input type="text" value="1" readonly />
                        <button>Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
</body>

</html>