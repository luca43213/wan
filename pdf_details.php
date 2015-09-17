<?php
function generateDataName($length) {        //Erzeugen eines zufälligen Strings.
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$datei = generateDataName(8).".pdf";//Zufälliger Name.pdf
$pfad = "/var/www/wan/".$datei;//Pfad zum speichern der Datei
//$wkhtmltopdf = '"C:/Program Files/wkhtmltopdf/bin/wkhtmltopdf.exe"';//Pfad der wkhtmltopdf.exe, Programm das die Html nach PDF wandelt
$url = $_GET['url'];
//$cmd = $wkhtmltopdf.' --javascript-delay 1000 '.$url.' '.$pfad;
shell_exec('wkhtmltopdf --no-stop-slow-scripts --javascript-delay 2000 '.$url.' '.$pfad);//Ausführen von wkhtmltopdf "--no-stop-slow-scripts Damit scripts die das aufbauen einer Seite verlangsamen
                                                                                           //nicht ignoriert werden d.h das gewartet wird bis die Google-Map aufgebaut ist.
header('Content-Type: application/pdf');//Datei den Browser anbieten
$pdf = file_get_contents($pfad);
echo $pdf;
unlink($pfad);//Datei löschen

?>
