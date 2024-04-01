<?php require ("mysql/mysqli_session.php");
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php if (!isset($_SESSION['username'])) {

    header("location: index.php");
    exit();
} ?>

<?php

function dd($item)
{
    var_dump($item);
    exit();
}

require "utilities/Translator_Functions.php"; // Translator_Functions and Error Handling are alr required in this file
require "utilities/verify_audio_files.php";
$id = is_array($_SESSION['user_id']) ? $_SESSION['user_id']['user_id'] : $_SESSION['user_id'];


// Query for total number of feedback sent
$q = "SELECT COUNT(contact_id) AS total_feedback FROM contacts";
$result = mysqli_query($dbcon, $q);
$num_of_feedback = mysqli_fetch_assoc($result);

// Query for total number of files uploaded
$q = "SELECT COUNT(file_id) AS total_files FROM audio_files";
$result = mysqli_query($dbcon, $q);
$num_of_files = mysqli_fetch_assoc($result);

// Query for total number of text-to-text translations
$q = "SELECT COUNT(from_audio_file) AS total_t2t FROM text_translations WHERE from_audio_file = 0";
$result = mysqli_query($dbcon, $q);
$num_of_t2t = mysqli_fetch_assoc($result);

// Query for total number of audio-to-text translations
$q = "SELECT COUNT(from_audio_file) AS total_a2t FROM text_translations WHERE from_audio_file = 1";
$result = mysqli_query($dbcon, $q);
$num_of_a2t = mysqli_fetch_assoc($result);


$sess_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$sess_hashedPass = $_SESSION['pword'];
$usernameerror = "";
$emailerror = "";
$passerror = "";

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
        header("Location: admin.php?e=1");
        exit();
    } elseif ($result > 0) {
        // username error, username already exists
        header("Location: admin.php?e=2");
        exit();
    } else {
        // no error
        $query = mysqli_prepare($dbcon, "UPDATE admin_users SET username = ?
        WHERE user_id = ?");
        mysqli_stmt_bind_param($query, "ss", $newUsername, $sess_id);
        $result = mysqli_stmt_execute($query);

        $_SESSION['username'] = $newUsername;
        unset($_POST);

        header("Location: admin.php");
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
        header("Location: admin.php?e=3");
        exit();
    } elseif ($result > 0) {
        // email error, email already exists
        header("Location: admin.php?e=4");
        exit();
    } else {
        // no error
        $query = mysqli_prepare($dbcon, "UPDATE admin_users SET email = ?
        WHERE user_id = ?");
        mysqli_stmt_bind_param($query, "ss", $newEmail, $sess_id);
        $result = mysqli_stmt_execute($query);

        $_SESSION['email'] = $newEmail;
        unset($_POST);

        header("Location: admin.php");
        exit();
    }

}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset ($_POST['new-pword'])) {
    $oldPass = mysqli_real_escape_string($dbcon, trim($_POST['old-pword']));

    $newPass = mysqli_real_escape_string($dbcon, trim($_POST['new-pword']));
    $hashedPass = password_hash($newPass, PASSWORD_BCRYPT);

    if ($oldPass == $newPass) {
        // pass error, user didnt change password
        header("Location: admin.php?e=5");
        exit();
    } elseif (password_verify($oldPass, $sess_hashedPass)) {
        // no error
        $query = mysqli_prepare($dbcon, "UPDATE admin_users SET pword = ?
        WHERE user_id = ?");
        mysqli_stmt_bind_param($query, "ss", $hashedPass, $sess_id);
        $result = mysqli_stmt_execute($query);

        $_SESSION['pword'] = $hashedPass;
        unset($_POST);

        header("Location: admin.php");
        exit();
    } else {
        // pass error, old password is wrong
        header("Location: admin.php?e=6");
        exit();
    }
}

?>




<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="images/icon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/newadmin.css">

    <style>
        .card-body {
            max-height: 500px;
            /* Adjust as needed */
            max-width: 500px;
            /* Adjust as needed */
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar" class="js-sidebar">
            <!-- Content For Sidebar -->
            <div class="h-100">
                <div class="sidebar-logo">
                    <img src="images/logonew.png" width="130px">

                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-file-lines pe-2"></i>
                            Features
                        </a>
                        <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="index.php" class="sidebar-link">Audio to Text</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="text-text.php" class="sidebar-link">Text to Text</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="faqs.php" class="sidebar-link">FAQs</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="about.php" class="sidebar-link">About Voce</a>
                            </li>
                        </ul>
                    </li>
                    <!--<li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#auth" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-regular fa-user pe-2"></i>
                            Auth
                        </a>
                        <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Edit a user</a>
                            </li>
                        </ul>
                    </li>-->
                    <li class="sidebar-item">
                        <a href="utilities/logout.php" class="sidebar-link logout-link">
                            <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="images/adminprofile.png" class="avatar img-fluid rounded" alt="">

                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#settingsModal">Settings</a>
                                <a href="utilities/logout.php" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="settingsModalLabel">Settings</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12 mb-3"> <a href="#" class="btn btn-light btn-block w-100"
                                            data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change
                                            Password</a>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <a href="#" class="btn btn-light btn-block w-100" data-bs-toggle="modal"
                                            data-bs-target="#changeUsernameModal">Change Username</a>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <a href="#" class="btn btn-light btn-block w-100" data-bs-toggle="modal"
                                            data-bs-target="#changeEmailModal">Change Email</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                data-bs-target="#settingsModal">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <h5 class="modal-title" id="changePasswordModalLabel">&nbsp;Change Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="admin.php" method="POST" id="inputpass-form">
                            <div class="modal-body">
                                <p id="pass-error"></p><!--Error Message-->
                                    <div class="mb-3">
                                        <label for="current-password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control user-input" id="old-pword"
                                            name="old-pword" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new-password" class="form-label user-input">New Password</label>
                                        <input type="password" class="form-control" id="new-pword" name="new-pword"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm-password" class="form-label user-input">Confirm Password</label>
                                        <input type="password" class="form-control" id="new-pword2" name="new-pword2"
                                            required>
                                    </div>
                                    <input type="submit" class="form-control edit-submit"
                                        id="updatePsword" name="updatePsword" value="Edit Password" disabled>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="changeUsernameModal" tabindex="-1" aria-labelledby="changeUsernameModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                data-bs-target="#settingsModal">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <h5 class="modal-title" id="changeUsernameModalLabel">&nbsp;Change Username</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="admin.php" method="POST" id="inputuser-form">
                                <p id="user-error"></p><!--Error Message-->
                                <div class="mb-3">
                                    <label for="current-username" class="form-label">Current Username</label>
                                    <input type="text" placeholder="Username" class="form-control user-input"
                                        id="current-username" name="current-username" maxlength="50" value="<?= $username ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="new-username" class="form-label">New Username</label>
                                    <input type="text" placeholder="Username" class="form-control user-input"
                                        id="username" name="username" maxlength="50" required>
                                        <br />
                                    <input type="submit" placeholder="Username" class="form-control edit-submit"
                                        id="updateUsername" name="updateUsername" value="Edit Username" disabled>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="changeEmailModal" tabindex="-1" aria-labelledby="changeEmailModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                data-bs-target="#settingsModal">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <h5 class="modal-title" id="changeEmailModalLabel">&nbsp;Change Email</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="admin.php" method="POST" id="inputemail-form">
                            <p id="email-error"></p><!--Error Message-->
                                <div class="mb-3">
                                    <label for="current-email" class="form-label">Current Email</label>
                                    <input type="email" class="form-control" id="current-email" name="current-email" value = "<?= $_SESSION['email']?>"
                                        disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="new-email" class="form-label">New Email</label>
                                    <input type="email" placeholder="Email" class="form-control user-input" id="new-email" name="email" required>
                                    <br />
                                    <input type="submit" class="form-control edit-submit"
                                        id="updateEmail" name="updateEmail" value="Edit Email">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <div class="mb-3">
                        <h4>Admin Dashboard</h4>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Total Audio Files uploaded</h4>
                                    <h5 class="count">
                                        <?= $num_of_files['total_files'] ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Total Text Translations</h4>
                                    <h5 class="count">
                                        <?= $num_of_t2t['total_t2t'] ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Total Audio Translations</h4>
                                    <h5 class="count">
                                        <?= $num_of_a2t['total_a2t'] ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">Total Translations</div>
                                <div class="card-body-md">
                                    <div class="donut-container">
                                        <br>
                                        <canvas id="donutCanvas"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg linegraph">
                            <div class="card ">
                                <div class="card-header">Translations for the Past 7 days</div>
                                <div class="card-body-md linechart-container">
                                    <canvas id="myChart">
                                    </canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Errors in Audio-Text</div>
                                <div class="card-body-md">
                                    <canvas id="baraudio">
                                    </canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Errors in Text-Text</div>
                                <div class="card-body-md">
                                    <canvas id="bartext">
                                    </canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Element 
                    <div class="card border-0">
                        <div class="card-header">
                            <h5 class="card-title">
                                Basic Table
                            </h5>
                            <h6 class="card-subtitle text-muted">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum ducimus,
                                necessitatibus reprehenderit itaque!
                            </h6>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">First</th>
                                        <th scope="col">Last</th>
                                        <th scope="col">Handle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td>@mdo</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <td colspan="2">Larry the Bird</td>
                                        <td>@twitter</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> -->
                </div>
                <div class="dlbtns-container">
                    <button class="dlpie-btn" href="#">Download Total Translations Graph</button>
                    <button class="dlgraph-btn" href="#">Download Recent Translations Graph</button>
                    <button class="dlbar1-btn" href="#">Download Text Errors Graph</button>
                    <button class="dlbar2-btn" href="#">Download Audio Errors Graph</button>
                    <form method="post" action="admin.php">
                        <button style="padding: 5px;" id="verify-files">
                            <h3>Verify Audio Files</h3>
                        </button>
                    </form>
                    <p>
                        <?= $verify_message; ?>
                    </p>
                </div>
            </main>
            <a href="#" class="theme-toggle">
                <i class="fa-regular fa-moon"></i>
                <i class="fa-regular fa-sun"></i>
            </a>
            <footer class="footer">
                <div class="container-fluid">
                </div>
            </footer>
        </div>
    </div>
    <script>
        document.getElementById('profileTab').classList.add('show', 'active');
    </script>
    <script src="scripts/account.js"></script>
    <script src="scripts/user-account.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="scripts/admin.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebarToggle = document.querySelector("#sidebar-toggle");
        sidebarToggle.addEventListener("click", function () {
            document.querySelector("#sidebar").classList.toggle("collapsed");
        });

        document.querySelector(".theme-toggle").addEventListener("click", () => {
            toggleLocalStorage();
            toggleRootClass();
        });

        function toggleRootClass() {
            const current = document.documentElement.getAttribute('data-bs-theme');
            const inverted = current == 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', inverted);
        }

        function toggleLocalStorage() {
            if (isLight()) {
                localStorage.removeItem("light");
            } else {
                localStorage.setItem("light", "set");
            }
        }

        function isLight() {
            return localStorage.getItem("light");
        }

        if (isLight()) {
            toggleRootClass();
        }
    </script>
</body>

</html>