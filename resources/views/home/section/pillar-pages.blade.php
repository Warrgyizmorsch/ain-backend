<!-- Pillar Page Interlinking Section -->

<!-- CSS -->
<style>
    .pillar-interlinking-section {
        padding: 30px 20px;
        margin: 2rem auto 0;
        max-width: 1200px;
    }

    .inner-container {
        position: relative;
        margin: 0 auto;
        max-width: 1005px;
    }

    .pillar-content-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .pillar-card {
        flex: 1 1 300px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        min-height: 250px; /* Uniform height for all cards */
        display: flex;
        flex-direction: column;
    }

    .pillar-card:hover {
        /* transform: translateY(-5px); */
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .card-title {
        color: #6B46C1;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .pillar-list {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1; /* Allows list to fill available space */
    }

    .pillar-list li {
        margin-bottom: 0.5rem;
        color: #333;
        position: relative;
        padding-left: 20px;
    }

    .pillar-list li::before {
        content: "➤";
        color: #6B46C1;
        position: absolute;
        left: 0;
    }

    .pillar-list a {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
        display: inline-block;
        margin-left: 10px;
    }

    .pillar-list a:hover {
        color: #6B46C1;
        text-decoration: underline;
    }

    .pillar-custom-nav-btn {
        text-align: center;
        margin-top: 1rem;
    }

    .pillar-custom-nav-btn button {
        background-color: #6B46C1;
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 16px;
        border-radius: 8px;
        margin: 0 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .pillar-custom-nav-btn button:hover {
        background-color: #553C9A;
    }

    .pillar-content-wrapper.slider-active {
        display: none;
    }

    .hm4-pillar-carousel {
        display: none;
    }

    .hm4-pillar-carousel.slider-active {
        display: block;
    }

    .hm4-pillar-carousel .pillar-card {
        flex: 0 0 100%;
        max-width: 100%;
    }

    @media (min-width: 1024px) {
        .pillar-content-wrapper {
            flex-wrap: nowrap;
        }
    }

    @media (max-width: 1023px) {
        .pillar-card {
            flex: 1 1 100%;
        }
    }

    @media (max-width: 480px) {
        .pillar-interlinking-section {
            padding: 20px 15px;
            margin: 1rem auto 0;
        }

        .card-title {
            font-size: 1.2rem;
        }

        .pillar-list a {
            font-size: 0.875rem;
            line-height: 1.5em;
        }

        .pillar-card {
            
            /* min-height: 310px;  */
            /* Uniform height for all cards */
        }
        
        .pillar-custom-nav-btn {
            text-align: center;
            margin-top: 0.5rem;
        }
    }
</style>

<section class="pillar-interlinking-section">
    <h2 class="section-title">Academic <span>Resources</span> & Quick Links</h2>
    
    <div class="inner-container my-4">
        <div class="pillar-content-wrapper">
            <div class="pillar-card">
                <h3 class="card-title">Assignment Writing Guides</h3>
                <ul class="pillar-list">
                    <li><a href="/blog/how-to-write-an-essay-proposal-tips-and-examples">Essay Writing Tips</a></li>
                    <li><a href="/blog/how-to-write-an-outstanding-assignment">Assignment Structure & Formatting</a></li>
                    <li><a href="/blog/how-to-write-a-research-paper">Perfect Research Paper Guide</a></li>
                    <li><a href="/blog/case-study-writing-guide-structure-examples-tips">Case Study Analysis Techniques</a></li>
                    <li><a href="/blog/how-to-write-a-winning-dissertation-proposal-tips-and-tricks">Winning Dissertation Proposal</a></li>
                </ul>
            </div>

            <div class="pillar-card">
                <h3 class="card-title">University Study Resources</h3>
                <ul class="pillar-list">
                    <li><a href="/academic-integrity">UK University Academic Integrity</a></li>
                    <li><a href="/uk-university-grading-system">UK Grading System Explained</a></li>
                    <li><a href="/referencing-citation-guide">Referencing & Citation Styles</a></li>
                    <li><a href="/critical-thinking-guide">Critical Thinking & Analysis Guide</a></li>
                    <li><a href="/academic-research-strategies">Academic Research Strategies</a></li>
                </ul>
            </div>

            <div class="pillar-card">
                <h3 class="card-title">Student Success Tools</h3>
                <ul class="pillar-list">
                    <li><a href="/student-productivity-guide">Time Management for Students</a></li>
                    <li><a href="/academic-stress-management">Academic Stress Management</a></li>
                    <li><a href="/assignment-feedback-guide">Feedback & Redrafting</a></li>
                    <li><a href="/effective-study-environment">Building Effective Study</a></li>
                    <li><a href="/blog/how-to-write-an-assignment-on-the-first-page">First Page of Assignment</a></li>
                </ul>
            </div>
        </div>
        <div class="pillar-custom-nav-btn" style="display: none;">
            <button class="nav-prev-btn"><i class="fa fa-chevron-left"></i></button>
            <button class="nav-next-btn"><i class="fa fa-chevron-right"></i></button>
        </div>
    </div>

    
</section>


<!-- Owl Carousel CSS -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" /> -->

<!-- jQuery -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<!-- Owl Carousel JS -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script> -->

<!-- Font Awesome for navigation icons -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->

<script>
    document.addEventListener("DOMContentLoaded", function () {
        function checkWrapAndInitSlider() {
            const wrapper = document.querySelector('.pillar-content-wrapper');
            const cards = document.querySelectorAll('.pillar-card');
            const totalWidth = Array.from(cards).reduce((sum, card) => sum + card.offsetWidth, 0) + (cards.length - 1) * 16; // 16px gap
            const containerWidth = wrapper.parentElement.offsetWidth;

            if (totalWidth > containerWidth) {
                wrapper.classList.add('slider-active');
                const $carousel = $('.hm4-pillar-carousel');
                if (!$carousel.length) {
                    const carouselHtml = `
                        <div class="hm4-pillar-carousel owl-carousel owl-theme">
                            ${Array.from(cards).map(card => `<div class="pillar-card item">${card.innerHTML}</div>`).join('')}
                        </div>
                        <div class="pillar-custom-nav-btn">
                            <button class="nav-prev-btn"><i class="fa fa-chevron-left"></i></button>
                            <button class="nav-next-btn"><i class="fa fa-chevron-right"></i></button>
                        </div>
                    `;
                    wrapper.insertAdjacentHTML('afterend', carouselHtml);
                    $('.hm4-pillar-carousel').addClass('slider-active').owlCarousel({
                        loop: true,
                        margin: 10,
                        nav: false,
                        autoplay: false, // Disabled auto-slide
                        responsive: {
                            0: { items: 1 },
                            768: { items: 2 },
                            1024: { items: 3 }
                        }
                    });

                    $('.nav-next-btn').on('click', function () {
                        $('.hm4-pillar-carousel').trigger('next.owl.carousel');
                    });

                    $('.nav-prev-btn').on('click', function () {
                        $('.hm4-pillar-carousel').trigger('prev.owl.carousel');
                    });
                }
            } else {
                wrapper.classList.remove('slider-active');
                const $carousel = $('.hm4-pillar-carousel');
                if ($carousel.length) {
                    $carousel.trigger('destroy.owl.carousel').remove();
                    $('.pillar-custom-nav-btn').remove();
                }
            }
        }

        checkWrapAndInitSlider();
        window.addEventListener('resize', debounce(checkWrapAndInitSlider, 250));

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    });
</script>