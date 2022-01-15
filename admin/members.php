<?php 

ob_start();

session_start();
$titlePage = "Members";
    

    if(isset($_SESSION['username'])) {

        include 'init.php';
        // errorID();

        $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

        if($do == "Manage"){ // manage page for admins to delete users

            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

            $admin = query("group_id","users_shop","user_id",$userid);
            $stmt = $conn->prepare("SELECT * FROM users_shop WHERE group_id = 1");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            ?>

                <div class="container">
                    <h1>admins page</h1>

                    <div class="table-responsive">
                        <table class="table table-dark table-striped">
                            <tr>
                                <td>#ID</td>
                                <td>full name</td>
                                <td>email</td>
                                <td>date</td>
                                <td>cell phone</td>
                                <td>control</td>
                            </tr>
                                <?php
                                    foreach($rows as $row){
                                        echo "<tr>";
                                            echo "<td>"; echo $row['user_id'];echo "</td>";
                                            echo "<td>"; echo $row['full_name'];echo "</td>";
                                            echo "<td>"; echo $row['Email'];echo "</td>";
                                            echo "<td>"; echo $row['reg_date'];echo "</td>";
                                            echo "<td>"; echo $row['cell_phone'];echo "</td>";
                                            echo "<td>"; ?>
                                                <a href="?do=Delete&userid=<?php echo $row['user_id']; ?>"class="btn btn-primary confirm">
                                                    delete
                                                </a>
                                            <?php 
                                            echo  "</td>";
                                        echo "</tr>";    
                                    }
                                ?>
                        </table>
                    </div>
                    <a href="?do=Add&userid=<?php echo $userid; ?>"class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> add new admin
                    </a>
                </div>
<?php 
            

        }elseif ($do == "Edit"){ // Edit page for users to edit his profile
            
            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

            $sql = "SELECT * FROM users_shop WHERE user_id = :id AND full_name = :name ";

            $stmt =  $conn->prepare($sql);
            $stmt->bindParam(":id",$userid);
            $stmt->bindParam(":name",$_SESSION['username']);
            $stmt->execute();
            $count = $stmt->rowCount();
            $dataBase = $stmt->fetch(PDO::FETCH_BOTH);
            
            if($count > 0){    ?>

                <div class="container text-center">
                    <h1>edit page</h1>
                </div>  
                <form class="login edit" action="?do=Update&userid=<?php echo $userid; ?>" method="POST">  
                    <div class="titel-registor">
                        <h4>edit profile</h4> <i class="fas fa-pen"></i>
                    </div>
                    <div class="first-last-name">
                        <div class="box first">
                            <i class="fas fa-user"></i>
                            <input  type="text" name="firstname" class="form-control" placeholder="first name" autocomplete="off" value="<?php print_r($dataBase['first_name']); ?>"  required />
                        </div>
                        <div class="box last">
                            <i class="fas fa-user"></i>
                            <input  type="text" name="lastname" class="form-control" placeholder="last name" autocomplete="off" value="<?php print_r($dataBase['last_name']); ?>" required />
                        </div>
                    </div>

                    <div class="box">
                        <i class="fas fa-user"></i>
                        <input  type="text" name="user" class="form-control" placeholder="Username" minlength="8" autocomplete="off" value="<?php print_r($dataBase['user_name']) ; ?>" required />
                    </div>
                    <div class="box">
                        <i class="fas fa-key"></i>
                        <input  type="password" name="old-pass" class="form-control"
                                placeholder="type old password" autocomplete="new-password"/>
                    </div>
                    <div class="box">
                        <i class="fas fa-key"></i>
                        <input  type="password" name="new-pass" class="form-control" minlength="8"
                                placeholder="type new password" autocomplete="new-password" />
                    </div>
                    <div class="box">
                        <i class="far fa-envelope"></i>
                        <input  type="email" name="email" class="form-control"
                                placeholder="email" autocomplete="off" value="<?php print_r($dataBase['Email']) ; ?>" required />
                    </div>
                    <div class="alert alert-secondary" role="alert"></div>
                    <input type="submit" class="btn btn-primary btn-block" name="send" value="save" />
                </form>

            <?php    
            }else{
                $Mas = "Sorry that is not your ID";
                $home = "home.php";
                returnHome($Mas,$home);
            }

        }elseif($do == "Update"){  // update page for admins and users 
            if($_SERVER['REQUEST_METHOD'] == "POST"){

                $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

                $fullName   = $_POST['firstname'] . " " . $_POST['lastname'];
                $firstName  = $_POST['firstname'];
                $lastName   = $_POST['lastname'];
                $userName   = $_POST['user'];
                $newPass    = $_POST['new-pass'];
                $shalpass   = sha1($newPass);
                $email      = $_POST['email'];

                //extract($_POST);
                
                $sql = "SELECT password FROM users_shop WHERE full_name = :name ";

                $stmt =  $conn->prepare($sql);
                $stmt->bindParam(":name",$_SESSION['username']);
                $stmt->execute();
                $count = $stmt->rowCount();
                $dataBase = $stmt->fetch();

                if(sha1($_POST['old-pass'])  == $dataBase['password']){

                    $stmt = $conn->prepare("UPDATE users_shop SET first_name = ? 
                                            , last_name = ? , user_name = ?
                                            , password = ? , Email = ? ,
                                            full_name = ? WHERE user_id = ? ");

                    $stmt->execute(array($firstName,$lastName,$userName,$shalpass,$email,$fullName,$userid));

                    $_SESSION['username'] = $fullName;
                    
                    $Mas = $stmt->rowCount() . " recoud updated ";
                    $home = $_SERVER['HTTP_REFERER'];

                    returnHome($Mas,$home,1);

                }elseif(empty($_POST['old-pass'])){
                    try{
                        $stmt = $conn->prepare("UPDATE users_shop SET first_name = ? ,
                        last_name = ? , user_name = ? , Email = ? , full_name = ? WHERE user_id = ?");
                        
                        $stmt->execute(array($firstName,$lastName,$userName,$email,$fullName,$userid));

                        $_SESSION['username'] = $fullName;

                        $Mas = $stmt->rowCount() . " recoud updated ";
                        $home = $_SERVER['HTTP_REFERER'];

                        returnHome($Mas,$home,1);

                    }catch(PDOException $e){

                        $Mas = "sorry you can't update this username !";

                        returnHome($Mas,$_SERVER['HTTP_REFERER']);
                    }

                }else{

                    $Mas = "old password not correct , please enter a correct password !";
                    $home = $_SERVER['HTTP_REFERER'];
                    returnHome($Mas,$home);
                }      
            }else{
                $Mas = "sorry you can't open this page directery !";
                $home = "home.php";
                returnHome($Mas,$home);
            }
        }elseif($do == "Delete"){  // Delete page for admins to delete users
            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
            try{

                $admin = query("group_id","users_shop","user_id",$userid);
                
                if($admin == 1){
                    $Mas = "Sorry that is admin you can't delete admin";
                    $home = "home.php";
                    returnHome($Mas,$home);
                }else{
                    $stmt = $conn->prepare("DELETE FROM users_shop WHERE user_id = ?");
                    $stmt->execute(array($userid));

                    $Mas = $stmt->rowCount() . " recorde Deleted done";
                    $home = $_SERVER['HTTP_REFERER'];
                    returnHome($Mas,$home,1);
                }
            }catch(PDOException $e){
                echo $e->getMassage;
            }
        }elseif($do == "Add"){ // Add page for admins to Add Admins

            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

            $admin = query("group_id","users_shop","user_id",$userid);

            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $addFirstName   = filter_var($_POST['firstname'],FILTER_SANITIZE_STRING);
                $addLastName    = filter_var($_POST['lastname'],FILTER_SANITIZE_STRING);
                $addFullName    = $addFirstName . " " . $addLastName;
                $addUser        = filter_var($_POST['user'],FILTER_SANITIZE_STRING);
                $addPass        =  filter_var($_POST['pass'],FILTER_SANITIZE_STRING);
                $testPass       = filter_var($_POST['test-pass'],FILTER_SANITIZE_STRING);
                $addEmail       = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
        
                $erro = "can't add that username or any proplem in your password";
                
                
            }


            if($admin == 1){       ?>

                <div class="container text-center">
                    <h1>add admins</h1>
                </div> 
                <form class="login edit" action="?do=Add&userid=<?php echo $userid; ?>" method="POST">

                <div class="titel-registor">
                    <h4>add new admin</h4> <i class="fas fa-pen"></i>
                </div>

                <div class="first-last-name">
                    <div class="box first">
                        <i class="fas fa-user"></i>
                        <input  type="text" name="firstname" class="form-control" placeholder="first name" autocomplete="off" required />
                    </div>

                    <div class="box last">
                        <i class="fas fa-user"></i>
                        <input  type="text" name="lastname" class="form-control" placeholder="last name" autocomplete="off" required />
                    </div>
                </div>

                <div class="box">
                    <i class="fas fa-user"></i>
                    <input  type="text" name="user" class="form-control" placeholder="Username" autocomplete="off" minlength="8"  required />
                </div>

                <div class="box">
                            <i class="far fa-envelope"></i>
                            <input  type="email" name="email" class="form-control"
                                    placeholder="email" autocomplete="off" required />
                </div>

                <div class="box">
                    <i class="fas fa-key"></i>
                    <input  type="password" name="pass" minlength="8" class="form-control"
                            placeholder="password" autocomplete="new-password" required />
                </div>

                <div class="box">
                    <i class="fas fa-unlock"></i>
                    <input  type="password" name="test-pass" class="form-control"
                            placeholder="type password again" autocomplete="new-password" required />
                </div>
                
                <div class="alert alert-secondary" role="alert">
                        <?php
                            if(isset($addUser)){
                                if($addPass != $testPass) {
                                    echo "sorry you don't write your password correct";
                                }else{
                                    echo "";
                                    try{ 
                                        $sql = "INSERT INTO users_shop (user_name,password,Email,first_name,last_name,full_name,group_id,reg_date) VALUES (:user , :pass ,:email ,:fname,:lname,:fullName,1,NOW())";
                                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bindParam(":user",$addUser);
                                        $stmt->bindParam(":pass",$addPass);
                                        $stmt->bindParam(":email",$addEmail);
                                        $stmt->bindParam(":fname",$addFirstName);
                                        $stmt->bindParam(":lname",$addLastName);
                                        $stmt->bindParam(":fullName",$addFullName);
                                        $stmt->execute();

                                        echo "<div style='color:#00ff08'>A new member has been added</div>";

                                    }
                                    catch(PDOException $e){
                                        $erroCreateAccount = $e->getMessage();
                                        echo $erro;
                                    }
                                }
                            } 
                        ?>
                    </div>
                    <input type="submit" class="btn btn-primary btn-block" name="send" value="submit" />

                </form>
                    <?php
            }
        }elseif($do == 'Accept'){

            $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
            try{
            $stmt = $conn->prepare("UPDATE users_shop SET group_id = 1 WHERE user_id = ?");
            $stmt->execute(array($userid));

            $Mas = $stmt->rowCount() . " accepted admin done";
            $home = $_SERVER['HTTP_REFERER'];
            returnHome($Mas,$home,1);

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