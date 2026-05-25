@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="Custom Assignment Help Designed to Meet Your Academic Needs"
        subtitle="Get expert guidance with Custom Assignment Help, designed to meet your unique academic needs. Our tailored solutions ensure accurate, high-quality, and timely assignments, helping you achieve better grades and enhanced understanding.">
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

    <x-common-section.services h1="How to Achieve A+ Grades with Custom Assignment Help UK in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified Custom Assignment Help UK Expert'" />

    <x-common-section.choose-us title="Our Guarantees That Make Uk Work Assignment Help Stress-Free and Reliable"
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
        ]" />

    @include('home.section.numbers')

    <x-common-section.writing-services-dynamic title="Types of Custom Assignment Help UK"
        description="Custom assignments require a personalised approach to meet individual academic guidelines and learning goals. Our Custom Assignment Help UK delivers tailored, original, and deadline-focused solutions across all subjects and academic levels."
        title1="Custom Essay Writing Help UK" desc1="We create fully customised essays based on your topic, marking criteria, and academic level. Each essay is structured clearly to reflect strong research and critical thinking.<br>
                                                ● Topic Research<br>
                                                ● Clear Structure<br>
                                                ● Strong Arguments<br>
                                                ● Original Content"
        title2="Research Assignment Help UK" desc2="Our experts provide in-depth, customised research assignments supported by credible academic sources. Every paper is tailored to your research objectives and formatting requirements.<br>
                                                ● Literature Review<br>
                                                ● Data Analysis<br>
                                                ● Evidence-Based Content<br>
                                                ● Proper Referencing"
        title3="Case Study Help UK" desc3="Custom case study solutions focus on real-world problem analysis and practical application of theories. Each case study is written to meet your specific subject and assessment criteria.<br>
                                                ● Problem Analysis<br>
                                                ● Theory Application<br>
                                                ● Real Examples<br>
                                                ● Clear Conclusions"
        title4="Dissertation & Project Help UK" desc4="We offer personalised support for dissertations and academic projects, ensuring logical structure and academic accuracy throughout. Assistance is provided at every stage of the process.<br>
                                                ● Topic Selection<br>
                                                ● Methodology Support<br>
                                                ● Chapter Writing<br>
                                                ● Final Review<br>"
        title5="Report Writing Help UK" desc5="Custom reports are prepared following your required format and academic standards. We ensure clarity, data accuracy, and professional presentation.<br>
                                                ● Structured Format<br>
                                                ● Data Presentation<br>
                                                ● Analytical Writing<br>
                                                ● Academic Compliance"
        title6="Programming & Technical Assignment Help UK" desc6="Our customised coding solutions help students complete technical assignments with accuracy and efficiency. Support is available for multiple programming languages and tools.<br>
                                                ● Code Development<br>
                                                ● Debugging<br>
                                                ● Logical Solutions<br>
                                                ● Error-Free Output"
        title7="Coursework Help UK" desc7="We provide personalised assistance for regular coursework to ensure consistency and accuracy. Each task is completed according to your instructions and deadlines.<br>
                                                ● Task-Based Support<br>
                                                ● Concept Clarity<br>
                                                ● Timely Submission<br>
                                                ● Curriculum Aligned"
        title8="Editing & Proofreading Help" desc8="Our editing and proofreading services enhance the quality of your existing assignments. We focus on language accuracy, structure, and academic standards.<br>
                                                ● Grammar Check<br>
                                                ● Content Flow<br>
                                                ● Formatting Review<br>
                                                ● Plagiarism Check" />

    <x-common-section.promo-banner />


    @include('components.common-section.video-testimonial')

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">

                <div class="column">
                    <div class="content-box">
                        <h2>Custom Assignment Help – Tailored Academic Support for Every Student</h2>
                        <p class="content-description">
                            College and university students often face the immense challenge of balancing academics, part-time jobs, internships, and personal life. One of the biggest hurdles is handling the growing number of assignments that demand in-depth research, critical thinking, and precise writing.
                        </p>
                        <p class="content-description">
                            Managing all these tasks without compromising on quality can be overwhelming, which is why many students now look for <b>help with assignment online</b> to stay on track.
                        </p>
                        <p class="content-description">
                            This is where <b>Custom Assignment Help</b> becomes a valuable solution. By providing personalised academic assistance, this service allows students to complete assignments efficiently while maintaining high standards. Whether it’s a complex research paper, a detailed report, or an analytical essay, custom assignment help ensures students submit original, well-structured, and high-quality work that meets academic expectations.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>The Importance of Custom Assignment Help</h2>
                        <p class="content-description">
                            Assignments are not merely academic tasks; they are crucial for shaping a student’s knowledge, analytical skills, and academic reputation. Here’s why custom assignment help is increasingly essential:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Maintaining Academic Standards: </b>Universities expect students to produce assignments that reflect research depth, analytical thinking, and clarity of expression.</li>
                            <li class="list-group-item"><b>Meeting Tight Deadlines: </b>bWith multiple subjects and overlapping deadlines, students often find it difficult to complete assignments on time without support.</li>
                            <li class="list-group-item"><b>Enhancing Learning: </b>Professional guidance ensures that assignments are not just completed but also educational, helping students understand concepts in depth.</li>
                            <li class="list-group-item"><b>Reducing Academic Stress: </b>Tight schedules and heavy workloads can cause anxiety and burnout. Expert support, including cheap assignment writing help, allows students to manage studies without constant pressure.</li>
                            <li class="list-group-item"><b>Gaining a Competitive Edge: </b>High-quality assignments can significantly impact grades, academic reputation, and future career opportunities.</li>
                        </ul>
                        <p class="content-description">
                            Custom assignment help is more than just assistance; it is a strategic approach to long-term academic success and skill development.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Benefits of Choosing Custom Assignment Help</h2>
                        <p class="content-description">
                            Opting for custom assignment help offers multiple advantages that go beyond simply submitting an assignment:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Tailored Solutions</b><br>Every assignment is customised according to the student’s academic level, subject requirements, and specific guidelines.
                            </li>
                            <li class="list-group-item"><b>Improved Grades</b><br>Assignments prepared by experts follow academic standards meticulously, helping students achieve better grades and academic recognition.
                            </li>
                            <li class="list-group-item"><b>Enhanced Learning Experience</b><br>Custom assignments are structured to explain concepts clearly, enabling students to grasp subject matter more effectively.
                            </li>
                            <li class="list-group-item"><b>Reduced Academic Pressure</b><br>With professional support, students can manage deadlines efficiently and focus on learning rather than stress.
                            </li>
                            <li class="list-group-item"><b>Skill Development</b><br>Exposure to high-quality assignments improves writing, research, analytical, and problem-solving skills essential for academic and professional growth.
                            </li>
                            <li class="list-group-item"><b>Confidence Boost</b><br>Submitting accurate, well-presented assignments boosts confidence in coursework, discussions, and examinations.
                            </li>
                        </ul>
                    </div>

                    <div class="content-box">
                        <h2>Supporting Students Across Academic Levels</h2>
                        <p class="content-description">
                            Custom assignment help caters to students at different academic stages, ensuring content remains relevant and appropriate.
                        </p>
                        <h3>Undergraduate Support</h3>
                        <p class="content-description">
                            Undergraduate students often face challenges with essays, research papers, and project reports. Custom assignment help provides structured guidance that aligns with course requirements.
                        </p>
                        <h3>Postgraduate Support</h3>
                        <p class="content-description">
                            Postgraduate assignments require advanced research, in-depth analysis, and academic precision. Expert assistance ensures these expectations are met effectively.
                        </p>
                        <h3>Professional and Career-Oriented Projects</h3>
                        <p class="content-description">
                            Some assignments connect academic learning with real-world applications. Custom assignment help ensures such projects are practical, insightful, and professionally structured.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Why Originality Matters</h2>
                        <p class="content-description">
                            Originality is a critical aspect of academic success. Custom assignment help guarantees plagiarism-free content through:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Writing every assignment from scratch
                            </li>
                            <li class="list-group-item">Proper referencing and citation
                            </li>
                            <li class="list-group-item">Avoiding duplicate or recycled content
                            </li>
                            <li class="list-group-item">Maintaining academic integrity
                            </li>
                        </ul>
                        <p class="content-description">
                            Original and authentic work not only improves grades but also protects a student’s academic credibility and reputation.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>Flexibility and Accessibility</h2>
                        <p class="content-description">
                            Custom assignment help services are designed to meet the diverse needs of students:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Support for urgent deadlines</li>
                            <li class="list-group-item">Assistance with complex or multi-step assignments</li>
                            <li class="list-group-item">Guidance in various academic formats such as APA, MLA, Harvard, and Chicago</li>
                            <li class="list-group-item">Online support for queries, revisions, and feedback</li>
                        </ul>
                        <p class="content-description">
                            Students seeking <b>pay for assignment help</b> often benefit from this flexibility, as it allows them to receive expert assistance exactly when needed.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>How Custom Assignment Help Builds Long-Term Academic Skills</h2>
                        <p class="content-description">
                            Assignments are not only about grades; they help develop essential academic and professional skills:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Research Skills: </b>Learning how to find and use credible academic sources</li>
                            <li class="list-group-item"><b>Analytical Skills: </b>Developing the ability to evaluate information critically</li>
                            <li class="list-group-item"><b>Writing Skills: </b>Improving clarity, structure, and presentation</li>
                            <li class="list-group-item"><b>Problem-Solving: </b>Applying theoretical knowledge to real-world scenarios</li>
                            <li class="list-group-item"><b>Time Management: </b>Learning how to prioritise tasks and meet deadlines</li>
                        </ul>
                        <p class="content-description">
                            By using custom assignment help wisely, students build skills that support higher education and future careers.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Affordable and Reliable Services</h2>
                        <p class="content-description">
                            Many students worry about affordability, but professional custom assignment help services offer value-driven solutions without compromising quality. A reliable service includes:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Strict confidentiality and data protection
                            </li>
                            <li class="list-group-item">24/7 academic supportTransparent pricing with no hidden charges
                            </li>
                            <li class="list-group-item">Plagiarism-free, original content
                            </li>
                            <li class="list-group-item">On-time delivery
                            </li>
                        </ul>
                        <p class="content-description">
                            Students searching for the <b>best online assignment writing service</b> often choose providers that balance affordability with academic excellence.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Commitment to Quality</h2>
                        <p class="content-description">
                            Quality remains the foundation of effective custom assignment help. Every assignment is prepared with:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Accurate and credible research</li>
                            <li class="list-group-item">Clear structure and logical flow</li>
                            <li class="list-group-item">Proper formatting and referencing</li>
                            <li class="list-group-item">Adherence to academic standards</li>
                            <li class="list-group-item">Attention to detail at every stage</li>
                        </ul>
                        <p class="content-description">
                            This commitment ensures assignments meet institutional requirements and help students achieve desired outcomes.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>How to Choose the Right Custom Assignment Help</h2>
                        <p class="content-description">
                            Selecting the right provider is essential for academic success. Students should consider:
                        </p>
                        <ul class="custom-bullet-list">
                            <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Expertise: </b>Writers with academic qualifications and subject experience</li>
                            <li class="list-group-item"><b>Originality: </b>Guaranteed plagiarism-free content</li>
                            <li class="list-group-item"><b>Timely Delivery: </b>Strict adherence to deadlines</li>
                            <li class="list-group-item"><b>Support: </b>Responsive assistance for queries and revisions</li>
                            <li class="list-group-item"><b>Reviews: </b>Genuine feedback from previous students</li>
                        </ul>
                        </ul>
                        <p class="content-description">
                            Students based in the UK often look for trusted services offering <b>assignment help London</b> to ensure familiarity with local academic standards.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Conclusion</h2>
                        <p class="content-description">
                            Navigating college academics can be challenging, especially when managing multiple assignments simultaneously. <b>Custom Assignment Help</b> offers personalised, professional, and reliable academic support that ensures assignments are completed accurately, on time, and to high academic standards.
                        </p>
                        <p class="content-description">
                            With expert guidance, students benefit from:
                        </p>
                        <ul class="custom-bullet-list">
                            <ul class="custom-bullet-list">
                            <li class="list-group-item">Improved grades and academic performance</li>
                            <li class="list-group-item">Better understanding of complex topics</li>
                            <li class="list-group-item">Reduced stress and academic pressure</li>
                            <li class="list-group-item">Stronger research, writing, and problem-solving skills</li>
                            <li class="list-group-item">Confidence in submitting high-quality assignments</li>
                        </ul>
                        <p class="content-description">
                            Custom assignment help is not just a service-it is a powerful tool for academic excellence and long-term success. By choosing tailored academic support, students can focus on learning, growth, and preparing for a brighter future without compromising on quality.
                        </p>
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
                <h2 class="section-title">Frequently Asked Questions For Custom Assignment Help UK</h2>

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