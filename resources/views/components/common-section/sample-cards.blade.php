<div>
    <section class="page-section sample-page">
        <div class="container">
            <h2 class="section-title">8000+ <span> Samples Reflect </span> the High Standard of Our Work </h2>
            {{-- <p  class="section-description">Discover a curated selection of expert-written samples that reflect UK academic standards. Trusted by students worldwide for quality and clarity</p> --}}

            <div class="card-container">
                <div class="card-background"></div>
                @foreach ($data["sample"] as $sample)
                    <div class="card-wrap">
                        <div class="card">
                            <img class="paperclip" src="/images/icons/paper-clip.png" alt="">
                            <h3>Paper Type: {{$sample->type->name}}</h3>
                            <div class="info"><span>Subject:</span>&nbsp;<span>{{$sample->categotyData->name}}</span></div>
                            <div class="info">
                                <span>No. of Pages:</span>&nbsp;
                                <span>
                                    @php
                                        $pageCount = ceil(str_word_count(strip_tags($sample->content)) / 250);
                                    @endphp
                                    {{ $pageCount }}
                                </span>
                            </div>
                            <div class="info"><span>Total Words:</span>&nbsp;<span>{{ str_word_count(strip_tags($sample->content)) }}</span></div>
                            <a href="/free-samples/{{$sample->categotyData->name}}/{{$sample->slug}}" class="view-button">View Sample</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="https://www.assignnmentinneed.com/free-samples">
                        <button class="place-order-btn">View More Samples</button>
                    </a>
        </div>
    </section>

    <style>

    .card-container {
        position: relative;
        display: flex;
        gap: 30px;
        padding: 20px;
        justify-content: center;
        align-items: center;
    }

    .card-background {
        position: absolute;
        width: 80%;
        height: 250px;
        background: #EAE2F8;
        border-radius: 20px;
        z-index: -1;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .card-wrap {
        position: relative;
        width: 100%;
        height: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card {
        width: 260px;
        background-color: #6B4FA7;
        border-radius: 15px;
        padding: 30px;
        position: relative;
        transform: rotate(0deg) translateY(-10px);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
    }

    .card::after {
        content: "";
        position: absolute;
        background: #DCCCFF;
        border-radius: 10px;
        border: 2px solid transparent;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        transform: rotate(-7deg);
        z-index: -1;
    }

    .paperclip {
        position: absolute;
        top: 10px;
        left: -30px;
        width: 30px;
        transform: translate(10px, -5px) rotate(70deg); /* Shift right/down and rotate */
    }

    .card h3 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        border-bottom: 2px solid #000;
        padding-bottom: 5px;
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
        background-color: #6C49B8;
        color: #fff;
        padding: 8px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        margin-top: 10px;
    }

    .view-button:hover {
        background-color: #553393;
        color: white;
    }
    @media (max-width: 1024px) {
        .card-container {
            flex-wrap: wrap; /* Allow cards to wrap */
            gap: 50px;
        }

        .card-wrap {
            flex: 0 0 45%; /* Two cards per row */
            height: auto;
        }

        .card {
            width: 100%;
            padding: 25px;
        }

        .background-card {
            width: 90%;
            height: 100%;
            top: 5px;
            left: 5px;
        }

        .card h3 {
            font-size: 15px;
        }

        .info {
            font-size: 13px;
        }
    }

    @media (max-width: 768px) {
        .card-container {
            flex-direction: column;
            align-items: center;
        }

        .card-wrap {
            flex: 0 0 100%; /* One card per row */
        }

        .card {
            width: 90%;
            padding: 40px;
        }

        .background-card {
            width: 95%;
            height: 100%;
            top: 4px;
            left: 4px;
        }

        .card h3 {
            font-size: 14px;
        }

        .info {
            font-size: 12px;
        }

        .view-button {
            padding: 6px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {


        .card {
            padding: 15px;
        }

        .background-card {
            width: 100%;
            height: 100%;
            top: 3px;
            left: 3px;
        }

        .card h3 {
            font-size: 13px;
        }

        .info {
            font-size: 11px;
        }

        .view-button {
            font-size: 12px;
            padding: 5px;
        }

        .place-order-btn {
            font-size: 14px;
            padding: 8px 15px;
        }
    }

    @media (max-width: 320px) {

        .card {
            padding: 12px;
        }

        .background-card {
            width: 100%;
            height: 100%;
            top: 2px;
            left: 2px;
        }

        .card h3 {
            font-size: 12px;
        }

        .info {
            font-size: 10px;
        }

        .view-button {
            font-size: 11px;
            padding: 4px;
        }
    }

    </style>


</div>
