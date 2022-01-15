<?php 


// function to print title page

    global $title;

$title = (isset($titlePage)) ? $titlePage : "defulte";

function get_item($cat_id){

    global $conn;

    $stmt = $conn->prepare("SELECT * FROM items WHERE cat_id = ? AND approve = 1");
    
    $stmt->execute(array($cat_id));

    $dataBase = $stmt->fetchAll(PDO::FETCH_BOTH);

    return $dataBase;
}

function get_itemAll($cat_id){

    global $conn;
    try{
        $stmt = $conn->prepare("SELECT * FROM items WHERE cat_id = ? AND approve = 1");
    
    $stmt->execute(array($cat_id));

    $dataBase = $stmt->fetchAll();
    return $dataBase;
    }catch(PDOException $e){
        return $e->getMessage();
    }
    

    
}
//function for view all aitems

function view($items){

    echo '<div class="container">';
    echo '<div class="row">';
    
    foreach($items as $item){ 
        $srcImg = (empty($item['image']))? "01.jpg":$item['image'];
        ?>
        <div class="col-md-4">
            <div class="card" style="width: 18rem;">
                <img src="admin/uploads/<?=$srcImg?>" class="card-img-top" alt="..." width="100%" height="200px">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $item['name']; ?></h5>
                    <p class="card-text"><?php echo $item['description']; ?></p>
                    <a href="#" class="btn btn-primary"><?php echo '$' . $item['price'] . " "; ?><i class="fas fa-cart-plus"></i></a>
                </div>
            </div>
        </div>
<?php
    }

    
    echo "</div>";
    echo "</div>";

}

















// function to control query in database

function query($selection,$table,$column,$val){

    global $conn;

    $stmt = $conn->prepare("SELECT $selection FROM $table WHERE $column = $val");
    
    $stmt->execute();

    $dataBase = $stmt->fetch();

    return $dataBase[$selection];
}

function queryAll($selection,$table){

    global $conn;

    $stmt = $conn->prepare("SELECT $selection FROM $table");
    
    $stmt->execute();

    $dataBase = $stmt->fetchAll();

    return $dataBase;
}

function checkItem($selection,$table,$val){

    global $conn;

    $stmt = $conn->prepare("SELECT $selection FROM $table WHERE $selection = ?");
    $stmt->execute(array($val));
    $count = $stmt->rowCount();

    return $count;
}

function getCount($selection,$table){
    global $conn;
    $stmt = $conn->prepare("SELECT $selection FROM $table ");
    $stmt->execute();

    $dataBase = $stmt->rowCount();

    return $dataBase;

}

