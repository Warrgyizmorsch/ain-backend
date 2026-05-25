@extends('frontend-layouts.app')

@section('content')

        <x-common-section.hero 
            title="Humanities Assignment Support to Improve Academic Writing"
            subtitle="Humanities students in the UK receive expert academic support for subjects such as history, philosophy, and cultural studies. Assignments are deeply researched, critically analysed, and written to reflect UK academic writing conventions and assessment criteria."
            >
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

    <x-common-section.services h1="How to Achieve A+ Grades with Humanities Assignment Help UK in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified Humanities Assignment Help UK Expert'" />

    <x-common-section.choose-us
        title="Our Guarantees That Humanities Assignment Help UK Stress-Free and Reliable"
        :features="[
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
        ]"
    />

    @include('home.section.numbers')

    <x-common-section.writing-services-dynamic
            title="Types of Humanities Assignment Help We Offer for UK Students"
            description="UK students trust our help because we keep things simple and student-friendly. We carefully read your assignment needs and write content that matches your subject and level of study. Every piece of work is created with care, keeping university rules in mind.
                        </br>We believe good humanities work should tell a clear story, explain ideas properly, and stay easy to read. That’s exactly what we deliver."
            
            title1="1. Essay Writing Help"
            desc1="We help UK students write clear and well-structured essays with strong ideas. Each essay is written in simple words and follows the question closely."
            
            title2="2. Assignment Editing and Improvement"
            desc2="If you already have a draft, we can improve it by fixing grammar, clarity, and flow, making your work sound more polished and confident."

            title3="3. Case Study Support"
            desc3="We assist students in explaining real-life examples in an easy and organised way, helping your case study look neat and meaningful."

            title4="4. Research Writing Help"
            desc4="We help you turn your reading and notes into a well-written assignment that explains ideas clearly without sounding confusing."

            title5="5. Coursework Assistance"
            desc5="Our support helps you complete coursework on time, making sure it follows university rules and stays easy to understand."

            title6="6. Proofreading Help"
            desc6="We carefully check your assignment for spelling, sentence flow, and small mistakes so your final work looks clean and ready to submit."


        />

    <x-common-section.promo-banner />


    @include('components.common-section.video-testimonial')

    <section class="page-section scrollables">
    <div class="scrollable-section">
        <div class="scrollable-container">
            
            <div class="column">
                <div class="content-box">
                    <h2>
                        Humanities Assignment Help UK Supporting University Students Across the UK
                    </h2>
                    <p class="content-description">
                        Studying humanities subjects can be deeply rewarding, but the workload often becomes overwhelming. From lengthy reading lists to critical writing and tight deadlines, students across the UK frequently struggle to balance academic expectations with part-time jobs and personal commitments. This is where <a href="/">assignment help UK</a> services become essential for students who want to submit well-structured and meaningful academic work without unnecessary stress.
                    </p>
                    <p class="content-description">
                        Our Humanities Assignment Help UK service is designed exclusively for UK students who need reliable academic support that aligns with university standards. Whether you are studying history, philosophy, literature, sociology, cultural studies, or political thought, we provide guidance that helps you meet marking criteria while maintaining your academic voice.
                    </p>
                </div>
                
                <div class="content-box">
                    <h2>
                        Understanding Humanities Studies in UK Universities
                    </h2>
                    <p class="content-description">
                        Humanities courses in the UK focus on critical thinking, interpretation, argument development, and evidence-based writing. Unlike technical subjects, humanities assignments demand depth, clarity of thought, and a strong understanding of social and cultural contexts. Universities expect students to demonstrate independent thinking, original arguments, and proper referencing in every submission.
                    </p>
                    <p class="content-description">
                        Many students find these expectations challenging, especially during exam seasons or dissertation periods. With professional humanities assignment help, UK students receive structured academic assistance that supports learning while improving confidence and performance.
                    </p>
                </div>
                
                <div class="content-box">
                    <h2>
                        Why UK Students Choose Humanities Assignment Help
                    </h2>
                    <p class="content-description">
                        UK university life is demanding, particularly for humanities students who often juggle multiple essays, case studies, and research projects at the same time. Seeking assignment writing help is no longer viewed as a weakness but as a smart academic decision.
                    </p>
                    <p class="content-description">
                        Students choose our support because it helps them:
                    </p>
                    <ul class="custom-bullet-list">
                        <li class="list-group-item">Manage overlapping deadlines efficiently</li>
                        <li class="list-group-item">Improve clarity and coherence in academic writing</li>
                        <li class="list-group-item">Understand complex theories and perspectives</li>
                        <li class="list-group-item">Meet UK university marking standards</li>
                        <li class="list-group-item">Reduce academic stress while maintaining quality</li>
                    </ul>
                    <p class="content-description">
                        Our approach focuses on helping students submit work that reflects strong understanding and proper academic structure.
                    </p>
                </div>
                
                <div class="content-box">
                    <h2>
                        Humanities Subjects We Support Across the UK
                    </h2>
                    <p class="content-description">
                        Humanities is a broad academic field, and our services cover a wide range of disciplines taught in UK institutions. These include:
                    </p>
                    <ul class="custom-bullet-list">
                        <li class="list-group-item">History and historical analysis</li>
                        <li class="list-group-item">English literature and comparative studies</li>
                        <li class="list-group-item">Philosophy and ethics</li>
                        <li class="list-group-item">Sociology and social theory</li>
                        <li class="list-group-item">Political science and international relations</li>
                        <li class="list-group-item">Cultural and media studies</li>
                        <li class="list-group-item">Anthropology and human geography</li>
                    </ul>
                    <p class="content-description">
                        Each subject requires a different writing style and analytical approach. Our humanities specialists understand these differences and tailor academic support accordingly.
                    </p>
                </div>
                
                <div class="content-box">
                    <h2>
                        City-Based Humanities Assignment Help in the UK
                    </h2>
                    <p class="content-description">
                        We proudly provide Humanities Assignment Help UK services to students studying in major academic cities. Our support is designed to meet the expectations of universities across the country.
                    </p>
                    <p class="content-description">
                        Students regularly seek help from:
                    </p>
                    <ul class="custom-bullet-list">
                        <li class="list-group-item">Humanities Assignment Help London for Russell Group and metropolitan universities</li>
                        <li class="list-group-item">Humanities Assignment Help Manchester for research-intensive institutions</li>
                        <li class="list-group-item">Humanities Assignment Help Birmingham for diverse humanities programmes</li>
                        <li class="list-group-item">Humanities Assignment Help Leeds for literature and social science students</li>
                        <li class="list-group-item">Humanities Assignment Help Nottingham for interdisciplinary humanities courses</li>
                        <li class="list-group-item">Humanities Assignment Help Bristol for cultural and historical studies</li>
                    </ul>
                    <p class="content-description">
                        No matter where you study in the UK, our academic support aligns with your university’s guidelines and assessment methods.
                    </p>
                </div>
                
                
            </div>
            
            <div class="column">                                
                <div class="content-box">
                    <h2>
                        How Our Humanities Assignment Help Supports Academic Growth
                    </h2>
                    <p class="content-description">
                        Our goal is not just submission support but academic improvement. Humanities assignments often require argument development, source evaluation, and critical engagement with texts. We help students structure their ideas clearly and present them in a logical, academic manner.
                    </p>
                    <p class="content-description">
                        By using professional <a href="/essay-writing-help-services">essay help UK</a> and academic assignment help, students learn how to:
                    </p>
                    <ul class="custom-bullet-list">
                        <li class="list-group-item">Frame strong introductions and conclusions</li>
                        <li class="list-group-item">Develop well-supported arguments</li>
                        <li class="list-group-item">Use evidence effectively</li>
                        <li class="list-group-item">Maintain academic tone and clarity</li>
                        <li class="list-group-item">Follow proper referencing styles used in UK universities</li>
                    </ul>
                    <p class="content-description">
                        This guidance proves especially valuable for first-year students and international students adapting to UK academic standards.
                    </p>
                </div>
                
                <div class="content-box">
                    <h2>
                        Plagiarism-Free and Original Humanities Work
                    </h2>
                    <p class="content-description">
                        UK universities place strict emphasis on originality and academic honesty. Our Humanities Assignment Help UK service ensures that every piece of work is written from scratch and tailored to individual requirements. Each assignment is carefully checked to ensure originality, relevance, and clarity.
                    </p>
                    <p class="content-description">
                        Students can confidently submit their work knowing it reflects independent thinking and meets university integrity policies.
                    </p>
                </div>
                
                <div class="content-box">
                    <h2>
                        Support for All Academic Levels
                    </h2>
                    <p class="content-description">
                        Humanities students at different academic stages face unique challenges. Our services support:
                    </p>
                    <ul class="custom-bullet-list">
                        <li class="list-group-item">Undergraduate students managing core modules</li>
                        <li class="list-group-item">Postgraduate students handling advanced research tasks</li>
                        <li class="list-group-item">Master’s students working on critical essays</li>
                        <li class="list-group-item">Final-year students preparing extended research projects</li>
                    </ul>
                    <p class="content-description">
                        For larger projects, students often use <a href="/dissertation-writing-help-services">dissertation help UK</a> alongside regular assignment support to manage research workload effectively.
                    </p>
                </div>
                
                <div class="content-box">
                    <h2>
                        Timely Humanities Assignment Help for UK Deadlines
                    </h2>
                    <p class="content-description">
                        UK universities operate on strict academic calendars. Missing deadlines can significantly impact final grades. Our assignment support is designed to help students meet deadlines without compromising quality.
                    </p>
                    <p class="content-description">
                        By planning work efficiently and providing timely guidance, we ensure students submit their humanities assignments on time, even during peak academic periods.
                    </p>
                </div>
                
                <div class="content-box">
                    <h2>
                        Why Humanities Students Trust Our UK-Based Support
                    </h2>
                    <p class="content-description">
                        Our service is built around the needs of UK students. We understand marking rubrics, assessment feedback styles, and university expectations. This localised understanding allows us to provide relevant and practical academic support.
                    </p>
                    <p class="content-description">
                        Students benefit from:
                    </p>
                    <ul class="custom-bullet-list">
                        <li class="list-group-item">Clear communication and guidance</li>
                        <li class="list-group-item">Writing aligned with UK academic standards</li>
                        <li class="list-group-item">Support tailored to humanities disciplines</li>
                        <li class="list-group-item">Flexible assistance for urgent and planned submissions</li>
                    </ul>
                    <p class="content-description">
                        This makes our Humanities Assignment Help UK service a dependable choice for students across the country.
                    </p>
                </div>
                
                <div class="content-box">
                    <h2>Conclusion</h2>
                    <p class="content-description">Humanities studies demand critical thinking, strong writing skills, and deep subject understanding. For many UK students, managing these expectations alongside busy schedules can be challenging. Humanities Assignment Help UK provides dependable academic support that helps students submit well-structured, original, and meaningful assignments on time.</p>
                    <p class="content-description">With subject-focused guidance, city-specific support across the UK, and alignment with university standards, our services help students improve academic performance while reducing stress. Whether you need help with an essay, <a href="/research-paper-writing-services">research paper writing help</a>, or extended humanities project, professional academic assistance can make a real difference in your university journey.</p>
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
                <h2 class="section-title">Frequently Asked Questions (FAQs)</h2>

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