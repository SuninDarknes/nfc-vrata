<?php
include_once "includes/connection/connection.php";
$db = new MySQLDB();

session_start();



if (isset($_SESSION['korisnik'])) {

    if (isset($_GET['prostorija'])){
        echo var_dump($_GET);
        exit;
    }

    if ($_SESSION['korisnik']['admin'] == 0) {
        header("Location: korisnik.php");
        exit;
    }else {
        header("Location: admin.php");
        exit;
    }

} else {
    if (isset($_GET['prostorija'])){
        $_SESSION['previousURL'] = $_SERVER['REQUEST_URI'];
        header('Location: prijava.php');
        exit;
    }
    header('Location: prijava.php');
    exit;
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





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>


</html>