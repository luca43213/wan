<?php

/**
 * @author 
 * @copyright 2015
 */

include ('connect.php');
if(isset($_GET['abschnitt']))
{
    $abschnitt_id = $_GET['abschnitt'];
    $query = "SELECT pdf_name, pdf_type, pdf_data, pdf_size FROM vertrag WHERE Abschnitt_ID = '$abschnitt_id'  LIMIT 1";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_object($result);//Daten der PDF-Datei
    //Erstellen des Headers, Browers die Datei einbinden, Wenn PDF-Plugin vorhanden im Browser öffnen sonst herunterladen
    header('Content-type: application/pdf');
    header("Content-disposition: attachment; filename=" . $row->pdf_name . ";");
    header("Content-length: " . $row->pdf_size);
    echo base64_decode($row->pdf_data);

    exit;
}


?>