@extends('frontend-layouts.app')
@section('content')

  <!-- Hero Section  -->
  @include('home.section.hero')

  <!-- Rating Section  -->
  @include('components.review-section')

  <!-- Slider Section  -->
  @include('home.section.slider')

  <!-- Process Section  -->
  @include('home.section.process')
    <!-- Stats Section  -->
  @include('home.section.stats')

  <!-- Our Writers  -->
  @include('home.section.expert-section')

  <!-- Testimonial Section  -->
  @include('home.section.testimonial-section')

  <!-- University Marquee -->
  @include('home.section.universityslider')



  <!-- Services Section  -->
  @include('home.section.services')

  <!-- Samples Section  -->
  @include('home.section.samples-demo')

  <!-- Subjects Section  -->
  @include('home.section.subjects')

  <!-- Blog Section  -->
  @include('home.section.blog')

  <!-- FAQ Section  -->
  @include('home.section.new-faq')

  <!-- Read More Section  -->
  @include('home.section.read-more')

  <!-- JS -->
  <script>
    function loadYoutube(el) {
    const videoId = el.getAttribute('data-video-id');
    const iframe = document.createElement('iframe');
    iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
    iframe.setAttribute('frameborder', '0');
    iframe.setAttribute('allowfullscreen', '');
    iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
    iframe.style.width = '100%';
    iframe.style.height = '100%';
    el.innerHTML = '';
    el.appendChild(iframe);
    }
  </script>
@endsection