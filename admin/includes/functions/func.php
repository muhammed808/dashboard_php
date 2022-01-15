<?php 


// function to print title page

    global $title;

$title = (isset($titlePage)) ? $titlePage : "defulte";

// function to control errors 

function returnHome($Mas,$home ,$seconds = 5){

    if(is_array($Mas)){
        echo "<div class='container'>";
        echo "<div class='alert alert-info'style='color:red' role='alert'>";
        foreach($Mas as $key){
            
                print_r($key); echo "<br>"; 
        }
        echo "</div>";
        echo "<div class='alert alert-warning' role='alert'>you will return at $seconds second</div>";
        echo "</div>";
    }else{
        echo "<div class='container'>";
            echo "<div class='alert alert-info' role='alert'>$Mas</div>";

            echo "<div class='alert alert-warning' role='alert'>you will return at $seconds second</div>";
        echo "</div>";
    }

    header("refresh: $seconds; url= $home ");

    exit();
}

// function to control query in database

function query($selection,$table,$column,$val){

    global $conn;

    $stmt = $conn->prepare("SELECT $selection FROM $table WHERE $column = $val");
    
    $stmt->execute();

    $dataBase = $stmt->fetch();

    return $dataBase[$selection];
}
function checkItem($selection,$table,$val){

    global $conn;

    $stmt = $conn->prepare("SELECT $selection FROM $table WHERE $selection = ?");
    $stmt->execute(array($val));
    $count = $stmt->rowCount();

    return $count;
}

// function to contole user_id in link

// function errorID(){
//     $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

//     global $conn;

//     $stmt = $conn->prepare("SELECT user_id , full_name , group_id FROM users_shop WHERE user_id = ? AND full_name = ? ");
//     $stmt->execute(array($userid,$_SESSION['username']));
//     $count = $stmt->rowCount();

//     $stmt2 = $conn->prepare("SELECT group_id FROM users_shop WHERE full_name = ?");
//     $stmt2->execute(array($_SESSION['username']));
//     $permation = $stmt2->fetch(PDO::FETCH_BOTH);

//     if($count != 1){
//         if($permation[0] != 1){
//             echo "error man";
//             // header("location: home.php");
//             // exit(); 
//         }
//     }
// }

