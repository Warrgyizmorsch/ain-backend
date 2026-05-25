@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="Business Intelligence Assignment Help from Professional Writers"
        subtitle="Business intelligence coursework involves data analysis, reporting, and decision-making insights. We help students connect data with business understanding and present reports clearly, ensuring ideas are explained in a simple and practical way.">
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

    <x-common-section.services h1="How to Achieve A+ Grades with Business Intelligence Assignment Help UK in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified UK Business Intelligence Assignment Help  Expert'" />

    <x-common-section.choose-us title="Our Guarantees That Make Business Intelligence Assignment Help UK Stress-Free and Reliable"
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

    <x-common-section.writing-services-dynamic title="Explore Other Types of Business Intelligence Assignment Help UK"
        description="Business Intelligence assignments focus on decision-making, reporting, and data analysis. Each task requires logical explanation, technical clarity, and structured presentation."
        title1="BI Tools Assignment Help UK"
        desc1="Assignments involve using Power BI, Tableau, or similar tools to analyse data. We guide students in presenting dashboards and reports clearly and logically.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Tool Usage<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Data Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Report Preparation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Visual Clarity"
        title2="BI Report Assignment Help UK"
        desc2="Report tasks include presenting insights, KPIs, and visual trends. We help students structure content academically and explain findings logically.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Report Structuring<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Data Interpretation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● KPI Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Visual Representation"
        title3="BI Project Assignment Help UK"
        desc3="Project tasks require integrating data sources, creating reports, and analysing insights. We assist students in organising project content and explaining methodology clearly.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Project Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Data Integration<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Insight Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Structured Reporting"
        title4="BI Dashboard Assignment Help UK"
        desc4="Dashboard tasks involve visualising business data for decision-making. We guide students in designing dashboards with clarity and professional academic explanation.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Dashboard Design<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Data Representation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Visual Clarity<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Logical Flow"
        title5="BI Data Analysis Assignment Help UK"
        desc5="Analysis assignments require extracting patterns, insights, and trends from datasets. We help students present results logically and academically.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Pattern Extraction<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Trend Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Result Interpretation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Logical Explanation"
        title6="BI Assignment Review & Proofreading UK"
        desc6="Final review ensures reports, dashboards, and analysis are correct, structured, and clear. We assist students in refining content and validating results.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Content Accuracy<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Logical Flow<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Result Verification<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Submission Readiness" />
    <x-common-section.promo-banner />


    @include('components.common-section.video-testimonial')

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">

                <div class="column">
                    <div class="content-box">
                        <h2>Business Intelligence Assignment Help UK - Academic Guidance for Strategic and Data-Driven Understanding
                        </h2>
                        <p class="content-description">
                            Business Intelligence (BI) has become a central subject in UK universities for students pursuing degrees in business analytics, information systems, management, and data-related disciplines. It focuses on how organisations collect, manage, analyse, and transform data into meaningful insights that support strategic and operational decisions. Through academic assignments, students are expected to demonstrate a clear understanding of how data influences business performance and decision-making processes.
                        </p>
                        <p class="content-description">
                            Business intelligence <a href="https://www.assignnmentinneed.com/">assignment help uk</a> are designed to evaluate more than technical knowledge. Students must analyse information logically, explain analytical processes clearly, and connect insights with organisational objectives. This combination of analytical thinking, business understanding, and academic writing makes BI coursework demanding and time-intensive for many students.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Growing Academic Importance of Business Intelligence</h2>
                        <p class="content-description">
                            UK universities place strong academic emphasis on business intelligence because of its relevance to modern organisations. BI supports informed decision-making, performance measurement, and long-term planning. Assignments encourage students to understand how data-driven strategies create value within competitive business environments.
                        </p>
                        <p class="content-description">
                            Business intelligence coursework helps students develop:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Strategic thinking based on data
                            </li>
                            <li class="list-group-item">Analytical reasoning for decision support
                            </li>
                            <li class="list-group-item">Understanding of organisational information systems
                            </li>
                        </ul>
                        <p class="content-description">
                            Universities assess how well students explain these connections, not just how much data they present.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Structure and Expectations of Business Intelligence Assignments</h2>
                        <p class="content-description">
                            Business intelligence assignments vary in format depending on the programme and academic level. Some focus on conceptual discussion of BI frameworks, while others involve analytical evaluation of organisational data and reporting systems.
                        </p>
                        <p class="content-description">
                            Typical BI assignments may require students to:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Explain business intelligence concepts clearly
                            </li>
                            <li class="list-group-item">Analyse organisational or case-based data
                            </li>
                            <li class="list-group-item">Evaluate decision-support approaches
                            </li>
                            <li class="list-group-item">Present findings in a structured academic format
                            </li>
                        </ul>
                        <p class="content-description">
                            Students must combine theory with application, ensuring that explanations remain clear, relevant, and academically sound.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Core Areas Covered in Business Intelligence Coursework</h2>
                        <p class="content-description">
                            Business intelligence coursework introduces students to the complete data-to-decision process. Assignments require students to understand how information flows within an organisation and how insights are generated.
                        </p>
                        <p class="content-description">
                            Key academic areas often explored include:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Data organisation and integration
                            </li>
                            <li class="list-group-item">Analytical reporting and visual interpretation
                            </li>
                            <li class="list-group-item">Performance monitoring concepts
                            </li>
                            <li class="list-group-item">Decision-support frameworks
                            </li>
                        </ul>
                        <p class="content-description">
                            Rather than focusing on isolated tools, universities expect students to explain how these elements work together to support business objectives.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Analytical Thinking in Business Intelligence Studies</h2>
                        <p class="content-description">
                            Analytical thinking is a central requirement in BI assignments. Students must evaluate data patterns, identify trends, and explain their significance in a business context. Simply describing data is not sufficient for high academic performance.
                        </p>
                        <p class="content-description">
                            Assignments often test a student’s ability to:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Interpret data logically
                            </li>
                            <li class="list-group-item">Explain business implications clearly
                            </li>
                            <li class="list-group-item">Support arguments with analytical reasoning
                            </li>
                        </ul>
                        <p class="content-description">
                            This level of analysis requires conceptual clarity and careful academic presentation.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Common Challenges in Business Intelligence Assignments</h2>
                        <p class="content-description">
                            Many students struggle with business intelligence due to its multidisciplinary nature. One major challenge is linking data analysis with business strategy. Students may understand analytical results but find it difficult to explain how those results support decision-making.
                        </p>
                        <p class="content-description">
                            Another challenge involves academic structuring. BI assignments often require formal reports, case evaluations, and reflective explanations. Poor organisation or unclear argument flow can significantly reduce marks.
                        </p>
                        <h3>Managing Conceptual and Writing Demands</h3>
                        <p class="content-description">
                            Business intelligence coursework demands both analytical ability and strong academic writing. Balancing these requirements can be challenging without structured guidance.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>Methodological Clarity in BI Coursework</h2>
                        <p class="content-description">
                            Methodology plays a critical role in business intelligence assignments. Universities expect students to clearly explain how data is collected, processed, analysed, and interpreted. Methodological transparency demonstrates academic maturity and understanding.
                        </p>
                        <p class="content-description">
                            A strong BI assignment typically includes:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Clear explanation of business context
                            </li>
                            <li class="list-group-item">Logical analytical approach
                            </li>
                            <li class="list-group-item">Structured interpretation of insights
                            </li>
                            <li class="list-group-item">Well-supported conclusions
                            </li>
                        </ul>
                        <p class="content-description">
                            Methodology helps examiners evaluate the quality and reliability of analysis.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Writing and Presentation Standards</h2>
                        <p class="content-description">
                            Business intelligence <a href="/assignment-writing-help-services">assignment writing help uk</a> require formal academic writing. Students must present ideas clearly, maintain logical progression, and use appropriate terminology. Explanations should be detailed yet concise, avoiding unnecessary technical jargon.
                        </p>
                        <p class="content-description">
                            Well-written BI assignments demonstrate clarity of thought and improve overall readability. Universities value structured arguments and coherent discussion over lengthy but unclear explanations.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Use of Business Scenarios in BI Assignments</h2>
                        <p class="content-description">
                            Many business intelligence assignments are based on real or simulated organisational scenarios. Students are required to analyse operational data, performance indicators, or strategic outcomes.
                        </p>
                        <p class="content-description">
                            These assignments test a student’s ability to:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Apply academic concepts to realistic situations
                            </li>
                            <li class="list-group-item">Interpret business data meaningfully
                            </li>
                            <li class="list-group-item">Explain how insights influence organisational decisions
                            </li>
                        </ul>
                        <p class="content-description">
                            Connecting theory with practice is a key assessment criterion.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Importance of Organisation and Flow</h2>
                        <p class="content-description">
                            Clear organisation significantly improves the quality of business intelligence assignments. Universities expect logical progression from problem identification to analysis and conclusion.
                        </p>
                        <p class="content-description">
                            A well-organised assignment reflects structured thinking and makes complex analysis easier to understand for examiners.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Time Management and BI Coursework Challenges</h2>
                        <p class="content-description">
                            Business intelligence assignments often require extended preparation time. Data analysis, interpretation, and academic writing all demand careful planning. Many students struggle to balance BI coursework with other academic responsibilities.
                        </p>
                        <p class="content-description">
                            Effective time management allows students to approach assignments systematically rather than rushing analysis or explanation.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Accuracy, Originality, and Academic Ethics</h2>
                        <p class="content-description">
                            Accuracy is essential in business intelligence coursework, as incorrect interpretation can lead to flawed conclusions. Universities also emphasise originality and independent reasoning.
                        </p>
                        <p class="content-description">
                            Students must demonstrate their own understanding while adhering to academic integrity standards. Clear guidance helps students maintain both accuracy and originality.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Skill Development Through Business Intelligence Assignments</h2>
                        <p class="content-description">
                            BI assignments support the development of important academic and professional skills, including analytical reasoning, strategic thinking, and data-based communication. These skills are valuable beyond university assessments and contribute to long-term career readiness.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Why Academic Support Matters in Business Intelligence</h2>
                        <p class="content-description">
                            Academic support helps students understand complex BI concepts, structure their assignments effectively, and meet university assessment expectations. Guidance encourages clarity, consistency, and confidence in analytical presentation.
                        </p>
                        <p class="content-description">
                            With structured support, students can move beyond surface-level analysis and develop deeper understanding of business intelligence principles.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Conclusion</h2>
                        <p class="content-description">
                            Business intelligence assignments require a careful balance of analytical insight, business understanding, and academic explanation. UK universities assess students on how effectively they transform data into meaningful insights and present those insights clearly.
                        </p>
                        <p class="content-description">
                            With Business Intelligence Assignment Help UK, students can strengthen conceptual clarity, improve academic structure, and approach BI coursework with confidence. Proper guidance enables students to meet academic standards and achieve consistent success in their studies.
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
                <h2 class="section-title">Frequently Asked Questions For Business Intelligence Assignment Help UK</h2>

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