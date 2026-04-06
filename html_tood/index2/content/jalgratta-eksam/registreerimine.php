<?php
/*
 Registreerimise leht
 Uute kasutajate registreerimine süsteemi
*/

$lehepealkiri = "Registreerimine";
require_once("konf.php");
require_once("auth.php");
require_once("funktsioonid.php");

suunaKuiSissologitud('index.php');
app_handle_ip_request_submission([
    'return_to' => 'registreerimine.php',
    'reason' => 'Register new exam participant',
]);

$viga = "";
$edu = "";

if(isset($_POST["regist_nupp"])){ 
    $kasutajanimi = getPOST("kasutajanimi");
    $parool = getPOST("parool");
    $paroolKinnitatud = getPOST("parool_kinnita");
    $eesnimi = getPOST("eesnimi");
    $perekonnanimi = getPOST("perekonnanimi");
    
    $validateesnimi = valideeriNimi($eesnimi);
    $validateperekonnanimi = valideeriNimi($perekonnanimi);
    
    if(!$validateesnimi['edukas']) {
        $viga = "Eesnimi: " . $validateesnimi['sõnum'];
    } 
    elseif(!$validateperekonnanimi['edukas']) {
        $viga = "Perekonnanimi: " . $validateperekonnanimi['sõnum'];
    }
    else {
        app_require_authorized_ip_for_action('Register new exam participant', 'registreerimine.php');
        $tulemus = registreeriKasutaja($yhendus, $kasutajanimi, $parool, $paroolKinnitatud);
        
        if($tulemus['edukas']) {
            $getUserId = $yhendus->prepare("SELECT id FROM kasutajad WHERE kasutajanimi = ?");
            $getUserId->bind_param("s", $kasutajanimi);
            $getUserId->execute();
            $getUserId->bind_result($newUserId);
            $getUserId->fetch();
            $getUserId->close();
            
            $kask = $yhendus->prepare(
                "INSERT INTO jalgrattaeksam(kasutaja_id, eesnimi, perekonnanimi) VALUES (?, ?, ?)"
            );
            $kask->bind_param("iss", $newUserId, $eesnimi, $perekonnanimi);
            $kask->execute();
            $kask->close();
            
            header("Location: login.php?registered=1");
            exit();
        } else {
            $viga = $tulemus['sõnum'];
        }
    }
}

require_once("header.php");
?>

<div class="container">
    <h1>📝 Registreerimine</h1>
    <?php echo app_render_ip_access_panel([
        'return_to' => 'registreerimine.php',
        'reason' => 'Register new exam participant',
    ]); ?>

    <?php 
    if($viga) { 
        echo kuvaTeade('viga', "❌ $viga");
    }
    if($edu) { 
        echo kuvaTeade('edukas', "✓ $edu<br>Suunatakse sisselogimise lehele...");
    }
    ?>

    <div class="info">
        <strong>ℹ️ Teave:</strong> Loo endale kasutajakonto, et pääseda ligi jalgratta eksamile.
        Pärast registreerumist saad sisse logida ja alustada eksamit.
    </div>

    <form method="POST">
        <dl>
            <dt>👤 Eesnimi</dt>
            <dd>
                <input type="text" name="eesnimi" minlength="3" required placeholder="Näiteks: Jaan"
                    value="<?php echo isset($_POST['eesnimi']) ? turvTekst($_POST['eesnimi']) : ''; ?>" />
                <small>Vähemalt 3 tähemärki</small>
            </dd>

            <dt>👤 Perekonnanimi</dt>
            <dd>
                <input type="text" name="perekonnanimi" minlength="3" required placeholder="Näiteks: Tamm"
                    value="<?php echo isset($_POST['perekonnanimi']) ? turvTekst($_POST['perekonnanimi']) : ''; ?>" />
                <small>Vähemalt 3 tähemärki</small>
            </dd>

            <dt>👤 Kasutajanimi</dt>
            <dd>
                <input type="text" name="kasutajanimi" minlength="3" required placeholder="Vähemalt 3 tähemärki"
                    value="<?php echo isset($_POST['kasutajanimi']) ? turvTekst($_POST['kasutajanimi']) : ''; ?>" />
                <small>Vähemalt 3 tähemärki</small>
            </dd>

            <dt>🔒 Parool</dt>
            <dd>
                <input type="password" name="parool" minlength="5" required placeholder="Vähemalt 5 tähemärki" />
                <small>Vähemalt 6 tähemärki</small>
            </dd>

            <dt>🔒 Kinnita Parool</dt>
            <dd>
                <input type="password" name="parool_kinnita" minlength="5" required
                    placeholder="Sisesta parool uuesti" />
                <small>Sisesta sama parool uuesti</small>
            </dd>

            <dt>
                <input type="submit" name="regist_nupp" value="Registreeri" />
            </dt>
        </dl>
    </form>

    <div style="margin-top: 20px; text-align: center;">
        <p>Juba on kasutaja? <a href="login.php" class="btn" style="display: inline-block; padding: 10px 20px;">Logi
                sisse</a></p>
    </div>
</div>

<?php require_once("footer.php"); ?>
