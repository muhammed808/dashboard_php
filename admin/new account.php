<?php

    $noNavbar = "";
    $titlePage = "registration";

    include 'init.php';
    $requestStat = array();
    $dataSent = array();

    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $addFirstName   = filter_var($_POST['firstname'],FILTER_SANITIZE_STRING);
        $addLastName    = filter_var($_POST['lastname'],FILTER_SANITIZE_STRING);
        $addFullName    = $addFirstName . " " . $addLastName;
        $addUser        = filter_var($_POST['user'],FILTER_SANITIZE_STRING);
        $addPass        =  filter_var($_POST['pass'],FILTER_SANITIZE_STRING);
        $shalpass       = sha1($addPass);
        $testPass       = filter_var($_POST['test-pass'],FILTER_SANITIZE_STRING);
        $addEmail       = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
        $cell           = filter_var($_POST['cell'],FILTER_SANITIZE_NUMBER_INT);
        $erro = "can't add that username or any proplem in your password";
        
        if($addPass != $testPass) {
            $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry you don't write your password correct";
        }
        if((is_numeric($cell)) == 0){
            $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> sorry proplem in your cell phone";
        }
        if(empty($requestStat)){
            try{ 
                $sql = "INSERT INTO users_shop (user_name,password,Email,first_name,last_name,full_name,group_id,reg_date,cell_phone) VALUES (:user , :pass ,:email ,:fname,:lname,:fullName,1,NOW(),:cell)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":user",$addUser);
                $stmt->bindParam(":pass",$shalpass);
                $stmt->bindParam(":email",$addEmail);
                $stmt->bindParam(":fname",$addFirstName);
                $stmt->bindParam(":lname",$addLastName);
                $stmt->bindParam(":fullName",$addFullName);
                $stmt->bindParam(":cell",$cell);
                $stmt->execute();
                $dataSent[] = '<i class="fas fa-check-circle"></i><span style="color:#00ff08"> your request has been sent you will login now</span>';
                $sql = "SELECT user_id FROM users_shop where user_name = :user";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute(array(":user" => $addUser));

                $userId = $stmt->fetch();

                header("location: index.php");
                exit();

            }catch(PDOException $e){
                $erroCreateAccount = $e->getMessage();
                $requestStat[] = "<i class='fas fa-exclamation-triangle'></i> $erro ";
            }
        }
    }
?>

<form class="login creat" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

    <div class="titel-registor">
        <h4>registration to be admin</h4> <i class="fas fa-pen"></i>   
    </div>
    <div class="first-last-name">
        <div class="box first">
            <i class="fas fa-user"></i>
            <input  type="text" name="firstname" class="form-control" placeholder="first name" autocomplete="off" value="<?php if(! empty($requestStat)){echo $addFirstName;} ?>" required />
        </div>
        <div class="box last">
            <i class="fas fa-user"></i>
            <input  type="text" name="lastname" class="form-control" placeholder="last name" autocomplete="off" value="<?php if(! empty($requestStat)){echo $addLastName;} ?>" required />
        </div>
    </div>
    
    <div class="box">
        <i class="fas fa-user"></i>
        <input  type="text" name="user" class="form-control" placeholder="Username" autocomplete="off" minlength="8" value="<?php if(! empty($requestStat)){echo $addUser;} ?>"  required />
    </div>
    <div class="box">
                <i class="far fa-envelope"></i>
                <input  type="email" name="email" class="form-control"
                        placeholder="email" autocomplete="off"
                        value="<?php if(! empty($requestStat)){echo $addEmail;} ?>" required />
    </div>
    <div class="box">
                <i class="fas fa-mobile-alt"></i>
                <input  type="text" name="cell" class="form-control"
                        placeholder="cell phone" autocomplete="off"
                        value="<?php if(! empty($requestStat)){echo $cell;} ?>" required />
    </div>
    <div class="box">
        <i class="fas fa-key"></i>
        <input  type="password" name="pass" minlength="8" class="form-control"
                placeholder="password" autocomplete="new-password"
                value="<?php if(! empty($requestStat)){echo $addPass;} ?>" required />
    </div>
    <div class="box">
        <i class="fas fa-unlock"></i>
        <input  type="password" name="test-pass" class="form-control"
                placeholder="type password again" autocomplete="new-password"
                value="<?php if(! empty($requestStat)){echo $testPass;} ?>" required />
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
    <span class="text-center">i have already account <a href="index.php">admin login</a> </span>       
</form>


<?php

    include $tpl . "/footer.inc.php";
?>
