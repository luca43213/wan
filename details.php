<!DOCTYPE HTML>

<html>
<?php include("navbar.html");//Einbindung der Navigationsleiste ?>	
<head>
	<title>Details</title>
    <!--CSS Einbinden und Google-Maps einbinden-->
    <style type="text/css" media="all">
        @import "design1.css";
        input[type="text"] { border: none }
      </style>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCRoQh_wvHfx-jN9cQa2wW6bnQF5dGf0so&sensor=false"></script>
<script type="text/javascript" >
function zellebearbeiten(item) {//Diese Funktion macht Tabellenzellen bearbeitbar, hier die der Abschnitt-Tabelle. 
	elem = document.getElementById(item);//Die Zelle über die ID identfizieren.
	val = elem.innerHTML;//Den jetzigen Inhalt in einer Variable speichern.
	elem.setAttribute("ondblclick", "");// Attribut "Doppelklick" setzen.
	size = elem.innerHTML.toString().length;//Variable für die größe des Textfeldes
	elem.innerHTML = "<form name='input_form' action='#' method='post'> " +
		" <input name='id' type='hidden' value='' /> " +
		" <input name='wert' class='textfield_effect' type='text' value='" + //Erstellung einer "HTML-Form" dessen Werte in PHP weiter verarbeitet werden.
		val + "' size='" + size + "' onblur='document.input_form.submit();' />" +
		"</form> ";
	document.input_form.id.value = item;
	document.input_form.wert.focus();
}

function zellebearbeiten_l(item) {//s.o , hier die Leitung-Tabelle
	elem = document.getElementById(item);
	val = elem.innerHTML;
	elem.setAttribute("ondblclick", "");
	size = elem.innerHTML.toString().length;
	elem.innerHTML = "<form name='input_form' action='#' method='post'> " +
		" <input name='id_l' type='hidden' value='' /> " +
		" <input name='wert_l' class='textfield_effect' type='text' value='" +
		val + "' size='" + size + "' onblur='document.input_form.submit();' />" +
		"</form> ";
	document.input_form.id_l.value = item;
	document.input_form.wert_l.focus();
}

function loeschen(formid) {//Bestätigung des Löschvorgangs
	
	if (confirm("Datensatz loeschen?")) {
		formid.submit();
	}

}

function alertloesch() {//Erfolgreiches Löschen
	alert("Datensatz wurde geloescht!");
}

function alertKnoten() {//Nicht erfolgreiches Löschen
	alert("Knoten nicht vorhanden!");
}

function load(MarkerA, MarkerB) {//Funktion Google Maps
	var floatAx = parseFloat(MarkerA[0]);
	var floatAy = parseFloat(MarkerA[1]);//Die Koordinaten konnten nur als String übergeben werden,
	var floatBx = parseFloat(MarkerB[0]);//Umwandlung in Float
	var floatBy = parseFloat(MarkerB[1]);
	var centerx = (floatAx + floatBx) / 2;//Punkt zwischen den beiden Markern, um die Mitte zu setzen.
	var centery = (floatAy + floatBy) / 2;
	var Markers = [
		new google.maps.LatLng(floatAx, floatAy),//Erstellen von Google Maps Koordinaten
		new google.maps.LatLng(floatBx, floatBy)
	];


	var mapOptions = {//Festlegen der Karten Optionen, die Zoomeinstellung wird nacher irrelevant
		zoom: 10,
		center: new google.maps.LatLng(centerx, centery)
	}
	var map = new google.maps.Map(document.getElementById("map"), mapOptions);//Div Block der für die Map vorgesehen ist erkennen und die Map anlegen
	var markerA = new google.maps.Marker({//Setzen der Marker
		position: Markers[0],
		title: "Start"
	});
	markerA.setMap(map);
	var markerB = new google.maps.Marker({
		position: Markers[1],
		title: "Ende"
	});
	markerB.setMap(map);
	var linie = new google.maps.Polyline({//Linie zwischen den Markern zeichnen
		path: Markers,
		geodesic: true,
		strokeColor: '#FF0000',
		strokeOpacity: 1.0,
		strokeWeight: 2
	});
	linie.setMap(map);

	var bounds = new google.maps.LatLngBounds();//Anpassen der Grenze bzw des Zooms
	for (var i = 0, LtLgLen = Markers.length; i < LtLgLen; i++) {
		bounds.extend(Markers[i]);
	}
	map.fitBounds(bounds);
}</script>
</head>
<?php
function kosten($preis, $period)//Rechnet die Kosten für ein Jahr eines Abschnittes aus.
{
	if($period == "Monat")
	{
		return $preis * 12;
	}
	else if($period == "Quartal")
	{
		return $preis * 4;
	}
	else
	{
		return $preis;
	}
}
include ('connect.php');
if(isset($_POST['id']))//Fängt die Werte der bearbeiteten Zelle auf.
{
    $wert = $_POST['wert'];//Neuer Wert
    $id = $_POST['id'];//Abschnitt_ID;Spaltenname
    $explode = explode(';',$id);//Aufteilen des Strings	
	mysqli_query($link, "Update abschnitt SET $explode[1] = '$wert' where Abschnitt_ID = '$explode[0]'");//Setzen des neuen Wertes.
    unset($_POST['wert']);
    unset($_POST['id']);
    
}
if(isset($_POST['id_l']))//Gleiches Prinzip
{
    $wert_l = $_POST['wert_l'];
    $id_l = $_POST['id_l'];
    $explode = explode(';',$id_l);
    $result_knoten = mysqli_query($link, "select Knoten_Name from knoten");//Jeden Knoten in der Datenbank
    $num_knoten = mysqli_num_rows($result_knoten);
    $chk = false;
    if($explode[1]=="Leitung_Start"||$explode[1]=="Leitung_Ende")//Wenn Knoten bearbeitet werden soll überprüft werden ob diese vorhanden sind
    {
        for($q = 0; $q<$num_knoten;$q++)
        {
            $row_knoten = mysqli_fetch_array($result_knoten);           
            if($wert_l == $row_knoten[0])//Überprüfen ob der Knoten vorhanden ist
            {
                $chk = true;
            }
        }
    }
    else{$chk = true;}//Falls der Name nur verändert worden ist, war die Überprüfung auch erfolgreich
    if($chk==true)//Bei erfolgreichen Überprüfen wird der Datensatz aktualisiert.
    {
        mysqli_query($link, "Update leitung SET $explode[1] = '$wert_l' where Leitung_ID = '$explode[0]'");
    }
    else
    {
       echo "<body onload='alertKnoten()'></body>"; //Bei Fehlschlagen Fehlermeldung
    }
        unset($_POST['wert_l']);
        unset($_POST['id_l']);       
}
if(isset($_POST['loesch_id']))//Wenn auf den Löschbutton gedrückt worden ist...
{
    $loesch_id = $_POST['loesch_id'];//If-Abfrage ob beide Löschabfragen erfolgreich sind
    if(mysqli_query($link,"DELETE FROM `wan-verbindungen`.`abschnitt` WHERE `abschnitt`.`Abschnitt_ID` = $loesch_id") && mysqli_query($link, "DELETE FROM `wan-verbindungen`.`vertrag` WHERE `vertrag`.`Abschnitt_ID` = $loesch_id"))
    {
        echo "<body onload='alertloesch()'></body>";//Datensatz wurde erfolgreich gelöscht.
    }
	unset($_POST['loesch_id']);
}
if(isset($_POST['loesch_idl']))//Wenn auf den Löschbutton gedrückt worden ist...Leitung+Abschnitte
{
    $loesch_idl = $_POST['loesch_idl'];
	$result_lösch = mysqli_query($link,"select Abschnitt_ID from abschnitt where Leitung_ID = $loesch_idl");
	$num_lösch = mysqli_num_rows($result_lösch);							 
    if(mysqli_query($link,"DELETE FROM `wan-verbindungen`.`leitung` WHERE `leitung`.`Leitung_ID` = $loesch_idl"))
    {
		for($p = 0;$p<$num_lösch;$p++)
		{
			$row_lösch = mysqli_fetch_array($result_lösch);
			$erfolg[0] = mysqli_query($link,"DELETE FROM `wan-verbindungen`.`abschnitt` WHERE `abschnitt`.`Abschnitt_ID` = ".$row_lösch[0]);
			$erfolg [1] = mysqli_query($link, "DELETE FROM `wan-verbindungen`.`vertrag` WHERE `vertrag`.`Abschnitt_ID` = ".$row_lösch[0]);	
		}
		if($erfolg[0] && $erfolg[1])
		{
			echo "<body onload='alertloesch()'></body>";
		}
    }
	unset($_POST['loesch_idl']);
}
if(isset($_GET['leitung']))//Haupteil der Seite
{

    $leitung = $_REQUEST['leitung'];
    $result_leitung = mysqli_query($link, "select * from leitung where Leitung_ID = '$leitung' limit 1");//Abfrage der Leitung
    $result_abschnitt = mysqli_query($link, "select * from abschnitt where Leitung_ID = '$leitung'");//Abfrage der Abschnitte
    $num_abschnitt = mysqli_num_rows($result_abschnitt);//Anzahl der Abschnitte
    $row = mysqli_fetch_array($result_leitung);//Unwandeln der $result_leitung in ein Array
    $result_maps = mysqli_query($link, "select Knoten_Laengengrad, Knoten_Breitengrad from knoten where Knoten_Name ='$row[2]' or Knoten_Name ='$row[3]'");//Abfrage für die Koordinaten der Knoten
    $row_maps = mysqli_fetch_array($result_maps);//Umwandeln in ein Array
    $markerA = json_encode(array($row_maps[1], $row_maps[0]));//Umwandlen in ein Javascript-Array der beiden Knoten-Koordinaten
    $row_maps = mysqli_fetch_array($result_maps);
    $markerB = json_encode(array($row_maps[1], $row_maps[0]));
    echo "<body onload='load($markerA, $markerB)'>";//Laden der Google-Maps
?>
    
    
<div class='CSSTableGenerator'>

	<!-- Erstellen der Tabelle für die Leitung in den Zellen, bei Doppelklick auf die Zellen können diese bearbeitet werden-->
    <table>
    <tr>
        <td colspan = '6'><?php echo "Leitung: ".$row[1]?></td>
    </tr>
    <tr>
        <td colspan=''><b>Leitung</b></td>
		<td id='<?php echo "$row[0];Leitung_Name";?>' ondblclick="zellebearbeiten_l('<?php echo "$row[0];Leitung_Name";?>');"><?php echo $row[1];?></td>
		<td rowspan='3' colspan='2' style='width: 37%'><div id='map' style='width: 100%; height: 300px;'></div></td>
	</tr>
    <tr>
        <td colspan=''><b>Start</b></td>
		<td id='<?php echo "$row[0];Leitung_Start";?>' ondblclick="zellebearbeiten_l('<?php echo "$row[0];Leitung_Start";?>');"><?php echo $row[2];?></td>
	</tr>
    <tr>
        <td colspan=''><b>Ende</b></td>
		<td id='<?php echo "$row[0];Leitung_Ende";?>' ondblclick="zellebearbeiten_l('<?php echo "$row[0];Leitung_Ende";?>');"><?php echo $row[3];?></td>
	</tr>
    </table>
    <table>
          
    <!-- Erstellen der Tabelle für die Abschnitte der Leitung-->        
    <tr>
        <td colspan='15'>Abschnitte</td>
    </tr>
    <tr>
        <td><b>Abschnitt</b></td>
        <td><b>von Knoten</b></td>
        <td><b>bis Knoten</b></td>
        <td><b>Faser/Anzahl</b></td>
        <td><b>Faser/Nr.</b></td>
        <td><b>Faser/Nutzung</b></td>
		<td><b>Vertragsnummer</b></td>
		<td><b>Vertragspartner</b></td>
		<td><b>Fakura Start</b></td>
		<td><b>Betrag einmalig(&euro;)</b></td>
		<td><b>Betrag(&euro;)</b></td>
		<td><b>Zahlungsperiode</b></td>
		<td><b>j&auml;hrliche Kosten(&euro;)</b></td>
		<td><b>Vertrag</b></td>
		<td></td>
    </tr>
<?php
    for($i = 0; $i<$num_abschnitt;$i++)//For-Schleife um jeden Abschnitt anzuzeigen.
    {
    $row1 = mysqli_fetch_array($result_abschnitt);
	$result_vertrag = mysqli_query($link, "select Vertrag_Nr, Vertrag_Partner from vertrag where Abschnitt_ID='$row1[0]'");//Vertrag des Abschnittes raussuchen.
	$vertrag = mysqli_fetch_array($result_vertrag);
	?>
    <tr>
				<!--<td id=AbschnittID;Spaltenname , bei Doppelklick wird die Funktion zellebearbeiten aufgerufen-->
                <td id='<?php echo "$row1[0];Abschnitt_Name";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Abschnitt_Name";?>');"><?php echo $row1[2];?></td>
                <td id='<?php echo "$row1[0];Abschnitt_Start";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Abschnitt_Start";?>');"><?php echo $row1[3];?></td>
                <td id='<?php echo "$row1[0];Abschnitt_Ende";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Abschnitt_Ende";?>');"><?php echo $row1[4];?></td>
                <td id='<?php echo "$row1[0];Faser_Anzahl";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Faser_Anzahl";?>');"><?php echo $row1[5];?></td>
                <td id='<?php echo "$row1[0];Faser_Nr";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Faser_Nr";?>');"><?php echo $row1[6];?></td>
                <td id='<?php echo "$row1[0];Faser_Nutzung";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Faser_Nutzung";?>');"><?php echo $row1[7];?></td>
				<td id='<?php echo "$row1[0];Vertrag_Nr";?>'><?php echo $vertrag[0];?></td>
				<td id='<?php echo "$row1[0];Vertrag_Partner";?>'><?php echo $vertrag[1];?></td>
				<td id='<?php echo "$row1[0];Fakura_Start";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Fakura_Start";?>');"><?php echo $row1[9];?></td>
				<td id='<?php echo "$row1[0];Betrag_Einmalig";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Betrag_Einmalig";?>');"><?php echo number_format($row1[10], 2);?></td>
				<td id='<?php echo "$row1[0];Betrag";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Betrag";?>');"><?php echo number_format($row1[11], 2);?></td>
				<td id='<?php echo "$row1[0];Periode";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Periode";?>');"><?php echo $row1[12];?></td>
				<td><?php echo number_format(kosten($row1[11], $row1[12]),2);?></td>
				<td><a href='<?php echo "download.php?abschnitt=$row1[0]"?>' target='_blank'>Vertrag</a></td>
                <form action="#" id="<?php echo "f".$row1[0];?>" method="POST">
                    <td><input type="button" value="L&ouml;schen" onclick="loeschen(<?php echo "f".$row1[0];?>)"/> </td>
                    <input type="hidden" name="loesch_id" value="<?php echo "$row1[0]";?>" />
                </form>
                
        </tr>
    <?php }
}
mysqli_close($link)?>
</table>
</div>
<table>
<td><form action='print_details.php' method='get'>
	<input type="submit" value=" Drucken">
	<input type="hidden" name="leitung" value="<?php echo $leitung;?>" /><!--Druck-Button-->
</form></td>
<td><form action='pdf_details.php' method='get'>
	<input type="submit" value=" PDF erstellen">
	<input type="hidden" name="url" value="<?php echo "http://wan.tkrz.de/print_details.php?leitung=$leitung";?>" /><!--PDF-Erstellen-->
</form></td>
<td><form action="#" id="<?php echo "f".$row[0];?>" method="POST">
    <input type="button" value="L&ouml;schen" onclick="loeschen(<?php echo "f".$row[0];?>)"/> </td>
    <input type="hidden" name="loesch_idl" value="<?php echo "$row[0]";?>" /><!--PDF-Erstellen-->
</form></td>
</table>
</body>
</html>