var drawn = [];
var snowflakes = [];
var animationIndex = 0;
var animationInterval = null;

function checkAndAdd(name, needs) {
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
        return false;
    } else if (ready) {
        status.textContent = "Saab lisada";
        status.className = "status ready";
        drawn.push(name);
        updateAllStatus();
        return true;
    } else {
        status.textContent = "Pole valmis";
        status.className = "status locked";
        return false;
    }
}

function updateAllStatus() {
    checkStatus("vaip", []);
    checkStatus("kolmnurk", ["vaip"]);
    checkStatus("joulukuulid", ["kolmnurk"]);
    checkStatus("kingid", ["vaip", "kolmnurk"]);
    checkStatus("aken", []);
    checkStatus("tool", ["kingid"]);
    checkStatus("kamin", []);
    checkStatus("sokk", ["kamin"]);
    checkStatus("lumi", []);
}

function checkStatus(name, needs) {
    var status = document.getElementById("status-" + name);
    var ready = true;

    for (var i = 0; i < needs.length; i++) {
        if (drawn.indexOf(needs[i]) === -1) { // not found
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

function startAnimation() {
    if (animationInterval) clearInterval(animationInterval);

    animationInterval = setInterval(function () {
        animationIndex = (animationIndex + 1) % 5;
        redrawAll();
        drawAnimations();
    }, 500);
}

function drawAnimations() {
    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");
    var colors = ["#ff0000ff", "#ffff00ff", "#0080ffff", "#ff00ffff", "#00ffffff"];

    if (drawn.indexOf("joulukuulid") !== -1) { // found
        ctx.beginPath();
        ctx.fillStyle = colors[animationIndex];
        ctx.arc(130, 60, 10, 0, 2 * Math.PI);
        ctx.fill();

        ctx.beginPath();
        ctx.fillStyle = colors[(animationIndex + 1) % colors.length];
        ctx.arc(170, 70, 10, 0, 2 * Math.PI);
        ctx.fill();

        ctx.beginPath();
        ctx.fillStyle = colors[(animationIndex + 2) % colors.length];
        ctx.arc(150, 110, 10, 0, 2 * Math.PI);
        ctx.fill();

        ctx.beginPath();
        ctx.fillStyle = colors[(animationIndex + 3) % colors.length];
        ctx.arc(100, 120, 10, 0, 2 * Math.PI);
        ctx.fill();

        ctx.beginPath();
        ctx.fillStyle = colors[(animationIndex + 4) % colors.length];
        ctx.arc(200, 130, 10, 0, 2 * Math.PI);
        ctx.fill();

        ctx.beginPath();
        ctx.fillStyle = colors[animationIndex];
        ctx.arc(150, 170, 10, 0, 2 * Math.PI);
        ctx.fill();
    }

    if (drawn.indexOf("lumi") !== -1) {
        ctx.fillStyle = "#ffffffff";

        for (var i = 0; i < snowflakes.length; i++) {
            ctx.beginPath();
            ctx.arc(snowflakes[i].x, snowflakes[i].y, snowflakes[i].size, 0, 2 * Math.PI);
            ctx.fill();

            snowflakes[i].y += snowflakes[i].speed;

            if (snowflakes[i].y > canvas.height) {
                snowflakes[i].y = 0;
                snowflakes[i].x = Math.random() * canvas.width;
            }
        }
    }
}

function redrawAll() {
    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    if (drawn.indexOf("vaip") !== -1) {
        drawVaip(ctx, canvas);
    }
    if (drawn.indexOf("kolmnurk") !== -1) {
        drawKolmnurk(ctx, canvas);
    }
    if (drawn.indexOf("kingid") !== -1) {
        drawKingid(ctx, canvas);
    }
    if (drawn.indexOf("aken") !== -1) {
        drawAken(ctx, canvas);
    }
    if (drawn.indexOf("tool") !== -1) {
        drawTool(ctx, canvas);
    }
    if (drawn.indexOf("kamin") !== -1) {
        drawKamin(ctx, canvas);
    }
    if (drawn.indexOf("sokk") !== -1) {
        drawSokk(ctx, canvas);
    }
}

function drawVaip(ctx, canvas) {
    ctx.beginPath();
    ctx.fillStyle = "#970000aa";
    ctx.save();
    ctx.scale(2.5, 1);
    ctx.arc(60, 280, 50, 0, 2 * Math.PI);
    ctx.restore();
    ctx.fill();
}

function drawKolmnurk(ctx, canvas) {
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

function drawKingid(ctx, canvas) {
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

function drawAken(ctx, canvas) {
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

function drawTool(ctx, canvas) {
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

function drawKamin(ctx, canvas) {
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

function drawSokk(ctx, canvas) {
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

function puhasta() {
    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawn = [];
    snowflakes = [];
    animationIndex = 0;
    clearInterval(animationInterval);
    animationInterval = null;
    updateAllStatus();
}

function lumi() {
    if (!checkAndAdd("lumi", [])) return;

    var canvas = document.getElementById("myCanvas");
    snowflakes = [];
    for (var i = 0; i < 50; i++) {
        snowflakes.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            size: Math.random() * 3 + 2,
            speed: Math.random() * 1 + 2
        });
    }

    startAnimation();
}

function joulukuulid() {
    if (!checkAndAdd("joulukuulid", ["kolmnurk"])) return;
    startAnimation();
}

function kingid() {
    if (!checkAndAdd("kingid", ["vaip", "kolmnurk"])) return;
    redrawAll();
}

function vaip() {
    if (!checkAndAdd("vaip", [])) return;
    redrawAll();
}

function sokk() {
    if (!checkAndAdd("sokk", ["kamin"])) return;
    redrawAll();
}

function kamin() {
    if (!checkAndAdd("kamin", [])) return;
    redrawAll();
}

function tool() {
    if (!checkAndAdd("tool", ["kingid"])) return;
    redrawAll();
}

function aken() {
    if (!checkAndAdd("aken", [])) return;
    redrawAll();
}

function kolmnurk() {
    if (!checkAndAdd("kolmnurk", ["vaip"])) return;
    redrawAll();
}