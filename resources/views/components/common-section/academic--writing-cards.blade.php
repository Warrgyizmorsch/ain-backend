<div>
    <section class="page-section academic-services">
        <div class="container card-container">

            @if ($heading)
                <h2 class="section-title">{{ $heading }}</h2>
                <p class="section-description">{!! $paragraph !!}</p>
            @endif

            <div class="services-slider-wrapper">
                <div class="services-slider">
                    @foreach ($cards as $index => $card)
                        <div class="service-item" data-readmore>
                            @if(!empty($card['href']))
                                <a href="{{ $card['href'] }}">
                                    <h3>{{ $card['heading'] }}</h3>
                                </a>
                            @else
                                <h3>{{ $card['heading'] }}</h3>
                            @endif

                            <div class="paragraph-container">
                                <p class="large-paragraph">{!! $card['paragraph'] !!}</p>
                                @if(!empty($card['points']) && is_array($card['points']))
                                    <ul class="service-points">
                                        @foreach($card['points'] as $point)
                                            <li>
                                                {{-- The Dot --}}
                                                <span class="point-icon">•</span>
                                                {{-- The Text --}}
                                                <span>{{ $point }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            {{-- <div class="card_btn">
                                <button class="read-more-btn" data-target="paragraph-{{ $index }}">Read More</button>
                            </div> --}}
                        </div>

                    @endforeach
                </div>
            </div>

            <div class="Card-btn">
                <button class="nav-button left-button" onclick="slideLeft()">←</button>
                <button class="nav-button right-button" onclick="slideRight()">→</button>
            </div>
            <div class="scroll-indicator">
                <div></div>
            </div>
        </div>
    </section>

    <style>
        ul.service-points {
            list-style: none;
            padding: 0;
            margin-top: 15px;
            margin-bottom: 10px;
        }

        /* Style the list item (The Row) */
        ul.service-points li {
            display: flex;
            /* Use Flexbox to align dot and text */
            align-items: flex-start;
            /* ✅ Fixes alignment: Keeps dot at the top of the line */
            font-weight: 700;
            /* ✅ Makes text BOLD */
            color: #000;
            /* ✅ Makes text Black */
            font-size: 15px;
            margin-bottom: 8px;
            line-height: 1.5;
            /* Good spacing for multi-line text */
        }

        /* Style the Dot (The Bullet) */
        ul.service-points li .point-icon {
            font-weight: 900;
            /* ✅ Makes the dot extra BOLD */
            color: #000;
            /* Black dot */
            margin-right: 10px;
            /* Space between dot and text */
            min-width: 15px;
            /* Prevents dot from getting squished */
            margin-top: 2px;
            /* slight adjustment to align with text cap-height */
        }

        .card-container {
            width: 100%;
        }

        .academic-services {
            margin-bottom: 30px;
        }

        .services-slider-wrapper {
            max-width: 100%;
            height: auto;
            overflow: hidden;
            margin: 0 auto;
            position: relative;
        }

        .services-slider {
            display: flex;
            transition: transform 0.3s ease-in-out;
            gap: 20px;
            padding: 20px 0;
        }



        .service-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid rgb(63, 21, 154);
            box-shadow: 0 4px 8px rgba(63, 21, 154, 0.15);
            flex: 0 0 calc((100% - 40px) / 3);
            max-width: calc((100% - 40px) / 3);
            min-height: 300px;
            height: auto;
            text-align: center;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            position: relative;
            overflow: hidden;
        }

        .service-item:hover {
            transform: translateY(-5px);
            box-shadow: rgba(94, 44, 237, 0.7) 0px 2px 8px 0px;
        }

        .service-item h3 {
            font-weight: bold;
            font-size: 20px;
            color: #5e2ced;
        }

        .service-item p {
            margin-top: 10px;
            margin-bottom: 7px;
            text-align: left;
            font-weight: 500;
            font-size: 16px;
        }

        .paragraph-container {
            max-height: 300px;
            overflow-y: auto;
        }

        .paragraph-container::-webkit-scrollbar {
            width: 8px;
        }

        .paragraph-container::-webkit-scrollbar-track {
            background: #fff;
            /* Track background */
            border-radius: 4px;
            border-radius: 4px;
        }

        .paragraph-container::-webkit-scrollbar-thumb {
            background-color: #5e2ced;
            /* Thumb (scroll handle) color */
            border-radius: 4px;
            border: 2px solid #eee;
        }

        .read-more-btn {
            background: #5e2ced;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 20px;
            transition: background 0.3s ease-in-out;
        }

        .read-more-btn:hover {
            background: rgb(63, 21, 154);
        }

        /* .large-paragraph {
            max-height: 180px;
            overflow: hidden;
            transition: max-height 0.3s ease;
        } */



        /* .service-item.expanded .large-paragraph {
            max-height:1000px;
    overflow: visible;
        } */

        .Card-btn {
            display: flex;
            justify-content: center;
            margin-top: 10px;
            gap: 20px;
        }



        .nav-button {
            background: #5e2ced;
            color: white;
            border: none;
            padding: 12px 15px;
            font-size: 20px;
            cursor: pointer;
            border-radius: 50%;
            transition: background 0.3s ease-in-out;
        }

        .nav-button:hover {
            background: rgb(63, 21, 154);
        }

        .scroll-indicator {
            width: 100%;
            height: 3px;
            background: #e0e0e0;
            margin: 10px auto 0;
        }

        .scroll-indicator div {
            height: 100%;
            background: #5e2ced;
            transition: width 0.3s ease-in-out;
            width: 0%;
        }

        /* Responsive View */
        @media (max-width: 768px) {
            .service-item {
                flex: 0 0 100%;
                max-width: 100%;
                height: auto;
            }



            .card-btn {
                position: fixed;
                top: 50%;
                transform: translateY(-50%);
                background-color: rgba(0, 0, 0, 0.6);
                color: white;
                border: none;
                padding: 12px 16px;
                font-size: 18px;
                border-radius: 50%;
                cursor: pointer;
                z-index: 10;
            }
        }

        @media (max-width: 380px) {
            .service-item {
                flex: 0 0 100%;
                max-width: 100%;
                height: auto;
            }

            .services-slider {
                gap: 19.7px;
            }

            .service-item h3 {
                font-size: 14px;
            }

            .service-item p {
                font-size: 12px;
            }

            .read-more-btn {
                font-size: 12px;
                padding: 6px 12px;
            }
        }


        @media (max-width: 360px) {
            .service-item {
                flex: 0 0 100%;
                max-width: 100%;
                height: auto;
            }

            .service-item h3 {
                font-size: 14px;
            }

            .service-item p {
                font-size: 12px;
            }

            .read-more-btn {
                font-size: 12px;
                padding: 6px 12px;
            }
        }

        @media (max-width: 1024px) {
            .service-item {
                flex: 0 0 calc(50% - 10px);
                max-width: calc(50% - 10px);
            }
        }

        @media (max-width: 600px) {
            .service-item {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.querySelector('.services-slider');
            const leftBtn = document.querySelector('.left-button');
            const rightBtn = document.querySelector('.right-button');
            const scrollProgress = document.querySelector('.scroll-indicator div');
            const cards = document.querySelectorAll('.service-item');
            const wrapper = document.querySelector('.services-slider-wrapper');

            let cardWidth;
            let visibleCards;
            let currentIndex = 0;
            const totalCards = cards.length;
            let maxIndex;

            function updateVisibleCards() {
                const containerWidth = wrapper.offsetWidth;

                if (window.innerWidth < 768) {
                    visibleCards = 1;
                    cardWidth = containerWidth;
                } else {
                    visibleCards = 3;
                    cardWidth = (containerWidth - 40) / 3;
                }

                if (window.innerWidth < 600) {
                    visibleCards = 1;
                    cardWidth = containerWidth;
                } else if (window.innerWidth < 1200) {
                    visibleCards = 2;
                    cardWidth = (containerWidth - 20) / 2;
                } else {
                    visibleCards = 3;
                    cardWidth = (containerWidth - 40) / 3;
                }

                maxIndex = Math.max(0, totalCards - visibleCards);
                currentIndex = Math.min(currentIndex, maxIndex);
                updateSlider();
            }

            function updateSlider() {
                slider.style.transform = `translateX(-${currentIndex * (cardWidth + 20)}px)`;
                leftBtn.disabled = currentIndex === 0;
                rightBtn.disabled = currentIndex >= maxIndex;
                updateScrollIndicator();
            }

            function updateScrollIndicator() {
                const percentage = maxIndex > 0 ? (currentIndex / maxIndex) * 100 : 100;
                scrollProgress.style.width = `${percentage}%`;
            }

            window.slideLeft = function () {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            };

            window.slideRight = function () {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateSlider();
                }
            };

            // 📌 Updated Read More Logic
            document.querySelectorAll('.read-more-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const serviceItem = button.closest('.service-item');
                    const isExpanded = serviceItem.classList.contains('expanded');

                    // Collapse all cards
                    document.querySelectorAll('.service-item').forEach(item => {
                        item.classList.remove('expanded');
                    });

                    // Reset all button texts
                    document.querySelectorAll('.read-more-btn').forEach(btn => {
                        btn.textContent = 'Read More';
                    });

                    // Expand selected card if not already expanded
                    if (!isExpanded) {
                        serviceItem.classList.add('expanded');
                        button.textContent = 'Read Less';
                    }
                });
            });

            updateVisibleCards();
            window.addEventListener('resize', updateVisibleCards);
        });
        window.addEventListener('resize', () => {
            updateVisibleCards();
        });
    </script>

</div>