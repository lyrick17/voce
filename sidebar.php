<div class="sidebar">
        <a href="dashboard1.php" class="logo">
            <img src="images/icon.ico" width="50px" height="50px"></img>
            <div class="logo-name"><span>Vo</span>CE</div>
        </a>
        <ul class="side-menu">
            <li>
                <a href="index.php" class="">
                    <i class='bx bx-log-out-circle'></i>
                    Home
                </a>
            </li>
            <li class="<?php echo ($current_page === 'text-text.php') ? 'active' : ''; ?>"><a href="text-text.php"><img src="images/sidebartext.png" alt="scroll icon" width="25" height="25" style="margin-left: 10px;">
            &nbsp Text-Text</a></li>        
            <li class="<?php echo ($current_page === 'history_audio.php') ? 'active' : ''; ?>">
            <a href="history_audio.php"><img src="images/sidebaraudio.png" alt="scroll icon" width="25" height="25" style="margin-left: 10px;">
            &nbsp Audio to Text</a></li>

            <?php if(isset($_SESSION['user_id'])): ?>
            
            <li class="<?php echo ($current_page === 'dashboard1.php') ? 'active' : ''; ?>"><a href="dashboard1.php"><i class='bx bxs-dashboard'></i>Dashboard</a></li> 
            
            <li class="<?php echo ($current_page === 'admin.php') ? 'active' : ''; ?>">
                <a href="admin.php"><img src="images/graph.png" alt="scroll icon" width="25" height="25" style="margin-left: 10px;">
            &nbsp Statistics</a></li>     
            
            <li class="<?php echo ($current_page === 'admin-text-history.php') ? 'active' : ''; ?>">
                    <a href="admin-text-history.php"><img src="images/users.png" alt="scroll icon" width="25" height="25" style="margin-left: 10px;">
                &nbsp Text Translations</a></li>   
            
            <li class="<?php echo ($current_page === 'admin-audio-history.php') ? 'active' : ''; ?>">
                    <a href="admin-audio-history.php"><img src="images/users.png" alt="scroll icon" width="25" height="25" style="margin-left: 10px;">
                &nbsp Audio Translations</a></li>   
            
            <li class="<?php echo ($current_page === 'account.php') ? 'active' : ''; ?>"><a href="account.php"><i class='bx bx-cog'></i>My Account</a></li> 
            
            <li>
                <a href="utilities/logout.php" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>

            <?php endif; ?>
            
            


        </ul>
    </div>