@extends('frontend-layouts.app')

@section('content')

<style>
  /* Global Styling */
  h1 {
    font-size: 35px;
    font-weight: 600;
    color: black;
  }

  p {
    position: relative;
    line-height: 1.8em;
    font-size: large;
    color: black;
    text-align: justify;
  }

  .heading-contact {
    font-size: 2rem;
    font-weight: bold;
    color: #222;
    text-align: center;
    position: relative;
  }

  .heading-contact:before {
    content: "";
    border-bottom: 6px solid #5e2ced;
    display: block;
    width: 5%;
    position: absolute;
    bottom: -2px;
    left: 50%;
    transform: translateX(-50%);
  }

  .heading-contact:after {
    content: "";
    border-bottom: 1px solid #5e2ced;
    display: block;
    width: 25%;
    margin: 8px auto 0;
  }

  h2 {
    color: #102a43;
    font-size: 32px;
    font-weight: 900;
    text-align: center;
    margin-bottom: 10px;
  }

  h3 {
    color: #45119E;
    font-size: 24px;
    margin-bottom: 10px;
    font-weight: 600;
  }

  ul {
    padding-left: 20px;
    color: #555;
  }

  ul li {
    margin-bottom: 8px;
    font-size: 1rem;
  }

  .social-media ul, .social-links {
    list-style: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1.5rem;
    align-items: center;
  }

  a {
    color: #0182CB;
    text-decoration: none;
  }

  a:hover i {
    transform: scale(1.2);
    transition: transform 0.3s;
  }

  .section {
    margin: 30px;
  }

  .intro h2 {
    text-align: left;
    margin-bottom: 10px;
  }

  .intro p {
    margin-bottom: 15px;
  }

  .section.submit-inquiry h2 {
    font-size: 2rem;
    text-align: center;
    margin-bottom: 1rem;
    color: #333;
  }

  .flex-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  margin-top: 2rem;
}

.flex-item {
  flex: 1 1 calc(50% - 1rem); /* 2 items per row, with gap */
  background: #dacaff;
  padding: 1.5rem;
  border-radius: 1rem;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  transition: 0.3s ease;
}

.flex-item:hover {
  box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px,
              rgba(0, 0, 0, 0.23) 0px 6px 6px;
}
.flex-item h3 {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
}
.flex-item p {
  font-size: 1rem;
  color: #333;
  margin-bottom: 0;
}

/* Responsive for mobile */
@media (max-width: 768px) {
  .flex-item {
    flex: 1 1 100%; /* Stack vertically */
  }
}

  /* Responsive Styling */
  @media (max-width: 991px) {
    .row {
      flex-direction: column;
    }
    .col-left, .col-right {
      flex: 1 1 100%;
      padding: 15px;
    }
    .section.submit-inquiry .grid {
      grid-template-columns: 1fr;
      gap: 1.5rem;
    }
  }

  @media (max-width: 768px) {
    .heading-contact {
      font-size: 1.8rem;
    }
    h2 {
      font-size: 26px;
    }
    h3 {
      font-size: 20px;
    }
    p {
      font-size: 1rem;
      text-align: left;
    }
    .section.submit-inquiry {
      padding: 2rem 1rem;
    }
  
    .contact-section {
      padding: 40px 10px;
    }
    .social-links {
      flex-direction: column;
      gap: 1rem;
    }
    .social-links a {
      font-size: 18px;
    }
  }

  @media (max-width: 480px) {
    .heading-contact {
      font-size: 1.5rem;
    }
    h2 {
      font-size: 22px;
    }
    h3 {
      font-size: 18px;
    }
    .section.submit-inquiry {
      padding: 1.5rem 1rem;
    }
    .social-links a {
      font-size: 16px;
    }
	.email{
		font-size: .7rem;
	}
  }
  @media (max-width: 320px) {
  .heading-contact {
    font-size: 1.3rem;
  }

  h1 {
    font-size: 28px;
  }

  h2 {
    font-size: 20px;
  }

  h3 {
    font-size: 16px;
  }

  p {
    font-size: 0.9rem;
    line-height: 1.6em;
    text-align: left;
  }

  ul li {
    font-size: 0.9rem;
  }

  .section {
    margin: 20px 10px;
  }

  .section.submit-inquiry {
    padding: 1rem;
  }

  

  .social-links {
    flex-direction: column;
    gap: 0.8rem;
  }

  .social-links a {
    font-size: 16px;
  }

  .contact-section {
    padding: 30px 10px;
  }

  .col-left, .col-right {
    padding: 10px;
  }
}
</style>
<div class="head-container mt-6 px-4 py-6">
        <div class="header-content">
            <img src="assets/media/avatars/assignment_logo.png" alt="Assignment Logo" class="logo-img" />
            <h1 class="section-title">Get in Touch with Assignment In Need Experts</h1>
        </div>
        <a href="/free-samples" class="primary-button mt-4">Get 100% Original Assignments</a>

        <div class="icon-section mt-6">
            <div class="icon-item">
                <img src="/images2/certificate.png" alt="100% Original Document" />
                <p>100% Original</p>
            </div>
            <div class="icon-item">
                <img src="/images2/sketch-book.png" alt="AI Free Content" />
                <p>AI Free Content</p>
            </div>
            <div class="icon-item">
                <img src="/images2/personal-data.png" alt="Free CV" />
                <p>Free CV on Every Order</p>
            </div>
            <div class="icon-item">
                <img src="/images2/24-hours.png" alt="24/7 Availability" />
                <p>24×7 Available</p>
            </div>
            <div class="icon-item">
                <img src="/images2/role-model.png" alt="Experienced Writers" />
                <p>Experienced Writers</p>
            </div>
            <div class="icon-item">
                <img src="/images2/article.png" alt="Plagiarism Report" />
                <p>Plagiarism Report</p>
            </div>
        </div>
</div>
<section class="contact-section" style="padding: 20px 0; background: #fff;">
  <div class="container" style="max-width: 1140px; margin: 0 auto;">
    
    <div class="row" style="display: flex; flex-wrap: wrap;">
      {{-- Left Section --}}
      <div class="col-left" style="flex: 1; padding: 20px;">
        <h2 class="heading-contact">Contact Us</h2>
        <br>
        <p>
          We're here to help you every step of the way at Assignment In Need. Whether you have a question,
          need help with ordering, would like to track your assignment, or just want to speak to a real
          person, we're always just a message away.
        </p>
        <p>
          We know how annoying deadlines and complex tasks are—that's why our support team is here to make sure
          that your experience is smooth, hassle-free, and completely transparent.
        </p>
      </div>

      {{-- Right Section (Form) --}}
      <div class="col-right" style="flex: 1; padding: 20px;">
       <div class="form-container">
    {{-- Error Alert --}}
    @if ($errors->any())
        <div id="errorAlert" class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <script>
            setTimeout(function () {
                const errorAlert = document.getElementById('errorAlert');
                if (errorAlert) {
                    errorAlert.style.display = 'none';
                }
            }, 10000); // Hide after 10 seconds
        </script>
    @endif

    {{-- Form --}}@if(session('success'))
    <div class="alert alert-success" style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #badbcc;">
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger" style="background: #f8d7da; color: #842029; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c2c7;">
        <ul class="mb-0" style="list-style-type: none; padding-left: 0;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
 <form action="{{ route('contact.submit') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="countrycode" id="country_c" />
  
      <div class="text-center mb-2">
          <h2 class="form-heading">Leave a Message</h2>
      </div>
  
      <div class="mb-2">
          <label for="user_name" class="form-label">Name</label>
          <input type="text" class="form-control" id="user_name" name="name" placeholder="Your Full Name*" required />
      </div>
  
      <div class="mb-2">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Your Email Address*" required />
      </div>
  
      <div class="mb-2">
          <label for="phone_number" class="form-label">Number</label>
          <input type="tel" class="form-control" id="phone_number" name="mobile" placeholder="Phone*" required />
      </div>
  
      <div class="mb-4">
          <label for="message" class="form-label">Message</label>
          <textarea class="form-control" id="message" name="message" placeholder="Comment" rows="4" required></textarea>
      </div>
  
      <button type="submit" class="btn btn-yellow w-100">SUBMIT</button>
  </form>
</div>

{{-- Form Styling --}}
<style>
  .head-container {
            background: linear-gradient(135deg, rgba(214, 216, 255, 0.9), rgba(227, 195, 255, 0.9));
            /* background: linear-gradient(white, white) padding-box,
                  linear-gradient(135deg, #6e40c9, #007fff) border-box;
      border-bottom: 4px solid transparent; */
            border-radius: 0 0 1.5rem 1.5rem;
            padding: 2rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.09);
            animation: fadeIn 1s ease-in-out;
            text-align: center;
        }

        .header-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }



        .logo-img {
            position: absolute;
            right: 20px;
            width: 80px;
            height: 80px;
            mix-blend-mode: multiply;
            animation: fadeInUp 1s ease-in-out;
        }
        .primary-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(to right, #6e40c9, #007fff);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 1.2s ease-in-out;
        }

        .primary-button:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .icon-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            animation: fadeInUp 1.4s ease-in-out;
        }

        .icon-item {
            background: linear-gradient(white, white) padding-box,
                linear-gradient(135deg, #6e40c9, #007fff) border-box;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.09);
            transition: transform 0.3s ease;
            text-align: center;
        }

        .icon-item:hover {
            transform: scale(1.05);
            box-shadow: 2px 8px 18px rgba(0, 0, 0, 0.15);
        }

        .icon-item img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            mix-blend-mode: multiply;
            margin-bottom: 0.5rem;
        }

        .icon-item p {
            font-size: 0.8rem;
            background: linear-gradient(135deg, #6e40c9, #007fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
            margin: 0;
        }



.form-container {
    width: 450px;
    max-width: 100%;
    border-radius: 1.2rem;
    padding: 1.2rem;
    margin: auto;
    background: 
        linear-gradient(white, white) padding-box,
        linear-gradient(180deg, #45119E, #0182CB) border-box;
    border: 0.3rem solid transparent;
    box-shadow: 
        rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, 
        rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    color: #222;
}

.form-control, textarea {
    background-color: #f9f9f9;
    height: 45px;
    color: #333;
    border-radius: 0.8rem;
    border: 2px solid #ccc;
    width: 100%;
    padding: 0.75rem 1rem;
    font-weight: 600;
    font-size: .9rem;
    outline: none;
    transition: border-color 0.3s, box-shadow 0.3s;
}

textarea.form-control {
    height: auto;
    min-height: 120px;
    resize: vertical;
}

.form-control:focus {
    border-color: #45119E;
    box-shadow: 0 0 0 4px rgba(69, 17, 158, 0.2);
    background-color: #fff;
}

.btn-yellow {
    background-color: #f1c40f;
    color: black;
    font-weight: 600;
    border-radius: 0.8rem;
    padding: 0.8rem 1.2rem;
    border: none;
    font-size: 1.2rem;
}

.btn-yellow:hover {
    background-color: #d4ac0d;
    color: black;
}

h2.form-heading {
    font-weight: 700;
    font-size: 1.8rem;
    color: #3f159a;
}

label {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
}

/* ============================
    Responsive Styling
============================ */

/* Tablet and below */
@media (max-width: 991px) {
    .form-container {
        width: 90%;
        padding: 1.5rem;
    }
}

/* Mobile large */
@media (max-width: 768px) {
    .form-container {
        width: 95%;
        padding: 1.2rem;
    }

    h2.form-heading {
        font-size: 1.75rem;
    }

    .btn-yellow {
        font-size: 1rem;
        padding: 0.7rem 1rem;
    }

    label {
        font-size: 1rem;
    }

    .form-control, textarea {
        font-size: 0.95rem;
        padding: 0.6rem 0.9rem;
    }
}

/* Mobile small */
@media (max-width: 480px) {
    .form-container {
        width: 100%;
        padding: 1rem;
    }

    h2.form-heading {
        font-size: 1.5rem;
    }

    .btn-yellow {
        font-size: 0.95rem;
        padding: 0.6rem 0.8rem;
    }

    label {
        font-size: 0.95rem;
    }

    .form-control, textarea {
        font-size: 0.9rem;
        padding: 0.5rem 0.8rem;
    }
}
</style>

      </div>
    </div>

    <div class="section intro">
      <h2>How Can We Help You?</h2>
      <p>
        Our group of professionals is well-trained to handle all types of questions ranging from general ones
        about our services to specific questions about your orders. You can contact us for:
      </p>
      <ul>
        <li>Assignment and dissertation-related questions</li>
        <li>Order placing help</li>
        <li>Modification or updates to an existing order</li>
        <li>Payment issues or invoice inquiries</li>
        <li>Technical issues on the website</li>
        <li>Complaints or feedback</li>
        <li>Collaboration or business inquiries</li>
        <li>No matter your concern, we strive to respond quickly and assist you efficiently.</li>
      </ul>
    </div>

    <div class="section submit-inquiry ">
      <h2>Submit an Inquiry</h2>
      <p style="text-align: center;">You can reach us using any of the options below. We're here to help!</p>

      <div class="flex-grid">
  <div class="flex-item">
    <h3>Email Support</h3>
    <p>Send us an email and our team will respond promptly.</p>
    <p><a class="email" href="mailto:order@assignnmentinneed.com">order@assignnmentinneed.com</a></p>
  </div>

  <div class="flex-item">
    <h3>Call Us</h3>
    <p>Need to talk? We're available 24/7 to assist you.</p>
    <p><a href="tel:+447917481696">+447917481696</a></p>
  </div>

  <div class="flex-item">
    <h3>Operating Hours</h3>
    <p>We're available 24/7—whenever you need us, wherever you are.</p>
  </div>

  <div class="flex-item">
    <h3>Physical Address</h3>
    <p>Roehampton Ln, London SW15 5PU, Kingdom </br> International House, Constance Street, London E16, United Kingdom</p>
  </div>
</div>

    </div>

    <section style="padding: 60px 20px; background: #ffffff;">
      <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <h2>Connect with Us on Social Media</h2>
        <p>We are on all the major social platforms. Connect, ask questions, or provide your comments freely:</p>
        
        <div class="social-links">
          <a href="https://www.facebook.com/people/Assignmentinneedofficial/61564613120071/" target="_blank" style="color: #1877f2; font-size: 20px;">
            <i class="fab fa-facebook-square"></i> Facebook
          </a>
          <a href="https://www.instagram.com/assignmentinneedofficial/" target="_blank" style="color: #e4405f; font-size: 20px;">
            <i class="fab fa-instagram"></i> Instagram
          </a>
          <a href="https://x.com/assignment_in" target="_blank" style="color: #1da1f2; font-size: 20px;">
            <i class="fab fa-twitter"></i> Twitter
          </a>
          <a href="https://in.pinterest.com/assignnmentinneed66/" target="_blank" style="color: #bd081c; font-size: 20px;">
            <i class="fab fa-pinterest"></i> Pinterest
          </a>
          <a href="https://www.youtube.com/@assignmentinneed1169" target="_blank" style="color: #ff0000; font-size: 20px;">
            <i class="fab fa-youtube"></i> YouTube
          </a>
        </div>
      </div>
    </section>

  </div>
</section>

@endsection
