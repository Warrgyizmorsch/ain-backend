@props([
    'title',
    'content'
])
<section class="page-section">
    <div class="promo-banner-wrapper">
        <div class="promo-banner">
            <div class="promo-banner__image">
                <img src="/images/books.webp" alt="Assignment Help">
            </div>
            <div class="promo-banner__content">
                <h2 class="banner-heading">
                    {{ $title ?? 'Order our assignment service today and enjoy a special discount!' }}
                </h2>

                <span>
                    {{ $content ?? 'Get help with your assignments easily and stress-free with our expert helpers!' }}
                </span>

                <a href="/upload-your-assignment" class="place-order-btn" style="margin-top: 0px !important;">
                    Order Now
                </a>
            </div>
        </div>
    </div>
</section>


<style>
    .promo-banner-wrapper {
        padding: 2px;
        background: linear-gradient(90.2deg, rgba(1, 47, 95, 1) -0.4%, rgba(56, 141, 217, 1) 106.1%);
        border-radius: 10px;
        display: inline-block;
        margin: 2rem auto;
        transition: box-shadow 0.3s ease;
    }

    .promo-banner-wrapper:hover {
        box-shadow: 0 0 15px rgba(1, 47, 95, 0.3), 0 0 25px rgba(56, 141, 217, 0.4);
    }

    .promo-banner {
        background: white;
        border-radius: 20px;
        padding: 40px 30px;
        max-width: 1200px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
        justify-content: space-around;
        overflow: hidden;
        animation: fadeInUp 1s ease-out forwards;
    }

    .promo-banner__image img {
        width: 300px;
        animation: bounce 3s infinite ease-in-out;
    }

    .promo-banner__content {
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        gap: 1.5rem;
    }

    .banner-heading {
        text-align: left;
        font-size: 28px;
        font-weight: 700;
        color: #1e1e2f;
    }

    /* Responsive Styles */
    @media (min-width: 768px) {
        .promo-banner {
            flex-direction: row;
        }

        .promo-banner__content {
            text-align: left;
            align-items: flex-start;
        }
    }

    @media (max-width: 767px) {
        .promo-banner__image img {
            width: 150px;
        }

        .promo-banner {
            gap: 0;
        }

        .promo-banner__content {
            align-items: center;
            text-align: center;
        }

        .banner-heading {
            font-size: 18px;
            text-align: center;
        }

        .promo-banner__content span {
            font-size: 14px;
        }
    }

    /* Animations */
    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>