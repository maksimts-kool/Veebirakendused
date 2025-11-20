<div class="content-card">
<?php
echo "<h2>Ajafunktsioonid PHP-s</h2>";
//timezone
date_default_timezone_set("Europe/Tallinn");
echo "<div class='container'>";
echo "<div class='section'>";
echo "<h3>Praegune aeg</h3>";
echo "<a href='https://www.php.net/manual/en/timezones.europe.php'>Timezone list</a>";
echo "<br>";
echo "time() funktsioon tagastab sekundite arvu alates 1. jaanuar 1970 kuni tänaseni: " . time();
echo "<br>";
echo "date() funktsioon vormindab aja vastavalt soovile: " . date("d.m.Y G:i:s", time());
echo "</div>";

echo "<div class='section'>";
echo "<h3>Praegune aeg 2</h3>";
echo "date('d.m.Y G:i:s'), time())";
echo "<pre> 
d - päev (01-31)
m - kuu (01-12)
Y - aasta (4 numbrit)
G - tund (0-23)
i - minut (00-59)
s - sekund (00-59)
</pre>";
echo "</div>";

echo "<div class='section'>";
echo "<h3>Tehted kuupäevaga</h3>";
echo "<br>";
echo "+1 min=time()+60: " . date("d.m.Y G:i:s", time() + 60);
echo "<br>";
echo "+1 tund=time()´+3600: " . date("d.m.Y G:i:s", time() + 3600);
echo "<br>";
echo "+1 päev=time()+86400: " . date("d.m.Y G:i:s", time() + 86400);
echo "</div>";

echo "<div class='section'>";
echo "<h3>Kuupäeva genereerimine</h3>";
echo "<br>";
echo "mktime(tund, minut, sekund, kuu, päev, aasta) funktsioon genereerib sekundite arvu mingi kindla kuupäeva jaoks: ";
echo "Minu sünnipäev: " . date("d.m.Y G:i:s", mktime(12, 0, 0, 6, 15, 2008));
echo "</div>";

echo "<div class='section'>";
echo "<h3>Massiivi abil näidata kuu nimega</h3>";
echo "<br>";
$kuud = [
    1 => "Jaanuar",
    2 => "Veebruar",
    3 => "Märts",
    4 => "Aprill",
    5 => "Mai",
    6 => "Juuni",
    7 => "Juuli",
    8 => "August",
    9 => "September",
    10 => "Oktoober",
    11 => "November",
    12 => "Detsember"
];
$aasta = date("Y");
$paev = date("d");
$kuu = $kuud[date("n")];
echo "Täna on: " . $paev . ". " . $kuu . " " . $aasta . " a.";
echo "</div>";
echo "</div>";
?>
</div>