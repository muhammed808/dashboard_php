<?php 


ob_start();
session_start();

$titlePage = "categories";

if(isset($_SESSION['username'])) {

    include 'init.php';
    // errorID();

    $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

    if($do == "Manage"){ // manage page for admins to delete users 
    
        $requestStat = array();
        $dataSent = array();
        
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        $admin = query("group_id","users_shop","user_id",$userid); 

        $stmt = $conn->prepare("SELECT * FROM categories ORDER BY ordering ASC");
        $stmt->execute();
        $allCate = $stmt->fetchAll();

        echo '<div class="container">
                        <h1>manage categories</h1>
                        <div class="card">
                        <h5 class="card-header"><i class="fas fa-tasks"></i> manage categories</h5>
                        <div class="card-body">           
                        <div class="accordion" id="accordionExample">';

        foreach($allCate as $key => $i){

            $idName = str_replace(" ","-",$i['name']);

            echo '
                    <div class="card">
                        <div class="card-header" id="heading'.$idName.'">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse'.$idName.'" aria-expanded="false" aria-controls="collapse'.$idName.'">
                                    <h5>'.$i['name'].'</h5>
                                </button>
                            </h2>
                        </div>
          
              <div id="collapse'. $idName .'" class="collapse" aria-labelledby="heading'. $idName .'" data-parent="#accordionExample">
                <div class="card-body">';  ?>

                    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample<?php echo $idName; ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo $idName; ?>">
                        <i class="far fa-eye"></i> description
                    </a>
                    <div class="collapse" id="collapseExample<?php echo $idName; ?>">
                        <div class="card card-body">
                            <?php
                                echo "<h5>" . $i['description'] . "</h5>";

                                if($i['visibility'] == "0"){
                                    echo '<span class="visibility">hidden</span>';
                                }
                                if($i['allow_comment'] == "0"){
                                    echo '<span class="comment">comment disabled</span>';
                                }
                                if($i['allow_ads'] == "0"){
                                    echo '<span class="ads">adverties disabled</span>';
                                }
                                echo '<span></span>';

                                $countItems = checkItem("cat_id","items",$i['id']);
                                echo '<span class="count-item"> ' . $countItems . ' items <i class="fas fa-cart-plus"></i></span>';
                                
                            ?>
                        </div>
                    </div>
                    <a href="categories.php?do=Edit&userid=<?php echo $userid ?>&cateid=<?php print_r($i['id']) ?>" class="btn btn-success"><i class="fas fa-edit"></i> edit</a>
                    <a href="categories.php?do=Delete&userid=<?php echo $userid ?>&cateid=<?php print_r($i['id']) ?>" class="btn btn-danger confirm"><i class="fas fa-trash-alt"></i> delete</a>

        <?php    
                echo '    

                    </div>
                </div>
            </div>
        ';
        } ?>
                    </div>
                </div>
            </div>
            <a href="?do=Add&userid=<?php echo $userid; ?>"class="btn btn-primary">
            <i class="fas fa-plus"></i> add new categories
            </a>
        </div>
    <?php
    }elseif ($do == "Edit"){ // Edit page for users to edit his profile
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        $admin = query("group_id","users_shop","user_id",$userid);
        $cateid = (isset($_GET['cateid']) && is_numeric($_GET['cateid'])) ? intval($_GET['cateid']) : 0;

        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute(array($cateid));
        $count = $stmt->rowCount();
        $dataBase = $stmt->fetch(PDO::FETCH_BOTH);

        if($count > 0 && $admin == 1){       ?>
         <div class="container text-center">
                <h1>edit categories</h1>
        </div> 
        <form class="login edit" action="?do=Update&userid=<?php echo $userid; ?>&cateid=<?php echo $cateid ?>" method="POST">
            <div class="titel-registor">
                <h4>edit categories</h4> <i class="fas fa-pen"></i>   
            </div>

            <div class="box">
                <i class="fab fa-delicious"></i>
                <input  type="text" name="name-cate" class="form-control" placeholder="name of the category" value="<?php print_r($dataBase['name']) ?>" required />
            </div>
            <div class="box">
                <i class="fas fa-audio-description"></i>
                <input  type="text" name="decription" class="form-control" placeholder="decribe the category" value="<?php print_r($dataBase['description']) ?>" />
            </div>
            <div class="box">
                <i class="fas fa-sort"></i>
                <input  type="number" name="ordering" class="form-control" placeholder="number to arrang the category" value="<?php print_r($dataBase['ordering']) ?>"  />
            </div>
            <div class="first-last-name">
                <div class="frow">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="visibility" class="custom-control-input" id="customSwitch1" value="1">
                        <label class="custom-control-label" for="customSwitch1">visibility</label>
                    </div>
                </div>
                <div class="mrow">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="comment" class="custom-control-input" id="customSwitch2" value="1">
                        <label class="custom-control-label" for="customSwitch2">allow comment</label>
                    </div> 
                </div>
                <div class="lrow">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="ads" class="custom-control-input" id="customSwitch3" value="1">
                        <label class="custom-control-label" for="customSwitch3">allow ads</label>
                    </div> 
                </div>
            </div>    
            
            <div class="alert alert-secondary" role="alert">
            </div>
            <input type="submit" class="btn btn-primary btn-block" name="send" value="save" />         
        </form>
    <?php
        }else{
            $Mas = "Sorry that is not your ID";
            $home = "home.php";
            returnHome($Mas,$home);
        }   
    }elseif($do == "Update"){  // update page for admins and users 
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        $admin = query("group_id","users_shop","user_id",$userid);
        $cateid = (isset($_GET['cateid']) && is_numeric($_GET['cateid'])) ? intval($_GET['cateid']) : 0;

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            $nameCate       = filter_var($_POST['name-cate'],FILTER_SANITIZE_STRING);
            $descripCate    = filter_var($_POST['decription'],FILTER_SANITIZE_STRING);
            $orderCate      = filter_var($_POST['ordering'],FILTER_SANITIZE_NUMBER_INT);
            $visibCate      = (isset($_POST['visibility'])) ? "1" : "0" ;
            $commentCate    = (isset($_POST['comment'])) ? "1" : "0" ;
            $adsCate        = (isset($_POST['ads'])) ? "1" : "0" ;

            if($admin == 1){
                $stmt = $conn->prepare("UPDATE
                                        categories
                                SET 
                                    name = ? , description = ? , ordering = ? ,
                                    visibility = ? , allow_comment = ? , 	allow_ads = ?
                                WHERE 
                                id = ?");
                $stmt->execute(array($nameCate,$descripCate,$orderCate,$visibCate,$commentCate,$adsCate,$cateid ));
                
                $Mas = $stmt->rowCount() . " recoud updated ";
                $home = $_SERVER['HTTP_REFERER'];

                returnHome($Mas,$home,1);
            }
        }
        
    }elseif($do == "Delete"){  // Delete page for admins to delete users
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        $admin = query("group_id","users_shop","user_id",$userid);
        $cateid = (isset($_GET['cateid']) && is_numeric($_GET['cateid'])) ? intval($_GET['cateid']) : 0;
            try{

                $admin = query("group_id","users_shop","user_id",$userid);
                
                if($admin == 1){
                    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
                    $stmt->execute(array($cateid));

                    $Mas = $stmt->rowCount() . " recorde Deleted done";
                    $home = $_SERVER['HTTP_REFERER'];
                    returnHome($Mas,$home,1);
                    
                }else{
                    $Mas = "Sorry you are not admin you can't delete categories";
                    $home = "home.php";
                    returnHome($Mas,$home);
                }
            }catch(PDOException $e){
                echo $e->getMassage;
            }
    }elseif($do == "Add"){  // Add page for admins to Add Admins
    
        $requestStat = array();
        $dataSent = array();
        
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        $admin = query("group_id","users_shop","user_id",$userid);

        if($_SERVER['REQUEST_METHOD'] == "POST"){

            $nameCate       = filter_var($_POST['name-cate'],FILTER_SANITIZE_STRING);
            $descripCate    = filter_var($_POST['decription'],FILTER_SANITIZE_STRING);
            $orderCate      = filter_var($_POST['ordering'],FILTER_SANITIZE_NUMBER_INT);
            $visibCate      = (isset($_POST['visibility'])) ? "1" : "0" ;
            $commentCate    = (isset($_POST['comment'])) ? "1" : "0" ;
            $adsCate        = (isset($_POST['ads'])) ? "1" : "0" ;

            $error = "sorry can't add this categories this categoreis is already exist";

            $check = checkItem("name","categories",$nameCate);

            if($check == 1){
                $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> $error";
            }else{
                try{
                    $stmt = $conn->prepare("INSERT INTO
                                                categories 
                                                (name ,description ,ordering ,visibility ,allow_comment ,allow_ads)
                                            VALUES(:name , :des , :order , :vis , :comm , :ads) ");
                    $stmt->execute(array(
                        ":name"     => $nameCate,
                        ":des"      => $descripCate,
                        ":order"    => $orderCate,
                        ":vis"      => $visibCate,
                        ":comm"     => $commentCate,
                        ":ads"      => $adsCate
                    ));
                    $dataSent[] = '<i class="fas fa-check-circle"></i><span style="color:#00ff08"> addation categories has been done </span>';
                }
                catch(PDOException $e){
                    echo $e->getMessage();
                }
            }
        }
    ?>
        <div class="container text-center">
                <h1>add categories</h1>
        </div> 
        <form class="login edit" action="?do=Add&userid=<?php echo $userid; ?>" method="POST">
            <div class="titel-registor">
                <h4>add categories</h4> <i class="fas fa-pen"></i>   
            </div>

            <div class="box">
                <i class="fab fa-delicious"></i>
                <input  type="text" name="name-cate" class="form-control" placeholder="name of the category" value="<?php if(! empty($requestStat)){echo $nameCate;} ?>" required />
            </div>
            <div class="box">
                <i class="fas fa-audio-description"></i>
                <input  type="text" name="decription" class="form-control" placeholder="decribe the category" value="<?php if(! empty($requestStat)){echo $descripCate;} ?>" />
            </div>
            <div class="box">
                <i class="fas fa-sort"></i>
                <input  type="number" name="ordering" class="form-control" placeholder="number to arrang the category" value="<?php if(! empty($requestStat)){echo $orderCate;} ?>"  />
            </div>
            <div class="first-last-name">
                <div class="frow">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="visibility" class="custom-control-input" id="customSwitch1" value="1">
                        <label class="custom-control-label" for="customSwitch1">visibility</label>
                    </div>
                </div>
                <div class="mrow">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="comment" class="custom-control-input" id="customSwitch2" value="1">
                        <label class="custom-control-label" for="customSwitch2">allow comment</label>
                    </div> 
                </div>
                <div class="lrow">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="ads" class="custom-control-input" id="customSwitch3" value="1">
                        <label class="custom-control-label" for="customSwitch3">allow ads</label>
                    </div> 
                </div>
            </div>  
            
            <div class="alert alert-secondary" role="alert">
                    <?php
                        if(empty($requestStat)){
                            echo "";
                        }else{
                            foreach($requestStat as $stat){
                                echo $stat . "<br>";
                            }
                        }
                        if(! empty($dataSent)){
                            foreach($dataSent as $data){
                                echo $data;
                            }
                        }
                    ?>
            </div>
            <input type="submit" class="btn btn-primary btn-block" name="send" value="add category" />         
        </form>
<?php

    }

    include $tpl . "/footer.inc.php";
}else{
    header("location: index.php");
    exit();
}
    ob_end_flush();
?>



