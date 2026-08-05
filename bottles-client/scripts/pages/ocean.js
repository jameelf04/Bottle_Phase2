const canvas = document.getElementById("oceanCanvas");
const ctx = canvas.getContext("2d");

let bottles = [];
let bubbles = [];
let renderedPositions = [];

for (let i = 0; i < 40; i++) {
    bubbles.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        r: 1 + Math.random() * 2.5,
        speed: 0.2 + Math.random() * 0.6,
        drift: Math.random() * 0.6 - 0.3
    });
}

function fetchPositions() {
    axios.get(BASE_URL + "ocean_positions.php")
        .then(res => { bottles = res.data.data; })
        .catch(err => console.log("Failed to load ocean: " + err.message));
}

fetchPositions();
setInterval(fetchPositions, 5000);

function drawBackground(time) {
    const grad = ctx.createLinearGradient(0, 0, 0, canvas.height);
    grad.addColorStop(0, "#0d2a3a");
    grad.addColorStop(0.5, "#082033");
    grad.addColorStop(1, "#020a12");
    ctx.fillStyle = grad;
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    ctx.save();
    ctx.globalAlpha = 0.06;
    for (let i = 0; i < 4; i++) {
        const rayX = ((time / 4000) * canvas.width + i * 220) % (canvas.width + 200) - 100;
        const grad2 = ctx.createLinearGradient(rayX, 0, rayX + 80, canvas.height);
        grad2.addColorStop(0, "rgba(255,255,255,0)");
        grad2.addColorStop(0.5, "rgba(255,255,255,0.8)");
        grad2.addColorStop(1, "rgba(255,255,255,0)");
        ctx.fillStyle = grad2;
        ctx.beginPath();
        ctx.moveTo(rayX, 0);
        ctx.lineTo(rayX + 60, 0);
        ctx.lineTo(rayX - 40, canvas.height);
        ctx.lineTo(rayX - 100, canvas.height);
        ctx.closePath();
        ctx.fill();
    }
    ctx.restore();
}

function drawWaveLine(time) {
    ctx.save();
    ctx.strokeStyle = "rgba(45, 212, 191, 0.25)";
    ctx.lineWidth = 2;
    ctx.beginPath();
    for (let x = 0; x <= canvas.width; x += 8) {
        const y = 30 + Math.sin((x / 60) + time / 900) * 8;
        if (x === 0) ctx.moveTo(x, y);
        else ctx.lineTo(x, y);
    }
    ctx.stroke();
    ctx.restore();
}

function drawBubbles(dt) {
    ctx.save();
    for (const b of bubbles) {
        b.y -= b.speed * dt * 0.06;
        b.x += b.drift * dt * 0.02;

        if (b.y < -10) {
            b.y = canvas.height + 10;
            b.x = Math.random() * canvas.width;
        }

        ctx.beginPath();
        ctx.arc(b.x, b.y, b.r, 0, Math.PI * 2);
        ctx.strokeStyle = "rgba(255,255,255,0.35)";
        ctx.lineWidth = 1;
        ctx.stroke();
    }
    ctx.restore();
}

function drawBottles(time) {
    renderedPositions = [];

    for (let i = 0; i < bottles.length; i++) {
        const b = bottles[i];
        const pixelX = (b.x / 100) * canvas.width;
        const bob = Math.sin(time / 700 + b.bottleid) * 4;
        const pixelY = (b.y / 100) * canvas.height + bob;

        renderedPositions.push({ bottleid: b.bottleid, x: pixelX, y: pixelY });

        const glow = ctx.createRadialGradient(pixelX, pixelY, 0, pixelX, pixelY, 16);
        glow.addColorStop(0, "rgba(245, 169, 98, 0.55)");
        glow.addColorStop(1, "rgba(245, 169, 98, 0)");
        ctx.fillStyle = glow;
        ctx.beginPath();
        ctx.arc(pixelX, pixelY, 16, 0, Math.PI * 2);
        ctx.fill();

        ctx.beginPath();
        ctx.arc(pixelX, pixelY, 4, 0, Math.PI * 2);
        ctx.fillStyle = "#f5d29a";
        ctx.shadowColor = "#f5a962";
        ctx.shadowBlur = 8;
        ctx.fill();
        ctx.shadowBlur = 0;
    }
}

let lastTime = performance.now();

function animate(time) {
    const dt = time - lastTime;
    lastTime = time;

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawBackground(time);
    drawWaveLine(time);
    drawBubbles(dt);
    drawBottles(time);

    requestAnimationFrame(animate);
}

requestAnimationFrame(animate);

canvas.addEventListener("click", function(event){
    const rect = canvas.getBoundingClientRect();
    const clickX = event.clientX - rect.left;
    const clickY = event.clientY - rect.top;

    let closest = null;
    let closestDist = 12;

    for (const pos of renderedPositions){
        const dist = Math.sqrt(Math.pow(pos.x - clickX, 2) + Math.pow(pos.y - clickY, 2));
        if (dist < closestDist){
            closest = pos;
            closestDist = dist;
        }
    }

    const preview = document.getElementById("bottlePreview");

    if (closest){
        axios.get(BASE_URL + "bottle_preview.php", { params: { bottleid: closest.bottleid } })
            .then(res => {
                preview.textContent = res.data.message;
                preview.style.display = "block";
            })
            .catch(err => console.log("Failed to load preview: " + err.message));
    } else {
        preview.style.display = "none";
    }
});