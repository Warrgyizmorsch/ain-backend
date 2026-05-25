<style>
    .scroll-container {
        overflow: hidden;
        white-space: nowrap;
    }

    .university-names {
        display: inline-block;
        animation: scroll-left 15s linear infinite;
    }

    .university-names span {
        display: inline-block;
        margin: 2px 10px;
    }

    .university-logo {
        height: 90px;
        object-fit: contain;
        vertical-align: middle;
        max-width: 180px;
        padding: 4px;
        background-color: white;
    }

    @keyframes scroll-left {
        0% {
            transform: translateX(0%);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    .trusted-card {
        background-color: rgb(88, 41, 199);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
    }

    .trusted-icon {
        font-size: 30px;
        margin-right: 15px;
    }

    .scroll-line {
        flex: 1;
        padding-left: 20px;
    }

    @media (max-width: 768px) {
        .row.flex-row {
            flex-direction: column;
        }

        .scroll-line {
            padding-left: 0;
            margin-top: 10px;
        }
    }
</style>
<div class="container my-4 py-0" >
    <section class="page-section" style="padding: 0px; !important;">
        
        <div class="row d-flex align-items-center">
            <!-- Left: Card -->
            <div class="col-md-5">
                <div class="trusted-card">
                    <div class="trusted-icon">🎓</div>
                    <div>
                        <h2 class="mb-1 font-weight-bold h6">Trusted by Thousands of Students from Top Universities for Academic Support and Expert Guidance</h2>

                    </div>
                </div>
            </div>

            <!-- Right: Scroller with Logos -->
            <div class="col-md-7 scroll-line">
                <div class="scroll-container">
                    <div class="university-names">
                        @for($i = 0; $i < 2; $i++)
                            <span><img src="/images/logo1.png" alt="Oxford" class="university-logo"></span>
                            <span><img src="/images/logo2.png" alt="Cambridge" class="university-logo"></span>
                            <span><img src="/images/logo3.jpeg" alt="UCL" class="university-logo"></span>
                            <span><img src="/images/logo44.png" alt="Edinburgh" class="university-logo"></span>
                            <span><img src="/images/logo5.png" alt="Manchester" class="university-logo"></span>
                            <span><img src="/images/logo6.jpeg" alt="LSE" class="university-logo"></span>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>