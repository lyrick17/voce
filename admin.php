<?php require ("mysql/mysqli_session.php");
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php if (!isset ($_SESSION['username'])) {

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
                                <a href="index.php" class="sidebar-link">Audio to text</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="text-text.php" class="sidebar-link">Text-Text</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#auth" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-regular fa-user pe-2"></i>
                            Auth
                        </a>
                        <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Edit a user</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
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
                                <a href="#" class="dropdown-item">Profile</a>

                                <a href="account.php" class="dropdown-item">Setting</a>
                                <a href="#" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="profileTab" role="tabpanel"
                            aria-labelledby="profile-tab">
                            <form action="#">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password">
                                </div>
                                <button type="submit" class="btn btn-primary">Change</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

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
                                <div class="card-header">Pie Chart</div>
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
                                <div class="card-header">Graph</div>
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
                    <button class="dlpie-btn" href="#">Download Pie Graph</button>
                    <button class="dlgraph-btn" href="#">Download Line Graph</button>
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