<?
include_once "includes/connection/connection.php";

session_start();

if (isset($_SESSION['korisnik'])) {
    if($_SESSION['korisnik']['admin']){
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: prijava.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Korisnik</title>
</head>

<body>


    <div class="container justify-content-center align-items-center mb-5">
        <div class="row mt-4">
            <div class="col">
                <div class="card bg-white rounded shadow p-2">
                    <div class="card-body">
                        <div class="row align-items-center text-center">
                            <div class="col-2">
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
    </div>

</body>

</html>