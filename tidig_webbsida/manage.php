<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style.css">
		<style>
		 .table { padding:10px; width:900px; }
		 .left { float:left; width:450px; }
		 .right { float:right; width:450px; }
		 input { width:80%; margin: 3px; }
		 textarea { width:80%; height:400px; }
		.nyingrd { width: 175px;}
		</style>
		<script>
	  var i = 1;
	  function addrow(box, text1, text2) {
		  i++;
		  var input = document.createElement("INPUT");
	  
		  input.setAttribute("type", "text");
		  input.setAttribute("name", "ingrdfield1[]");
		  input.setAttribute("placeholder", text1);
		  input.className = "nyingrd";
		  document.getElementById(box).appendChild(input);
	  
	  
		  var t = document.createElement("INPUT");
		  t.setAttribute("type", "text");
		  t.setAttribute("name", "ingrdfield2[]");
		  t.setAttribute("placeholder", text2);
		  t.className = "nyingrd";
		  document.getElementById(box).appendChild(t);
	  
	  }
		</script>
	</head>
	<?php
		include("db.php");
		
		$db = new DB();
		if(!$db){
			echo $db->lastErrorMsg();
			exit();
		}
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// Lägga till recept
			if ($_POST["addtype"] == 1) {
				$q = $db->prepare("INSERT INTO Recipes VALUES(:name,:image,:instructions,:description,:course,:views,:rating)");
				$q->bindValue(':name', $_POST["name"], SQLITE3_TEXT);
				$q->bindValue(':image', $_POST["pic"], SQLITE3_TEXT);
				$q->bindValue(':instructions', $_POST["instructions"], SQLITE3_TEXT);
				$q->bindValue(':description', $_POST["description"], SQLITE3_TEXT);
				$q->bindValue(':course', mb_strtolower($_POST["course"]), SQLITE3_TEXT);
				$q->bindValue(':views', 0, SQLITE3_INTEGER);
				$q->bindValue(':rating', $_POST["rating"], SQLITE3_INTEGER);
				$ret = $q->execute();
				if(!$ret){
					echo $db->lastErrorMsg();
				}
		
				$id = $db->lastInsertRowID();
				$ingredients = explode(PHP_EOL, mb_strtolower($_POST["ingredients"], 'UTF-8'));
		
				// Gå genom alla ingredienser och lägg till i Ingredients, + en koppling till recept-id
				// i RecipesIngredients.
				foreach ($ingredients as $i) {
					if ($i !== '') {
						// Lägg till ingrediensen:
						$q = $db->prepare("INSERT OR IGNORE INTO Ingredients VALUES(:ingredient,'')");
						$q->bindValue(':ingredient', $i, SQLITE3_TEXT);
						$ret = $q->execute();
		
						if(!$ret){
							echo $db->lastErrorMsg();
						}
						// Lägg till kopplingen recept<->ingrediens:
						$q = $db->prepare("INSERT INTO RecipesIngredients VALUES(:id,:ingredient)");
						$q->bindValue(':id', $id, SQLITE3_INTEGER);
						$q->bindValue(':ingredient', $i, SQLITE3_TEXT);
						$ret = $q->execute();
		
						if(!$ret){
							echo $db->lastErrorMsg();
						}
					}
				}
			}
		
			// Lägga till endast ingredienser
			if ($_POST["addtype"] == 2) {
				$ingredients = explode(PHP_EOL, mb_strtolower($_POST["ingredients"], 'UTF-8'));
				foreach ($ingredients as $i) {
					if ($i !== '') {
						$q = $db->prepare("INSERT OR IGNORE INTO Ingredients VALUES(:ingredient,'')");
						$q->bindValue(':ingredient', $i, SQLITE3_TEXT);
						$ret = $q->execute();
		
						if(!$ret){
							echo $db->lastErrorMsg();
						}
					}
				}
				echo "Lade till ingrediens(er).";
			}
		
			// Nuka databasen och lägg till tables igen (om schemat ändras t ex)
			if ($_POST["addtype"] == 3) {
				$db->drop_tables();
				$db->init_tables();
			}
		}
		
	?>
	<body>
		<div id="wrapper">
			<div id="main">
				<a href="index.html"><header>
						<h1>Receptsökare!</h1>
					</header></a>
				<div class="table">
					<div class="left">
						<h2>Lägg till recept</h2>
						<form method="post">
							<input type="text" name="name" id="name" placeholder="Namn"><br>
							<input type="text" name="pic" id="pic" placeholder="Bild (filnamn)"><br>
								<div id="ingrdbox">
									<input type="text" name="ingrdfield1[]" placeholder="Ingrediens" class="nyingrd"><input type="text" name="ingrdfield2[]" placeholder="Mängd" class="nyingrd">
								</div>
							<p><button type="button" onclick="addrow('ingrdbox','Ingrediens','Mängd')">+</button></p>


							<br>
							<input type="text" name="instructions" id="instructions" placeholder="Instruktioner"><br>
							<input type="text" name="description" id="description" placeholder="Kort beskrivning"><br>
							<select name="course">
								<option value="1">Förrätt</option>
								<option value="2">Huvudrätt</option>
								<option value="3">Efterrätt</option>
							</select>
							<input type="number" name="rating" min="1" max="5"><br>
							<input type="hidden" name="addtype" value="1">
							<input type="submit" value="Klar" style="width:100px;">
						</form>
					</div>
					<div class="right">
						<h2>Lägg till ingredienser</h2>
						<form method="post">
							<div id="nyingrdbox">
								<input type="text" name="ingrdfield1[]" placeholder="Ny ingrediens" class="nyingrd"><input type="text" name="ingrdfield2[]" placeholder="Kategori" class="nyingrd">
							</div>
							<p><button type="button" onclick="addrow('nyingrdbox','Ny ingrediens','Kategori')">+</button></p>
							<input type="hidden" name="addtype" value="2">
							<input type="submit" value="Klar" style="width:100px;">
						</form>
						
						<h2>Rensa databasen</h2>
						<form method="post">
							<input type="hidden" name="addtype" value="3">
							<input type="submit" value="Rensa" style="width:100px;">
						</form>
					</div>
				</div>
			</div>
			<footer>
				<p>Hannes Birgersson, Martin Gustavsson, Johan Stubbengaard, Maria Nguyen, Jenny Vuong</p>
			</footer>
		</div>

	</body>
</html>