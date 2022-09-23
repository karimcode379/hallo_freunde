<?php
require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Registrierung</title>
    <link rel="stylesheet" href="assets/css/register_style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="assets/js/register.js"></script>
</head>

<body>
    <?php

    function console_log($output, $with_script_tags = true)
    {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
            ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }

    if (isset($_POST['register_button'])) {
        echo '
    <script>

    $(document).ready(function() {
        $("#first-form").hide();
        $("#second-form").show();
    });

    </script>

    ';
    }
    ?>
    <div class="wrapper">
        <div class=login-box>
            <div class="login-header">
                <h1>Hallo Freunde</h1>
                Registriere dich jetzt oder logge dich ein!
            </div>
            <div id="first-form">
                <form action="register.php" METHOD="POST">
                    <!-- EMAIL -->
                    <input type="email" name="log_email" placeholder="E-Mail Adresse" value="<?= $_SESSION['log_email'] ?? "" ?>" required>
                    <br />
                    <!-- PASSWORD -->
                    <input type="password" name="log_password" placeholder="Passwort">
                    <br />
                    <?php if (in_array("E-Mail oder Passwort falsch<br />", $error_array)) echo "E-Mail oder Passwort falsch<br />" ?>
                    <!-- SUBMIT LOGIN -->
                    <input type="submit" name="login_button" value="Einloggen">
                    <br />
                    <a href="#" id="signup" class="signup">Noch keinen Account? Hier registrieren!</a>
                </form>
            </div>
            <div id="second-form">
                <form action="register.php" method="POST">
                    <!-- FIRST NAME -->
                    <input type="text" name="reg_fname" placeholder="Vorname" value="<?= $_SESSION['reg_fname'] ?? "" ?>" required>
                    <br />
                    <?php if (in_array("Der Vorname muss mindestens 3 und maximal 25 Zeichen lang sein.<br />", $error_array)) echo "Der Vorname muss mindestens 3 und maximal 25 Zeichen lang sein.<br />"; ?>
                    <!-- LAST NAME -->
                    <input type="text" name="reg_lname" placeholder="Nachname" value="<?= $_SESSION['reg_lname'] ?? "" ?>" required>
                    <br />
                    <?php if (in_array("Der Nachname muss mindestens 3 und maximal 25 Zeichen lang sein.<br />", $error_array)) echo "Der Nachname muss mindestens 3 und maximal 25 Zeichen lang sein.<br />"; ?>
                    <!-- EMAIL -->
                    <input type="email" name="reg_email" placeholder="E-Mail" value="<?= $_SESSION['reg_email'] ?? "" ?>" required>
                    <br />
                    <input type="email" name="reg_email2" placeholder="E-Mail Bestätigen" value="<?= $_SESSION['reg_email2'] ?? "" ?>" required>
                    <br />
                    <?php if (in_array("E-Mail wird bereits genutzt.<br />", $error_array)) echo "E-Mail wird bereits genutzt.<br />";
                    elseif (in_array("ungültiges E-Mail Format<br />", $error_array)) echo "ungültiges E-Mail Format<br />";
                    elseif (in_array("Emails stimmen nicht überein.<br />", $error_array)) echo "Emails stimmen nicht überein.<br />"; ?>
                    <!-- PASSWORD -->
                    <input type="password" name="reg_password" placeholder="Passwort" required>
                    <br />
                    <input type="password" name="reg_password2" placeholder="Passwort Bestätigen" required>
                    <br />
                    <?php if (in_array("Die eingegebenen Passwörter stimmen nicht überein.<br />", $error_array)) echo "Die eingegebenen Passwörter stimmen nicht überein.<br />";
                    elseif (in_array("Das Passwort darf nur Buchstaben und Zahlen enthalten.<br />", $error_array)) echo "Das Passwort darf nur Buchstaben und Zahlen enthalten.<br />";
                    elseif (in_array("Das Passwort muss mindestens 5 und maximal 30 Zeichen beinhalten.<br />", $error_array)) echo "Das Passwort muss mindestens 5 und maximal 30 Zeichen beinhalten.<br />"; ?>
                    <!-- SUBMIT REGISTRATION -->
                    <input type="submit" name="register_button" value="Registrieren">
                    <br />
                    <!-- SUBMIT SUCCESS -->
                    <?php if (in_array("<span style='color: #14C800;'>Registrierung erfolgreich! Du kannst dich nun einloggen.</span><br />", $error_array)) echo "<span style='color: #14C800;'>Registrierung erfolgreich! Du kannst dich nun einloggen.</span><br />"; ?>
                    <a href="#" id="signin" class="signin">Du hast schon einen Account? Hier einloggen!</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>