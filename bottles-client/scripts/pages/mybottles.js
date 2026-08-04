const token = localStorage.getItem("token");

axios.get(BASE_URL + "mybottles.php", { params: { token: token } })
    .then( res => renderBottles(res.data.data))
    .catch( err => console.log("Failed to load bottles: " + err.message));

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

        html += "</div>";
        container.innerHTML += html;
    }
}

const shelfToken = localStorage.getItem("token");

axios.get(BASE_URL + "keeps.php", { params: { token: shelfToken } })
    .then( res => renderShelf(res.data.data))
    .catch( err => console.log("Failed to load shelf: " + err.message));

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