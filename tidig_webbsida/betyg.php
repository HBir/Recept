<?php
	$a = $_GET['a'];
	$b = $_GET['b'];

	$db = new PDO('sqlite:test2.db');

	$stmt = $db->prepare("UPDATE Recipes 
						SET Rating=Rating+:Betyg 
						WHERE rowid=:ID");
	if ($b > 0) {
		$b = 1;
	} else {
		$b = -1;
	}
	$stmt->bindParam(':Betyg', $b);
	$stmt->bindParam(':ID', $a);
	$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Ändrar betyg</title>
    </head>
    <body>
       
    </body>
</html>
