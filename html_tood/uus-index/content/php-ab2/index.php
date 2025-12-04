<?php
require('config.php');

$teade = "";

// Uue teate lisamine
if (isset($_REQUEST["uusleht"])) {
  $kask = $yhendus->prepare("INSERT INTO lehed (pealkiri, sisu) VALUES (?, ?)");
  $kask->bind_param("ss", $_REQUEST["pealkiri"], $_REQUEST["sisu"]);
  $kask->execute();
  header("Location: ".$_SERVER["PHP_SELF"]."?teade=lisatud");
  $yhendus->close();
  exit();
}

// Teate kustutamine
if (isset($_REQUEST["kustutusid"])) {
  $kask = $yhendus->prepare("DELETE FROM lehed WHERE id=?");
  $kask->bind_param("i", $_REQUEST["kustutusid"]);
  $kask->execute();
  $teade = "Teade kustutatud!";
}

// Teate muutmine
if (isset($_REQUEST["muutmisid"])) {
  $kask = $yhendus->prepare("UPDATE lehed SET pealkiri=?, sisu=? WHERE id=?");
  $kask->bind_param(
    "ssi",
    $_REQUEST["pealkiri"],
    $_REQUEST["sisu"],
    $_REQUEST["muutmisid"]
  );
  $kask->execute();
  $teade = "Teade muudetud!";
}

if (isset($_REQUEST["teade"]) && $_REQUEST["teade"] == "lisatud") {
  $teade = "Teade lisatud!";
}
?>
<!DOCTYPE html>
<html lang="et">
  <head>
    <title>Teated lehel</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <?php if ($teade): ?>
      <div class="teade"><?= htmlspecialchars($teade) ?></div>
    <?php endif; ?>
    <div id="menyykiht">
        <h2>Teated</h2>
        <ul>
          <?php
             $kask = $yhendus->prepare(
               "SELECT id, pealkiri FROM lehed"
             );
             $kask->bind_result($id, $pealkiri);
             $kask->execute();
             while ($kask->fetch()) {
               echo "<li><a href='".$_SERVER["PHP_SELF"].
                    "?id=$id'>".htmlspecialchars($pealkiri)."</a></li>";
             }
          ?>
        </ul>
        <a href="<?=$_SERVER['PHP_SELF']?>?lisamine=jah">Lisa ...</a>
    </div>

    <?php if (isset($_REQUEST["id"]) || isset($_REQUEST["lisamine"])): ?>
    <div id="sisukiht">
       <?php
         // Ühe teate kuvamine või muutmine
         if (isset($_REQUEST["id"])) {
            $kask = $yhendus->prepare("SELECT id, pealkiri, sisu FROM lehed WHERE id=?");
            $kask->bind_param("i", $_REQUEST["id"]);
            $kask->bind_result($id, $pealkiri, $sisu);
            $kask->execute();

            if ($kask->fetch()) {
             if (isset($_REQUEST["muutmine"])) {
                echo "
                   <form action='".$_SERVER["PHP_SELF"]."'>
                     <input type='hidden' name='muutmisid' value='$id'/>
                     <h2>Teate muutmine</h2>
                     <dl>
                       <dt>Pealkiri:</dt>
                       <dd>
                         <input type='text' name='pealkiri' value='".
                                    htmlspecialchars($pealkiri)."'/>
                       </dd>
                       <dt>Teate sisu:</dt>
                       <dd>
                         <textarea rows='20' cols='30' name='sisu'>".
                            htmlspecialchars($sisu)."</textarea>
                       </dd>
                     </dl>                      
                     <input type='submit' value='Muuda' />
                   </form>
                ";
             } else {
              echo "<h2>".htmlspecialchars($pealkiri)."</h2>";
              echo htmlspecialchars($sisu);
              echo "<br /><a href='".$_SERVER["PHP_SELF"].
                   "?kustutusid=$id'>kustuta</a> ";
              echo "<a href='".$_SERVER["PHP_SELF"].
                   "?id=$id&amp;muutmine=jah'>muuda</a>";
             }
            } else {
              echo "Vigased andmed.";
            }
         }

         // Uue teate lisamise vorm
         if (isset($_REQUEST["lisamine"])) {
           ?>
             <form action="<?=$_SERVER["PHP_SELF"]?>">
              <input type="hidden" name="uusleht" value="jah" />
              <h2>Uue teate lisamine</h2>
              <dl>
                <dt>Pealkiri:</dt>
                <dd>
                 <input type="text" name="pealkiri" />
                </dd>
                <dt>Teate sisu:</dt>
                <dd>
                  <textarea rows="20" cols="30" name="sisu"></textarea>
                </dd>
               </dl>
               <input type="submit" value="Sisesta" />
             </form>
           <?php
         }
       ?>
    </div>
    <?php endif; ?>

    <div id="jalusekiht">
       Lehe tegi Maksim
    </div>
  </body>
</html>
<?php
  $yhendus->close();
?>
