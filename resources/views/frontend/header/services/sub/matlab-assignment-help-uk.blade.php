@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="MATLAB Assignment Support from Experts for UK Students"
        subtitle="MATLAB assignments require coding logic, correct functions, and clear output interpretation. We support students in writing clean scripts, understanding calculations, and explaining results properly without making tasks feel complicated or overwhelming.">
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

    <x-common-section.services h1="How to Achieve A+ Grades with MATLAB Assignment Help UK in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified MATLAB Assigment Help Uk Expert'" />

    <x-common-section.choose-us
        title="Our Guarantees That Make UK MATLAB Assignment Help Stress-Free and Reliable" :features="[
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

    <x-common-section.writing-services-dynamic title="Explore Other Types of MATLAB Assignment Help UK"
        description="MATLAB coursework involves simulations, algorithm development, and data analysis. Each assignment requires clear presentation, structured methodology, and academic reasoning. We help students present MATLAB solutions professionally."
        title1="MATLAB Programming Assignment Help UK"
        desc1="Programming assignments include coding scripts, developing algorithms, and debugging. We guide students in presenting solutions with logical flow and academic clarity.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Code Logic<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Algorithm Accuracy<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Debugging<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Structured Explanation"
        title2="MATLAB Simulation Assignment Help UK"
        desc2="Simulation assignments involve modelling systems and visualising outputs. We assist students in presenting simulations clearly with structured methodology and academic reasoning.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● System Modelling<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Output Visualisation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Method Explanation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Academic Clarity"
        title3="MATLAB Data Analysis Assignment Help UK"
        desc3="Data analysis tasks focus on processing datasets, performing statistical tests, and interpreting results. We guide students to present findings logically and academically.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Data Processing<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Statistical Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Result Interpretation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Logical Reporting"
        title4="MATLAB Project Assignment Help UK"
        desc4="Project assignments require integrating multiple scripts, modules, and results. We help students organise content clearly and explain methodology professionally.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Project Integration<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Code Organisation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Result Explanation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Academic Presentation"
        title5="MATLAB GUI Development Assignment Help UK"
        desc5="GUI tasks focus on interactive interface creation, user input handling, and functional design. We guide students to present interface logic and technical explanation clearly.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Interface Design<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Event Handling<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Code Logic<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Workflow Organisation"
        title6="MATLAB Assignment Review & Proofreading UK"
        desc6="Final review ensures correctness, logical flow, and technical clarity. We assist students in refining code, explanations, and structure for academic submission.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Error Checking<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Code Validation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Logical Flow<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Submission Readiness" />
    <x-common-section.promo-banner />


    @include('components.common-section.video-testimonial')

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">

                <div class="column">
                    <div class="content-box">
                        <h2>MATLAB Assignment Help UK - Online Support for Technical and Analytical Excellence
                        </h2>
                        <p class="content-description">
                            MATLAB is one of the most widely used computational and analytical tools in academic institutions across the UK. It is introduced early in many undergraduate programmes and continues to play an important role in postgraduate studies across engineering, mathematics, computer science, and data-related disciplines. MATLAB helps students understand how theoretical concepts can be transformed into practical numerical solutions through simulation, modelling, and data analysis.MATLAB is one of the most widely used computational and analytical tools in academic institutions across the UK. It is introduced early in many undergraduate programmes and continues to play an important role in postgraduate studies across engineering, mathematics, computer science, and data-related disciplines. MATLAB helps students understand how theoretical concepts can be transformed into practical numerical solutions through simulation, modelling, and data analysis.MATLAB is one of the most widely used computational and analytical tools in academic institutions across the UK. It is introduced early in many undergraduate programmes and continues to play an important role in postgraduate studies across engineering, mathematics, computer science, and data-related disciplines. MATLAB helps students understand how theoretical concepts can be transformed into practical numerical solutions through simulation, modelling, and data analysis.
                        </p>
                        <p class="content-description">
                            For many students, MATLAB becomes challenging because it requires a combination of mathematical understanding, programming logic, and analytical interpretation. <a href="https://www.assignnmentinneed.com/">assignment help uk</a> are not limited to writing scripts or executing commands. Students are expected to understand the logic behind algorithms, apply appropriate functions, analyse results critically, and explain outcomes clearly in an academic format. This makes MATLAB assignments both technically and conceptually demanding.For many students, MATLAB becomes challenging because it requires a combination of mathematical understanding, programming logic, and analytical interpretation. assignment help uk are not limited to writing scripts or executing commands. Students are expected to understand the logic behind algorithms, apply appropriate functions, analyse results critically, and explain outcomes clearly in an academic format. This makes MATLAB assignments both technically and conceptually demanding.For many students, MATLAB becomes challenging because it requires a combination of mathematical understanding, programming logic, and analytical interpretation. assignment help uk are not limited to writing scripts or executing commands. Students are expected to understand the logic behind algorithms, apply appropriate functions, analyse results critically, and explain outcomes clearly in an academic format. This makes MATLAB assignments both technically and conceptually demanding.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Understanding the Academic Importance of MATLAB</h2>
                        <p class="content-description">
                            MATLAB holds strong academic value because it bridges the gap between theory and application. UK universities use MATLAB to help students visualise mathematical concepts, test engineering models, and analyse complex systems. Through MATLAB assignments, students learn how abstract theories are applied in real-world scenarios.
                        </p>
                        <p class="content-description">
                            Academically, MATLAB helps students develop:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Strong numerical and analytical problem-solving skills
                            </li>
                            <li class="list-group-item">Ability to model mathematical and engineering systems
                            </li>
                            <li class="list-group-item">Logical thinking and algorithmic understanding
                            </li>
                        </ul>
                        <p class="content-description">
                            Universities assess MATLAB work not only on output accuracy but also on the reasoning behind each step. Students must justify their approach, explain why certain methods are used, and interpret results correctly. This emphasis on explanation makes MATLAB assignments academically significant.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Nature of MATLAB Assignments in UK Universities</h2>
                        <p class="content-description">
                            MATLAB assignments vary depending on the subject and level of study. Some assignments focus on basic scripting and calculations, while others involve simulations, data analysis, or system modelling. Students may be asked to write scripts, define functions, generate plots, or simulate mathematical models.
                        </p>
                        <p class="content-description">
                            In most cases, assignments also include a written component where students explain the methodology, discuss results, and relate findings to theoretical concepts. This combination of coding and academic explanation increases the complexity of MATLAB assignments and requires careful planning and structured execution.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Common Challenges Students Face in MATLAB Assignments</h2>
                        <p class="content-description">
                            Many students struggle with MATLAB due to its technical depth and academic expectations. One of the most common challenges is converting mathematical equations into correct MATLAB syntax. Even small errors in logic or indexing can lead to incorrect results, making debugging a difficult task for beginners.
                        </p>
                        <p class="content-description">
                            Another major challenge lies in interpreting outputs. MATLAB often produces numerical data, graphs, or simulations that must be analysed academically. Students may understand how to generate results but find it difficult to explain what those results mean in relation to theory.
                        </p>
                        <p class="content-description">
                            Time management is also a significant issue. MATLAB assignments often require multiple steps, including coding, testing, correcting errors, and preparing explanations. Without proper guidance, students may feel overwhelmed, especially when deadlines are close.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Conceptual Understanding in MATLAB Coursework</h2>
                        <p class="content-description">
                            MATLAB assignments are designed to strengthen conceptual understanding rather than rote learning. Topics such as matrices, vectors, loops, conditional statements, and numerical methods form the foundation of MATLAB coursework. As students progress, assignments may include advanced concepts such as signal processing, control system simulation, optimisation, or statistical analysis.
                        </p>
                        <p class="content-description">
                            To perform well, students must understand why a particular algorithm is used, how it works, and what conclusions can be drawn from the results. MATLAB coursework encourages students to think analytically and approach problems systematically, which is essential for academic success.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>Role of Structure and Logical Flow in MATLAB Assignments</h2>
                        <p class="content-description">
                            UK universities place strong emphasis on structure and clarity. <a href="/instant-assignment-help">instant  assignment help uk</a> are evaluated not only on whether the code runs successfully but also on how well it is organised and explained. Clear variable naming, logical flow of code, and proper documentation play an important role in assessment.
                        </p>
                        <p class="content-description">
                            In addition to code structure, written explanations must follow a logical sequence. Students are expected to describe the problem, explain their approach, present results, and discuss conclusions clearly. Poor organisation or unclear explanation can negatively impact grades, even if numerical results are correct.
                        </p>
                        <p class="content-description">
                            A well-structured MATLAB assignment typically demonstrates:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Clear logical flow of code and explanations
                            </li>
                            <li class="list-group-item">Proper connection between theory and implementation
                            </li>
                            <li class="list-group-item">Accurate interpretation of results
                            </li>
                            <li class="list-group-item">Academic clarity and coherence
                            </li>
                        </ul>
                    </div>

                    <div class="content-box">
                        <h2>Academic Writing Expectations in MATLAB Assignments</h2>
                        <p class="content-description">
                            Unlike informal coding tasks, academic MATLAB assignments require formal written explanations. Students must explain their methodology, justify the use of specific functions, and discuss results using appropriate academic language. This aspect of MATLAB coursework is often challenging, particularly for students who are comfortable with calculations but less confident in academic writing.
                        </p>
                        <p class="content-description">
                            UK universities expect students to maintain clarity, formal tone, and logical progression in their explanations. Learning how to communicate technical ideas clearly is an essential part of MATLAB education.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Applications of MATLAB in Academic Studies</h2>
                        <p class="content-description">
                            MATLAB is widely used across multiple disciplines, which increases its academic importance. Engineering students use MATLAB for modelling and simulation, mathematics students apply it for numerical methods, and data-focused courses rely on MATLAB for analysis and visualisation.
                        </p>
                        <p class="content-description">
                            Assignments often involve modelling real-world systems, analysing experimental data, or simulating theoretical problems. These tasks help students understand how academic concepts are applied practically, making MATLAB both valuable and challenging.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Time Management and MATLAB Assignments</h2>
                        <p class="content-description">
                            MATLAB assignments require careful planning. Writing code, testing results, correcting errors, and preparing explanations can take significant time. Many students underestimate this effort and struggle to complete <a href="/top-assignment-writing-help-service">top assignment help uk</a> efficiently.
                        </p>
                        <p class="content-description">
                            Structured academic guidance helps students approach MATLAB assignments in an organised manner. With proper support, students can manage their time better, reduce errors, and submit well-prepared work.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Importance of Accuracy and Originality</h2>
                        <p class="content-description">
                            Accuracy is crucial in MATLAB assignments, as even minor mistakes can affect outcomes. Students must ensure correct logic, appropriate function usage, and accurate interpretation of results. At the same time, originality is essential to meet UK academic integrity standards.
                        </p>
                        <p class="content-description">
                            Assignments must reflect the student’s own understanding and reasoning. Proper guidance helps students produce original, accurate, and academically acceptable work without compromising integrity.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Learning Outcomes Through MATLAB Coursework</h2>
                        <p class="content-description">
                            When completed effectively, MATLAB assignments help students achieve several important learning outcomes. These include improved analytical thinking, stronger mathematical understanding, better problem-solving ability, and increased confidence in using computational tools.
                        </p>
                        <p class="content-description">
                            These skills are not only valuable for academic assessments but also highly relevant for future professional and technical careers.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Conclusion</h2>
                        <p class="content-description">
                            MATLAB assignments are designed to challenge students both technically and intellectually. They require a balance of mathematical understanding, computational accuracy, and academic explanation. Success depends on clear concepts, logical structure, and professional presentation.
                        </p>
                        <p class="content-description">
                            With MATLAB Assignment Help UK, students can overcome common challenges, strengthen their understanding, and approach assignments with confidence. Proper academic guidance enables students to meet university expectations, improve performance, and gain long-term academic benefits.
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
                <h2 class="section-title">Frequently Asked Questions For MATLAB Assignment Help UK</h2>

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