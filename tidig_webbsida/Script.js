window.onload = setUp;
var ingrds = []

function setUp() {
	//alert("Nu har sidan laddats");
	document.getElementById("knapp").onclick = skapalank;
    document.getElementById("rensa").onclick = rensa;
    if (document.body.addEventListener) {
        document.body.addEventListener('click',addItem,false);
		document.body.addEventListener('click',removeItem,false);
    }
    else {
        document.body.attachEvent('onclick',addItem);
		document.body.attachEvent('onclick',removeItem);
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
        div.innerHTML += "<span><li>" + target.innerHTML + '<span id="cross"><a href="javaScript:void(0);" class="RemoveCross">X</a></span></li></span>';
        return true;
    } 
}

function removeItem(e) {
    e = e || window.event;
    var target;
    target = e.target || e.srcElement;
    if (target.className.match(/\bRemoveCross\b/)) {
		var fullRow = target.parentNode.parentNode.parentNode.innerHTML;
		var part = fullRow.substring(4,fullRow.lastIndexOf("<span id"));
		if (ingrds.indexOf(part) != -1) {
			var index = ingrds.indexOf(part);
			ingrds.splice(index,1);
			target.parentNode.parentNode.parentNode.innerHTML = "";
		} else {return false;}
        return true;
    } 
}


