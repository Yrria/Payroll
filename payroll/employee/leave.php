<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/leave.css">
    <title>Leave</title>
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
                    <h5><a href="./leave.php">Leave </a></h5>
                </div>
                <hr>
            </div>
            <!-- Leave Table -->
            <div id="maindiv">
                <div class="grid-item">
                    <button class="button" onclick="alter()"  style="width: 30%;">+ File a Leave</button>
                </div>
                <div class="grid-item">
                    <button class="button">Search</button>
                    <input type="text" class="textbox" placeholder="Search employee...">
                </div>
                <div class="grid-item">
                    <table>
                        <tr>
                            <th>Leave #</th>
                            <th>Subject</th>
                            <th>Dates</th>
                            <th>Message</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Leave for Medical Concern</td>
                            <td>12/25/2024</td>
                            <td>I won't be able to come to work due...</td>
                            <td>Sick Leave</td>
                            <td style="color:rgb(0, 255, 4)">Approved</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Leave for Personal Needs</td>
                            <td>1/19/2025</td>
                            <td>I am writing this request for an...</td>
                            <td>Annual Leave</td>
                            <td style="color:rgb(255, 0, 0);">Declined</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Leave for Loved Ones Funeral</td>
                            <td>2/14/2025</td>
                            <td>I regret to inform you that I have...</td>
                            <td>Bereavement Leave</td>
                            <td style="color: #888888;">Pending</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Leave for Loved Ones Funeral</td>
                            <td>2/14/2025</td>
                            <td>I regret to inform you that I have...</td>
                            <td>Bereavement Leave</td>
                            <td style="color: #888888;">Pending</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Leave for Loved Ones Funeral</td>
                            <td>2/14/2025</td>
                            <td>I regret to inform you that I have...</td>
                            <td>Bereavement Leave</td>
                            <td style="color: #888888;">Pending</td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="grid-item">
                    <Button class="button" style="width: 7%;">Prev</Button>
                    <Button class="pagination" style="width: 3%;">1</Button>
                    <button class="button" style="width: 7%;">Next</button>
                </div>
            </div>
            <!-- File Leave -->
            <div id="alter" style="display: none;">
                <div class="grid-item">
                    <h3>Apply for Leave</h3>
                    <label for="">Leave Subject</label>
                    <input type="text" class="textbox">
                </div>
                <div class="grid-item">
                    <label for="">Leave Date (MM/DD/YYYY)</label>
                    <input type="date" class="textbox">
                </div>
                <div class="grid-item">
                    <label for="">Leave Type</label>
                    <select name="leavetype" id="leavetype" class="textbox">
                        <option value=""></option>
                        <option value="">Sick Leave</option>
                        <option value="">Medical Leave</option>
                        <option value="">Annual Leave</option>
                        <option value="">Bereavement Leave</option>
                    </select>
                </div>
                <div class="grid-item">
                    <label for="">Leave Message</label>
                    <input type="text" class="textbox" style="height: 200px;">
                </div>
                <div class="grid-item">
                    <button class="button">Apply for Leave</button>
                </div>
            </div>
        </div>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
    <script src="./javascript/fileleave.js"></script>
</body>

</html>