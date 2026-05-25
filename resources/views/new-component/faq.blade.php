<!-- FAQ Section -->
 @props([
    'title' => 'FAQs About Our Assignment Help Services',
    'faqs' => []
])
<style>
  .section-title {
    background: linear-gradient(to bottom, #3F159A, #0E8FCE);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 2rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 10px;
}
.trust-cta {
    display: inline-block;
    margin-top: 18px;
    padding: 8px 30px;
    border-radius: 10px;
    font-weight: 600;
    letter-spacing: 0.6px;
    background: #3F159A;
    color: #fff;
    text-decoration: none;
    transition: 0.2s ease;
}
</style>
<section class="new-faq-section">
    <div class="new-faq-container">
        <div class="new-faq-left">
            <h2 class="section-title">{{ $title }}</h2>
            <!-- <div class="new-faq-graphic">
                <img src="/new-home-images/faq-image2.jpeg"
                    style="border-top-right-radius: 20px; border-top-left-radius: 20px; border-bottom-left-radius: 50%; border-bottom-right-radius: 50%;"
                    alt="FAQ Graphic" />
            </div> -->
            <div class="hide-on-mobile" style="display: flex; width: 100%; justify-content: center;">
                <a href="/faqs" class="trust-cta">View More FAQs</a>
            </div>
        </div>

        <div class="new-faq-right">
            <div class="new-faq-accordion">
                @foreach ($faqs as $faq)
                  <div class="new-faq-item">
                      <button class="new-faq-btn">{{ $faq['question'] }}
                          <p><span><img src="/assets/media/layout/faq-plus.svg" alt="Plus icon"></span></p>
                      </button>
                      <div class="new-faq-content">{{ $faq['answer'] }}</div>
                  </div>
                @endforeach
            </div>

            <div class="hide-on-desktop" style="display: flex; width: 100%; justify-content: center;">
                <a href="/faqs" class="trust-cta">View More FAQs</a>
            </div>

        </div>
    </div>
</section>
<style>
    .new-faq-section {
        padding: 30px 0;
        max-width: 1200px;
        margin: 0 auto;
    }

    .new-faq-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: start;
        gap: 40px;
    }

    @media(max-width: 880px) {
        .new-faq-container {
            grid-template-columns: 1fr;
        }
    }

    .new-faq-text {
        font-size: 15px;
        color: #555;
        line-height: 1.5;
        margin-top: -5px;
        margin-bottom: 20px;
    }

    .new-faq-graphic {
        text-align: center;
    }

    .new-faq-graphic img {
        width: 300px;
        max-width: 100%;
    }

    .new-faq-accordion {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .new-faq-item {
        background: white;
        border-radius: 10px;
        /* border: 1px solid #A3A0A0; */
        overflow: hidden;
    }

    .new-faq-btn {
        width: 100%;
        background: none;
        border: none;
        padding: 16px;
        text-align: left;
        font-size: 18px;
        /* font-weight: 600; */
        color: black;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .new-faq-btn p {
        transform: rotate(320deg);
        flex-shrink: 0;
        margin: 0;
    }

    .new-faq-btn span {
        font-weight: bold;
        flex-shrink: 0;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        height: 20px;
        width: 20px;
        background-color: #3F159A;
        border-radius: 50%;
        transition: transform 1s ease;
        transform: rotate(40deg);
    }

    .new-faq-btn.active span {
        transform: rotate(175deg);
        color: white;
    }

    .new-faq-btn.active{
        background: #e9f7fe;
        color: black;
    }

    .new-faq-content {
        max-height: 0;
        overflow: hidden;
        padding: 0 16px;
        font-size: 14px;
        line-height: 1.5;
        color: #444;
        transition: max-height 0.7s ease, padding .3s ease;
    }

    .new-faq-content.open {
        padding: 0 16px 14px;
        margin-bottom: 15px;
        background: #e9f7fe;
        color: black;
    }

    @media (max-width: 768px) {
        .new-faq-section {
            padding: 30px 3px;
        }
    }
</style>
<script>
    document.querySelectorAll(".new-faq-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            const content = btn.nextElementSibling;
            const open = content.classList.contains("open");
            document.querySelectorAll(".new-faq-content").forEach((c) => {
                c.style.maxHeight = null;
                c.classList.remove("open");
            });
            document
                .querySelectorAll(".new-faq-btn")
                .forEach((b) => b.classList.remove("active"));
            if (!open) {
                content.classList.add("open");
                content.style.maxHeight = content.scrollHeight + "px";
                btn.classList.add("active");
            }
        });
    });
</script>