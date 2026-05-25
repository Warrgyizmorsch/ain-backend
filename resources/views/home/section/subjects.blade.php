<style>
  :root {
    --brand-purple: #3F159A;
    --brand-purple-2: #6e40c9;
    --text-dark: #0f1b3d;
    --text-muted: #5b6275;
    --icon-soft: rgba(63, 21, 154, 0.10);
    --border: rgba(17, 24, 39, 0.10);
    --shadow: 0px 2px 6px 0px rgba(0, 0, 0, 0.32);

    /* ✅ sprite settings */
    --subject-sprite: url('/assets/media/layout/subject/subjecticons.webp');
    --icon-size: 56px;
    /* each icon tile size */
    --cols: 6;
    /* icons per row in sprite */
  }

  /* Section */
  .subjects-icon-section {
    padding: 50px 0;
    background: #fff;
  }

  .custom-icon-size {
    margin: 0;
    scale: 0.9;
  }

  .subjects-icon-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 18px;
  }

  /* Grid like screenshot */
  .subjects-icon-grid {
    margin-top: 22px;
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 28px 34px;
    /* row gap / col gap */
    align-items: start;
  }

  /* Link wrapper */
  .subject-link {
    text-decoration: none;
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 7px;
  }

  /* Icon circle (sprite based) */
  .subject-icon {
    width: var(--icon-size);
    height: var(--icon-size);
    border-radius: 999px;
    display: grid;
    place-items: center;

    background-color: var(--icon-soft);
    border: 1px solid rgba(63, 21, 154, 0.12);
    box-shadow: 0 10px 22px rgba(63, 21, 154, 0.08);
    transition: .22s ease;

    /* ✅ sprite */
    background-image: var(--subject-sprite);
    background-repeat: no-repeat;
    background-size: calc(6 * 95px) auto;
    /* background-position is set via classes below */
  }

  /* Label */
  .subject-label {
    font-size: 15px;
    font-weight: 500;
    color: #111827;
    margin: 0;
    text-align: center;
    line-height: 1.1;
  }

  /* Hover */
  .subject-link:hover .subject-icon {
    transform: translateY(-3px);
    box-shadow: 0 16px 30px rgba(63, 21, 154, 0.14);
    border-color: rgba(63, 21, 154, 0.22);
    background-color: rgba(63, 21, 154, 0.12);
  }

  .subject-link:hover .subject-label {
    color: var(--brand-purple);
  }

  .icon-engineering {
    background-position: -53px -85px;
  }

  .icon-business {
    background-position: -165px -85px;
  }

  .icon-management {
    background-position: -291px -85px;
  }

  .icon-accounting {
    background-position: -411px -85px;
  }

  .icon-law {
    background-position: -54px -169px;
  }

  .icon-healthcare {
    background-position: -165px -169px;
  }

  .icon-it {
    background-position: -290px -169px;
  }

  .icon-psychology {
    background-position: -412px -169px;
  }

  .icon-math {
    background-position: -54px -258px;
  }

  .icon-finance {
    background-position: -165px -258px;
  }

  .icon-marketing {
    background-position: -291px -258px;
  }

  .icon-science {
    background-position: -411px -258px;
  }

  /* Responsive */
  @media (max-width: 1100px) {
    .subjects-icon-grid {
      grid-template-columns: repeat(4, 1fr);
    }
  }

  @media (max-width: 768px) {

    /* keeps sprite math consistent */
    .subjects-icon-grid {
      grid-template-columns: repeat(3, 1fr);
      gap: 22px 18px;
    }

    .subject-label {
      font-size: 13px;
    }
  }

</style>

<section class="subjects-icon-section">
  <div class="subjects-icon-wrap">
    <h2 class="section-title">Best Assignment Help UK Covering <span>200+ Academic Subjects</span></h2>
    <p class="section-description">We Provide Assignment Assistance in the following subjects for Students</p>

    @php
      $subjects = [
        ['Engineering', 'engineering-assignment-writing-help', 'icon-engineering'],
        ['Business', 'business-assignment-writing-help', 'icon-business'],
        ['Management', 'management-assignment-writing-help', 'icon-management'],
        ['Accounting', 'accounting-assignment-writing-help', 'icon-accounting'],
        ['Law', 'law-assignment-writing-help', 'icon-law'],
        ['Nursing', 'nursing-assignment-writing-help', 'icon-healthcare'],
        ['Information Technology', 'it-assignment-help-uk', 'icon-it'],
        ['Psychology', 'psychology-assignment-help-uk', 'icon-psychology'],
        ['Mathematics', 'math-assignment-help', 'icon-math'],
        ['Finance', 'finance-assignment-writing-help', 'icon-finance'],
        ['Marketing', 'marketing-assignment-writing-help', 'icon-marketing'],
        ['Chemistry ', 'chemistry-assignment-writing-help', 'icon-science'],
      ];
    @endphp

    <div class="subjects-icon-grid">
      @foreach($subjects as [$label, $url, $iconClass])
        <a class="subject-link" href="{{ url($url) }}" aria-label="{{ $label }}">
          <p class="custom-icon-size"><span class="subject-icon {{ $iconClass }}" aria-hidden="true"></span></p>
          <p class="subject-label">{{ $label }}</p>
        </a>
      @endforeach
    </div>

  </div>
</section>