<!DOCTYPE HTML>
<html>
<?php
include("navbar.html");//Navigationsleiste
include("connect.php");//Verbindung Datenbank
?>
    <head>
        <title>Suche</title>
        <!--CSS-Einbinden-->
        <style type="text/css" media="all">
            @import "design1.css";
        </style>
    </head>
    
<?php

if(isset($_GET['search']))
{
    $treffer = false;//Variable ob bei der Suche etwas gefunden worden ist
    $search = $_GET['search'];
    //Suche in "Leitung".
    $result_leitung = mysqli_query($link, "select * from leitung where Leitung_Name LIKE '%$search%' OR Leitung_Start LIKE '%$search%' or Leitung_Ende LIKE '%$search%'");
    echo "<div class='CSSTableGenerator'>";
    if(0 !=$num_leitung = mysqli_num_rows($result_leitung) )//Wenn das Ergebnis nicht leer ist , dann eine Tabelle mit den Suchergbnisse erstellen
     {
        $treffer = true;
        ?>
        
        <table>
            <tr>
                <td colspan='4'>Leitung(en)</td>
            </tr>
            <tr>
                <b><td>Name/Nr.</b></td>
                <td><b>Startpunkt</b></td>
                <td><b>Endpunkt</b></td>
                <td><b>Details</b></td>
            </tr>
        <?php
            
            for ($i = 0;$i<$num_leitung; $i++) {
            $row = mysqli_fetch_array($result_leitung);
            
            echo"<tr>
                <td>$row[1]</td>
                <td>$row[2]</td>
                <td>$row[3]</td>
                <td><a href='details.php?leitung=$row[0]'>Details</a></td>
            </tr>";
        }
        echo"</table>";
     }
     //Suche in Abschnitt 
    $result_abschnitt = mysqli_query($link, "select * from abschnitt where Abschnitt_Name LIKE '%$search%' OR Abschnitt_Start LIKE '%$search%' or Abschnitt_Ende LIKE '%$search%' or Faser_Anzahl LIKE '%$search%' or Faser_Nr LIKE '%$search%' or Faser_Nutzung LIKE '%$search%'");
    if(0 !=$num_abschnitt = mysqli_num_rows($result_abschnitt) )
     {
        $treffer = true;
        ?>
        <table>
            <tr>
                <td colspan='7'>Abschnitt(e)</td>
            </tr>
            <tr>
        <td><b>Leitung</b></td>
        <td><b>Abschnitt</b></td>
        <td><b>von Knoten</b></td>
        <td><b>bis Knoten</b></td>
        <td><b>Faser/Anzahl</b></td>
        <td><b>Faser/Nr.</b></td>
        <td><b>Faser/Nutzung</b></td>
            </tr>
        <?php
            
            for ($i = 0;$i<$num_abschnitt; $i++) {
            $row = mysqli_fetch_array($result_abschnitt);
            $leitung_name = mysqli_fetch_array(mysqli_query($link, "select Leitung_Name from leitung where Leitung_ID = '$row[1]'"));
            
            echo"<tr>
                <td>$leitung_name[0]</td>
                <td>$row[2]</td>
                <td>$row[3]</td>
                <td>$row[4]</td>
                <td>$row[5]</td>
                <td>$row[6]</td>
                <td>$row[7]</td>
            </tr>";
        }
        echo"</table>";
     }
     //Suche in Verträge
    $result_vertrag = mysqli_query($link, "select Vertrag_Nr, Abschnitt_ID, Vertrag_Partner from vertrag where Vertrag_Nr LIKE '%$search%' OR Vertrag_Partner LIKE '%$search%'");
    if(0 !=$num_vertrag = mysqli_num_rows($result_vertrag) )
     {
        
        $treffer = true;
        ?>
        <table>
            <tr>
                <td colspan='4'>Vertr&auml;ge</td>
            </tr>
            <tr>
                <td><b>Nummer/Name</b></td>
                <td><b>Vertragspartner</b></td>
                <td>Abschnitt</td>
                <td><b>PDF</b></td>
            </tr>
        <?php
            
            for ($i = 0;$i<$num_vertrag; $i++) {
            $row = mysqli_fetch_array($result_vertrag);
            $abschnitt_name = mysqli_fetch_array(mysqli_query($link, "select Abschnitt_Name from abschnitt where Abschnitt_ID = '$row[1]'"));
            
            echo"<tr>
                <td>$row[0]</td>
                <td>$row[2]</td>
                <td>$abschnitt_name[0]</td>
                <td><a href='download.php?abschnitt=$row[0]' target='_blank'>Vertrag</a></td>   
            </tr>";
        }
        echo"</table>";
     }
    
}
mysqli_close($link);
?>
    </div>
</html>