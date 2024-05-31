<?php
    //Já mám rád vypisování do konzole :(
    function console_log($output, $with_script_tags = true) {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
            ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }
?>

<html lang='cs'>
    <head>
        <title>Chebunet - register</title>

        <link href='images/favicon.png' rel='shortcut icon' type='image/png'>
        <link type="text/css" href="style.css" rel="stylesheet">
        <meta charset="UTF-8">


    </head>

    <body>
    <a href="index.php" class="back-button">Zpět</a>
    <h1 style="text-align: center">Register</h1>
        <form action="" method="post" >
            <fieldset class="formLogin">
                <label for="username" style="text-align: center">Username:
                    <input type="text" name="username" class="loginInput">
                </label>

                <label for="password" style="text-align: center">Password:<br>
                    <input type="password" name="password" class="loginInput">
                </label>

                <label for="confirm_password" style="text-align: center">Confirm Password:
                    <input type="password" name="confirm_password" id="confirm_password" class="loginInput">
                </label>

                <label for="gender" style="text-align: center">Gender:
                    <select name="gender" id="gender" class="loginInput" style="background: white; color: black">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="Apache helicopter 9000">Apache helicopter 9000</option>
                        <option value="Walmart bag">Walmart bag</option>
                        <option value="other">Other</option>
                    </select>
                </label>

                <label for="dob" style="text-align: center">Date of Birth:
                    <input type="date" name="dob" id="dob" class="loginInput" min="1900-01-01" max="<?php echo date('Y-m-d'); ?>">
                </label>

                </label>

                <input type="submit" name="prihlasit" value="Registrovat" id="loginButton">
                <h4>Máte už účet? Přihlaš se sakra!</h4><a href="login.php" style="color: #003366;">Přihlásit se</a>

                <?php
                $hostname = "localhost";
                $username = "root";
                if (!$_POST) return;

                if(isset($_POST["prihlasit"])) {
                    $connection = null;
                    require_once "pripojit.php";


                    $prikaz = "CREATE TABLE IF NOT EXISTS accounts (
                                id INT PRIMARY KEY AUTO_INCREMENT,
                                username VARCHAR(50) NOT NULL,
                                password VARCHAR(50) NOT NULL,
                                gender VARCHAR(10),
                                dob DATE
                            );";

                    $vysl = mysqli_query($connection, $prikaz);



                    $username = $_POST["username"];
                    $password = $_POST["password"];
                    $confirm_password = $_POST["confirm_password"];
                    $gender = $_POST["gender"];
                    $dob = $_POST["dob"];

                    // Validate the date of birth format
                    if (!empty($dob) && !strtotime($dob)) {
                        echo "<div class='notification error-notification'>Neplatný formát data narození.</div>";
                        return;
                    }

                    // Check if passwords match
                    if ($password !== $confirm_password) {
                        echo "<div class='notification error-notification'>Hesla se neshodují.</div>";
                        return;
                    }

                    $vyber = mysqli_query($connection, "select * from accounts");

                    if(empty($username) || empty($password)) {
                        echo "<div class='notification error-notification'>Musíte zadat i username i heslo.</div>";
                        return;
                    }

                    echo "<div class='notification ";
                    $isUsernameTaken = false;
                    while ($row = mysqli_fetch_assoc($vyber)) {
                        if ($row["password"] == $password) {
                            $whoseUsername = $row["username"];
                            $isUsernameTaken = true;
                            break;
                        }
                    }

                    if ($isUsernameTaken) {
                        echo "error-notification'>";
                        echo "Heslo je už zabrané uživatelem $whoseUsername<br>";
                    } else {
                        echo "success-notification'>";
                        echo "Úspěšně registrován";
                        //echo "Jojo, určitě jsi registrovaný 👍👍👍👍👍👍 <br>";
                        //echo "HAHAHAHHA, LEAKNUL JSEM TI HESLO<br>";
                        //echo "username: $username <br>password: $password";
                    }
                    echo "</div>";


                    if ($vysl) console_log("Tabulka je funkční");
                    else console_log("Zajimavý, někde je problém, nechtěla se vytvořit stránka. (Víte co? Mě to nezajímá)");

                    $prikaz = "INSERT INTO accounts (username, password, gender, dob) VALUES ('$username', '$password', '$gender', '$dob');";

                    $vysl = mysqli_query($connection, $prikaz);

                    mysqli_close($connection);
                }


                ?>






            </fieldset>
        </form>


    </body>

</html>



