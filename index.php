
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VoCE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="styles/styles.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Roboto+Mono:wght@100&family=Young+Serif&display=swap"
    rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body style="background: url('images/headset.jpg') center/cover no-repeat;">

  <header id="header">
    <div class="container d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo"><img src="images/logonew.png" alt="" class="img-fluid"></a>
      <!-- Uncomment below if you prefer to use text as a logo -->
      <!-- <h1 class="logo"><a href="index.html">Butterfly</a></h1> -->

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="#services">Features</a></li>         
          <li><a class="nav-link scrollto" href="#team">Team</a></li>
          <li><a class="nav-link scrollto" href="#contact">Contact Us</a></li>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

      <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">

    <div class="container">
      <div class="row">
        <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center">
          <h1>Voice Transcriber</h1>
             
          <h2>Unlock the
            power of your audio content by effortlessly
            converting it into text</h2>
          <div><a href="loginpage.php" class="btn-get-started rounded-pill scrollto">Get Started</a></div>
        </div>
      </div>
    </div>

  </section>
  <br>
<main class="main"> 

  
  
  
  
  <section id="services" class="services section-bg">
    <div class="container">
      <div class="section-title">
        <h2>Features</h2>
        <p>Unlock a world of possibilities with our application's exceptional features. Delve into a language experience like never before, where convenience, precision, and accessibility converge seamlessly.</p>
      </div>
      
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="icon-box">
            <div class="icon"><img src="images/infinite.png" width="50" height="50" style="margin-left: 20px;"></i></div>
            <h4 class="title">Multiple Languages</h4>
            <p class="description">Break language barriers effortlessly. Our application supports a wide array of languages, ensuring your content is accessible to a global audience.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="icon-box">
            <div class="icon"><img src="images/fast.png" width="50" height="50" style="margin-left: 20px;"></i></div>
            <h4 class="title">Fast</h4>
            <p class="description">Experience swift results. Our application prioritizes speed, delivering quick translations and efficient processing for an optimal user experience</p>
          </div>
        </div>
        
        <div class="col-lg-4 col-md-6" data-wow-delay="0.1s">
          <div class="icon-box">
            <div class="icon"><img src="images/reliable.png" width="50" height="50" style="margin-left: 20px;"></i></div>
            <h4 class="title">Reliable</h4>
            <p class="description">Trust in consistency. Our application ensures reliable performance, providing accurate translations and dependable service every time</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-wow-delay="0.1s">
          <div class="icon-box">
            <div class="icon"><img src="images/quality.png" width="50" height="50" style="margin-left: 20px;"></i></div>
            <h4 class="title">Quality</h4>
            <p class="description">Elevate your content with precision. Our application is dedicated to delivering high-quality translations, maintaining the integrity of your message.</p>
          </div>
        </div>

        <div class="col-lg-4 col-md-6" data-wow-delay="0.2s">
          <div class="icon-box">
            <div class="icon"><img src="images/free.png" width="50" height="50" style="margin-left: 20px;"></i></div>
            <h4 class="title">Free</h4>
            <p class="description">Discover the power of language without a price tag. Our application offers essential features for free, making language accessibility accessible to all.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-wow-delay="0.2s">
          <div class="icon-box">
            <div class="icon"><img src="images/lowstorage1.png" width="50" height="50" style="margin-left: 20px;"></i></div>
            <h4 class="title"><a href="">Low Storage</a></h4>
            <p class="description">Efficient and space-friendly. Our application optimizes storage usage, ensuring a low footprint on your device while providing powerful language capabilities.</p>
          </div>
        </div>
      </div>
      
    </div>
  </section><!-- End Services Section -->
  
  <!-- ======= About Section ======= -->
  <br>
  <section id="about" class="about">
    <div class="container">
  
      <div class="row">
        <div class="col-xl-5 col-lg-6 d-flex justify-content-center align-items-stretch position-relative">
          <video class="embed-responsive-item" height="600" width="500" controls>
            <source src="images/vid.mp4" type="video/mp4">
          </video>
        </div>
  
        <div class="col-xl-7 col-lg-6 icon-boxes d-flex flex-column align-items-stretch justify-content-center py-5 px-lg-5">
          <h3>How Does our Application Work?</h3>
          <p style="font-family: 'Poppins', sans-serif;">Our application is here to make language easy for you. As you explore, you'll find a mix of advanced technology and easy-to-use design. It's like a friendly guide through the world of languages.</p>
  
          <div class="icon-box">
            <div class="icon"><img src="images/indexlogin.png" width="40" height="50" style="margin-left: 5px;"></i></div>
            <h4 class="title">Login</h4>
            <p class="description">To begin, select either the "Login" or "Try It Free" buttons.</p>
          </div>
  
          <div class="icon-box">
            <div class="icon"><img src="images/indexupload.png" width="40" height="50" style="margin-left: 5px;"></i></div>
            <h4 class="title">Upload</h4>
            <p class="description">Select the "Upload" button to navigate to your dashboard, where you can either drag and drop your file onto the designated area.</p>
          </div>
  
          <div class="icon-box">
            <div class="icon"><img src="images/indextranslate.png" width="40" height="50" style="margin-left: 5px;"></i></div>
            <h4 class="title">Translate</h4>
            <p class="description">After uploading your file, our application seamlessly translates it into your preferred language, delivering a transformed and accessible document in moments.</p>
          </div>
  
        </div>
      </div>
  
    </div>  <!-- End About Section -->
    </section>

    <!-- ======= Team Section ======= -->
    <section id="team" class="team section-bg">
      <div class="container">

        <div class="section-title-team">
          <h2>Team</h2>
          <div class="underline"></div>
          <p>Young Entrepreneurs that are hoping to revolutionize the digital world</p>
        </div>

        <div class="row">
          <div class="col-lg-12 mt-4 py-3">
            <div class="member d-flex align-items-center" >
              <div class="teampic"><img src="images/ducklean.png" class="img" width="100%" height="100%" alt=""></div>
              <div class="member-info">
                <h4>Lean</h4>
                <span>Project Manager</span>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Obcaecati, ducimus.</p>
                <div class="social">
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                  <a href=""> <i class="bi bi-whatsapp"></i> </a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="member d-flex align-items-start" >
              <div class="teampic"><img src="images/lyrickhamster.png" class="img" width="100%" height="100%" alt=""></div>
              <div class="member-info">
                <h4>Lyrick</h4>
                <span>Backend Developer</span>
                
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Obcaecati, ducimus.</p>
                <div class="social">
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                  <a href=""> <i class="bi bi-whatsapp"></i> </a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 mt-4 mt-lg-0">
            <div class="member d-flex align-items-start" >
              <div class="teampic"><img src="images/jcrabbit.png" class="img-fluid" width="166px" height="208px"alt=""></div>
              <div class="member-info">
                <h4>John</h4>
                <span>Backend Developer</span>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Obcaecati, ducimus.</p>
                <div class="social">
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                  <a href=""> <i class="bi bi-whatsapp"></i> </a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 mt-4">
            <div class="member d-flex align-items-start" >
              <div class="teampic"><img src="images/cattyrone.png" class="img-fluid" width="166px" height="208px" alt=""></div>
              <div class="member-info">
                <h4>Tyrone</h4>
                <span>Frontend Developer</span>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Obcaecati, ducimus.</p>
                <div class="social">
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                  <a href=""> <i class="bi bi-whatsapp"></i> </a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 mt-4">
            <div class="member d-flex align-items-start" >
              <div class="teampic"><img src="images/sywindo.png" class="img-fluid" width="166px" height="208px" alt=""></div>
              <div class="member-info">
                <h4>Sywin</h4>
                <span>Documentation Specialist</span>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Obcaecati, ducimus.</p>
                <div class="social">
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                  <a href=""> <i class="bi bi-whatsapp"></i> </a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      
    </section><!-- End Team Section -->

     <!-- ======= Contact Section ======= -->
     <section id="contact" class="contact">
      <div class="container">

        <div class="section-title">
          <h2>Contact</h2>
          <p>Feel free to reach out to us using the contact form below. Your questions matter, and we are committed to providing timely and helpful responses. We value your input and look forward to connecting with you.</p>
        </div>

        <div>
          <iframe style="border:0; width: 100%; height: 270px;" src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3866.3471686600947!2d120.91254507486468!3d14.291250184576121!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTTCsDE3JzI4LjUiTiAxMjDCsDU0JzU0LjQiRQ!5e0!3m2!1sen!2sph!4v1701362280315!5m2!1sen!2sph" frameborder="0" allowfullscreen></iframe>
        </div>

        <div class="row mt-5">

          <div class="col-lg-4">
            <div class="info">
              <div class="address">
                <i class="bi bi-geo-alt"></i>
                <h4>Location:</h4>
                <p>Lyceum Cavite</p>
              </div>

              <div class="email">
                <i class="bi bi-envelope"></i>
                <h4>Email:</h4>
                <p>info@example.com</p>
              </div>

              <div class="phone">
                <i class="bi bi-phone"></i>
                <h4>Call:</h4>
                <p>+1 5589 55488 55s</p>
              </div>

            </div>

          </div>

          <div class="col-lg-8 mt-5 mt-lg-0">

            
              <div class="row">
                <div class="col-md-6 form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                </div>
              </div>
              <div class="form-group mt-3">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
              </div>
              <div class="form-group mt-3">
                <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
              </div>
              <div class="text-center"><button type="submit" class="rounded-pill py-1">Send Message</button></div>
            

          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="fa-solid fa-arrow-up-wide-short"></i></a>
</main>




  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

  <script src="scripts/landingpage.js"></script>
</body>

</html>
