<html>
<?php
include("navbar.html");

?>
<head>
	<title>Leitung anlegen</title>
</head>

<body>
    <h1>Formular neue Leitung anlegen</h1>
    <form action="Leitung_FormSubmit.php" method="post">
        <p>Name/Nummer:<div><input type="text" name="name"/></p></div><br>
        <p>Startpunkt</p>
        <select name="Sknoten" size='5'> 
        <?php 
            include('connect.php');
            $query = "select Knoten_Name from knoten";
            $result = mysqli_query($link, $query);
            $num = mysqli_num_rows($result);
            for($i = 0; $i<$num;$i++)
            {
                $row = mysqli_fetch_array($result);
                echo "<option value= '$row[0]' size='5'> $row[0]</option>";
            }   
       
        ?>
        </select>
        <p><br>Endpunkt</p>
        <select name="Eknoten" size='5'> 
        <?php 
            $query = "select Knoten_Name from knoten";
            $result = mysqli_query($link, $query);
            $num = mysqli_num_rows($result);
            for($i = 0; $i<$num;$i++)
            {
                $row = mysqli_fetch_array($result);
                echo "<option value= '$row[0]' size='5'> $row[0]</option>";
            }
            mysqli_close($link);   
        ?>
        </select><br>
        <p>Kommentar:</p>
        <textarea name="kommentar" cols="50" rows="30" wrap="virtual" maxlength="400"></textarea><br>
        <input type="submit"/>
		
		
		
    </form>
    </body>
</html>