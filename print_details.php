<?php
ob_start();
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Details</title>
    
    <style type="text/css" media="all">
        @import "design1.css";
        input[type="text"] { border: none }
        </style>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyCRoQh_wvHfx-jN9cQa2wW6bnQF5dGf0so&sensor=false"></script>
<script type="text/javascript" >
function zellebearbeiten(item) {}

function zellebearbeiten_l(item) {}

function loeschen() {
	if (confirm("Datensatz loeschen?")) {
		document.forms[0].submit();
	}

}

function alertloesch() {
	alert("Datensatz wurde geloescht!");
}

function alertKnoten() {
	alert("Knoten nicht vorhanden!");
}

function load(MarkerA, MarkerB) {
	var floatAx = parseFloat(MarkerA[0]);
	var floatAy = parseFloat(MarkerA[1]);
	var floatBx = parseFloat(MarkerB[0]);
	var floatBy = parseFloat(MarkerB[1]);
	var centerx = (floatAx + floatBx) / 2;
	var centery = (floatAy + floatBy) / 2;
	var Markers = [
		new google.maps.LatLng(floatAx, floatAy),
		new google.maps.LatLng(floatBx, floatBy)
	];


	var mapOptions = {
		zoom: 10,
		center: new google.maps.LatLng(centerx, centery)
	}
	var map = new google.maps.Map(document.getElementById("map"), mapOptions);
	var markerA = new google.maps.Marker({
		position: Markers[0],
		title: "Start"
	});
	markerA.setMap(map);
	var markerB = new google.maps.Marker({
		position: Markers[1],
		title: "Ende"
	});
	markerB.setMap(map);
	var linie = new google.maps.Polyline({
		path: Markers,
		geodesic: true,
		strokeColor: '#FF0000',
		strokeOpacity: 1.0,
		strokeWeight: 2
	});
	linie.setMap(map);

	var bounds = new google.maps.LatLngBounds();
	for (var i = 0, LtLgLen = Markers.length; i < LtLgLen; i++) {
		bounds.extend(Markers[i]);
	}
	map.fitBounds(bounds);
	google.maps.event.addListener(map, 'tilesloaded', function() {
		window.print();//wenn fertig geladen dann Drucken
		history.back();
	});
}

</script>
</head>
<?php
include ('connect.php');
if(isset($_GET['leitung']))
{

    $leitung = $_REQUEST['leitung'];
    $result_leitung = mysqli_query($link, "select * from leitung where Leitung_ID = '$leitung' limit 1");
    $result_abschnitt = mysqli_query($link, "select * from abschnitt where Leitung_ID = '$leitung'");
    $num_abschnitt = mysqli_num_rows($result_abschnitt);
    $row = mysqli_fetch_array($result_leitung);
    $result_maps = mysqli_query($link, "select Knoten_Laengengrad, Knoten_Breitengrad from knoten where Knoten_Name ='$row[2]' or Knoten_Name ='$row[3]'");
    $row_maps = mysqli_fetch_array($result_maps);
    $markerA = json_encode(array($row_maps[1], $row_maps[0]));
    $row_maps = mysqli_fetch_array($result_maps);
    $markerB = json_encode(array($row_maps[1], $row_maps[0]));
    echo "<body onload='load($markerA, $markerB)'>";
?>
    
    
<div class='CSSTableGenerator'>


<?php

if(isset($_POST['id']))
{
    $wert = $_POST['wert'];
    $id = $_POST['id'];
    $explode = explode(';',$id);
    mysqli_query($link, "Update abschnitt SET $explode[1] = '$wert' where Abschnitt_ID = '$explode[0]'");
    unset($_POST['wert']);
    unset($_POST['id']);
    
}
if(isset($_POST['id_l']))
{
    $wert_l = $_POST['wert_l'];
    $id_l = $_POST['id_l'];
    $explode = explode(';',$id_l);
    $result_knoten = mysqli_query($link, "select Knoten_Name from knoten");
    $num_knoten = mysqli_num_rows($result_knoten);
    $chk = false;
    if($explode[1]=="Leitung_Start"||$explode[1]=="Leitung_Ende")
    {
        for($q = 0; $q<$num_knoten;$q++)
        {
            $row_knoten = mysqli_fetch_array($result_knoten);           
            if($wert_l == $row_knoten[0])
            {
                $chk = true;
            }
        }
    }
    else{$chk = true;}
    if($chk==true)
    {
        mysqli_query($link, "Update leitung SET $explode[1] = '$wert_l' where Leitung_ID = '$explode[0]'");
    }
    else
    {
       echo "<body onload='alertKnoten()'></body>"; 
    }
        unset($_POST['wert_l']);
        unset($_POST['id_l']);       
}


    
?>
    <table>
    <tr>
        <td colspan = '6'>Leitung:<?php echo $row[1];?></td>
    </tr>
    <tr>
        <td colspan=''><b>Leitung</b></td>
		<td id='<?php echo "$row[0];Leitung_Name";?>' ondblclick="zellebearbeiten_l('<?php echo "$row[0];Leitung_Name";?>');"><?php echo $row[1];?></td>
		<td rowspan='3' style='width: 37%'><div id='map' style='width: 400px; height: 300px;'></div></td>
		<script type="text/javascript" >setTimeout('',1000); </script>
    <tr>
        <td colspan=''><b>Start</b></td>
		<td id='<?php echo "$row[0];Leitung_Start";?>' ondblclick="zellebearbeiten_l('<?php echo "$row[0];Leitung_Start";?>');"><?php echo $row[2];?></td>
	</tr>
    <tr>
        <td colspan=''><b>Ende</b></td>
		<td id='<?php echo "$row[0];Leitung_Ende";?>' ondblclick="zellebearbeiten_l('<?php echo "$row[0];Leitung_Ende";?>');"><?php echo $row[3];?></td>
    </tr>
	<tr></tr>
    </table><br>
    <table>
          
            
    <tr>
        <td colspan='7'>Abschnitte</td>
    </tr>
    <tr>
        <td><b>Abschnitt</b></td>
        <td><b>von Knoten</b></td>
        <td><b>bis Knoten</b></td>
        <td><b>Faser/Anzahl</b></td>
        <td><b>Faser/Nr.</b></td>
        <td><b>Faser/Nutzung</b></td><td></td>
    </tr>
<?php
    for($i = 0; $i<$num_abschnitt;$i++)
    {
    $row1 = mysqli_fetch_array($result_abschnitt);?>
    <tr>
                <td id='<?php echo "$row1[0];Abschnitt_Name";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Abschnitt_Name";?>');"><?php echo $row1[2];?></td>
                <td id='<?php echo "$row1[0];Abschnitt_Start";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Abschnitt_Start";?>');"><?php echo $row1[3];?></td>
                <td id='<?php echo "$row1[0];Abschnitt_Ende";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Abschnitt_Ende";?>');"><?php echo $row1[4];?></td>
                <td id='<?php echo "$row1[0];Faser_Anzahl";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Faser_Anzahl";?>');"><?php echo $row1[5];?></td>
                <td id='<?php echo "$row1[0];Faser_Nr";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Faser_Nr";?>');"><?php echo $row1[6];?></td>
                <td id='<?php echo "$row1[0];Faser_Nutzung";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Faser_Nutzung";?>');"><?php echo $row1[7];?></td>
                <form action="#" name="formloesch" method="POST">
                    <input type="hidden" name="loesch_id" value="<?php echo "$row1[0]";?>" />
                </form>
                
        </tr>
    <?php }
}
mysqli_close($link)?>
</table>
</div> 
</body>
</html>
