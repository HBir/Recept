<?php 
	class DB extends SQLite3
	{
		function __construct()
		{
			$this->open('test2.db');
		}

		function execute($sql, $message)
		{
			$ret = $this->exec($sql);
			if(!$ret){
				echo $this->lastErrorMsg();
			} else {
				echo "<br>" . $message;
			}
		}
		// Tömmer databasen.
		function drop_tables()
		{
			$sql = <<<SQL
				DROP TABLE Recipes;
				DROP TABLE Ingredients;
				DROP TABLE RecipesIngredients;
SQL;
			$this->execute($sql, "Dropped tables.");
		}

		function init_tables()
		{
			$sql = <<<SQL
				CREATE TABLE Recipes
				(Name          TEXT NOT NULL,
				Picture        TEXT NOT NULL,
				Text           TEXT NOT NULL,
				Rating          INT);
				
				CREATE TABLE Ingredients
				(Ingredient TEXT NOT NULL UNIQUE,
				Category    TEXT);

				CREATE TABLE RecipesIngredients
				(RecipeID INT NOT NULL,
				Ingredient TEXT NOT NULL);
SQL;
			$this->execute($sql, "Created tables.");
		}

		// Returnerar receptet med $id.
		function get_recipe($id)
		{
			$ret = $this->query("SELECT rowid, * FROM Recipes WHERE rowid = " . $id);
			return $ret->fetchArray();
		}
	} 
	/* Exempelgrejer
	$huhu = $db->query("SELECT rowid, * FROM Recipes WHERE Rating > 2");

	while ($row = $huhu->fetchArray()) {
		echo $row["rowid"] . " - " . $row["Name"] . ": " . $row["Text"];
		echo "<br>";
	}

	echo $db->get_recipe(2)["Name"]; 
	*/
?>