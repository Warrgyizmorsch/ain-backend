<style>
  :root{
    --brand-purple:#3F159A; /* your main purple */
    --brand-border:rgba(17,24,39,0.14);
    --muted:#5b6275;
    --dark:#0f1b3d;
  }

  .services-tabs-section{
    padding:40px 0;
    background:#fff;
  }

  .services-tabs-wrap{
    max-width:1200px;
    margin:0 auto;
    padding:0 18px;
  }

  /* layout like screenshot */
  .services-tabs-grid{
    display:grid;
    grid-template-columns: 320px 1fr;
    gap:24px;
    align-items:start;
    margin-top:18px;
  }

  /* LEFT PANEL */
  .services-tabs-left{
    background:#fff;
    border:1px solid rgba(17,24,39,0.10);
    border-radius:16px;
    box-shadow:0px 2px 6px 0px rgba(0, 0, 0, 0.32);
    padding:18px 14px;
    position:relative;
  }

  .services-tabs-left:before{
    content:"";
    position:absolute;
    left:14px;
    top:14px;
    width:90px;
    height:26px;
    background:
      radial-gradient(circle at 10px 10px, rgba(63,21,154,0.12) 2px, transparent 3px) 0 0/12px 12px;
    opacity:.9;
    pointer-events:none;
  }

  .services-tabs-left-title{
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:600;
    color:#111827;
    margin:0 0 14px;
    padding-left:4px;
    font-size:13px;
  }

  .services-tabs-left-title .pen{
    width:18px;
    height:18px;
    color:var(--brand-purple);
  }

  .services-tab-btn{
    width:100%;
    text-align:left;
    border:1px solid rgba(17,24,39,0.14);
    background:#fff;
    border-radius:8px;
    padding:16px 14px;
    display:flex;
    align-items:center;
    gap:12px;
    cursor:pointer;
    transition:.2s ease;
    margin-bottom:12px;
  }

  .services-tab-btn:last-child{ margin-bottom:0; }

  .services-tab-btn:hover{
    transform:translateY(-1px);
    border-color:rgba(63,21,154,0.35);
    box-shadow:0px 2px 6px 0px rgba(0, 0, 0, 0.32);
  }

  .services-tab-btn.active{
    border-color:#3F159A;
    box-shadow:0px 2px 6px 0px rgba(0, 0, 0, 0.32);
  }

  .services-tab-ic{
    width:28px;
    height:28px;
    flex:0 0 28px;
    display:grid;
    place-items:center;
    color:var(--brand-purple);
  }

  .services-tab-ic svg{ width:26px; height:26px; }

  .services-tab-text{
    font-weight:600;
    color:black;
    font-size:18px;
    line-height:1;
  }

  /* RIGHT PANEL */
  .services-tabs-right{
    background:#f1f1f1;
    border-radius:10px;
    padding:26px;
    position:relative;
    overflow:hidden;
  }

  .services-tabs-right:after{
    content:"";
    position:absolute;
    right:10px;
    bottom:10px;
    width:220px;
    height:220px;
    opacity:.12;
    background:
      radial-gradient(closest-side, rgba(63,21,154,0.35), transparent 65%);
    pointer-events:none;
  }

  .services-content{
    display:none;
    position:relative;
    z-index:1;
  }

  .services-content.active{ display:block; }

  .services-content h3{
    margin:0 0 8px;
    color: #3F159A;
    font-weight: 600;
  }

  .services-content p{
    margin:0 0 16px;
    color:#111827;
    line-height:1.7;
    font-size:15px;
    max-width:900px;
  }

  .services-checklist{
    display:grid;
    grid-template-columns: 1fr;
    gap:10px 22px;
    margin-top:10px;
    margin-bottom:22px;
  }

  .services-check{
    display:flex;
    align-items:center;
    gap:10px;
    color:#111827;
    font-size:15px;
    font-weight:500;
    white-space:nowrap;
  }

  .services-check .tick{
    width:18px;
    height:18px;
    border-radius:999px;
    background:rgba(34,197,94,0.12);
    display:grid;
    place-items:center;
    flex:0 0 18px;
  }

  .services-check .tick svg{
    width:12px;
    height:12px;
    color:#16a34a;
  }



  /* responsive */
  @media (max-width: 1024px){
    .services-tabs-grid{ grid-template-columns: 1fr; }
    .services-tabs-left{ order:1; }
    .services-tabs-right{ order:2; }
    .services-checklist{ grid-template-columns: 1fr; }
  }

  @media (max-width: 640px){
    .services-tabs-right{ padding:18px; }
    .services-tab-text{ font-size:16px; }
    .services-checklist{ grid-template-columns: 1fr; }
    .services-check{ white-space:normal; }
  }
</style>

<section class="services-tabs-section" id="services-tabs">
  <div class="services-tabs-wrap">

    <h2 class="section-title">Explore Our <span>Expert Assignment Help Services</span> for UK Students</h2>

    <div class="services-tabs-grid">

      {{-- LEFT --}}
      <div class="services-tabs-left">
        <div class="services-tabs-left-title">
          <span class="pen" aria-hidden="true">
            <!-- pen icon -->
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </span>
          We Offer Academic Assistance In:
        </div>

        <button class="services-tab-btn active" type="button" data-tab="assignments">
          <span class="services-tab-ic" aria-hidden="true">
            <!-- document icon -->
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="M8 13h8M8 17h8M8 9h3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </span>
          <span class="services-tab-text">Assignments</span>
        </button>

        <button class="services-tab-btn" type="button" data-tab="essay">
          <span class="services-tab-ic" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
              <path d="M8 12h8M8 16h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </span>
          <span class="services-tab-text">Essay</span>
        </button>

        <button class="services-tab-btn" type="button" data-tab="dissertation">
          <span class="services-tab-ic" aria-hidden="true">
            <!-- book icon -->
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M4 19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12Z" stroke="currentColor" stroke-width="2"/>
              <path d="M8 5V3h8v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M8 11h8M8 15h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </span>
          <span class="services-tab-text">Dissertation</span>
        </button>

        <button class="services-tab-btn" type="button" data-tab="coursework">
          <span class="services-tab-ic" aria-hidden="true">
            <!-- clipboard icon -->
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M9 2h6v3H9V2Z" stroke="currentColor" stroke-width="2"/>
              <path d="M7 5h10a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2"/>
              <path d="M8 11h8M8 15h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </span>
          <span class="services-tab-text">Coursework</span>
        </button>

        <button class="services-tab-btn" type="button" data-tab="homework">
          <span class="services-tab-ic" aria-hidden="true">
            <!-- pencil icon -->
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 20h9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4L16.5 3.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            </svg>
          </span>
          <span class="services-tab-text">Homework</span>
        </button>
      </div>

      {{-- RIGHT --}}
      <div class="services-tabs-right">

        {{-- Assignments --}}
        <div class="services-content active" id="tab-assignments">
          <h3>Assignment Writing Help</h3>
          <p>
            Assignment writing often becomes overwhelming for students because researching topics deeply, analysing information accurately,
            and meeting strict university guidelines require significant time and effort. Along with lectures, part-time jobs, and personal
            commitments, managing everything together can feel exhausting. With our expert support, we ensure that your assignments are crafted
            with clarity, precision, and a strong academic presentation, so you can submit your work with confidence.
          </p>

          <div class="services-checklist">
            <div class="services-check"><span class="tick">@svg</span>Comprehensive Research</div>
            <div class="services-check"><span class="tick">@svg</span>Expertly Crafted Writing</div>
            <div class="services-check"><span class="tick">@svg</span>Thorough Editing & Review</div>
            <div class="services-check"><span class="tick">@svg</span>Proper Formatting & Referencing</div>
          </div>

          <a class="trust-cta" href="/assignment-writing-help-services">Get Assignment Help</a>
        </div>

        {{-- Essay --}}
        <div class="services-content" id="tab-essay">
          <h3>Essay Writing Help</h3>
          <p>
            Essay writing demands critical thinking, structured arguments, and strong academic language, which many students find challenging.
            From understanding the essay question to presenting ideas logically, the process can be stressful alongside daily academic pressures.
            Our professional essay writers create well-researched, coherent, and original essays that meet UK university standards and help you achieve higher grades.
          </p>

          <div class="services-checklist">
            <div class="services-check"><span class="tick">@svg</span>Strong Argument Development</div>
            <div class="services-check"><span class="tick">@svg</span>Subject-Specific Expertise</div>
            <div class="services-check"><span class="tick">@svg</span>Plagiarism-Free Content</div>
            <div class="services-check"><span class="tick">@svg</span>Accurate Referencing Styles</div>
          </div>

          <a class="trust-cta" href="/essay-writing-help-services">Get Essay Help</a>
        </div>

        {{-- Dissertation --}}
        <div class="services-content" id="tab-dissertation">
          <h3>Dissertation Help</h3>
          <p>
            Dissertation writing is a complex and time-consuming task that requires extensive research, data analysis, and academic expertise.
            Balancing this major project with coursework and other responsibilities can be overwhelming for students. Our dissertation help ensures
            structured guidance at every stage, delivering original, well-researched work that meets academic expectations.
          </p>

          <div class="services-checklist">
            <div class="services-check"><span class="tick">@svg</span>Topic Selection Assistance</div>
            <div class="services-check"><span class="tick">@svg</span>In-Depth Research Support</div>
            <div class="services-check"><span class="tick">@svg</span>Data Analysis Guidance</div>
            <div class="services-check"><span class="tick">@svg</span>University-Approved Formatting</div>
          </div>

          <a class="trust-cta" href="/dissertation-writing-help-services">Get Dissertation Help</a>
        </div>

        {{-- Coursework --}}
        <div class="services-content" id="tab-coursework">
          <h3>Coursework Help</h3>
          <p>
            Coursework involves consistent effort, detailed research, and timely submissions, which can be difficult to manage alongside other academic tasks.
            Missing deadlines or unclear content can affect overall performance. Our coursework helps deliver accurate, well-organised, and deadline-focused solutions tailored to your subject requirements.
          </p>

          <div class="services-checklist">
            <div class="services-check"><span class="tick">@svg</span>Timely Submission Support</div>
            <div class="services-check"><span class="tick">@svg</span>Clear Academic Structure</div>
            <div class="services-check"><span class="tick">@svg</span>Subject-Specific Experts</div>
            <div class="services-check"><span class="tick">@svg</span>Quality Assurance Checks</div>
          </div>

          <a class="trust-cta" href="/coursework-writing-help">Get Coursework Help</a>
        </div>

        {{-- Homework --}}
        <div class="services-content" id="tab-homework">
          <h3>Homework Help</h3>
          <p>
            Homework assignments often pile up quickly, leaving students stressed and short on time. Understanding concepts while completing tasks accurately can be challenging,
            especially with tight schedules. Our homework help provides step-by-step solutions and clear explanations to help you submit correct and confident work.
          </p>

          <div class="services-checklist">
            <div class="services-check"><span class="tick">@svg</span>Easy-to-Understand Solutions</div>
            <div class="services-check"><span class="tick">@svg</span>Step-by-Step Guidance</div>
            <div class="services-check"><span class="tick">@svg</span>Multiple Subject Coverage</div>
            <div class="services-check"><span class="tick">@svg</span>On-Time Delivery</div>
          </div>

          <a class="trust-cta" href="/homework-writing-help-services">Get Homework Help</a>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
  // inject tick svg into all .tick spans
  document.querySelectorAll('.services-check .tick').forEach(t => {
    t.innerHTML = `
      <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    `;
  });

  // tabs
  document.querySelectorAll('.services-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const key = btn.getAttribute('data-tab');

      document.querySelectorAll('.services-tab-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      document.querySelectorAll('.services-content').forEach(c => c.classList.remove('active'));
      const active = document.getElementById('tab-' + key);
      if(active) active.classList.add('active');
    });
  });
</script>
