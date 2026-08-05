let adminKey = "";

function handleAdminViewResult(res){
    if(!res.data.success){
        document.getElementById("reportedContainer").textContent = res.data.message;
        return;
    }
    renderReported(res.data.data);
}

function handleAdminViewError(err){
    document.getElementById("reportedContainer").textContent = "Failed to load reported bottles: " + err.message;
}

function submitKey(){
    adminKey = document.getElementById("adminKeyInput").value;

    axios.post(BASE_URL + "admin_view.php", new URLSearchParams({ key: adminKey }))
        .then(handleAdminViewResult)
        .catch(handleAdminViewError);
}

function renderReported(bottles){
    const container = document.getElementById("reportedContainer");
    container.innerHTML = "";

    for(let i = 0; i < bottles.length; i++){
        let html = "<div class='card'>";
        html += "<p>" + bottles[i].message + "</p>";
        html += "<p>Reported " + bottles[i].reportcount + " times</p>";
        html += "<button onclick=\"removeBottle(" + bottles[i].bottleid + ")\">remove</button>";
        html += "</div>";
        container.innerHTML += html;
    }
}

function handleRemoveResult(res){
    submitKey();
}

function handleRemoveError(err){
    document.getElementById("reportedContainer").textContent = "Failed to remove bottle: " + err.message;
}

function removeBottle(bottleid){
    axios.post(BASE_URL + "admin_remove.php", new URLSearchParams({ key: adminKey, bottleid: bottleid }))
        .then(handleRemoveResult)
        .catch(handleRemoveError);
}