//Projekt: Receptsökare
//Ansvarig: Hannes Birgersson

window.onload = setUp;
var ingrds = []; //Global array för att hantera valda ingredienser
var course;

function setUp() {
    //Lyssnare för att skapa händelser på tryck
	document.getElementById("knapp").onclick = skapalank;
    document.getElementById("rensa").onclick = rensa;
    document.getElementById("förrätt").onclick = forratt;
    document.getElementById("huvudrätt").onclick = huvudratt;
    document.getElementById("efterrätt").onclick = efterratt;
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
    if (ingrds.length <= 0) {                                                  //Ser till att inte listan är tom
        alert("Var god välj de ingredienser du vill använda");
        return false;
    }
    var courseID;
    switch(course) {
        case "förrätt":
            courseID = 1;
            break;
        case "huvudrätt":
            courseID = 2;
            break;
        case "efterrätt":
            courseID = 3;
            break;
        default:
            courseID = 0;
    }
    var ingrdsStr = ingrds.join("+");
    var destination = "search.php?c=" + courseID + "&s=" + ingrdsStr;
    
    window.location.href = destination;
}
function rensa() {
    //Tömmer både ingredienslistans innehåll, samt tar bort dem från att visas som valda på sidan.
    ingrds.length = 0;
    var div = document.getElementById('Ingredienslista');
    var x = document.getElementsByClassName("ItemMarked");
    var i;
    var l = x.length;
    for (i = 0; i < l; i++) {
        x[0].parentNode.innerHTML = x[0].parentNode.innerHTML.replace("Item ItemMarked", "Item");
    }

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
            //alert("Du har redan lagt till denna ingrediens");
        	var name = target.innerHTML;
        	var fullrow = target.parentNode.innerHTML;
        	var newrow = fullrow.replace("Item ItemMarked", "Item");
			target.parentNode.innerHTML = newrow;
			
			var index = ingrds.indexOf(name);
			ingrds.splice(index,1);

			var x = document.getElementsByClassName("addedItem");
			console.log(x[0].parentNode.innerHTML);
			var i;
			for (i = 0; i < x.length; i++) {
				if (x[i].innerHTML.indexOf(name) != -1) {
					x[i].innerHTML = "";
					i = x.length;
				}
			}

			return false;
        }
        if (ingrds.length >= 10) {
            alert("Du kan inte lägga till fler ingredienser");
            return false;
        }
        ingrds.push(target.innerHTML);
        
        /*Hitta det tryckta elementet och lägg*/
        var parent_Node = target.parentNode.innerHTML;
        parent_Node = parent_Node.replace("Item", "Item ItemMarked");
        target.parentNode.innerHTML = parent_Node;

        document.getElementById('Ingredienslista').innerHTML += 
        "<span class='addedItem'><li>" + target.innerHTML + 
        '<span id="cross"><a href="javaScript:void(0);" class="RemoveCross">X</a></span></li></span>';
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

        /*Hittar objektet som ska tas bort och tar bort det från ingrds och listan*/
		if (ingrds.indexOf(part) != -1) {
			var index = ingrds.indexOf(part);
			ingrds.splice(index,1);
			target.parentNode.parentNode.parentNode.innerHTML = "";
		} else {return false;}
        
        /*Hittar alla objekt som är markerade som valda, och tar bort stilklassen från den borttagna*/
        var x = document.getElementsByClassName("ItemMarked");
        var i;
        for (i = 0; i < x.length; i++) {
            if (x[i].parentNode.innerHTML.indexOf(part) != -1) {
                x[i].parentNode.innerHTML = x[i].parentNode.innerHTML.replace("Item ItemMarked", "Item");
                i = x.length;
            }
        }
        return true;
    } 
}


function courseselect(ratt) {
    /**/
    
    /*Hitta gamla markering och ta bort dem*/
    var x = document.getElementsByClassName("kursmark");
    var i;
    var l = x.length;
    for (i = 0; i < l; i++) {
        x[0].parentNode.innerHTML = x[0].parentNode.innerHTML.replace("kursbox kursmark", "kursbox");
        /*onclick pekarna måste återskapas eftersom även de tas bort*/
        recreatePointers();
    }
    
    if (ratt == course) {
        course = 0;
        return false;
    }
    
    course = ratt;
    var diver = document.getElementById(ratt);

    var newdiv = diver.parentNode.innerHTML.replace("kursbox", "kursbox kursmark");
    document.getElementById(ratt).parentNode.innerHTML = newdiv;
    recreatePointers();
}

function forratt() {
    courseselect("förrätt")
}
function huvudratt() {
    courseselect("huvudrätt")
}
function efterratt() {
    courseselect("efterrätt");
}
function recreatePointers() {
    document.getElementById("förrätt").onclick = forratt;
    document.getElementById("huvudrätt").onclick = huvudratt;
    document.getElementById("efterrätt").onclick = efterratt;
}