<?php

//Declaring varibales
$fname = ""; //first name
$flame = ""; //last name
$email = ""; //email
$email2 = ""; //email 2
$password = ""; //password
$password2 = ""; //password 2
$date = ""; //sign up date
$error_array = array(); //holds error messages


if (isset($_POST["register_button"])) {

    // Registration form values

    //first name
    $fname = strip_tags($_POST["reg_fname"]); //Remove html tags
    $fname = str_replace(" ", "", $fname); //remove spaces
    $fname = ucfirst(strtolower($fname)); //Uppercase first letter
    $_SESSION["reg_fname"] = $fname; //stores first name into session variable

    //last name
    $lname = strip_tags($_POST["reg_lname"]); //Remove html tags
    $lname = str_replace(" ", "", $lname); //remove spaces
    $lname = ucfirst(strtolower($lname)); //Uppercase first letter
    $_SESSION["reg_lname"] = $lname; //stores emailinto session variable


    //email
    $email = strip_tags($_POST["reg_email"]); //Remove html tags
    $email = str_replace(" ", "", $email); //remove spaces
    $email = ucfirst(strtolower($email)); //Uppercase first letter
    $_SESSION["reg_email"] = $email; //stores email into session variable


    //email2
    $email2 = strip_tags($_POST["reg_email2"]); //Remove html tags
    $email2 = str_replace(" ", "", $email2); //remove spaces
    $email2 = ucfirst(strtolower($email2)); //Uppercase first letter
    $_SESSION["reg_email2"] = $email2; //stores email2 into session variable


    //password
    $password = strip_tags($_POST["reg_password"]); //Remove html tags

    //password2
    $password2 = strip_tags($_POST["reg_password2"]); //Remove html tags

    //date
    $date = date("Y-m-d"); //gets current date

    if ($email === $email2) {
        //check if email is in valid format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            //check if email already exists
            $email_check = mysqli_query($con, "SELECT email FROM users WHERE email = '$email'");

            //Count the number of rows returned
            $num_rows = mysqli_num_rows($email_check);

            if ($num_rows > 0) {
                array_push($error_array, "E-Mail wird bereits genutzt.<br />");
            }
        } else {
            array_push($error_array, "ungültiges E-Mail Format<br />");
        }
    } else {
        array_push($error_array, "Emails stimmen nicht überein.<br />");
    }

    if (strlen($fname) > 25 || strlen($fname) < 2) {
        array_push($error_array, "Der Vorname muss mindestens 3 und maximal 25 Zeichen lang sein.<br />");
    }

    if (strlen($lname) > 25 || strlen($lname) < 2) {
        array_push($error_array, "Der Nachname muss mindestens 3 und maximal 25 Zeichen lang sein.<br />");
    }

    if ($password !== $password2) {
        array_push($error_array, "Die eingegebenen Passwörter stimmen nicht überein.<br />");
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array, "Das Passwort darf nur Buchstaben und Zahlen enthalten.<br />");
        }
    }

    if (strlen($password) > 30 || strlen($password) < 5) {
        array_push($error_array, "Das Passwort muss mindestens 5 und maximal 30 Zeichen beinhalten.<br />");
    }

    if (empty($error_array)) {
        // hash password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // generate username by concatenating first and last name and check if it exists (then --> username_$i)
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");

        $i = 0;
        while (mysqli_num_rows($check_username_query) !== 0) {
            $i++;
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");
        }

        // assign default profile picture
        $profile_pic = "./assets/img/profile_pics/defaults/head_wet_asphalt.png";

        // insert user into database
        $query = mysqli_query($con, "INSERT INTO users VALUES (NULL, '$fname', '$lname', '$username', '$email', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

        // push success msg to array
        array_push($error_array, "<span style='color: #14C800;'>Registrierung erfolgreich! Du kannst dich nun einloggen.</span><br />");

        // clear session variables
        $_SESSION["reg_fname"] = NULL;
        $_SESSION["reg_lname"] = NULL;
        $_SESSION["reg_email"] = NULL;
        $_SESSION["reg_email2"] = NULL;
    }
}
