let adminKey = "";

function submitKey(){
    adminKey = document.getElementById("adminKeyInput").value;

    axios.post(BASE_URL + "admin_view.php", new URLSearchParams({ key: adminKey }))
        .then( res => {
            if(!res.data.success){
                alert(res.data.message);
                return;
            }
            renderReported(res.data.data);
        })
        .catch( err => console.log("Failed to load reported bottles: " + err.message));
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

function removeBottle(bottleid){
    axios.post(BASE_URL + "admin_remove.php", new URLSearchParams({ key: adminKey, bottleid: bottleid }))
        .then( res => {
            console.log(res.data);
            submitKey();
        })
        .catch( err => console.log("Failed to remove bottle: " + err.message));
}