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