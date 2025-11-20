<div class="content-card">
    
<body onload="algneSeadistus()">
    
    <h1>Poodi veebikalkulaator</h1>
    <table>
        <tr>
            <td>
                <label><input type="radio" name="valikud" value="content/post1/1.png" onchange="muudaPiltiJaArvuta()"
                        checked>Sai</label>
                <label><input type="radio" name="valikud" value="content/post1/2.png"
                        onchange="muudaPiltiJaArvuta()">Leib</label>
                <label><input type="radio" name="valikud" value="content/post1/3.png"
                        onchange="muudaPiltiJaArvuta()">Muffin</label>
                <label><input type="radio" name="valikud" value="content/post1/4.png"
                        onchange="muudaPiltiJaArvuta()">Tee</label>
                <label><input type="radio" name="valikud" value="content/post1/5.png"
                        onchange="muudaPiltiJaArvuta()">Kook</label>
            </td>
            <td>
                <div id="valitudtekst">Valitud pilt:</div>
            </td>
            <td>
                <img src="content/post1/1.png" alt="" id="pilt">
            </td>
        </tr>
        <tr>
            <td>
                <label for="kogus">Mitu tk soovid osta?</label>
                <input type="number" id="kogus" name="kogus" min="1" max="100" value="1"
                    onchange="arvutaKogusJaTarne()">
            </td>
            <td id="summatekst">Summa:</td>
            <td id="summa"></td>
        </tr>
        <tr>
            <td colspan="3">
                <p>Vali tarneviis:</p>
                <label><input type="radio" name="tarne" value="kohapeal" onchange="arvutaKogusJaTarne()"
                        checked>Kaupluses (+0.00 EUR)</label>
                <label><input type="radio" name="tarne" value="kuller" onchange="arvutaKogusJaTarne()">Kuller
                    (+3.00 EUR)</label>
            </td>
        </tr>
    </table>
</body>
</div>

<script src="script.js"></script>
<link rel="stylesheet" href="style.css">