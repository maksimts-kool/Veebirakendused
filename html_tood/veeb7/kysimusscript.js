function muusikaF() {
    let v1 = document.getElementById("v1")
    let v2 = document.getElementById("v2")
    let v3 = document.getElementById("v3")
    let v4 = document.getElementById("v4")
    let v5 = document.getElementById("v5")
    let vastus = document.getElementById("vastus")

    let valik = ""
    if (v1.checked) {
        valik += v1.value + ", "
    }
    if (v2.checked) {
        valik += v2.value + ", "
    }
    if (v3.checked) {
        valik += v3.value + ", "
    }
    if (v4.checked) {
        valik += v4.value + ", "
    }
    if (v5.checked) {
        valik += v5.value + " "
    }
    vastus.innerText = "Sinu valitud muusikud: " + valik
};

function arvamusF() {
    let text = document.getElementById("arvamus").value;
    document.getElementById("arvamusVastus").innerText = "Sinu arvamus: " + text;
}

function muusikaRangeF() {
    let range = document.getElementById("muusikaRange").value;
    document.getElementById("rangeVastus").innerText = "Sa kuulad umbes " + range + " tundi päevas.";
}

function raadiokulF() {
    let jah = document.getElementById("jah")
    let ei = document.getElementById("ei")
    let vastus = ""

    if (jah.checked)
        vastus = "Jah";
    else if (ei.checked)
        vastus = "Ei"
    document.getElementById("raadioVastus").innerText = "Kas sa kuulad raadiot: " + vastus;
}

function raadiojaamF() {
    let jaam = document.getElementById("raadiojaam").value
    document.getElementById("raadiojaamVastus").innerText = "Sinu nimetatud jaamad: " + jaam;
}

function muusikakulF() {
    let pop = document.getElementById("v1p");
    let retro = document.getElementById("v2p");
    let hh = document.getElementById("v3p");
    let rock = document.getElementById("v4p");
    let rapp = document.getElementById("v5p");
    let valik = "";

    if (pop.checked)
        valik = pop.value;
    else if (retro.checked)
        valik = retro.value;
    else if (hh.checked)
        valik = hh.value;
    else if (rock.checked)
        valik = rock.value;
    else if (rapp.checked)
        valik = rapp.value;

    document.getElementById("muusikakulVastus").innerText = "Sinu vastus: " + valik;
}

function saada() {
    let muusika = document.getElementById("vastus").innerText;
    let arvamus = document.getElementById("arvamus").value;
    let tunnid = document.getElementById("muusikaRange").value;

    let radioJah = document.getElementById("jah");
    let radioEi = document.getElementById("ei");
    let raadio = "";
    if (radioJah.checked)
        raadio = "Jah";
    else if (radioEi.checked)
        raadio = "Ei";

    let jaamad = document.getElementById("raadiojaam").value;

    let pop = document.getElementById("v1p");
    let retro = document.getElementById("v2p");
    let hh = document.getElementById("v3p");
    let rock = document.getElementById("v4p");
    let rapp = document.getElementById("v5p");
    let lemmik = "";
    if (pop.checked)
        lemmik = "Pop";
    else if (retro.checked)
        lemmik = "Retro";
    else if (hh.checked)
        lemmik = "Hip-Hop";
    else if (rock.checked)
        lemmik = "Rock";
    else if (rapp.checked)
        lemmik = "Räpp";

    let text = "<h2>KOKKUVÕTE</h2>";
    text += "<p>" + muusika + "</p>";
    text += "<p>Arvamus koolimuusikast: " + (arvamus || "—") + "</p>";
    text += "<p>Kuulamise aeg päevas: " + tunnid + " tundi</p>";
    text += "<p>Kuulad raadiot: " + (raadio || "—") + "</p>";
    text += "<p>Raadiojaamad: " + (jaamad || "—") + "</p>";
    text += "<p>Lemmikmuusika: " + (lemmik || "—") + "</p>";

    document.getElementById("kokkuvote").innerHTML = text;
}

function puhasta() {
    document.getElementById("regvorm").reset();

    document.getElementById("vastus").innerText = "";
    document.getElementById("arvamusVastus").innerText = "";
    document.getElementById("rangeVastus").innerText = "";
    document.getElementById("raadioVastus").innerText = "";
    document.getElementById("raadiojaamVastus").innerText = "";
    document.getElementById("muusikakulVastus").innerText = "";
    document.getElementById("kokkuvote").innerHTML = "";
}