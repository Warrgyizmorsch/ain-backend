@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="Electrical Engineering Assignment Help to Improve Your Grades"
        subtitle="Electrical engineering assignment often include circuits, systems, and numerical problems. We help students understand concepts clearly, work through calculations step by step, and present solutions in a well-organised and easy-to-follow manner.">
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

    <x-common-section.services h1="How to Achieve A+ Grades with Electrical Engineering Assignment Help in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified UK Electrical Engineering Assignment Expert'" />

    <x-common-section.choose-us title="Our Guarantees That Make Electrical Engineering Assignment Help UK Stress-Free and Reliable"
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

    <x-common-section.writing-services-dynamic title="Explore Other Types of Electrical Engineering Assignment Help UK"
        description="Electrical engineering assignments require students to analyse circuits, evaluate electrical systems, and design solutions effectively. Each task demands structured methodology, logical reasoning, and professional academic presentation suitable for UK university standards."
        title1="Circuit Analysis Assignment Help UK"
        desc1="These assignments involve studying electrical circuits, calculating parameters, and verifying outputs. We guide students in presenting analyses logically, maintaining structured reporting, and ensuring academic reasoning.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Parameter Calculation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Circuit Testing<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Component Evaluation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Signal Flow"
        title2="Power Systems Assignment Help UK"
        desc2="Assignments focus on generation, transmission, and distribution of electrical power. Students are supported in explaining systems clearly, maintaining structured reporting, and applying professional academic clarity.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Generation Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Load Management<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Transmission Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Distribution Study"
        title3="Control Systems Assignment Help UK"
        desc3="These assignments involve designing control mechanisms, analysing system stability, and applying feedback loops. We assist students in presenting methods logically and maintaining structured academic reporting.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● System Design<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Feedback Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Stability Evaluation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Controller Implementation"
        title4="Electronics & Instrumentation Assignment Help UK"
        desc4="Assignments require understanding sensors, electronic devices, and measurement techniques. Students are guided in presenting processes clearly, maintaining structured reporting, and ensuring academic standards.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Sensor Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Instrumentation Study<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Device Testing<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Measurement Techniques"
        title5="Electrical Project Implementation Assignment Help UK"
        desc5="Assignments involve designing practical electrical projects, assembling circuits, and documenting results. We help students explain procedures clearly, maintain structured reporting, and ensure academic clarity.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Project Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Circuit Assembly<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Testing & Troubleshooting<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Result Documentation"
        title6="Electrical Assignment Review & Proofreading Help UK"
        desc6="Assignments focus on reviewing calculations, verifying systems, and refining reports. Students are guided to maintain clarity, logical flow, and adhere to UK academic presentation standards.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Error Verification<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Calculation Checking<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Clarity Enhancement<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Structural Refinement" />
    <x-common-section.promo-banner />


    @include('components.common-section.video-testimonial')

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">

                <div class="column">
                    <div class="content-box">
                        <h2>Electrical Engineering Assignment Help UK – Expert Support for Engineering Students
                        </h2>
                        <p class="content-description">
                            Electrical Engineering is one of the most essential and dynamic branches of engineering, forming the backbone of modern technological advancements. It encompasses a wide array of subjects, including circuit theory, power systems, control engineering, electrical machines, and electronics. In academic institutions across the UK, students pursuing Electrical Engineering are frequently required to complete <a href="https://www.assignnmentinneed.com/">assignment help uk</a>, projects, and reports that test both their theoretical understanding and practical application skills. These assignments are designed to evaluate a student’s analytical abilities, problem-solving skills, and capacity to present technically sound solutions in a clear and structured manner.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>The Academic Significance of Electrical Engineering</h2>
                        <p class="content-description">
                            Electrical Engineering plays a critical role in the academic development of students by providing foundational knowledge and practical skills that are applicable across multiple industries. Assignments in this subject serve as a platform for students to explore essential engineering concepts, develop analytical reasoning, and gain hands-on experience in designing and implementing electrical systems. <a href="/do-my-assignment-for-me">do my assignment uk</a> often cover areas such as:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Analysis and design of electrical circuits
                            </li>
                            <li class="list-group-item">Power generation, transmission, and distribution
                            </li>
                            <li class="list-group-item">Control systems and automation
                            </li>
                            <li class="list-group-item">Electrical machines and transformers
                            </li>
                            <li class="list-group-item">Electronics and semiconductor devices
                            </li>
                        </ul>
                        <p class="content-description">
                            Through these assignments, students are expected to demonstrate not only their understanding of theoretical concepts but also their ability to apply them to solve practical problems. UK universities place strong emphasis on clarity, logical reasoning, and structured presentation. This means that students must present their solutions in a coherent manner, including explanations, diagrams, and calculations that are easy to follow and academically sound.
                        </p>
                        <p class="content-description">
                            Assignments in Electrical Engineering help students develop a deeper understanding of how electrical systems operate in real-world scenarios. By integrating theoretical knowledge with practical application, students learn to approach problems systematically, ensuring accuracy and efficiency in their solutions. This approach also encourages critical thinking, as students must analyse complex systems, identify potential challenges, and propose effective solutions based on sound engineering principles.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Common Challenges Students Face in Electrical Engineering Assignments</h2>
                        <p class="content-description">
                            Electrical Engineering is a complex subject that demands precision, analytical skills, and practical knowledge. Students often face several challenges while completing their assignments, which can include:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Complex Calculations: </b>Many assignments require detailed mathematical analysis, including solving differential equations, performing circuit analysis, and calculating power, voltage, and current in various systems. Mistakes in calculations can significantly affect results, making accuracy critical.
                            </li>
                            <li class="list-group-item"><b>Theoretical Complexity: </b>Understanding core concepts such as Kirchhoff’s laws, Thevenin and Norton theorems, transformer principles, or control system stability can be challenging for students who are still developing their analytical skills.
                            </li>
                            <li class="list-group-item"><b>Practical Application: </b>Assignments often require students to connect theoretical knowledge with real-world scenarios. This includes designing circuits, analysing electrical machines, and simulating system performance, which can be difficult without proper guidance.
                            </li>
                            <li class="list-group-item"><b>Structured Presentation: </b>Students must organise their assignments logically, with step-by-step calculations, diagrams, explanations, and academic formatting. Poor presentation or lack of clarity can reduce the overall quality of work, even if calculations are correct.
                            </li>
                            <li class="list-group-item"><b>Time Management: </b>Balancing multiple assignments, lab work, and other academic responsibilities often puts students under pressure. Tight deadlines can lead to rushed submissions, incomplete explanations, or errors in analysis.
                            </li>
                            <li class="list-group-item"><b>Academic Writing Skills: </b>Many students struggle to explain technical solutions in clear academic language. Assignments require more than just solving problems—they must include reasoning, justification, and a coherent flow of ideas.
                            </li>
                        </ul>
                        <p class="content-description">
                            These challenges highlight why professional guidance can be invaluable for students seeking to succeed in Electrical Engineering coursework.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>The Role of Coursework in Electrical Engineering</h2>
                        <p class="content-description">
                            Coursework in Electrical Engineering is an essential component of academic assessment in the UK. It is designed to evaluate a student’s ability to apply theoretical knowledge, solve complex problems, and present results in a professional manner. Assignments often include tasks such as:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Circuit design and analysis
                            </li>
                            <li class="list-group-item">Power system studies
                            </li>
                            <li class="list-group-item">Control system design and evaluation
                            </li>
                            <li class="list-group-item">Electrical machine performance analysis
                            </li>
                            <li class="list-group-item">Electronics and digital system tasks
                            </li>
                        </ul>
                        <p class="content-description">
                            Each type of assignment requires students to not only obtain the correct solution but also to explain their approach logically. Universities expect clarity in documentation, accuracy in calculations, and structured reasoning. This ensures that students not only learn how to solve problems but also develop skills in communication, organisation, and professional reporting.
                        </p>
                        <p class="content-description">
                            Coursework provides an opportunity for students to demonstrate their understanding of core engineering principles, explore real-world applications, and build confidence in tackling complex technical problems. It also helps students prepare for professional engineering roles, where analytical thinking, problem-solving, and documentation skills are critical.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>How Electrical Engineering Assignment Help UK Supports Students</h2>
                        <p class="content-description">
                            Professional support for Electrical Engineering assignments can significantly enhance a student’s learning experience. Experts in the field provide detailed guidance, ensuring that students understand both theoretical concepts and practical applications. Services typically include:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Step-by-step explanations of calculations and problem-solving methods
                            </li>
                            <li class="list-group-item">Guidance in designing circuits, machines, and control systems
                            </li>
                            <li class="list-group-item">Assistance in preparing reports, diagrams, and structured documentation
                            </li>
                            <li class="list-group-item">Support in applying theoretical knowledge to practical scenarios
                            </li>
                            <li class="list-group-item">Ensuring academic standards, clarity, and originality in every assignment
                            </li>
                        </ul>
                        <p class="content-description">
                            With expert help, students can overcome common challenges, manage their time effectively, and submit high-quality <a href="/top-assignment-writing-help-service">top assignment help uk</a> that meet the expectations of UK universities. This support not only improves grades but also helps students build a solid understanding of Electrical Engineering concepts, preparing them for future academic and professional success.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Key Areas Covered in Electrical Engineering Assignments</h2>
                        <p class="content-description">
                            Assignments in Electrical Engineering can vary widely depending on the topic and level of study. Some of the most common areas include:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Circuit Analysis: </b>Students perform calculations to determine voltages, currents, and resistances in various circuits. Assignments may include analysis of series, parallel, and complex networks.
                            </li>
                            <li class="list-group-item"><b>Power Systems: </b>Assignments cover generation, transmission, and distribution of electrical power. Students analyse load flow, fault conditions, and system stability.
                            </li>
                            <li class="list-group-item"><b>Control Systems: </b>Tasks involve designing controllers, analysing feedback mechanisms, and evaluating system responses. Students may also simulate system behaviour under different conditions.
                            </li>
                            <li class="list-group-item"><b>Electrical Machines: </b>Assignments include the study of transformers, motors, and generators. Students calculate efficiency, performance characteristics, and operational parameters.
                            </li>
                            <li class="list-group-item"><b>Electronics and Digital Systems: </b>Students analyse semiconductor devices, design circuits, and evaluate electronic systems for efficiency and functionality.
                            </li>
                            <li class="list-group-item"><b>Projects and Reports: </b>Students are required to integrate knowledge, design systems, document findings, and present results professionally.
                            </li>
                        </ul>
                        <p class="content-description">
                            Assignments in these areas require a combination of theoretical understanding, practical application, and clear academic presentation.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Benefits of Electrical Engineering Assignment Help UK</h2>
                        <p class="content-description">
                            Accessing professional support offers several benefits for students:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item"><b>Expert Guidance: </b>Work with highly qualified Electrical Engineering professionals who provide clear and accurate explanations.
                            </li>
                            <li class="list-group-item"><b>Conceptual Clarity: </b>Gain a deeper understanding of circuits, power systems, control engineering, and electronics.
                            </li>
                            <li class="list-group-item"><b>Improved Academic Performance: </b>Submit high-quality, well-structured assignments that meet UK academic standards.
                            </li>
                            <li class="list-group-item"><b>Time Management: </b>Save time and reduce stress by receiving professional support for complex assignments.
                            </li>
                            <li class="list-group-item"><b>Professional Presentation: </b>Learn how to structure assignments, present calculations and diagrams, and communicate solutions effectively.
                            </li>
                        </ul>
                        <p class="content-description">
                            Professional assistance ensures that students can handle challenging assignments confidently while enhancing their learning experience.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Learning Outcomes Through Electrical Engineering Assignments</h2>
                        <p class="content-description">
                            Completing assignments with expert guidance helps students achieve important learning outcomes, such as:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Strong analytical and problem-solving skills
                            </li>
                            <li class="list-group-item">Practical understanding of electrical systems and machines
                            </li>
                            <li class="list-group-item">Ability to connect theory with real-world applications
                            </li>
                            <li class="list-group-item">Improved technical writing and structured reporting
                            </li>
                            <li class="list-group-item">Confidence in tackling complex academic tasks
                            </li>
                        </ul>
                        <p class="content-description">
                            By approaching assignments systematically, students develop skills that are valuable not only in their academic journey but also in future professional engineering careers.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Conclusion</h2>
                        <p class="content-description">
                            Electrical Engineering assignments are intellectually challenging and require a balance of technical knowledge, logical reasoning, and academic explanation. With Electrical Engineering Assignment Help UK, students gain access to expert guidance that enhances understanding, ensures accuracy, and supports professional presentation. By following structured solutions and clear explanations, students can successfully complete assignments, improve their grades, and develop a deeper understanding of core Electrical Engineering concepts. Professional support not only helps students meet academic expectations but also equips them with skills that are essential for success in both their studies and future careers.
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
                <h2 class="section-title">Frequently Asked Questions For Electrical Engineering Assignment Help UK</h2>

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