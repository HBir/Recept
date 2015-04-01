<html>
<?php
	include("db.php"); 

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$search = $_POST["search"];
		/*
		$istring = sprintf("INSERT INTO Recipes VALUES('%s','%s','%s','%s',%d)",
			               $name, $image, $ingredients, $recipe, $rating);
		echo $istring;
		*/
		$istring = "SELECT * FROM Recipes WHERE Ingredients LIKE '%" . $search . "%'";
		$ret = $db->query($istring);
		if(!$ret) {
			echo $db->lastErrorMsg();
		} else {
			while ($row = $ret->fetchArray()) {
				echo "<br>" . $row["Name"];
			}
		}
		$db->close();
		exit;
	}
	
?>
<br>
<form method="post">
	<input type="text" name="search" id="search" placeholder="Query"><br>
	<input type="submit" value="Search"><br>
	
</form>
</html>