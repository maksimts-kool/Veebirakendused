function naitaKuupaevaJaKellaega() {
    const now = new Date();

    const kuupaev = now.toLocaleDateString();
    const kellaaeg = now.toLocaleTimeString();
    const kokku = now.toLocaleString();

    document.getElementById("kuupaev").innerText = "Kuupäev: " + kuupaev;
    document.getElementById("kellaaeg").innerText = "Kellaaeg: " + kellaaeg;
    document.getElementById("kokku").innerText = "Kuupäev ja kellaaeg: " + kokku;

    console.log("Kuupäev:" + kuupaev);
    console.log("Kellaaeg:" + kellaaeg);
    console.log("Kuupäev ja kellaaeg:" + kokku);
}

function arvutaSynnipaevani() {
    const tana = new Date();
    const synniP = new Date(tana.getFullYear(), 6, 15);

    if (synniP < tana) {
        synniP.setFullYear(tana.getFullYear() + 1);
    }

    const vaheMs = synniP - tana;
    const paevad = Math.ceil(vaheMs / (1000 * 60 * 60 * 24));

    document.getElementById("vahe").innerText = "Minu sünnipäevani on jäänud: " + paevad + " päeva.";
    document.getElementById("teade").innerText = "Minu sünnipäeva kuupäev on: 15.06"

    console.log("Minu sünnipäevani on:" + paevad + "päeva");
}