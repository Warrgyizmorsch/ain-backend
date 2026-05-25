<section class="testimonials">
    <!-- <h2 class="testimonials-title">Student Testimonials</h2> -->
    <!-- <p class="testimonials-subtitle">
        Find out what students from all over the globe say about our online academic writing services.
    </p> -->
    <div class="testimonials-container">
        <div class="testimonial-card">
            <div class="quote-icon"><img src="/images/quote.png" alt=""></div>
            <p class="testimonial-text">
                This service helped me a lot in completing my assignments on time. The quality of work was excellent!
            </p>
            <div class="testimonial-rating">⭐⭐⭐⭐⭐</div>
            <p class="testimonial-name">Emily Watson</p>
            <p class="testimonial-location">New York, USA</p>
        </div>
        <div class="testimonial-card">
            <div class="quote-icon"><img src="/images/quote.png" alt=""></div>
            <p class="testimonial-text">
                The writers are knowledgeable and deliver top-notch work. I highly recommend it to students!
            </p>
            <div class="testimonial-rating">⭐⭐⭐⭐⭐</div>
            <p class="testimonial-name">Liam Brown</p>
            <p class="testimonial-location">Toronto, Canada</p>
        </div>
        <div class="testimonial-card">
            <div class="quote-icon"><img src="/images/quote.png" alt=""></div>
            <p class="testimonial-text">
                I'm impressed by the professionalism and accuracy of this service. It saved me a lot of time.
            </p>
            <div class="testimonial-rating">⭐⭐⭐⭐⭐</div>
            <p class="testimonial-name">Sophia Martinez</p>
            <p class="testimonial-location">Sydney, Australia</p>
        </div>
    </div>
</section>

<style>
    .testimonials {
    text-align: center;
    padding-top: 20px;


}

.testimonials-title {
    font-size: 28px;
    font-weight: bold;
    color: #333;
}

.testimonials-subtitle {
    font-size: 16px;
    color: #666;
    margin-bottom: 40px;
    position: relative;
    display: inline-block;
}
.testimonials-container {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.testimonial-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 300px;
    text-align: center;
    position: relative;
}

.quote-icon img{
    width: 70px;
    /* font-size: 20px; */
    align-items: center;
    color: #fff;
    background: #fff;
    border-radius: 50%;
    display: inline-block;
    padding: 3px;
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    border: 2px solid ;
}

.testimonial-text{
    font-size: 14px;
    color: #555;
    margin-top: 30px;
}

.testimonial-rating {
    font-size: 16px;
    color: #ffa500;
    margin: 10px 0;
}

.testimonial-name {
    font-size: 16px;
    font-weight: bold;
    color: #222;
}

.testimonial-location {
    font-size: 14px;
    color: #888;
}
@media (max-width: 1024px) {
    .faq-container {
        flex-direction: column;
        align-items: center;
    }

    .faq-column {
        width: 90%;
    }

    .testimonials-container {
        flex-wrap: wrap;
        max-width: 90%;
        margin: auto;
        gap: 30px;
    }

    .testimonial-card {
        max-width: 90%;
    }
}

@media (max-width: 768px) {
    .faq-question {
        font-size: 14px;
        padding: 12px;
    }

    .faq-answer {
        font-size: 13px;
        padding: 8px;
    }

    .testimonials-title {
        font-size: 24px;
    }

    .testimonials-subtitle {
        font-size: 14px;
    }

    .testimonial-card {
        padding: 15px;
    }

    .quote-icon img {
        width: 50px;
        top: -20px;
    }

    .testimonial-text {
        font-size: 13px;
    }

    .testimonial-rating {
        font-size: 14px;
    }

    .testimonial-name {
        font-size: 14px;
    }

    .testimonial-location {
        font-size: 12px;
    }

    .ad-banner-section {
        padding: 30px 0;
    }

    .buttons {
        flex-direction: column;
        gap: 10px;
    }

    .btn {
        width: 80%;
        text-align: center;
    }

    .illustration {
        width: 100px;
    }
}

@media (max-width: 480px) {
    .faq-column {
        width: 100%;
    }

    .faq-question {
        font-size: 13px;
        padding: 10px;
    }

    .faq-answer {
        font-size: 12px;
        padding: 6px;
    }

    .testimonial-card {
        max-width: 100%;
        padding: 10px;
    }

    .quote-icon img {
        width: 40px;
        top: -15px;
    }

    .testimonial-text {
        font-size: 12px;
    }

    .testimonial-rating {
        font-size: 12px;
    }

    .testimonial-name {
        font-size: 13px;
    }

    .testimonial-location {
        font-size: 11px;
    }

    .btn {
        font-size: 14px;
        padding: 10px;
    }

    .illustration {
        width: 80px;
    }
}


</style>