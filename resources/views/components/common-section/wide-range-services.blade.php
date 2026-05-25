@props(['heading', 'paragraph', 'services' => []])

<div class="page-section">

    <div class="section-header text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">{{ $heading }}</h2>
        <p class="paragraph-text">{{ $paragraph }}</p>
    </div>

    <div class="subject-grid">
        @foreach ($services as $service)
            <div class="subject-card">
                <div class="subject-name">
                    <i class="{{ $service['icon'] }} mr-2 text-purple-600"></i>
                    {{ $service['text'] }}
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-header h2 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #333;
        }

        .section-header p {
            font-size: 1rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        .subject-grid {
            display: grid;
            padding: 0px 50px 0px 50px;
            grid-template-columns: repeat(3, 1fr);
            /* 3 columns fixed */
            gap: 20px;
        }

        .subject-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
            border: 1px solid rgb(0, 60, 255);
        }

        .subject-card:hover {
            transform: translateY(-4px);
        }

        .subject-name {
            font-size: 1rem;
            font-weight: 600;
            color: #222;
            display: flex;
            align-items: center;
        }

        .paragraph-text {
            font-size: 14px !important;
            font-weight: 500;
        }

        .subject-name i {
            font-size: 1.2rem;
            margin-right: 8px;
            color: #7c3aed;
        }

        @media (max-width: 768px) {
            .subject-grid {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .subject-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

</div>