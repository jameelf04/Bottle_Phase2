<?php

function fakeMessage(){
    $messages = [
        "I told everyone I got the internship. I didn't.",
        "I still check his messages even though I said I moved on.",
        "I let my sister take the blame and never said anything.",
        "I pretend I'm okay with being alone but I'm really not.",
        "I lied about being busy just so I wouldn't have to go.",
        "I never told my parents I dropped that class.",
        "Sometimes I miss being someone I'm not anymore.",
        "I said I forgave them. I haven't.",
        "I still have the voicemail. I listen to it sometimes.",
        "I smiled through the whole thing and cried after.",
        "I know exactly why they left. I never said sorry.",
        "I keep the ticket stub from a trip I never told anyone about.",
        "I'm scared I peaked already and no one noticed.",
        "I wrote the resignation letter three times and deleted it.",
        "I still set two cups out some mornings.",
        "Nobody knows I failed the exam I bragged about acing.",
        "I said I was proud of them. I was actually just jealous.",
        "I keep starting over and calling it a new beginning.",
        "I never returned the money and I think about it a lot.",
        "I laughed at the joke but I didn't understand it at all.",
        "I still wear the ring, just not where anyone can see.",
        "I told them I was busy that day. I just didn't want to go.",
        "I practiced the apology in the mirror and never said it.",
        "Some nights I still set an alarm for a job I don't have anymore.",
        "I let them think it was their fault. It wasn't.",
        "I kept the plant alive longer than the relationship.",
        "I said I was over it in front of everyone. I lied.",
        "I still know their number by heart and I hate that.",
        "I told the truth to a stranger before I told anyone I love.",
        "Some days I forget why I'm still doing this, and that scares me."
    ];

    return $messages[array_rand($messages)];
}

function fakeMarkContent(){
    $marks = [
        "this happened to me too",
        "tell them. it's lighter after.",
        "you're not alone in this",
        "thank you for saying this",
        "I needed to read this today",
        "it gets easier, I promise",
        "same. every word of this.",
        "I hope you're doing okay now",
        "sending you strength",
        "this made me feel less alone",
        "I've never told anyone this either",
        "you're braver than you think"
    ];

    return $marks[array_rand($marks)];
}

function fakeDisplayName(){
    $adjectives = ["salt", "ash", "storm", "quiet", "deep", "pale", "still", "faint"];
    $nouns = ["lantern", "signal", "harbor", "current", "tide", "drift", "hollow", "shore"];

    return $adjectives[array_rand($adjectives)] . "-" . $nouns[array_rand($nouns)] . "-" . rand(1, 999);
}

function fakeToken(){
    return bin2hex(random_bytes(32));
}

function fakeTimeInPast($maxDaysAgo){
    $daysAgo = rand(0, $maxDaysAgo);
    $secondsAgo = rand(0, 86399);
    return date("Y-m-d H:i:s", time() - ($daysAgo * 86400) - $secondsAgo);
}

?>