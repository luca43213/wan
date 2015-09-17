<?php
include('navbar.html');

/*
 * @author 
 * @copyright 2015
 */

if (isset($_POST['name']) && isset($_POST['Leitung']) ) {
    include ("connect.php");  
    $name = $_POST['name'];
    $leitung= $_POST['Leitung'];
    $start= $_POST['start'];
    $ende = $_POST['ende'];
    $f_anzahl = $_POST['f_anzahl'];
    $f_nr = $_POST['f_nr'];
    $f_nutzung = $_POST['f_nutzung'];
    $kom = $_POST['kommentar'];
    $v_id = $_POST['v_id'];
    $v_partner = $_POST['v_partner'];
    move_uploaded_file($_FILES['v_pdf']['tmp_name'], "/var/www/wan/tempfile.tmp"); //Temporäres speichern der vom Benutzer ausgewählten Datei.
    chmod("/var/www/wan/tempfile.tmp", 0644);//Leserechte einräumen
    $zeiger = fopen("/var/www/wan/tempfile.tmp", "rb"); //r = Lesemodus b= Binäre Übersetzungsmodus
    $size = $_FILES['v_pdf']['size'];
    $data = fread($zeiger, $size); //Dateilesen
    fclose($zeiger);//Stream beenden
    @unlink("/var/www/wan/tempfile.tmp");//Datei löschen
    $data = base64_encode($data);//"PDF-Datei" in "Binär-Datei" umwandeln
    $dateiname = str_replace(" ", "_", $_FILES['v_pdf']['name']);//Leerzeichen ersetzen
    $type = $_FILES['v_pdf']['type']; 
    
    $fakura = $_POST['fakura'];
    $betrag_ein = $_POST['betrag_ein'];
    $betrag = $_POST['betrag'];
    $periode = $_POST['periode'];
    
    
    echo "Ihr Eingaben:<br> Name: '$name'<br> Leitung: '$leitung'<br> Startpunkt: '$start'<br> Endpunkt:: '$ende'<br> Faser/Anzahl: '$f_anzahl'<br>Faser/Nr.: '$f_nr'<br> Faser/Nutzung: '$f_nutzung'<br> Kommentar:<br>'$kom'<br>";
    $query = "insert into abschnitt(Leitung_ID, Abschnitt_Name, Abschnitt_Start, Abschnitt_Ende, Faser_Anzahl, Faser_Nr, Faser_Nutzung, Kommentar, Fakura_Start, Betrag_Einmalig, Betrag, Periode) VALUES('$leitung', '$name', '$start', '$ende', '$f_anzahl', '$f_nr', '$f_nutzung', '$kom', '$fakura', '$betrag_ein', '$betrag', '$periode')";
    $result_abschnitt = mysqli_query($link, $query);
    $result = mysqli_query($link, "select Abschnitt_ID from abschnitt Order by Abschnitt_ID desc limit 1");
    $abschnitt_id = mysqli_fetch_array($result);
    $query2 = "insert into vertrag(Vertrag_Nr, Abschnitt_ID, Vertrag_Partner, pdf_name, pdf_type, pdf_data, pdf_size) VALUES('$v_id', '$abschnitt_id[0]', '$v_partner', '$dateiname', '$type', '$data', '$size')";
    if ($result_abschnitt and mysqli_query($link, $query2) ) {
        echo "<font color=\"green\" size=\"10\">Eintrag erfolgreich</font>";
    } else {
        echo "<font color=\"red\" size=\"10\">Eintrag fehlgeschlagen</font>";
    }
    echo '<form action="Abschnitt_Form.php"><input type="submit" value="zurueck"></form>';
    mysqli_close($link);

}
else{  echo "<font color=\"red\" size=\"10\">Eintrag fehlgeschlagen</font>";}

?>