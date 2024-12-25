<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="./assets/css/forgot_pass.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="email-container">
        <div class="email-box">
        <a href="./index.php" class="title-tags"><span class="comp-name"><img src="./assets/title.png" class="title-name" alt="ExPense"></span></a>
            <p>To change your password, enter your registered email, open your inbox to see the reset code sent to you, and follow the instructions to reset your password.</p>
        </div>
        <div class="form-box">
            <form action="#">
                <label for="email">Email:</label>
                <div class="input-container">
                    <i class="fas fa-envelope icon"></i>
                    <input type="email" id="email" placeholder="Enter Email" required>
                </div>
                
                <div class="buttons">
                    <!-- <button type="button" class="back-to-login">Back to login</button>
                    <button type="submit" class="next">Next</button> -->

                    <a href="./index.php" class="next">Back to login</a>
                    <a href="./otp.php" class="next">Next</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
