<div class="content-card">
    <?php
    echo "<div class='section'>";
    echo "<h2>Tekstifunktsioonid</h2>";
    $tekst = "PHP on skriptikeel serveripoolne";
    echo $tekst;
    echo "<br>teksti pikkus -strlen() =" . strlen($tekst) . " tähemärgi";
    echo "<br>Esimesed 6 tähte -substr() =" . substr($tekst, 0, 6);
    echo "<br>Alates 6 tähest =" . substr($tekst, 6);
    echo "<br>Sõnade arv lauses -str_word_count() " . str_word_count($tekst) . " sõna lauses";
    echo "<br>Kõik tähed on suured -" . strtoupper($tekst);
    echo "<br>Kõik tähed on väikesed -" . strtolower($tekst);
    echo "<br>Iga sõna algab suure tähega -" . ucwords($tekst);
    echo "<br>Vaata " . ucwords(strtolower($tekst));
    
    $tekst2 = '           PHP on skriptikeel serveripoolne           ';
    echo "<br>|" . $tekst2 . "|";
    echo "trim() -eemaldab tekstist tühikuid paremnalt ja vasakult -" . trim($tekst2);
    echo "<br>ltrim() -eemaldab tekstist tühikuid vasakult -" . ltrim($tekst2);
    echo "<br>rtrim() -eemaldab tekstist tühikuid paremnalt -" . rtrim($tekst2);
    
    echo "<div class='inner-section'>";
    echo "<h3>Tekst kui massiiv</h3>";
    echo "$tekst- 1.täht massiivist - " . $tekst[0];
    echo "<br>$tekst- 5.täht massiivist - " . $tekst[4];
    echo "<br>";
    
    print_r(str_word_count($tekst, 1));
    $syna = str_word_count($tekst, 1);
    echo "<br>massivist 2 sõna" . $syna[2];
    echo "<br>";
    print_r(str_word_count($tekst, 2));
    echo "</div>";
    echo "</div>";
    
    echo "<div class='section'>";
    echo "<h2>Teksti asendamine -replace</h2>";
    $asendus = 'Javascript';
    echo substr_replace($tekst, $asendus, 0, 3);
    echo "<br>";
    echo substr_replace($tekst, $asendus, 7, 3);
    echo "<br>";
    $otsi = array('PHP', 'serveripoolne');
    $asendav = array('Javascript', 'kliendipoolne');
    echo str_replace($otsi, $asendav, $tekst);
    echo "</div>";

    // Peida linna nimest mõned tähed
    echo "<div class='section'>";
    echo "<h2>Mõistatus. Arva ära Eesti linnad</h2>";
    $linn = "Tallinnasaare";

    echo "<ol>";
    echo "<li>Linnanimi pikkus on " . strlen($linn) . " tähte</li>";
    echo "<li>Esimene täht on " . $linn[0] . "</li>";
    echo "<li>Esimesed 5 tähte on " . substr($linn, 0, 5) . "</li>";
    echo "<li>Kui asendame lõpu, jääb alles: " . str_replace("saare", "*****", $linn) . "</li>";
    echo "<li>Linn suurelt kirjutades: <strong>" . strtoupper($linn) . "</strong></li>";
    echo "</ol>";
    ?>

    <form onsubmit="return postForm(event, 'content/post5/index.php')">
        <label for="linn">Sisesta linn:</label>
        <input type="text" id="linn" name="linn">
        <input type="submit" value="Kontrolli">
    </form>

    <?php
    if (isset($_REQUEST['linn'])) {
        if (strtolower($_REQUEST['linn']) == strtolower($linn)) {
            echo "<p style='color:green; font-weight:bold;'>Õige vastus! Linn on " . $linn . ".</p>";
        } else {
            echo "<p style='color:red; font-weight:bold;'>Vale vastus! Proovi uuesti.</p>";
        }
    }
    echo "</div>";
    ?>
</div>

<script>
    function postForm(e, url) {
        e.preventDefault();
        
        const formData = new FormData(e.target);

        fetch(url, {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById("content-area").innerHTML = html;
        });
    }
</script>