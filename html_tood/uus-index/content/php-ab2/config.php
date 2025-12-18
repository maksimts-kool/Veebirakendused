<?php
$serverinimi = "d141144.mysql.zonevs.eu";
$kasutajanimi = "d141144_maksimts";
$salasana = "Makism123.";
$andmebaasinnimi = "d141144_maksimts";
$yhendus = new mysqli($serverinimi, $kasutajanimi, $salasana, $andmebaasinnimi);
$yhendus->set_charset("utf8");
?>