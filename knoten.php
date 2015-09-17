<!DOCTYPE HTML>

<html>
<?php include("navbar.html"); ?>	
<head>
	<title>Knoten</title>
    
    <style type="text/css" media="all">
        @import "design1.css";
        input[type="text"] { border: none }
      </style>
<script type="text/javascript" >
function zellebearbeiten(item) { 
	elem = document.getElementById(item);
	val = elem.innerHTML;
	elem.setAttribute("ondblclick", "");
	size = elem.innerHTML.toString().length;
	elem.innerHTML = "<form name='input_form' action='#' method='post'> " +
		" <input name='id' type='hidden' value='' /> " +
		" <input name='wert' class='textfield_effect' type='text' value='" +
		val + "' size='" + size + "' onblur='document.input_form.submit();' />" +
		"</form> ";
	document.input_form.id.value = item;
	document.input_form.wert.focus();
}
function loeschen(formid) {//Bestätigung des Löschvorgangs
	
	if (confirm("Datensatz loeschen?")) {
		formid.submit();
	}

}

function alertloesch() {//Erfolgreiches Löschen
	alert("Datensatz wurde geloescht!");
}
</script>
</head>
<body>
<?php
include('connect.php');
if(isset($_POST['id']))//Wenn eine Zelle bearbeiten worden ist , neuen Wert in die Datenbank übertragen.
{
    $wert = $_POST['wert'];
    $id = $_POST['id'];
    $explode = explode(';',$id);
	mysqli_query($link, "Update knoten SET $explode[1] = '$wert' where Knoten_Name = '$explode[0]'");	
    unset($_POST['wert']);
    unset($_POST['id']);
    
}
if(isset($_POST['loesch_id']))//Wenn auf den Löschbutton gedrückt worden ist...
{
	$loesch_id = $_POST['loesch_id'];//If-Abfrage ob beide Löschabfragen erfolgreich sind
	$loesch_id = str_replace("_"," ",$loesch_id);
    if(mysqli_query($link,"DELETE FROM `wan`.`knoten` WHERE `knoten`.`Knoten_Name` = '$loesch_id' "))
    {
        echo "<body onload='alertloesch()'></body>";//Datensatz wurde erfolgreich gelöscht.
    }
	unset($_POST['loesch_id']);
}
$result = mysqli_query($link, "select * from knoten");
$num = mysqli_num_rows($result);
echo"
<div class='CSSTableGenerator'>
<table>                      
    <tr>
        <td colspan='9'>Knoten</td>
    </tr>
    <tr>
        <td><b>Name</b></td>
        <td><b>PLZ</b></td>
        <td><b>Ort</b></td>
        <td><b>Stra&szlig;e</b></td>
        <td><b>Hausnummer</b></td>
        <td><b>Breitengrad</b></td>
		<td><b>L&auml;ngengrad</b></td>
		<td></td>
    </tr>";
    for($i = 0; $i<$num;$i++)
    {
    $row1 = mysqli_fetch_array($result);

	?>
    <tr><!-- Knoten_Name ist nicht bearbeitbar da es ein Primär bzw. Fremdschlüssel ist-->
                <td id='<?php echo "$row1[0];Knoten_Name";?>'><?php echo $row1[0];?></td>
                <td id='<?php echo "$row1[0];Knoten_PLZ";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Knoten_PLZ";?>');"><?php echo $row1[1];?></td>
                <td id='<?php echo "$row1[0];Knoten_Ort";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Knoten_Ort";?>');"><?php echo $row1[2];?></td>
                <td id='<?php echo "$row1[0];Knoten_Strasse";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Knoten_Strasse";?>');"><?php echo $row1[3];?></td>
                <td id='<?php echo "$row1[0];Knoten_Hnr";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Knoten_Hnr";?>');"><?php echo $row1[4];?></td>
                <td id='<?php echo "$row1[0];Knoten_Breitengrad";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Knoten_Breitengrad";?>');"><?php echo $row1[5];?></td>
                <td id='<?php echo "$row1[0];Knoten_Laengengrad";?>' ondblclick="zellebearbeiten('<?php echo "$row1[0];Knoten_Laengengrad";?>');"><?php echo $row1[6];?></td>
				<?php $row1[0] = str_replace(" ","_",$row1[0]);?>
				<form action="#" id="<?php echo "f$row1[0]";?>" method="POST">
                    <td><input type="button" value="L&ouml;schen" onclick="loeschen(<?php echo "f$row1[0]";?>)"/> </td>
                    <input type="hidden" name="loesch_id" value="<?php echo "$row1[0]";?>" />
                </form>
    </tr>
    <?php
    }
echo "</table></div>";
?>
</body>
</html>    







 