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
            header("Location: index.php ");
            die;
        } else {
            $db->insert("INSERT INTO pristup_korisnik(ime,prezime,email,lozinka,admin,aktivan) VALUES (?,?,?,?,?,1);", array($ime, $prezime, $mail, $lozinka, $admin));
            header("Location: index.php ");
            die;
        }
    } else if (isset($_POST["upisiProstoriju"])) {
        $naziv = $_POST["naziv"];
        $kljuc = md5($naziv);
        $db->insert("INSERT INTO pristup_prostorija(naziv,kljuc,aktivan) VALUES (?,?,1);", array($naziv, $kljuc));
        header("Location: index.php ");
        die;
    } else if (isset($_POST["izbrisiProstoriju"])) {
        $id = $_POST["id"];
        $naziv = $_POST["naziv"];
        $db->update("UPDATE pristup_prostorija SET aktivan = 0 WHERE id = ? ", array($id));
        header("Location: index.php ");
        die;
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
            <div class="col">
                <div class="card bg-white rounded shadow p-2">
                    <div class="card-body">
                        <div class="row align-items-center text-center">
                            <div class="col-md-2">
                                <img src="includes/img/profile.svg" alt="" style="height: 5rem;">
                            </div>

                            <div class="col-7 text-start">
                                <h3><?php echo $_SESSION['korisnik']['ime'] . " " . $_SESSION['korisnik']['prezime']; ?></h3>
                                <p><?php if ($_SESSION['korisnik']['admin'])  echo "Admin" ?> </p>
                            </div>


                            <div class="col-3 text-end">
                                <form method="post">
                                    <button type="submit" class="btn btn-info text-white" disabled>Uredi</button>
                                    <?php if (isset($_SESSION['korisnik'])) : ?>
                                        <a href="odjava.php" class="btn btn-danger me-2">Odjava</a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-4">
            <div class="col-md-6 mb-2">
                <div class="card bg-white rounded shadow p-2">
                    <div class="card-body">
                        <form action="index.php" method="post">
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

        <div class="row mt-2">
            <div class="col-md-6 mb-2">
                <div class="card card bg-white rounded shadow p-2">
                    <div class="card-body ">
                        <form action="index.php" method="post">
                            <div class="mb-3">
                                <label class="form-label">Naziv prostorije</label>
                                <input placeholder="31 Lab" type="input" class="form-control" id="naziv" name="naziv" required>
                            </div>

                            <br>
                            <button name="upisiProstoriju" type="submit" class="btn btn-info text-white w-100">Potvrdi</button>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-2">
                <div class="card card bg-white rounded shadow p-2">
                    <div class="card-body ">
                        <div id="prostorijaTableContainer" style="display: block; max-height: 50vh; overflow: auto; position: relative;">
                            <?php
                            $result = $db->select("SELECT * FROM pristup_prostorija WHERE aktivan = 1");
                            if ($result["row_count"] > 0) : ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Naziv</th>
                                            <th>Ključ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($result['result'] as $row) :
                                            $id = $row["id"];
                                            $naziv = $row["naziv"];
                                            $kljuc = $row["kljuc"];

                                        ?>
                                            <tr>
                                                <td><?php echo $id ?></td>
                                                <td><?php echo $naziv ?></td>
                                                <td><?php echo $kljuc ?></td>
                                                <td><button class="btn btn-danger p-1" data-bs-toggle='modal' data-bs-target='#izbrisiProstoriju<?php echo $id ?>' value="<?php echo $row['naziv'] ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" class="bi bi-x" viewBox="0 0 16 16">
                                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                                        </svg>
                                                    </button></td>
                                            </tr>

                                            <!-- Modal za brisanje prostorija -->
                                            <div class="modal fade" id="izbrisiProstoriju<?php echo $id ?>" tabindex="-1" aria-labelledby="izbrisiProstoriju" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="post">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="izbrisiProstoriju">Brisanje prostorije "<?php echo $naziv ?>"</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Jeste li sigurni da želite obrisatu prostoriju ?</p>
                                                                <input type="hidden" name="id" value="<?php echo $id ?>">
                                                                <input type="hidden" name="naziv" value="<?php echo $naziv ?>">
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
                                                                <button name="izbrisiProstoriju" type="submit" class="btn btn-info text-white">Izbriši</button>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p class="text-muted">Nema dodanih prostorija</p>
                                    <?php endif; ?>
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