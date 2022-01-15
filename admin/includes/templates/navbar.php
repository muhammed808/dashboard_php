
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container">
        <a href="../index.php" class="navbar-brand">
            <div class="logo">
                <span>abu</span>hassiba
            </div> 
        </a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="home.php" class="nav-link"><i class="fas fa-home"></i> home</a>
                </li>
                <li class="nav-item">
                <a href="categories.php?do=Manage&userid=<?php echo $_SESSION['id']; ?>" class="nav-link"><i class="fab fa-delicious"></i> categories</a>
                </li>
                <li class="nav-item">
                <a href="items.php?do=Manage&userid=<?php echo $_SESSION['id']; ?>" class="nav-link"><i class="fas fa-shopping-cart"></i> items</a>
                </li>
                <li class="nav-item">
                <a href="members.php?do=Manage&userid=<?php echo $_SESSION['id']; ?>" class="nav-link"><i class="fas fa-users"></i> members</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"><i class="fas fa-bell"></i> logs</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" data-target="dropdown_target">
                        <?php
                            if(isset($_SESSION['username'])){
                                echo '<i class="far fa-user-circle"></i>' . " " . $_SESSION['username'];
                            }
                        ?>
                            <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown_target">
                            <a href="members.php?do=Edit&userid=<?php echo $_SESSION['id']; ?>" class="dropdown-item"><i class="fas fa-user-edit"></i> edit profile</a>
                            <a href="../index.php"  class="dropdown-item"><i class="fab fa-shopify"></i> view shops</a>
                            <a href="logout.php"  class="dropdown-item"><i class="fas fa-sign-out-alt"></i> logout</a>
                        </div>
                    </li>
            </ul>
                
            
        </div>
    </div>

    </nav>
