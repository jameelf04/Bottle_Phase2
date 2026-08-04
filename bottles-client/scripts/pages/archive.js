axios.get(BASE_URL + "archive.php")
    .then( res => renderArchive(res.data.data))
    .catch( err => console.log("Failed to load archive: " + err.message));

function renderArchive(bottles){
    const container = document.getElementById("archiveContainer");
    container.innerHTML = "";

    if(bottles.length === 0){
        container.innerHTML = "<p>No bottles have retired to the archive yet.</p>";
        return;
    }

    for(let i = 0; i < bottles.length; i++){
        const ageDays = bottles[i].age_seconds / 86400;
        const opacity = Math.max(0.4, 1 - (ageDays / 60));

        let html = "<div class='card' style='opacity:" + opacity + "'>";
        html += "<p>" + bottles[i].message + "</p>";
        html += "<p>" + bottles[i].markcount + " marks collected</p>";

        for(let j = 0; j < bottles[i].marks.length; j++){
            html += "<p>" + bottles[i].marks[j] + "</p>";
        }

        html += "</div>";
        container.innerHTML += html;
    }
}