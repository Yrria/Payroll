<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/header.css">
    <title>Header</title>
</head>

<body>
    <div class="header">
        <span class="comp-name">BytePayroll</span>
        <div class="toggle-btn" onclick="toggleNav()">☰</div>
        <span class="current-date-time">
            <span id="current-date"></span> - <span id="current-time"></span>
        </span>
        <div class="profile" onclick="toggleDropdown()">
            <i class="bi bi-person-circle profile-icon"></i> <span>Emp0001</span>
            <!-- Dropdown Menu -->
            <ul class="dropdown-menu">
               <a href="#"><li><span>Profile</span></li></a> 
               <a href="#"><li><span>Logout</span></li></a> 
            </ul>
        </div>
    </div>
    <script src="./javascript/header.js"></script>
</body>

</html>
