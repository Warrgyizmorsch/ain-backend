<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Assignment in Need — Feedback</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet" />
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --blue:        #1a56db;
    --blue-dark:   #1240a8;
    --blue-light:  #e8f0fd;
    --blue-mid:    #c3d7fa;
    --amber:       #f59f00;
    --surface:     #f5f6fa;
    --white:       #ffffff;
    --text-1:      #0f172a;
    --text-2:      #475569;
    --text-3:      #94a3b8;
    --border:      rgba(15,23,42,0.08);
    --border-md:   rgba(15,23,42,0.14);
    --radius-sm:   8px;
    --radius-md:   12px;
    --radius-lg:   18px;
    --radius-xl:   24px;
    --shadow-sm:   0 1px 4px rgba(15,23,42,0.07);
    --shadow-md:   0 4px 16px rgba(15,23,42,0.10);
    --shadow-lg:   0 8px 32px rgba(15,23,42,0.12);
    --transition:  0.2s cubic-bezier(0.4,0,0.2,1);
  }

  html { font-size: 16px; }
  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--surface);
    color: var(--text-1);
    min-height: 100vh;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
  }

  /* ── NAVBAR ── */
  .navbar {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    height: 62px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 48px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: var(--shadow-sm);
  }
  .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
  .nav-logo-icon { width: 36px; height: 36px; background: var(--blue); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
  .nav-logo-text { font-size: 15px; font-weight: 600; color: var(--text-1); letter-spacing: -0.2px; }
  .nav-logo-text span { color: var(--blue); }
  .nav-links { display: flex; align-items: center; gap: 6px; }
  .nav-link { font-size: 14px; font-weight: 400; color: var(--text-2); padding: 6px 14px; border-radius: var(--radius-sm); text-decoration: none; cursor: pointer; transition: background var(--transition), color var(--transition); }
  .nav-link:hover { background: var(--surface); color: var(--text-1); }
  .nav-link.active { color: var(--blue); font-weight: 500; }
  .nav-right { display: flex; align-items: center; gap: 12px; }
  .nav-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--blue-light); color: var(--blue); font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; cursor: pointer; }

  /* ── PAGE CONTAINER ── */
  .page { max-width: 960px; margin: 0 auto; padding: 48px 24px 80px; display: none; }
  .page.active { display: block; }

  /* ── PAGE HEADER ── */
  .page-header { text-align: center; margin-bottom: 40px; }
  .page-header h1 { font-family: 'Instrument Serif', serif; font-size: 32px; font-weight: 400; color: var(--text-1); letter-spacing: -0.5px; margin-bottom: 8px; }
  .page-header p { font-size: 15px; color: var(--text-2); }

  /* ── STEP BAR ── */
  .step-bar { display: flex; align-items: center; justify-content: center; gap: 0; margin-bottom: 40px; }
  .step-item { display: flex; align-items: center; gap: 10px; }
  .step-circle { width: 32px; height: 32px; border-radius: 50%; background: var(--blue); color: #fff; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; transition: background var(--transition); flex-shrink: 0; }
  .step-circle.done { background: #22c55e; }
  .step-circle.inactive { background: var(--white); color: var(--text-3); border: 1.5px solid var(--border-md); }
  .step-label { font-size: 13px; font-weight: 500; color: var(--text-1); }
  .step-label.inactive { color: var(--text-3); font-weight: 400; }
  .step-line { width: 80px; height: 2px; background: var(--border-md); margin: 0 12px; border-radius: 2px; }
  .step-line.done { background: #22c55e; }

  /* ── CARD ── */
  .card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 36px 40px; box-shadow: var(--shadow-sm); margin-bottom: 20px; }
  .card-label { font-size: 11px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: var(--blue); margin-bottom: 6px; }
  .card-title { font-size: 20px; font-weight: 500; color: var(--text-1); margin-bottom: 32px; line-height: 1.35; }

  /* ── EMOJI ROW ── */
  .emoji-row { display: flex; justify-content: center; gap: 16px; flex-wrap: wrap; }
  .emoji-btn { display: flex; flex-direction: column; align-items: center; gap: 12px; padding: 24px 20px; border-radius: var(--radius-lg); border: 1.5px solid var(--border); background: var(--white); cursor: pointer; min-width: 128px; transition: all var(--transition); position: relative; outline: none; }
  .emoji-btn::after { content: ''; position: absolute; inset: -2px; border-radius: calc(var(--radius-lg) + 2px); border: 2px solid transparent; transition: border-color var(--transition); pointer-events: none; }
  .emoji-btn:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); border-color: var(--border-md); }
  .emoji-btn.selected { border-color: var(--blue); background: var(--blue-light); transform: translateY(-4px); box-shadow: var(--shadow-md); }
  .emoji-btn.selected::after { border-color: var(--blue); }

  .emoji-face { width: 72px; height: 72px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 44px; transition: transform var(--transition); }
  .emoji-btn:hover .emoji-face, .emoji-btn.selected .emoji-face { transform: scale(1.08); }

  .face-worst  { background: radial-gradient(circle at 35% 35%, #ff6b6b, #c0392b); box-shadow: 0 8px 24px rgba(192,57,43,0.35), inset 0 2px 4px rgba(255,160,160,0.4); }
  .face-poor   { background: radial-gradient(circle at 35% 35%, #ff8c42, #e55d04); box-shadow: 0 8px 24px rgba(229,93,4,0.35),  inset 0 2px 4px rgba(255,180,120,0.4); }
  .face-avg    { background: radial-gradient(circle at 35% 35%, #ffd43b, #f08c00); box-shadow: 0 8px 24px rgba(240,140,0,0.35),  inset 0 2px 4px rgba(255,230,100,0.4); }
  .face-good   { background: radial-gradient(circle at 35% 35%, #69db7c, #2f9e44); box-shadow: 0 8px 24px rgba(47,158,68,0.35),  inset 0 2px 4px rgba(150,240,170,0.4); }
  .face-excel  { background: radial-gradient(circle at 35% 35%, #38d9a9, #0ca678); box-shadow: 0 8px 24px rgba(12,166,120,0.35), inset 0 2px 4px rgba(100,240,200,0.4); }

  .emoji-name { font-size: 13px; font-weight: 500; color: var(--text-2); text-align: center; }
  .emoji-btn.selected .emoji-name { color: var(--blue); font-weight: 600; }

  /* ── NEXT BUTTON ── */
  .btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 13px 40px; background: var(--blue); color: #fff; border: none; border-radius: var(--radius-md); font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 500; cursor: pointer; transition: background var(--transition), transform var(--transition), opacity var(--transition); }
  .btn-primary:hover { background: var(--blue-dark); }
  .btn-primary:active { transform: scale(0.98); }
  .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
  .btn-primary:disabled:hover { background: var(--blue); }

  .btn-ghost { display: inline-flex; align-items: center; gap: 8px; padding: 13px 28px; background: transparent; color: var(--text-2); border: 1px solid var(--border-md); border-radius: var(--radius-md); font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 400; cursor: pointer; transition: background var(--transition), color var(--transition); }
  .btn-ghost:hover { background: var(--surface); color: var(--text-1); }

  .btn-center { text-align: center; margin-top: 8px; }

  /* ── SCREEN 2 LAYOUT ── */
  .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
  .two-col .card { margin-bottom: 0; height: 100%; }

  /* ── SELECTED BADGE ── */
  .selected-badge { display: inline-flex; align-items: center; gap: 8px; background: var(--blue-light); color: var(--blue); border: 1px solid var(--blue-mid); border-radius: 100px; padding: 6px 18px; font-size: 13px; font-weight: 500; margin-bottom: 28px; }

  /* ── ISSUE CHIPS ── */
  .issue-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
  .issue-chip { padding: 14px 16px; border-radius: var(--radius-md); border: 1.5px solid var(--border); background: var(--white); font-size: 13px; font-weight: 500; color: var(--text-1); cursor: pointer; display: flex; align-items: center; gap: 10px; transition: all var(--transition); user-select: none; }
  .issue-chip:hover { border-color: var(--amber); background: #fff9e6; }
  .issue-chip.selected { border-color: var(--amber); background: #fff3cc; color: #7c4f00; }
  .chip-icon { font-size: 20px; flex-shrink: 0; }

  /* ── FORM FIELDS ── */
  .field-group { display: flex; flex-direction: column; gap: 16px; }
  .field { display: flex; flex-direction: column; gap: 6px; }
  .field-label { font-size: 11px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: var(--text-2); }
  .field-input { width: 100%; padding: 11px 14px; border: 1.5px solid var(--border-md); border-radius: var(--radius-md); font-family: 'DM Sans', sans-serif; font-size: 14px; color: var(--text-1); background: var(--white); outline: none; transition: border-color var(--transition), box-shadow var(--transition); resize: none; }
  .field-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(26,86,219,0.12); }
  .field-input::placeholder { color: var(--text-3); }

  .order-wrap { display: flex; align-items: center; gap: 10px; border: 1.5px solid var(--border-md); border-radius: var(--radius-md); padding: 11px 14px; background: var(--white); transition: border-color var(--transition), box-shadow var(--transition); }
  .order-wrap:focus-within { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(26,86,219,0.12); }
  .order-wrap input { flex: 1; border: none; outline: none; font-family: 'DM Sans', sans-serif; font-size: 14px; color: var(--text-1); background: transparent; font-feature-settings: 'tnum'; letter-spacing: 0.5px; }
  .order-wrap input::placeholder { color: var(--text-3); letter-spacing: normal; }
  .order-icon { font-size: 18px; flex-shrink: 0; }
  .field-hint { font-size: 12px; color: var(--text-3); }
  .error-message { color: #dc2626; font-size: 13px; font-weight: 500; margin-top: 10px; display: none; }

  /* ── SUBMIT ROW ── */
  .submit-row { display: flex; align-items: center; justify-content: space-between; margin-top: 8px; }

  /* ── SUCCESS SCREEN ── */
  .success-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-xl); padding: 80px 48px; box-shadow: var(--shadow-sm); text-align: center; max-width: 560px; margin: 0 auto; }
  .success-anim { width: 88px; height: 88px; border-radius: 50%; background: #dcfce7; display: flex; align-items: center; justify-content: center; font-size: 44px; margin: 0 auto 28px; animation: pop 0.4s cubic-bezier(0.34,1.56,0.64,1); }
  @keyframes pop { from { transform: scale(0); opacity: 0; } to { transform: scale(1); opacity: 1; } }
  .success-title { font-family: 'Instrument Serif', serif; font-size: 28px; font-weight: 400; color: var(--text-1); margin-bottom: 12px; }
  .success-sub { font-size: 15px; color: var(--text-2); line-height: 1.65; max-width: 380px; margin: 0 auto 36px; }
  .success-meta { display: flex; gap: 16px; justify-content: center; margin-bottom: 36px; }
  .meta-pill { background: var(--surface); border: 1px solid var(--border); border-radius: 100px; padding: 5px 16px; font-size: 12px; color: var(--text-2); font-weight: 500; }

  /* ── ANIMATIONS ── */
  .page.active { animation: fadeUp 0.3s ease; }
  @keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to  { opacity: 1; transform: translateY(0); } }

  /* ── FOOTER ── */
  .footer { text-align: center; padding: 24px; font-size: 12px; color: var(--text-3); border-top: 1px solid var(--border); background: var(--white); }

  /* ── RESPONSIVE ── */
  @media (max-width: 720px) {
    .navbar { padding: 0 20px; }
    .nav-links { display: none; }
    .page { padding: 28px 16px 60px; }
    .card { padding: 24px 20px; }
    .emoji-row { gap: 10px; }
    .emoji-btn { min-width: 100px; padding: 18px 12px; }
    .emoji-face { width: 56px; height: 56px; font-size: 34px; }
    .two-col { grid-template-columns: 1fr; }
    .step-line { width: 40px; }
    .success-card { padding: 48px 24px; }
  }
</style>
</head>
<body>

<nav class="navbar">
  <a class="nav-logo" href="#">
    <div class="nav-logo-icon">📝</div>
    <span class="nav-logo-text">Assignment <span>in Need</span></span>
  </a>
  <div class="nav-links">
    <a class="nav-link" href="#">Home</a>
    <a class="nav-link active" href="#">Feedback</a>
    <a class="nav-link" href="#">Orders</a>
    <a class="nav-link" href="#">Support</a>
  </div>
  <div class="nav-right">
    <div class="nav-avatar">AJ</div>
  </div>
</nav>

<main class="page active" id="screen1">
  <div class="page-header">
    <h1>How was your experience?</h1>
    <p>Your honest feedback helps us serve you better</p>
  </div>

  <div class="step-bar">
    <div class="step-item">
      <div class="step-circle" id="step1-circle">1</div>
      <span class="step-label">Overall rating</span>
    </div>
    <div class="step-line" id="step-line"></div>
    <div class="step-item">
      <div class="step-circle inactive" id="step2-circle">2</div>
      <span class="step-label inactive" id="step2-label">Detailed feedback</span>
    </div>
  </div>

  <div class="card">
    <div class="card-label">Step 1 of 2</div>
    <div class="card-title">How would you rate your overall experience with Assignment in Need?</div>
    <div class="emoji-row" id="emojiRow">
      <button class="emoji-btn" data-val="worst" data-label="Worst" onclick="selectEmoji(this)" aria-label="Worst" type="button">
        <div class="emoji-face face-worst">😡</div>
        <div class="emoji-name">Worst</div>
      </button>
      <button class="emoji-btn" data-val="poor" data-label="Poor" onclick="selectEmoji(this)" aria-label="Poor" type="button">
        <div class="emoji-face face-poor">😞</div>
        <div class="emoji-name">Poor</div>
      </button>
      <button class="emoji-btn" data-val="average" data-label="Average" onclick="selectEmoji(this)" aria-label="Average" type="button">
        <div class="emoji-face face-avg">😐</div>
        <div class="emoji-name">Average</div>
      </button>
      <button class="emoji-btn" data-val="good" data-label="Good" onclick="selectEmoji(this)" aria-label="Good" type="button">
        <div class="emoji-face face-good">🙂</div>
        <div class="emoji-name">Good</div>
      </button>
      <button class="emoji-btn" data-val="excellent" data-label="Excellent" onclick="selectEmoji(this)" aria-label="Excellent" type="button">
        <div class="emoji-face face-excel">😁</div>
        <div class="emoji-name">Excellent</div>
      </button>
    </div>
  </div>

  <div class="btn-center">
    <button class="btn-primary" id="nextBtn" onclick="goToScreen2()" disabled>
      Continue <span>→</span>
    </button>
  </div>
</main>

<main class="page" id="screen2">
  <div class="page-header">
    <h1>Tell us more</h1>
    <p>Help us understand what happened</p>
  </div>

  <div class="step-bar">
    <div class="step-item">
      <div class="step-circle done">✓</div>
      <span class="step-label">Overall rating</span>
    </div>
    <div class="step-line done"></div>
    <div class="step-item">
      <div class="step-circle">2</div>
      <span class="step-label">Detailed feedback</span>
    </div>
  </div>

  <div id="selectedBadge" class="selected-badge">😞 Poor experience</div>

  <div class="two-col">

    <div class="card">
      <div class="card-label" id="chipSectionLabel">What went wrong?</div>
      <div class="card-title" id="issueQuestion" style="font-size:17px;margin-bottom:20px;">Which area felt most disappointing?</div>
      <div class="issue-grid">
        <div class="issue-chip" onclick="toggleIssue(this)" role="checkbox" tabindex="0">
          <span class="chip-icon">🎧</span> Customer service
        </div>
        <div class="issue-chip" onclick="toggleIssue(this)" role="checkbox" tabindex="0">
          <span class="chip-icon">✏️</span> Work quality
        </div>
        <div class="issue-chip" onclick="toggleIssue(this)" role="checkbox" tabindex="0">
          <span class="chip-icon">⏰</span> Deadline
        </div>
        <div class="issue-chip" onclick="toggleIssue(this)" role="checkbox" tabindex="0">
          <span class="chip-icon">💰</span> Pricing
        </div>
        <div class="issue-chip" onclick="toggleIssue(this)" role="checkbox" tabindex="0">
          <span class="chip-icon">📄</span> Originality
        </div>
        <div class="issue-chip" onclick="toggleIssue(this)" role="checkbox" tabindex="0">
          <span class="chip-icon">🔄</span> Revisions
        </div>
      </div>
    </div>

    <div class="card">
      <div class="field-group" style="height:100%;">
        <div class="field">
          <label class="field-label">Order ID</label>
          <div class="order-wrap">
            <span class="order-icon">🔖</span>
            <input type="text" id="orderIdInput" placeholder="e.g. AIN-2026-00123" maxlength="30" />
          </div>
          <span class="field-hint">Helps us locate and resolve your case faster</span>
        </div>
        <div class="field" style="flex:1;display:flex;flex-direction:column;">
          <label class="field-label">Your suggestion</label>
          <textarea
            class="field-input"
            id="suggestionInput"
            rows="7"
            placeholder="Tell us how we can improve. Be as specific as possible..."
            style="flex:1;resize:none;"
          ></textarea>
          <span class="field-hint" id="charCount">0 / 500 characters</span>
        </div>
      </div>
    </div>
  </div>

  <div id="apiErrorMsg" class="error-message"></div>

  <div class="submit-row">
    <button class="btn-ghost" onclick="goBack()" id="backBtn">← Back</button>
    
    <button type="button" class="btn-primary" id="submitFeedbackBtn" onclick="submitFeedbackToAPI()">Submit feedback →</button>
  </div>
</main>

<main class="page" id="screen3">
  <div class="success-card">
    <div class="success-anim">✅</div>
    <div class="success-title">Thank you for your feedback!</div>
    <div class="success-sub">
      Your response has been received. Our team will review your case and reach out shortly with a resolution.
    </div>
    <div class="success-meta">
      <span class="meta-pill" id="successRating">😞 Poor</span>
      <span class="meta-pill" id="successOrder">Order: —</span>
      <span class="meta-pill" id="successTime"></span>
    </div>
    <button class="btn-primary" onclick="resetAll()">Submit another response →</button>
  </div>
</main>

<footer class="footer">
  © 2026 Assignment in Need &nbsp;·&nbsp; All rights reserved
</footer>

<script>
  let selectedEmoji = null;

  const emojis    = { worst:'😡', poor:'😞', average:'😐', good:'🙂', excellent:'😁' };
  const questions = {
    worst:    'What made it the worst experience?',
    poor:     'Which area felt most disappointing?',
    average:  'What could we have done better?',
    good:     'What could make it even better?',
    excellent:'What did you love the most?'
  };
  const sections  = {
    worst:    'What went wrong?',
    poor:     'What went wrong?',
    average:  'What to improve?',
    good:     'What to improve?',
    excellent:'What stood out?'
  };

  function showPage(id) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function selectEmoji(btn) {
    document.querySelectorAll('.emoji-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedEmoji = { val: btn.dataset.val, label: btn.dataset.label };
    document.getElementById('nextBtn').disabled = false;
  }

  function goToScreen2() {
    if (!selectedEmoji) return;
    const v = selectedEmoji.val;
    document.getElementById('selectedBadge').textContent  = emojis[v] + ' ' + selectedEmoji.label + ' experience';
    document.getElementById('issueQuestion').textContent  = questions[v];
    document.getElementById('chipSectionLabel').textContent = sections[v];
    showPage('screen2');
  }

  function goBack() {
    showPage('screen1');
  }

  function toggleIssue(chip) {
    chip.classList.toggle('selected');
  }

  /* ── NEW: API Integration Function ── */
//   function submitFeedbackToAPI() {
//     // 1. Inputs get karna
//     const order_id = document.getElementById('orderIdInput').value.trim();
//     const your_suggestion = document.getElementById('suggestionInput').value.trim();
//     const experience = selectedEmoji ? selectedEmoji.label : '';

//     // 2. Sirf selected chips ka text nikalna (emoji hatakar)
//     const selectedChips = document.querySelectorAll('.issue-chip.selected');
//     const scopeArray = Array.from(selectedChips).map(chip => {
//         // Clone banakar icon hata rahe hain taaki sirf text mile (jaise "Pricing")
//         let clone = chip.cloneNode(true);
//         let icon = clone.querySelector('.chip-icon');
//         if(icon) clone.removeChild(icon);
//         return clone.textContent.trim();
//     });

//     // 3. Payload Set Karna (Aapki Laravel API ke hisab se)
//     const payload = {
//       order_id: order_id,
//       experience: experience,
//       feedback_scope: scopeArray, 
//       your_suggestion: your_suggestion
//     };

//     // 4. Loading State Set Karna
//     const submitBtn = document.getElementById('submitFeedbackBtn');
//     const backBtn = document.getElementById('backBtn');
//     const errorMsg = document.getElementById('apiErrorMsg');
    
//     submitBtn.innerText = "Submitting...";
//     submitBtn.disabled = true;
//     backBtn.disabled = true;
//     errorMsg.style.display = 'none';

//     // 5. Aapki Live API Call 🚀
//     fetch('https://backend-lovepeace.londonstreetstore.com/api/submit-feedback', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json',
//         'Accept': 'application/json'
//       },
//       body: JSON.stringify(payload)
//     })
//     .then(response => response.json())
//     .then(data => {
//       if(data.status === 'success') {
//          showSuccess(); // Server se success aane par hi Page 3 dikhega
//       } else {
//          errorMsg.innerText = "Error: " + data.message;
//          errorMsg.style.display = 'block';
//       }
//     })
//     .catch(error => {
//       console.error('API Error:', error);
//       errorMsg.innerText = "Connection failed. Please check your internet or CORS policy.";
//       errorMsg.style.display = 'block';
//     })
//     .finally(() => {
//       // Wapas normal state mein lana
//       submitBtn.innerText = "Submit feedback →";
//       submitBtn.disabled = false;
//       backBtn.disabled = false;
//     });
//   }

function submitFeedbackToAPI() {
    // 1. Inputs collect karna
    const order_id = document.getElementById('orderIdInput').value.trim();
    const your_suggestion = document.getElementById('suggestionInput').value.trim();
    
    // selectedEmoji object se label uthana (Excellent, Good, etc.)
    const experience = selectedEmoji ? selectedEmoji.label : '';

    // 2. Selected chips nikalna (Inhe saaf karke array mein convert karna)
    const selectedChips = document.querySelectorAll('.issue-chip.selected');
    const scopeArray = Array.from(selectedChips).map(chip => {
        // Icon (emoji) hatakar sirf text lena
        let clone = chip.cloneNode(true);
        let icon = clone.querySelector('.chip-icon');
        if(icon) clone.removeChild(icon);
        return clone.textContent.trim();
    });

    // 3. UI elements for Loading State
    const submitBtn = document.getElementById('submitFeedbackBtn');
    const errorMsg = document.getElementById('apiErrorMsg');
    
    // Button ko disable karna taaki double submit na ho
    submitBtn.innerText = "Submitting...";
    submitBtn.disabled = true;
    errorMsg.style.display = 'none';

    // 4. API CALL
    fetch("{{ url('/api/submit-feedback') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
    order_id: order_id,
    experience: experience,
    // Convert ['Slow', 'Cold'] to "Slow, Cold"
    feedback_scope: scopeArray.join(', '), 
    your_suggestion: your_suggestion
})
    })
    .then(async response => {
        const data = await response.json();
        if (response.ok && data.status === 'success') {
            showSuccess(); // Agli screen par le jayein
        } else {
            // Agar validation ya server error ho
            throw new Error(data.message || "Something went wrong");
        }
    })
    .catch(error => {
        console.error('API Error:', error);
        errorMsg.innerText = error.message; // User ko error dikhayein
        errorMsg.style.display = 'block';
    })
    .finally(() => {
        // Wapas button ko normal karna
        submitBtn.innerText = "Submit feedback →";
        submitBtn.disabled = false;
    });
}

  /* ── ORIGINAL SUCCESS FUNCTION (API success ke baad chalegi) ── */
  function showSuccess() {
    const order = document.getElementById('orderIdInput').value.trim();
    const now   = new Date();
    const time  = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    document.getElementById('successRating').textContent = selectedEmoji
      ? emojis[selectedEmoji.val] + ' ' + selectedEmoji.label
      : '—';
    document.getElementById('successOrder').textContent  = 'Order: ' + (order || '—');
    document.getElementById('successTime').textContent   = 'Submitted ' + time;
    showPage('screen3');
  }

  function resetAll() {
    selectedEmoji = null;
    document.querySelectorAll('.emoji-btn').forEach(b => b.classList.remove('selected'));
    document.querySelectorAll('.issue-chip').forEach(c => c.classList.remove('selected'));
    document.getElementById('orderIdInput').value = '';
    document.getElementById('suggestionInput').value = '';
    document.getElementById('charCount').textContent = '0 / 500 characters';
    document.getElementById('nextBtn').disabled = true;
    document.getElementById('apiErrorMsg').style.display = 'none';
    showPage('screen1');
  }

  // Character counter
  document.getElementById('suggestionInput').addEventListener('input', function () {
    const len = this.value.length;
    if (len > 500) this.value = this.value.slice(0, 500);
    document.getElementById('charCount').textContent = Math.min(len, 500) + ' / 500 characters';
  });

  // Keyboard support for issue chips
  document.querySelectorAll('.issue-chip').forEach(chip => {
    chip.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleIssue(chip); }
    });
  });
</script>
</body>
</html>