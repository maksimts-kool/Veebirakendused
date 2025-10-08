function nimiLugemine() {
    let nimi = document.getElementById("nimi")
    let v1 = document.getElementById("vastus1")

    v1.innerText = "Tere õpilane, " + nimi.value
}

function puhasta() {
    let v1 = document.getElementById("vastus1")
    let nimi = document.getElementById("nimi")
    let v2 = document.getElementById("vastus2")
    let v3 = document.getElementById("vastus3")
    let v4 = document.getElementById("vastus4")
    let v5 = document.getElementById("vastus5")

    v2.innerText = ""
    nimi.value = ""
    v1.innerText = ""
    v3.innerText = ""
    v4.innerText = ""
    v5.innerText = ""
    mees.checked = false
    naine.checked = false
    laps.checked = false
    slider.value = 0
    varv.value = "#000000"
    koht2.selectedIndex = 0
    java.checked = false
    py.checked = false
    c.checked = false
    js.checked = false
    cpp.checked = false
}

function meesjanaine() {
    let v2 = document.getElementById("vastus2")
    let mees = document.getElementById("mees")
    let naine = document.getElementById("naine")
    let laps = document.getElementById("laps")
    let pilt = document.getElementById("pilt")
    if (mees.checked) {
        v2.innerHTML = mees.value
        pilt.src = "images/Hea.png"
    } else if (naine.checked) {
        v2.innerHTML = naine.value
        pilt.src = "images/Kuri.png"
    }
    else if (laps.checked) {
        v2.innerHTML = laps.value
        pilt.src = "images/Normal.png"
    } else {
        v2.innerHTML = "Palun valige sugu!"

    }
}

function varvida() {
    let v1 = document.getElementById("vastus1")
    let v2 = document.getElementById("vastus2")
    let v3 = document.getElementById("vastus3")
    let varv = document.getElementById("varv")
    v1.style.backgroundColor = varv.value
    v2.style.color = varv.value
    v3.style.color = varv.value
}

function sliderLiigub() {
    let v3 = document.getElementById("vastus3")
    let slider = document.getElementById("slider")

    v3.innerText = slider.value + " punkti"
}

function koht() {
    let v4 = document.getElementById("vastus4")
    let koht1 = document.getElementById("koht2")
    if (koht1.selectedIndex !== 0) {
        v4.innerText = "Teie valitud koht on: " + koht1.value
    } else {
        v4.innerText = "Palun valige koht!"
    }
}

function valikeel() {
    let v5 = document.getElementById("vastus5")
    let java = document.getElementById("java")
    let py = document.getElementById("py")
    let c = document.getElementById("c")
    let js = document.getElementById("js")
    let cpp = document.getElementById("cpp")
    let pilt2 = document.getElementById("pilt2")

    let valik = ""
    if (java.checked) {
        valik += java.value + ", "
        pilt2.src = "images/Hea.png"
    }
    if (js.checked) {
        valik += js.value + ", "
        pilt2.src = "images/Hea.png"
    }
    if (c.checked) {
        valik += c.value + ", "
        pilt2.src = "images/Kuri.png"
    }
    if (py.checked) {
        valik += py.value + ", "
        pilt2.src = "images/Kuri.png"
    }
    if (cpp.checked) {
        valik += cpp.value + " "
        pilt2.src = "images/Normal.png"
    }
    v5.innerText = "Te oskate: " + valik

}