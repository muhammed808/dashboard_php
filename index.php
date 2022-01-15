<?php

    $titlePage = "ecommerce";


    include 'init.php';

    $sql = "SELECT * FROM items WHERE approve = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $items = $stmt->fetchAll();
    
    echo '<div class="container">';
    echo '<div class="row">';

        view($items);

    echo "</div>";
    echo "</div>";
    
    

    include $tpl . "/footer.inc.php";
?>

