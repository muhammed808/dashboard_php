<?php 

ob_start();
session_start();
$titlePage = "Home";



    if(isset($_SESSION['username'])) {

        include 'init.php';
        $userid = $_SESSION['id'];
        ?>

        <div class="container home-stats text-center">
            <h1>dashboard page</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-memebers">
                        <i class="fa fa-users"></i>
                        <div class="info">
                           <div class="title">total members</div> 
                            <span>
                                <?php
                                    $stmt = $conn->prepare("SELECT * FROM users_shop");
                                    $stmt->execute();
                                    echo $stmt->rowCount();
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat st-pending">   
                        <i class="fab fa-delicious"></i>
                        <div class="info">
                           <div class="title">total categories</div> 
                            <span>
                                <?php
                                    $stmt=$conn->prepare("SELECT * FROM categories");
                                    $stmt->execute();
                                    echo $stmt->rowCount();
                                ?>
                            </span>
                            </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fas fa-shopping-cart"></i>
                        <div class="info">
                            <div class="title">total items</div> 
                            <span>
                                <?php
                                    $stmt=$conn->prepare("SELECT * FROM items WHERE approve = 1");
                                    $stmt->execute();
                                    echo $stmt->rowCount();
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat st-comments"> 
                        <div class="info">
                            <i class="fa fa-comments"></i>
                            <div class="title">comments</div> 
                            <span>
                                150
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container latest">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <h5 class="card-header">
                            <i class="fa fa-users"></i> latest 5 Registration 
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </h5>
                        <div class="card-body">
                            <?php
                                $stmt = $conn->prepare("SELECT user_id , user_name, Email ,full_name , reg_date , cell_phone FROM users_shop WHERE group_id = 1 ORDER BY user_id DESC LIMIT 5 ");
                                $stmt->execute();
                                $lastMember = $stmt->fetchAll();
                                ?>
                            <div class="accordion" id="accordionExample">
                                <?php

                                    if(isset($lastMember[0]['full_name'])){  ?>
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        <?php echo '<i class="fas fa-user-clock"></i> '; print_r($lastMember[0]['full_name']); ?>
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                        <i class="far fa-eye"></i> view
                                                    </a>
                                                    <div class="collapse" id="collapseExample">
                                                        <div class="card card-body">
                                                            <?php

                                                            foreach($lastMember[0] as $key => $val){
                                                                
                                                                echo '<h6><span class="data">' . $key .  '</span> : ' .  $val . '</h6>'; 
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <a href="members.php?do=Accept&userid=<?php print_r($lastMember[0]['user_id']); ?>" class="btn btn-success"><i class="fas fa-user-check"></i> accept</a>
                                                    <a href="members.php?do=Delete&userid=<?php print_r($lastMember[0]['user_id']); ?>" class="btn btn-danger confirm"><i class="fas fa-trash-alt"></i> refuse</a>
                                                </div>
                                            </div>
                                        </div>

                            <?php    }
                            if(isset($lastMember[1]['full_name'])){ ?>

                                <div class="card">
                                    <div class="card-header" id="headingTwo">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <?php echo '<i class="fas fa-user-clock"></i> ';print_r($lastMember[1]['full_name']); ?>
                                        </button>
                                    </h2>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <i class="far fa-eye"></i> view
                                            </a>
                                            <div class="collapse" id="collapseExample">
                                                <div class="card card-body">
                                                    <?php 
                                                        foreach($lastMember[1] as $key => $val){
                                                            
                                                            echo '<h6><span class="data">' . $key .  '</span> : ' .  $val . '</h6>'; 
                                                        }
                                                    ?>                                                
                                                </div>
                                            </div>
                                                <a href="members.php?do=Accept&userid=<?php print_r($lastMember[1]['user_id']); ?>" class="btn btn-success"><i class="fas fa-user-check"></i> accept</a>
                                                <a href="members.php?do=Delete&userid=<?php print_r($lastMember[1]['user_id']); ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i> refuse</a>
                                        </div>
                                    </div>
                                </div>
                        <?php    }
                        if(isset($lastMember[2]['full_name'])){ ?>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <?php echo '<i class="fas fa-user-clock"></i> '; print_r($lastMember[2]['full_name']); ?>
                                    </button>
                                </h2>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            <i class="far fa-eye"></i> view
                                        </a>
                                        <div class="collapse" id="collapseExample">
                                            <div class="card card-body">
                                                <?php
                                                            
                                                    foreach($lastMember[2] as $key => $val){
                                                        
                                                        echo '<h6><span class="data">' . $key .  '</span> : ' .  $val . '</h6>'; 
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                            <a href="members.php?do=Accept&userid=<?php print_r($lastMember[2]['user_id']); ?>" class="btn btn-success"><i class="fas fa-user-check"></i> accept</a>
                                            <a href="members.php?do=Delete&userid=<?php print_r($lastMember[2]['user_id']); ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i> refuse</a>
                                    </div>
                                </div>
                            </div>
                        <?php    }
                        if(isset($lastMember[3]['full_name'])){ ?>
                            <div class="card">
                                <div class="card-header" id="headingFour">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        <?php echo '<i class="fas fa-user-clock"></i> '; print_r($lastMember[3]['full_name']); ?>
                                    </button>
                                </h2>
                                </div>
                                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            <i class="far fa-eye"></i> view
                                        </a>
                                        <div class="collapse" id="collapseExample">
                                            <div class="card card-body">
                                                <?php
                                                            
                                                    foreach($lastMember[3] as $key => $val){
                                                        
                                                        echo '<h6><span class="data">' . $key .  '</span> : ' .  $val . '</h6>'; 
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <a href="members.php?do=Accept&userid=<?php print_r($lastMember[3]['user_id']); ?>" class="btn btn-success"><i class="fas fa-user-check"></i> accept</a>
                                        <a href="members.php?do=Delete&userid=<?php print_r($lastMember[3]['user_id']); ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i> refuse</a>
                                    </div>
                                </div>
                            </div>
                        <?php    }
                        if(isset($lastMember[4]['full_name'])){ ?>
                            <div class="card">
                                <div class="card-header" id="headingFive">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        <?php echo '<i class="fas fa-user-clock"></i> '; print_r($lastMember[4]['full_name']); ?>
                                    </button>
                                </h2>
                                </div>
                                <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            <i class="far fa-eye"></i> view
                                        </a>
                                        <div class="collapse" id="collapseExample">
                                            <div class="card card-body">
                                                <?php
                                                            
                                                    foreach($lastMember[4] as $key => $val){
                                                        
                                                        echo '<h6><span class="data">' . $key .  '</span> : ' .  $val . '</h6>'; 
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                        <a href="members.php?do=Accept&userid=<?php print_r($lastMember[4]['user_id']); ?>" class="btn btn-success"><i class="fas fa-user-check"></i> accept</a>
                                        <a href="members.php?do=Delete&userid=<?php print_r($lastMember[4]['user_id']); ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i> refuse</a>
                                    </div>
                                </div>
                            </div>
                    <?php     }
                            ?>   
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="card">
                        <h5 class="card-header">
                            <i class="fa fa-tag"></i> latest 5 items
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </h5>
                        <div class="card-body">
                            <div class="accordion" id="accordionExample">
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM items ORDER BY item_id DESC LIMIT 5");
                                $stmt->execute();
                                $allitems = $stmt->fetchAll();
                                
                                foreach($allitems as $key => $i){

                                    $arrNameItem = explode(" ",$i['name']);
                                    $nameItem = $arrNameItem[0]; ?>
                                <div class="card">
                                    <div class="card-header" id="heading<?php echo $nameItem; ?>">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?php echo $nameItem; ?>" aria-expanded="false" aria-controls="collapse<?php echo $nameItem; ?>">
                                                <i class="fas fa-shopping-cart"></i> <?php echo $i['name']; ?>
                                            </button>
                                        </h2>
                                    </div>
                        
                                    <div id="collapse<?php echo $nameItem; ?>" class="collapse" aria-labelledby="heading<?php echo $nameItem; ?>" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample<?php echo $nameItem; ?>" role="button" aria-expanded="false" aria-controls="collapseExample<?php echo $nameItem; ?>">
                                                <i class="far fa-eye"></i> description
                                            </a>
                                            <div class="collapse" id="collapseExample<?php echo $nameItem; ?>">
                                                <div class="card card-body">
                                                    <?php
                                                        echo "<h5>" . $i['description'] . "</h5>";
                                                    ?>
                                                </div>
                                            </div>
                                            <a href="items.php?do=Delete&userid=<?php echo $userid; ?>&itemid=<?php echo $i['item_id'];?>" type="button" class="confirm btn btn-danger"><i class="fas fa-cart-arrow-down"></i> delete</a>
                                            <a href="items.php?do=Edit&userid=<?php echo $userid; ?>&itemid=<?php echo $i['item_id'];?>" type="button" class="btn btn-secondary"><i class="fas fa-edit"></i> edit</a>
                                        </div>
                                    </div> 
                                </div> <?php    } ?>
                            </div>                
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php
        include $tpl . "/footer.inc.php";
    }else{
        header("location: index.php");
        exit();
    }

    ob_end_flush();

?>