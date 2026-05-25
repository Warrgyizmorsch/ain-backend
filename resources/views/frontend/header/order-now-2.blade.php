@extends('frontend-layouts.app')

@section('content')



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
        max-width: 100%;
        border-radius: 1.2rem;
        padding: 1.2rem;
        background:
            linear-gradient(white, white) padding-box,
            linear-gradient(180deg, #45119E, #0182CB) border-box;
        border: 0.3rem solid transparent;
        box-shadow:
            rgba(50, 50, 93, 0.25) 0px 6px 12px -2px,
            rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
        color: #222;
    }

    label {
        font-size: .9rem;
        font-weight: 600;
        color: #333;
    }

    .form-control,
    textarea {
        background-color: #f9f9f9;
        height: 55px !important;
        color: #333;
        border-radius: 0.8rem;
        border: 2px solid #ccc;
        width: 100%;
        padding: 0.75rem 1rem;
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


    .form-row {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .form-field {
        margin-bottom: 1.2rem;
        width: 100%;
    }

    .form-field.half {
        flex: 1 1 48%;
    }

    .custom-file-upload {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0.75rem 1rem;
        background-color: #f9f9f9;
        border-radius: 0.8rem;
        border: 2px dashed #ccc;
        color: #555;
    }

    .file-input {
        display: none;
    }

    .upload-text {
        font-size: 0.95rem;
    }

    .order-btn {
        width: 100% !important;
        background: linear-gradient(90deg, #35569D, #3E27C0, #6E40C9);
        color: #ffffff;
        font-weight: 600;
        border-radius: 0.8rem;
        padding: 0.8rem 1.2rem;
        border: none;
        margin: auto;
        font-size: 1.2rem;
        transition: all .3s ease;
    }

    .order-btn:hover {
        background: linear-gradient(90deg, #2c4685, #331fa8, #5d35b3);
        color: #fff;
    }

    h2.form-heading {
        font-weight: 700;
        font-size: 1.8rem;
        color: #3f159a;
        text-align: center;
    }

    /* Base Card Style */
    .estimate-card,
    .order-summary-card {
        background-color: #fff;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 1.5rem;
        font-family: 'Roboto', sans-serif;
    }

    /* Estimated Price Card */
    .review-card {
        background: #ffffff;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .review-title {
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 12px;
        text-align: start;
    }

    .review-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 12px;
        margin-bottom: 12px;
    }

    .review-left {
        display: flex;
        justify-content: space-between;
        height: 44px;
        width: 157px;
        border: 1px solid #e0e0e0;
        border-radius: 22px;
        padding: 10px;
    }

    .stars {
        color: #ffc107;
        font-size: 18px;
    }

    .review-text {
        display: block;
        font-size: 14px;
        color: #555;
    }

    .rating {
        font-weight: bold;
        color: #3a3aff;
    }

    .review-right .number {
        margin: 0;
        font-weight: 600;
        font-size: 20px;
        line-height: 23.8px;
        background: linear-gradient(90deg, #3F159A, #007FFF);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .review-right p {
        margin: 0;
        font-size: 13px;
        color: #000000;
    }

    .review-footer {
        font-size: 14px;
        color: #555;


    }

    /* Review Card Box */

    .review-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Pill Design */

    .review-left {
        display: flex;
        align-items: center;
        gap: 8px;

        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 50px;

        padding: 6px 12px;

        min-width: 170px;
        height: 44px;

        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    /* Thumb */

    .review-thumb {
        width: 22px;
        height: 22px;
        object-fit: contain;
    }

    /* Middle */

    .review-mid {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }

    .review-text {
        font-size: 13px;
        font-weight: 600;
        color: #111;
    }

    .stars {
        font-size: 12px;
        color: #ffc107;
    }

    /* Rating */

    .rating {
        font-size: 14px;
        font-weight: 700;
        background: linear-gradient(90deg, #3F159A, #007FFF);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Right */


    .review-right p {
        margin: 0 !important;
        font-size: 13px;
        color: #000000;
    }

    /* Mobile Responsive */

    @media (max-width: 576px) {

        .review-box {
            flex-direction: column;
            align-items: flex-start;
        }

        .review-right {
            width: 100%;
            text-align: right;
        }

    }




    /* Order Summary Card */
    .order-summary-title {
        background: linear-gradient(90deg, #6E40C9, #5C4FBE, #007FFF);
        color: #ffffff;
        text-align: center;
        font-weight: 600;
        padding: 10px;
        margin: -1.5rem -1.5rem 1rem -1.5rem;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .order-summary-content {
        background-color: #f0f8ff;
        padding: 10px;
        border-radius: 5px;
    }

    /* Promo Card */
    .promo-image {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }

    .order-web {
        border-radius: 10px;
    }

    /* Ensure mobile responsiveness for split fields */
    @media (max-width: 768px) {
        .form-field.half {
            flex: 1 1 100%;
        }
    }

    .price-summary {
        margin-top: 1rem;
        overflow-x: auto;
    }

    .price-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #f9f9f9;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .price-table th,
    .price-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }


    .price-table th {
        background-color: #eef1f5;
        color: #333;
        font-weight: 800;
        width: 40%;
    }

    .price-table td {
        background-color: #fff;
        color: #444;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .price-table tr:last-child td {
        border-bottom: none;
    }

    .price-table td strong {
        color: #45119E;
        font-weight: 800;
        font-size: 1rem;
    }


    /* Section Container */
    .order-benefits {
        padding: 20px;
        background-color: #ae8ce952;
        border-radius: 10px;

    }

    /* Inner Card */
    .benefits-container {
        /* max-width: 800px; */
        margin: auto;
        background: #ffffff;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.08);
        border-radius: 10px;
        padding: 30px 130px;
    }

    /* Title */
    .benefits-title {
        font-size: 1.5em;
        color: #222;
        margin-bottom: 20px;
        font-weight: 600;
        text-align: start;
    }

    /* Benefit List */
    .benefits-list {
        list-style: none;
        color: #444;
        font-size: 1em;
        line-height: 1.7;
    }

    /* Each List Item */
    .benefits-list li {
        position: relative;
        font-weight: 400;
        font-size: 13px;
        color: #444444;
        padding-left: 30px;
        margin-bottom: 15px;
    }

    /* Green Checkmark */
    .checkmark {
        position: absolute;
        left: 0;
        top: 0;
        color: #4caf50;
        font-weight: bold;
        font-size: 1.2em;
    }

    .bg-hero {
        position: relative;
        overflow: hidden;
        padding: 3rem 2rem 1rem;
        box-shadow: rgba(0, 0, 0, 0.06) 0px 2px 4px inset;
        background: linear-gradient(135deg, #e0e7ff 0%, #f3e8ff 50%, #fce7f3 100%);
    }

    .bg-hero::before {
        content: "";
        position: absolute;
        inset: -50%;
        background:
            radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.12) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(236, 72, 153, 0.12) 0%, transparent 50%),
            radial-gradient(circle at 40% 20%, rgba(139, 92, 246, 0.12) 0%, transparent 50%);
        animation: backgroundShift 20s ease-in-out infinite;
        z-index: 0;
    }

    @keyframes backgroundShift {

        0%,
        100% {
            transform: translate(0, 0) rotate(0deg);
        }

        33% {
            transform: translate(30px, -30px) rotate(3deg);
        }

        66% {
            transform: translate(-20px, 20px) rotate(-3deg);
        }
    }

    /* Floating shapes */
    .shape {
        position: absolute;
        opacity: 0.12;
        z-index: 0;
        pointer-events: none;
    }

    .shape-1 {
        width: 300px;
        height: 300px;
        background: linear-gradient(135deg, #6366f1, #ec4899);
        border-radius: 50%;
        top: 8%;
        left: 4%;
        animation: float 15s ease-in-out infinite;
        filter: blur(1px);
    }

    .shape-2 {
        width: 220px;
        height: 220px;
        background: linear-gradient(135deg, #8b5cf6, #ec4899);
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        top: 55%;
        right: 6%;
        animation: float 12s ease-in-out infinite reverse;
        filter: blur(1px);
    }

    .shape-3 {
        width: 160px;
        height: 160px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 50%;
        bottom: 10%;
        left: 14%;
        animation: float 18s ease-in-out infinite;
        filter: blur(1px);
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-30px) rotate(10deg);
        }
    }

    /* Order Summary New Design */

    #orderSummary {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Each Row */

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;

        padding: 10px 14px;

        background: #fff;

        border: 1px solid #3F159A;
        border-radius: 10px;

        font-size: 14px;
    }

    /* Left Text */

    .summary-item span {
        color: #111;
        font-weight: 500;
    }

    /* Right Value */

    .summary-item strong {
        color: #3F159A;
        font-weight: 600;
    }

    .summary-item.final strong {
        font-size: 16px;
        font-weight: 700;
    }

    .custome-class {
        background: linear-gradient(#E8DCFF, #CEE6FF);
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }

    /* List */
    .benefits-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    /* Items */
    .benefits-list li {
        display: flex;
        align-items: flex-start;
        font-weight: 400;
        font-size: 13px;
        color: #444444;
    }


    /* Mobile */
    @media (max-width: 768px) {
        .benefits-grid {
            grid-template-columns: 1fr;
            gap: 0px;
        }

        .benefits-container {
            padding: 30px 10px;
        }
    }

    /* Main Section */
    .why-love-us {
        margin-bottom: 70px;
    }

    .love-container {
        position: relative;
        padding: 30px 20px;
        border-radius: 20px;
        overflow: hidden;

        /* Base gradient */
        background: linear-gradient(90deg,
                #FAECF3 0%,
                #DADAFF 60%,
                #EAF9FC 100%);
    }

    /* White overlay 15% (Figma jaisa) */
    .love-container::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.35);
        pointer-events: none;
    }

    /* Heading */
    .love-title {
        text-align: center;
        font-size: 36px;
        font-weight: 600;
        background: linear-gradient(90deg, #6E40C9, #007FFF);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 50px;
    }

    /* Cards Grid */
    .love-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 45px;
        max-width: 1200px;
        margin: auto;
    }



    /* Each Card */
    .love-card {
        background: rgba(255, 255, 255, 0.6);
        border-radius: 18px;
        padding: 30px 25px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: 0.3s ease;

    }

    /* Hover Effect */
    .love-card:hover {
        transform: translateY(-8px);
    }

    /* Icon */
    .love-icon {
        font-size: 40px;
        margin-bottom: 15px;
    }
     
    .love-icon img{
         filter: brightness(0) !important;
    }


    /* Card Title */
    .love-card h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 12px;
        color: #2F2F2F;
    }

    /* Text */
    .love-card p {
        font-size: 12.52px;
        font-weight: 500;
        line-height: 1.6;
        color: #343434;
    }

    .iti {
        display: block !important;
    }



    /* Responsive */
    @media (max-width: 900px) {
        .love-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .love-cards {
            grid-template-columns: 1fr;
        }

        .love-title {
            font-size: 24px;
        }
    }
</style>


<section style="padding:0px;" class="bg-hero">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    <div class="container py-4">

        <div class="row mt-3 ">
            <div class="col-lg-8 col-md-12 col-sm-12">
                <div class="">
                    <form id="placeOrder" method="POST" enctype="multipart/form-data"
                        class="form-container">
                        @csrf
                        <input type="hidden" name="source_page" value="{{ url()->current() }}">
                        <div class="form-row">
                            <div class="form-field half">
                                <label for="Name">Name</label>
                                <input type="text" name="name" required class="form-control"
                                    placeholder="Enter Your Full Name*">
                            </div>
                            <div class="form-field half">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" required class="form-control"
                                    placeholder="Enter Your Email Address*">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-field half">
                                <label for="country">Country</label>

                                <select name="country" required class="form-control">
                                    <option value="">Select Your Country*</option>

                                    @foreach($countries as $country)
                                    <option value="{{ $country->name }}">
                                        {{ $country->name }}
                                    </option>
                                    @endforeach

                                </select>
                            </div>

                            @if(Auth::user() == '')
                            <div class="form-field half">
                                <label for="phone_number_input">Contact Details</label>
                                <input type="hidden" name="countrycode" id="hidden_country_code">
                                <input type="tel" name="mobile" id="phone_number_input" required class="form-control"
                                    placeholder="Phone*">
                                <input type="hidden" name="full_phone_number" id="hidden_phone_number">
                            </div>
                            @endif
                        </div>

                        <div class="form-row">
                            <div class="form-field half">
                                <label for="serviceSelect">Services</label>
                                <select id="serviceSelect" name="service" required class="form-control">
                                    <option value="">Select Services</option>
                                    <option value="Assignment">Assignment</option>
                                    <option value="Dissertation">Dissertation</option>
                                    <option value="Thesis">Thesis</option>
                                    <option value="Research Project">Research Project</option>
                                </select>
                            </div>

                            <div class="form-field half">
                                <label for="subjectSelect">Subject</label>
                                <select id="subjectSelect" name="subject" required class="form-control">
                                    <option value="">Select Subject</option>
                                    <option value="Matlab">Matlab</option>
                                    <option value="Data Science">Data Science</option>
                                    <option value="Engineering">Engineering</option>
                                    <option value="App Development">App Development</option>
                                    <option value="Web Development">Web Development</option>
                                    <option value="Exam">Exam</option>
                                    <option value="Public Health">Public Health</option>
                                    <option value="Presentation">Presentation (PPT)</option>
                                    <option value="Portfolio">Portfolio</option>
                                    <option value="Research Report">Research Report</option>
                                    <option value="Business Management">Business Management</option>
                                    <option value="Project Management">Project Management</option>
                                    <option value="Essay">Essay</option>
                                    <option value="HRM">HRM</option>
                                    <option value="Economic">Economic</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-field">
                                <label for="workTypeSelect">Work Type</label>
                                <select id="workTypeSelect" name="workType" required class="form-control">
                                    <option value="">Select Work Type</option>
                                    <option value="Standard">Standard</option>
                                    <option value="FirstClass">First Class Work</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-field half">
                                <label for="urgencySelect">Select Urgency</label>
                                <select id="urgencySelect" name="urgency" required class="form-control">
                                    <option value="">Select Urgency</option>
                                    @for ($i = 1; $i <= 15; $i++)
                                        <option value="{{ $i }}">{{ $i }} Day{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                        <option value="16 to 20">16-20 Days</option>
                                        <option value="21+">21+ Days</option>
                                </select>
                            </div>

                            <div class="form-field half">
                                <label for="wordCount">Word Count</label>
                                <input type="number" name="wordCount" id="wordCount" min="250" value="250" required
                                    class="form-control" placeholder="Enter Count">
                            </div>
                        </div>

                        <div class="form-field">
                            <label for="enterTopic">Enter Topic</label>
                            <input type="text" name="topic" id="enterTopic" class="form-control"
                                placeholder="Enter Topic" required>
                        </div>

                        <div class="form-field">
                            <label for="requirements">Specify Your Requirements Here</label>
                            <textarea name="requirements" id="requirements" class="form-control"
                                placeholder="The more instructions you provide, the better our writers can craft a paper that meets your needs."
                                required></textarea>
                        </div>

                        <div class="form-field">
                            <label for="fileUpload" class="custom-file-upload">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                <span class="upload-text">Drop file here or Click to upload</span>
                            </label>
                            <input type="file" name="fileUpload[]" id="fileUpload" class="file-input" multiple>
                        </div>

                        <script>
                            document.getElementById('fileUpload').addEventListener('change', function(event) {
                                var fileName = event.target.files.length > 0 ? event.target.files[0].name : 'Drop file here or Click to upload';
                                document.querySelector('.upload-text').textContent = fileName;
                            });
                        </script>

                        <input type="hidden" name="estimatedPrice" id="estimatedPrice" value="">
                        <input type="hidden" name="discount" id="discount" value="">
                        <input type="hidden" name="finalPrice" id="finalPrice" value="">

                        <div class="form-field">
                            <label><input type="checkbox" id="agreeCheckbox" required> I agree with Privacy Policy and
                                Terms & Conditions</label>
                        </div>

                        <div class="form-field">
                            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        </div>

                        <button id="placeOrderBtn" class="btn order-btn" type="submit">Place Order</button>
                    </form>

                </div>

            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 mt-4 mt-lg-0 custome-class" style="display: flex; flex-direction:column; gap:40px; padding-right:35px; border-radius:12px;">
                <!-- Estimated Price Card -->
                <div class="review-card">

                    <h5 class="review-title">Ratings & Reviews</h5>

                    <div class="review-box">

                        <!-- Left Pill -->
                        <div class="review-left">

                            <img src="/images/review_thumb_image.png" class="review-thumb" />

                            <div class="review-mid">
                                <span class="review-text">Reviews</span>
                                <div class="stars">⭐⭐⭐⭐⭐</div>
                            </div>

                            <span class="rating">4.9</span>

                        </div>

                        <!-- Right -->
                        <div class="review-right">
                            <p class="number">5500</p>
                            <p style="color:#000000">user Reviews</p>
                        </div>

                    </div>

                    <p class="review-footer" style="color: #000000; margin:0px;">
                        Based on 15000+ delivered projects
                    </p>

                </div>

                <div class="estimate-card" style="display: none;">
                    <div class="estimate-content">
                        <label class="estimate-price">Estimated Price</label>
                        <div class="price-details">
                            <span class="basePrice">£100</span>
                            <span class="priceoff">40% OFF</span>
                        </div>
                        <p class="final-price">£<span class="value offerPrice">75</span></p>
                    </div>
                </div>


                <!-- Order Summary Card -->
                <div class="order-summary-card">
                    <h6 class="order-summary-title">Order Summary</h6>
                    <div id="orderSummary">
                        <!-- Summary details will be dynamically added here -->
                    </div>
                </div>

                <!-- Image Banner Card -->

                <a>
                    <img src="/images/offer-banner-vertical-2.png" alt="promo" class="promo-image" />
                </a>

            </div>
        </div>
    </div>
</section>

<section class="courses-section-three pt-4">
    <div class="order-benefits auto-container">
        <div class="benefits-container">
            <h2 class="benefits-title">With Every Order, You Will Receive These Benefits:</h2>
            <div class="benefits-grid">

                <!-- Left Column -->
                <ul class="benefits-list" style="padding-left:10px;">
                    <li><span class="checkmark"><img src="images/hugeicons_tick-04.png" alt="right-icon" /></span>120% money-back guarantee if we fail to deliver.</li>
                    <li><span class="checkmark"><img src="images/hugeicons_tick-04.png" alt="right-icon" /></span>Free plagiarism report with every order.</li>
                    <li><span class="checkmark"><img src="images/hugeicons_tick-04.png" alt="right-icon" /></span>24/7 customer support for any queries.</li>
                    <li><span class="checkmark"><img src="images/hugeicons_tick-04.png" alt="right-icon" /></span>Complete ownership of your work.</li>
                </ul>

                <!-- Right Column -->
                <ul class="benefits-list" style="padding-left:10px;">
                    <li><span class="checkmark"><img src="images/hugeicons_tick-04.png" alt="right-icon" /></span>On-time delivery guaranteed.</li>
                    <li><span class="checkmark"><img src="images/hugeicons_tick-04.png" alt="right-icon" /></span>Exclusive offers and discounts for clients.</li>
                    <li><span class="checkmark"><img src="images/hugeicons_tick-04.png" alt="right-icon" /></span>Unlimited free revisions if needed.</li>
                    <li><span class="checkmark"><img src="images/hugeicons_tick-04.png" alt="right-icon" /></span>Privacy of your confidential information.</li>
                </ul>

            </div>
        </div>
    </div>
</section>

<section class="why-love-us">
    <div class="love-container auto-container">

        <h2 class="love-title">“Why Students Love Us”</h2>

        <div class="love-cards">

            <!-- Card 1 -->
            <div class="love-card">
                <div class="love-icon"><img src="images/vaadin_academy-cap.png" alt="vaadin-academy-cap" /></div>
                <h3>Global Academic Excellence</h3>
                <p>
                    "Empowering students worldwide with expert-led academic support, ensuring top grades through quality research and 24/7 dedication."
                </p>
            </div>

            <!-- Card 2 -->
            <div class="love-card">
                <div class="love-icon"><img src="images/fluent_sparkle-28-filled.png" alt="fluent_sparkle-28-filled" /></div>
                <h3>Stress-Free Learning</h3>
                <p>
                    "Combining years of expertise with a passion for excellence to make assignments simpler, faster, and stress-free for every student."
                </p>
            </div>

            <!-- Card 3 -->
            <div class="love-card">
                <div class="love-icon"><img src="images/Vector.png" alt="Vector" /></div>
                <h3>Result-Oriented Trust</h3>
                <p>
                    " From PhD experts to plagiarism-free reports, we provide everything you need to excel in your academic journey."
                </p>
            </div>

        </div>

    </div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var phoneInput = document.querySelector("#phone_number_input");
        var phoneInputInstance = window.intlTelInput(phoneInput, {
            separateDialCode: true,
            preferredCountries: ["gb"],
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });

        function updatePhoneNumber() {
            var fullNumber = phoneInputInstance.getNumber();
            var countryData = phoneInputInstance.getSelectedCountryData();
            var countryCode = countryData.dialCode;

            document.querySelector("#hidden_phone_number").value = fullNumber;
            document.querySelector("#hidden_country_code").value = countryCode;
        }

        updatePhoneNumber();

        phoneInput.addEventListener('input', function() {
            updatePhoneNumber();
        });

        phoneInput.addEventListener('countrychange', function() {
            updatePhoneNumber();
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var encryptionKey = "your-encryption-key"; // Replace with a strong key
        var iv = CryptoJS.enc.Hex.parse('1234567890123456'); // Example IV

        function decryptData(encryptedData) {
            try {
                var bytes = CryptoJS.AES.decrypt(encryptedData, encryptionKey, {
                    iv: iv,
                    mode: CryptoJS.mode.CBC,
                    padding: CryptoJS.pad.Pkcs7
                });
                return bytes.toString(CryptoJS.enc.Utf8);
            } catch (error) {
                console.error('Decryption failed:', error);
                return 'Decryption error';
            }
        }

        // Retrieve encrypted data from query parameters
        var urlParams = new URLSearchParams(window.location.search);
        var encryptedService = urlParams.get('service');
        var encryptedWorkType = urlParams.get('workType');
        var encryptedSubject = urlParams.get('subject');
        var encryptedUrgency = urlParams.get('urgency');
        var encryptedWordCount = urlParams.get('wordCount');
        var encryptedPrice = urlParams.get('price');
        var encryptedBasePrice = urlParams.get('basePrice');
        var encryptedDiscount = urlParams.get('discount');

        // Check if any query parameters are present
        if (encryptedService || encryptedWorkType || encryptedSubject || encryptedUrgency || encryptedWordCount || encryptedPrice || encryptedBasePrice || encryptedDiscount) {
            // Decrypt data
            var decryptedService = decryptData(encryptedService);
            var decryptedWorkType = decryptData(encryptedWorkType);
            var decryptedSubject = decryptData(encryptedSubject);
            var decryptedUrgency = decryptData(encryptedUrgency);
            var decryptedWordCount = decryptData(encryptedWordCount);
            var decryptedPrice = decryptData(encryptedPrice);
            var decryptedBasePrice = decryptData(encryptedBasePrice);
            var decryptedDiscount = decryptData(encryptedDiscount);

            // Populate form with decrypted data
            function populateFormWithDecryptedData() {
                $('#serviceSelect').val(decryptedService);
                $('#workTypeSelect').val(decryptedWorkType);
                $('#subjectSelect').val(decryptedSubject);
                $('#urgencySelect').val(decryptedUrgency);
                $('#wordCount').val(decryptedWordCount);
                $('#enterTopic').val(decryptedTopic);
                $('#requirements').val(decryptedRequirements);
            }

            populateFormWithDecryptedData();
        } else {
            // If no query parameters, clear the form or set placeholders
            function clearForm() {
                $('#serviceSelect').val('').attr('placeholder', 'Please select');
                $('#workTypeSelect').val('').attr('placeholder', 'Please select');
                $('#subjectSelect').val('').attr('placeholder', 'Please select');
                $('#urgencySelect').val('').attr('placeholder', 'Please select');
                $('#wordCount').val('').attr('placeholder', 'Please enter');
                $('#enterTopic').val('');
                $('#requirements').val('');
            }

            clearForm();
        }
    });
</script>
<script>
    $(document).ready(function() {
        function calculatePrice() {
            var service = $('#serviceSelect').val();
            var type = $('#workTypeSelect').val();
            var urgency = $('#urgencySelect').val();
            var wordCount = Math.max($('#wordCount').val(), 250);
            var subject = $('#subjectSelect').val();

            var basePricePerWord = 0.03;
            var basePrice = basePricePerWord * wordCount;
            // Apply word count multipliers
            if (wordCount >= 250 && wordCount < 500) {
                basePrice *= 2.67;
            } else if (wordCount >= 500 && wordCount < 1000) {
                basePrice *= 2.22;
            } else if (wordCount >= 1000 && wordCount < 2000) {
                basePrice *= 1.94;
            } else if (wordCount >= 2000 && wordCount < 3000) {
                basePrice *= 1.67;
            } else if (wordCount >= 3000 && wordCount < 4000) {
                basePrice *= 1.30;
            } else if (wordCount >= 4000 && wordCount < 5000) {
                basePrice *= 1.13;
            } else if (wordCount >= 5000) {
                basePrice *= 1.17;
            }
            var serviceMultiplier = (service === 'Dissertation' || service === 'Thesis' || service === 'Research Project') ? 1.1 : 1;
            var typeMultiplier = (type === 'FirstClass') ? 1.3 : 1;
            var urgencyMultiplier = 1;

            switch (urgency) {
                case '1':
                    urgencyMultiplier = 2.0;
                    break;
                case '2':
                    urgencyMultiplier = 1.5;
                    break;
                case '3':
                    urgencyMultiplier = 1.4;
                    break;
                case '4':
                    urgencyMultiplier = 1.3;
                    break;
                case '5':
                    urgencyMultiplier = 1.2;
                    break;
                case '6':
                    urgencyMultiplier = 1.15;
                    break;
                case '7':
                    urgencyMultiplier = 1.1;
                    break;
                case '8':
                    urgencyMultiplier = 1.09;
                    break;
                case '9':
                    urgencyMultiplier = 1.07;
                    break;
                case '10':
                    urgencyMultiplier = 1.05;
                    break;
                case '11':
                    urgencyMultiplier = 1.04;
                    break;
                case '12':
                    urgencyMultiplier = 1.03;
                    break;
                case '13':
                    urgencyMultiplier = 1.02;
                    break;
                case '14':
                    urgencyMultiplier = 1.01;
                    break;
                case '15':
                    urgencyMultiplier = 1.0;
                    break;
                case '16 to 20':
                    urgencyMultiplier = 0.95;
                    break;
                case '21+':
                    urgencyMultiplier = 0.9;
                    break;
            }

            var estimatedPrice = basePrice * serviceMultiplier * typeMultiplier * urgencyMultiplier;

            var discountPercentage = parseFloat($('.priceoff').text().replace('% OFF', '').trim()) / 100;
            var discount = estimatedPrice * discountPercentage;

            var finalPrice = estimatedPrice - discount;

            // Update the price quotation and estimated price
            $('.offerPrice').text(finalPrice.toFixed(2));
            $('.basePrice').text('£ ' + estimatedPrice.toFixed(2));

            updateSummary(service, type, subject, urgency, wordCount, estimatedPrice, finalPrice, discountPercentage, discount);
        }

        function updateSummary(service, type, subject, urgency, wordCount, estimatedPrice, finalPrice, discountPercentage, discount) {
            let summaryHtml = `

         
        <div class="summary-item">
            <span>Service</span>
            <strong>${service || 'N/A'}</strong>
        </div>
        <div class="summary-item">
            <span>Work Type</span>
            <strong>${type || 'N/A'}</strong>
        </div>

        <div class="summary-item">
            <span>Subject</span>
            <strong>${subject || 'N/A'}</strong>
        </div>

        <div class="summary-item">
            <span>Urgency</span>
            <strong>${urgency} Days</strong>
        </div>

        <div class="summary-item">
            <span>Word Count</span>
            <strong>${wordCount}</strong>
        </div>

        <div class="summary-item">
            <span>Estimated Price</span>
            <strong>£${estimatedPrice.toFixed(2)}</strong>
        </div>

        <div class="summary-item">
            <span>Discount</span>
            <strong>${(discountPercentage * 100).toFixed(0)}% (£${discount.toFixed(2)})</strong>
        </div>

        <div class="summary-item final">
            <span>Final Price</span>
            <strong>£${finalPrice.toFixed(2)}</strong>
        </div>

    `;

            $('#orderSummary').html(summaryHtml);

            // Update the hidden input fields
            $('#estimatedPrice').val(estimatedPrice.toFixed(2));
            $('#discount').val(`${(discountPercentage * 100).toFixed(0)}% (£${discount.toFixed(2)})`);
            $('#finalPrice').val(finalPrice.toFixed(2));
        }

        // Trigger price calculation and summary update when any input changes
        $('#serviceSelect, #workTypeSelect, #subjectSelect, #urgencySelect, #wordCount').on('change keyup', function() {
            calculatePrice();
        });

        // Trigger initial calculation and summary update
        calculatePrice();

    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('placeOrder');
        const submitBtn = document.getElementById('placeOrderBtn');

        // Optional: Create a toast element like your previous code
        const showToast = (msg, success = false) => {
            Swal.fire({
                icon: success ? 'success' : 'error',
                title: success ? 'Success' : 'Error',
                text: msg
            });
        };

        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(form);

            // Add hidden phone fields (if intlTelInput is being used)
            const phoneInput = document.querySelector("#phone_number_input");
            if (phoneInput && window.intlTelInput) {
                const iti = window.intlTelInputGlobals.getInstance(phoneInput);
                formData.set('full_phone_number', iti.getNumber());
                formData.set('countrycode', iti.getSelectedCountryData().dialCode);
                console.log('window intltelinput')
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Placing Order...';

            try {
                const response = await fetch("{{ route('upload-from-order-now') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    form.reset();
                    showToast(data.message || 'Order placed successfully!', true);
                    // Redirect to thank-you page after a delay
                    setTimeout(() => {
                        window.location.href = "{{ route('thank-you') }}";
                    }, 2000);
                } else {
                    showToast(data.message || 'Something went wrong.');
                }
            } catch (error) {
                console.error(error);
                showToast('An error occurred while placing your order.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Place Order';
            }
        });
    });
</script>

@endsection