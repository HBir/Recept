<?php 
class DB extends SQLite3
{
	// Konstruktorn öppnar anslutningen till databasen.
	function __construct()
	{
		$this->open('test2.db');
	}

	// Exekvera en sql-query och skriv ut felmeddelande
	// eller $message vid framgång.
	function execute($sql, $message)
	{
		$ret = $this->exec($sql);
		if(!$ret){
			echo $this->lastErrorMsg();
		} else {
			echo "<br>" . $message;
		}
	}
	// Töm databasen.
	function drop_tables()
	{
		$sql = <<<SQL
			DROP TABLE Recipes;
			DROP TABLE Ingredients;
			DROP TABLE RecipesIngredients;
SQL;
		$this->execute($sql, "Dropped tables.");
	}

	// Bygg tabellerna
	function init_tables()
	{
		$sql = <<<SQL
			CREATE TABLE Recipes
			(Name          TEXT NOT NULL,
			Picture        TEXT NOT NULL,
			Instructions   TEXT NOT NULL,
			Description    TEXT,
			Course 		   TEXT,
			Views		   INT,
			Rating         INT);
			
			CREATE TABLE Ingredients
			(Ingredient TEXT NOT NULL UNIQUE,
			Category    TEXT);

			CREATE TABLE RecipesIngredients
			(RecipeID  INT NOT NULL,
			Ingredient TEXT NOT NULL);
SQL;
		$this->execute($sql, "Created tables.");
	}
}
?>