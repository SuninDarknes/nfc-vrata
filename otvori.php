<?php
include_once "includes/connection/connection.php";
session_start();

$db = new MySQLDB();

if (isset($_SESSION['korisnik'])){
    if (!isset($_SESSION['prostorija'])){
        header("Location: index.php");
        exit;
    }
    else {
        $prostorija = $_SESSION["prostorija"];

        $rezultat = $db->select("SELECT * FROM pristup_prostorija WHERE naziv=?", array($prostorija));
        if ($rezultat["row_count"] == 1){
            $id_prostorije = $rezultat["result"][0]["id"];
            $id_korisnika = $_SESSION["korisnik"]["id"];

            // provjera ima li korisnik dozvolu za otvaranje vrata
            $rezultat = $db->select("SELECT * FROM pristup_dozvole WHERE korisnik=? AND prostorija=? AND trajanje > NOW() AND aktivno=1", array($id_korisnika, $id_prostorije));

            if ($rezultat["row_count"] != 0) {
                // dodavanje na evidenciju (ujedino i otvaranje vrata)
                $db->insert("INSERT INTO pristup_evidencija(korisnik, prostorija, vrijeme) VALUES (?, ?, NOW())", array($id_korisnika, $id_prostorije));
            }
            else {
                echo "Nemate dozvolu za otvaranje prostorije!";
                exit;
            }
        }
        else {
            echo "Prostorija '" . $_SESSION["prostorija"] . "' ne postoji!!!<br>";
            exit;
        }
    }
}
else {
    $_SESSION['previousURL'] = $_SERVER['REQUEST_URI'];
    header("Location: prijava.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otvaranje vrata</title>
</head>
<body>
    Otvaranje vrata!
</body>
</html>