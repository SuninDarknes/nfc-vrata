<?php
include_once "includes/connection/connection.php";
$db = new MySQLDB();

session_start();

if (isset ($_SESSION['korisnik'])) {
    if ($_SESSION['korisnik']['admin'] == 0) {
        header("Location: korisnik.php");
        exit;
    }
} else {
    header('Location: prijava.php');
    exit;
}
?>
<div class="drag-container">

    <input type="hidden" name="UserID" value="<?php echo $_GET["id"]; ?>">
    <ul class="drag-list">

        <li class="drag-column drag-column-on-hold card bg-white rounded shadow">
            <span class="drag-column-header">
                <h2>Prostorije</h2>
            </span>

            <ul class="drag-inner-list" id="1">
                <?php

                $result = $db->select("SELECT t1.id, t1.naziv, t1.aktivan FROM pristup_prostorija t1 LEFT JOIN pristup_dozvole t2 ON t1.id = t2.prostorija AND t2.korisnik = ? AND t2.trajanje > CURRENT_TIMESTAMP AND t2.aktivno = 1 WHERE t1.aktivan = 1 AND t2.prostorija IS NULL ;", array($_GET["id"]));
                if (isset ($result['result']))
                foreach ($result['result'] as $row):
                    $id = $row["id"];
                    $ime = $row["naziv"];

                    ?>
                    <li class="drag-item">
                        <input name="p<?php echo $id; ?>" value="<?php echo $ime; ?>" style="all: unset;" readonly>


                    </li>

                <?php endforeach; ?>

            </ul>
        </li>
        <li class="drag-column drag-column-approved card bg-white rounded shadow ">
            <span class="drag-column-header ">
                <h2>Dozvoljene prostorije</h2>
            </span>
            <ul class="drag-inner-list" id="2">
                <?php
                $result = $db->select("SELECT t1.id, t2.naziv FROM pristup_dozvole t1 JOIN pristup_prostorija t2 WHERE t1.prostorija = t2.id AND t1.trajanje > CURRENT_TIMESTAMP AND t1.aktivno=1 AND t2.aktivan=1 AND t1.korisnik = ?;", array($_GET["id"]));
                if (isset ($result['result']))
                    foreach ($result['result'] as $row):
                        $id = $row["id"];
                        $ime = $row["naziv"];

                        ?>
                        <li class="drag-item">
                            <input name="d<?php echo $id; ?>" value="<?php echo $ime; ?>" style="all: unset;" readonly>
                        </li>

                    <?php endforeach; ?>
            </ul>
        </li>

    </ul>

</div>
<script src="https://cdn.jsdelivr.net/npm/dragula@3.7.3/dist/dragula.min.js"></script>
<script src="includes/js/drag.js"></script>