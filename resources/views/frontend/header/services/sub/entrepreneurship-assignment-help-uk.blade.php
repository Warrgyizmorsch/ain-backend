@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="Entrepreneurship Assignment Support to Boost Your Business Knowledge"
        subtitle="Entrepreneurship assignments focus on idea development, feasibility analysis, and theoretical application. Students are expected to link innovation with academic models and explain decisions clearly in a structured academic format.">
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

    <x-common-section.services h1="How to Achieve A+ Grades withEntrepreneurship Assignment Help UK in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified UK Entrepreneurship Assignment Help Expert'" />

    <x-common-section.choose-us
        title="Our Guarantees That Make Uk Entrepreneurship Assignment Help  Stress-Free and Reliable" :features="[
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

    <x-common-section.writing-services-dynamic title="Explore Other Types of Entrepreneurship Assignment Help UK"
        description="Entrepreneurship assignments require students to develop business ideas, assess opportunities, and plan startup strategies. Each task demands structured analysis, practical reasoning, and professional academic presentation suitable for UK university standards."
        title1="Business Idea Formulation Assignment Help UK"
        desc1="These assignments involve researching innovative concepts, analysing market feasibility, and defining objectives. We guide students in presenting ideas logically, maintaining structured reporting, and applying clear academic reasoning.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Idea Generation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Market Research<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Objective Definition<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Feasibility Assessment"
        title2="Startup Strategy Assignment Help UK"
        desc2="Assignments focus on creating operational plans, resource management, and risk evaluation for new ventures. Students are supported in explaining strategies clearly, maintaining logical structure, and demonstrating professional academic clarity.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Operational Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Resource Management<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Risk Evaluation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Milestone Planning"
        title3="Financial Planning Assignment Help UK"
        desc3="These assignments require budgeting, forecasting, and analysing financial viability for entrepreneurial projects. We assist students in presenting financial strategies clearly and maintaining structured academic reporting.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Budget Preparation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Revenue Forecasting<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Cost Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Investment Assessment"
        title4="Marketing & Promotion Assignment Help UK"
        desc4="Assignments involve designing marketing strategies, analysing target audiences, and planning promotional campaigns. Students are guided to present marketing approaches logically and maintain academic reasoning.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Market Segmentation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Promotion Strategy<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Brand Positioning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Campaign Planning"
        title5="Innovation & Product Development Assignment Help UK"
        desc5="These assignments focus on creating innovative products, testing prototypes, and evaluating feasibility. We help students explain development methods clearly, maintain structured reporting, and ensure academic clarity.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Idea Testing<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Prototype Development<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Feasibility Study<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Design Refinement"
        title6="Entrepreneurship Project Review Assignment Help UK"
        desc6="Assignments require reflecting on project outcomes, evaluating success factors, and suggesting improvements. Students are supported in presenting insights clearly, maintaining structured reporting, and following academic standards.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Outcome Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Methodology Review<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Reflection Notes<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Improvement Suggestions" />
    <x-common-section.promo-banner />


    @include('components.common-section.video-testimonial')

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">

                <div class="column">
                    <div class="content-box">
                        <h2>Entrepreneurship Assignment Help UK: Structured Academic Support for UK University Students
                        </h2>
                        <p class="content-description">
                            Entrepreneurship is one of the most practical yet academically demanding subjects taught in UK universities. It combines theoretical understanding with real-world application, requiring students to demonstrate creativity, strategic thinking, and analytical depth within a formal academic framework. Entrepreneurship assignments are designed not only to test business ideas but also to assess how effectively students understand and apply entrepreneurial theories.
                        </p>
                        <p class="content-description">
                            Due to this balance between creativity and academic discipline, many students seek entrepreneurship <a href="https://www.assignnmentinneed.com/">assignment help uk</a> to improve clarity, structure, and alignment with university marking criteria. With increasing academic pressure and tight deadlines, structured academic guidance has become a valuable resource for students aiming to perform consistently well.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Understanding Entrepreneurship in UK Higher Education
                        </h2>
                        <p class="content-description">
                            Entrepreneurship in UK academic programmes is taught as a structured discipline rather than a purely practical activity. Universities focus on helping students understand how entrepreneurial ideas are developed, evaluated, and sustained using established academic models. Assignments require critical engagement with concepts such as innovation, opportunity recognition, risk management, and business growth.
                        </p>
                        <p class="content-description">
                            Students are expected to show evidence of learning through explanation, analysis, and evaluation. Simply presenting an idea is not enough; the academic reasoning behind decisions is what carries marks.
                        </p>
                        <h3>Academic Focus of Entrepreneurship Modules
                        </h3>
                        <p class="content-description">
                            Entrepreneurship modules typically aim to develop analytical and strategic thinking. Coursework is designed to measure how well students can:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Apply entrepreneurial theories to practical scenarios
                            </li>
                            <li class="list-group-item">Analyse opportunities using academic frameworks
                            </li>
                            <li class="list-group-item">Evaluate risks and strategic decisions
                            </li>
                            <li class="list-group-item">Communicate ideas clearly in academic language
                            </li>
                        </ul>
                        <p class="content-description">
                            This structured approach ensures that entrepreneurship is assessed academically rather than subjectively.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Types of Entrepreneurship Assignments Students Encounter
                        </h2>
                        <p class="content-description">
                            Entrepreneurship coursework comes in various formats depending on the university and programme level. Each type of assignment has specific expectations regarding structure, depth, and clarity.
                        </p>
                        <h3>Business Idea Analysis Assignments
                        </h3>
                        <p class="content-description">
                            These assignments require students to evaluate a proposed business idea using academic tools. Focus is placed on feasibility, innovation, and market understanding rather than creativity alone.
                        </p>
                        <h3>Case Study-Based Entrepreneurship Tasks
                        </h3>
                        <p class="content-description">
                            Case studies ask students to analyse real or hypothetical entrepreneurial ventures. Students must identify challenges, evaluate strategies, and justify recommendations using theory.
                        </p>
                        <h3>Reflective Entrepreneurship Assignments
                        </h3>
                        <p class="content-description">
                            Reflective tasks focus on learning development. Students are expected to critically evaluate their understanding of entrepreneurship concepts while maintaining a formal academic tone.
                        </p>
                        <h3>Research-Focused Entrepreneurship Projects
                        </h3>
                        <p class="content-description">
                            These assignments involve market research, opportunity analysis, and academic justification of findings. Strong organisation and clarity are essential for achieving high marks.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Why Entrepreneurship Assignments Feel Challenging
                        </h2>
                        <p class="content-description">
                            Many students struggle with entrepreneurship assignments because they require a combination of creativity and academic structure. One of the most common issues is misunderstanding assessment objectives. Students often focus too heavily on ideas while neglecting explanation and analysis.
                        </p>
                        <p class="content-description">
                            Another challenge is maintaining academic flow. Entrepreneurship topics can be broad, and without a clear structure, assignments may appear unfocused or repetitive. Time management also becomes difficult due to research demands and overlapping deadlines.
                        </p>
                        <p class="content-description">
                            Because of these challenges, students increasingly explore options such as <a href="/help-with-assignment-online">help with assignment online</a> to improve academic clarity and organisation.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Importance of Structure in Entrepreneurship Assignments
                        </h2>
                        <p class="content-description">
                            UK universities strongly value structured academic writing. Entrepreneurship assignments are assessed not only on content quality but also on presentation and organisation. Clear headings, logical progression, and focused paragraphs significantly improve readability and academic impact.
                        </p>
                        <h3>Key Elements of Strong Structure
                        </h3>
                        <p class="content-description">
                            Well-structured entrepreneurship assignments typically include:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Clear introduction outlining purpose and scope
                            </li>
                            <li class="list-group-item">Logically organised main sections
                            </li>
                            <li class="list-group-item">Focused discussion aligned with assessment criteria
                            </li>
                            <li class="list-group-item">Clear conclusion summarising academic insights
                            </li>
                        </ul>
                        <p class="content-description">
                            Using structure helps students communicate ideas effectively and ensures alignment with marking guidelines.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Developing Transferable Skills Through Entrepreneurship Assignments
                        </h2>
                        <p class="content-description">
                            Entrepreneurship coursework develops skills that extend beyond academics. These include analytical thinking, structured communication, strategic planning, and decision-making.
                        </p>
                        <p class="content-description">
                            Such skills are valuable not only for academic success but also for professional development, making entrepreneurship one of the most impactful subjects in higher education.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>Academic Writing Style Required for Entrepreneurship Coursework
                        </h2>
                        <p class="content-description">
                            Entrepreneurship assignments must follow formal academic writing conventions. Informal tone, unsupported claims, or descriptive narration without analysis are discouraged in UK universities.
                        </p>
                        <h3>Writing Expectations Include
                        </h3>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Clear and concise sentence construction
                            </li>
                            <li class="list-group-item">Logical flow of arguments
                            </li>
                            <li class="list-group-item">Objective academic tone
                            </li>
                            <li class="list-group-item">Focus on explanation rather than opinion
                            </li>
                        </ul>
                        <p class="content-description">
                            Developing this writing style takes time, especially for students new to UK academic standards.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Role of Analysis and Critical Evaluation
                        </h2>
                        <p class="content-description">
                            Critical analysis is central to entrepreneurship assignments. Students are expected to evaluate opportunities, assess risks, and justify decisions using academic reasoning. Simply describing entrepreneurial concepts does not demonstrate sufficient understanding.
                        </p>
                        <h3>What Examiners Look For
                        </h3>
                        <p class="content-description">
                            Examiners assess whether students can:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Explain why a strategy is suitable
                            </li>
                            <li class="list-group-item">Identify strengths and limitations of decisions
                            </li>
                            <li class="list-group-item">Support arguments with theory
                            </li>
                            <li class="list-group-item">Demonstrate logical reasoning
                            </li>
                        </ul>
                        <p class="content-description">
                            Strong evaluation shows depth of understanding and academic maturity.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Managing Time and Workload Effectively
                        </h2>
                        <p class="content-description">
                            Entrepreneurship students often juggle multiple academic responsibilities, including presentations, group work, and written assignments. Poor time management can result in rushed submissions and reduced quality.
                        </p>
                        <p class="content-description">
                            Structured academic guidance, including <a href="/instant-assignment-help">instant assignment help</a>instant assignment help, supports students in planning tasks more efficiently. This helps reduce stress and improve consistency across submissions.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Support for International Entrepreneurship Students
                        </h2>
                        <p class="content-description">
                            International students studying entrepreneurship in the UK often face additional academic challenges. Adapting to UK assessment standards, academic writing style, and evaluation methods can be demanding.
                        </p>
                        <p class="content-description">
                            Language clarity, understanding marking rubrics, and maintaining formal tone are common concerns. Academic support helps international students improve confidence and present ideas more clearly while maintaining academic integrity.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Maintaining Consistency Across Entrepreneurship Modules
                        </h2>
                        <p class="content-description">
                            Entrepreneurship programmes often include multiple units with different assessment styles. Maintaining consistency in structure, tone, and academic approach across all assignments is essential for long-term academic success.
                        </p>
                        <p class="content-description">
                            Guided academic support enables students to develop a standardised writing approach that can be applied across different entrepreneurship tasks.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Ethical Academic Support and Skill Development
                        </h2>
                        <p class="content-description">
                            Responsible academic assistance focuses on enhancing learning rather than replacing student effort. Ethical support encourages independent thinking while improving clarity, structure, and academic focus.
                        </p>
                        <h3>Ethical Academic Support Emphasises
                        </h3>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Original and customised academic content
                            </li>
                            <li class="list-group-item">Alignment with university guidelines
                            </li>
                            <li class="list-group-item">Skill development rather than shortcuts
                            </li>
                            <li class="list-group-item">Respect for academic integrity
                            </li>
                        </ul>
                        <p class="content-description">
                            This approach allows students to grow academically while meeting institutional standards.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Benefits of Structured Academic Guidance
                        </h2>
                        <p class="content-description">
                            Structured guidance helps students understand expectations, organise ideas, and improve academic presentation. Over time, this leads to better grades, increased confidence, and stronger academic skills.
                        </p>
                        <p class="content-description">
                            Students learn how to approach assignments strategically rather than reactively, improving performance across multiple modules.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Final Conclusion
                        </h2>
                        <p class="content-description">
                            Entrepreneurship assignments require a careful balance of creativity, analysis, and academic discipline. UK universities assess how well students understand concepts, apply theory, and communicate ideas clearly within structured academic frameworks.
                        </p>
                        <p class="content-description">
                            With proper planning, strong structure, and focused academic guidance, students can approach entrepreneurship assignments with confidence. Reliable academic support helps learners manage workload effectively, improve writing quality, and meet assessment expectations without compromising integrity.
                        </p>
                        <p class="content-description">
                            By developing clarity, consistency, and analytical depth, students can achieve long-term success in entrepreneurship studies and beyond.
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
                <h2 class="section-title">Frequently Asked Questions For Entrepreneurship Assignment Help UK</h2>

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