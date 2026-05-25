<div {{ $attributes->merge(['class' => 'section-wrapper']) }}>
    @props(['heading', 'paragraph'])

    <div class="custom-section">
        <div class="custom-container">
            <h2 class="section-heading">{{ $heading }}</h2>

            <div class="section-content">
                <div class="section-text">
                    {!! $paragraph !!}
                </div>
                <div class="section-image">
    <img src="images2/WORLDMAP.png" alt="World Map" class="floating-image">
</div>

            </div>

            <div class="section-button">
                <a href="/upload-your-assignment">
                    <button class="place-order-btn">Order Now</button>
                </a>
            </div>
        </div>
    </div>

    <style>
        .floating-image {
    animation: floatY 3s ease-in-out infinite;
    transition: transform 0.3s ease;
}

@keyframes floatY {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-15px);
    }
    100% {
        transform: translateY(0px);
    }
}

        .section-wrapper {
            padding: 40px 20px;
            background-color: #F5F7FF;
        }

        .custom-section {
            max-width: 1200px;
            margin: auto;
        }

        .custom-container {
            background: linear-gradient(to bottom, #3F159A, #0E8FCE);
            border-radius: 24px;
            padding: 40px;
            color: #FFFFFF;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            border: 4px solid #2F3CCB;
        }

        .section-heading {
            font-size: 30px;
            text-align: center;
            font-weight: 700;
            margin-bottom: 30px;
            border-bottom: 2px solid #fff;
            padding-bottom: 20px;
        }

        .section-content {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 30px;
            justify-content: space-between;
            margin-top: 20px;
        }

        .section-text {
            flex: 1 1 50%;
            font-size: 16px;
            line-height: 1.7;
            text-align: justify;
        }

        .section-image {
            flex: 1 1 45%;
            text-align: center;
        }

        .section-image img {
            max-width: 100%;
            border-radius: 12px;
        }

        .section-button {
            text-align: center;
        }

        .order-btn {
            background-color: #4E50E5;
            color: #fff;
            padding: 14px 32px;
            font-size: 18px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .order-btn:hover {
            background-color: #3742d8;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .section-text,
            .section-image {
                flex: 1 1 100%;
                text-align: center;
            }

            .section-text {
                font-size: 16px;
            }

            .section-heading {
                font-size: 28px;
            }
        }

        @media (max-width: 576px) {
            .custom-container {
                padding: 24px;
            }

            .section-text {
                font-size: 12px;
            }
            .section-heading {
                font-size: 19px;
            }

            .order-btn {
                font-size: 16px;
                padding: 12px 24px;
            }
        }
    </style>
</div>
