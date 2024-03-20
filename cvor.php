<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo "error post\n";
    echo "hi there! I'm affraid you aren't supposed to be here (Or are you a node??) REDERECTING NOW!";
    echo "<meta http-equiv=\"Refresh\" content=\"0; url='https://tsck.eu'\" />";
    exit();
}
$json = file_get_contents('php://input');
$data = json_decode($json, true);
if($data == null) {
    echo "error json\n";
    exit();
}else if(isset($data["Stanje_vrata"])){
    //debug
    $file2=fopen("log_data.txt", "a");
    fwrite($file2, "Node je rekao: " . $json);
    fwrite($file2, "\n");
    fclose($file2);

    /*$servername = "localhost";
    $username = "studentadmin";
    $password = "dmKKXIm{r^v,";
    $db_name = "zavrsni2024";
    
    //spajanje
    $conn = new mysqli($servername, $username, $password, $db_name);
*/

echo "dobar dan";
}
?>