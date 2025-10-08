function naitaKuupaeva() {
    const now = new Date();
    const kuupaev = now.toLocaleDateString("et-EE");
    document.getElementById("valjund").innerText = "Kuupäev: " + kuupaev;
    document.getElementById("valjund2").innerText = "";
    console.log("Kuupäev:", kuupaev);
}

function naitaKellaaega() {
    const now = new Date();
    const kellaaeg = now.toLocaleTimeString("et-EE");
    document.getElementById("valjund").innerText = "Kellaaeg: " + kellaaeg;
    document.getElementById("valjund2").innerText = "";
    console.log("Kellaaeg:", kellaaeg);
}

function naitaKoos() {
    const now = new Date();
    const koos1 = now.toLocaleDateString("et-EE");
    const koos2 = now.toLocaleTimeString("et-EE");
    document.getElementById("valjund").innerText = "Kuupäev: " + koos1;
    document.getElementById("valjund2").innerText = "Kellaaeg: " + koos2;
    console.log("Kuupäev:", koos1);
    console.log("Kellaaeg:", koos2);
}

function minuSynnipaevani() {
    const synnip2evInput = document.getElementById("synnip2ev").value;

    if (!synnip2evInput) {
        document.getElementById("synnipaevavaljund").innerText =
            "Palun sisesta oma sünnipäev.";
        return;
    }

    const now = new Date();
    const sisestatud = new Date(synnip2evInput);
    let synnipaev = new Date(
        now.getFullYear(),
        sisestatud.getMonth(),
        sisestatud.getDate()
    );

    if (synnipaev < now) {
        synnipaev = new Date(
            now.getFullYear() + 1,
            sisestatud.getMonth(),
            sisestatud.getDate()
        );
    }

    const vaheMillis = synnipaev - now;
    const paevad = Math.ceil(vaheMillis / (1000 * 60 * 60 * 24));

    document.getElementById("synnipaevavaljund").innerText =
        "Sünnipäevani on jäänud " + paevad + " päeva!";
    console.log("Sünnipäevani on jäänud:", paevad, "päeva");
}