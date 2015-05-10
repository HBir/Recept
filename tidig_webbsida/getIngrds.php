<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 5px;
}

th {text-align: left;}
	
	
	
</style>
</head>
<body>

<?php
$q = $_GET['q'];
$t = $_GET['t'];

$db = new PDO('sqlite:test2.db');

/*$sql="SELECT * FROM user WHERE id = '".$q."'";
 OR Ingredient="'.$t.'"*/
$query = 'SELECT * FROM Ingredients WHERE Category="'.$q.'"';

$result = $db->query($query);
echo "<div class='row'><ul>";
foreach($result as $row) {

    echo '<li><a href="javaScript:void(0);" class="Item">' . $row['Ingredient'] . '</a></li>';


}
echo "</ul></div>";

?>
</body>
</html>