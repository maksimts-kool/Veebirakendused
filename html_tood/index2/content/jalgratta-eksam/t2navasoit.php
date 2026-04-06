<?php
/*
 Tänavasõidu katse leht
 Admin saab sisestada tänavasõidu katse tulemusi (korras/ebaõnnestunud)
*/

$lehepealkiri = "Tänavasõit";
require_once("konf.php");
require_once("auth.php");
require_once("funktsioonid.php");

nouaSisselogimist('login.php?nouab_sisselogimist=1');
app_handle_ip_request_submission([
  'return_to' => 't2navasoit.php',
  'reason' => 'Save street ride exam result',
]);

$teade = "";

if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["korras_id"])){ 
  if(onAdmin()) {
  app_require_authorized_ip_for_action('Save street ride exam result', 't2navasoit.php');
  $kask = $yhendus->prepare("UPDATE jalgrattaeksam SET t2nav=1 WHERE id=?"); 
  $kask->bind_param("i", $_POST["korras_id"]); 
  $kask->execute(); 
  $teade = "✓ Tulemus sisestatud!";
  }
} 

if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["vigane_id"])){ 
  if(onAdmin()) {
  app_require_authorized_ip_for_action('Save street ride exam result', 't2navasoit.php');
  $kask = $yhendus->prepare("UPDATE jalgrattaeksam SET t2nav=2 WHERE id=?"); 
  $kask->bind_param("i", $_POST["vigane_id"]); 
  $kask->execute(); 
  $teade = "✓ Tulemus sisestatud!";
  }
} 

$kask = $yhendus->prepare(
  "SELECT id, eesnimi, perekonnanimi FROM jalgrattaeksam WHERE slaalom=1 AND ringtee=1 AND t2nav=-1"
); 
$kask->bind_result($id, $eesnimi, $perekonnanimi); 
$kask->execute(); 

$osalejaread = [];
while($kask->fetch()) {
  $osalejaread[] = ['id' => $id, 'eesnimi' => $eesnimi, 'perekonnanimi' => $perekonnanimi];
}
?>

<?php require_once("header.php"); ?>

<div class="container">
    <h1>🛣️ Tänavasõit</h1>
    <?php echo app_render_ip_access_panel([
      'return_to' => 't2navasoit.php',
      'reason' => 'Save street ride exam result',
    ]); ?>

    <?php if($teade) echo "<div class='edukas'>$teade</div>"; ?>

    <div class="info">
        Kontrollige tänavasõitu ja märkige tulemus.
    </div>

    <?php if(empty($osalejaread)) { ?>
    <div class="edukas">✓ Kõik osalejad on tänavasõidu sooritanud!</div>
    <?php } else { ?>

    <table>
        <tr>
            <th>Eesnimi</th>
            <th>Perekonnanimi</th>
            <th>Tulemus</th>
        </tr>

        <?php foreach($osalejaread as $osaleja) { ?>
        <tr>
            <td><?php echo htmlspecialchars($osaleja['eesnimi']); ?></td>
            <td><?php echo htmlspecialchars($osaleja['perekonnanimi']); ?></td>
            <td>
                <?php if(onAdmin()): ?>
                <form method="POST" style="display: flex; gap: 10px;">
                    <input type="hidden" name="korras_id" value="<?php echo $osaleja['id']; ?>" />
                    <input type="submit" value="✓ Korras" class="btn btn-info" />
                </form>
                <form method="POST" style="display: flex; gap: 10px;">
                    <input type="hidden" name="vigane_id" value="<?php echo $osaleja['id']; ?>" />
                    <input type="submit" value="✗ Ebaõnnestunud" class="btn btn-danger" />
                </form>
                <?php else: ?>
                ⏳ Ootel
                <?php endif; ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <?php } ?>
</div>

<?php require_once("footer.php"); ?>
