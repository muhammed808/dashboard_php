<?php 


ob_start();
session_start();

$titlePage = "categories";

if(isset($_SESSION['username'])) {

    include 'init.php';
    // errorID();

    $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

    if($do == "Manage"){ // manage page for admins to delete users

        $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';
        
    }elseif ($do == "Edit"){ // Edit page for users to edit his profile
        echo "edit";
    }elseif($do == "Update"){  // update page for admins and users 
        echo "Update!";
    }elseif($do == "Delete"){  // Delete page for admins to delete users
        echo "Delete!";
    }elseif($do == "Add"){ // Add page for admins to Add Admins
        echo "Add!";
    }elseif($do == 'Accept'){ // accept page 
        echo "accept !";
    }

    include $tpl . "/footer.inc.php";
}else{
    header("location: index.php");
    exit();
}
    ob_end_flush();
?>



