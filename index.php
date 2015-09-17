<!DOCTYPE HTML>
<html>
<?php include("navbar.html");//Einbinden der Navigationsleiste	 ?>
<head>
	<title>Uebersicht</title><!--Einbinden CSS-->
    <style type="text/css" media="all">
        @import "design1.css"; 
        </style>
    <script type="text/javascript" language="JavaScript1.2">
        var angezeigt = false;//Javascript zum verstecken der Abschnitt-Tabelle
        function versteckt(element)
        {
            //document.getElementById(element).colSpan="4";
            var elem = document.getElementById(element);
			elem.colSpan="4";
            if(elem.style.display == "none")//Wenn Zelle nicht angezeigt , dann anzeigen.
            {
                elem.style.display = "table-cell";
            }
            else
            {
                elem.style.display = "none";
            }
        }
    </script>
</head>

<body>
<div class="CSSTableGenerator">
<!--Tabelle der Leitung -->
<table>
    <tr>
	   <td>Name/Nr.</td>
	   <td>Startpunkt</td>
	   <td>Endpunkt</td>
       <td>Kommentar</td>
	   <td colspan='1'></td>
    </tr>
   
<?php
include ('connect.php');
include('download.php');
$result_leitung = mysqli_query($link, "select * from leitung ");//Alle Leitungen
$num_leitung = mysqli_num_rows($result_leitung);

for ($i = 0;$i<$num_leitung; $i++) {//Ausgabe aller Leitungen
    $row = mysqli_fetch_array($result_leitung);
echo"<tr>
	   <td>$row[1]</td>
	   <td>$row[2]</td>
	   <td>$row[3]</td>
       <td>$row[4]</td>
       <td><a href='#' onclick='versteckt($i)'> Abschnitte</a> <a href='details.php?leitung=$row[0]'>   Details</a></td>
    </tr>
    <tr>
       <td style='display : none' id='$i' >
       <div class='design1'>
       <table>
            <tr>
                <td>Abschnitt Name</td>
                <td>Startpunkt</td>
                <td>Endpunkt</td>
                <td>Faser/Anzahl</td>
                <td>Faser/Nr.</td>
                <td>Faser/Nutzung</td>
                <td>Vertragsnr.</td>
                <td>Vertragspartner</td>
                <td>Vertrag</td>   
            </tr>";//Die Tabelle der Abschnitte in einer Zelle der Tabelle der Leitung.
            $result_abschnitt = mysqli_query($link, "select abschnitt.Abschnitt_ID, Abschnitt_Name, Abschnitt_Start, Abschnitt_Ende, Faser_Anzahl, Faser_Nr, Faser_Nutzung, Vertrag_Nr, Vertrag_Partner from abschnitt, leitung, vertrag where abschnitt.Leitung_ID = '$row[0]' and abschnitt.Leitung_ID = leitung.Leitung_ID and abschnitt.Abschnitt_ID=vertrag.Abschnitt_ID");
            $num_abschnitt = mysqli_num_rows($result_abschnitt);
            for($a = 0; $a < $num_abschnitt;$a++)
            {
                $row_abschnitt = mysqli_fetch_array($result_abschnitt);    
                echo"
                <tr>
                    <td>$row_abschnitt[1]</td>
                    <td>$row_abschnitt[2]</td>
                    <td>$row_abschnitt[3]</td>
                    <td>$row_abschnitt[4]</td>
                    <td>$row_abschnitt[5]</td>
                    <td>$row_abschnitt[6]</td>
                    <td>$row_abschnitt[7]</td>
                    <td>$row_abschnitt[8]</td>
                    <td><a href='download.php?abschnitt=$row_abschnitt[0]' target='_blank'>Vertrag</a></td>
                </tr>";   
            }
  echo"</table></div>
    </td><td style='display : none' id='$i' >asd</td>
        </tr>";
}
mysqli_close($link);
?>
</table>
</div>
</body>
</html>