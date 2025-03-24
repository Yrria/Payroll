<?php
$email = $_SESSION['email'];
if ($email == 0) {
  header('Location: ./login.php');
  exit();
}


?>