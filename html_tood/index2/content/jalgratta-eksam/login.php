<?php
/*
 Sisselogimise leht
 Võimaldab kasutajatel süsteemi siseneda
*/

$lehepealkiri = "Sisselogimine";
require_once("konf.php");
require_once("auth.php");
require_once("funktsioonid.php");

suunaKuiSissologitud('index.php');

$viga = "";
$edu = "";

if(isset($_POST["login_nupp"])){ 
    $kasutajanimi = getPOST("kasutajanimi");
    $parool = getPOST("parool");
    
    $tulemus = sisenemine($yhendus, $kasutajanimi, $parool);
    
    if($tulemus['edukas']) {
        header("Location: index.php");
        exit();
    } else {
        $viga = $tulemus['sõnum'];
    }
}

require_once("header.php");
?>

<div class="container">
    <h1>🔐 Sisselogimine</h1>

    <?php 
    if($viga) { 
        echo kuvaTeade('viga', "❌ $viga");
    }
    if(isset($_GET['logout'])) { 
        echo kuvaTeade('edukas', "✓ Välja logitud!");
    }
    if(isset($_GET['registered'])) { 
        echo kuvaTeade('edukas', "✓ Registreerimine õnnestus! Oled automaatselt eksamile registreeritud. Logi nüüd sisse.");
    }
    if(isset($_GET['nouab_sisselogimist'])) { 
        echo kuvaTeade('info', "ℹ️ See leht nõuab sisselogimist!");
    }
    ?>

    <div class="info">
        <strong>ℹ️ Teave:</strong> Logi sisse, et pääseda ligi jalgratta eksamisüsteemile.
    </div>

    <form method="POST">
        <dl>
            <dt>👤 Kasutajanimi</dt>
            <dd>
                <input type="text" name="kasutajanimi" required placeholder="Sisesta kasutajanimi"
                    value="<?php echo isset($_POST['kasutajanimi']) ? turvTekst($_POST['kasutajanimi']) : ''; ?>"
                    autofocus />
            </dd>

            <dt>🔒 Parool</dt>
            <dd>
                <input type="password" name="parool" required placeholder="Sisesta parool" />
            </dd>

            <dt>
                <input type="submit" name="login_nupp" value="Logi sisse" />
            </dt>
        </dl>
    </form>

    <div style="margin-top: 20px; text-align: center;">
        <p>Pole veel kasutajat? <a href="registreerimine.php" class="btn"
                style="display: inline-block; padding: 10px 20px;">Registreeri</a></p>
    </div>
</div>

<?php require_once("footer.php"); ?>