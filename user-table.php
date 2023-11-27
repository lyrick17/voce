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

$q = "SELECT user_id, username, email, registration_date, type FROM users ORDER BY registration_date";
$result = mysqli_query()
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/style2.css">
    <link rel = "stylesheet" href = "styles/table.css">
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
                            <td class = "create-cell"><button class = "create-btn">Create User</button></td>
                        </tr>
                        <tr>
                            <th class = "data">User ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Registration Date</th>
                            <th>Type</th>
                            <th colspan = 3></th>
                            
                        </tr>
                        <tr class = "user-info">
                            <td>1</td>
                            <td>John Doe</td>
                            <td>Johndoe@example.com</td>
                            <td>27/11/2023</td>
                            <td>Admin</td>
                            <td><a href = "#">Update</a></td>
                            <td><a href = "#">Delete</a></td>
                            <td><a href = "#">View</a></td>
                        </tr>
                        <tr class = "user-info">
                            <td>1</td>
                            <td>John Doe</td>
                            <td>Johndoe@example.com</td>
                            <td>27/11/2023</td>
                            <td>Admin</td>
                            <td><a href = "#">Update</a></td>
                            <td><a href = "#">Delete</a></td>
                            <td><a href = "#">View</a></td>
                        </tr>
                        <tr class = "user-info">
                            <td>1</td>
                            <td>John Doe</td>
                            <td>Johndoe@example.com</td>
                            <td>27/11/2023</td>
                            <td>Admin</td>
                            <td><a href = "#">Update</a></td>
                            <td><a href = "#">Delete</a></td>
                            <td><a href = "#">View</a></td>
                        </tr>

                </table>
        </div>
        </main>

    </div>

    <script src="scripts/index.js"></script>

</body>

</html>