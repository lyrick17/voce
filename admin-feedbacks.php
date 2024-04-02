<?php require ("mysql/mysqli_session.php");
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php if (!isset($_SESSION['username'])) {

    header("location: index.php");
    exit();
} ?>

<?php


require "utilities/Translator_Functions.php"; // Translator_Functions and Error Handling are alr required in this file

$id = is_array($_SESSION['user_id']) ? $_SESSION['user_id']['user_id'] : $_SESSION['user_id'];


$sess_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$sess_hashedPass = $_SESSION['pword'];
$usernameerror = "";
$emailerror = "";
$passerror = "";

//Retrieves searched users 
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search = mysqli_real_escape_string($dbcon, trim($_GET['search']));

    $q = "SELECT * FROM contacts WHERE 
                (`contact_id` LIKE '%$search%' OR 
                `username` LIKE '%$search%' OR 
                `subject` LIKE '%$search%' OR 
                `message` LIKE '%$search%')
                ORDER BY contact_id DESC";

    $query = mysqli_query($dbcon, $q);
    if ($query) {
        $history = $query;
    }

    // Query for total feedback result from search
    $q = "SELECT COUNT(contact_id) as total_feedback FROM contacts WHERE 
                (`contact_id` LIKE '%$search%' OR 
                `username` LIKE '%$search%' OR 
                `subject` LIKE '%$search%' OR 
                `message` LIKE '%$search%')";
    $result = mysqli_query($dbcon, $q);
    if ($result) {
        $num_of_feedback = mysqli_fetch_assoc($result);
    }
    
} else {

    // Translation history for audio to text 
    $history = mysqli_query($dbcon, "SELECT * FROM contacts ORDER BY contact_id DESC");
    
    // Query for total feedbacks
    $q = "SELECT COUNT(contact_id) as total_feedback FROM contacts";
    $result = mysqli_query($dbcon, $q);
    $num_of_feedback = mysqli_fetch_assoc($result);
}


?>




<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feedbacks</title>
    <link rel="icon" type="image/x-icon" href="images/icon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/newadmin.css">
    <link rel="stylesheet" href="styles/simplePagination.css">

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
                        <a href="admin.php" class="sidebar-link collapsed">
                            <i class="fa-solid fa-chart-simple pe-2"></i>Dashboard
                        </a>
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-file-lines pe-2"></i>
                            Features ▼
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
                        <a href="admin-feedbacks.php" class="sidebar-link collapsed">
                            <i class="fa-solid fa-comment pe-1"></i>Feedbacks
                        </a>
                    </li>
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
                                <p>*must be atleast 8 characters</p><!--Error Message-->

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
                                    <input type="email" class="form-control" id="current-email" name="current-email" value = "<?= $_SESSION['email']?>" maxlength="100" 
                                        disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="new-email" class="form-label">New Email</label>
                                    <input type="email" placeholder="Email" class="form-control user-input" id="new-email" name="email" maxlength="100" required>
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
                        <h4>User Feedbacks</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-8 pb-3">
                            <form method="get" action="admin-feedbacks.php">
                                <input type="text" placeholder="Search..." name="search" class="w-50 p-2" maxlength="255">
                                <input type="submit" name="search-submit" class="btn btn-primary" value="Search">
                                <a href="admin-feedbacks.php" class="btn btn-secondary">Clear Search</a>
                            </form>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <?php if ($num_of_feedback['total_feedback'] != 0): ?>
                                <div>
                                    <?php while($row = mysqli_fetch_assoc($history)): ?>
                                        <div class="card paginate">
                                            <div class="card-body">
                                                <h4>Feedback No. #<?= $row['contact_id'] ?></h4>
                                                <h5 class="count">
                                                    Name: <b><?= $row['username'] ?></b>
                                                </h5>
                                                <h6>Subject: <b><?= $row['subject'] ?></b></h6>
                                                <hr />
                                                <span><b>Message:</b></span><br />
                                                <p><?= $row['message'] ?></p>
                                                <p><?= date('F j, Y g:i A', strtotime($row['contact_date'])) ?></p>
                                            </div>
                                        </div>
                                        
                                    <?php endwhile; ?>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <div id="page-nav-content">
                                        <div id="page-nav"></div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="card">
                                    <div class="card-body">
                                        <h4>No feedbacks have been found.</h4>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <br />
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h4>Total User Feedbacks</h4>
                                    <h5 class="count">
                                        <?= $num_of_feedback['total_feedback'] ?>
                                    </h5>
                                    <hr />
                                    <?php if (isset($search)): ?>
                                        <span>with search results:</span><br />
                                        <span><i><?= $search; ?></i></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                
            </main>
            <a href="#" class="theme-toggle">
                <i class="fa-regular fa-moon"></i>
                <i class="fa-regular fa-sun"></i>
            </a>
        </div>
    </div>
    <script src="scripts/account.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.4/jquery.simplePagination.min.js" integrity="sha512-J4OD+6Nca5l8HwpKlxiZZ5iF79e9sgRGSf0GxLsL1W55HHdg48AEiKCXqvQCNtA1NOMOVrw15DXnVuPpBm2mPg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="scripts/paginate-feedback.js"></script>
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