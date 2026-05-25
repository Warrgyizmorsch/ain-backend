@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="Business Environment Assignment Help to Understand Market Dynamics"
        subtitle="Business environment assignments require analysis of economic, social, and organisational factors. Students must demonstrate understanding through logical explanation, academic frameworks, and clear evaluation of real-world influences.">
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

    <x-common-section.services h1="How to Achieve A+ Grades with Business Environment Assignment Help UK in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified UK Business Environment Assignment Help Expert'" />

    <x-common-section.choose-us
        title="Our Guarantees That Make Uk Business Environment Assignment Help  Stress-Free and Reliable" :features="[
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

    <x-common-section.writing-services-dynamic title="Explore Other Types of Business Environment Assignment Help UK"
        description="Business environment assignments require students to analyse internal and external factors influencing organisations. Each task demands structured evaluation, critical reasoning, and professional academic presentation suitable for UK university standards."
        title1="Market Environment Analysis Assignment Help UK"
        desc1="These assignments involve evaluating market trends, competition, and consumer behaviour. We guide students in presenting insights logically, maintaining structured reporting, and ensuring academic clarity throughout.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Market Trends<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Competitor Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Consumer Behaviour<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Opportunity Assessment"
        title2="Political & Legal Environment Assignment Help UK"
        desc2="Assignments focus on examining policies, regulations, and legal frameworks affecting business operations. Students are supported in explaining impacts clearly, maintaining structured academic presentation, and demonstrating analytical reasoning.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Policy Evaluation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Regulatory Compliance<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Impact Assessment<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Legal Considerations"
        title3="Economic Environment Assignment Help UK"
        desc3="These assignments require analysing macro and microeconomic factors, financial indicators, and market fluctuations. We assist students in presenting economic analysis logically and maintaining structured academic reporting.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Economic Indicators<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Market Fluctuations<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Financial Implications<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Strategic Insights"
        title4="Socio-Cultural Environment Assignment Help UK"
        desc4="Assignments involve assessing social trends, cultural influences, and demographic patterns affecting organisations. Students are guided to present analysis clearly, maintain logical structure, and ensure academic reasoning.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Cultural Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Demographic Study<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Social Trends<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Market Impact"
        title5="Technological Environment Assignment Help UK"
        desc5="These assignments focus on evaluating technological advancements, digital transformation, and innovation strategies. We help students present findings logically, maintain structured reporting, and demonstrate academic clarity.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Innovation Assessment<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Technology Integration<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Process Enhancement<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Strategic Application"
        title6="Environmental & Sustainability Analysis Assignment Help UK"
        desc6="Assignments require assessing ecological and sustainability factors affecting organisational decisions. Students are supported in explaining strategies clearly, maintaining structured reporting, and applying professional academic reasoning.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Environmental Impact<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Sustainability Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Risk Assessment<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ● Policy Compliance" />
    <x-common-section.promo-banner />


    @include('components.common-section.video-testimonial')

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">

                <div class="column">
                    <div class="content-box">
                        <h2>Business Environment Assignment Help UK: Understanding Markets, Policies, and Organisational Context
                        </h2>
                        <p class="content-description">
                            Business Environment is a core subject in many UK undergraduate and postgraduate programmes related to business, management, and economics. The subject focuses on how internal and external factors influence organisational decision-making, performance, and long-term sustainability. Assignments in this area require students to analyse political, economic, social, technological, legal, and environmental influences using academic frameworks.
                        </p>
                        <p class="content-description">
                            Due to its analytical nature and wide theoretical scope, Business Environment <a href="https://www.assignnmentinneed.com/">assignment help uk</a> can be challenging for students. Many learners seek academic guidance to better understand concepts, improve structure, and present well-reasoned academic discussions aligned with UK university standards.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Understanding the Business Environment as an Academic Subject
                        </h2>
                        <p class="content-description">
                            In UK higher education, Business Environment is not taught as a descriptive subject. Instead, it emphasises structured analysis, critical thinking, and application of theory to real organisational contexts. Students are expected to demonstrate how external and internal forces shape business strategy and operational decisions.
                        </p>
                        <p class="content-description">
                            Assignments assess not only subject knowledge but also the student’s ability to interpret data, evaluate impacts, and justify conclusions using recognised academic models.
                        </p>
                        <h3>Academic Scope of Business Environment Studies
                        </h3>
                        <p class="content-description">
                            Business Environment covers a broad range of themes that are interconnected. These include economic conditions, government policies, competitive forces, technological change, and social trends. Students must show how these factors interact and influence organisational outcomes.
                        </p>
                        <p class="content-description">
                            Rather than memorising definitions, students are assessed on explanation, relevance, and analytical depth.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Common Types of Business Environment Assignments
                        </h2>
                        <p class="content-description">
                            Business Environment coursework varies across UK universities, but the academic expectations remain consistent. Each assignment type requires clarity, logical structure, and theoretical support.
                        </p>
                        <h3>PESTLE and Macro-Environment Analysis
                        </h3>
                        <p class="content-description">
                            One of the most common assignment formats involves analysing macro-environmental factors using models such as PESTLE. Students must explain how political, economic, social, technological, legal, and environmental elements affect a business or industry.
                        </p>
                        <p class="content-description">
                            Marks are awarded for relevance and explanation, not for listing factors without discussion.
                        </p>
                        <h3>Industry and Market Analysis Tasks
                        </h3>
                        <p class="content-description">
                            These assignments focus on competitive conditions within specific industries. Students may be required to examine market trends, customer behaviour, and competitive intensity using academic frameworks.
                        </p>
                        <p class="content-description">
                            Clear linkage between theory and real-world context is essential for strong performance.
                        </p>
                        <h3>Case Study-Based Assignments
                        </h3>
                        <p class="content-description">
                            Case studies require students to analyse real organisations and evaluate how environmental factors influence strategic decisions. This type of assignment tests problem-solving ability and academic reasoning.
                        </p>
                        <h3>Report-Style Business Environment Projects
                        </h3>
                        <p class="content-description">
                            Many UK institutions require formal reports that include structured sections such as introduction, analysis, discussion, and conclusion. These assignments assess both content quality and academic presentation.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Why Business Environment Assignments Are Challenging
                        </h2>
                        <p class="content-description">
                            Students often find Business Environment assignments demanding because of the subject’s broad scope. One of the main challenges is deciding what information is relevant. Including too much general information can weaken academic focus.
                        </p>
                        <p class="content-description">
                            Another difficulty is applying theory correctly. Students may understand concepts but struggle to connect them logically to organisational examples. Academic writing style and structure also present challenges, particularly for first-year and international students.
                        </p>
                        <p class="content-description">
                            Because of these issues, learners frequently explore <a href="/best-online-assignment-writing-service">best online assignment help</a> to improve clarity and alignment with marking criteria.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Importance of Structure and Organisation
                        </h2>
                        <p class="content-description">
                            UK universities place strong emphasis on structured academic writing. Business Environment assignments must follow a clear logical flow that guides the reader through the analysis.
                        </p>
                        <p class="content-description">
                            A well-organised assignment improves readability and demonstrates academic discipline. Disorganised content, even if well-researched, may receive lower marks due to lack of clarity.
                        </p>
                        <h3>Key Structural Expectations
                        </h3>
                        <p class="content-description">
                            Strong Business Environment assignments usually include:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">A focused introduction explaining purpose and context
                            </li>
                            <li class="list-group-item">Clearly organised analytical sections
                            </li>
                            <li class="list-group-item">Logical progression of arguments
                            </li>
                            <li class="list-group-item">A concise conclusion summarising key findings
                            </li>
                        </ul>
                        <p class="content-description">
                            Structure helps examiners quickly identify how well assessment objectives have been addressed.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Analytical Thinking in Business Environment Assignments
                        </h2>
                        <p class="content-description">
                            Analysis is at the core of Business Environment coursework. Students are expected to explain why certain factors matter, how they influence organisations, and what implications arise from these influences.
                        </p>
                        <h3>What Academic Analysis Involves
                        </h3>
                        <p class="content-description">
                            Effective analysis includes:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Explaining cause-and-effect relationships
                            </li>
                            <li class="list-group-item">Evaluating positive and negative impacts
                            </li>
                            <li class="list-group-item">Comparing alternative viewpoints
                            </li>
                            <li class="list-group-item">Supporting discussion with academic theory
                            </li>
                        </ul>
                        <p class="content-description">
                            Simply describing environmental factors without evaluation limits academic value.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>Academic Writing Style Required by UK Universities
                        </h2>
                        <p class="content-description">
                            Business Environment assignments must be written in a formal academic tone. Informal language, unsupported opinions, or vague statements reduce credibility and academic strength.
                        </p>
                        <h3>Writing Style Expectations
                        </h3>
                        <p class="content-description">
                            UK institutions expect:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Clear and concise sentences
                            </li>
                            <li class="list-group-item">Logical paragraph structure
                            </li>
                            <li class="list-group-item">Objective and neutral tone
                            </li>
                            <li class="list-group-item">Consistent academic language
                            </li>
                        </ul>
                        <p class="content-description">
                            Developing this style is a gradual process, especially for students transitioning from school-level writing.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Role of Research and Evidence
                        </h2>
                        <p class="content-description">
                            Business Environment assignments often require students to refer to credible academic and industry sources. Evidence strengthens arguments and demonstrates independent learning.
                        </p>
                        <p class="content-description">
                            Students must also show appropriate integration of sources rather than excessive quotation. Paraphrasing and explanation are preferred over copying information.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Time Management and Academic Pressure
                        </h2>
                        <p class="content-description">
                            Many students study Business Environment alongside multiple modules, making time management a common challenge. Tight deadlines and overlapping submissions can affect assignment quality.
                        </p>
                        <p class="content-description">
                            Academic guidance, including <a href="/cheap-assignment-writing-help">cheap assignment writing help</a>, supports students in managing workload more effectively by improving planning and organisation skills rather than rushing content.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Challenges Faced by International Students
                        </h2>
                        <p class="content-description">
                            International students studying Business Environment in the UK often face additional academic challenges. Understanding UK marking criteria, academic tone, and analytical expectations requires adjustment.
                        </p>
                        <p class="content-description">
                            Language clarity and structured argumentation are particularly important. Academic support helps international learners present ideas more confidently while maintaining academic integrity.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Maintaining Consistency Across Business Modules
                        </h2>
                        <p class="content-description">
                            Business Environment is often linked with other subjects such as strategy, economics, and management. Maintaining consistent academic style and structure across modules improves overall performance.
                        </p>
                        <p class="content-description">
                            Students who develop a standardised approach to analysis and writing find it easier to adapt to different assignment requirements.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Ethical Academic Guidance and Learning Development
                        </h2>
                        <p class="content-description">
                            Ethical academic support focuses on skill enhancement rather than content replacement. Responsible guidance encourages students to understand concepts, improve structure, and refine writing techniques.
                        </p>
                        <p class="content-description">
                            This approach helps students become more independent and confident learners over time.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Skill Development Through Business Environment Assignments
                        </h2>
                        <p class="content-description">
                            Beyond grades, Business Environment assignments help students develop transferable skills. These include critical thinking, analytical reasoning, structured communication, and decision-making.
                        </p>
                        <p class="content-description">
                            Such skills are highly valued in professional settings and contribute to long-term career development.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Applying Business Environment Knowledge to Real-World Contexts
                        </h2>
                        <p class="content-description">
                            One of the strengths of Business Environment studies is its practical relevance. Understanding environmental influences helps students interpret real business challenges more effectively.
                        </p>
                        <p class="content-description">
                            Assignments encourage learners to think beyond theory and recognise how academic concepts apply to contemporary organisational issues.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Importance of Clear Conclusions
                        </h2>
                        <p class="content-description">
                            Conclusions in Business Environment assignments should summarise key analytical insights rather than introduce new ideas. A strong conclusion reinforces academic arguments and demonstrates understanding.
                        </p>
                        <p class="content-description">
                            Concise and focused conclusions improve overall assignment coherence.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Final Conclusion
                        </h2>
                        <p class="content-description">
                            Business Environment assignments require structured thinking, analytical depth, and clear academic communication. UK universities assess how effectively students can interpret environmental factors and apply theory to organisational contexts.
                        </p>
                        <p class="content-description">
                            With proper planning, strong structure, and focused academic development, students can manage Business Environment coursework confidently. Developing clarity, consistency, and analytical skills not only improves grades but also supports long-term academic and professional success.
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
                <h2 class="section-title">Frequently Asked Questions For Business Environment Assignment Help UK</h2>

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