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

function throwBottle(){
    const messageText = document.getElementById("throwText").value;
    const token = localStorage.getItem("token");

    axios.post(BASE_URL + "throw.php", new URLSearchParams({ message: messageText, token: token }))
        .then( res => document.getElementById("throwStatus").textContent = res.data.message)
        .catch( err => console.log("Failed to throw: " + err.message));
}

function drawBottle(){
    const token = localStorage.getItem("token");

    axios.get(BASE_URL + "draw.php", { params: { token: token } })
        .then( res => {
            document.getElementById("drawResult").textContent = res.data.message;
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