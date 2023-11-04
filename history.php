<?php require("mysql/mysqli_session.php");
    $current_page = basename($_SERVER['PHP_SELF']);
    if (!isset($_SESSION['username'])) {
        header("location: loginpage.php");
        exit();
    }

    $id = is_array($_SESSION['user_id']) ? $_SESSION['user_id']['user_id'] : $_SESSION['user_id'];
    $history = mysqli_query($dbcon, "SELECT * FROM text_translations WHERE user_id = $id AND from_audio_file = 0 ORDER BY translation_date DESC");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/style2.css">
    <title>History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <i class="fa fa-microphone"></i>
            <div class="logo-name"><span>Vo</span>CE</div>
        </a>
        <ul class="side-menu">
            <li><a href="dashboard1.php"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
            <li class="<?php echo ($current_page === 'history.php') ? 'active' : ''; ?>">
            <a href="history.php"><img src="images/scroll-solid.svg" alt="scroll icon" width="25" height="25" style="margin-left: 5px;">
            &nbsp History</a>      
           </li> 
            
            <li><a href="#"><i class='bx bx-group'></i>Users</a></li>
            <li><a href="#"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button class="search-btn" type="submit"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="theme-toggle" hidden>
            <label for="theme-toggle" class="theme-toggle"></label>
            <a href="#" class="notif">
                <i class='bx bx-bell'></i>
                <span class="count">12</span>
            </a>
            <a href="#" class="profile">
                <img src="images/logo.png">
            </a>
        </nav>

        <!-- End of Navbar -->

        <main>
            <div class="header">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">
                                Analytics
                            </a></li>
                        /
                        <li><a href="#" class="active">Shop</a></li>
                    </ul>
                </div>
            </div>

            <!-- Insights -->
            <ul class="insights">
                <li>
                    <i class='bx bx-calendar-check'></i>
                    <span class="info">
                        <h3>
                        <?php
                            $total_text = "SELECT COUNT(*) FROM text_translations WHERE user_id = '" . $_SESSION['user_id'] . "' AND from_audio_file = 0";
                            $total_text_result = mysqli_query($dbcon, $total_text);
                            
                            $text_result_row = mysqli_fetch_array($total_text_result);
                            echo $text_result_row[0];
                                ?>
                        </h3>
                        <p>Total Text to Text Translations</p>
                    </span>
                </li>
                <li><i class='bx bx-show-alt'></i>
                    <span class="info">
                        <h3>
                            3,944
                        </h3>
                        <p>Site Visit</p>
                    </span>
                </li>
            </ul>
            <!-- End of Insights -->

            <div class="bottom-data">
                <div class="orders">
                    <div class="header">
                        <h2>Recent Text to Text Translations</h2>
                        <br>
                        
                    </div>
                    <table>
                        <thead>
                            <tr>
                            <th>Original Text</th>
                            <th>Source Language</th>
                            <th>Translated Text</th>
                            <th>Target Language</th>
                            <th>Translation Date</th>  
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($row = mysqli_fetch_assoc($history)) : ?>
                            <tr>
                            <td><?= $row['translate_from'] ?></td>
                            <td><?= $row['original_language'] ?></td>
                            <td><?= $row['translate_to']?></td>
                            <td><?= $row['translated_language']?></td>
                            <td><?= $row['translation_date']?></td>

                            </tr>
                        <?php endwhile ?>
                        </tbody>
                    </table>
                </div>

                <!-- End of Reminders-->

            </div>

        </main>

    </div>

    <script src="scripts/index.js"></script>
</body>

</html>