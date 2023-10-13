<?php include("mysql/mysqli_registration.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="styles.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Roboto+Mono:wght@100&family=Young+Serif&display=swap"
    rel="stylesheet">

</head>

<body class="vh-100">
  <nav class="navbar navbar-expand-lg navbar-light bg-transparent">
    <div class="container">
      <!-- Logo -->

      <a class="navbar-brand fs-4 text-light nav-link active" style="color: #D3C5C3; font-family: 'Young Serif', serif;"
        aria-current="page" href="index.html">Voice</a>




      <!-- Sidebar Body -->
      <div class="offcanvas-body d-flex flex-column flex-lg-row p-4">
        <ul class="navbar-nav justify-content-center align-items-center fs-5 flex-grow-1 pe-3">
          <li class="nav-item mx-2">
            <a class="nav-link" style="color: #D2ACA4; font-family: 'Roboto Mono', monospace;" href="#">Sample Audio</a>
          </li>
          <li class="nav-item mx-2">
            <a class="nav-link" style="color: #D2ACA4; font-family: 'Roboto Mono', monospace;" href="#">Features</a>
          </li>
          <li class="nav-item mx-2">
            <a class="nav-link" style="color: #D2ACA4; font-family: 'Roboto Mono', monospace;" href="#">Enthusiasts</a>
          </li>
        </ul>
        <!-- Login -->
        <div class="d-flex flex-column justify-content-conter align-items-center gap-3 flex-lg-row">
          <a href="dashboard.php" class="" style="color: #D2ACA4"><span class="login">Login</span></a>
          <button class="btn btn-primary rounded-pill text-center" data-bs-toggle="modal" data-bs-target="#enroll"
            style="border-width: 2px; padding: 10px 20px; font-family: 'Young Serif', serif;">Signup</button>

        </div>
      </div>
    </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6 ">
        <br /><br> <br /> <br /> <br /> <br />
        <h1 class="text-light">
          <center>T R A N S C R I P T E R</center>
          </h2>
          <br><br />
          <p class="text-center text-white" style="font-family: 'Roboto Mono', monospace; font-size: 25px;">Unlock the
            power of your audio content by effortlessly
            converting it into text</p>
          <br><br>
          <center>
            <a href="loginpage.php"><button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#enroll"
              style="border-width: 2px; padding: 20px 50px; font-family: 'Young Serif', serif;">Get Started</button></a>
          </center>

      </div>
      <!--  <div class="col-md-6">
			<iframe class="embed-responsive-item rounded-pill" width="660" height="500"	src="vid.mp4" frameborder="0" ></iframe>
         <img src="vid.mp4" alt="Sample Image" class="img-fluid rounded-pill"> 
        </div> -->
    </div>
  </div>
   <!-- Add a new section about how your translator works with a blue background 

	<section id="members" class="p-5 bg-primary">
    <div class="container">
      <h2 class="text-center text-white">Enthusiasts</h2>
      <p class="lead text-center text-white mb-5">
        Our Members all have been working on finding out how much more amusing munchkins cats can be
        
      </p>
      <div class="row g-5">
        <div class="col-md-6 col-lg-3">
          <div class="card bg-light">
            <div class="card-body text-center">
              <img
                src="https://randomuser.me/api/portraits/men/21.jpg"
                class="rounded-circle mb-3"
                alt=""
              />
              <h3 class="card-title mb-3">Kyle Hingpit</h3>
              <p class="card-text">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Assumenda accusamus nobis sed cupiditate iusto? Quibusdam.
              </p>              
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="card bg-light">
            <div class="card-body text-center">
              <img
                src="https://randomuser.me/api/portraits/men/39.jpg"
                class="rounded-circle mb-3"
                alt=""
              />
              <h3 class="card-title mb-3">Stephen Moral</h3>
              <p class="card-text">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Assumenda accusamus nobis sed cupiditate iusto? Quibusdam.
              </p>
              
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="card bg-light">
            <div class="card-body text-center">
              <img
                src="https://randomuser.me/api/portraits/men/50.jpg"
                class="rounded-circle mb-3"
                alt=""
              />
              <h3 class="card-title mb-3">Reyland Olivar</h3>
              <p class="card-text">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Assumenda accusamus nobis sed cupiditate iusto? Quibusdam.
              </p>
              
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="card bg-light">
            <div class="card-body text-center">
              <img
                src="https://randomuser.me/api/portraits/men/34.jpg"
                class="rounded-circle mb-3"
                alt=""
              />
              <h3 class="card-title mb-3">John Aratan</h3>
              <p class="card-text">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Assumenda accusamus nobis sed cupiditate iusto? Quibusdam.
              </p>
              
            </div>
          </div>
        </div>

        
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
	  <!-- Form -->
  <div class="modal fade" id="enroll" tabindex="-1" aria-labelledby="enrollLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="enrollLabel">Enrollment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-white rounded">
          <p class="lead">Fill out this form and we will get back to you</p>
          
          <!-- REGISTER FORM-->
          <form action="index.php" method="post"> <!--  -->
            
            <!-- This input type determines whether form is REGISTER or login -->
            <input type="hidden" id="formType" name="formType" value="register">
            
            <div class="mb-3">
              <!-- Username -->
              <label for="username" class="col-form-label">
                Username:
              </label>

              <!-- span element for handling USERNAME ERRORS -->
              <span id="username-error" class="text-danger" style="font-size: 12px; font-style: italic;"><?php echo $display_errors['username']; ?></span>

              <input type="text" class="form-control" id="username" name="username" value=""/>
            </div>

            <div class="mb-3">
              <!-- Email -->
              <label for="email" class="col-form-label">Email:</label>
              <!-- span element for handling EMAIL ERRORS -->
              <span id="email-error" class="text-danger" style="font-size: 12px; font-style: italic;"><?php echo $display_errors['email']; ?></span>

              <input type="email" class="form-control" id="email" name="email" value=""/>
            </div>

            <div class="mb-3">
              <!-- Password -->
              <label for="pword" class="col-form-label">Password:</label>
              <!-- span element for handling PASSWORD ERRORS -->
              <span id="pword-error" class="text-danger" style="font-size: 12px; font-style: italic;"><?php echo $display_errors['password']; ?></span>

              <input type="password" class="form-control" id="pword" name="pword" value="" />
            </div>

            <div class="mb-3">
              <!-- Confirm Password -->
              <label for="pword2" class="col-form-label">Confirm Password:</label>
              <input type="password" class="form-control" id="pword2" name="pword2" value=""/>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Close
              </button>
              <input type="submit" id="submit-register" name="submit-register" class="btn btn-primary" value="Submit" disabled>
            </div>
          </form>
        
        
        </div>
        
      </div>
    </div>
  </div>





  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
  <script src="javascript.js"></script>
  <script src="form-validation.js"></script>
</body>

</html>