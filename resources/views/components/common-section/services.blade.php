{{-- hero section & How Our Assignment Writing Help --}}
<div>
    @props(['h1', 'p1', 'step1', 'step1Content', 'step2', 'step2Content', 'step3', 'step3Content', 'step4', 'step4Content'])

    <section class="page-section how-it-works">
        <div class="container">
            <h2 class="section-title">
                @php
                    $highlighted = str_replace('Assignment Writing Help', '<span>Assignment Writing Help</span>', $h1);
                @endphp

                {!! $highlighted !!}

            </h2>
            @if (!empty($p1))
                <p class="textCommon text-center">{{$p1}}</p>
            @endif


            <div class="content-wrapper">
                <!-- Left Side: YouTube Video -->
                <div class="video-column">
                    <div class="youtube-facade">
                        <img src="images/assignment-help-process-uk.webp" alt="How assignment help works in UK"
                            loading="lazy" />
                        <!-- <div class="play-button"></div> -->
                    </div>
                </div>

                <!-- Right Side: All Steps -->
                <div class="steps-column">
                    <div class="step">

                        <h3><span class="step-number">1. </span>{{ $step1 }}</h3>
                        <p>{{ $step1Content }}</p>
                    </div>

                    <div class="step">

                        <h3><span class="step-number">2. </span>{{ $step2 }}</h3>
                        <p>{{ $step2Content }}</p>
                    </div>

                    <div class="step">

                        <h3> <span class="step-number">3. </span> {{ $step3 }}</h3>
                        <p>{{ $step3Content }}</p>
                    </div>
                    @if (!empty($step4))
                    <div class="step">

                        <h3> <span class="step-number">4. </span> {{ $step4 }}</h3>
                        <p>{{ $step4Content }}</p>
                    </div>
                    @endif
                    <a href="https://www.assignnmentinneed.com/upload-your-assignment">
                        <button class="place-order-btn">Place Order</button>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
        .how-it-works {
            padding: 2rem 1.5rem;
            background: #fff;
            text-align: center;
        }

        /*  */

        /* Main 2-column layout */
        .content-wrapper {
            display: flex;
            gap: 20px;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        /* Video section */
        .video-column {
            flex: 1;
            /* min-width: 300px; */
            max-width: 600px;
            text-align: center;
        }

        .video-column iframe {
            width: 100%;
            height: 315px;
            border-radius: 12px;
        }

        /* Steps section */
        .steps-column {
            flex: 1;
            /* min-width: 300px; */
            max-width: 600px;
            text-align: left;
        }

        .step {
            margin-bottom: 30px;
        }

        .step-number {
            font-size: 28px;
            color: #5e2ced;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 8px;
        }

        .step h3 {
            font-size: 22px;
            margin: 8px 0;
            color: #333;
        }

        .step p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        /* Button Styling */
        /*  */

        /* Responsive Design */
        @media (max-width: 768px) {


            .step-number {
                font-size: 24px;
            }

            .step h3 {
                font-size: 20px;
            }

            .step p {
                font-size: 14px;
            }

            .place-order-btn {
                max-width: 220px;
                font-size: 14px;
                padding: 10px;
            }

            .video-column iframe {
                height: 250px;
            }
        }

        @media (max-width: 480px) {
            .step-number {
                font-size: 20px;
            }

            .step h3 {
                font-size: 18px;
            }

            .step p {
                font-size: 13px;
            }

            .place-order-btn {
                width: 200px;
                font-size: 13px;
                padding: 8px;
            }

            .video-column iframe {
                height: 200px;
            }
        }
    </style>

</div>