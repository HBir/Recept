
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
        // Bygg sträng i format 'foo','bar','apa'
        $ingredients = "'" . implode("','", explode(' ', mb_strtolower($_GET['s'], 'UTF-8'))) . "'";
        $sql = <<<SQL
        SELECT Recipes.rowid, * FROM Recipes
        JOIN RecipesIngredients ON Recipes.rowid = RecipesIngredients.RecipeID
        WHERE RecipesIngredients.Ingredient IN ({$ingredients})
SQL;
       //$q = $db->prepare($sql); 
       //$q->bindValue(':ingredients', $ingredients, SQLITE3_TEXT);
       $ret = $db->query($sql);
       //var_dump($ret->fetchArray());
    }
    $db->close();
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
                <div id="pagenav">
                    
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
                    $result = 1;
                    // Loopa genom resultaten och skapar en div för varje.
                    while ($row = $ret->fetchArray()) {
                        echo <<<HTML
                        <div class="resultbox" id="result$result">
                            <div class="bildbox">
                            </div>
                            <div class="receptrubrik"><a href="recipe.php?id={$row['rowid']}">{$row['Name']}</a></div>
                            <div class="recepttext">{$row['Text']}</div>
                        </div>
HTML;
                        $result++;
                    }
                    ?>
                    <div id="next">
                        <a href="#">Nästa sida</a>
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
