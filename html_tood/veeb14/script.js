var drawn = [];

function checkReady(name, needs) {
    var status = document.getElementById("status-" + name);
    var ready = true;

    for (var i = 0; i < needs.length; i++) {
        if (drawn.indexOf(needs[i]) === -1) {
            ready = false;
            break;
        }
    }

    if (drawn.indexOf(name) !== -1) {
        status.textContent = "Lisatud";
        status.className = "status ready";
    } else if (ready) {
        status.textContent = "Saab lisada";
        status.className = "status ready";
    } else {
        status.textContent = "Pole valmis";
        status.className = "status locked";
    }
}

function updateAllStatus() {
    checkReady("vaip", []);
    checkReady("kolmnurk", ["vaip"]);
    checkReady("joulukuulid", ["kolmnurk"]);
    checkReady("kingid", ["vaip", "kolmnurk"]);
    checkReady("aken", []);
    checkReady("tool", ["kingid"]);
    checkReady("kamin", []);
    checkReady("sokk", ["kamin"]);
}

function canAdd(name, needs) {
    if (drawn.indexOf(name) !== -1) {
        return false;
    }

    for (var i = 0; i < needs.length; i++) {
        if (drawn.indexOf(needs[i]) === -1) {
            return false;
        }
    }

    drawn.push(name);
    updateAllStatus();
    return true;
}

function puhasta() {
    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawn = [];
    updateAllStatus();
}

function kolmnurk() {
    if (!canAdd("kolmnurk", ["vaip"])) return;

    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");

    ctx.beginPath();
    ctx.fillStyle = "#8b4513ff";
    ctx.strokeStyle = "#654321ff";
    ctx.rect(135, 60, 30, 190);
    ctx.closePath();
    ctx.stroke();
    ctx.fill();

    ctx.strokeStyle = "#009a21ff";
    ctx.fillStyle = "#00ff00d8";
    ctx.lineWidth = 2;

    ctx.beginPath();
    ctx.moveTo(150, 30);
    ctx.lineTo(100, 80);
    ctx.lineTo(200, 80);
    ctx.closePath();
    ctx.stroke();
    ctx.fill();

    ctx.beginPath();
    ctx.moveTo(150, 70);
    ctx.lineTo(80, 130);
    ctx.lineTo(220, 130);
    ctx.closePath();
    ctx.stroke();
    ctx.fill();

    ctx.beginPath();
    ctx.moveTo(150, 120);
    ctx.lineTo(50, 200);
    ctx.lineTo(250, 200);
    ctx.closePath();
    ctx.stroke();
    ctx.fill();
}

function joulukuulid() {
    if (!canAdd("joulukuulid", ["kolmnurk"])) return;

    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");

    ctx.beginPath();
    ctx.fillStyle = "#ff0000ff";
    ctx.arc(130, 60, 10, 0, 2 * Math.PI);
    ctx.fill();

    ctx.beginPath();
    ctx.fillStyle = "#ffff00ff";
    ctx.arc(170, 70, 10, 0, 2 * Math.PI);
    ctx.fill();

    ctx.beginPath();
    ctx.fillStyle = "#ff00ffff";
    ctx.arc(150, 110, 10, 0, 2 * Math.PI);
    ctx.fill();

    ctx.beginPath();
    ctx.fillStyle = "#ff0000ff";
    ctx.arc(100, 120, 10, 0, 2 * Math.PI);
    ctx.fill();

    ctx.beginPath();
    ctx.fillStyle = "#ffff00ff";
    ctx.arc(200, 130, 10, 0, 2 * Math.PI);
    ctx.fill();

    ctx.beginPath();
    ctx.fillStyle = "#00ffffff";
    ctx.arc(150, 170, 10, 0, 2 * Math.PI);
    ctx.fill();
}

function kingid() {
    if (!canAdd("kingid", ["vaip", "kolmnurk"])) return;

    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");

    ctx.beginPath();
    ctx.fillStyle = "#ff0000d6";
    ctx.rect(70, 220, 40, 40);
    ctx.fill();

    ctx.beginPath();
    ctx.fillStyle = "#0000ffd2";
    ctx.rect(130, 220, 40, 40);
    ctx.fill();

    ctx.beginPath();
    ctx.fillStyle = "#07def6d4";
    ctx.rect(190, 220, 40, 40);
    ctx.fill();

    ctx.strokeStyle = "#ffd700ff";
    ctx.lineWidth = 3;

    ctx.beginPath();
    ctx.moveTo(90, 220);
    ctx.lineTo(90, 260);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(70, 240);
    ctx.lineTo(110, 240);
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(150, 220);
    ctx.lineTo(150, 260);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(130, 240);
    ctx.lineTo(170, 240);
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(210, 220);
    ctx.lineTo(210, 260);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(190, 240);
    ctx.lineTo(230, 240);
    ctx.stroke();
}

function vaip() {
    if (!canAdd("vaip", [])) return;

    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");

    ctx.beginPath();
    ctx.fillStyle = "#970000aa";
    ctx.save();
    ctx.scale(2.5, 1);
    ctx.arc(60, 280, 50, 0, 2 * Math.PI);
    ctx.restore();
    ctx.fill();
}

function sokk() {
    if (!canAdd("sokk", ["kamin"])) return;

    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");

    ctx.beginPath();
    ctx.fillStyle = "#ffffffff";
    ctx.strokeStyle = "#000000ff";
    ctx.lineWidth = 2;
    ctx.rect(375, 60, 25, 10);
    ctx.fill();
    ctx.stroke();

    ctx.beginPath();
    ctx.fillStyle = "#fd4d4dff";
    ctx.strokeStyle = "#000000ff";
    ctx.rect(375, 70, 25, 40);
    ctx.fill();
    ctx.stroke();

    ctx.beginPath();
    ctx.fillStyle = "#fd4d4dff";
    ctx.strokeStyle = "#000000ff";
    ctx.rect(365, 110, 35, 20);
    ctx.fill();
    ctx.stroke();
}

function kamin() {
    if (!canAdd("kamin", [])) return;

    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");

    ctx.beginPath();
    ctx.fillStyle = "#a0512dff";
    ctx.strokeStyle = "#654321ff";
    ctx.rect(375, 0, 60, 400);
    ctx.fill();
    ctx.stroke();

    ctx.beginPath();
    ctx.fillStyle = "#a0512dff";
    ctx.strokeStyle = "#654321ff";
    ctx.rect(344, 175, 120, 150);
    ctx.fill();
    ctx.stroke();

    ctx.beginPath();
    ctx.fillStyle = "#000000aa";
    ctx.rect(360, 195, 90, 100);
    ctx.fill();

    ctx.beginPath();
    ctx.fillStyle = "#ffaa00aa";
    ctx.moveTo(375, 240);
    ctx.lineTo(370, 285);
    ctx.lineTo(380, 285);
    ctx.closePath();
    ctx.fill();

    ctx.beginPath();
    ctx.fillStyle = "#ff6600aa";
    ctx.moveTo(395, 240);
    ctx.lineTo(390, 285);
    ctx.lineTo(400, 285);
    ctx.closePath();
    ctx.fill();
}

function tool() {
    if (!canAdd("tool", ["kingid"])) return;

    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");

    ctx.beginPath();
    ctx.fillStyle = "#8b4513ff";
    ctx.strokeStyle = "#654321ff";
    ctx.lineWidth = 2;
    ctx.rect(220, 250, 100, 30);
    ctx.fill();
    ctx.stroke();

    ctx.beginPath();
    ctx.fillStyle = "#A0522Daa";
    ctx.strokeStyle = "#654321ff";
    ctx.rect(220, 200, 100, 50);
    ctx.fill();
    ctx.stroke();

    ctx.beginPath();
    ctx.fillStyle = "#db9a7cd7";
    ctx.strokeStyle = "#654321ff";
    ctx.rect(210, 220, 20, 60);
    ctx.fill();
    ctx.stroke();

    ctx.beginPath();
    ctx.fillStyle = "#db9a7cd7";
    ctx.strokeStyle = "#654321ff";
    ctx.rect(310, 220, 20, 60);
    ctx.fill();
    ctx.stroke();
}

function aken() {
    if (!canAdd("aken", [])) return;

    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");

    ctx.beginPath();
    ctx.fillStyle = "#8B4513aa";
    ctx.strokeStyle = "#654321ff";
    ctx.lineWidth = 3;
    ctx.rect(240, 30, 80, 100);
    ctx.stroke();

    ctx.beginPath();
    ctx.fillStyle = "#87CEEBaa";
    ctx.rect(245, 35, 70, 90);
    ctx.fill();

    ctx.beginPath();
    ctx.strokeStyle = "#654321ff";
    ctx.lineWidth = 2;
    ctx.moveTo(280, 30);
    ctx.lineTo(280, 130);
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(240, 80);
    ctx.lineTo(320, 80);
    ctx.stroke();
}