<?php
/*
 Avaleht
 Näitab statistikat, kasutaja tulemusi ja eksamite ülevaadet
*/

$lehepealkiri = "Avaleht";
require_once("konf.php");
require_once("auth.php");
require_once("funktsioonid.php");
require_once("header.php");

$kask = $yhendus->prepare("SELECT COUNT(*) as kogus FROM jalgrattaeksam");
$kask->bind_result($kogus);
$kask->execute();
$kask->fetch();
$kask->close();

$kask2 = $yhendus->prepare("SELECT COUNT(*) as lope FROM jalgrattaeksam WHERE luba=1");
$kask2->bind_result($lope);
$kask2->execute();
$kask2->fetch();
$kask2->close();

$minuTulemus = null;
if(onSissologitud()) {
    $userId = kasutajaID();
    $kask3 = $yhendus->prepare("SELECT id, eesnimi, perekonnanimi, teooriatulemus, slaalom, ringtee, t2nav, luba FROM jalgrattaeksam WHERE kasutaja_id=? LIMIT 1");
    $kask3->bind_param("i", $userId);
    $kask3->execute();
    $kask3->bind_result($id, $eesnimi, $perekonnanimi, $teooriatulemus, $slaalom, $ringtee, $t2nav, $luba);
    if($kask3->fetch()) {
        $minuTulemus = [
            'id' => $id,
            'eesnimi' => $eesnimi,
            'perekonnanimi' => $perekonnanimi,
            'teooriatulemus' => $teooriatulemus,
            'slaalom' => $slaalom,
            'ringtee' => $ringtee,
            't2nav' => $t2nav,
            'luba' => $luba
        ];
    }
    $kask3->close();
}
?>

<div class="container">
    <h1>Tervist!</h1>

    <div style="background-color: #3498db; color: white; padding: 30px; border-radius: 8px; margin: 20px 0;">
        <h2 style="color: white; border: none; margin-top: 0;">Jalgratta Eksami Süsteem</h2>
        <p style="color: #ecf0f1; margin-bottom: 0;">Tere tulemast! Siin saad registreeruda jalgratta eksamile ja
            testida oma teadmisi.</p>
    </div>

    <h2>Statistika</h2>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">
        <div style="background-color: #d5f4e6; padding: 20px; border-radius: 8px; text-align: center;">
            <h3 style="color: #27ae60; font-size: 2em; margin: 0;">
                <?php echo $kogus; ?>
            </h3>
            <p style="margin-bottom: 0;">Registreeritud osalejat</p>
        </div>
        <div style="background-color: #d6eaf8; padding: 20px; border-radius: 8px; text-align: center;">
            <h3 style="color: #2980b9; font-size: 2em; margin: 0;">
                <?php echo $lope; ?>
            </h3>
            <p style="margin-bottom: 0;">Eksamit läbinud</p>
        </div>
    </div>

    <?php if(onSissologitud() && $minuTulemus): ?>
    <h2>Minu Eksami Tulemus</h2>
    <table>
        <tr>
            <th>Etapp</th>
            <th>Staatus</th>
        </tr>
        <tr>
            <td><strong>Teooriaeksam</strong></td>
            <td>
                <?php 
                if($minuTulemus['teooriatulemus'] == -1) {
                    echo "<span class='badge badge-warning'>⏳ Ootel</span>";
                } else {
                    echo $minuTulemus['teooriatulemus'] . "/10 punkti";
                    if($minuTulemus['teooriatulemus'] >= 9) {
                        echo " <span class='badge badge-success'>✓ Läbitud</span>";
                    } else {
                        echo " <span class='badge badge-danger'>✗ Ebaõnnestunud</span>";
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td><strong>Slaalom</strong></td>
            <td><?php echo asenda($minuTulemus['slaalom']); ?></td>
        </tr>
        <tr>
            <td><strong>Ringtee</strong></td>
            <td><?php echo asenda($minuTulemus['ringtee']); ?></td>
        </tr>
        <tr>
            <td><strong>Tänavasõit</strong></td>
            <td><?php echo asenda($minuTulemus['t2nav']); ?></td>
        </tr>
        <tr>
            <td><strong>Luba</strong></td>
            <td>
                <?php 
                if($minuTulemus['luba'] == 1) {
                    echo "<span class='badge badge-success'>✓ Väljastatud</span>";
                } else {
                    echo "<span class='badge badge-warning'>⏳ Ootel</span>";
                }
                ?>
            </td>
        </tr>
    </table>
    <?php elseif(onSissologitud()): ?>
    <div class="info">
        <strong>ℹ️ Teave:</strong> Sa ei ole veel eksamile registreeritud. Palun kontakteeru administraatoriga, et
        registreerida eksamile.
    </div>
    <?php endif; ?>

    <h2>Eksami etapid</h2>
    <table>
        <tr>
            <th>Järg</th>
            <th>Nimi</th>
            <th>Kirjeldus</th>
            <th>Nõue</th>
        </tr>
        <tr>
            <td><span class="badge badge-info">1</span></td>
            <td><strong>Teooriaeksam</strong></td>
            <td>10 küsimusest õigete vastuste andmine</td>
            <td>Vähemalt <strong>9 punkti</strong></td>
        </tr>
        <tr>
            <td><span class="badge badge-info">2</span></td>
            <td><strong>Slaalom</strong></td>
            <td>Slaalomsõidu reeglitepärane läbimine</td>
            <td>Teooria läbimine</td>
        </tr>
        <tr>
            <td><span class="badge badge-info">3</span></td>
            <td><strong>Ringtee</strong></td>
            <td>Ringteesõidu sooritamine</td>
            <td>Teooria läbimine</td>
        </tr>
        <tr>
            <td><span class="badge badge-info">4</span></td>
            <td><strong>Tänavasõit</strong></td>
            <td>Liikluses sõitmise eksamimine</td>
            <td>Slaalom + Ringtee läbimine</td>
        </tr>
        <tr>
            <td><span class="badge badge-success">✓</span></td>
            <td><strong>Luba</strong></td>
            <td>Jalgratta sõitumisluba</td>
            <td>Kõik etapid korras</td>
        </tr>
    </table>

    <?php if(!onSissologitud()): ?>
    <h2>Alustamiseks</h2>
    <p>Registreeri kasutaja ja logi sisse!</p>
    <a href="registreerimine.php" class="btn">Registreeri nüüd</a>
    <?php endif; ?>
</div>

<?php require_once("footer.php"); ?>