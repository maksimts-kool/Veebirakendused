<div class="content-card">
    <h1>Mobiilimalli konspekt</h1>

    <section>
        <h2>1. Sissejuhatus</h2>
        <p>
            Minu ülesanne oli luua mobiilisõbralik veebileht teemal <strong>anekdoodid</strong>.
            Leht pidi kuvama ühes failis päise, sisu ja jaluse ning lugema andmeid eraldi tekstifailidest
            (<em>teade.txt</em> ja <em>tegija.txt</em>). Samuti pidin looma mitme anekdoodi jaoks eraldi sisulehed ning ühendama need ühiseks lahenduseks.
        </p>
        <p>
            Kasutasin järgmiseid tehnoloogiaid:
        </p>
        <ul>
            <li><strong>HTML</strong> – põhistruktuuri loomiseks</li>
            <li><strong>CSS</strong> – kujunduseks ja responsive disaini saavutamiseks</li>
            <li><strong>PHP</strong> – serveripoolseks failisisuga töötamiseks ja komponentide ühendamiseks</li>
        </ul>
    </section>

    <section>
        <h2>2. Koodilõigud ja selgitused</h2>

        <h3>a) Päise lisamine ja JavaScripti abil sisu dünaamiline laadimine</h3>
        <p>
            See lõik asub failis <code>päis.php</code>. 
            See lisab lehe päise ja navigeerimismenüü, mille lingid kutsuvad välja funktsiooni <code>postForm()</code> – 
            see laeb uue sisu fetch() abil samasse leheossa ilma uut vahelehte avamata.
        </p>
        <pre>
function postForm(e, url) {
    e.preventDefault();
    fetch(url)
        .then(r => r.text())
        .then(html => {
            document.getElementById("content-area").innerHTML = html;
        })
        .catch(err => console.error("Viga sisu laadimisel: ", err));
}
        </pre>
        <p>
            See kood võimaldab navigeerida anekdootide vahel, ilma uut lehte avamata – see muudab kasutuskogemuse sujuvaks ka mobiilivaates.
        </p>

        <h3>b) Faili sisu kuvamine PHP abil</h3>
        <p>
            Failis <code>index.php</code> kasutasin järgmist koodilõiku teate lugemiseks <em>teade.txt</em> failist:
        </p>
        <pre>
<?php
$teade = file_get_contents("../anekdoot/teade.txt");
echo "<section><p>" . htmlspecialchars($teade) . "</p></section>";
?>
        </pre>
        <p>
            Selle abil kuvatakse teade otse failist, nii et sisu saab muuta faili kaudu ilma lehe koodi redigeerimata.
        </p>

        <h3>c) Grid-kujundus mitme anekdoodi jaoks</h3>
        <p>
            Anekdootide näitamiseks kasutasin CSS grid süsteemi, mis võimaldab neid kuvada kõrvuti, kui ekraan on lai.
        </p>
        <pre>
.anekdoodid-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  justify-items: center;
}

.anekdoot {
  background: #ffffff;
  border-radius: 10px;
  padding: 25px;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
}
        </pre>
        <p>
            Selle tulemusel moodustub anekdootidest ilus võrgustik ehk <em>grid layout</em>. 
            Väiksematel ekraanidel kohandub iga anekdoot automaatselt ühe veeru paigutusse.
        </p>

        <h3>d) Mobiilivaate reeglid</h3>
        <p>
            Allolev media query tagab, et ekraani alla 600 px muutub kujundus automaatselt üheveeruliseks ja tekst sobitub väiksemale ekraanile.
        </p>
        <pre>
@media (max-width: 600px) {
  nav li {
    display: block;
    margin: 10px 0;
  }
  .anekdoodid-grid {
    grid-template-columns: 1fr;
  }
  header h1 {
    font-size: 1.8em;
  }
}
        </pre>
        <p>
            See on üks olulisemaid osi mobiilisõbralikkuse loomiseks.
            Media query võimaldab muuta elementide paigutust ja suurust vastavalt seadme ekraanilaiusele.
        </p>
        <h3>e) Anekdootide lehe põhiosa (anekdoot_nadal1.php)</h3>
    <p>
      See kood ühendab <code>päis.php</code> ja <code>jalus.php</code> ning kasutab CSS grid-paigutust, et näidata mitut
      anekdooti korraga ühes reas suurel ekraanil ja ühes veerus mobiilil.
    </p>
    <pre>
&lt;?php include("pais.php"); ?&gt;

&lt;section&gt;
  &lt;h2&gt;Anekdoodid – Nädal 1&lt;/h2&gt;
  &lt;p&gt;Siin on valik selle nädala parimaid anekdoote!&lt;/p&gt;
&lt;/section&gt;

&lt;div class="anekdoodid-grid"&gt;

  &lt;section class="anekdoot"&gt;
    &lt;h3&gt;Anekdoot 1&lt;/h3&gt;
    &lt;p&gt;Õpetaja küsib: "Juku, miks sa hilinesid?"&lt;br&gt;
    Juku vastab: "Kell oli liiga kiire!"&lt;/p&gt;
  &lt;/section&gt;

&lt;/div&gt;

&lt;?php include("jalus.php"); ?&gt;
    </pre>
    <p>
      Iga <code>&lt;section class="anekdoot"&gt;</code> plokk kujutab ühte anekdooti. Grid tagab, et need on
      kõrvuti, kuni ekraani laius muutub väiksemaks.
    </p>

    <h3>f) CSS grid ja mobiilivaade</h3>
    <pre>
.anekdoodid-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  justify-items: center;
}
@media (max-width: 600px) {
  .anekdoodid-grid { grid-template-columns: 1fr; }
}
    </pre>
    <p>
      Selle reegli tõttu muutub anekdootide paigutus automaatselt üherealiseks mobiiliekraanil (responsive layout).
    </p>
  </section>
    </section>

    <section>
        <h2>3. Mobiilivaade</h2>
        <p>
            Telefonis on sisu paigutatud ühes veerus, menüü lingid asuvad üksteise all 
            ning iga <em>anekdoot</em> kuvatakse eraldi kaardina, mis võtab kogu ekraani laiuse.
        </p>
        <p>
            Pildiloleks näha:
        </p>
        <ul>
            <li>Päis on endiselt üleval ja nähtav.</li>
            <li>Menüü lingid on üksteise all keskel.</li>
            <li>Anekdootide „kaardid“ laienevad täislaiusesse ilma tühikuta servades.</li>
            <li>Tekst kohandub väiksemale ekraanile, font on endiselt loetav.</li>
        </ul>
        <figure>
            <img src="content/mobillimalliKonspekt/{D188C027-2FFC-4253-BF9B-3536171B45AA}.png" alt="Veebilehe mobiilivaate ekraanitõmmis">
            <figcaption>Näidis: veebileht mobiilis – menüü vertikaalne ja anekdoodid all.</figcaption>
        </figure>
    </section>
    </div>