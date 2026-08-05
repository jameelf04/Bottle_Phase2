let currentBottleId = null;

function handleUserReady(res){
    localStorage.setItem("token", res.data.token);
}

function handleUserError(err){
    console.log("Failed to load user: " + err.message);
}

function initUser(){
    const savedToken = localStorage.getItem("token") || "";
    axios.get(BASE_URL + "create_user.php", { params: { token: savedToken } })
        .then(handleUserReady)
        .catch(handleUserError);
}

initUser();

function handleConditionResult(res){
    document.getElementById("oceanCondition").textContent = "Today's ocean: " + res.data.condition;
}

function handleConditionError(err){
    document.getElementById("oceanCondition").textContent = "Ocean condition unavailable.";
}

axios.get(BASE_URL + "condition.php")
    .then(handleConditionResult)
    .catch(handleConditionError);

function handleThrowResult(res){
    document.getElementById("throwStatus").textContent = res.data.message;
}

function handleThrowError(err){
    document.getElementById("throwStatus").textContent = "Something went wrong: " + err.message;
}

function throwBottle(){
    const messageText = document.getElementById("throwText").value;
    const token = localStorage.getItem("token");

    axios.post(BASE_URL + "throw.php", new URLSearchParams({ message: messageText, token: token }))
        .then(handleThrowResult)
        .catch(handleThrowError);
}

function handleDrawResult(res){
    const resultEl = document.getElementById("drawResult");
    resultEl.textContent = res.data.message;
    if(res.data.success){
        currentBottleId = res.data.bottleid;
    }
    setTimeout(() => { resultEl.style.opacity = 1; }, 50);
}

function handleDrawError(err){
    document.getElementById("drawResult").textContent = "Something went wrong: " + err.message;
}

function drawBottle(){
    const token = localStorage.getItem("token");
    document.getElementById("drawResult").style.opacity = 0;

    axios.get(BASE_URL + "draw.php", { params: { token: token } })
        .then(handleDrawResult)
        .catch(handleDrawError);
}

function handleMarkResult(res){
    document.getElementById("markStatus").textContent = res.data.message;
}

function handleMarkError(err){
    document.getElementById("markStatus").textContent = "Something went wrong: " + err.message;
}

function markBottle(){
    const contentText = document.getElementById("markText").value;
    const token = localStorage.getItem("token");

    if(currentBottleId === null){
        document.getElementById("markStatus").textContent = "Draw a bottle first!";
        return;
    }

    axios.post(BASE_URL + "mark.php", new URLSearchParams({ bottleid: currentBottleId, content: contentText, token: token }))
        .then(handleMarkResult)
        .catch(handleMarkError);
}

function handleReportResult(res){
    document.getElementById("reportStatus").textContent = res.data.message;
}

function handleReportError(err){
    document.getElementById("reportStatus").textContent = "Something went wrong: " + err.message;
}

function reportBottle(){
    const token = localStorage.getItem("token");

    if(currentBottleId === null){
        document.getElementById("reportStatus").textContent = "Draw a bottle first!";
        return;
    }

    axios.post(BASE_URL + "report.php", new URLSearchParams({ bottleid: currentBottleId, token: token }))
        .then(handleReportResult)
        .catch(handleReportError);
}