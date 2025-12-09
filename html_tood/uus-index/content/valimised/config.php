<?php
$serverinimi = "127.0.0.1";
$kasutajanimi = "maksimts";
$salasana = "1111";
$andmebaasinnimi = "maksimts";
$yhendus = new mysqli($serverinimi, $kasutajanimi, $salasana, $andmebaasinnimi);
$yhendus->set_charset("utf8");
?>