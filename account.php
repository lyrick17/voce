<?php
require ("mysql/mysqli_session.php");
$current_page = basename($_SERVER['PHP_SELF']);

if (!isset ($_SESSION['username'])) {
    header("location: index.php");
    exit();
}

function dd($item)
{
    var_dump($item);
    exit();
}

require "utilities/Translator_Functions.php";


$sess_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$sess_hashedPass = $_SESSION['pword'];
$usernameerror = "";
$emailerror = "";
$passerror = "";

if (isset ($_GET['e'])) {
    switch ($_GET['e']) {
        // username errors
        case 1:
            $usernameerror = "<style>#edit-username-btn{display:none}#edit-username-div{display:block;}</style><p style = 'color:red'>You are already using this username.</p>";
            break;
        case 2:
            $usernameerror = "<style>#edit-username-btn{display:none}#edit-username-div{display:block;}</style><p style = 'color:red'>This username already exists.</p>";
            break;

        // email errors
        case 3:
            $emailerror = "<style>#edit-email-btn{display:none;}#edit-email-div{display:block;}</style><p style = 'color:red'>You are already using this email.</p>";
            break;
        case 4:
            $emailerror = "<style>#edit-email-btn{display:none;}#edit-email-div{display:block;}</style><p style = 'color:red'>This email is already taken.</p>";
            break;


        // password errors
        case 5:
            $passerror = "<style>#edit-psword-btn{display:none;}#edit-psword-div{display:block;}</style><p style = 'color:red'>Your password must be different from your old password</p>";
            break;
        case 6:
            $passerror = "<style>#edit-psword-btn{display:none;}#edit-psword-div{display:block;}</style><p style = 'color:red'>Your old password is not correct.</p>";
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset ($_POST['username'])) {

    $newUsername = mysqli_real_escape_string($dbcon, trim($_POST['username']));

    // check if username already exists
    $query = mysqli_prepare($dbcon, "SELECT user_id FROM admin_users where username = ?");
    mysqli_stmt_bind_param($query, "s", $newUsername);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $result);
    mysqli_stmt_fetch($query);

    if ($username == $newUsername) {
        // username error, user didnt change username
        header("Location: account.php?e=1");
        exit();
    } elseif ($result > 0) {
        // username error, username already exists
        header("Location: account.php?e=2");
        exit();
    } else {
        // no error
        $query = mysqli_prepare($dbcon, "UPDATE admin_users SET username = ?
        WHERE user_id = ?");
        mysqli_stmt_bind_param($query, "ss", $newUsername, $sess_id);
        $result = mysqli_stmt_execute($query);

        $_SESSION['username'] = $newUsername;
        unset($_POST);

        header("Location: account.php");
        exit();

    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset ($_POST['email'])) {
    $newEmail = mysqli_real_escape_string($dbcon, trim($_POST['email']));

    // check if email already exists
    $query = mysqli_prepare($dbcon, "SELECT user_id FROM admin_users where email = ?");
    mysqli_stmt_bind_param($query, "s", $newEmail);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $result);
    mysqli_stmt_fetch($query);


    if ($email == $newEmail) {
        // email error, user type his same email
        header("Location: account.php?e=3");
        exit();
    } elseif ($result > 0) {
        // email error, email already exists
        header("Location: account.php?e=4");
        exit();
    } else {
        // no error
        $query = mysqli_prepare($dbcon, "UPDATE admin_users SET email = ?
        WHERE user_id = ?");
        mysqli_stmt_bind_param($query, "ss", $newEmail, $sess_id);
        $result = mysqli_stmt_execute($query);

        $_SESSION['email'] = $newEmail;
        unset($_POST);

        header("Location: account.php");
        exit();
    }

}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset ($_POST['new-pword'])) {
    $oldPass = mysqli_real_escape_string($dbcon, trim($_POST['old-pword']));

    $newPass = mysqli_real_escape_string($dbcon, trim($_POST['new-pword']));
    $hashedPass = password_hash($newPass, PASSWORD_BCRYPT);

    if ($oldPass == $newPass) {
        // pass error, user didnt change password
        header("Location: account.php?e=5");
        exit();
    } elseif (password_verify($oldPass, $sess_hashedPass)) {
        // no error
        $query = mysqli_prepare($dbcon, "UPDATE admin_users SET pword = ?
        WHERE user_id = ?");
        mysqli_stmt_bind_param($query, "ss", $hashedPass, $sess_id);
        $result = mysqli_stmt_execute($query);

        $_SESSION['pword'] = $hashedPass;
        unset($_POST);

        header("Location: account.php");
        exit();
    } else {
        // pass error, old password is wrong
        header("Location: account.php?e=6");
        exit();
    }
}

// if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
//     $q = "SELECT username, email FROM USERS where user_id = $sess_id";
//     $result = mysqli_query($dbcon, $q);
//     $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
//     unset($_POST);
//     exit(json_encode($users));
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="styles/UI-OLDSTYLE.css">
    <link rel="stylesheet" href="styles/user-account.css">
    <title>My Account</title>
    <link rel="icon" type="image/x-icon" href="images/icon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>



<body>

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i><span id="nav-name">
                <?= $username ?>
            </span>
        </nav>

        <!-- End of Navbar -->

        <main style="padding-top: 2em;">
            <div class="acc-info-div">
                <h1>Account Details</h1>
                <hr>
                <div class="info-label">
                    <h4>Username</h4>
                </div>
                <div class="user-info-div">
                    <p id="user-username">
                        <?= $username ?>
                    </p>
                </div>
                <button type="button" class="edit-btn" id="edit-username-btn">Edit</button>
                <div class="edit-info-div" id="edit-username-div">
                    <form action="account.php" method="POST" id="inputuser-form">
                        <div><label for="username">Change Username</label></div>
                        <?php
                        echo $usernameerror;
                        ?>
                        <p class="status username-status"></p>
                        <input type="text" placeholder="Name" id="username" class="user-input" name="username" required
                            maxlength="50" required>
                        <p class="req unique-user2">Note: <br> *Username must be unique and 6-30 characters long <br>
                            *Username must only contain numbers, letters, dashes, and underscores</p>
                        <input type="submit" class="edit-submit" id="updateUsername" name="updateUsername"
                            value="Edit Username" disabled>
                        <button type="button" class="close-btn" id="close-username-btn">cancel</button>
                    </form>
                </div>
                <br />
                <br />
                <hr>
                <div class="info-label">
                    <h4>Email</h4>
                </div>
                <div class="user-info-div">
                    <p id="user-email">
                        <?= $email ?>
                    </p>
                </div>
                <button type="button" class="edit-btn" id="edit-email-btn">Edit</button>
                <div class="edit-info-div" id="edit-email-div">
                    <form action="account.php" method="POST" id="inputemail-form">
                        <div><label for="email">Change Email</label></div>
                        <?php
                        echo $emailerror;
                        ?>
                        <p class="status username-status"></p>
                        <input type="email" placeholder="Email" id="email" class="user-input" name="email" required
                            maxlength="100" required>
                        <p class="req valid-email2">Note:<br>*Email must be unique and valid</p>
                        <input type="submit" class="edit-submit" id="updateEmail" name="updateEmail" value="Edit Email"
                            disabled>
                        <button type="button" class="close-btn" id="close-email-btn">cancel</button>
                    </form>
                </div>


            </div>
            <br />
            <div class="acc-info-div">
                <h2>Change Password</h2>
                <div class="">Do you want to change your password?</div>
                <hr>
                <br />
                <button type="button" class="edit-btn" id="edit-psword-btn">Change Password</button>
                <div class="edit-info-div" id="edit-psword-div">
                    <form action="account.php" method="POST" id="inputpass-form">
                        <div><label for="pword">Old Password</label></div>
                        <?php
                        echo $passerror;
                        ?>
                        <input type="password" placeholder="Old Password" class="user-input" id="old-pword"
                            name="old-pword" required>
                        <div><label for="pword">New Password</label></div>
                        <input type="password" placeholder="Password" class="user-input" id="new-pword" name="new-pword"
                            required>
                        <div><label for="pword2">Confirm New Password</label></div>
                        <input type="password" placeholder="Confirm Password" class="user-input" id="new-pword2"
                            name="new-pword2" required>
                        <p class="req confirm-pass2">Note:<br>*New Passwords must match and atleast 8 characters long
                        </p>
                        <p class="req confirm-pass2">*Old Password must match your current password</p>
                        <input type="submit" class="edit-submit" id="updatePsword" name="updatePsword"
                            value="Edit Password" disabled>
                        <button type="button" class="close-btn" id="close-psword-btn">cancel</button>
                    </form>
                </div>

            </div>

            <br />
            <div class="acc-info-div">
                <h2>Provide Us Feedback!</h2>
                <div class="">We value your input and look forward to connecting with you.</div>
                <hr>
                <br />
                <span id="contact-error" style="<?php echo "color: " . $contact_color . ";" ?? ''; ?>">
                    <?php echo htmlspecialchars($contact_message) ?? ''; ?>
                </span>
                <form method="post" action="account.php">
                    <input type="text" placeholder="Your Name" id="name" class="user-input" name="contact_name" required
                        maxlength="100" value="<?= $username ?>" readonly required> <br>
                    <input type="text" placeholder="Subject" id="subject" class="user-input" name="contact_subject"
                        required maxlength="100" required><br>
                    <textarea class="user-input" name="contact_message" rows="5" placeholder="Message"></textarea>

                    <br />
                    <button type="submit" class="edit-btn" id="edit-psword-btn">Send Message</button>

                </form>

            </div>
        </main>

    </div>

    <script src="scripts/index.js"></script>
    <script src="scripts/user-account.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>

</body>

</html>