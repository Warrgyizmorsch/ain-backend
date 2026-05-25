@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="Data Mining Assignment Assistance for Academic Success"
        subtitle="Data mining assignments focus on identifying patterns and analysing large datasets. We assist students in understanding methods, interpreting results correctly, and presenting findings clearly with structured explanations and meaningful insights.">
        <div class="content-list-item">
            <ul class="content-list">
                <li>AI Free Content </li>
                <li>Ph.D. Writes </li>
                <li>24/7 Live Support</li>
            </ul>
            <ul class="content-list">
                <li>Free Revisions</li>
                <li>Plagiarism Free</li>
                <li>Refund Guarantee</li>
            </ul>
        </div>

        <div class="Trusted-paragraph">
            <p>Trusted by <b>5,126+&nbsp;students </b>- Rated 4.8/5 Across All Platforms</p>
        </div>
    </x-common-section.hero>

    <x-common-section.services h1="How to Achieve A+ Grades with Data Mining Assignment Help UK in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified UK Data Mining Assignment Help  Expert'" />

    <x-common-section.choose-us
        title="Our Guarantees That Make Uk Data Mining Assignment Help  Stress-Free and Reliable" :features="[
            [
                'icon' => 'fas fa-check',
                'title' => '100% Plagiarism-Free',
                'link' => '/GuaranteedPolicy',
                'description' => 'Every document is written from scratch and scanned before delivery. We guarantee 100% original, unique content that meets all academic standards. A free plagiarism report is provided to every student.'
            ],
            [
                'icon' => 'fa fa-truck',
                'title' => 'On-Time Delivery',
                'link' => '/contact-us',
                'description' => 'We guarantee your custom assignment will be delivered on or before your specified deadline, even for urgent orders. Your academic success is our priority.'
            ],
            [
                'icon' => 'fa fa-refresh',
                'title' => 'Free Unlimited Revisions',
                'link' => '/GuaranteedPolicy',
                'description' => 'If the final document does not fully meet your initial instructions, we offer free revisions until you are completely satisfied.'
            ],
            [
                'icon' => 'fa fa-user',
                'title' => 'Confidentiality & Privacy',
                'link' => '/PrivacyPolicy',
                'description' => 'Your personal details and order information are 100% confidential. We use secure systems and never share your data with any third party.'
            ],
            [
                'icon' => 'fa fa-money',
                'title' => 'Money-Back Satisfaction',
                'link' => '#',
                'description' => 'If the delivered paper fails to meet the core specifications you provided in your instructions, you are covered by our comprehensive money-back guarantee.'
            ],
            [
                'icon' => 'fas fa-user-shield',
                'title' => '24/7 Customer Support',
                'link' => '/contact-us',
                'description' => 'You are never alone. Our dedicated support team is available 24 hours a day, 7 days a week via live chat, email, or phone. Get instant answers to your questions, track your order progress, or get immediate help from anywhere in the world.'
            ],
            [
                'icon' => 'fas fa-edit',
                'title' => 'Verified Expert Writers',
                'link' => '/writers',
                'description' => 'Only the best work on your paper. We guarantee that your project will be handled by a verified academic expert with a relevant degree (Master\'s or PhD). Our writers are rigorously tested for their subject matter expertise and writing proficiency.'
            ],
            [
                'icon' => 'fas fa-shield-alt',
                'title' => 'Depth Research & Referencing',
                'link' => '/PrivacyPolicy',
                'description' => 'Receive credible, well-supported work. We guarantee that every assignment includes in-depth, scholarly research using current and authoritative sources. All documents are meticulously formatted with accurate referencing in any style (APA, MLA, Chicago, etc.) required.'
            ]
        ]" />

    @include('home.section.numbers')

    <x-common-section.writing-services-dynamic title="Explore Other Types of Data Mining Assignment Help UK"
        description="Data Mining assignments focus on extracting knowledge from datasets using algorithms and analysis. Each task requires structured explanation, technical reasoning, and professional presentation."
        title1="Data Mining Algorithm Assignment Help UK"
        desc1="Assignments include clustering, classification, and association rules. We guide students to present algorithms clearly and explain methods logically.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Algorithm Design<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Method Explanation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Result Interpretation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Logical Flow"
        title2="Data Preprocessing Assignment Help UK"
        desc2="Preprocessing tasks involve cleaning, transforming, and preparing data. We help students present steps clearly and maintain technical accuracy.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Data Cleaning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Transformation Techniques<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Accuracy Validation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Stepwise Explanation"
        title3="Data Analysis Assignment Help UK"
        desc3="Analysis tasks involve extracting patterns, trends, and insights. We assist students in presenting findings clearly and explaining results academically.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Pattern Detection<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Trend Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Result Interpretation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Logical Reporting"
        title4="Data Mining Project Assignment Help UK"
        desc4="Project assignments require integrating datasets, applying techniques, and presenting results. We guide students in organising work logically and explaining methodology clearly.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Project Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Technique Application<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Result Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Structured Documentation"
        title5="Data Visualisation Assignment Help UK"
        desc5="Visualisation tasks involve graphs, charts, and dashboards. We help students present insights with clarity and professional explanation.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Graph Design<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Chart Representation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Dashboard Creation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Academic Clarity"
        title6="Data Mining Assignment Review & Proofreading UK"
        desc6="Final review ensures methods, results, and explanations are accurate and structured. We assist students in refining content for clarity and submission readiness.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Method Verification<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Result Checking<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Logical Flow<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Submission Quality" />
    <x-common-section.promo-banner />


    @include('components.common-section.video-testimonial')

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">

                <div class="column">
                    <div class="content-box">
                        <h2>Data Mining Assignment Help UK - Academic Support for Data-Driven Learning
                        </h2>
                        <p class="content-description">
                            Data Mining is an important subject in UK universities for students studying computer science, information systems, data science, and business analytics. It focuses on discovering meaningful patterns, trends, and relationships from large datasets. Through academic assignments, students are expected to understand how raw data can be transformed into valuable information for decision-making.
                        </p>
                        <p class="content-description">
                            Data mining <a href="https://www.assignnmentinneed.com/">assignment help uk</a> are conceptually demanding because they require a combination of statistical understanding, analytical thinking, and clear academic explanation. Students must not only apply techniques but also justify their approach and interpret outcomes in a structured and logical manner.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Role of Data Mining in UK Universities</h2>
                        <p class="content-description">
                            Data mining plays a significant role in modern education as it supports evidence-based analysis and research-oriented learning. Universities use data mining coursework to train students in analytical reasoning and data interpretation.
                        </p>
                        <p class="content-description">
                            Assignments are designed to test how well students can:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Understand data behaviour
                            </li>
                            <li class="list-group-item">Apply suitable mining techniques
                            </li>
                            <li class="list-group-item">Analyse patterns and trends
                            </li>
                            <li class="list-group-item">Explain findings academically
                            </li>
                        </ul>
                        <p class="content-description">
                            Rather than focusing only on results, UK institutions emphasise reasoning, methodology, and clarity of explanation.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Nature of Data Mining Assignments</h2>
                        <p class="content-description">
                            Data mining assignments differ based on academic level and subject focus. Introductory coursework may involve understanding basic concepts, while advanced assignments require applying techniques to real datasets and evaluating outcomes.
                        </p>
                        <p class="content-description">
                            Most data mining tasks involve:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Analysing datasets
                            </li>
                            <li class="list-group-item">Selecting appropriate methods
                            </li>
                            <li class="list-group-item">Explaining processes and results
                            </li>
                            <li class="list-group-item">Drawing meaningful conclusions
                            </li>
                        </ul>
                        <p class="content-description">
                            Students are often required to combine technical explanation with written academic discussion, making these assignments more complex than theory-based subjects.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Key Concepts Covered in Data Mining Coursework</h2>
                        <p class="content-description">
                            Data mining coursework focuses on helping students understand how data can be explored and analysed effectively. Assignments usually require a strong grasp of fundamental concepts before moving to advanced applications.
                        </p>
                        <p class="content-description">
                            Common academic areas include:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Data preparation and cleaning
                            </li>
                            <li class="list-group-item">Pattern recognition
                            </li>
                            <li class="list-group-item">Classification and clustering
                            </li>
                            <li class="list-group-item">Association analysis
                            </li>
                        </ul>
                        <p class="content-description">
                            Students must demonstrate not only what technique was used but also why it was suitable for the given problem.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Challenges Faced by Students in Data Mining Assignments</h2>
                        <p class="content-description">
                            Many students find data mining difficult due to its analytical nature. One common challenge is understanding large datasets and identifying relevant variables. Without proper interpretation, results can be misleading.
                        </p>
                        <p class="content-description">
                            Another difficulty is explaining analytical processes in clear academic language. Students may obtain correct outputs but struggle to describe how patterns were identified and what they represent.
                        </p>
                        <h3>Balancing Analysis and Explanation
                        </h3>
                        <p class="content-description">
                            Data mining assignments require equal focus on analysis and explanation. Weak interpretation often affects grading, even when technical steps are correct.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>Importance of Methodology in Data Mining Work</h2>
                        <p class="content-description">
                            UK universities assess data mining assignments heavily on methodology. Students must clearly explain how data was processed, which technique was applied, and how conclusions were drawn.
                        </p>
                        <p class="content-description">
                            A well-presented assignment typically shows:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Clear problem definition
                            </li>
                            <li class="list-group-item">Logical selection of methods
                            </li>
                            <li class="list-group-item">Step-by-step analysis
                            </li>
                            <li class="list-group-item">Justified conclusions
                            </li>
                        </ul>
                        <p class="content-description">
                            Poorly explained methodology can reduce marks even if results appear accurate.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Writing in Data Mining Assignments</h2>
                        <p class="content-description">
                            Data mining coursework includes written components such as reports, analytical explanations, and result interpretation. Students are expected to maintain formal academic tone and structured presentation.
                        </p>
                        <p class="content-description">
                            Clear academic writing helps demonstrate understanding and improves readability for examiners. Overly brief or unstructured answers usually fail to meet assessment standards.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Practical Application in Data Mining Coursework</h2>
                        <p class="content-description">
                            Many data mining assignments are based on practical or real-world datasets. Students may be asked to analyse business data, customer behaviour, or system-generated information.
                        </p>
                        <p class="content-description">
                            These tasks require students to:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Interpret data patterns
                            </li>
                            <li class="list-group-item">Explain trends logically
                            </li>
                            <li class="list-group-item">Relate findings to theoretical concepts
                            </li>
                        </ul>
                        <p class="content-description">
                            The ability to connect analysis with real-world context is an important assessment factor.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Structure and Logical Flow in Data Mining Assignments</h2>
                        <p class="content-description">
                            A clear structure improves the quality of data mining assignments. Universities expect students to organise their work in a logical sequence, ensuring smooth flow between analysis and explanation.
                        </p>
                        <p class="content-description">
                            Well-structured assignments make it easier to follow reasoning and demonstrate strong academic skills.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Time Management and Data Mining Coursework</h2>
                        <p class="content-description">
                            Data mining assignments often take longer than expected. Data preparation, analysis, interpretation, and documentation require careful planning. Many students face difficulties when managing time across multiple technical subjects.
                        </p>
                        <p class="content-description">
                            A systematic approach helps reduce errors and improves overall quality.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Accuracy, Originality, and Academic Integrity</h2>
                        <p class="content-description">
                            Accuracy is critical in data mining assignments because incorrect interpretation can lead to wrong conclusions. Universities also place strong emphasis on originality and independent understanding.
                        </p>
                        <p class="content-description">
                            Students must present their own analysis and reasoning while following academic integrity guidelines. Proper academic support helps maintain correctness and originality.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Learning Outcomes of Data Mining Assignments</h2>
                        <p class="content-description">
                            Through data mining coursework, students develop valuable skills such as analytical thinking, data interpretation, and academic communication. These skills are useful not only for assessments but also for future professional roles.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Conclusion</h2>
                        <p class="content-description">
                            Data mining assignments require a careful balance of analytical skill, conceptual understanding, and academic explanation. UK universities assess students on how effectively they analyse data and present their findings in a structured manner.
                        </p>
                        <p class="content-description">
                            With Data Mining Assignment Help UK, students can improve clarity, strengthen analytical reasoning, and meet academic expectations confidently. Structured guidance helps students approach data mining coursework with confidence and consistency.
                        </p>
                    </div>

                </div>

            </div>
        </div>
        </div>
    </section>

    @if(!empty($data['faqs']))
        <div>
            @php
                $total = count($data['faqs']);
                $half = ceil($total / 2);
                $leftFaqs = array_slice($data['faqs'], 0, $half);
                $rightFaqs = array_slice($data['faqs'], $half);
            @endphp

            <section class="faq-section">
                <h2 class="section-title">Frequently Asked Questions For Data Mining Assignment Help UK</h2>

                <div class="faq-container">
                    <div class="faq-column">
                        @foreach ($leftFaqs as $item)
                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>{!! $item['question'] ?? '' !!}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>{!! $item['answer'] !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="faq-column">
                        @foreach ($rightFaqs as $item)
                            <div class="faq-item">
                                <button class="faq-question">
                                    <span>{!! $item['question'] ?? '' !!}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>{{ $item['answer'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>
                <div class="title-box text-center mb-5">
                    <a href="/faqs" class="place-order-btn">View More FAQs</a>
                </div>
            </section>


            <style>
                .faq-section {
                    text-align: center;
                    padding: 50px 20px;
                }

                .faq-container {
                    display: flex;
                    justify-content: center;
                    gap: 20px;
                    max-width: 1200px;
                    margin: 1rem auto;
                }

                .faq-column {
                    width: 45%;
                }

                .faq-item {
                    margin-bottom: 10px;
                    border: 2px solid #5e2ced;
                    border-radius: 5px;
                    overflow: hidden;
                }

                .faq-question {
                    width: 100%;
                    text-align: left;
                    background: white;
                    padding: 15px;
                    cursor: pointer;
                    font-size: 16px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border: none;
                    font-weight: 500;
                }

                .faq-question i {
                    transition: transform 0.3s ease-in-out;
                    color: #5e2ced;
                }

                .faq-answer {
                    max-height: 0;
                    overflow: hidden;
                    transition: max-height 0.4s ease-out, padding 0.4s ease-out;
                    padding: 0 15px;
                    background: #f9f9f9;
                    text-align: justify;
                }

                .faq-answer p {
                    font-size: 14px;
                    color: #555;
                }

                .faq-item.active .faq-answer {
                    max-height: 150px;
                    padding: 10px 15px;
                }

                .faq-item.active .faq-question i {
                    transform: rotate(180deg);
                }

                @media (max-width: 768px) {
                    .faq-container {
                        flex-direction: column;
                        align-items: center;
                        width: 100%;
                        gap: 0px;
                    }

                    .faq-column {
                        width: 100%;
                    }

                    .section-title {
                        margin-left: 0px;
                    }
                }
            </style>

            <script>
                document.querySelectorAll(".faq-question").forEach((button) => {
                    button.addEventListener("click", () => {
                        const faqItem = button.parentElement;
                        const faqAnswer = faqItem.querySelector(".faq-answer");

                        // Close other open FAQs
                        document.querySelectorAll(".faq-item").forEach((item) => {
                            if (item !== faqItem) {
                                item.classList.remove("active");
                                item.querySelector(".faq-answer").style.maxHeight = null;
                            }
                        });

                        // Toggle current FAQ
                        faqItem.classList.toggle("active");
                        if (faqItem.classList.contains("active")) {
                            faqAnswer.style.maxHeight = faqAnswer.scrollHeight + "px";
                        } else {
                            faqAnswer.style.maxHeight = null;
                        }
                    });
                });
            </script>

        </div>

    @endif



@endsection