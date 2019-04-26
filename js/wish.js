document.getElementById("btn").onclick = function () {
    var btn = document.getElementById("btn")
    if(btn.style.background == "white"){
        btn.style.background = "red";
    }
    else {
        btn.style.background = "white";
    }
}