<div class="mini-quote">
  <!-- ✅ Offer Ribbon -->
  <!-- <div class="mini-offer">
    <img src="{{ asset('images/offer-3.png') }}" alt="Offer" class="mini-offer__img">
  </div> -->

  @if(empty($dontShowInModal) || $dontShowInModal == false)
    <p class="mini-quote__tagline">Get Free Assignment Quote Instantly</p>
  @endif

  <form id="quoteForm" class="mini-quote__form">
    <div class="mini-quote__grid">

      <div class="mini-field">
        <label for="q_name">Name</label>
        <input type="text" id="q_name" name="name" placeholder="Your name" required>
      </div>

      <div class="mini-field">
        <label for="q_email">Email</label>
        <input type="email" id="q_email" name="email" placeholder="you@example.com" required>
      </div>

      <div class="mini-field">
        <label for="q_deadline">Deadline</label>
        <select id="q_deadline" name="deadline" required>
          <option value="">Select deadline</option>
          <option value="1">1 Day</option>
          <option value="2">2 Days</option>
          <option value="3">3 Days</option>
          <option value="4">4 Days</option>
          <option value="5">5 Days</option>
          <option value="7">7 Days</option>
          <option value="10">10 Days</option>
          <option value="14">14 Days</option>
          <option value="21">21+ Days</option>
        </select>
      </div>

      <div class="mini-field">
        <label for="q_phone">Phone number</label>
        <div class="phone-field" id="phoneField">
          <button type="button" class="cc-btn" id="ccBtn">
            <span class="cc-flag" id="ccFlag">🇬🇧</span>
            <span class="cc-code" id="ccCode">+44</span>
            <span class="cc-caret">▾</span>
          </button>

          <input type="tel" id="q_phone" name="phone" placeholder="Numbers only*" required
                 oninput="this.value = this.value.replace(/[^0-9]/g, '');">

          <div class="cc-menu" id="ccMenu">
            <div class="cc-search-wrap">
              <input type="text" id="ccSearch" class="cc-search" placeholder="Search country...">
            </div>
            <div class="cc-list" id="ccList"></div>
          </div>
        </div>

        <!-- hidden fields (same as your form) -->
        <input type="hidden" id="countryCodeHidden" name="countryCode" value="+44">
        <input type="hidden" id="countryIsoHidden" name="countryIso" value="GB">
      </div>

      <div class="mini-field">
        <label for="q_service">Service</label>
        <select id="q_service" name="service" required>
          <option value="">Select service</option>
          <option value="Assignment">Assignment</option>
          <option value="Dissertation">Dissertation</option>
          <option value="Essay">Essay</option>
          <option value="SOP">SOP</option>
          <option value="Other">Other</option>
        </select>
      </div>

      <div class="mini-field">
        <label for="q_subject">Subject</label>
        <input type="text" id="q_subject" name="subject" placeholder="e.g., IT" required>
      </div>

      <div class="mini-field mini-field--pages">
        <label>Pages</label>
        <div class="pages-stepper">
          <button type="button" class="pg-btn" id="pgMinus">−</button>
          <div class="pg-mid">
            <div class="pg-pages"><span id="pgVal">1</span> <span class="pg-muted">Pages</span></div>
            <div class="pg-words"><span id="wordVal">250</span> <span class="pg-muted">words</span></div>
          </div>
          <button type="button" class="pg-btn" id="pgPlus">+</button>
        </div>
        <input type="hidden" name="pages" id="pagesHidden" value="1">
        <input type="hidden" name="words" id="wordsHidden" value="250">
      </div>
    </div>

    <div class="mini-policy">
      <label class="mini-check">
        <input type="checkbox" id="policy" name="policy" required>
        <span class="mini-check__box"></span>
        <span class="mini-check__text">I agree with <a href="/PrivacyPolicy">Privacy Policy</a> & <a href="/Terms-Conditions">T&C</a>.</span>
      </label>
    </div>

    <button type="submit" class="mini-submit" id="submitBtn">
      <span class="btn-text" id="btnText">Chat with experts now</span>
      <span class="spinner" id="spinner" style="display:none;">⏳</span>
    </button>
  </form>
</div>

<div class="toast" id="toastMessage"></div>

<style>
  :root {
    --text: #0f172a;
    --muted: #64748b;
    --accent: #3F159A;
    --accent-2: #0E8FCE;
  }

  /* ✅ Form Height Reduced */
  .mini-quote {
    width: 440px; max-width: 100%;
    padding: 10px 14px;
    border-radius: 16px;
    background: linear-gradient(135deg, rgba(255,255,255,.9) 0%, rgba(237,231,248,.7) 100%);
    border: 1px solid rgba(255,255,255,.5);
    box-shadow: 0 10px 30px rgba(2, 6, 23, .08);
    position: relative;
	border: 1px solid black;
  }

  .mini-offer {
    width: 100%;
    justify-content: center;
    position: absolute;
    height: 48px;
    width: 300px;
    top: -25px;
    right: 0px;
    z-index: 5;
    pointer-events: none;
  }

  .mini-offer__img{
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    border-top-right-radius: 10px;
  }

  .mini-quote__tagline{
    padding-top: 16px;
  }

  .mini-quote__tagline { font-size: 18px; color: var(--accent); font-weight: 700; margin-bottom: 8px; text-align: center; }

  .mini-quote__grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 10px; }

  .mini-field label { display: block; font-size: 11px; font-weight: 700; margin-bottom: 1px; color: #333; }
  .mini-field input, .mini-field select {
    width: 100%; height: 36px; border-radius: 8px; border: 1px solid var(--accent);
    padding: 0 10px; font-size: 13px; outline: none; background: #fff;
  }
  .mini-field--pages { grid-column: 1 / -1; }

  .phone-field { position: relative; display: flex; border-radius: 8px; border: 1px solid var(--accent); background: #fff; overflow: visible; }
  .cc-btn { display: flex; align-items: center; gap: 4px; padding: 0 6px; background: #f1f5f9; border: none; border-right: 1px solid #ddd; cursor: pointer; font-weight: 700; min-width: 70px; height: 34px; font-size: 12px; }
  .phone-field input[type="tel"] { flex: 1; border: none !important; height: 34px; padding: 0 8px; }

  .cc-menu { position: absolute; top: 38px; left: 0; width: 100%; background: #fff; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); display: none; z-index: 999; }
  .cc-menu.open { display: block; }
  .cc-list { max-height: 180px; overflow-y: auto; }
  .cc-item { display: flex; align-items: center; gap: 8px; padding: 8px; cursor: pointer; font-size: 12px; border-bottom: 1px solid #f1f5f9; }

  .pages-stepper { display: flex; align-items: center; border: 1px solid var(--accent); border-radius: 8px; height: 36px; background: #fff; }
  .pg-btn { width: 36px; height: 100%; border: none; background: #f8fafc; font-size: 16px; font-weight: 700; cursor: pointer; }
  .pg-mid { flex: 1; display: flex; justify-content: center; gap: 8px; font-size: 12px; font-weight: 700; }
  .pg-muted { color: var(--muted); font-size: 10px; }

  .mini-policy { margin-top: 6px; font-size: 11px; }

  .mini-submit {
    width: 100%; height: 40px; margin-top: 10px; border: none; border-radius: 10px;
    background: linear-gradient(135deg, rgba(63, 21, 154, 0.85), rgba(14, 143, 206, 0.85));
    backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
    color: #fff; font-weight: 800; font-size: 13px; cursor: pointer;
    box-shadow: 0 4px 15px rgba(63, 21, 154, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.2s;
  }
  .mini-submit:hover { transform: translateY(-1px); background: linear-gradient(135deg, rgba(63, 21, 154, 1), rgba(14, 143, 206, 1)); }

  .toast {
    position: fixed; top: 20px; right: 20px;
    padding: 10px 12px;
    background: #ef4444; color: #fff;
    border-radius: 8px; display: none;
    z-index: 10000; font-size: 12px;
  }
  .toast.success { background: #22c55e; }

  /* ✅ field error text */
  .mini-error{
    margin-top: 4px;
    font-size: 11px;
    color: #ef4444;
    font-weight: 600;
    line-height: 1.2;
  }
  .mini-field.has-error input,
  .mini-field.has-error select,
  .mini-field.has-error .phone-field{
    border-color: #ef4444 !important;
  }

  @media (max-width: 600px){
    .mini-quote{ width: 100%; padding: 10px 12px; }
    .mini-quote__grid{ grid-template-columns: 1fr; gap: 6px; }
    .mini-offer{
      left: 50%;
      right: auto;
      transform: translateX(-50%);
      width: min(290px, 92%);
      top: -16px;
      height: 42px;
    }
    .mini-quote__tagline{ padding-top: 18px; font-size: 16px; }
  }
</style>

<!-- ✅ SweetAlert (add once on page if not already) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
const COUNTRIES = [
  { n:"Afghanistan", i:"AF", d:"+93" },
  { n:"Albania", i:"AL", d:"+355" },
  { n:"Algeria", i:"DZ", d:"+213" },
  { n:"American Samoa", i:"AS", d:"+1" },
  { n:"Andorra", i:"AD", d:"+376" },
  { n:"Angola", i:"AO", d:"+244" },
  { n:"Anguilla", i:"AI", d:"+1" },
  { n:"Antigua & Barbuda", i:"AG", d:"+1" },
  { n:"Argentina", i:"AR", d:"+54" },
  { n:"Armenia", i:"AM", d:"+374" },
  { n:"Aruba", i:"AW", d:"+297" },
  { n:"Australia", i:"AU", d:"+61" },
  { n:"Austria", i:"AT", d:"+43" },
  { n:"Azerbaijan", i:"AZ", d:"+994" },

  { n:"Bahamas", i:"BS", d:"+1" },
  { n:"Bahrain", i:"BH", d:"+973" },
  { n:"Bangladesh", i:"BD", d:"+880" },
  { n:"Barbados", i:"BB", d:"+1" },
  { n:"Belarus", i:"BY", d:"+375" },
  { n:"Belgium", i:"BE", d:"+32" },
  { n:"Belize", i:"BZ", d:"+501" },
  { n:"Benin", i:"BJ", d:"+229" },
  { n:"Bermuda", i:"BM", d:"+1" },
  { n:"Bhutan", i:"BT", d:"+975" },
  { n:"Bolivia", i:"BO", d:"+591" },
  { n:"Bosnia & Herzegovina", i:"BA", d:"+387" },
  { n:"Botswana", i:"BW", d:"+267" },
  { n:"Brazil", i:"BR", d:"+55" },
  { n:"British Virgin Islands", i:"VG", d:"+1" },
  { n:"Brunei", i:"BN", d:"+673" },
  { n:"Bulgaria", i:"BG", d:"+359" },
  { n:"Burkina Faso", i:"BF", d:"+226" },
  { n:"Burundi", i:"BI", d:"+257" },

  { n:"Cambodia", i:"KH", d:"+855" },
  { n:"Cameroon", i:"CM", d:"+237" },
  { n:"Canada", i:"CA", d:"+1" },
  { n:"Cape Verde", i:"CV", d:"+238" },
  { n:"Cayman Islands", i:"KY", d:"+1" },
  { n:"Central African Republic", i:"CF", d:"+236" },
  { n:"Chad", i:"TD", d:"+235" },
  { n:"Chile", i:"CL", d:"+56" },
  { n:"China", i:"CN", d:"+86" },
  { n:"Colombia", i:"CO", d:"+57" },
  { n:"Comoros", i:"KM", d:"+269" },
  { n:"Congo (DRC)", i:"CD", d:"+243" },
  { n:"Congo (Republic)", i:"CG", d:"+242" },
  { n:"Costa Rica", i:"CR", d:"+506" },
  { n:"Croatia", i:"HR", d:"+385" },
  { n:"Cuba", i:"CU", d:"+53" },
  { n:"Cyprus", i:"CY", d:"+357" },
  { n:"Czech Republic", i:"CZ", d:"+420" },

  { n:"Denmark", i:"DK", d:"+45" },
  { n:"Djibouti", i:"DJ", d:"+253" },
  { n:"Dominica", i:"DM", d:"+1" },
  { n:"Dominican Republic", i:"DO", d:"+1" },

  { n:"Ecuador", i:"EC", d:"+593" },
  { n:"Egypt", i:"EG", d:"+20" },
  { n:"El Salvador", i:"SV", d:"+503" },
  { n:"Equatorial Guinea", i:"GQ", d:"+240" },
  { n:"Eritrea", i:"ER", d:"+291" },
  { n:"Estonia", i:"EE", d:"+372" },
  { n:"Eswatini", i:"SZ", d:"+268" },
  { n:"Ethiopia", i:"ET", d:"+251" },

  { n:"Fiji", i:"FJ", d:"+679" },
  { n:"Finland", i:"FI", d:"+358" },
  { n:"France", i:"FR", d:"+33" },

  { n:"Gabon", i:"GA", d:"+241" },
  { n:"Gambia", i:"GM", d:"+220" },
  { n:"Georgia", i:"GE", d:"+995" },
  { n:"Germany", i:"DE", d:"+49" },
  { n:"Ghana", i:"GH", d:"+233" },
  { n:"Gibraltar", i:"GI", d:"+350" },
  { n:"Greece", i:"GR", d:"+30" },
  { n:"Greenland", i:"GL", d:"+299" },
  { n:"Grenada", i:"GD", d:"+1" },
  { n:"Guam", i:"GU", d:"+1" },
  { n:"Guatemala", i:"GT", d:"+502" },
  { n:"Guernsey", i:"GG", d:"+44" },
  { n:"Guinea", i:"GN", d:"+224" },
  { n:"Guinea-Bissau", i:"GW", d:"+245" },
  { n:"Guyana", i:"GY", d:"+592" },

  { n:"Haiti", i:"HT", d:"+509" },
  { n:"Honduras", i:"HN", d:"+504" },
  { n:"Hong Kong", i:"HK", d:"+852" },
  { n:"Hungary", i:"HU", d:"+36" },

  { n:"Iceland", i:"IS", d:"+354" },
  { n:"India", i:"IN", d:"+91" },
  { n:"Indonesia", i:"ID", d:"+62" },
  { n:"Iran", i:"IR", d:"+98" },
  { n:"Iraq", i:"IQ", d:"+964" },
  { n:"Ireland", i:"IE", d:"+353" },
  { n:"Isle of Man", i:"IM", d:"+44" },
  { n:"Israel", i:"IL", d:"+972" },
  { n:"Italy", i:"IT", d:"+39" },

  { n:"Jamaica", i:"JM", d:"+1" },
  { n:"Japan", i:"JP", d:"+81" },
  { n:"Jersey", i:"JE", d:"+44" },
  { n:"Jordan", i:"JO", d:"+962" },

  { n:"Kazakhstan", i:"KZ", d:"+7" },
  { n:"Kenya", i:"KE", d:"+254" },
  { n:"Kiribati", i:"KI", d:"+686" },
  { n:"Kuwait", i:"KW", d:"+965" },
  { n:"Kyrgyzstan", i:"KG", d:"+996" },

  { n:"Laos", i:"LA", d:"+856" },
  { n:"Latvia", i:"LV", d:"+371" },
  { n:"Lebanon", i:"LB", d:"+961" },
  { n:"Lesotho", i:"LS", d:"+266" },
  { n:"Liberia", i:"LR", d:"+231" },
  { n:"Libya", i:"LY", d:"+218" },
  { n:"Liechtenstein", i:"LI", d:"+423" },
  { n:"Lithuania", i:"LT", d:"+370" },
  { n:"Luxembourg", i:"LU", d:"+352" },

  { n:"Macau", i:"MO", d:"+853" },
  { n:"Madagascar", i:"MG", d:"+261" },
  { n:"Malawi", i:"MW", d:"+265" },
  { n:"Malaysia", i:"MY", d:"+60" },
  { n:"Maldives", i:"MV", d:"+960" },
  { n:"Mali", i:"ML", d:"+223" },
  { n:"Malta", i:"MT", d:"+356" },
  { n:"Marshall Islands", i:"MH", d:"+692" },
  { n:"Mauritania", i:"MR", d:"+222" },
  { n:"Mauritius", i:"MU", d:"+230" },
  { n:"Mexico", i:"MX", d:"+52" },
  { n:"Micronesia", i:"FM", d:"+691" },
  { n:"Moldova", i:"MD", d:"+373" },
  { n:"Monaco", i:"MC", d:"+377" },
  { n:"Mongolia", i:"MN", d:"+976" },
  { n:"Montenegro", i:"ME", d:"+382" },
  { n:"Morocco", i:"MA", d:"+212" },
  { n:"Mozambique", i:"MZ", d:"+258" },
  { n:"Myanmar", i:"MM", d:"+95" },

  { n:"Namibia", i:"NA", d:"+264" },
  { n:"Nauru", i:"NR", d:"+674" },
  { n:"Nepal", i:"NP", d:"+977" },
  { n:"Netherlands", i:"NL", d:"+31" },
  { n:"New Zealand", i:"NZ", d:"+64" },
  { n:"Nicaragua", i:"NI", d:"+505" },
  { n:"Niger", i:"NE", d:"+227" },
  { n:"Nigeria", i:"NG", d:"+234" },
  { n:"North Macedonia", i:"MK", d:"+389" },
  { n:"Norway", i:"NO", d:"+47" },

  { n:"Oman", i:"OM", d:"+968" },

  { n:"Pakistan", i:"PK", d:"+92" },
  { n:"Palau", i:"PW", d:"+680" },
  { n:"Palestine", i:"PS", d:"+970" },
  { n:"Panama", i:"PA", d:"+507" },
  { n:"Papua New Guinea", i:"PG", d:"+675" },
  { n:"Paraguay", i:"PY", d:"+595" },
  { n:"Peru", i:"PE", d:"+51" },
  { n:"Philippines", i:"PH", d:"+63" },
  { n:"Poland", i:"PL", d:"+48" },
  { n:"Portugal", i:"PT", d:"+351" },
  { n:"Puerto Rico", i:"PR", d:"+1" },

  { n:"Qatar", i:"QA", d:"+974" },

  { n:"Romania", i:"RO", d:"+40" },
  { n:"Russia", i:"RU", d:"+7" },
  { n:"Rwanda", i:"RW", d:"+250" },

  { n:"Saint Kitts & Nevis", i:"KN", d:"+1" },
  { n:"Saint Lucia", i:"LC", d:"+1" },
  { n:"Saint Vincent & Grenadines", i:"VC", d:"+1" },
  { n:"Samoa", i:"WS", d:"+685" },
  { n:"San Marino", i:"SM", d:"+378" },
  { n:"Sao Tome & Principe", i:"ST", d:"+239" },
  { n:"Saudi Arabia", i:"SA", d:"+966" },
  { n:"Senegal", i:"SN", d:"+221" },
  { n:"Serbia", i:"RS", d:"+381" },
  { n:"Seychelles", i:"SC", d:"+248" },
  { n:"Sierra Leone", i:"SL", d:"+232" },
  { n:"Singapore", i:"SG", d:"+65" },
  { n:"Slovakia", i:"SK", d:"+421" },
  { n:"Slovenia", i:"SI", d:"+386" },
  { n:"Solomon Islands", i:"SB", d:"+677" },
  { n:"Somalia", i:"SO", d:"+252" },
  { n:"South Africa", i:"ZA", d:"+27" },
  { n:"South Korea", i:"KR", d:"+82" },
  { n:"South Sudan", i:"SS", d:"+211" },
  { n:"Spain", i:"ES", d:"+34" },
  { n:"Sri Lanka", i:"LK", d:"+94" },
  { n:"Sudan", i:"SD", d:"+249" },
  { n:"Suriname", i:"SR", d:"+597" },
  { n:"Sweden", i:"SE", d:"+46" },
  { n:"Switzerland", i:"CH", d:"+41" },
  { n:"Syria", i:"SY", d:"+963" },

  { n:"Taiwan", i:"TW", d:"+886" },
  { n:"Tajikistan", i:"TJ", d:"+992" },
  { n:"Tanzania", i:"TZ", d:"+255" },
  { n:"Thailand", i:"TH", d:"+66" },
  { n:"Togo", i:"TG", d:"+228" },
  { n:"Tonga", i:"TO", d:"+676" },
  { n:"Trinidad & Tobago", i:"TT", d:"+1" },
  { n:"Tunisia", i:"TN", d:"+216" },
  { n:"Turkey", i:"TR", d:"+90" },
  { n:"Turkmenistan", i:"TM", d:"+993" },
  { n:"Turks & Caicos", i:"TC", d:"+1" },

  { n:"Uganda", i:"UG", d:"+256" },
  { n:"Ukraine", i:"UA", d:"+380" },
  { n:"United Arab Emirates", i:"AE", d:"+971" },
  { n:"United Kingdom", i:"GB", d:"+44" },
  { n:"United States", i:"US", d:"+1" },
  { n:"Uruguay", i:"UY", d:"+598" },
  { n:"Uzbekistan", i:"UZ", d:"+998" },

  { n:"Vanuatu", i:"VU", d:"+678" },
  { n:"Vatican City", i:"VA", d:"+379" },
  { n:"Venezuela", i:"VE", d:"+58" },
  { n:"Vietnam", i:"VN", d:"+84" },

  { n:"Yemen", i:"YE", d:"+967" },

  { n:"Zambia", i:"ZM", d:"+260" },
  { n:"Zimbabwe", i:"ZW", d:"+263" }
];


  const ccBtn  = document.getElementById('ccBtn');
  const ccMenu = document.getElementById('ccMenu');
  const ccList = document.getElementById('ccList');

  function getFlag(iso) {
    return iso.toUpperCase().replace(/./g, char => String.fromCodePoint(char.charCodeAt(0) + 127397));
  }

  function renderCountries(list = COUNTRIES) {
    ccList.innerHTML = list.map(c => `
      <div class="cc-item" data-name="${c.n.toLowerCase()}" data-iso="${c.i}" data-dial="${c.d}">
        <span>${getFlag(c.i)}</span>
        <span>${c.n}</span>
        <span style="margin-left:auto; font-weight:700;">${c.d}</span>
      </div>
    `).join('');
  }

  function selectCountry(iso, dial) {
    document.getElementById('ccFlag').textContent = getFlag(iso);
    document.getElementById('ccCode').textContent = dial;
    document.getElementById('countryCodeHidden').value = dial;
    document.getElementById('countryIsoHidden').value  = iso;
    ccMenu.classList.remove('open');
  }

  ccList.addEventListener('click', (e) => {
    const item = e.target.closest('.cc-item');
    if (!item) return;
    selectCountry(item.dataset.iso, item.dataset.dial);
  });

  ccBtn.onclick = (e) => { e.stopPropagation(); ccMenu.classList.toggle('open'); };
  document.onclick = () => ccMenu.classList.remove('open');

  const ccSearch = document.getElementById('ccSearch');
  if (ccSearch) {
    ccSearch.addEventListener('input', () => {
      const q = ccSearch.value.toLowerCase().trim();
      if (!q) return renderCountries(COUNTRIES);
      renderCountries(COUNTRIES.filter(c => c.n.toLowerCase().includes(q)));
    });
  }

  renderCountries();

  // Stepper Logic
  const pgVal = document.getElementById('pgVal');
  const wordVal = document.getElementById('wordVal');
  const pagesHidden = document.getElementById('pagesHidden');
  const wordsHidden = document.getElementById('wordsHidden');

  function updatePages(step) {
    let val = parseInt(pagesHidden.value || "1", 10) + step;
    if (val < 1) val = 1;
    pagesHidden.value = val;
    wordsHidden.value = String(val * 250);
    pgVal.textContent = val;
    wordVal.textContent = val * 250;
  }

  document.getElementById('pgPlus').onclick = () => updatePages(1);
  document.getElementById('pgMinus').onclick = () => updatePages(-1);

  // Toast helper (kept)
  const toast = document.getElementById('toastMessage');
  function showToast(msg, ok = false) {
    toast.textContent = msg;
    toast.classList.toggle('success', !!ok);
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 2500);
  }

  // ✅ Error helpers (field-wise)
  function clearErrors(){
    document.querySelectorAll('.mini-error').forEach(el => el.remove());
    document.querySelectorAll('.mini-field').forEach(el => el.classList.remove('has-error'));
  }

  function setFieldError(el, message){
    if (!el) return;
    const fieldWrap = el.closest('.mini-field') || el;
    fieldWrap.classList.add('has-error');

    // avoid duplicate
    const existing = fieldWrap.querySelector('.mini-error');
    if (existing) existing.remove();

    const div = document.createElement('div');
    div.className = 'mini-error';
    div.textContent = message;
    fieldWrap.appendChild(div);
  }

  function validatePayload(payload){
    clearErrors();

    let ok = true;

    if (!payload.name){
      ok = false; setFieldError(document.getElementById('q_name'), 'Name is required.');
    }
    if (!payload.email){
      ok = false; setFieldError(document.getElementById('q_email'), 'Email is required.');
    } else {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!re.test(payload.email)){
        ok = false; setFieldError(document.getElementById('q_email'), 'Enter a valid email.');
      }
    }
    if (!payload.deadline){
      ok = false; setFieldError(document.getElementById('q_deadline'), 'Please select deadline.');
    }
    if (!payload.phone){
      ok = false; setFieldError(document.getElementById('q_phone'), 'Phone number is required.');
    }
    if (!payload.countryCode){
      ok = false; setFieldError(document.getElementById('q_phone'), 'Country code is required.');
    }
    if (!payload.service){
      ok = false; setFieldError(document.getElementById('q_service'), 'Please select service.');
    }
    if (!payload.subject){
      ok = false; setFieldError(document.getElementById('q_subject'), 'Subject is required.');
    }
    if (!payload.policy){
      ok = false; setFieldError(document.getElementById('policy'), 'You must agree to Privacy Policy & T&C.');
    }

    return ok;
  }

  // ✅ SUBMIT
  const form = document.getElementById('quoteForm');
  const submitBtn = document.getElementById('submitBtn');
  const btnText = document.getElementById('btnText');
  const spinner = document.getElementById('spinner');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const payload = {
      name: document.getElementById('q_name').value.trim(),
      email: document.getElementById('q_email').value.trim(),
      deadline: document.getElementById('q_deadline').value,
      phone: document.getElementById('q_phone').value.trim(),
      countryCode: document.getElementById('countryCodeHidden').value.trim(),
      countryIso: document.getElementById('countryIsoHidden').value.trim(),
      service: document.getElementById('q_service').value,
      subject: document.getElementById('q_subject').value.trim(),
      pages: document.getElementById('pagesHidden').value,
      words: document.getElementById('wordsHidden').value,
      policy: document.getElementById('policy').checked,

      // ✅ new source page (as per backend)
      source_page: window.location.href
    };

    if (!validatePayload(payload)) return;

    // loading
    submitBtn.disabled = true;
    btnText.style.display = "none";
    spinner.style.display = "inline";

    fetch("{{ route('mini-new-order') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify(payload)
    })
    .then(async (res) => {
      const data = await res.json().catch(() => ({}));

      // Laravel validation errors (422)
      if (res.status === 422 && data && data.errors) {
        Object.keys(data.errors).forEach((key) => {
          const msg = data.errors[key]?.[0] || 'Invalid value.';
          const map = {
            name: 'q_name',
            email: 'q_email',
            deadline: 'q_deadline',
            phone: 'q_phone',
            service: 'q_service',
            subject: 'q_subject',
            policy: 'policy',
            countryCode: 'q_phone',
            words: 'pagesHidden',
            pages: 'pagesHidden'
          };
          const id = map[key] || null;
          if (id) setFieldError(document.getElementById(id), msg);
        });

        throw { message: "Please fix the highlighted fields." };
      }

      if (!res.ok) throw data;
      return data;
    })
    .then((data) => {
      if (data && data.success) {

        // reset UI
        form.reset();
        selectCountry("GB", "+44");

        pagesHidden.value = "1";
        wordsHidden.value = "250";
        pgVal.textContent = "1";
        wordVal.textContent = "250";

        clearErrors();

        // ✅ redirect to thank you page
        window.location.href = "/thank-you?order=" + encodeURIComponent(data.order_id || "");

      } else {
        Swal.fire({
          icon: "error",
          title: "Submission Failed",
          text: (data && data.message) ? data.message : "Submission failed.",
          confirmButtonColor: "#3F159A"
        });
      }
    })
    .catch((err) => {
      const msg = (err && err.message) ? err.message : "Something went wrong. Try again later.";
      console.error(err);

      Swal.fire({
        icon: "error",
        title: "Error",
        text: msg,
        confirmButtonColor: "#3F159A"
      });
    })
    .finally(() => {
      submitBtn.disabled = false;
      btnText.style.display = "inline";
      spinner.style.display = "none";
    });
  });
});
</script>
