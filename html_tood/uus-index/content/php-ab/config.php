<?php
$serverinimi = "d141144.mysql.zonevs.eu";
$kasutajanimi = "d141144_maksimts";
$salasana = "";
$andmebaasinnimi = "maksimts";
$connect = new mysqli($serverinimi, $kasutajanimi, $salasana, $andmebaasinnimi);
$connect->set_charset("utf8");
?>