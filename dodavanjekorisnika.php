<?php
include_once "includes/connection/connection.php";
$db = new MySQLDB();

session_start();

if (isset($_SESSION['korisnik'])) {
    if ($_SESSION['korisnik']['admin'] == 0) {
        header("Location: korisnik.php");
        exit;
    }
} else {
    header('Location: prijava.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["upisKorisnika"])) {

        $ime = $_POST["ime"];
        $prezime = $_POST["prezime"];
        $mail = $_POST["mail"];
        $lozinka = md5($_POST["lozinka"]);
        $admin = isset($_POST["admin"]) ? 1 : 0;

        $provjeraEmaila = $db->select("SELECT email FROM pristup_korisnik WHERE email = ? ", array($mail));

        if ($provjeraEmaila['row_count'] == 1) {
            $_SESSION['emailErr'] = "Email već postoji";
            header("Location: dodavanjekorisnika.php ");
            die;
        } else {
            $db->insert("INSERT INTO pristup_korisnik(ime,prezime,email,lozinka,admin,aktivan) VALUES (?,?,?,?,?,1);", array($ime, $prezime, $mail, $lozinka, $admin));
            header("Location: dodavanjekorisnika.php ");
            die;
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Admin</title>

    <link rel="stylesheet" href="includes/style/index.css">

</head>

<body style="background-color: #f3f3f3;">



    <div class="container justify-content-center align-items-center mb-5">
        

        <div class="row mt-4">
            <div class="col-md-6 mb-2">
                <div class="card bg-white rounded shadow p-2">
                    <div class="card-body">
                        <form action="dodavanjekorisnika.php" method="post">
                            <div class="mb-3">
                                <label class="form-label">Ime</label>
                                <input placeholder="Pero" type="input" class="form-control" id="ime" name="ime" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prezime</label>
                                <input placeholder="Peric" type="input" class="form-control" id="prezime" name="prezime" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Mail</label>
                                <input placeholder="pero.peric@skole.hr" type="input" class="form-control" id="mail" aria-describedby="emailHelp" name="mail" required>
                                <?php
                                if (isset($_SESSION['emailErr'])) {
                                    echo "<p class='alert alert-danger mt-2 p-2'>" . $_SESSION['emailErr']  . " </p>";
                                    unset($_SESSION['emailErr']);
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Lozinka</label>
                                <input type="password" class="form-control" id="lozinka" name="lozinka" required>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="admin" name="admin">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Admin</label>
                            </div>
                            <br>
                            <button name="upisKorisnika" type="submit" class="btn btn-info text-white w-100">Potvrdi</button>
                        </form>
                    </div>
                </div>
            </div>


            <div class="col-md-6 mb-2">
                <div class="card bg-white rounded shadow p-3" style="max-height: 500px;">
                    <div class="card-body" style=" overflow-y: auto;">
                        <div id="userTableContainer">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Ime</th>
                                        <th>Prezime</th>
                                        <th>Email</th>
                                        <th>Admin</th>
                                        <th>Prava</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $db->select("SELECT * FROM pristup_korisnik");
                                    foreach ($result['result'] as $row) :
                                        $ime = $row["ime"];
                                        $prezime = $row["prezime"];
                                        $email = $row["email"];
                                        $admin = $row["admin"] == 1 ? "Da" : "Ne";

                                    ?>
                                        <tr>
                                            <td><?php echo $ime; ?></td>
                                            <td><?php echo $prezime ?></td>
                                            <td><?php echo $email ?></td>
                                            <td><?php echo $admin ?></td>
                                            <td><button class='btn btn-info text-white' data-bs-toggle='modal' data-bs-target='#editModal'>Uredi</button></td>
                                        </tr>

                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    

    </div>



    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Uredi dozvole</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
                    <button type="button" class="btn btn-info text-white">Spremi</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>


</html>