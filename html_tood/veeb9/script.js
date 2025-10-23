const hind1 = 5.00;
const hind2 = 3.50;
const hind3 = 1.20;
const hind4 = 4.20;
const hind5 = 2.50;
const tarneHindKuller = 3.00;

function arvutaSumma(hind, kogus) {
    return (kogus * hind);
}

function muudaPiltiJaArvuta() {
    let radio = document.getElementsByName("valikud");
    let valitudPiltSrc = null;

    for (let i = 0; i < radio.length; i++) {
        if (radio[i].checked) {
            valitudPiltSrc = radio[i].value;
            break;
        }
    }
    document.getElementById("pilt").src = valitudPiltSrc;
    arvutaKogusJaTarne();
}

function arvutaKogusJaTarne() {
    let summaElement = document.getElementById("summa");
    let kogus = document.getElementById("kogus").value;
    let source = document.getElementById("pilt").getAttribute("src");
    let hind = 0;

    if (source === "pildid/1.png") {
        hind = hind1;
    } else if (source === "pildid/2.png") {
        hind = hind2;
    } else if (source === "pildid/3.png") {
        hind = hind3;
    } else if (source === "pildid/4.png") {
        hind = hind4;
    } else if (source === "pildid/5.png") {
        hind = hind5;
    }

    let tooteSumma = arvutaSumma(hind, kogus);
    let koguSumma = tooteSumma;

    const tarneValikud = document.getElementsByName("tarne");
    let valitudTarneviis = "kohapeal";
    for (let i = 0; i < tarneValikud.length; i++) {
        if (tarneValikud[i].checked) {
            valitudTarneviis = tarneValikud[i].value;
            break;
        }
    }

    if (valitudTarneviis === "kuller") {
        koguSumma += tarneHindKuller;
    }

    summaElement.innerHTML = koguSumma.toFixed(2) + " EUR";
}

function algneSeadistus() {
    let tarne = document.getElementsByName("tarne");
    tarne[0].checked = true;

    let valikud = document.getElementsByName("valikud");
    valikud[0].checked = true;
    document.getElementById("pilt").src = valikud[0].value;

    let kogus = document.getElementsByName("kogus");
    kogus[0].value = 1

    arvutaKogusJaTarne();
}