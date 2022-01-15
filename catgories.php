<?php

    $titlePage = "ecommerce";


    include 'init.php';

    if( isset($_GET['catename'])){

        $cat_name = $_GET['catename'] ;

        $stmt = $conn->prepare("select id from categories where name = ?");

        $stmt->execute(array($cat_name));

        $cat_id = $stmt->fetch();
        
    $items = get_itemAll($cat_id['id']);


    

        view($items);

    }    

    include $tpl . "/footer.inc.php";
?>

