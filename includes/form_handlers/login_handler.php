<?php

if (isset($_POST['login_button'])) {
    $email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL);

    $_SESSION['log_email'] = $email; //store email into session variable

    $password = $_POST['log_password'];
    $database_query  = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
    $row = mysqli_fetch_array($database_query);
    $password_hash = $row['password'];
    $check_password = password_verify($password, $password_hash);

    if ($check_password) {
        $username = $row['username'];

        $user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
        if (mysqli_num_rows($user_closed_query) === 1) {
            $reopen_account = mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email'");
        }

        $_SESSION['username'] = $username;
        header("Location: index.php");
        $_SESSION["log_email"] = NULL;
        exit();
    } else {
        array_push($error_array, "E-Mail oder Passwort falsch<br />");
    }
}
