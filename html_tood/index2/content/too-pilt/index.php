<div class="content-card">
    <h1>Töö pildifailidega</h1>
    
    <?php
    $kaust_systeemis = 'pildid/'; 
    $kaust_veebis = 'content/too-pilt/pildid/';
    
    if (is_dir($kaust_systeemis)) {
        $failid = array_diff(scandir($kaust_systeemis), array('.', '..'));
        
        $pildid = array_filter($failid, function($fail) use ($kaust_systeemis) {
            return is_file($kaust_systeemis . $fail) && @getimagesize($kaust_systeemis . $fail);
        });
        $pildid = array_values($pildid);
    } else {
        echo "<p style='color:red'>Viga: Kausta 'pildid' ei leitud faili kõrvalt!</p>";
        $pildid = [];
    }
    ?>

    <div class="section">
        <h2>Pildi parameetrid</h2>
        <form onsubmit="return postForm(event, 'content/too-pilt/index.php')">
            <select name="valitud_pilt">
                <option value="">Vali pilt</option>
                <?php 
                foreach($pildid as $pilt){
                    $selected = (isset($_REQUEST['valitud_pilt']) && $_REQUEST['valitud_pilt'] == $pilt) ? 'selected' : '';
                    echo "<option value='$pilt' $selected>$pilt</option>";
                }
                ?>
            </select>
            <input type="submit" value="Vaata andmeid">
        </form>

        <?php
        if(!empty($_REQUEST['valitud_pilt'])){
            $valitud_fail = $_REQUEST['valitud_pilt'];
            $pildi_aadress_sys = $kaust_systeemis . $valitud_fail;
            $pildi_aadress_web = $kaust_veebis . $valitud_fail;
            
            if(file_exists($pildi_aadress_sys)){
                $pildi_andmed = getimagesize($pildi_aadress_sys);
                
                $laius = $pildi_andmed[0];
                $korgus = $pildi_andmed[1];
                $formaat = $pildi_andmed[2];

                $formaat_nimi = match($formaat) {
            1 => "GIF",
            2 => "JPEG",
            3 => "PNG",
            default => "Tundmatu",
        };
                
                $max_laius = 120;
                $max_korgus = 90;
                
                if($laius <= $max_laius && $korgus <= $max_korgus){
                    $ratio = 1; 
                } elseif($laius > $korgus){
                    $ratio = $max_laius / $laius; 
                } else {
                    $ratio = $max_korgus / $korgus; 
                }
                
                $pisi_laius = round($laius * $ratio);
                $pisi_korgus = round($korgus * $ratio);
                
                echo "<div class='inner-section'>";
                echo "<h3>Pilt: $valitud_fail</h3>";
                echo "<p>Originaal: $laius x $korgus px</p>";
                echo "<p>Formaat: $formaat_nimi</p>";
                echo "<p>Arvutamise suhe: {$ratio}</p>";
                echo "<p>Uus suurus: $pisi_laius x $pisi_korgus px</p>";
                
                echo "<p>Eelvaade:</p>";
                echo "<img src='$pildi_aadress_web' width='$pisi_laius' height='$pisi_korgus' style='border:1px solid #ccc'>";
                echo "</div>";
            }
        }
        ?>
    </div>

    <div class="section">
        <h2>Suvaline pilt</h2>
        <?php
        if (!empty($pildid)) {
            $suvaline_voti = array_rand($pildid);
            $suvaline_pilt = $pildid[$suvaline_voti];
            
            $suvaline_src = $kaust_veebis . $suvaline_pilt;
            
            echo "<p>Fail: <strong>$suvaline_pilt</strong></p>";
            echo "<img src='$suvaline_src' style='max-width: 200px; border-radius: 8px;'>";
        } else {
            echo "<p>Pilte ei ole.</p>";
        }
        ?>
    </div>
</div>

<script>
    function postForm(e, url) {
        e.preventDefault();
        const formData = new FormData(e.target);
        fetch(url, { method: "POST", body: formData })
        .then(response => response.text())
        .then(html => {
            document.getElementById("content-area").innerHTML = html;
        });
    }
</script>