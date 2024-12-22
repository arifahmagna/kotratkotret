<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kala Senja Coffee</title>
  <!-- Style CSS -->
  <link rel="stylesheet" href="index.css">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">Kala Senja</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-auto d-flex align-items-center">
          <a class="nav-link active" href="#">Home</a>
          <a class="nav-link" href="#about">About</a>
          <a class="nav-link" href="#menu">Menu</a>
          <a class="nav-link" href="#contact">Contact</a>
          <a href="fungsi/login.php">
          <button type="button" class="btn btn-secondary">Login</button>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Jumbotron -->
  <section class="jumbotron text-center bg-light py-5" id="custom-jumbotron"  style="background-image: url('index/foto.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="container">
      <h1 class="display-4">Welcome to Kala Senja</h1>
      <p class="lead">Secangkir kenangan dalam setiap tegukan</p>
      <a href="#" class="btn btn-primary btn-lg">Order Now</a>
    </div>
  </section>

  <!-- About -->
  <section id="about" class="py-5">
    <div class="container text-center">
      <h3 class="fw-bold mb-3">ABOUT US</h3>
      <p class="lead">Setiap cangkir kopi di sini dibuat dengan cinta, untuk memberi kenyamanan di setiap momen.</p>
      <div class="row g-5 align-items-center mt-4">
        <div class="col-lg-5">
          <img src="index/about.jpg" class="img-fluid rounded-4" alt="Tentang Kami">
        </div>
        <div class="col-lg-7 text-start">
          <h4 class="fw-bold lh-2 mb-3">Coffee With a Touch of Love</h4>
          <p>Kala Senja Coffee menghadirkan lebih dari sekedar kopi. Kami membawa semangat kehangatan dan kebersamaan dalam setiap cangkirnya. Sejak pertama kali dibuka, kami telah berkomitmen untuk menawarkan pengalaman kopi yang tak hanya memanjakan lidah, tetapi juga menciptakan kenangan manis bagi setiap pelanggan. Dari biji kopi terbaik hingga penyajian yang penuh cinta, kami ada untuk menemani setiap momen berharga Anda.</p>
          <p>Di Kala Senja, kami percaya bahwa kopi lebih dari sekadar minuman—kopi adalah medium untuk berbagi cerita, tawa, dan momen istimewa. Setiap tegukan membawa kedamaian, setiap aroma mengundang nostalgia. Kami berkomitmen untuk membuat setiap kunjungan Anda menjadi sebuah pengalaman yang tak terlupakan.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Menu -->
  <section id="menu" class="py-5 bg-light">
    <div class="container text-center">
      <h3 class="fw-bold mb-3">Menu Popular</h3>
      <p class="lead">Nikmati kopi dan makanan ringan pilihan kami.</p>
      <div class="row g-4 mt-4">
        <!-- Card Example -->
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4 d-flex justify-content-center">
          <div class="card border-0 shadow-sm">
            <img src="index/img1.jpg" class="card-img-top rounded-top-4" alt="Menu 1">
            <div class="card-body">
              <h5 class="card-title">Coffee Based</h5>
              <p class="card-text">Rp 50.000</p>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4 d-flex justify-content-center">
            <div class="card border-0 shadow-sm">
              <img src="index/img1.jpg" class="card-img-top rounded-top-4" alt="Menu 1">
              <div class="card-body">
                <h5 class="card-title">Coffee Based</h5>
                <p class="card-text">Rp 50.000</p>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4 d-flex justify-content-center">
            <div class="card border-0 shadow-sm">
              <img src="index/img1.jpg" class="card-img-top rounded-top-4" alt="Menu 1">
              <div class="card-body">
                <h5 class="card-title">Coffee Based</h5>
                <p class="card-text">Rp 50.000</p>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4 d-flex justify-content-center">
            <div class="card border-0 shadow-sm">
              <img src="index/img1.jpg" class="card-img-top rounded-top-4" alt="Menu 1">
              <div class="card-body">
                <h5 class="card-title">Coffee Based</h5>
                <p class="card-text">Rp 50.000</p>
              </div>
            </div>
          </div>
      </div>
    </div>
  </section>

  <!-- Contact -->
  <section id="contact" class="py-5">
    <div class="container text-center">
      <h3 class="fw-bold mb-3">Contact</h3>
      <p class="lead">Kami akan dengan senang hati menyambut Anda di "Kala Senja".</p>
      <div class="map-container mb-4">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.456636741013!2d107.30051997453268!3d-6.334844361981748!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6977d4c9f9c6dd%3A0x6ca27b8c4e919b76!2sKala%20Senja%20Kopi!5e0!3m2!1sid!2sid!4v1733410479481!5m2!1sid!2sid" class="map-square" allowfullscreen=""></iframe>
      </div>
      <p>Unnamed Road, Sirnabaya, Telukjambe Timur, Karawang, West Java 41361</p>
      <p>Weekday, pukul 10.00-00.00</p>
      <p>Weekend, pukul 13.00-00.000</p>
      <a href="#" class="mx-2"><i class="fab fa-instagram"></i>kala.senja</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3">
    <div class="container-fluid p-0 ">
        <div class="row">
          <div class="col text-center">
            <h5>KALA SENJA COFFE</h5>
          </div>
        </div>
  
        <div class="row">
            <div class="col text-center">
              <div class="socials-icons mt-3">
              <a href="#" class="mx-2"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="mx-2"><i class="fab fa-twitter"></i></a>
              <a href="#" class="mx-2"><i class="fab fa-instagram"></i></a>
              </div>
        </div>
      </div>
  
      <div class="row">
        <div class="col text-center">
        <div class="links">
          <a href="#home">Home</a>
          <a href="#about">About</a>
          <a href="#menu">Menu</a>
          <a href="#contact">Contact</a>
        </div>
      </div>
      </div>
  
  
      <div class="row">
        <div class="col text-center">
          <div class="credit">
            <p>Created by<a href="">Kala Senja Coffe</a>. | &copy;2024</p>
          </div>
      </div>
      </div>
    </div>
  
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
