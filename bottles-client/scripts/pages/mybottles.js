function handleBottlesResult(res){
    renderBottles(res.data.data);
}

function handleBottlesError(err){
    document.getElementById("bottlesContainer").textContent = "Failed to load bottles: " + err.message;
}

function handleShelfResult(res){
    renderShelf(res.data.data);
}

function handleShelfError(err){
    document.getElementById("shelfContainer").textContent = "Failed to load shelf: " + err.message;
}

axios.get(BASE_URL + "mybottles.php", { params: { token: localStorage.getItem("token") } })
    .then(handleBottlesResult)
    .catch(handleBottlesError);

axios.get(BASE_URL + "keeps.php", { params: { token: localStorage.getItem("token") } })
    .then(handleShelfResult)
    .catch(handleShelfError);

function renderBottles(bottles){
    const container = document.getElementById("bottlesContainer");
    container.innerHTML = "";

    for(let i = 0; i < bottles.length; i++){
        let html = "<div class='card'>";
        html += "<p>" + bottles[i].message + "</p>";
        html += "<p>Held " + bottles[i].holdcount + " times</p>";

        for(let j = 0; j < bottles[i].marks.length; j++){
            html += "<p>" + bottles[i].marks[j] + "</p>";
        }

        html += "<button onclick=\"keepBottle(" + bottles[i].bottleid + ")\">Keep Forever</button>";
        html += "</div>";
        container.innerHTML += html;
    }
}

function handleKeepResult(res){
    document.getElementById("shelfContainer").textContent = res.data.message;
    location.reload();
}

function handleKeepError(err){
    document.getElementById("bottlesContainer").textContent = "Failed to keep bottle: " + err.message;
}

function keepBottle(bottleid){
    const token = localStorage.getItem("token");

    axios.post(BASE_URL + "keep.php", new URLSearchParams({ bottleid: bottleid, token: token }))
        .then(handleKeepResult)
        .catch(handleKeepError);
}

function renderShelf(bottles){
    const container = document.getElementById("shelfContainer");
    container.innerHTML = "";

    if(bottles.length === 0){
        container.innerHTML = "<p>You haven't kept a bottle yet.</p>";
        return;
    }

    for(let i = 0; i < bottles.length; i++){
        let html = "<div class='card'>";
        html += "<p>" + bottles[i].message + "</p>";
        html += "</div>";
        container.innerHTML += html;
    }
}