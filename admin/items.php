<?php 


ob_start();
session_start();

$titlePage = "items";

if(isset($_SESSION['username'])) {

    include 'init.php';
    // errorID();

    $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

    if($do == "Manage"){ // manage page for admins to delete users

        
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

        $admin  = query("group_id","users_shop","user_id",$userid);
        $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;
        $sort   = (isset($_GET['sort'])) ? $_GET['sort'] : "item_id"; 

        $stmt = $conn->prepare("SELECT 
                                items.* , categories.name AS categories , users_shop.full_name 
                            FROM items 
                            JOIN categories ON  items.cat_id = categories.id 
                            JOIN users_shop on items.member_id = users_shop.user_id
                            ORDER BY $sort");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_BOTH);
        ?>

            <div class="container">
                <h1>items table</h1>

                <div class="table-responsive">
                    <table class="table table-dark table-striped">
                        <tr>
                            <td><a href="<?php echo $_SERVER["PHP_SELF"] . "?do=Manage&userid=" . $userid . "&sort=item_id" ; ?>">image</a></td>
                            <td><a href="<?php echo $_SERVER["PHP_SELF"] . "?do=Manage&userid=" . $userid . "&sort=name" ; ?>">name</a></td>
                            <td><a href="<?php echo $_SERVER["PHP_SELF"] . "?do=Manage&userid=" . $userid . "&sort=description" ; ?>">description</a></td>
                            <td><a href="<?php echo $_SERVER["PHP_SELF"] . "?do=Manage&userid=" . $userid . "&sort=price" ; ?>">price</a></td>
                            <td><a href="<?php echo $_SERVER["PHP_SELF"] . "?do=Manage&userid=" . $userid . "&sort=add_date" ; ?>">date</a></td>
                            <td><a href="<?php echo $_SERVER["PHP_SELF"] . "?do=Manage&userid=" . $userid . "&sort=country_made" ; ?>">country</a></td>
                            <td><a href="<?php echo $_SERVER["PHP_SELF"] . "?do=Manage&userid=" . $userid . "&sort=categories" ; ?>">categories</a></td>
                            <td><a href="<?php echo $_SERVER["PHP_SELF"] . "?do=Manage&userid=" . $userid . "&sort=full_name" ; ?>">member</a></td>
                            <td>control</td>
                        </tr>
                            <?php
                                foreach($rows as $row){
                                    echo "<tr>";
                                        echo "<td>";?> <img src="uploads/<?=$row['image']?>" class="card-img-top" alt="..." width="70px" height="60px"> <?php echo "</td>";
                                        echo "<td>"; echo $row['name'];echo "</td>";
                                        echo "<td>"; echo $row['description'];echo "</td>";
                                        echo "<td>"; echo '$' . $row['price'];echo "</td>";
                                        echo "<td>"; echo $row['add_date'];echo "</td>";
                                        echo "<td>"; echo $row['country_made'];echo "</td>";
                                        echo "<td>"; echo $row['categories'];echo "</td>";
                                        echo "<td>"; echo $row['full_name'];echo "</td>";
                                        echo "<td>"; ?>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="?do=Delete&userid=<?php echo $userid; ?>&itemid=<?php echo $row['item_id'];?>" type="button" class="confirm btn btn-danger"><i class="fas fa-cart-arrow-down"></i>delete</a>
                                                <a href="?do=Edit&userid=<?php echo $userid; ?>&itemid=<?php echo $row['item_id'];?>" type="button" class="btn btn-secondary"><i class="fas fa-edit"></i> edit</a>
                                                <?php if($row['approve'] == 0){
                                                    echo '<a href="?do=Accept&userid=' . $userid .'&itemid=' . $row['item_id'] . '" type="button" class="btn btn-success"><i class="fas fa-thumbs-up"></i> aprrove</a>';
                                                }?>
                                                
                                            </div>
                                        <?php 
                                        echo  "</td>";
                                    echo "</tr>";    
                                }
                            ?>
                    </table>
                </div>
                <a href="?do=Add&userid=<?php echo $userid; ?>"class="btn btn-primary">
                    <i class="fas fa-cart-plus"></i> add new item
                </a>
            </div>
<?php 
        
    }elseif ($do == "Edit"){ // Edit page for users to edit his profile
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        $admin = query("group_id","users_shop","user_id",$userid);
        $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;

        $stmt = $conn->prepare("SELECT * FROM items WHERE item_id = ?");
        $stmt->execute(array($itemid));
        $count = $stmt->rowCount();
        $dataBase = $stmt->fetch(PDO::FETCH_BOTH);

        $memberId = $dataBase['member_id'];
        $dataBase2 = query("full_name","users_shop","user_id",$memberId);

        $cateId = $dataBase['cat_id'];
        $dataBase3 = query("name","categories","id",$cateId);

        $imgSrc = query("image","items","item_id",$itemid);

        if($count > 0){       ?>
    
        <div class="container text-center">
            <h1>edit items</h1>
        </div> 

        <form class="login edit" action="?do=Update&userid=<?php echo $userid; ?>&itemid=<?php echo $dataBase['item_id']; ?>" method="POST" enctype="multipart/form-data">

            <div class="titel-registor">
                <h4>edit items</h4> <i class="fas fa-pen"></i>
            </div>

            <div class="box">
                <i class="fas fa-sitemap"></i>
                <input  type="text" name="item-name" class="form-control"
                        placeholder=" name of the item" 
                        value="<?php print_r($dataBase['name']) ?>"  required />
            </div>

            <div class="box">
                <i class="fas fa-audio-description"></i>
                <input  type="text" name="description" class="form-control"
                        placeholder="description of item"
                        value="<?php print_r($dataBase['description']) ?>" required />
            </div>

            <div class="box">
                <i class="fas fa-dollar-sign"></i>
                <input  type="text" name="price" class="form-control"
                        placeholder="price of the item"
                        value="<?php print_r($dataBase['price']) ?>" required />
            </div>

            <div class="box">
                <i class="fas fa-globe-americas"></i>
                <input  type="text" name="country" class="form-control"
                        placeholder="country of made"
                        value="<?php print_r($dataBase['country_made']); ?>" required />
            </div>
            
            <div class="box">
            <i class="far fa-question-circle"></i>
                <select class="custom-select" name ="item-status">
                    <option value="<?php print_r($dataBase['status']); ?>"  selected><?php print_r($dataBase['status']); ?></option>
                    <option value="new">new</option>
                    <option value="like new">like new</option>
                    <option value="used">used</option>
                    <option value="very old">very old</option>
                </select>
            </div>
            <div class="box">
            <i class="fas fa-users"></i>
                <select class="custom-select" name ="item-member">
                    <option value="<?php print_r($dataBase['member_id']); ?>"  selected> <?php print_r($dataBase2); ?></option>
                    <?php
                        try{
                            $stmt = $conn->prepare("SELECT * FROM users_shop WHERE group_id = 1 ORDER BY full_name");
                            $stmt->execute();
                            $dataBase1 = $stmt->fetchAll(PDO::FETCH_BOTH);
                        
                            foreach($dataBase1 as $val){
                                echo '<option value="' . $val["user_id"] . '">' . $val["full_name"] . '</option>';
                            }
                        }
                        catch(PDOException $e){
                            echo $e->getMessage();
    
                        }
                    ?>
                </select>
            </div>
            <div class="box">
                <i class="fab fa-delicious"></i>
                <select class="custom-select" name ="item-cate">
                    <option value="<?php print_r($dataBase['cat_id']); ?>"  selected> <?php print_r($dataBase3); ?></option>
                <?php
                    try{
                        $stmt = $conn->prepare("SELECT * FROM categories ORDER BY name");
                        $stmt->execute();
                        $dataBase = $stmt->fetchAll(PDO::FETCH_BOTH);
                    
                        foreach($dataBase as $val){
                            echo '<option value="' . $val["id"] . '">' . $val["name"] . '</option>';
                        }
                    }
                    catch(PDOException $e){
                        echo $e->getMessage();

                    }
                ?>
                </select>
            </div>
            <div class="box ">
                <div class="custom-file upload-img">
                    <input type="file" name="img-item" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" onchange="showImg();"  hidden>
                    <i class="fas fa-cloud-upload-alt uploading-icon display"></i>
                    <img id="img-upload" class="" style="cursor: pointer;" src="uploads/<?=$imgSrc?>"  width="100%" height ="100%">
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
            <input type="submit" class="btn btn-primary btn-block" name="send" value="save" />
        </form>
        

    <?php }
    }elseif($do == "Update"){  // update page for admins and users 
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        $admin = query("group_id","users_shop","user_id",$userid);
        $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;
        $oldImg = query("image","items","item_id",$itemid );
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            $itemName       = filter_var($_POST['item-name'],FILTER_SANITIZE_STRING);
            $itemDescrip    = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
            $price          = filter_var($_POST['price'],FILTER_SANITIZE_STRING);
            $country        = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
            $itemStatus     = $_POST['item-status'];
            $itemMember     = $_POST['item-member'];
            $itemCate       = $_POST['item-cate'];

            $imgItem        = (!empty($_FILES['img-item']['name']))? $_FILES['img-item'] : NULL; 
            
            if($itemStatus == '0'){
                $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry you should select status";
            }
            if(!preg_match("/[a-zA-Z][\w]+$/",$itemName)){
                $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry items name should contain letters";
            }
            if(!preg_match("/[a-zA-Z][\w]+$/",$itemDescrip)){
                $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry items description should contain letters";
            }
            
            if($imgItem != NULL){

                extract($imgItem);
                $imgType        = pathinfo($name,PATHINFO_EXTENSION);

                $imgAllowExtension = array("jpeg","jpg","png","gif");

                if($size > 4194304){
                    $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry this img greater than 4MG";
                }

                if(!empty($name) && !in_array($imgType,$imgAllowExtension)){
                    $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry file shall by image";
                }
                if(empty($requestStat)){
                    $random = rand(0,10000000);
                    $endNameImg = $random . "_" . $name;
                        
                    unlink("uploads/$oldImg");
        
                    move_uploaded_file($tmp_name,"uploads/$endNameImg");
                }
            }else{
            $endNameImg = $oldImg;
            }
        
            if(empty($requestStat)){
                try{
                    $stmt = $conn->prepare("UPDATE `items` SET `name` = ?, `description` = ?, `price` = ?, `country_made` = ?, `image` = ? ,`status` = ?, `cat_id` = ?, `member_id` = ? , `approve` = 0 WHERE `items`.`item_id` = ?;");
        
                    $stmt->execute(array($itemName ,$itemDescrip,$price,$country,$endNameImg ,$itemStatus,$itemCate,$itemMember,$itemid ));
                    $Mas = $stmt->rowCount() . " recoud updated ";
                    $home = "items.php?do=Manage&userid=" . $userid;
                    // $home = $_SERVER['HTTP_REFERER'];
                    returnHome($Mas,$home,1);
                }
                catch(PDOException $e){
                    echo $e->getMassage;
                }
            }else{
                $Mas = $requestStat ;
                $home = $_SERVER['HTTP_REFERER'];
                returnHome($Mas,$home);
            }
        }

    }elseif($do == "Delete"){  // Delete page for admins to delete users
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;
        try{

            $admin = query("group_id","users_shop","user_id",$userid);
            $oldImg = query("image","items","item_id",$itemid );
            unlink("uploads/$oldImg");
            
            if($admin == 1){
                $stmt = $conn->prepare("DELETE FROM items WHERE item_id = ?");
                $stmt->execute(array($itemid));

                

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
    }elseif($do == "Add"){ // Add page for admins to Add Admins
        $requestStat = array();
        $dataSent = array();
        
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        $admin = query("group_id","users_shop","user_id",$userid);

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $itemName       = filter_var($_POST['item-name'],FILTER_SANITIZE_STRING);
            $itemDescrip    = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
            $price          = filter_var($_POST['price'],FILTER_SANITIZE_STRING);
            $country        = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
            $itemStatus     = $_POST['item-status'];
            $itemMember     = $_POST['item-member'];
            $itemCate       = $_POST['item-cate'];
            $imgItem        = $_FILES['img-item'];

            

            extract($imgItem);

            $imgType        = pathinfo($name,PATHINFO_EXTENSION);

            $imgAllowExtension = array("jpeg","jpg","png","gif");

            if($itemStatus == '0'){
                $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry you should select status";
            }
            if(!preg_match("/[a-zA-Z][\w]+$/",$itemName)){
                $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry items name should contain letters";
            }
            if(!preg_match("/[a-zA-Z][\w]+$/",$itemDescrip)){
                $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry items description should contain letters";
            }
            if($size > 4194304){
                $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry this img greater than 4MG";
            }
            if(!empty($name) && !in_array($imgType,$imgAllowExtension)){
                $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry file shall by image";
            }
            
            if(empty($requestStat)){
                $random = rand(0,10000000);
                $endNameImg = $random . "_" . $name;
                move_uploaded_file($tmp_name,"uploads/$endNameImg");


                try{ 

                    
                    $stmt = $conn->prepare("INSERT INTO
                                                    items (	name , description , price, country_made,image,
                                                    status , add_date , cat_id , member_id ) 
                                            VALUES (:name , :des ,:pri ,:coun ,:img ,:stat , NOW() , :cat , :member )");
                    $stmt->execute(array(
                        ":name"     => $itemName,
                        ":des"      => $itemDescrip,
                        ":pri"      => $price,
                        ":coun"     => $country,
                        ":img"      => $endNameImg,
                        ":stat"     => $itemStatus,
                        ":cat"      => $itemCate,
                        ":member"   => $itemMember 
                    ));
                    $dataSent[] = '<i class="fas fa-check-circle"></i><span style="color:#00ff08"> addation item has been done</span>';
    
                }catch(PDOException $e){
                    $erroCreateAccount = $e->getMessage();
                    $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry you can't add this item ";
                }
            }

        }
        
        ?>

        <div class="container text-center">
            <h1>add items</h1>
        </div> 

        <form class="login edit" action="?do=Add&userid=<?php echo $userid; ?>" method="POST" enctype="multipart/form-data">

            <div class="titel-registor">
                <h4>add new items</h4> <i class="fas fa-pen"></i>
            </div>

            <div class="box">
                <i class="fas fa-sitemap"></i>
                <input  type="text" name="item-name" class="form-control"
                        placeholder=" name of the item" 
                        value="<?php if(! empty($requestStat)){echo $itemName;} ?>"  required />
            </div>

            <div class="box">
                <i class="fas fa-audio-description"></i>
                <input  type="text" name="description" class="form-control"
                        placeholder="description of item"
                        value="<?php if(! empty($requestStat)){echo $itemDescrip;} ?>" required />
            </div>

            <div class="box">
                <i class="fas fa-dollar-sign"></i>
                <input  type="text" name="price" class="form-control"
                        placeholder="price of the item"
                        value="<?php if(! empty($requestStat)){echo $price;} ?>" required />
            </div>

            <div class="box">
                <i class="fas fa-globe-americas"></i>
                <input  type="text" name="country" class="form-control"
                        placeholder="country of made"
                        value="<?php if(! empty($requestStat)){echo $country;} ?>" required />
            </div>
            
            <div class="box">
            <i class="far fa-question-circle"></i>
                <select class="custom-select" name ="item-status">
                    <option value="0" disabled selected>status</option>
                    <option value="new">new</option>
                    <option value="like new">like new</option>
                    <option value="used">used</option>
                    <option value="very old">very old</option>
                </select>
            </div>
            <div class="box">
            <i class="fas fa-users"></i>
                <select class="custom-select" name ="item-member">
                    <option value="0" disabled selected> <?php if(! empty($requestStat)){echo $itemMember;}else{echo "members";} ?></option>
                    <?php
                        try{
                            $stmt = $conn->prepare("SELECT * FROM users_shop WHERE group_id = 1 ORDER BY full_name");
                            $stmt->execute();
                            $dataBase = $stmt->fetchAll(PDO::FETCH_BOTH);
                        
                            foreach($dataBase as $val){
                                echo '<option value="' . $val["user_id"] . '">' . $val["full_name"] . '</option>';
                            }
                        }
                        catch(PDOException $e){
                            echo $e->getMessage();
    
                        }
                    ?>
                </select>
            </div>
            <div class="box">
            <i class="fab fa-delicious"></i>
                <select class="custom-select" name ="item-cate">
                    <option value="0" disabled selected> <?php if(! empty($requestStat)){echo $itemCate;}else{echo "categories";} ?></option>
                <?php
                    try{
                        $stmt = $conn->prepare("SELECT * FROM categories ORDER BY name");
                        $stmt->execute();
                        $dataBase = $stmt->fetchAll(PDO::FETCH_BOTH);
                    
                        foreach($dataBase as $val){
                            echo '<option value="' . $val["id"] . '">' . $val["name"] . '</option>';
                        }
                    }
                    catch(PDOException $e){
                        echo $e->getMessage();

                    }
                ?>
                </select>
            </div>
            <div class="box ">
                <div class="custom-file upload-img">
                    <input type="file" name="img-item" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" onchange="showImg();" required="required" hidden>
                    <i class="fas fa-cloud-upload-alt uploading-icon "></i>
                    <img id="img-upload" class="display" style="cursor: pointer;" src=""  width="100%" height ="100%">
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
            <input type="submit" class="btn btn-primary btn-block" name="send" value="submit" />
        </form>
            <?php    
    }elseif($do == 'Accept'){ // accept page 

        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;
        try{

            $admin = query("group_id","users_shop","user_id",$userid);
            
            if($admin == 1){
                $stmt = $conn->prepare("UPDATE `items` SET `approve` = '1' WHERE `items`.`item_id` = ?;");
                $stmt->execute(array($itemid));

                $Mas = $stmt->rowCount() . " recorde accepted done";
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
    }

    include $tpl . "/footer.inc.php";
}else{
    header("location: index.php");
    exit();
}
    ob_end_flush();
?>



