<section class="bg-hero">
    @props(['title', 'subtitle'])
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    <div class="container-section">
        <div class="hero-content">
            <div class="content-column">
                <h1 class="main-heading">
                    {!! $title !!}
                </h1>
                <div class="content-paragraph">
                    <p>
                        {!! $subtitle !!}
                    </p>
                </div>
                <div class="hero-v2__chips">
                    <div class="chip" style="--d: 0ms">
                        <span  aria-hidden="true">
                            ⭐
                        </span>
                        <span style="color:#000000">
                            Trusted by 5,126+ students with a 4.8/5 overall rating
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-column">
                <div class="form-container">
                    @include('home.section.custom-form')
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ✅ ONLY BACKGROUND UPDATED (bubbles like old design) */
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

        /* ✅ content always above bubbles */
        .container-section {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: auto;
            padding: 0 0.5rem;
        }

        /* --- REST OF YOUR CSS EXACT SAME --- */
        .hero-content {
            display: flex;
            align-items: stretch;
            gap: 2rem;
            align-items: center;
            /* vertical align */
            justify-content: center;
            /* horizontal center */
        }

        /* 
        .hero-content > .content-column {
            flex: 1;
            max-width: 800px;  /* control width */
        margin: 0 auto;
        text-align: center;
        /* text center */
        }

        */ .hero-content>.content-column {
            flex: 1 1 55%;
            max-width: 55%;
        }

        .hero-content>.form-column {
            flex: 0 0 auto;
            margin-left: auto;
        }

        .form-column .form-container {
            width: min(420px, 100%);
        }

        @media (max-width: 768px) {
            .hero-content {
                flex-direction: column;
            }

            .hero-content>.content-column,
            .hero-content>.form-column {
                max-width: 100%;
                margin-left: 0;
            }
        }

        .form-container {
            margin: auto;
            display: flex;
            justify-content: center;
        }

        .main-heading {
            background: linear-gradient(to bottom, #3F159A, #0E8FCE);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.6rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }

        .content-paragraph p {
            font-size: 1rem;
            line-height: 1.3;
            /* color: #000; */
            /* font-weight: 500; */
            margin-bottom: 1rem;
            text-align: justify;
        }

        .content-list-item {
            display: flex;
            flex-wrap: wrap;
            gap: 1.2rem;
        }

        .content-list {
            padding-left: 20px;
            margin: 0;
        }

        .content-list li {
            color: #333;
            list-style-type: square;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .Trusted-paragraph {
            margin-top: 1rem;
            font-size: 1rem;
            font-weight: 500;
        }

        /* ✅ NEW: White card look (only for those 3 blocks) */
        .trust-cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            width: 100%;
            align-items: stretch;
        }

        .trust-card-hero {
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            justify-content: center !important;
            gap: 8px !important;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 18px;
            border: 1px solid rgba(63, 21, 154, 0.14);
            box-shadow: 0 12px 26px rgba(16, 24, 40, 0.10);
            /* padding: 30px 24px 24px; */
            text-align: center;
            position: relative;
            min-height: 160px;
            height: 100%;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            backdrop-filter: blur(6px);
        }

        a.trust-card-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(2, 6, 23, 0.10);
        }

        @media (max-width: 900px) {
            .trust-cards {
                grid-template-columns: 1fr;
            }

            .trust-card-hero {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .main-heading {
                font-size: 2rem;
            }

            .content-paragraph p {
                font-size: 0.9rem;
                text-align: left;
                margin-bottom: 0.25rem;
            }

            .content-list li {
                font-size: 0.70rem;
            }

            .content-list-item {
                gap: 0.25rem;
            }

            .Trusted-paragraph {
                margin-top: 0.20rem;
                font-size: 0.70rem;
            }
        }

        @media (max-width: 600px) {
            .bg-hero {
                padding: 2.25rem 0.25rem 1rem;
            }

            .main-heading {
                font-size: 1.5rem;
            }

            .content-list-item {
                gap: 0.25rem;
            }
        }

        @media (max-width: 1154px) {
            .content-list-item {
                gap: 0;
            }
        }
    </style>
    <style>
        .hero-v2__chips {
            display: flex;
            /* justify-content: center;  horizontal center */
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .chip {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #4F299F;
            font-family: sans-serif;
            font-size: 18px;
            font-weight: bold;
        }

        .chip__icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background-color: white;
            /* Sirf circle white */
            border-radius: 50%;
            color: #333;
            /* Icon ka color dark taaki white pe dikhe */
            color: #4F299F;
        }
    </style>
</section>