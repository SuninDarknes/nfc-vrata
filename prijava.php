<?php
session_start();
include_once "includes/connection/connection.php";

$db = new MySQLDB();


if (isset($_SESSION['korisnik'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['mail'];
    $lozinka = md5($_POST['lozinka']);

    try {

        $rezultat = $db->select("SELECT * FROM pristup_korisnik WHERE email = ?", array($email));

        if ($rezultat["row_count"] == 1) {

            $korisnik = $rezultat['result'][0];
            if ($lozinka == $korisnik['lozinka']) {

                $_SESSION['korisnik'] = $korisnik;

                header('Location: index.php');
                exit;
            } else {
                $_SESSION["neuspjesna_prijava"] = "Unjeli ste krive korisničke podatke!";
                header('Location: prijava.php');
                exit;
            }
        } else {
            $_SESSION["neuspjesna_prijava"] = "Unjeli ste krive korisničke podatke!";
            header('Location: prijava.php');
            exit;
        }
        $_SESSION["neuspjesna_prijava"] = "Unjeli ste krive korisničke podatke!";
        header('Location: prijava.php');
        exit;
    } catch (PDOException $e) {

        echo "Connection failed: " . $e->getMessage();
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Prijava</title>

</head>

<body style="background-color: #f3f3f3;">

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="p-5 col-12 col-sm-8 col-md-6 col-lg-4">
            <form class="bg-white rounded shadow p-3" method="post">
                <div class="mb-3">
                    <h2 class="form-label text-center">Prijava</h2>

                </div>
                <div class="mb-3">
                    <label for="InputEmail1" class="form-label">E-mail</label>
                    <input placeholder="pero.peric@skole.hr" type="input" class="form-control" id="mail" aria-describedby="emailHelp" name="mail" required>
                </div>
                <div class="mb-3">
                    <label for="InputPassword1" class="form-label">Lozinka</label>
                    <input type="password" class="form-control" id="lozinka" name="lozinka" required>
                </div>
                <?php
                if (isset($_SESSION["neuspjesna_prijava"])) {
                    echo "<div class='alert alert-danger mt-2' role='alert'>" . $_SESSION["neuspjesna_prijava"] . "</div>";
                    unset($_SESSION["neuspjesna_prijava"]);
                }

                ?>


                <button type="submit" class="btn btn-info text-white w-100">Prijavi se</button>
            </form>
        </div>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>


</html>