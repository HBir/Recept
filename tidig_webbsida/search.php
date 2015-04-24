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
        // Bygg sträng i format 'foo','bar','apa' av ingredienserna i URLen.
        $ing_array = explode(' ', $_GET['s']);
        $ingredients = "'" . mb_strtolower(implode("','", $ing_array), 'UTF-8') . "'";

        $sql = <<<SQL
        SELECT Recipes.rowid, Recipes.* FROM Recipes
        JOIN RecipesIngredients ON Recipes.rowid = RecipesIngredients.RecipeID
        WHERE RecipesIngredients.Ingredient IN ({$ingredients})
SQL;
        // Här vill man nog ha prepared statements för att undvika injektion,
        // men det är knepigt med ett godtyckligt antal ingredienser.
        // http://stackoverflow.com/questions/327274/mysql-prepared-statements-with-a-variable-size-variable-list
        $ret = $db->query($sql);
    }
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
                        extract($row); // Alla element i $row blir egna variabler.
                        $q = "SELECT Ingredient FROM RecipesIngredients WHERE RecipeID = " . $rowid;
                        $ing = $db->query($q);
                        ?>
                        <a href="recipe.php?id=<?=$rowid?>">
                        <div class="resultbox">
                            <div class="bildbox">
                                <img src="bilder/<?=$Picture?>" alt="bilder/<?=$Picture?>">
                            </div>
                            <div class="receptrubrik"><?=$Name?></div>
                            <div class="recepttext"><?= $Description ?></div>
                            <div class="ingrlabel">Ingredienser:</div>
                            <div class="resultingrdbox">
                                <ul>
                                    <?php
                                    // Loopa genom ingredienserna i receptet och lägg i listan.
                                    while ($i = $ing->fetchArray()) { ?>
                                        <li class="resultingrd"><?= $i[0] ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        </a>
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
                        <?php
                        // Befolka listan över valda ingredienser.
                        foreach($ing_array as $i) { ?>
                        <li><?= $i ?></li>
                        <?php } ?>
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
