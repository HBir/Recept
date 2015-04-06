window.onload = setUp;
var ingrds = []

function setUp() {
	//alert("Nu har sidan laddats");
	document.getElementById("knapp").onclick = skapalank;
    document.getElementById("rensa").onclick = rensa;
    if (document.body.addEventListener) {
        document.body.addEventListener('click',addItem,false);
    }
    else {
        document.body.attachEvent('onclick',addItem);
    }
	
}


function skapalank() {
    //var ingrds = ["Mjölk", "Ägg", "Bacon"];
    var ingrdsStr = ingrds.join("+");
    alert(".../search/" + ingrdsStr);
    console.log(ingrdsStr);

}
function rensa() {
    ingrds.length = 0;
    var div = document.getElementById('Ingredienslista');
    div.innerHTML = "";
}

function addItem(e) {
    e = e || window.event;
    var target;
    target = e.target || e.srcElement;
    if (target.className.match(/\bItem\b/)) {
        if (ingrds.indexOf(target.innerHTML) != -1) {
            alert("Du har redan lagt till denna ingrediens");
            return false;
        }
        ingrds.push(target.innerHTML)
        var div = document.getElementById('Ingredienslista');
        div.innerHTML += "<li>" + target.innerHTML + '<span><a href="javaScript:void(0);">X</a></span></li>';
        return true;
    } 
}

function removeItem(e) {
    
}


