<?php
require 'config/config.php';

if (isset($_SESSION['username'])) {
    $userLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username = '$userLoggedIn'");
    $user = mysqli_fetch_array($user_details_query);
} else {
    header("Location: register.php");
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hallo Freunde</title>

    <!-- Javascript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>

<body>
    <div class="top-bar">
        <div class="top-logo">
            <a href="index.php"><img src="assets/img/logo_computer.png" alt="computer logo" width="50px"></a>
            <a href="index.php">Hallo Freunde</a>
        </div>
        <nav>
            <a href="<?= $userLoggedIn ?>"><i class="las la-user la-lg"></i></a>
            <a href="#"><i class="las la-home la-lg"></i></a>
            <a href="#"><i class="las la-envelope la-lg"></i></a>
            <a href="#"><i class="las la-bell la-lg"></i></a>
            <a href="#"><i class="las la-users la-lg"></i></a>
            <a href="#"><i class="las la-cog la-lg"></i></a>
            <a href="includes/handlers/logout.php"><i class="las la-sign-out-alt la-lg"></i></i></a>
        </nav>
    </div>
    <div class="wrapper">