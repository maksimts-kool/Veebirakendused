<?php
/**
 * Üldised abifunktsioonid
 * Sisaldab valideerimisfunktsioone, teksti töötlemist ja turvalisuse funktsioone
 */

function asenda($nr) { // teisendab numbrid värvilisteks badge'ideks
  if($nr == -1) {
    return "<span class='badge badge-warning'>⏳ Tegemata</span>";
  } 
  if($nr == 1) {
    return "<span class='badge badge-success'>✓ Korras</span>";
  } 
  if($nr == 2) {
    return "<span class='badge badge-danger'>✗ Ebaõnnestunud</span>";
  }
  return "Tundmatu"; 
}

function valideeriTeooriaTulemus($tulemus) { // kontrollib, kas teooria tulemus on 9-10 punkti
  $tulemus = (int)$tulemus;
  
  if($tulemus < 0 || $tulemus > 10) {
    return [
      'edukas' => false,
      'sõnum' => 'Tulemus peab olema 0-10!'
    ];
  }
  
  if($tulemus < 9) {
    return [
      'edukas' => false,
      'sõnum' => "❌ Vajalik on vähemalt 9 punkti! Saite ainult $tulemus punkti."
    ];
  }
  
  return [
    'edukas' => true,
    'sõnum' => "✓ Tulemus $tulemus sisestatud edukalt!"
  ];
}

function valideeriNimi($nimi) { // kontrollib, kas nimi on vähemalt 3 tähemärki
  $nimi = trim($nimi);
  
  if(empty($nimi)) {
    return [
      'edukas' => false,
      'sõnum' => 'Nimi ei saa olla tühi!'
    ];
  }
  
  if(strlen($nimi) < 3) {
    return [
      'edukas' => false,
      'sõnum' => 'Nimi peab olema vähemalt 3 tähemärki!'
    ];
  }
  
  return [
    'edukas' => true,
    'sõnum' => ''
  ];
}

function kontrollilubaMögus($teooriatulemus, $slaalom, $ringtee, $t2nav, $luba) { // kontrollib, kas kõik nõuded loa saamiseks on täidetud
  $voib_lubada = (
    $teooriatulemus >= 9 && 
    $slaalom == 1 && 
    $ringtee == 1 && 
    $t2nav == 1 && 
    $luba == -1
  );
  
  return $voib_lubada;
}

function kuvaTeade($tyyp, $sisu) { // näitab kasutajale teateid (viga, edukas, info)
  if(empty($sisu)) {
    return '';
  }
  
  $klassid = [
    'viga' => 'viga',
    'edukas' => 'edukas',
    'info' => 'info'
  ];
  
  $klass = isset($klassid[$tyyp]) ? $klassid[$tyyp] : 'info';
  
  return "<div class='$klass'>$sisu</div>";
}

function turvTekst($tekst) { // on vaja et PHP ei tõlgendaks HTML silte
  return htmlspecialchars($tekst, ENT_QUOTES, 'UTF-8');
}

function onPostPäring() { // kontrollib, kas päring on POST meetodiga
  return $_SERVER["REQUEST_METHOD"] == "POST";
}

function getPOST($võti, $vaikeVäärtus = '') { // võtab turvaliselt väärtuse POST massivist, et kasutaja ei saaks XSS rünnakut teha
  if(isset($_POST[$võti])) {
    return trim($_POST[$võti]);
  }
  return $vaikeVäärtus;
}

function getGET($võti, $vaikeVäärtus = '') { // võtab turvaliselt väärtuse GET massivist, et kasutaja ei saaks XSS rünnakut teha
  if(isset($_GET[$võti])) {
    return trim($_GET[$võti]);
  }
  return $vaikeVäärtus;
}

?>