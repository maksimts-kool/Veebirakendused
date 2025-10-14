function juhuslikPilt() {
    const pildid = [
        "1.png",
        "2.png",
        "3.png",
        "4.png"
    ]
    const juhuslikIndeks = Math.floor(Math.random() * pildid.length);
    const valitudPilt = pildid[juhuslikIndeks];
    document.getElementById("pilt").src = "pildid/" + valitudPilt;
}

function valiPilt() {
    const valik = document.getElementById("valikud").value;
    document.getElementById("pilt").src = "pildid/" + valik;
}

function teeOmaValiku() {
    var valik = document.getElementById("valikud").value;
    var piltNimi = document.getElementById("pilt").src.split("/").pop();
    var vastus = document.getElementById("vastus");
    if (valik === piltNimi) {
        vastus.innerHTML = "Sinu vastus on õige!";
        vastus.style.color = "green";
    } else {
        vastus.innerHTML = "Mõtle veel, sa vastasid valesti!";
        vastus.style.color = "red";
    }
}

function arvutaSumma(hind, kogus) {
    return (kogus * hind).toFixed(2);
}

const hind1 = 5.00;
const hind2 = 3.50;
const hind3 = 0.00;
const hind4 = 4.20;
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
    } else {
        summa.innerHTML = "0.00 EUR";
    }
}

