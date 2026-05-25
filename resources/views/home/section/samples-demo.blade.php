<!-- ===== SAMPLE SECTION (Hero Background + Same Cards) ===== -->

<section class="sample-page">

  <!-- HERO floating shapes -->
  <div class="sample-shape shape-1"></div>
  <div class="sample-shape shape-2"></div>
  <div class="sample-shape shape-3"></div>

  <div style="max-width: 1200px; margin: 0 auto; position: relative; z-index: 1;">
    <h2 class="section-title">Try Our Free Assignment Writing Samples</h2>

    <p class="section-description">
      Explore thousands of free assignment samples across all subject areas.
    </p>

    <div class="card-container">
      @foreach ($data["sample"] as $sample)
        <div class="card-wrap">
          <div class="card">

            <h3>Topic: {{$sample->custom_heading }}</h3>

            <div class="info"><span>Subject:</span><span>{{$sample->categotyData->name}}</span></div>

            <div class="info">
              <span>No. of Pages:</span>
              <span>
                @php
                  $pageCount = ceil(str_word_count(strip_tags($sample->content)) / 250);
                @endphp
                {{ $pageCount }}
              </span>
            </div>

            <div class="info"><span>Total Words:</span><span>{{ str_word_count(strip_tags($sample->content)) }}</span></div>
            <div class="info"><span>Paper Type:</span><span>{{$sample->type->name }}</span></div>

            <a href="/free-samples/{{$sample->categotyData->name}}/{{$sample->slug}}" class="view-button">View Sample</a>

          </div>
        </div>
      @endforeach
    </div>

    <div style="display:flex; justify-content:center;">
      <a class="trust-cta" href="/free-samples">View More Samples</a>
    </div>

  </div>
</section>

<style>

/* ================= HERO BACKGROUND ================= */

.sample-page {
  position: relative;
  overflow: hidden;
  padding: 40px 18px;

  background: linear-gradient(
    135deg,
    #e0e7ff 0%,
    #f3e8ff 50%,
    #fce7f3 100%
  );
}

/* moving gradient layer */
.sample-page::before {
  content: "";
  position: absolute;
  inset: -50%;
  background:
    radial-gradient(circle at 20% 50%, rgba(99,102,241,0.10), transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(236,72,153,0.10), transparent 50%),
    radial-gradient(circle at 40% 20%, rgba(139,92,246,0.10), transparent 50%);
  animation: sampleBgMove 20s ease-in-out infinite;
  z-index: 0;
}

@keyframes sampleBgMove {
  0%,100% { transform: translate(0,0) rotate(0deg); }
  33% { transform: translate(30px,-25px) rotate(2deg); }
  66% { transform: translate(-20px,20px) rotate(-2deg); }
}

/* floating bubbles */

.sample-shape {
  position: absolute;
  opacity: 0.12;
  border-radius: 50%;
  z-index: 0;
}

.shape-1 {
  width: 260px;
  height: 260px;
  background: linear-gradient(135deg,#6366f1,#ec4899);
  top: 10%;
  left: 5%;
  animation: sampleFloat 15s ease-in-out infinite;
}

.shape-2 {
  width: 200px;
  height: 200px;
  background: linear-gradient(135deg,#8b5cf6,#ec4899);
  top: 60%;
  right: 8%;
  animation: sampleFloat 12s ease-in-out infinite reverse;
}

.shape-3 {
  width: 150px;
  height: 150px;
  background: linear-gradient(135deg,#6366f1,#8b5cf6);
  bottom: 10%;
  left: 15%;
  animation: sampleFloat 18s ease-in-out infinite;
}

@keyframes sampleFloat {
  0%,100% { transform: translateY(0); }
  50% { transform: translateY(-30px); }
}

/* ================= CARDS (UNCHANGED UI + HOVER) ================= */

.card-container {
  display: flex;
  gap: 30px;
  padding: 20px 0;
  justify-content: center;
  align-items: center;
  flex-wrap: nowrap;
}

.card-wrap {
  width: 100%;
  height: 300px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.card {
  width: 260px;
  background: #6B4FA7;
  border-radius: 15px;
  padding: 30px;
  position: relative;
  transform: translateY(-10px);
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
  transition: transform .25s ease, box-shadow .25s ease;
}

.card::after {
  content: "";
  position: absolute;
  inset: 0;
  background: #DCCCFF;
  border-radius: 10px;
  transform: rotate(-7deg);
  z-index: -1;
  transition: transform .25s ease;
}

/* hover */
.card-wrap:hover .card {
  transform: translateY(-18px);
  box-shadow: 0 14px 26px rgba(0,0,0,0.22);
}

.card-wrap:hover .card::after {
  transform: rotate(-9deg);
}

.card h3 {
  font-size: 16px;
  margin-bottom: 10px;
  border-bottom: 2px solid #000;
}

.info {
  font-size: 14px;
  margin-bottom: 5px;
  display: flex;
  justify-content: space-between;
}

.view-button {
  display: block;
  text-align: center;
  background: #6C49B8;
  color: #fff;
  padding: 8px;
  border-radius: 5px;
  text-decoration: none;
  font-weight: 600;
  margin-top: 10px;
  transition: transform .2s ease;
}

.view-button:hover {
  transform: translateY(-2px);
}

/* ================= RESPONSIVE ================= */

@media (max-width:1024px){
  .card-container { flex-wrap: wrap; gap: 40px; }
  .card-wrap { flex: 0 0 45%; height:auto; }
}

@media (max-width:768px){
  .card-container { flex-direction: column; }
  .card-wrap { flex: 0 0 100%; }
}

</style>
