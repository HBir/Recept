window.onload = setUp;
var ingrds = [] //Skapar en global array för att hantera valda ingredienser

function setUp() {
    //Lyssnare för att skapa händelser på tryck
	document.getElementById("knapp").onclick = skapalank;
    document.getElementById("rensa").onclick = rensa;
    if (document.body.addEventListener) { //För äldre versioner av IE
        document.body.addEventListener('click',addItem,false);
		document.body.addEventListener('click',removeItem,false);
    }
    else {
        document.body.attachEvent('onclick',addItem);
		document.body.attachEvent('onclick',removeItem);
    }
}

function skapalank() {
    //Tar alla rader i listan på valda ingredienser och gör en URL av dem
    if (ingrds.length == 0) {                                                  //Ser till att inte listan är tom
        alert("Var god välj de ingredienser du vill använda");
        return false;
    }
    var ingrdsStr = ingrds.join("+");
    var destination = "/search/" + ingrdsStr;

    window.location.href = destination;
    //alert(".../search/" + ingrdsStr);
    console.log(ingrdsStr); 

}
function rensa() {
    //Tömmer både ingredienslistans innehåll, samt tar bort dem från att visas som valda på sidan.
    ingrds.length = 0;
    var div = document.getElementById('Ingredienslista');
    div.innerHTML = "";
}

function addItem(e) {
    //Lägger till en vald ingrediens i listan över valda ingredienser och visar upp dem som valda i sidans HTML. 
    //(Rader med "||" är för kompabilitetssyfte)
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
    //Tar bort markerat objekt, och tar bort dem från att visas som valda. 
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


