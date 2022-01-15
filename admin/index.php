<?php
    session_start();
    
    // session_unset();
    // session_destroy();
    $noNavbar = "";
    $errorMag = "";
    $titlePage = "Login";
    include 'init.php';
    
//$sql = "INSERT INTO users (user_name,password,Email,first_name,last_name) VALUES (:user , :pass ,:email ,:fname,:lname)";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $shalpass = sha1($password);
        try{
        $sql = "SELECT 
                       user_id, user_name, password, full_name , group_id
                FROM 
                    users_shop 
                WHERE 
                    user_name = :name 
                AND 
                    password = :pass
                AND 
                    group_id = 1";

        $stmt =  $conn->prepare($sql);
        $stmt->bindParam(":name",$username);
        $stmt->bindParam(":pass",$shalpass);
        $stmt->execute();
        $count = $stmt->rowCount();
        $dataBase = $stmt->fetch();
        

        }
        catch(PDOException $e){
            echo "ERRO! " . $e->getMessage();
        }

        

        if($count > 0){            
            $_SESSION['username'] = $dataBase['full_name'];
            $_SESSION['id'] = $dataBase['user_id'];
            header("location: home.php");
            exit();
        }
        else{
            $errorMag = "sorry your username or your password isn't correct !";
        }
    }
?>
        <form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <i class="fas fa-user-circle fa-5x"></i>
            <div class="text-center">
                <h4>admin login</h4>
            </div>
            
            
            <div class="box">
                <i class="fas fa-user"></i>
                <input  type="text" name="user" class="form-control" placeholder="Username" autocomplete="off" />
            </div>
            <div class="box">
                <i class="fas fa-key"></i>
                <input  type="password" name="pass" class="form-control"
                        placeholder="password" autocomplete="new-password" />
            </div>
            <div class="alert alert-secondary" role="alert">
                <?php 
                    echo $errorMag;
                ?>
            </div>
            <input type="submit" class="btn btn-primary btn-block" name="send" value="login" />
            <span class="text-center">you are not admin? <a href="new account.php">request to be admin</a> </span> 
        </form>
<?php

    include $tpl . "/footer.inc.php";
?>

