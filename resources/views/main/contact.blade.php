<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{asset('css/contact.css')}}" />
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 bg-dark">
      <a class="navbar-brand fw-bold" href="#"> GoFlyHabibi </a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div
        class="collapse navbar-collapse justify-content-between"
        id="navbarNav"
      >
        <ul class="navbar-nav services-nav">
          <li class="nav-item">
            <a class="nav-link" href="#"
              ><i class="bi bi-airplane"></i> Flight</a
            >
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"
              ><i class="bi bi-building"></i> Hotel</a
            >
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"
              ><i class="bi bi-suitcase"></i> Holidays</a
            >
          </li>
        </ul>
        <ul class="navbar-nav align-items-center nav-btns-wrapper">
          <li class="nav-item">
            <a class="nav-link" href="#">
              English <i class="bi bi-caret-down-fill"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="btn btn-sm me-2 become-expert-btn" href="#">
              Become An Expert
            </a>
          </li>
          <li class="nav-item">
            <a class="btn btn-outline-light btn-sm register-btn" href="#">
              Sign In / Register
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
      <h1>Contact Us</h1>
    </section>

    <!-- Contact Section -->
    <div class="map-section">
      <div class="row g-4 m-0 h-100 align-items-stretch">
        <!-- Map -->
        <div class="col-lg-7 d-flex p-0 m-0">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3680.645358707857!2d72.52714597505191!3d23.106502814122095!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e9abf9c0af5e7%3A0x32d15848b204361f!2sVenus%20Atmiya!5e0!3m2!1sen!2s!4v1703141882579"
            width="100%"
            height="100%"
            style="border: 0; min-height: 450px"
            allowfullscreen=""
            loading="lazy"
          >
          </iframe>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-5 d-flex p-0 m-0">
          <div class="contact-form mx-4 w-100">
            <h5 class="mb-4">Send a message</h5>
            <form>
              <div class="mb-3">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Full Name"
                  required
                />
              </div>
              <div class="mb-3">
                <input
                  type="email"
                  class="form-control"
                  placeholder="Email"
                  required
                />
              </div>
              <div class="mb-3">
                <input type="tel" class="form-control" placeholder="Phone" />
              </div>
              <div class="mb-3">
                <textarea
                  class="form-control"
                  rows="4"
                  placeholder="Your Messages"
                ></textarea>
              </div>
              <button type="submit" class="btn form-submit-btn w-100">
                <i class="fa fa-paper-plane me-2"></i> Send a Message
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Newsletter -->
    <div class="container newsletter my-5">
      <div class="row align-items-stretch">
        <!-- Important -->
        <div class="col-lg-6 d-flex">
          <div class="form-img-wrapper w-100">
            <img
              src="{{asset('images/contact/hotel.png')}}"
              alt=""
              class="img-fluid h-100 w-100 object-fit-cover"
            />
          </div>
        </div>
        <div class="col-lg-6 d-flex">
          <div
            class="form-data w-100 d-flex flex-column justify-content-center"
          >
            <h4>Get Updates and More</h4>
            <p>Sign up and we’ll send the best deals</p>
            <form>
              <input
                type="email"
                class="form-control w-50 me-2"
                placeholder="Your Email"
              />
              <button type="submit" class="btn btn-danger mt-4">
                Subscribe
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="footer-title">Contact Us</div>
            <p><i class="fa fa-phone me-2"></i> Toll Free: 6352599244</p>
            <p><i class="fa fa-envelope me-2"></i> contact@GoFlyHabibi.com</p>
          </div>
          <div class="col-md-5 mb-4">
            <div class="footer-title">Company</div>
            <p><a href="#">Home</a></p>
            <p><a href="#">Flight service</a></p>
            <p><a href="#">Hotel service</a></p>
            <p><a href="#">Contact us</a></p>
          </div>
          <div class="col-md-3 mb-4">
            <div class="footer-links">
              <div class="footer-title">Mobile</div>
              <a href="#" class="btn w-100 mb-2"
                ><i class="fab fa-apple me-2"></i> Download on the App Store</a
              >
              <a href="#" class="btn w-100"
                ><i class="fab fa-google-play me-2"></i> Get it on Google
                Play</a
              >
            </div>
          </div>
        </div>
        <div
          class="mt-4 d-flex align-items-center justify-content-between border-top-light pt-4 copy-right-line"
        >
          <p class="mb-0">Copyright &copy; 2024 GoFlyHabibi</p>
          <p class="mb-0">
            Privacy Policy | Terms & Conditions | Cancellations & Payment Policy
            | Disclaimer
          </p>
        </div>
      </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
