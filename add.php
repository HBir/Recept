<html>
<?php
	include("db.php"); 

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = $_POST["name"];
		$image = $_POST["pic"];
		$ingredients = $_POST["ingredients"];
		$recipe = $_POST["recipe"];
		$rating = $_POST["rating"];

		$istring = sprintf("INSERT INTO Recipes VALUES('%s','%s','%s','%s',%d)",
			               $name, $image, $ingredients, $recipe, $rating);
		echo $istring;
		$ret = $db->exec($istring);
		if(!$ret) {
			echo $db->lastErrorMsg();
		} else {
			echo "Lade till recept " . $db->lastInsertRowID();
		}
		$db->close();
		exit;
	}
?>
<br>
<form method="post">
	<input type="text" name="name" id="name" placeholder="Namn"><br>
	<input type="text" name="pic" id="pic" placeholder="Bild-url"><br>
	<textarea name="ingredients" id="ingredients" placeholder="Ingredienser"></textarea><br>
	<input type="text" name="recipe" id="recipe" placeholder="Receptet"><br>
	<input type="number" name="rating" min="1" max="5"><br>
	<input type="submit" value="Klar"><br>

</form>
</html>