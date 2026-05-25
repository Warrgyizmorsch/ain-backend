<div>
  @props(['heading', 'paragraph', 'fparagraph' => '', 'subjects' => []])

  @php
  use Illuminate\Support\Str;
@endphp

  <section class="page-section subjects-section">
    <div class="container">
      <h2 class="section-title">
        {{ $heading }}
      </h2>
      <p class="section-description"> {{ $paragraph }}</p>

     
      @if (!empty($fparagraph))
      <p class="section-description mt-4"> {{ $fparagraph }}</p>
    @endif
    </div>

  </section>


 <style>
/* Common Section Styles */
.subjects-section {
  background: #f7f9fc;
  max-width: 100%;
}

.subjects-grid, .subjects-grid-boost {
  display: flex;
  justify-content: center;
  gap: 20px;
  align-items: start;
  max-width: 1200px;
  margin: 0 auto;
  padding-top: 20px;
  flex-direction: row;
}

.subjects-list-container,
.subjects-list-container-boost {
  display: flex;
  justify-content: center;
  width: 100%;
  padding: 0 1rem;
}

/* Card Styles */
.included-services-card {
  background: #fff;
  padding: 25px 30px;
  width: 400px;
  border-radius: 15px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.05);
  border: 1px solid #eee;
}

.card-title {
  font-size: 20px;
  font-weight: bold;
  text-align: center;
  margin-bottom: 15px;
}

/* Service List */
.service-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.service-list li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 15px;
  padding: 10px 0;
  border-bottom: 1px solid #eee;
}

.prices {
  display: flex;
  gap: 8px;
  align-items: center;
}

.old-price {
  text-decoration: line-through;
  color: #999;
  font-size: 14px;
}

.tag {
  background: #fffaeb;
  color: #f97316;
  font-size: 13px;
  font-weight: 600;
  padding: 3px 10px;
  border-radius: 9999px;
  text-transform: uppercase;
}

/* Subjects List Styles */
.subjects-list {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
  list-style: none;
  max-width: 700px;
  width: 100%;
  padding: 0;
  margin: 0;
}

.subjects-list li {
  background: #fff;
  padding: 12px 15px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  display: flex;
  align-items: center;
  cursor: pointer;
  transition: all 0.3s ease;
  border: 1px solid #eee;
  text-align: left;
}

.subjects-list li a {
  text-decoration: none;
  color: #333;
  display: flex;
  align-items: center;
  width: 100%;
}

.subjects-list li:hover {
  color: #6a36ff;
  background-color: #e8dbff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  transform: translateY(-5px);
}

.subjects-list li:hover .circle {
  background: #4a25cc;
  transform: scale(1.1);
}

/* Circle Icon */
.circle {
  background-color: #6a36ff;
  color: #fff;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  font-weight: bold;
  font-size: 14px;
  margin-right: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  flex-shrink: 0;
}

/* Responsive Styles */
@media (max-width: 1024px) {
  .subjects-grid {
    flex-direction: column;
    align-items: center;
  }
  .included-services-card{
    width: 680px;
  }

  .subjects-list {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .subjects-list li {
    font-size: 13px;
    padding: 12px;
  }
  .included-services-card{
    width: 680px;
  }

  .circle {
    width: 26px;
    height: 26px;
    font-size: 12px;
  }
}

@media (max-width: 480px) {
  .card-title {
    font-size: 16px;
  }

  .included-services-card {
    width: 100%;
  }

  .service-list li {
    font-size: 12px;
    text-align: left;
  }

  .old-price {
    font-size: 12px;
  }

  .tag {
    font-size: 11px;
    padding: 2px 8px;
  }

  .circle {
    width: 24px;
    height: 24px;
    font-size: 11px;
  }

  .subjects-list {
    grid-template-columns: 1fr;
    gap: 10px;
  }

  .subjects-list li {
    font-size: 12px;
    padding: 10px;
  }
}

/* Animations */
@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>



</div>