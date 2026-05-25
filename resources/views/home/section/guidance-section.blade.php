<section class="page-section subjects-section">
    <div class="container">
        <h2 class="section-title">
            Transparent & Affordable Assignment Help Pricing for UK Students
        </h2>
        <p class="section-description">
           We believe in clear costs. You'll find essential academic support services included for free with every order. For additional specialised assignment help, extra support is available starting from just £12.01. Please note, pricing for these additional services may vary based on the complexity of guidance, required turnaround time, and the expert's qualification level.

        </p>

        <div class="subjects-grid">
            <!-- Left Column (Subjects List) -->
            <div class="subjects-image">
                <div class="included-services-card">
                    <h3 class="card-title">Always Included - Totally Free with Every Order!</h3>
                    <ul class="service-list">
                        @php
                            $services = [
                                ['name' => 'Title Page', 'price' => '£7.00'],
                                ['name' => 'Precise Formatting', 'price' => '£15.00'],
                                ['name' => 'Plagiarism Check', 'price' => '£30.00'],
                                ['name' => 'Proofreading & Editing', 'price' => '£12.00'],
                                ['name' => 'Unlimited Revisions', 'price' => '£12.00'],
                                ['name' => 'Reference Page', 'price' => '£8.00']
                            ];
                        @endphp
                        @foreach($services as $service)
                            <li>
                                <b>{{ $service['name'] }}</b>
                                <span class="prices">
                                    <span class="old-price">{{ $service['price'] }}</span>
                                    <span class="tag">Free</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Right Column (Free Services Card) -->
            <div class="subjects-image">
                <div class="included-services-card">
                    <h3 class="card-title">Extra Help (Additional Cost)</h3>
                    <ul class="service-list">
                        @php
                            $services = [
                                ['name' => 'Service', 'price' => 'Price'],
                                ['name' => 'Grade-A Targeted Guidance', 'price' => '£12.01'],
                                ['name' => 'Early Draft Review', 'price' => '+10% of base price'],
                                ['name' => 'Abstract Writing Help', 'price' => 'from £17.99'],
                                ['name' => 'Detailed Outline Support', 'price' => 'from £13.99'],
                                ['name' => 'Fast-Track or Priority Support', 'price' => 'from £14.99']
                            ];
                        @endphp
                        @foreach($services as $service)
                            <li>
                                <b>{{ $service['name'] }}</b>
                                <span class="prices">
                                    <span class="old-price"
                                        style="padding: 3px; text-decoration: none;"><b>{{ $service['price'] }}</b></span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</section>


<style>
    /* Common Section Styles */
    .subjects-section {
        background: #f7f9fc;
        max-width: 100%;
    }

    .subjects-grid,
    .subjects-grid-boost {
        display: flex;
        justify-content: center;
        gap: 20px;
        align-items: start;
        max-width: 1200px;
        margin: 0 auto;
        padding-top: 20px;
        flex-direction: row;
    }

    /* Card Styles */
    .included-services-card {
        background: #fff;
        padding: 25px 30px;
        width: 400px;
        border-radius: 15px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
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


    /* Responsive Styles */
    @media (max-width: 1024px) {
        .subjects-grid {
            flex-direction: column;
            align-items: center;
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