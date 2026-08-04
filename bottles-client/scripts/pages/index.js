let currentBottleId = null;

function initUser(){
    const savedToken = localStorage.getItem("token") || "";

    axios.get(BASE_URL + "create_user.php", { params: { token: savedToken } })
        .then( res => {
            localStorage.setItem("token", res.data.token);
            console.log("User ready:", res.data);
        })
        .catch( err => console.log("Failed to load user: " + err.message));
}

initUser();

axios.get(BASE_URL + "condition.php")
    .then( res => document.getElementById("oceanCondition").textContent = "Today's ocean: " + res.data.condition)
    .catch( err => console.log("Failed to load condition: " + err.message));

function throwBottle(){
    const messageText = document.getElementById("throwText").value;
    const token = localStorage.getItem("token");

    axios.post(BASE_URL + "throw.php", new URLSearchParams({ message: messageText, token: token }))
        .then( res => document.getElementById("throwStatus").textContent = res.data.message)
        .catch( err => console.log("Failed to throw: " + err.message));
}

function drawBottle(){
    const token = localStorage.getItem("token");
    const resultEl = document.getElementById("drawResult");

    resultEl.style.opacity = 0;

    axios.get(BASE_URL + "draw.php", { params: { token: token } })
        .then( res => {
            resultEl.textContent = res.data.message;
            setTimeout(() => { resultEl.style.opacity = 1; }, 50);
            if(res.data.success){
                currentBottleId = res.data.bottleid;
            }
        })
        .catch( err => console.log("Failed to draw: " + err.message));
}

function markBottle(){
    const contentText = document.getElementById("markText").value;
    const token = localStorage.getItem("token");

    if(currentBottleId === null){
        document.getElementById("markStatus").textContent = "Draw a bottle first!";
        return;
    }

    axios.post(BASE_URL + "mark.php", new URLSearchParams({ bottleid: currentBottleId, content: contentText, token: token }))
        .then( res => document.getElementById("markStatus").textContent = res.data.message)
        .catch( err => console.log("Failed to mark: " + err.message));
}

function reportBottle(){
    const token = localStorage.getItem("token");

    if(currentBottleId === null){
        document.getElementById("reportStatus").textContent = "Draw a bottle first!";
        return;
    }

    axios.post(BASE_URL + "report.php", new URLSearchParams({ bottleid: currentBottleId, token: token }))
        .then( res => document.getElementById("reportStatus").textContent = res.data.message)
        .catch( err => console.log("Failed to report: " + err.message));
}

function keepBottle(){
    const token = localStorage.getItem("token");

    if(currentBottleId === null){
        document.getElementById("keepStatus").textContent = "Draw a bottle first!";
        return;
    }

    axios.post(BASE_URL + "keep.php", new URLSearchParams({ bottleid: currentBottleId, token: token }))
        .then( res => document.getElementById("keepStatus").textContent = res.data.message)
        .catch( err => console.log("Failed to keep: " + err.message));
}