
  <style>
    

    .video-section {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
    }

    .video-box {
      flex: 1;
      min-width: 300px;
      max-width: 550px;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .video-box:hover {
      transform: scale(1.02);
    }

    .video-box iframe {
      width: 100%;
      height: 315px;
      border: none;
      display: block;
    }

    @media (max-width: 768px) {
      .video-box iframe {
        height: 250px;
      }
    }

    @media (max-width: 500px) {
      .video-box iframe {
        height: 200px;
      }
    }
  </style>


  <section class="page-section video-section">
    <div class="video-box">
      <iframe src="https://www.youtube.com/embed/OULrjYTenjQ" allowfullscreen></iframe>
    </div>
    <div class="video-box">
      <iframe src="https://www.youtube.com/embed/9hLKeJpE2XU" allowfullscreen></iframe>
    </div>
  </section>

