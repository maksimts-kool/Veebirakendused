<?php
/*
 Väljalogimine
 Lõpetab kasutaja sessiooni ja suunab sisselogimise lehele
*/

require_once("auth.php");

valjalogimine();

header("Location: login.php?logout=1");
exit();