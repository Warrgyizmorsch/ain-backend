<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>{{ $page->meta_title }}</title><meta name="description" content="{{ $page->meta_description }}"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"><style>
*{box-sizing:border-box}body{margin:0;font-family:'Poppins',Inter,Arial,sans-serif;color:#0f1838;background:#fff}.wrap{max-width:1180px;margin:auto;padding:0 22px}.preview{background:#fff3cd;color:#664d03;text-align:center;padding:8px;font-size:13px}.hero{background:linear-gradient(120deg,#fff 50%,#faf8ff);padding:55px 0 30px}.hero-grid{display:grid;grid-template-columns:1.15fr .85fr;gap:55px;align-items:center}.crumb,.rating{font-size:12px;color:#667085;margin-bottom:24px}.stars{background:#24b56a;color:#fff;padding:4px 7px;border-radius:4px;margin-right:8px}.hero h1{font-size:46px;line-height:1.1;margin:0 0 12px}.orange{color:#ef5b12}.hero p{color:#5d6474;line-height:1.75;font-size:15px}.quote{background:#fff;border:1px solid #eee;border-radius:22px;padding:28px;box-shadow:0 14px 45px #25115a14}.quote h2{text-align:center;margin-top:0}.field{border:1px solid #e9e6ef;border-radius:10px;padding:13px;margin:10px 0;color:#697080}.btn{display:block;background:#f45c08;color:#fff;border-radius:8px;text-align:center;padding:14px;font-weight:700}.features{padding:35px 0}.features h2,.section-title{text-align:center}.feature-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:20px}.feature{text-align:center;font-size:12px;color:#606778}.icon{width:48px;height:48px;margin:auto;border-radius:50%;display:grid;place-items:center;background:#f1ebff;color:#4a19a8;font-size:20px}.cards{display:grid;grid-template-columns:repeat(5,1fr);gap:16px}.card{border:1px solid #e9e6ef;border-radius:9px;overflow:hidden;background:#fff}.photo{height:135px;background:#f1eff5;display:grid;place-items:center;font-size:45px}.card-body{padding:14px}.purple{background:linear-gradient(100deg,#301076,#5c23be);color:#fff;padding:25px 0;margin:38px 0}.reviews{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}.review{border:1px solid #e7e4ed;border-radius:8px;padding:22px;min-height:170px}.review:nth-child(2){background:#5b28bd;color:#fff}.content{padding:40px 0}.content-grid{display:grid;grid-template-columns:1fr 1fr;gap:40px}.content p{line-height:1.8;color:#596174;font-size:15px}.faq-section{padding:50px 0;background:#fdfdfd;border-top:1px solid #f3f1f7}.faq-grid{display:grid;grid-template-columns:1fr 1.6fr;gap:45px;align-items:start}.faq-left{position:sticky;top:20px}.faq-badge{background:#f5efff;color:#6e39c8;font-size:10px;font-weight:800;letter-spacing:0.8px;padding:6px 14px;border-radius:20px;display:inline-block;margin-bottom:18px}.faq-heading{font-size:30px!important;font-weight:800!important;color:#101838!important;line-height:1.2!important;margin:0 0 14px!important;letter-spacing:-0.8px}.faq-purple{color:#6e39c8}.faq-desc{color:#596174;font-size:15px;line-height:1.75;margin-bottom:20px}.faq-contact-btn{border:1px solid #6e39c8;color:#6e39c8;font-size:12px;font-weight:700;padding:11px 25px;border-radius:30px;text-decoration:none!important;display:inline-block;transition:all 0.3s ease}.faq-contact-btn:hover{background:#6e39c8;color:#fff!important;box-shadow:0 4px 12px rgba(110,57,200,0.25);transform:translateY(-1px)}.faq-card{background:#fff;border:1px solid #eef0f5;border-radius:12px;padding:18px 20px;margin-bottom:15px;box-shadow:0 4px 12px rgba(38,16,90,0.02);cursor:pointer;transition:all 0.3s ease}.faq-card:hover{box-shadow:0 8px 24px rgba(38,16,90,0.06);border-color:#dcdfea}.faq-card.active{border-color:#6e39c8;box-shadow:0 8px 24px rgba(110,57,200,0.05)}.faq-question-wrapper{display:flex;align-items:center;width:100%}.faq-icon-box{width:36px;height:36px;border-radius:8px;background:#f5efff;display:flex;align-items:center;justify-content:center;color:#6e39c8;font-size:14px;margin-right:16px;flex-shrink:0;transition:all 0.3s ease}.faq-card:hover .faq-icon-box{background:#6e39c8;color:#fff}.faq-question{font-weight:700;color:#101838;font-size:15px;flex-grow:1;line-height:1.5}.faq-arrow{color:#a0a5b5;font-size:12px;margin-left:12px;transition:transform 0.3s ease;flex-shrink:0}.faq-card.active .faq-arrow{transform:rotate(180deg);color:#6e39c8}.faq-answer-wrapper{overflow:hidden}.faq-answer-content{padding:15px 0 0 52px;color:#596174;font-size:15px;line-height:1.75}.faq-answer-content p{margin:0}.btn,.feature,.icon,.card,.review{transition:all 0.3s ease}.btn:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(244,92,8,0.25)}.feature:hover{transform:translateY(-4px)}.card:hover,.review:hover{transform:translateY(-5px);box-shadow:0 10px 25px rgba(38,16,90,0.08)}@media(max-width:800px){.hero-grid,.content-grid{grid-template-columns:1fr}.feature-grid{grid-template-columns:repeat(2,1fr)}.cards{grid-template-columns:1fr 1fr}.reviews{grid-template-columns:1fr}.hero h1{font-size:36px}.faq-grid{grid-template-columns:1fr;gap:30px}.faq-left{position:static;text-align:center}.faq-heading{font-size:25px!important}}
.long-content-section{padding:50px 0;background:#fff;border-top:1px solid #f3f1f7}.long-content-wrapper{position:relative;max-height:380px;overflow:hidden;transition:max-height 0.5s ease}.long-content-inner{color:#3f4452;font-size:15px;line-height:1.8}.long-content-inner p{margin-bottom:18px}.long-content-inner h2,.long-content-inner h3,.long-content-inner h4{color:#101838;font-weight:800;margin-top:28px;margin-bottom:14px}.long-content-inner h2{font-size:24px!important}.long-content-inner h3{font-size:20px!important}.long-content-inner ul,.long-content-inner ol{margin-left:20px;margin-bottom:18px;padding-left:10px}.long-content-inner li{margin-bottom:8px;line-height:1.8}.long-content-fade{position:absolute;bottom:0;left:0;right:0;height:120px;background:linear-gradient(to bottom,rgba(255,255,255,0) 0%,rgba(255,255,255,1) 100%)!important;pointer-events:none}.load-more-btn{border:1px solid #6e39c8;color:#6e39c8;background:transparent;font-size:12px;font-weight:700;padding:10px 30px;border-radius:30px;cursor:pointer;transition:all 0.3s ease;outline:none}.load-more-btn:hover{background:#6e39c8!important;color:#fff!important;box-shadow:0 4px 12px rgba(110,57,200,0.25);transform:translateY(-1px)}
</style></head><body><div class="preview">Admin preview — draft pages are not visible on the frontend.</div>
<section class="hero"><div class="wrap hero-grid"><div><div class="crumb">Home › Dynamic Page › {{ $page->subject?->name ?? 'Subject' }} Assignment Help</div><div class="rating"><span class="stars">★★★★★</span>Rated 4.9/5 by 25,000+ UK Students</div><h1>{{ $page->hero_heading }}<br><span class="orange">{{ $page->hero_highlight }}</span></h1><p>{{ $page->hero_content }}</p></div><div class="quote"><h2>✨ Get Instant Quote ✨</h2><div class="field">Academic Level &nbsp; — &nbsp; Select Level</div><div class="field">Subject &nbsp; — &nbsp; {{ $page->subject?->name ?? 'Subject' }}</div><div class="field">Assignment Type &nbsp; — &nbsp; Select Type</div><div class="field">Deadline &nbsp; — &nbsp; Select Deadline</div><div class="field">Email Address</div><div class="btn">Get Price Now →</div></div></div></section>
<section class="features"><div class="wrap"><h2>Why Students Choose Our {{ $page->subject?->name ?? 'Subject' }} Assignment Help?</h2><div class="feature-grid">@foreach(['Qualified Experts','100% Original','On-Time Delivery','24/7 Support','UK-Based Experts','Affordable Pricing'] as $i=>$feature)<div class="feature"><div class="icon">{{ ['♙','✓','◷','☏','⌖','£'][$i] }}</div><h3>{{ $feature }}</h3><p>Professional academic support you can rely on.</p></div>@endforeach</div></div></section>
@if($experts->isNotEmpty())<section><div class="wrap"><h2 class="section-title">Our {{ $page->subject?->name ?? 'Subject' }} Assignment Experts</h2><div class="cards">@foreach($experts->take(5) as $expert)<div class="card"><div class="photo">👤</div><div class="card-body"><strong>{{ $expert->name }}</strong><p>{{ $expert->subject ?: $page->subject?->name ?? 'Subject' }} Expert</p><small>★ 4.9 · {{ $expert->finish_order ?: 800 }}+ Orders</small></div></div>@endforeach</div></div></section>@endif
<section class="purple"><div class="wrap"><strong>UP TO 30% OFF ON YOUR FIRST ORDER</strong> &nbsp; · &nbsp; Plagiarism Report &nbsp; · &nbsp; AI Report &nbsp; · &nbsp; Unlimited Revisions &nbsp; · &nbsp; 24/7 Support</div></section>
@if($reviews->isNotEmpty())<section><div class="wrap"><h2 class="section-title">What Our Students Say</h2><div class="reviews">@foreach($reviews->take(3) as $review)<div class="review"><p>“{{ $review->description }}”</p><strong>{{ $review->name }}</strong><br><small>{{ $review->location }} · {{ str_repeat('★', (int)($review->customer_rating ?: 5)) }}</small></div>@endforeach</div></div></section>@endif
@if($page->why_heading || $page->why_items)<section class="content"><div class="wrap"><div style="max-width:900px;margin:0 auto 28px;text-align:center"><h2>{{ $page->why_heading }}</h2><p>{{ $page->why_subheading }}</p></div><div class="content-grid">@foreach($page->why_items ?: [] as $item)<div style="display:grid;grid-template-columns:48px 1fr;gap:14px"><span class="icon">✓</span><div><h3 style="margin:0 0 6px">{{ $item['heading'] }}</h3><p style="margin:0">{{ $item['content'] }}</p></div></div>@endforeach</div></div></section>@endif
@if($page->faqs)
<section class="faq-section">
    <div class="wrap faq-grid">
        <div class="faq-left">
            <span class="faq-badge">FREQUENTLY ASKED QUESTIONS</span>
            <h2 class="faq-heading">Find Answers To <br><span class="faq-purple">Common Questions</span></h2>
            <p class="faq-desc">If you have any other questions, feel free to contact our support team.</p>
            <div class="faq-contact-btn" style="cursor: pointer;">Contact Us →</div>
        </div>
        <div class="faq-right">
            @php
                $faqIcons = [
                    'fas fa-star',
                    'fas fa-shopping-cart',
                    'fas fa-sync-alt',
                    'fas fa-bolt',
                    'fas fa-hand-holding-usd'
                ];
            @endphp
            @foreach($page->faqs as $index => $faq)
                <div class="faq-card">
                    <div class="faq-question-wrapper">
                        <div class="faq-icon-box">
                            <i class="{{ $faqIcons[$index % count($faqIcons)] }}"></i>
                        </div>
                        <span class="faq-question">{{ $faq['question'] }}</span>
                        <span class="faq-arrow"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-answer-wrapper" style="display: none;">
                        <div class="faq-answer-content">
                            <p>{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($page->long_content)
<section class="long-content-section">
    <div class="wrap">
        <div class="long-content-wrapper" style="position: relative; max-height: 380px; overflow: hidden; transition: max-height 0.5s ease;">
            <div class="long-content-inner">
                {!! $page->long_content !!}
            </div>
            <div class="long-content-fade"></div>
        </div>
        <div style="text-align: center; margin-top: 24px;">
            <button type="button" class="load-more-btn">Load More</button>
        </div>
    </div>
</section>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const questions = document.querySelectorAll('.faq-question-wrapper');
    questions.forEach(q => {
        q.addEventListener('click', function() {
            const card = this.parentElement;
            const answer = card.querySelector('.faq-answer-wrapper');
            const arrow = this.querySelector('.faq-arrow i');
            
            document.querySelectorAll('.faq-card').forEach(c => {
                if (c !== card) {
                    c.classList.remove('active');
                    c.querySelector('.faq-answer-wrapper').style.display = 'none';
                    const a = c.querySelector('.faq-arrow i');
                    a.className = 'fas fa-chevron-down';
                }
            });
            
            card.classList.toggle('active');
            if (card.classList.contains('active')) {
                answer.style.display = 'block';
                arrow.className = 'fas fa-chevron-up';
            } else {
                answer.style.display = 'none';
                arrow.className = 'fas fa-chevron-down';
            }
        });
    });

    // Long Content Load More
    const loadMoreBtn = document.querySelector('.load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const wrapper = document.querySelector('.long-content-wrapper');
            const fade = document.querySelector('.long-content-fade');
            if (wrapper.style.maxHeight !== 'none') {
                wrapper.style.maxHeight = 'none';
                fade.style.display = 'none';
                this.textContent = 'Read Less';
            } else {
                wrapper.style.maxHeight = '380px';
                fade.style.display = 'block';
                this.textContent = 'Load More';
                wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }
});
</script>
</body></html>
