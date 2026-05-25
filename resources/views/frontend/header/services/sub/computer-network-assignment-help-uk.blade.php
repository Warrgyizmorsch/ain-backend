@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="Computer Network Assignment Help for Comprehensive Solutions"
        subtitle="Computer network coursework covers protocols, architectures, and system communication. We help students understand networking concepts clearly and organise their answers logically, making complex network topics easier to explain and present.">
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

    <x-common-section.services h1="How to Achieve A+ Grades with Computer Network Assignment Help UK in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified  Computer Network Assignment Help UK Expert'" />

    <x-common-section.choose-us
        title="Our Guarantees That Make Computer Network Assignment Help UK Stress-Free and Reliable" :features="[
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

    <x-common-section.writing-services-dynamic title="Explore Other Types of Computer Network Assignment Help UK"
        description="Computer Network assignments involve protocols, data communication, and network design. Each task requires logical explanation, structured presentation, and accuracy. We help students present networking concepts clearly and professionally."
        title1="Network Protocol Assignment Help UK"
        desc1="Assignments on protocols focus on TCP/IP, HTTP, and routing rules. We guide students to explain functioning and structure logically while maintaining clear technical and academic reasoning.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Protocol Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Data Flow<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Logical Explanation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Technical Accuracy"
        title2="LAN & WAN Assignment Help UK"
        desc2="LAN and WAN tasks involve design, configuration, and performance evaluation. We help students present networks clearly with structured explanation and technical clarity.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Network Design<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Configuration Accuracy<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Performance Evaluation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Logical Flow"
        title3="Network Security Assignment Help UK"
        desc3="Security assignments include firewalls, encryption, and threat management. We guide students in presenting solutions clearly and explaining methods professionally.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Threat Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Security Measures<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Encryption Techniques<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Logical Explanation"
        title4="Network Project Assignment Help UK"
        desc4="Project tasks require implementing networks, testing, and documenting results. We assist students in organising work logically and presenting explanations clearly.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Project Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Implementation Logic<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Testing Accuracy<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Structured Documentation"
        title5="Network Simulation Assignment Help UK"
        desc5="Simulation assignments involve simulating traffic, protocols, and network behaviour. We help students explain methods and results academically and clearly.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Traffic Simulation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Protocol Testing<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Result Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Logical Reporting"
        title6="Network Assignment Review & Proofreading UK"
        desc6="Final review ensures correctness, clarity, and accuracy. We help students refine explanations, validate network logic, and prepare for submission.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Content Accuracy<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Logical Flow<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Technical Review<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Submission Readiness" />
    <x-common-section.promo-banner />


    @include('components.common-section.video-testimonial')

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">

                <div class="column">
                    <div class="content-box">
                        <h2>Computer Network Assignment Help UK - Academic Support for Connected Systems Learning
                        </h2>
                        <p class="content-description">
                            Computer Networks form the backbone of modern computing and communication systems. In UK universities, computer networking is a core subject across computer science, information technology, and engineering programmes. From understanding how data travels across systems to analysing network security and performance, students are required to develop both theoretical knowledge and practical insight through academic assignments.
                        </p>
                        <p class="content-description">
                            Computer network <a href="https://www.assignnmentinneed.com/">assignment help uk</a> are designed to test a student’s understanding of communication models, protocols, and network behaviour. These tasks often involve analysing real-world networking scenarios, explaining technical concepts, and presenting solutions in a structured academic format. Due to the subject’s technical depth and broad scope, many students find network-related coursework challenging.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Importance of Computer Networks in UK Education</h2>
                        <p class="content-description">
                            Computer networking is not treated as an isolated technical subject in UK universities. It is closely linked with system design, cybersecurity, cloud computing, and distributed systems. Assignments are structured to help students understand how interconnected systems function in real environments.
                        </p>
                        <p class="content-description">
                            Through network coursework, students learn how devices communicate, how data is transmitted securely, and how network efficiency is maintained. Universities emphasise conceptual clarity, correct terminology, and logical explanation of networking processes rather than memorised definitions.
                        </p>
                        <h3>Networks as a Foundation Subject</h3>
                        <p class="content-description">
                            A strong understanding of computer networks supports learning in advanced areas such as network security, system administration, and cloud-based architectures. This is why academic assessments focus heavily on explanation and analysis.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Nature of Computer Network Assignments</h2>
                        <p class="content-description">
                            Computer network assignments vary depending on the level of study. Introductory tasks may focus on basic models and protocols, while advanced coursework involves performance analysis, network design, or security evaluation. Many assignments require students to explain how networks behave under specific conditions.
                        </p>
                        <p class="content-description">
                            In addition to theoretical discussion, students are often asked to analyse diagrams, interpret network configurations, or evaluate communication processes. Written explanation plays a major role in how these assignments are assessed.
                        </p>
                        <h3>Combination of Theory and Application
                        </h3>
                        <p class="content-description">
                            UK universities expect students to connect networking theory with practical scenarios. Assignments often assess how well students apply concepts rather than simply defining them.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Common Difficulties Faced by Students</h2>
                        <p class="content-description">
                            One of the main difficulties students face is understanding abstract networking concepts. Topics such as layered models, packet transmission, and routing behaviour can be difficult to visualise without strong conceptual grounding.
                        </p>
                        <p class="content-description">
                            Another challenge is academic explanation. Students may understand how a network works practically but struggle to explain processes clearly in formal academic language. Incorrect use of terminology or vague explanations often lead to reduced marks.
                        </p>
                        <h3>Technical Concepts and Clarity Issues
                        </h3>
                        <p class="content-description">
                            Networking involves multiple interconnected processes. Explaining these step by step while maintaining clarity and logical flow requires careful academic structuring.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Understanding Core Networking Concepts</h2>
                        <p class="content-description">
                            Computer network coursework focuses heavily on foundational concepts. These include communication models, data transmission methods, and network organisation. Students must demonstrate understanding of how information flows from one system to another.
                        </p>
                        <p class="content-description">
                            As assignments progress, complexity increases. Students are expected to analyse network behaviour, identify issues, and explain solutions using correct theoretical reasoning. Conceptual understanding becomes more important than surface-level knowledge.
                        </p>
                        <h3>Importance of Conceptual Accuracy
                        </h3>
                        <p class="content-description">
                            In network assignments, small conceptual misunderstandings can lead to incorrect conclusions. Universities assess how accurately students interpret networking behaviour.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Role of Logical Structure in Network Assignments</h2>
                        <p class="content-description">
                            Logical organisation is critical in computer network coursework. Assignments are expected to follow a clear structure, beginning with problem explanation, followed by analysis, and ending with justified conclusions.
                        </p>
                        <p class="content-description">
                            Well-structured answers help examiners understand a student’s reasoning. Poor organisation or scattered explanations often affect overall grading, even if some technical points are correct.
                        </p>
                        <h3>Flow and Coherence in Academic Answers
                        </h3>
                        <p class="content-description">
                            Networking topics involve sequences of processes. Explaining them in a clear order improves readability and demonstrates understanding.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>Academic Writing Expectations in Computer Networking</h2>
                        <p class="content-description">
                            Computer network assignments are not purely technical documents. Students must explain concepts in a formal academic style, using correct terminology and structured arguments. Reports, short-answer explanations, and analytical discussions are common formats.
                        </p>
                        <p class="content-description">
                            UK universities expect clarity, coherence, and logical justification. Overly brief or informal explanations usually do not meet academic standards.
                        </p>
                        <h3>Balancing Technical Detail With Simplicity
                        </h3>
                        <p class="content-description">
                            Good network assignments explain complex processes in a simple, understandable manner without losing technical accuracy.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Practical Scenarios in Network Coursework</h2>
                        <p class="content-description">
                            Many computer network assignments are based on real-world scenarios. Students may be asked to analyse organisational networks, evaluate performance issues, or discuss communication efficiency.
                        </p>
                        <p class="content-description">
                            These tasks require analytical thinking and the ability to relate theory to practice. Students must explain not only what happens in a network but also why it happens.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Time Management and Network Assignments</h2>
                        <p class="content-description">
                            Network assignments often require careful reading, conceptual analysis, and structured writing. Students sometimes underestimate the time needed to understand technical scenarios and prepare clear explanations.
                        </p>
                        <p class="content-description">
                            Balancing network coursework with other subjects can be challenging, especially when multiple technical modules are involved.
                        </p>
                        <h3>Avoiding Last-Minute Errors
                        </h3>
                        <p class="content-description">
                            Rushed submissions often result in unclear explanations or incomplete analysis. A systematic approach improves overall quality.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Accuracy and Academic Integrity</h2>
                        <p class="content-description">
                            Accuracy is essential in computer network assignments. Incorrect protocol behaviour, misused terminology, or flawed explanations can significantly affect grades. Universities expect students to demonstrate precise understanding.
                        </p>
                        <p class="content-description">
                            Originality is equally important. Assignments must reflect independent learning and proper reasoning. Clear academic guidance helps students maintain both accuracy and originality.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Learning Outcomes Through Network Coursework</h2>
                        <p class="content-description">
                            Computer network <a href="/instant-assignment-help">instant assignment help uk</a> are designed to build essential academic and professional skills. Students develop analytical thinking, technical communication ability, and structured problem-solving skills.
                        </p>
                        <p class="content-description">
                            These learning outcomes are valuable for careers in IT, networking, cybersecurity, and system administration.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Why Academic Support Matters in Networking Studies</h2>
                        <p class="content-description">
                            Academic support helps students understand complex networking concepts, organise their answers properly, and meet university assessment criteria. Guidance encourages a structured approach rather than fragmented learning.
                        </p>
                        <p class="content-description">
                            With the right academic direction, students gain confidence in explaining network behaviour and applying concepts correctly.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Conclusion</h2>
                        <p class="content-description">
                            Computer network assignments require a strong balance of conceptual understanding, logical explanation, and academic presentation. UK universities assess not only technical knowledge but also clarity, structure, and reasoning.
                        </p>
                        <p class="content-description">
                            With Computer Network Assignment Help UK, students can overcome conceptual difficulties, improve academic expression, and approach networking coursework with confidence. Structured support enables students to meet academic expectations and achieve consistent results in their studies.
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
                <h2 class="section-title">Frequently Asked Questions For Computer Network Assignment Help UK</h2>

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