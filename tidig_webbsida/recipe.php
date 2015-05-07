<!DOCTYPE html>
<?php
    include("db.php");

    $db = new DB();
    if(!$db){
        echo $db->lastErrorMsg();
        exit();
    }
    if ($_GET['id'] == '') {
        echo 'inget id';
    } else {
        // Hämta recept baserat på id i URL.
        $q = $db->prepare("SELECT rowid,* FROM Recipes WHERE rowid=:id");
        $q->bindValue(':id', $_GET['id'], SQLITE3_INTEGER);
        $ret = $q->execute();
        $recipe = $ret->fetchArray();

        // Hämta ingredienserna.
        $q = $db->prepare("SELECT Ingredient FROM RecipesIngredients WHERE RecipeID=:id");
        $q->bindValue(':id', $_GET['id'], SQLITE3_INTEGER);
        $ingredients = $q->execute();

        // Ta fram 3 liknande recept genom att göra en sökning på nuvarande
        // recepts ingredienser.
        while($i = $ingredients->fetchArray()) {
            $ing_array[] = $i[0];
        }
        $ing_string = "'" . implode("','", $ing_array) . "'";

        $sql = <<<SQL
        SELECT Recipes.rowid, Recipes.*, COUNT(*) AS Count FROM Recipes
        JOIN RecipesIngredients ON Recipes.rowid = RecipesIngredients.RecipeID
        WHERE RecipesIngredients.Ingredient IN ({$ing_string})
        AND Recipes.rowid <> {$recipe['rowid']}
        GROUP BY Recipes.rowid
        ORDER BY Count DESC, Recipes.Rating DESC
        LIMIT 3
SQL;
        $ret = $db->query($sql);
    }
?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?= $recipe['Name'] ?></title>
        <link rel="stylesheet" href="style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
       <div id="wrapper">
            <a href="index.html"><header>
                <h1>Receptsökare!</h1>
            </header></a>
            <div id="main">
                
                
                <div id="sok">
                    <input type="text" id="sokruta" name="sok" placeholder="Sök">
                    <button type="button" id="sokknapp">Hitta</button>
                </div>
                <div id="pagenav">
                  
                <hr class="side">  
                </div>
                <div id="recipepage">
                    
                    <div class="recipebox" id="recipeheader">
                        <h1 class="title"><?= $recipe['Name'] ?></h1> 
                        
                        <div class="recipepic"><img alt="<?= $recipe['Name'] ?>" src="bilder/<?= $recipe['Picture'] ?>"/></div>
                       
                       <div class="rating"><strong>Betyg</strong>
                           <span><a href="#"title="5 stjärnor"><input type="radio" name="rating" id="star5" value="5"><label for="star5">
                            </label></a></span>
                            <span><a href="#" title="4 stjärnor"><input type="radio" name="rating" id="star4" value="4"><label for="star4">
                            </label></a></span>
                            <span><a href="#" title="3 stjärnor"><input type="radio" name="rating" id="star3" value="3"><label for="star3">
                            </label></a></span>
                            <span><a href="#" title="2 stjärnor"><input type="radio" name="rating" id="star2" value="2"><label for="star2">
                            </label></a></span>
                            <span><a href="#" title="1 stjärna"><input type="radio" name="rating" id="star1" value="1"><label for="star1">
                            </label></a></span>
                        </div>

                           
                        <div class="recipedescription">
                            <p class="r_description"><?= $recipe['Description'] ?></p>
                            <p class="">4 portioner</p>

                        </div>

                    </div>

                    <hr class="side">  

                    <div class="recipecontent">
                        <div class="ingridients">
                            <h2 class"">Ingredienser</h2>
                                <ul class="ingridient-list">
                                    <?php // Ingredienslistan
                                    while($i = $ingredients->fetchArray()) { ?>
                                    <li><?= $i[0] ?></li>
                                    <?php } ?>
                                </ul>
                        </div>
                        <div class="instructions">
                            <h2>Gör såhär</h2>
                            <?= $recipe['Instructions'] ?>
                        </div>
                    </div><!--end recipecontent-->

                </div><!-- end recipepage-->

                    <div id="related_recipe">
                        <h2>Liknande recept</h2>
                        <ul class="teaser">
                            <?php
                            while($i = $ret->fetchArray()) { ?>
                            <li>
                                <a href="recipe.php?id=<?= $i['rowid'] ?>" title="<?= $i['Name'] ?>">
                                    <img src="bilder/<?= $i['Picture'] ?>">
                                    <figcaption><?= $i['Name'] ?></figcaption>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div><!--end related_recipe-->
            </div>
            <footer>
                <p>Hannes Birgersson, Martin Gustavsson, Johan Stubbengaard, Maria Nguyen, Jenny Vuong</p>
            </footer>
        
        </div>
    </body>
</html>