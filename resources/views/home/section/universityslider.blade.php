<section class="university-section">
  <!-- Floating Bubbles -->
  <div class="uni-shape uni-shape-1"></div>
  <div class="uni-shape uni-shape-2"></div>
  <div class="uni-shape uni-shape-3"></div>

  <div class="uni-wrap">
    <h2 class="section-title">Trusted by Students from Leading UK Universities</h2>

    <div class="university-marquee">

      <!-- Row 1 -->
      <div class="university-marquee-row left-right">
        <div class="university-marquee-track">

          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/bristol.jpeg"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/cambridge.png"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/edinburg.jpg"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/glasgow.jpeg"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/imperial.jpg"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/lse.jpg"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/manchester.jpg"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/oxford.jpg"></div></div>

          <!-- Repeat for smooth loop -->
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/bristol.jpeg"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/cambridge.png"></div></div>

        </div>
      </div>

      <!-- Row 2 -->
      <div class="university-marquee-row right-left">
        <div class="university-marquee-track">

          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/ucl.png"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/warwick.jpeg"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/kingslondon.png"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/durham.png"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/leedsuni.png"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/birmingham.png"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/southampton.png"></div></div>

          <!-- Repeat -->
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/ucl.png"></div></div>
          <div class="university-card"><div class="university-inner"><img src="/assets/media/layout/university/warwick.jpeg"></div></div>

        </div>
      </div>

    </div>
  </div>
</section>
<style>
    /* ===== FULL WIDTH HERO STYLE BACKGROUND ===== */
.university-section {
  position: relative;
  width: 100%;
  overflow: hidden;
  padding: 60px 0;

  background: linear-gradient(
    135deg,
    #e0e7ff 0%,
    #f3e8ff 50%,
    #fce7f3 100%
  );
}

/* animated gradient layer */
.university-section::before {
  content: "";
  position: absolute;
  inset: -50%;
  background:
    radial-gradient(circle at 20% 50%, rgba(99,102,241,0.12), transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(236,72,153,0.12), transparent 50%),
    radial-gradient(circle at 40% 20%, rgba(139,92,246,0.12), transparent 50%);
  animation: bgShift 20s ease-in-out infinite;
  z-index: 0;
}

@keyframes bgShift {
  0%,100% { transform: translate(0,0) rotate(0deg); }
  33% { transform: translate(30px,-30px) rotate(3deg); }
  66% { transform: translate(-20px,20px) rotate(-3deg); }
}

/* Floating Bubbles */
.uni-shape {
  position: absolute;
  opacity: 0.12;
  z-index: 0;
}

.uni-shape-1 {
  width: 260px;
  height: 260px;
  background: linear-gradient(135deg,#6366f1,#ec4899);
  border-radius: 50%;
  top: 10%;
  left: 5%;
  animation: float 15s ease-in-out infinite;
}

.uni-shape-2 {
  width: 210px;
  height: 210px;
  background: linear-gradient(135deg,#8b5cf6,#ec4899);
  border-radius: 40%;
  top: 60%;
  right: 8%;
  animation: float 12s ease-in-out infinite reverse;
}

.uni-shape-3 {
  width: 150px;
  height: 150px;
  background: linear-gradient(135deg,#6366f1,#8b5cf6);
  border-radius: 50%;
  bottom: 12%;
  left: 15%;
  animation: float 18s ease-in-out infinite;
}

@keyframes float {
  0%,100% { transform: translateY(0); }
  50% { transform: translateY(-30px); }
}

/* ===== CONTENT WRAP ===== */
.uni-wrap {
  position: relative;
  z-index: 1;
  max-width: 1200px;
  margin: auto;
  padding: 0 18px;
}

/* ===== UNIVERSITY CARDS ===== */
.university-card {
  display: inline-block;
  padding: 2px;
  border-radius: 20px;
  background: linear-gradient(50deg,#3F159A,white,#3F159A);
}

.university-inner {
  background: #fff;
  border-radius: 18px;
  padding: 10px;
  width: 200px;
  height: 80px;
}

.university-inner img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

/* ===== MARQUEE ===== */
.university-marquee { display: grid; gap: 20px; }

.university-marquee-row {
  overflow: hidden;
  white-space: nowrap;
}

.university-marquee-track {
  display: inline-flex;
  gap: 40px;
  animation: scroll-left 20s linear infinite;
}

.university-marquee-row.right-left .university-marquee-track {
  animation: scroll-right 20s linear infinite;
}

@keyframes scroll-left {
  0% { transform: translateX(-30%); }
  100% { transform: translateX(0%); }
}

@keyframes scroll-right {
  0% { transform: translateX(0%); }
  100% { transform: translateX(-80%); }
}

/* Mobile */
@media (max-width:768px){
  .university-inner{ width:150px; height:65px; }
}

</style>