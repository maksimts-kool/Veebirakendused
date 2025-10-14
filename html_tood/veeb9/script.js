function juhuslikPilt() {
    const pildid = [
        "1.png",
        "2.png",
        "3.png",
        "4.png",
        "5.png"
    ]
    const juhuslikIndeks = Math.floor(Math.random() * pildid.length);
    const valitudPilt = pildid[juhuslikIndeks];
    document.getElementById("pilt").src = "pildid/" + valitudPilt;
    arvutaPildiHind();
}

function teeOmaValiku() {
    const radio = document.getElementsByName("valikud");
    let valik = null;
    for (let i = 0; i < radio.length; i++) {
        if (radio[i].checked) {
            valik = radio[i];
            break;
        }
    }
    const pilt = document.getElementById("pilt");

    const vastus = document.getElementById("vastus");
    if (pilt.getAttribute("src") == valik.value) {
        vastus.textContent = "Sinu vastus on õige!";
        vastus.style.color = "green";
    } else {
        vastus.textContent = "Mõtle veel, sa vastasid valesti!";
        vastus.style.color = "red";
    }
    arvutaPildiHind();
}

function arvutaSumma(hind, kogus) {
    return (kogus * hind).toFixed(2);
}

const hind1 = 5.00;
const hind2 = 3.50;
const hind3 = 1.20;
const hind4 = 4.20;
const hind5 = 2.50;
function arvutaPildiHind() {
    let summa = document.getElementById("summa");
    let kogus = document.getElementById("kogus").value;
    let source = document.getElementById("pilt").getAttribute("src");
    if (source == "pildid/1.png") {
        summa.innerHTML = arvutaSumma(hind1, kogus) + " EUR";
    } else if (source == "pildid/2.png") {
        summa.innerHTML = arvutaSumma(hind2, kogus) + " EUR";
    } else if (source == "pildid/3.png") {
        summa.innerHTML = arvutaSumma(hind3, kogus) + " EUR";
    } else if (source == "pildid/4.png") {
        summa.innerHTML = arvutaSumma(hind4, kogus) + " EUR";
    } else if (source == "pildid/5.png") {
        summa.innerHTML = arvutaSumma(hind5, kogus) + " EUR";
    } else {
        summa.innerHTML = "0.00 EUR";
    }
}