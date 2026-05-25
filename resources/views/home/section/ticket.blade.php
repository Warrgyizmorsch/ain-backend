<section class="ticket-section">
    <div class="offer-ticket">
      <div class="offer-left">
        <div class="offer-left-content">
          <!-- <p class="small-text">New Students can get </p>
          <h2> UPTO +40% OFF</h2>
          <p class="sub-text">On their first order + A Free CV For Career Support</p> -->
          <p class="small-text">Need Assignment Help? Request a Call Back & Get Connected With an Expert writer within 10 minutes.
          </p>
        </div>
      </div>
      <div class="offer-right">
        <div class="offer-right-content">
          <!-- <p class="small-text">Get</p>
          <h2>Your Offer </h2>
          <p class="sub-text">Today</p> -->
          <a href="/contact-us">
            <button class="claim-btn">Call Back</button>
          </a>
        </div>
      </div>
    </div>
  </section>
  
  <style>
    .ticket-section {
      padding: 1.5rem 0;
      max-width: 1500px;
      margin: auto;
    }
  
    .offer-ticket {
      display: flex;
      justify-content: space-between;
      align-items: stretch;
      background: url('/images/ticket-bg.avif') no-repeat center;
      background-size: cover;
      color: black;
      max-width: 70%;
      height: 225px;
      margin:auto;
      border-radius: 8px;
      overflow: hidden;
    }
  
    .offer-left,
    .offer-right {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 30px;
    }
  
    .offer-left {
      width: 65%;
      text-align: left;
    }
  
    .offer-left-content {
      max-width: 90%;
    }
  
    .offer-left .small-text {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 0 !important;
    }
  
    .offer-left h2 {
      font-size: 3.5rem;
      font-weight: bold;
    }
  
    .offer-left .sub-text {
      font-size: 16px;
      font-weight: bold;
      margin: 0 !important;
    }
  
    .offer-right {
      width: 30%;
      color: white;
      text-align: center;
    }
  
    .offer-right-content {
      width: 100%;
    }
  
    .offer-right .small-text {
      font-size: 16px;
      font-weight: bold;
      margin: 0;
    }
  
    .offer-right h2 {
      font-size: 2rem;
      font-weight: bold;

    }
  
    .offer-right .sub-text {
      font-size: 14px;
      font-weight: bold;
      margin: 0 !important;
    }
  
    .claim-btn {
      background: linear-gradient(135deg, #5e2ced, #4821c3);
      color: white;
      padding: 10px 18px;
      border: none;
      width: 70%;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 15px;
      transition: all 0.3s ease-in-out;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
  
    .claim-btn:hover {
      transform: translateY(-5px);
      background: linear-gradient(135deg, #4821c3, #35189f);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }
  
     @media (max-width: 1024px) {
    .offer-ticket {
      flex-direction: column;
      width: 90%;
      height: auto;
    }

    .offer-left,
    .offer-right {
      width: 100%;
      padding: 25px;
      text-align: center;
    }

    .offer-left h2 {
      font-size: 2.5rem;
    }

    .offer-right h2 {
      font-size: 1.8rem;
    }

    .claim-btn {
      width: 80%;
      font-size: 16px;
    }
  }

    @media (max-width: 768px) {
      .offer-ticket {
        flex-direction: column;
        width: 100%;
      }
  
      .offer-left,
      .offer-right {
        width: 100%;
        padding: 25px;
        text-align: center;
      }
  
      .offer-left h2 {
        font-size: 36px;
      }
  
      .offer-right h2 {
        font-size: 28px;
      }
  
      .claim-btn {
        width: 90%;
        font-size: 16px;
      }
    }
  
    @media (max-width: 480px) {
      .offer-ticket {
        flex-direction: column;
        max-width: 85%;
        height: auto;
      }
      .offer-right{
        padding-top: 0;
      }
      .offer-left{
        padding-bottom: 0;
      }

      .offer-left h2,
      .offer-right h2 {
        font-size: 20px;
      }
  
      .offer-left .small-text,
      .offer-left .sub-text,
      .offer-right .small-text,
      .offer-right .sub-text {
        font-size: 12px;
      }
  
      .claim-btn {
        font-size: 14px;
      }
    }
  </style>
  