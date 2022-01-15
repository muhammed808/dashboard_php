<?php

    $titlePage = "ecommerce";


    include 'init.php';

    if( isset($_GET['finditem'])){

        $item_name = '%' . $_GET['finditem'] . '%' ;

        $stmt = $conn->prepare("select * from items where name like ?");

        $stmt->execute(array($item_name));

        $items = $stmt->fetchAll();

        view($items);

    } 

    include $tpl . "/footer.inc.php";
?>