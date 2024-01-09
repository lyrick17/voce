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

require "utilities/Translator_Functions.php";

//Retrieves searched users 
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])){
    
        if(str_contains($_POST['search'], '@')){
            $q = "SELECT user_id, username, email, registration_date, type FROM users WHERE email LIKE '%". $_POST['search'] ."%'" . "ORDER BY user_id ASC";
            $users = mysqli_query($dbcon, $q);
            $result = mysqli_fetch_all($users, MYSQLI_ASSOC);
            exit(json_encode($result));
        }

        elseif(is_numeric($_POST['search'])){
            $q = "SELECT user_id, username, email, registration_date, type FROM users WHERE user_id LIKE '%". $_POST['search'] ."%'" . "ORDER BY user_id ASC";
            $users = mysqli_query($dbcon, $q);
            $result = mysqli_fetch_all($users, MYSQLI_ASSOC);
            exit(json_encode($result));
        }

        else{
        $q = "SELECT user_id, username, email, registration_date, type FROM users WHERE username LIKE '%". $_POST['search'] ."%'" . "ORDER BY user_id ASC";
        $users = mysqli_query($dbcon, $q);
        $result = mysqli_fetch_all($users, MYSQLI_ASSOC);
        exit(json_encode($result));
        }
}


// else if(func == "update"){
//     if((username.length >= 6 && username.length <= 30 && !usernames.includes(username.toLowerCase())) || username == preUpdateUsername) 
//         uniqueUserTxt2.style.color = validColor;
//     else{
//         uniqueUserTxt2.style.color = "red";
//     }
//     if(userPattern.test(username)){
//         validUserTxt2.style.color = validColor;
//     }
//     else{
//         validUserTxt2.style.color = "red";
//     }

// }

// else if(func == "update"){
//     if((emailPattern.test(email) && !emails.includes(email)) || email == preUpdateEmail){
//             validEmailTxt2.style.color = validColor;    
//     }
//     else{
//             validEmailTxt2.style.color = "red";    

//     }
// }


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userType'])) {
    $errors = ["ERRORS"];
    $userPattern = '/^[\w\-]+$/';
    $emailPattern = '/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/';
    $username = mysqli_real_escape_string($dbcon, trim($_POST['username']));
    $email = mysqli_real_escape_string($dbcon, trim($_POST['email']));
    $password = mysqli_real_escape_string($dbcon, trim($_POST['pword']));
    $password2 = mysqli_real_escape_string($dbcon, trim($_POST['pword2']));

    $usertype = mysqli_real_escape_string($dbcon, trim($_POST['userType']));
    $hashedPass = password_hash($password, PASSWORD_BCRYPT);

    // Username Validation
    $stmt = mysqli_prepare($dbcon, "SELECT EXISTS(SELECT username FROM USERS WHERE username = ?)");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result);
    mysqli_stmt_fetch($stmt);
    $stmt->close();

    if($result == 1){
        array_push($errors, "Username must be unique");
    }
    if(strlen($username) < 6 || strlen($username) > 30){
        array_push($errors, "Username length should be between 6 to 30 characters");
    }
    
    if(!preg_match($userPattern, $username)){
        array_push($errors, "Username must only contain letters digits, dashes, and underscores");
    }
    
    $stmt = mysqli_prepare($dbcon, "SELECT EXISTS(SELECT email FROM USERS WHERE email = ?)");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result);
    mysqli_stmt_fetch($stmt);
    $stmt->close();
    
    if($result == 1){
        array_push($errors, "The email you entered is already in use");
    }
    
    if(!preg_match($emailPattern, $email)){
        array_push($errors, "Email must be valid.");
    }

    if((strlen($password) < 8)){
        array_push($errors, "Password must be atleast 8 characters.");
    }
    if($password != $password2){
        array_push($errors, "Passwords must match");
    }    

    if(sizeof($errors) == 1){
    $query = mysqli_prepare($dbcon, "INSERT INTO users(username, email, pword, type, registration_date) 
                                    VALUES (?, ?, ?, ?, NOW())");
    mysqli_stmt_bind_param($query, "ssss", $username, $email, $hashedPass, $usertype);
    $result = mysqli_stmt_execute($query);
    
    $q = "SELECT user_id, username, email, registration_date, type FROM users ORDER BY user_id ASC";
    $users = mysqli_query($dbcon, $q);
    $result = mysqli_fetch_all($users, MYSQLI_ASSOC);
    exit(json_encode($result));
    }
    else{
        exit(json_encode($errors));
    }
}

else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new-username'])) {
    $errors = ["ERRORS"];
    $userPattern = '/^[\w\-]+$/';
    $emailPattern = '/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/';

    $username = mysqli_real_escape_string($dbcon, trim($_POST['new-username']));
    $email = mysqli_real_escape_string($dbcon, trim($_POST['new-email']));

        // Username Validation
        $stmt = mysqli_prepare($dbcon, "SELECT EXISTS(SELECT username FROM USERS WHERE username = ?)");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result);
        mysqli_stmt_fetch($stmt);
        $stmt->close();
    
        if($result == 1 && $_POST['preUpdateUsername'] != $username){
            array_push($errors, "Username must be unique");
        }
        if(strlen($username) < 6 || strlen($username) > 30){
            array_push($errors, "Username length should be between 6 to 30 characters");
        }
        
        if(!preg_match($userPattern, $username)){
            array_push($errors, "Username must only contain letters digits, dashes, and underscores");
        }

    $stmt = mysqli_prepare($dbcon, "SELECT EXISTS(SELECT email FROM USERS WHERE email = ?)");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result);
    mysqli_stmt_fetch($stmt);
    $stmt->close();
    
    if($result == 1 && $_POST['preUpdateEmail'] != $email){
        array_push($errors, "The email you entered is already in use");
    }
    
    if(!preg_match($emailPattern, $email)){
        array_push($errors, "Email must be valid.");
    }

    if(sizeof($errors) == 1){
        if($_POST['userId'] == $_SESSION['user_id']){
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            // $_SESSION['pword'] = $hashedPass;
        }
    $userId = (int)$_POST['userId'];

    $query = mysqli_prepare($dbcon, "UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    mysqli_stmt_bind_param($query, "ssi", $username, $email, $userId);
    $result = mysqli_stmt_execute($query);
    

    $q = "SELECT user_id, username, email, registration_date, type FROM users ORDER BY user_id ASC";
    $users = mysqli_query($dbcon, $q);
    $result = mysqli_fetch_all($users, MYSQLI_ASSOC);
    exit(json_encode($result));
    }
    else{
        exit(json_encode($errors));
    }
}




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
    <link rel="stylesheet" href="styles/simplePagination.css">
    <title>Admin - Users</title>
    <link rel="icon" type="image/x-icon" href="images/icon.ico">
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

<!-- Create window -->
<div class = "create-window">
    <div class = "create-form-div">
        <button type = "button" class = "close-btn closecreate-btn">X</button>
        <h4 class = "create-div-header">Create A New User</h4>
        <form id = "form">
            <div class = "input-div">
                <div><label for = "username">Username</label></div>
                    <input type="text" placeholder="Name" id="username" class = "admin-input" name="username" required maxlength="50" required>
                <div><label for = "email">Email</label></div>
                    <input type="email" placeholder="Email" id="email" class = "admin-input" name="email" required maxlength="100" required>
                <div><label for = "pword">Password</label></div>
                    <input type="password" placeholder="Password" class = "admin-input" id="pword" name="pword" required>
                <div><label for = "pword2">Confirm Password</label></div>
                    <input type="password" placeholder="Confirm Password" class = "admin-input" id="pword2" name="pword2" required>
                <span for = "userType" class = "typeLabel">Type of user</span>
                    <select name="userType" id="userType" class="form-control select-type">
                        <option value="default">Type of User …</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
            </div>

            <hr>
            <div class = "acc-req">
                <p class = "req unique-user">*Username must be unique and 6-30 characters long</p>
                <p class = "req valid-user">*Username must only contain numbers, letters, dashes, and underscores</p>
                <p class = "req valid-email">*Email must be unique and valid</p>
                <p class = "req confirm-pass">*Passwords must match and atleast 8 characters long</p>
            </div>
            <input type="submit" class= "admin-submit" id="create-user" name="submit-register" value="Create User" disabled>
        </form>
    </div>
</div>

<!-- Update window -->
<div class = "update-window">
    <div class = "update-form-div">
        <button type = "button" class = "close-btn closeupdate-btn">X</button>
        <h4 class = "update-div-header">Update this user</h4>
        <form id = "update-form">
            <div class = "input-div">
                <label for = "username">Username</label>
                    <input type="text" placeholder="Name" class = "admin-input" id="new-username" name="new-username" required maxlength="50" required>
                <label for = "email">Email</label>
                <input type="email" placeholder="Email" class = "admin-input" id="new-email" name="new-email" required maxlength="100" required>
                <br />
            </div>
            <hr>
            <div class = "acc-req">
                <p class = "req unique-user2">*Username must be unique and 6-30 characters long</p>
                <p class = "req valid-user2">*Username must only contain numbers, letters, dashes, and underscores</p>
                <p class = "req valid-email2">*Email must be unique and valid</p>
            </div>
            <input type="submit" class= "admin-submit" id="submitUpdate" name="submitUpdate" value="Update User" disabled>
        </form>
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
            <i class='bx bx-menu'></i><span id = "nav-name"><?= $_SESSION['username']; ?></span>
            <form id = "search-form">
                        <input id = "search-user" type="text" placeholder="Search User.." name="search">
        </form>
        </nav>

        <!-- End of Navbar -->

        <main style = "padding: 0;">
        <div class = "table-container">
                <table class = "users-table">
                        <tr>

                        </tr>
                        <tr>
                            <td class = "create-cell" colspan = 1><button class = "table-btn create-btn">Create User</button></td>
                            <td class = "select-cell" colspan = 2><button type = 'button' class = "deleteSelectedUsers">Delete Selected Rows</button><button type = 'button' class = "deleteRows-btn">Delete Rows</button></td>
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
                <br />
                
                <div id="page-nav-content">
                    <div id="page-nav"></div>
                </div>

        </div>
        </main>

    </div>

    <!-- for an in-depth walkthrough for pagination, please visit https://bilalakil.me/simplepagination -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/jquery.simplePagination.min.js" integrity="sha512-J4OD+6Nca5l8HwpKlxiZZ5iF79e9sgRGSf0GxLsL1W55HHdg48AEiKCXqvQCNtA1NOMOVrw15DXnVuPpBm2mPg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="scripts/index.js"></script>
    <script type = "text/javascript" src ="scripts/user-table.js"></script>
</body>

</html>