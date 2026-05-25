@extends('frontend-layouts.app')

@section('content')


    <div class="head-container mt-6 px-4 py-6">
        <div class="header-content">
            <img src="assets/media/avatars/assignment_logo.png" alt="Assignment Logo" class="logo-img" />
            <h1 class="section-title">Free Assignment Samples</h1>
        </div>

        <a href="/free-samples" class="primary-button mt-4">Get 100% Original Assignments</a>

        <div class="icon-section mt-6">
            <div class="icon-item">
                <img src="/images2/certificate.png" alt="100% Original Document" />
                <p>100% Original</p>
            </div>
            <div class="icon-item">
                <img src="/images2/sketch-book.png" alt="AI Free Content" />
                <p>AI Free Content</p>
            </div>
            <div class="icon-item">
                <img src="/images2/personal-data.png" alt="Free CV" />
                <p>Free CV on Every Order</p>
            </div>
            <div class="icon-item">
                <img src="/images2/24-hours.png" alt="24/7 Availability" />
                <p>24×7 Available</p>
            </div>
            <div class="icon-item">
                <img src="/images2/role-model.png" alt="Experienced Writers" />
                <p>Experienced Writers</p>
            </div>
            <div class="icon-item">
                <img src="/images2/article.png" alt="Plagiarism Report" />
                <p>Plagiarism Report</p>
            </div>
        </div>
    </div>


    <section class="instructor-section">
        <div class="container">
            <div class="category-grid">
                @foreach ($categories as $letter => $group)
                    <div class="category-card">
                        <div class="category-icon">{{ $letter }}</div>

                        @foreach ($group as $category)
                            <div class="subject-list">
                                <div class="subject-container">
                                    <h4 class="subject-header">
                                        <a href="/free-samples/{{$category->name}}" class="subject-name"
                                            aria-label="View details about {{ $category->name }}">
                                            {{ $category->name }}
                                        </a>
                                        <span class="subject-count">{{ $category->sample_count }}</span>
                                    </h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <style>
        .instructor-section {
            padding: 3rem 1rem;
            background-color: #f9f9fb;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 3fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .category-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            padding: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .category-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }

        .category-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #5e2ced;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            transition: background-color 0.3s ease;
        }

        .category-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.25rem 0;
            border-bottom: 1px dashed #eee;
            transition: color 0.3s ease;
        }

        .category-title:last-child {
            border-bottom: none;
        }

        .category-title a {
            text-decoration: none;
            color: inherit;
            transition: color 0.3s ease;
        }

        .category-title a:hover {
            color: #5c67f2;
        }

        .subject-count {
            background: #e0e7ff;
            color: #4f46e5;
            padding: 0.2rem 0.6rem;
            border-radius: 8px;
            font-size: 0.8rem;
        }
    </style>
    <style>
        .head-container {
            background: linear-gradient(135deg, rgba(214, 216, 255, 0.9), rgba(227, 195, 255, 0.9));
            /* background: linear-gradient(white, white) padding-box,
                  linear-gradient(135deg, #6e40c9, #007fff) border-box;
      border-bottom: 4px solid transparent; */
            border-radius: 0 0 1.5rem 1.5rem;
            padding: 2rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.09);
            animation: fadeIn 1s ease-in-out;
            text-align: center;
        }

        .header-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }



        .logo-img {
            position: absolute;
            right: 20px;
            width: 80px;
            height: 80px;
            mix-blend-mode: multiply;
            animation: fadeInUp 1s ease-in-out;
        }

        .primary-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(to right, #6e40c9, #007fff);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 1.2s ease-in-out;
        }

        .primary-button:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .icon-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            animation: fadeInUp 1.4s ease-in-out;
        }

        .icon-item {
            background: linear-gradient(white, white) padding-box,
                linear-gradient(135deg, #6e40c9, #007fff) border-box;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.09);
            transition: transform 0.3s ease;
            text-align: center;
        }

        .icon-item:hover {
            transform: scale(1.05);
            box-shadow: 2px 8px 18px rgba(0, 0, 0, 0.15);
        }

        .icon-item img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            mix-blend-mode: multiply;
            margin-bottom: 0.5rem;
        }

        .icon-item p {
            font-size: 0.8rem;
            background: linear-gradient(135deg, #6e40c9, #007fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
            margin: 0;
        }

        .subject-name {
            font-size: 20px;
            color: #000;
            text-decoration: none;
            font-weight: 500;
        }


        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Tweaks */
        /* Small devices (phones < 480px) */
        @media (max-width: 479px) {
            .header-content img {
                display: none;
            }

            .logo-img {
                width: 50px;
                height: 50px;
                right: 10px;
            }

            .primary-button {
                font-size: 0.9rem;
                padding: 0.5rem 1rem;
            }

            .icon-item img {
                width: 50px;
                height: 50px;
            }

            .icon-item p {
                font-size: 0.7rem;
            }

            .subject-name {
                font-size: 16px;
            }

            .category-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .instructor-section {
                padding: 2rem 1rem;
            }
        }

        /* Medium phones (480px - 767px) */
        @media (min-width: 480px) and (max-width: 767px) {

            .logo-img {
                width: 60px;
                height: 60px;
            }



            .icon-item img {
                width: 35px;
                height: 35px;
            }

            .icon-item p {
                font-size: 0.75rem;
            }

            .subject-name {
                font-size: 18px;
            }

            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Tablets (768px - 1023px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            .logo-img {
                width: 70px;
                height: 70px;
            }


            .icon-item img {
                width: 40px;
                height: 40px;
            }

            .icon-item p {
                font-size: 0.8rem;
            }

            .category-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
    </style>
@endsection