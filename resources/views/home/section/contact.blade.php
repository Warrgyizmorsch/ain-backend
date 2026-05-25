<section class="contact-section" style="padding: 20px; background: #fff;">
  <div class="container" style="max-width: 1140px; margin: 0 auto;">

    <div class="row" style="display: flex; flex-wrap: wrap;">
      {{-- Left Section --}}
      <div class="col-left" style="flex: 1; padding: 20px;">
         <h2 class="section-title">Request a Free Call Back From Our <span>Assignment Experts</span></h2>
        <br>
        <p>
         Need help deciding the right service or want to speak directly with an expert? Fill out the form below, and we’ll call you back within 10 minutes to guide you through the process — no pressure, just helpful advice.

        </p>
        
      </div>

      {{-- Right Section (Form) --}}

      {{-- Form Styling --}}
    

      <div class="form-containersss">
        <form action="{{ route('contact.submit') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="countrycode" id="country_c" />

          <div class="text-center mb-4">
            <h2 class="form-heading">Leave a Message</h2>
          </div>

          <!-- Row 1: Name + Email -->
          <div class="form-row">
            <div class="form-field">
              <label for="user_name" class="form-label">Name</label>
              <input type="text" class="form-control" id="user_name" name="name" placeholder="Your Full Name*"
                required />
            </div>
            <div class="form-field">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Your Email Address*"
                required />
            </div>
          </div>

          <!-- Row 2: Number (Full Width) -->
          <div class="form-field mb-4">
            <label for="phone_number" class="form-label">Number</label>
            <input type="tel" class="form-control" id="phone_number" name="mobile" placeholder="Phone*" required />
          </div>

          <!-- Row 3: Message (Full Width) -->
          <div class="form-field mb-4">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" placeholder="Comment" rows="4"
              required></textarea>
          </div>

          <button type="submit" class="btn btn-yellow w-100">SUBMIT</button>
        </form>

        </form>
      </div>
    </div>
</section>
  <style>
        .form-containersss {
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

        .form-control,
        textarea {
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

        .form-control label {
          font-size: 1rem;
          font-weight: 600;
          color: #333;
          margin-bottom: 0.3rem;
          display: block;
        }

        /* Container for the whole form */
        .form-container-contact {
          width: 100%;
          border-radius: 1.2rem;
          padding: 2rem;
          background:
            linear-gradient(white, white) padding-box,
            linear-gradient(180deg, #45119E, #0182CB) border-box;
          border: 0.3rem solid transparent;
          box-shadow:
            rgba(50, 50, 93, 0.25) 0px 6px 12px -2px,
            rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
          color: #222;
        }

        /* Grid for Name and Email only */
        .form-row {
          display: grid;
          grid-template-columns: repeat(2, 1fr);
          gap: 1.5rem;
          margin-bottom: 1.5rem;
        }

        /* Field with label and input stacked */
        .form-field {
          display: flex;
          flex-direction: column;
        }

        /* Full-width fields use form-field directly (like Number & Message) */

        .form-label {
          font-size: 1rem;
          font-weight: 600;
          color: #333;
          margin-bottom: 0.3rem;
        }

        .form-control,
        textarea.form-control {
          background-color: #f9f9f9;
          color: #333;
          border-radius: 0.8rem;
          border: 2px solid #ccc;
          width: 100%;
          padding: 0.75rem 1rem;
          font-weight: 600;
          font-size: 0.9rem;
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
          width: 100%;
        }

        .btn-yellow:hover {
          background-color: #d4ac0d;
          color: black;
        }

        /* Responsive stacking */
        @media (max-width: 768px) {
          .form-row {
            grid-template-columns: 1fr;
          }
        }

        /* Your existing responsive styles */

        @media (max-width: 991px) {
          .form-container-contact {
            width: 90%;
            padding: 1.5rem;
          }
        }

        @media (max-width: 480px) {
          .form-container-contact {
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

          .form-control,
          textarea {
            font-size: 0.9rem;
            padding: 0.5rem 0.8rem;
          }
        }
      </style>