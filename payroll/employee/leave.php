<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/leave.css">
    <title>Leave</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
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

            <div class="main-content">
                <div class="sub-content">
                        <!-- Leave Table -->
                        <div id="maindiv">
                            <div class="grid-item">
                                <button class="button" onclick="alter()" style="width: 30%;">+ File a Leave</button>
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
                                        <td style="color:rgb(0, 255, 0)">Approved</td>
                                        <td><button class="button" onclick="alter_approved()">View Info</button></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Leave for Personal Needs</td>
                                        <td>1/19/2025</td>
                                        <td>I am writing this request for an...</td>
                                        <td>Annual Leave</td>
                                        <td style="color:rgb(255, 0, 0);">Declined</td>
                                        <td><button class="button" onclick="alter_declined()">View Info</button></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Leave for Loved Ones Funeral</td>
                                        <td>2/14/2025</td>
                                        <td>I regret to inform you that I have...</td>
                                        <td>Bereavement Leave</td>
                                        <td style="color: #888888;">Pending</td>
                                        <td><button class="button" onclick="alter_pending()">View Info</button></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Leave for Loved Ones Funeral</td>
                                        <td>2/14/2025</td>
                                        <td>I regret to inform you that I have...</td>
                                        <td>Bereavement Leave</td>
                                        <td style="color: #888888;">Pending</td>
                                        <td><button class="button" onclick="alter_pending()">View Info</button></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Leave for Loved Ones Funeral</td>
                                        <td>2/14/2025</td>
                                        <td>I regret to inform you that I have...</td>
                                        <td>Bereavement Leave</td>
                                        <td style="color: #888888;">Pending</td>
                                        <td><button class="button" onclick="alter_pending()">View Info</button></td>
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
                                <textarea type="text" class="textbox" style="height: 200px;"></textarea>
                            </div>
                            <div class="grid-item">
                                <button class="button">Apply for Leave</button>
                            </div>
                        </div>

                        <!-- view leave approved-->
                        <div class="info-container" id="approved">
                            <h1>Leave Info</h1>
                            <hr>
                            <div class="info-box">
                                <div class="info-grid">
                                    <label for="">Leave Subject</label>
                                    <input type="text" class="textbox" placeholder="Leave for Medical Concern" disabled>
                                </div>
                                <div class="info-grid">
                                    <label for="">Status</label>
                                    <input type="text" class="textbox" placeholder="Approved" disabled>
                                </div>
                                <div class="info-grid">
                                    <label for="">Leave Date (MM/DD/YYYY)</label>
                                    <input type="text" class="textbox" placeholder="12/25/2024" disabled>
                                </div>
                                <div class="info-grid">
                                    <label for="">Leave Type</label>
                                    <input type="text" class="textbox" placeholder="Sick Leave" disabled>
                                </div>
                                <div class="info-grid">
                                    <label for="">Leave Message</label>
                                    <textarea type="text" class="textbox" style="height: 200px;" placeholder="I regret to inform you that I am unable to come to work due to health issues. I am currently unwell and require time to recover. I kindly request sick leave from the given date." disabled></textarea>
                                </div>
                                <div class="info-grid">
                                    <button class="button" onclick="approved()">Back</button>
                                </div>
                            </div>
                        </div>
                        <!-- view leave declined -->
                        <div class="info-container" id="declined">
                            <h1>Leave Info</h1>
                            <hr>
                            <div class="info-box">
                                <div class="info-grid">
                                    <label for="">Leave Subject</label>
                                    <input type="text" class="textbox" placeholder="Leave for Personal Needs" disabled>
                                </div>
                                <div class="info-grid">
                                    <label for="">Status</label>
                                    <input type="text" class="textbox" placeholder="Declined" disabled>
                                </div>
                                <div class="info-grid">
                                    <label for="">Leave Date (MM/DD/YYYY)</label>
                                    <input type="text" class="textbox" placeholder="01/19/2025" disabled>
                                </div>
                                <div class="info-grid">
                                    <label for="">Leave Type</label>
                                    <input type="text" class="textbox" placeholder="Annual Leave" disabled>
                                </div>
                                <div class="info-grid">
                                    <label for="">Leave Message</label>
                                    <textarea type="text" class="textbox" style="height: 200px;" placeholder="I am writing this request for an annual leave from the given date. During this time, I plan to travel, and I hope this period will be convenient for the team." disabled></textarea>
                                </div>
                                <div class="info-grid">
                                    <button class="button" onclick="declined()">Back</button>
                                </div>
                            </div>
                        </div>
                        <!-- view leave pending -->
                        <div class="info-container" id="pending">
                            <h1>Leave Info</h1>
                            <hr>
                            <div class="info-box">
                                <div class="info-grid">
                                    <label for="">Leave Subject</label>
                                    <input type="text" class="textbox" placeholder="Leave for Loved Ones Funeral" disabled>
                                </div>
                                <div class="info-grid">
                                    <label for="">Status</label>
                                    <input type="text" class="textbox" placeholder="Pending" disabled>
                                </div>
                                <div class="info-grid">
                                    <label for="">Leave Date (MM/DD/YYYY)</label>
                                    <input type="text" class="textbox" placeholder="02/14/2025" disabled>
                                </div>
                                <div class="info-grid">
                                    <label for="">Leave Type</label>
                                    <input type="text" class="textbox" placeholder="Bereavement Leave" disabled>
                                </div>
                                <div class="info-grid">
                                    <label for="">Leave Message</label>
                                    <textarea type="text" class="textbox" style="height: 200px;" placeholder="I regret to inform you that I am suffering from the loss of my cousin, who passed away on 02/07/2025. This is a deeply challenging time for me and my family, and I kindly request bereavement leave from the requested date to grieve and make necessary arrangements." disabled></textarea>
                                </div>
                                <div class="info-grid">
                                    <button class="button" onclick="pending()">Back</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
    <script src="./javascript/leave.js"></script>
</body>

</html>