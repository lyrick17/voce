<?php require("mysql/mysqli_session.php"); 
$current_page = basename($_SERVER['PHP_SELF']);



?>
<?php if (!isset($_SESSION['username'])) {
    
  header("location: index.php");
  exit(); 
}?>

<?php

function dd($item){
    var_dump($item);
    exit();
}

require "Translator_Functions.php";

$q = "SELECT user_id, username, email, registration_date, type FROM users ORDER BY user_id ASC";
$users = mysqli_query($dbcon, $q);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/style2.css">
    <link rel = "stylesheet" href = "styles/user-table.css">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">





</head>

<!-- Confirm delete window -->
<div class = "delete-window">
    <div class = "confirm-div">
        <h4 class = "confirm-text"></h4>
        <div class = "confirm-btn-div">
            <button class = "confirm-btn confirm-yes">Yes</button>
            <button class = "confirm-btn confirm-no">No</button>
        </div>
    </div>
</div>

<!-- Confirm delete window -->
<div class = "create-window">
    <div class = "create-form-div">
            <button type = "button" class = "closecreate-btn">X</button>
            <h4 class = "create-div-header">Create A New User</h4>
            <label for = "username">Username</label>
            <input type="text" placeholder="Name" id="username" name="username" required maxlength="50">
            <label for = "email">Email</label>
            <input type="email" placeholder="Email" id="email" name="email" required maxlength="100">
            <label for = "pword">Password</label>
            <input type="password" placeholder="Password" id="pword" name="pword" required>
            <label for = "pword2">Confirm Password</label>
            <input type="password" placeholder="Confirm Password" id="pword2" name="pword2" required>
            <input type="submit" id="submit-register" name="submit-register" value="Submit" disabled>
    </div>
</div>

<!-- Stores current user id in hidden content -->
<input type="hidden" id="<?= $_SESSION['user_id']?>" class="mysession">

<body>

    <!-- Sidebar -->
    <?php require "sidebar.php"?>

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
        </nav>

        <!-- End of Navbar -->

        <main style = "padding: 0;">

        <div class = "table-container">
                <table class = "users-table">
                        <tr>
                            <td class = "create-cell"><button class = "table-btn create-btn">Create User</button></td>
                        </tr>
                        <tr>
                            <th class = "data">User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Registration Date</th>
                            <th>Type</th>
                            <th colspan = 3>Actions</th>   
                        </tr>
                        <?php Translator::displayUsers($users) ?>
                </table>
        </div>
        </main>

    </div>

    <script src="scripts/index.js"></script>
    <script type = "text/javascript" src ="scripts/user-table.js"></script>
    <script type = "text/javascript" src ="scripts/create-user.js"></script>

</body>

</html>