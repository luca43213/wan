<?php
include ("navbar.php");

if (isset($_POST['name'])) {
    include ("connect.php");  
    $name = $_POST['name'];
    $plz = $_POST['plz'];
    $ort = $_POST['ort'];
    $strasse = $_POST['strasse'];
    $hnr = $_POST['hnr'];
    $lg = $_POST['lg'];
    $bg = $_POST['bg'];
    echo "Ihr Eingaben:<br> Name: '$name'<br> PLZ: '$plz'<br> Strasse: '$strasse' '$hnr'<br> Breitengrad/Laengengrad: '$lg' / '$bg'<br>";
    //Einfügen der Daten in die Datenbank.
    $query = "insert into knoten(Knoten_Name, Knoten_PLZ, Knoten_Ort, Knoten_Strasse, Knoten_Hnr, Knoten_Breitengrad, Knoten_Laengengrad ) VALUES('$name', '$plz', '$ort', '$strasse', '$hnr', '$bg', '$lg')";
    if (mysqli_query($link, $query)) {
        echo "<font color=\"green\" size=\"10\">Eintrag erfolgreich</font>";
    } else {
        echo "<font color=\"red\" size=\"10\">Eintrag fehlgeschlagen</font>";
    }			
    echo '<form action="Knoten_Form.php"><input type="submit" value="zurueck"></form>';
}
mysqli_close($link)
?>