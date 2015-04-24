<!DOCTYPE html>
<?php
    include("db.php");

    $db = new DB();
    if(!$db){
        echo $db->lastErrorMsg();
        exit();
    }

    if ($_GET['s'] == '') {
        echo 'ingen söksträng';
    } else {
        //$ingredients = explode(' ',$_GET['s']);
        // Bygg sträng i format 'foo','bar','apa' av ingredienserna i URLen.
        $ingredients = "'" . implode("','", explode(' ', mb_strtolower($_GET['s'], 'UTF-8'))) . "'";
        $sql = <<<SQL
        SELECT Recipes.rowid, Recipes.* FROM Recipes
        JOIN RecipesIngredients ON Recipes.rowid = RecipesIngredients.RecipeID
        WHERE RecipesIngredients.Ingredient IN ({$ingredients})
SQL;
        // Här vill man ha prepared statements för att undvika injektion,
        // men det är knepigt med ett godtyckligt antal ingredienser.
        // http://stackoverflow.com/questions/327274/mysql-prepared-statements-with-a-variable-size-variable-list
        $ret = $db->query($sql);
        //$recipes = $ret->fetchArray();
        //var_dump($recipes);
    }
    //$db->close();
?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Välj recept</title>
        <link rel="stylesheet" href="style.css">
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
                <div id="resultatarea">
                    <div id="sort">
                        Sortera efter:
                        <select>
                            <option value="volvo">Mest populär</option>
                            <option value="saab">Relevans</option>
                            <option value="mercedes">Högst betyg</option>
                            <option value="audi">Flest ägda bilar</option>
                        </select>
                    </div>
                    <?php
                    while ($row = $ret->fetchArray()) {
                        extract($row);
                        $q = "SELECT Ingredient FROM RecipesIngredients WHERE RecipeID = " . $rowid;
                        $ing = $db->query($q);
                        ?>
                        <div class="resultbox">
                            <div class="bildbox">
                                <img src="bilder/<?=$Picture?>" alt="bilder/<?=$Picture?>">
                            </div>
                            <div class="receptrubrik"><a href="recipe.php?id=<?=$rowid?>"><?=$Name?></a></div>
                            <div class="recepttext">detta receptet är bra</div>
                            <div class="ingrlabel">Ingredienser:</div>
                            <div class="resultingrdbox">
                                <ul>
                                    <?php
                                    while ($i = $ing->fetchArray()) { ?>
                                        <li class="resultingrd"><?=$i[0]?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                    <div id="previous">
                        <a href="#" id="nexttext">Föregående sida</a>
                    </div>
                    <div id="resultnav">
                        <a href="#" id="nexttext">1 - 2 - 3 - 4 - 5</a>
                    </div>
                    <div id="next">
                        <a href="#" id="nexttext">Nästa sida</a>
                    </div>
                </div>

                
                
                
                <div id="ingredienser">
                    <p>Dina valda ingredienser:</p>
                    
                    <ul>
                        <li>Mjölk</li>
                        <li>Ägg</li>
                        <li>Bacon</li>
                    </ul>
                    
                    <FORM METHOD="LINK" ACTION="index.html">
                    <INPUT TYPE="submit" class="button" id="redobutton" VALUE="Gör om sökning">
                    </FORM>
                    
                </div>

                
            </div>
            <footer>
                <p>Hannes Birgersson, Martin Gustavsson, Johan Stubbengaard, Maria Nguyen, Jenny Vuong</p>
            </footer>
        
        </div>
    </body>
</html>
