function drawOcean(){
    axios.get(BASE_URL + "ocean_positions.php")
        .then( res => renderOcean(res.data.data))
        .catch( err => console.log("Failed to load ocean: " + err.message));
}

function renderOcean(bottles){
    const canvas = document.getElementById("oceanCanvas");
    const ctx = canvas.getContext("2d");

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    for(let i = 0; i < bottles.length; i++){
        const pixelX = (bottles[i].x / 100) * canvas.width;
        const pixelY = (bottles[i].y / 100) * canvas.height;

        ctx.beginPath();
        ctx.arc(pixelX, pixelY, 4, 0, 2 * Math.PI);
        ctx.fillStyle = "#c9a24b";
        ctx.fill();
    }
}

drawOcean();
setInterval(drawOcean, 2000);