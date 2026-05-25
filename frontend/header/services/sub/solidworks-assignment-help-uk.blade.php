@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="SolidWorks Assignment Support to Complete Your Engineering Tasks"
        subtitle="SolidWorks Assignment Help UK supports students in developing clear 3D models, assemblies, and design concepts. Assignments are prepared with logical structure, concept clarity, and academic presentation suitable for engineering and design-based evaluations.">
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

    <x-common-section.services h1="How to Achieve A+ Grades with SolidWorks Assignment Help in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified UK Work Assignment Expert'" />

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

    <x-common-section.writing-services-dynamic title="Explore Other Types of SolidWorks Assignment Help UK"
        description="SolidWorks assignment uk involves part modelling, assemblies, simulations, and technical projects. Each assignment type demands structured explanation, precise modelling, and clear academic reasoning. We help students present SolidWorks solutions professionally with logical clarity."
        title1="SolidWorks Part Modelling Assignment Help UK"
        desc1="Part modelling assignments involve designing individual components with accurate dimensions, features, and functions. We guide students to present parts clearly with logical explanation and structured academic reasoning throughout the task.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Feature Design<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Model Accuracy<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Logical Workflow<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Academic Explanation"
        title2="SolidWorks Assembly Assignment Help UK"
        desc2="Assembly assignments focus on combining parts, defining constraints, and ensuring functional interactions. We help students organise assembly work clearly and explain connections logically with professional academic clarity.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Component Connection<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Constraint Logic<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Assembly Verification<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Technical Reporting"
        title3="SolidWorks Simulation Assignment Help UK"
        desc3="Simulation tasks involve analysing models under stress, motion, or thermal conditions. We assist students in presenting results with clear methodology, structured explanation, and logical academic reasoning.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Stress Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Motion Study<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Result Interpretation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Academic Presentation"
        title4="SolidWorks Project Assignment Help UK"
        desc4="Project assignments require planning, executing, and documenting designs. We help students organise content logically, present designs accurately, and explain methodologies professionally and academically.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Project Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Component Organisation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Technical Documentation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Structured Explanation"
        title5="SolidWorks Rendering & Visualisation Help UK"
        desc5="Rendering assignments involve producing realistic visualisations of models. We guide students to present high-quality renders with proper technical details, structured workflow, and academic clarity throughout.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Visual Accuracy<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Rendering Quality<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Component Detail<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Academic Clarity"
        title6="SolidWorks Assignment Review & Proofreading UK"
        desc6="Final review ensures accuracy of models, assemblies, and documentation. We help students refine work, check for errors, and maintain structured academic presentation before submission.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Model Validation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Error Checking<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Workflow Structuring<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Submission Readiness" />
    <x-common-section.promo-banner />


    @include('components.common-section.video-testimonial')

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">

                <div class="column">
                    <div class="content-box">
                        <h2>SolidWorks Assignment Help UK - Top Academic Support for 3D Modelling and Engineering Design
                        </h2>
                        <p class="content-description">
                            SolidWorks is a widely used 3D computer-aided design software taught across mechanical,
                            manufacturing, and product design programmes in UK universities. SolidWorks coursework focuses
                            on creating accurate three-dimensional models, assemblies, and technical designs that reflect
                            engineering intent and functional understanding. Students are expected to demonstrate both
                            software proficiency and strong conceptual design reasoning.
                        </p>
                        <p class="content-description">
                            SolidWorks <a href="https://www.assignnmentinneed.com/">assignment help uk</a> are academically
                            demanding because they require precision
                            modelling, correct application of design constraints, and clear academic explanation of design
                            decisions. Success depends on accuracy, logical modelling workflow, and professional
                            presentation.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Importance of SolidWorks in UK Higher Education</h2>
                        <p class="content-description">
                            SolidWorks plays a vital role in engineering and design education. UK universities include
                            SolidWorks coursework to help students develop industry-relevant design skills and spatial
                            understanding.
                        </p>
                        <p class="content-description">
                            Assignments assess how well students translate engineering concepts into detailed 3D models
                            while maintaining academic and technical standards.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Core Concepts Covered in SolidWorks Coursework</h2>
                        <p class="content-description">
                            SolidWorks assignments commonly involve part modelling, assemblies, surface modelling basics,
                            and drawing creation. Each task requires understanding of dimensions, constraints, and
                            relationships between components.
                        </p>
                        <p class="content-description">
                            Students must clearly explain modelling approaches and justify design choices within an academic
                            context.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Parametric Modelling and Design Intent</h2>
                        <p class="content-description">
                            Parametric modelling is a key concept in SolidWorks coursework. Assignments evaluate how
                            effectively students use dimensions and constraints to control design behaviour.
                        </p>
                        <p class="content-description">
                            Clear demonstration of design intent and logical feature organisation are essential for academic
                            assessment.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Assembly Design and Component Interaction</h2>
                        <p class="content-description">
                            Assembly-based <a href="/top-assignment-writing-help-service">top assignment writing help</a>
                            focus on how individual parts interact within a complete system. Students must apply mates
                            correctly and ensure functional movement where required.
                        </p>
                        <p class="content-description">
                            Academic evaluation emphasises accuracy, logical assembly structure, and explanation of
                            component relationships.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Understanding Engineering Constraints and Features</h2>
                        <p class="content-description">
                            SolidWorks coursework requires students to apply engineering constraints such as tolerances,
                            fits, and material considerations. Assignments test understanding of how these factors influence
                            design functionality.
                        </p>
                        <p class="content-description">
                            Students must justify feature selection and explain design limitations clearly.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Technical Drawings and Documentation</h2>
                        <p class="content-description">
                            Many SolidWorks assignments require conversion of 3D models into 2D technical drawings. This
                            includes views, sections, dimensions, and annotations.
                        </p>
                        <p class="content-description">
                            Assignments are assessed on drawing accuracy, clarity, and adherence to drafting standards.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Design Analysis and Model Validation</h2>
                        <p class="content-description">
                            Some SolidWorks coursework includes basic design validation, such as checking model feasibility
                            or identifying design improvements.
                        </p>
                        <p class="content-description">
                            Students are expected to analyse their models critically and explain design effectiveness within
                            academic submissions.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>Integration of Theory and Practical Modelling</h2>
                        <p class="content-description">
                            SolidWorks assignments require students to apply theoretical engineering knowledge to practical
                            modelling tasks. This demonstrates understanding of mechanical principles and design logic.
                        </p>
                        <p class="content-description">
                            Academic success depends on clear explanation of how theory supports modelling decisions.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Visual Accuracy and Professional Presentation</h2>
                        <p class="content-description">
                            Visual quality is a key assessment criterion in SolidWorks coursework. Models must be clean,
                            well-structured, and professionally presented.
                        </p>
                        <p class="content-description">
                            Assignments are evaluated on visual clarity and consistency throughout the design.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Writing in SolidWorks Assignments</h2>
                        <p class="content-description">
                            SolidWorks coursework often includes written explanations alongside models and drawings.
                            Students must describe design processes, justify decisions, and present results using formal
                            academic language.
                        </p>
                        <p class="content-description">
                            Clear structure and logical flow are essential for academic evaluation.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Problem-Solving and Design Refinement</h2>
                        <p class="content-description">
                            SolidWorks assignments often involve refining designs based on given constraints or feedback.
                            Students must demonstrate problem-solving ability and adaptability.
                        </p>
                        <p class="content-description">
                            Logical reasoning and systematic refinement improve academic outcomes.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Managing Complexity in SolidWorks Coursework</h2>
                        <p class="content-description">
                            SolidWorks assignments can become complex due to multiple features, assemblies, and
                            documentation requirements. Effective organisation and planning are essential.
                        </p>
                        <p class="content-description">
                            Structured modelling practices help students manage complexity and maintain accuracy.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Learning Outcomes of SolidWorks Coursework</h2>
                        <p class="content-description">
                            Through SolidWorks assignments, students develop essential academic and technical skills,
                            including:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">3D modelling accuracy
                            </li>
                            <li class="list-group-item">Engineering design thinking
                            </li>
                            <li class="list-group-item">Spatial reasoning
                            </li>
                            <li class="list-group-item">Technical documentation
                            </li>
                        </ul>
                        <p class="content-description">
                            These skills are highly relevant to engineering and product design careers.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Development Through SolidWorks Studies</h2>
                        <p class="content-description">
                            SolidWorks education strengthens attention to detail, design discipline, and technical
                            confidence. Coursework supports the development of professional design capabilities.
                        </p>
                        <p class="content-description">
                            Consistent academic engagement leads to improved modelling quality and design understanding.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Role of Academic Support in SolidWorks Studies</h2>
                        <p class="content-description">
                            Academic guidance helps students understand modelling principles, organise designs logically,
                            and present work clearly. Support enhances academic quality and technical accuracy.
                        </p>
                        <p class="content-description">
                            With proper academic direction, students can approach SolidWorks coursework confidently and
                            improve overall performance.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Conclusion</h2>
                        <p class="content-description">
                            SolidWorks assignment coursework in the UK is technically rigorous and design-focused. It
                            requires precision, structured modelling, and clear academic explanation.
                        </p>
                        <p class="content-description">
                            By focusing on design intent, modelling accuracy, and professional documentation, students can
                            succeed in SolidWorks coursework and strengthen their engineering design foundation.
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
                <h2 class="section-title">Frequently Asked Questions For SolidWorks Assignment Help UK</h2>

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