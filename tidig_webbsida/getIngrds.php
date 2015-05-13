<!--Ansvarig: Hannes Birgersson-->
<?php
function letterSearch($char, $Category, $stmt) {
	/*Ritar ut alla ingredienser som börjar på bokstaven $char, under kategorin $Category
	 *$stmt är en SQL query*/
	$a= $char."%";

	$stmt->bindParam(':Category', $Category);
	$stmt->bindParam(':Letter', $a);
	$stmt->execute();
	$result = $stmt->fetchAll();

	echo "<div class='row'><ul>";
	echo "<h2>".$char."</h2>";
	$i = 0;

	foreach($result as $row) {
		echo '<li><a href="javaScript:void(0);" class="Item">' . $row['Ingredient'] . '</a></li>';
		$i++;
		if($i %10 == 0) {
			echo "</div></ul></div>";
			echo "<div class='row'><ul>";
		}
	}
	echo "</div></ul></div>";
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
	</head>
	<body>

		<?php
			$q = $_GET['q'];
			$s = $_GET['s'];
			
			
			$db = new PDO('sqlite:test2.db');
			
			if ($s!='0'){
				$stmt = $db->prepare("SELECT Ingredient FROM Ingredients 
										WHERE Category=:Category
										AND Ingredient LIKE :Letter 
										ORDER BY Ingredient");
				/*Kör en bokstavssökning för varje bokstav i $s*/
				for ($x = 0; $x < (strlen(utf8_decode($s))); $x++) {
					letterSearch(mb_substr($s, $x, 1, 'UTF-8'), $q, $stmt);
				}
			} else {
				/*$s som 0 resulterar i en lista med alla ingredienser ur en kategori*/
				$stmt = $db->prepare("SELECT Ingredient FROM Ingredients 
										WHERE Category=:Category 
										ORDER BY Ingredient");
				$stmt->bindParam(':Category', $q);
				$stmt->execute();
			
				$result = $stmt->fetchAll();
				echo "<div class='row'><ul>";
				$i = 0;
				foreach($result as $row) {
					echo '<li><a href="javaScript:void(0);" class="Item">' . $row['Ingredient'] . '</a></li>';
					$i++;
					if($i %10 == 0) {
						echo "</div></ul></div>";
						echo "<div class='row'><ul>";
					}
				}
				echo "</div></ul></div>";
				
			}
		?>
	</body>
</html>