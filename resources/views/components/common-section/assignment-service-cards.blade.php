<section>
    @props(['cardsheading', 'cardsparagraph' => '', 'carditems' => []])

    <div class="page-section">
        <div class="container">
            @if ($cardsheading)
                <h2 class="section-title">{{ $cardsheading }}</h2>
                <p class="section-description">{{ $cardsparagraph }}</p>
            @endif

            <div class="card-containers">
                @foreach ($carditems as $card)
                    <div class="card">
                        <h3>{{ $card['heading'] }}</h3>
                        <p>{{ $card['paragraph'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .card-containers {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        padding: 20px 0;
    }

    .card {
        background: linear-gradient(145deg, #ffffff, #f0f4ff);
        border: none;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1), 0 4px 8px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, #4ea4ec, #7bc3ff);
        transition: height 0.3s ease;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15), 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .card:hover::before {
        height: 12px;
    }

    .card h3 {
        color: #1a3c5e;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        text-align: center;
        letter-spacing: 0.5px;
    }

    .card p {
        color: #2d3748;
        font-size: 15px;
        line-height: 1.7;
        text-align: justify;
        opacity: 0.9;
    }

    @media (max-width: 768px) {
        .card-containers {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 15px 0;
        }

        .card {
            padding: 25px;
        }
    }

    @media (max-width: 480px) {
        .card {
            padding: 20px;
        }

        .card h3 {
            font-size: 18px;
        }

        .card p {
            font-size: 14px;
        }
    }
</style>