<?php

/**
 * @author Till Kröger 
 * @copyright 2015
 */
$host = "localhost";
$user = "abfragetest";
$db = "wan";
$pw = "kevin";
$link = mysqli_connect($host, $user, $pw, $db);
if(!$link)
{
  exit("Verbindungsfehler: ".mysqli_connect_error());
}




?>