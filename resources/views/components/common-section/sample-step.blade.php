<div>

    <style>

        .accordion-header h3 {
          font-size: 20px;
          color: #000;
          text-align: center;
          margin-left: 12px;
          font-weight: 600;
        }

        .accordion {
          width: 100%;
          max-width: 850px;
          margin: auto;
        }

        .accordion-item {
          border: 2px dashed #5e2ced;
          border-radius: 5px;
          margin: 20px 0;
          position: relative;
        }

        .accordion-header {
          padding: 20px 16px 15px 30px;
          width: 100%;
          background: #fff;
          font-size: 22px;
          text-align: left;
          display: flex;
          justify-content: space-between;
          align-items: center;
          cursor: pointer;
          transition: background 0.3s ease;
        }

        .accordion-header:hover {
          background: rgba(200, 130, 255, 0.5);
          color: #fff !important;
        }

        .accordion-content {
            max-height: 0;
      overflow: hidden;
      padding: 0 15px;
      font-size: 16px;
      color: #555;
      background: #f9f9f9;
      transition: max-height 0.5s ease, padding 0.3s ease;
        }

        .accordion-arrow {
          transition: transform 0.3s ease;
          color: #000;
          font-size: 30px;
        }

        .accordion-item.active .accordion-content {
          padding: 15px;

        }

        .accordion-item.active .accordion-arrow {
          transform: rotate(180deg);
        }

        .step-numbers {
          position: absolute;
          top: -14px;
          left: -20px;
          font-size: 20px;
          font-weight: 600;
          background: #e74c3c;
          color: #fff;
          padding: 7px;
          border-radius: 5px;
        }

        .step-color-1 {
          background-color: #f63131;
        }

        .step-color-2 {
          background-color: #ffbd23;
        }

        .step-color-3 {
          background-color: #259400;
        }

        .container h4 {
          font-size: 22px;
          font-weight: 500;
          text-align: left;
          color: #111;
          margin-left: 40px;
          margin-bottom: 10px;
        }



        .content-wrapper {
          display: flex;
          justify-content: space-between;
          align-items: center;
          flex-wrap: nowrap;
          margin: auto;
        }

        .image-container {
          width: 40%;
        }

        .image-container img {
          width: 90%;
          border-radius: 10px;
        }

        @keyframes fadeInDown {
          from {
            opacity: 0;
            transform: translateY(-10px);
          }

          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        @media (max-width: 768px) {
          .content-wrapper {
            flex-direction: column;
            text-align: center;
            width: 100%;
          }


          .image-container {
            width: 80%;
          }

          .accordion-header h3 {
            font-size: 14px;
          }

          .container h4 {
            font-size: 16px;
            margin: auto;
            text-align: center;
            margin-bottom: 10px;
          }

          .accordion {
            width: 90%;
          }

          .accordion-header {
            font-size: 14px;
            padding: 25px 8px 14px 8px;
          }

          .accordion-content {
            font-size: 14px;
          }
          .step-numbers{
            font-size: 14px;
          }

          .place-order-btn {
            max-width: 90%;
          }
        }
        </style>

    <section class="page-section sample-guide-section">
        <div class="container">
            <h2 class="section-title">Explore<span> Well-Written Assignments </span> in 3 Simple Steps</h2>
            {{-- <p class="section-description">Check out our free assignment samples to see the quality of work we provide. These samples give you a clear idea of how we can help you with your assignments, making it easier for you to decide if our services are the right fit for you.</p>
            <h4>Easy Steps to Download Our Free Assignment Samples</h4> --}}
            <div class="underline"></div>
            <div class="content-wrapper">
                <div class="accordion">

                    <div class="accordion-item">
                        <div class="accordion-header step-1">
                            <div class="step-numbers step-color-1">Step-1:</div>
                            <h3>Explore Assignment Samples</h3>
                            <span class="accordion-arrow">&#9662;</span>
                        </div>
                        <div class="accordion-content">
                            <p>Explore a variety of free academic samples to understand the quality of work we provide. Each sample showcases the level of detail and research that goes into our assignments.</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header step-2">
                            <div class="step-numbers step-color-2">Step-2:</div>
                            <h3>Get Popular Subjects for Math Assignment Help And Math Homework Help</h3>
                            <span class="accordion-arrow">&#9662;</span>
                        </div>
                        <div class="accordion-content">
                            <p>Pick the sample that best matches your assignment type and subject. This will give you a clear idea of how we approach different topics and formats.</p>

                    </div>
                   </div>
                    <div class="accordion-item">
                        <div class="accordion-header step-3">
                            <div class="step-numbers step-color-3">Step-3:</div>
                            <h3>Download Your Sample</h3>
                            <span class="accordion-arrow">&#9662;</span>
                        </div>
                        <div class="accordion-content">
                            <p>Click the download button to get your chosen sample in PDF instantly. You can review it at your own pace and use it as a reference for your assignment.</p>
                    </div>
                    </div>
                    <div style="text-center" class="mt-4">
                        <a href="/upload-your-assignment"><button class="place-order-btn">Order Assignment</button></a>
                    </div>
                </div>
                <div class="image-container">
                    <img src="/images/duplicate-concept-illustration/4788363.jpg" alt="Guide to Download Samples">
                </div>
            </div>
        </div>
    </section>

    <script>
        document.querySelectorAll('.accordion-header').forEach(header => {
          header.addEventListener('click', function () {
            const item = this.parentElement;
            const content = item.querySelector('.accordion-content');
            const arrow = item.querySelector('.accordion-arrow');
            const isActive = item.classList.contains('active');

            // Close all items
            document.querySelectorAll('.accordion-item').forEach(i => {
              i.classList.remove('active');
              i.querySelector('.accordion-content').style.maxHeight = null;
              i.querySelector('.accordion-arrow').style.transform = "rotate(0deg)";
            });

            // Open if it wasn't already active
            if (!isActive) {
              item.classList.add('active');
              content.style.maxHeight = content.scrollHeight + "px";
              arrow.style.transform = "rotate(180deg)";
            }
          });
        });

        // Recalculate max-height on window resize
        window.addEventListener('resize', () => {
          document.querySelectorAll('.accordion-item.active .accordion-content').forEach(content => {
            content.style.maxHeight = content.scrollHeight + "px";
          });
        });
      </script>



</div>
