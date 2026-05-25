<div>
    @props(['heading' => '', 'faqs' => []])

    @php
        $total = count($faqs);
        $half = ceil($total / 2);
        $leftFaqs = array_slice($faqs, 0, $half);
        $rightFaqs = array_slice($faqs, $half);
    @endphp

    <section class="faq-section">
        @if ($heading)
            <h2 class="section-title">{{ $heading }}</h2>

        @endif
        <div class="faq-container">
            <div class="faq-column">
                @foreach ($leftFaqs as $item)
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>{!! $item['text'] ?? '' !!}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>{!! $item['paragraph'] !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="faq-column">
                @foreach ($rightFaqs as $item)
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>{!! $item['text'] ?? '' !!}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>{!! $item['paragraph'] ?? '' !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>


        </div>
        <div class="title-box text-center mb-5">
            <a href="/faqs" class="place-order-btn">View More FAQs</a>
        </div>
    </section>


    <style>
        .faq-section {
            text-align: center;
            padding: 50px 20px;
        }

        .faq-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            max-width: 1200px;
            margin: 1rem auto;
        }

        .faq-column {
            width: 45%;
        }

        .faq-item {
            margin-bottom: 10px;
            border: 2px solid #5e2ced;
            border-radius: 5px;
            overflow: hidden;
        }

        .faq-question {
            width: 100%;
            text-align: left;
            background: white;
            padding: 15px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            font-weight: 500;
        }

        .faq-question i {
            transition: transform 0.3s ease-in-out;
            color: #5e2ced;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out, padding 0.4s ease-out;
            padding: 0 15px;
            background: #f9f9f9;
            text-align: justify;
        }

        .faq-answer p {
            font-size: 14px;
            color: #555;
        }

        .faq-item.active .faq-answer {
            max-height: 150px;
            padding: 10px 15px;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        @media (max-width: 768px) {
            .faq-container {
                flex-direction: column;
                align-items: center;
                width: 100%;
                gap: 0px;
            }

            .faq-column {
                width: 100%;
            }

            .section-title {
                margin-left: 0px;
            }
        }
    </style>

    <script>
        document.querySelectorAll(".faq-question").forEach((button) => {
            button.addEventListener("click", () => {
                const faqItem = button.parentElement;
                const faqAnswer = faqItem.querySelector(".faq-answer");

                // Close other open FAQs
                document.querySelectorAll(".faq-item").forEach((item) => {
                    if (item !== faqItem) {
                        item.classList.remove("active");
                        item.querySelector(".faq-answer").style.maxHeight = null;
                    }
                });

                // Toggle current FAQ
                faqItem.classList.toggle("active");
                if (faqItem.classList.contains("active")) {
                    faqAnswer.style.maxHeight = faqAnswer.scrollHeight + "px";
                } else {
                    faqAnswer.style.maxHeight = null;
                }
            });
        });
    </script>

</div>