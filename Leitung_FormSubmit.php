<?php
include("navbar.html");

/**
 * @author 
 * @copyright 2015
 */

if (isset($_POST['name'])) {
    include ("connect.php");  
    $name = $_POST['name'];
    $start= $_POST['Sknoten'];
    $ende = $_POST['Eknoten'];
    $kom = $_POST['kommentar'];
    echo "Ihr Eingaben:<br> Name: '$name'<br> Startpunkt: '$start'<br> Endpunkt:: '$ende'<br> Kommentar:<br>'$kom'<br>";
    $query = "insert into leitung(Leitung_Name, Leitung_Start, Leitung_Ende, Kommentar) VALUES('$name', '$start', '$ende', '$kom')";
    if (mysqli_query($link, $query)) {
        echo "<font color=\"green\" size=\"10\">Eintrag erfolgreich</font>";
    } else {
        echo "<font color=\"red\" size=\"10\">Eintrag fehlgeschlagen</font>";
    }
    echo '<form action="Leitung_Form.php"><input type="submit" value="zurueck"></form>';
    mysqli_close($link);
}

?>
