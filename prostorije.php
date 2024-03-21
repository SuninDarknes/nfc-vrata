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

     if (isset($_POST["upisiProstoriju"])) {
        $naziv = $_POST["naziv"];
        $kljuc = md5($naziv);
        $db->insert("INSERT INTO pristup_prostorija(naziv,kljuc,aktivan) VALUES (?,?,1);", array($naziv, $kljuc));
        header("Location: prostorije.php ");
        die;
    } else if (isset($_POST["izbrisiProstoriju"])) {
        $id = $_POST["id"];
        $naziv = $_POST["naziv"];
        $db->update("UPDATE pristup_prostorija SET aktivan = 0 WHERE id = ? ", array($id));
        header("Location: prostorije.php ");
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
    <link rel="stylesheet" href="includes/style/dragndrop.css">

</head>

<body style="background-color: #f3f3f3;">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>


    <div class="container justify-content-center align-items-center mb-5">
        <div class="row mt-4">
            <div class="col-md-6 mx-auto">
                <div class="card bg-white rounded shadow p-2">
                    <div class="card-body">
                        <div class="row align-items-center text-center">
                            <div class="col-md-2">
                                <img src="includes/img/profile.svg" alt="" style="height: 5rem;">
                            </div>

                            <div class="col-10 text-start">
                                <h3>
                                    <?php echo $_SESSION['korisnik']['ime'] . " " . $_SESSION['korisnik']['prezime']; ?>
                                </h3>
                                <p>
                                    <?php if ($_SESSION['korisnik']['admin'])
                                        echo "Admin" ?>
                                </p>
                            </div>


                            <div class="col-md-12 d-flex justify-content-end align-items-center">
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


            <div class="col-md-3 mx-auto">
                <a href="/kontrolaulaza/admin.php" class="card-link text-decoration-none">
                    <div class="card bg-white rounded shadow p-2">
                        <div class="card-body">
                            <div class="row align-items-center text-center">
                                <div class="col-md-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="5rem" height="5rem" fill="currentColor" class="bi bi-house-gear" viewBox="0 0 16 16">
                                        <path d="M7.293 1.5a1 1 0 0 1 1.414 0L11 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l2.354 2.353a.5.5 0 0 1-.708.708L8 2.207l-5 5V13.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 2 13.5V8.207l-.646.647a.5.5 0 1 1-.708-.708z" />
                                        <path d="M11.886 9.46c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.044c-.613-.181-.613-1.049 0-1.23l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0" />
                                    </svg>
                                </div>
                                <div class="col-md-10">
                                    <h1>Prostorije</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3 mx-auto">
                <a href="/kontrolaulaza/admin.php" class="card-link text-decoration-none">
                    <div class="card bg-white rounded shadow p-2">
                        <div class="card-body">
                            <div class="row align-items-center text-center">
                                <div class="col-md-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="5rem" height="5rem" fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07" />
                                    </svg>
                                </div>
                                <div class="col-md-10">
                                    <h1>Evidencija</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>


        <div class="row mt-2">
            <div class="col-md-6 mb-2">
                <div class="card card bg-white rounded shadow p-2">
                    <div class="card-body ">
                        <form  method="post">
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
                                                <td>
                                                    <?php echo $id ?>
                                                </td>
                                                <td>
                                                    <?php echo $naziv ?>
                                                </td>
                                                <td>
                                                    <?php echo $kljuc ?>
                                                </td>
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
                                                                <h5 class="modal-title" id="izbrisiProstoriju">Brisanje
                                                                    prostorije "
                                                                    <?php echo $naziv ?>"
                                                                </h5>
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

    <script src="https://cdn.jsdelivr.net/npm/dragula@3.7.3/dist/dragula.min.js"></script>
    <script src="includes/js/drag.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>


</body>


</html>