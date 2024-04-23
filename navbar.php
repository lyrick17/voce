
<?php 


?>
<nav>
        <div class="logo">
            <img src="images/logo.png" alt="Voce logo">
            <?php 
            if($_SERVER['REQUEST_URI'] == '/voice/index.php'){
                echo "<span>Audio Translator</span>";
            }
            elseif($_SERVER['REQUEST_URI'] == '/voice/text-text.php'){
                echo "<span>Text Translator</span>";
            }
            ?>

            <span><a href="loginpage.php" id = "admin-anchor"></a></span>

        </div>
</nav>