<!DOCTYPE HTML>
<html>
<?php include("navbar.html"); ?>	

<head>
	<title>Abschnitt anlegen</title>
</head>

<body>
    <h1>Formular neuen Abschnitt anlegen</h1>
    <form action="Abschnitt_FormSubmit.php" method="post" enctype="multipart/form-data">
        <p>Name/Nummer:<div><input type="text" name="name"/></p></div><br>
        <p>Leitung</p>
        <select name="Leitung" size='5'> 
        <?php 
            include('connect.php');
            $query = "select Leitung_ID, Leitung_Name from leitung";
            $result = mysqli_query($link, $query);
            $num = mysqli_num_rows($result);
            for($i = 0; $i<$num;$i++)
            {
           $row =  mysqli_fetch_array($result);
                echo "<option value= '$row[0]'> $row[1]</option>";
			}
			mysqli_close
       
        ?>
        </select><br>
        <p>Startpunkt:<div><input type="text" name="start"/></p></div>
        <p>Endpunkt:<div><input type="text" name="ende"/></p></div><br>
        <p>Anzahl der Fasern:<div><input type="text" name="f_anzahl"/></p></div>
        <p>Nummer der Faser:<div><input type="text" name="f_nr"/></p></div>
        <p>Nutzung der Faser:<div><input type="text" name="f_nutzung"/></p></div><br>
        <p>Kommentar:</p>
        <textarea name="kommentar" cols="50" rows="20" wrap="virtual" maxlength="400"></textarea><br>
        <p>Vertrag:</p>
        <p>Vertrags Nr./ID:<input type="text" name="v_id"/></p><br>
        <p>Vertragspartner:<input type="text" name="v_partner"/></p><br>
        <p>PDF-Vertrag:<input type="file" name="v_pdf" accept="application/pdf"/></p><br>
		<p>Kosten:</p>
		<p>Fakura Start:<input type="text" name="fakura"/></p><br>
        <p>Betrag einmalig:<input type="text" name="betrag_ein"/></p><br>
        <p>Betrag:<input type="text" name="betrag"/></p><br>
		<select name="periode" size='3'>
			<option value ='Monat'>Monat</option>
			<option value ='Quartal'>Quartal</option>
			<option value ='Jahr'>Jahr</option>
		</select>
		<br>
<input type="submit"/>
</body>
</html>