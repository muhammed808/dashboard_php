<?php
    $all = queryAll("name","categories");
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <div class="logo">
                <span>abu</span>hassiba
            </div> 
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="catgories.php?catename=<?= $all[0]['name'] ?>">
                        <?= $all[0]['name'] ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="catgories.php?catename=<?= $all[1]['name'] ?>">
                        <?= $all[1]['name'] ?>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    more caregories
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php
                            for($i = 2 ; $i<getCount("name","categories");$i++){
                                echo '<a class="dropdown-item" href="catgories.php?catename='. $all[$i]['name'] .' ">' . $all[$i]['name'] . '</a>';    
                            }
                        ?>
                    </div>
                </li>
                
            </ul>
            <form class="form-inline my-2 my-lg-0" action="items.php" method="get">
                <input class="form-control mr-sm-2 search-in" placeholder="search items" aria-label="Search" name="finditem" autofocus />

                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <a href="admin/home.php" style="color: #B2ABAB; margin-left:10px;font-size:20px" ><i class="fas fa-user-tie"></i><a>
        </div>
    </div>
</nav>