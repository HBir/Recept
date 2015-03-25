<?php 
	class DB extends SQLite3
	{
		function __construct()
		{
			$this->open('test2.db');
		}

		function init_table()
		{
			$sql = <<< EOF
				CREATE TABLE Recipes
				(Name          TEXT NOT NULL,
				Picture        TEXT NOT NULL,
				Ingredients    TEXT NOT NULL,
				Text           TEXT NOT NULL,
				Rating          INT);
EOF;

			$ret = $this->exec($sql);
			if(!$ret){
				echo $this->lastErrorMsg();
			} else {
				echo "<br>Created table";
			}
		}
		
		// Returnerar receptet med $id.
		function get_recipe($id)
		{
			$ret = $this->query("SELECT rowid, * FROM Recipes WHERE rowid = " . $id);
			return $ret->fetchArray();
		}
		
		// Temp-funktion för att lägga till lite testdata.
		function populate()
		{
			$sql = <<< EOF
			INSERT INTO Recipes VALUES('Mat', 'img/bild.jpg', 'ost', 'tillaga osten', 3);
			INSERT INTO Recipes VALUES('Mat2', 'img/bild2.jpg', 'skinka', '&auml;t l&aring;ngsamt', 5);
EOF;
			$ret = $this->exec($sql);
			if(!$ret){
				echo $this->lastErrorMsg();
			} else {
				echo "<br>Inserted";
			}
		}
	} 


	$db = new DB();
	if(!$db){
		echo $db->lastErrorMsg();
	} else {
		echo "Opened database<br>\n\n";
	}

	//$db->init_table();
	//$db->populate();

	/* Exempelgrejer
	$huhu = $db->query("SELECT rowid, * FROM Recipes WHERE Rating > 2");

	while ($row = $huhu->fetchArray()) {
		echo $row["rowid"] . " - " . $row["Name"] . ": " . $row["Text"];
		echo "<br>";
	}

	echo $db->get_recipe(2)["Name"]; 
	$db->close(); */
?>