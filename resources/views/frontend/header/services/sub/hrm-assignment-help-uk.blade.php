@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="HRM Assignment Help to Strengthen Your Management Knowledge"
        subtitle="HRM assignments involve people management, organisational behaviour, and workplace practices. We help students explain HR concepts clearly, connect theory with examples, and structure answers in an easy-to-understand way.">
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

    <x-common-section.services
        h1="How to Achieve A+ Grades with Human Resource Management Assignment Help UK in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified UK Human Resource Management Assignment Help  Expert'" />

    <x-common-section.choose-us
        title="Our Guarantees That Make Uk Human Resource Management Assignment Help  Stress-Free and Reliable" :features="[
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

    <x-common-section.writing-services-dynamic title="Explore Other Types of Human Resource Management Assignment Help UK"
        description="HRM assignments focus on employee management, organisational policies, and workplace practices. Each task requires structured explanation, logical reasoning, and professional presentation."
        title1="HR Policies Assignment Help UK"
        desc1="Assignments involve analysing HR policies, procedures, and implementation outcomes. We guide students to present content clearly, maintain structured explanation, and demonstrate professional reasoning throughout.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Policy Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Implementation Review<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Structured Explanation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Logical Flow"
        title2="Employee Management Assignment Help UK"
        desc2="Tasks include recruitment, training, performance evaluation, and employee development. We help students present management concepts clearly, maintain academic structure, and explain reasoning logically.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Recruitment Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Training Evaluation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Performance Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Academic Reporting"
        title3="HRM Project Assignment Help UK"
        desc3="Projects require planning HR interventions, documenting processes, and evaluating results. We guide students in organising work clearly and presenting methodology and outcomes academically.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Project Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Process Documentation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Outcome Evaluation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Structured Presentation"
        title4="HRM Case Study Assignment Help UK"
        desc4="Case studies involve analysing workplace scenarios, employee behaviour, and policy application. We assist students in presenting evaluations logically, applying concepts accurately, and maintaining academic clarity.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Scenario Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Policy Application<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Behaviour Evaluation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Professional Writing"
        title5="HR Reports Assignment Help UK"
        desc5="Report tasks focus on presenting HR data, employee metrics, and policy outcomes. We guide students to structure reports clearly, explain insights logically, and maintain academic standards.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Data Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Report Structuring<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Metric Explanation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Logical Presentation"
        title6="HRM Assignment Review & Proofreading UK"
        desc6="Final review ensures clarity, accuracy, and structured presentation. We help students refine content, verify reasoning, and prepare submissions professionally and academically.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Content Accuracy<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Logical Flow<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Editing & Proofreading<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ● Submission Quality" />
    <x-common-section.promo-banner />


    @include('components.common-section.video-testimonial')

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">

                <div class="column">
                    <div class="content-box">
                        <h2>Human Resource Management Assignment Help UK - Academic Guidance for Management Students
                        </h2>
                        <p class="content-description">
                            Human Resource Management is a crucial subject in business and management programmes in UK
                            universities. It focuses on managing people effectively, understanding employee behaviour,
                            improving organisational performance, and aligning human capital with company goals. HRM
                            assignments require students to combine theoretical knowledge with practical application, making
                            coursework challenging and time-intensive.
                        </p>
                        <p class="content-description">
                            Many students struggle to complete HRM assignments independently due to the combination of
                            critical analysis, case studies, research requirements, and strict academic standards. Human
                            Resource Management <a href="https://www.assignnmentinneed.com/">assignment Help uk</a> provides
                            professional support to help students create well-researched, academically structured, and
                            conceptually accurate assignments that adhere to university guidelines.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Understanding the Importance of HRM
                        </h2>
                        <p class="content-description">
                            HRM is not just about administrative tasks like recruitment or payroll. In UK academic
                            programmes, it is studied as a strategic function that directly influences organisational
                            outcomes. Students are expected to critically analyse HR strategies, employee engagement
                            methods, and leadership practices.
                        </p>
                        <p class="content-description">
                            HRM assignments allow students to explore topics such as:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Employee motivation and performance management
                            </li>
                            <li class="list-group-item">Recruitment and selection strategies
                            </li>
                            <li class="list-group-item">Training and development
                            </li>
                            <li class="list-group-item">Organisational culture and leadership
                            </li>
                            <li class="list-group-item">Employment law and ethical practices
                            </li>
                        </ul>
                        <p class="content-description">
                            Assignments often involve analysing real-world HR issues, comparing theoretical frameworks, and
                            providing recommendations, which requires both clarity and in-depth understanding.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Nature and Structure of HRM Assignments
                        </h2>
                        <p class="content-description">
                            HRM assignments are primarily analytical rather than descriptive. Students are required to
                            evaluate models, assess case studies, and apply theoretical knowledge to practical scenarios.
                            Simply defining concepts is insufficient; UK universities expect strong argument development and
                            evidence-based analysis.
                        </p>
                        <p class="content-description">
                            Assignments can include:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Essays on HR topics
                            </li>
                            <li class="list-group-item">Case studies analysing organisational practices
                            </li>
                            <li class="list-group-item">Reflective reports on HR strategies
                            </li>
                            <li class="list-group-item">Research-based projects exploring workforce management
                            </li>
                        </ul>
                        <p class="content-description">
                            The assessment focuses on the quality of arguments, logical structure, proper referencing, and
                            relevance of examples rather than mere description.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Challenges Students Face in HRM Coursework
                        </h2>
                        <p class="content-description">
                            Students often face difficulties applying theory to practice. While many understand HR
                            principles, they struggle to integrate them into case studies or assignments requiring
                            real-world application. Critical thinking is also a key challenge; UK universities expect
                            students to evaluate different perspectives, discuss limitations of theories, and justify
                            recommendations using credible sources.
                        </p>
                        <p class="content-description">
                            Time management is another common issue. Balancing multiple assignments, lectures, part-time
                            jobs, and personal responsibilities makes it difficult to devote adequate time to HRM
                            coursework. Professional support helps students approach assignments systematically, ensuring
                            clarity, structure, and academic rigor.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Role of HRM Theories and Models
                        </h2>
                        <p class="content-description">
                            HRM assignments often involve analysing complex theories and frameworks, such as motivation
                            theories, leadership models, performance management frameworks, and organisational behaviour
                            concepts. Students must demonstrate not only understanding but also critical evaluation of these
                            models.
                        </p>
                        <p class="content-description">
                            Assignments may require comparison between traditional HR approaches and modern strategic
                            practices. For example, evaluating transactional versus transformational leadership or assessing
                            the effectiveness of different performance appraisal systems. Academic guidance ensures that
                            students understand the practical implications of these theories and can apply them effectively.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Case Studies in HRM Assignments
                        </h2>
                        <p class="content-description">
                            Case studies are commonly used to test student knowledge. They involve analysing organisational
                            situations, identifying HR-related challenges, and proposing evidence-based solutions.
                        </p>
                        <p class="content-description">
                            Effective case study analysis requires:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Identifying key HR issues accurately
                            </li>
                            <li class="list-group-item">Applying theoretical concepts appropriately
                            </li>
                            <li class="list-group-item">Developing structured recommendations
                            </li>
                            <li class="list-group-item">Presenting analysis clearly and logically
                            </li>
                        </ul>
                        <p class="content-description">
                            Students often find it challenging to link theory with practice; academic support helps bridge
                            this gap and produce high-quality assignments.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Human Resource Management Assignment Help UK
                        </h2>
                        <p class="content-description">
                            Assignments in HRM must adhere to formal academic writing standards. Clarity, coherence, and
                            proper structuring are essential. UK universities expect students to present well-organised
                            content with logically connected arguments.
                        </p>
                        <p class="content-description">
                            Key writing standards include:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Clear introduction with context and objectives
                            </li>
                            <li class="list-group-item">Well-structured body paragraphs developing arguments
                            </li>
                            <li class="list-group-item">Evidence-based discussion with credible references
                            </li>
                            <li class="list-group-item">Conclusion summarising key insights and recommendations
                            </li>
                        </ul>
                        <p class="content-description">
                            Adhering to these standards significantly improves assessment outcomes and demonstrates academic
                            maturity.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>Importance of Research in HRM Assignments
                        </h2>
                        <p class="content-description">
                            Research is fundamental to HRM assignments. Students must integrate scholarly articles, books,
                            and credible organisational reports to support arguments. Evidence-based discussion enhances the
                            quality of assignments and ensures alignment with UK academic expectations.
                        </p>
                        <p class="content-description">
                            Professional support assists students in:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Identifying relevant sources
                            </li>
                            <li class="list-group-item">Synthesising information logically
                            </li>
                            <li class="list-group-item">Presenting findings in an academically appropriate manner
                            </li>
                        </ul>
                        <p class="content-description">
                            This research-driven approach strengthens the conceptual depth of assignments and develops
                            critical thinking skills.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Critical Thinking and Analytical Skills
                        </h2>
                        <p class="content-description">
                            Critical thinking is essential in HRM coursework. Students must evaluate theories, discuss
                            alternative perspectives, and provide reasoned recommendations. <a
                                href="/help-with-assignment-online">help with assignment online</a> often require analysis
                            of HR strategies, ethical dilemmas, and organisational challenges.
                        </p>
                        <p class="content-description">
                            Academic guidance ensures students:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Understand multiple viewpoints
                            </li>
                            <li class="list-group-item">Analyse organisational effectiveness
                            </li>
                            <li class="list-group-item">Develop logical and evidence-based arguments
                            </li>
                            <li class="list-group-item">Draw justified conclusions
                            </li>
                        </ul>
                        <p class="content-description">
                            This analytical approach enhances grades and demonstrates intellectual maturity.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Customised Academic Support
                        </h2>
                        <p class="content-description">
                            Human Resource Management Assignment Help UK provides tailored academic assistance based on the
                            student’s level, assignment type, and university requirements. Undergraduate assignments focus
                            on foundational knowledge and explanation, while postgraduate tasks demand deeper analysis,
                            independent thinking, and advanced research.
                        </p>
                        <p class="content-description">
                            Support is customised to help students:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Understand assignment requirements
                            </li>
                            <li class="list-group-item">Apply theories appropriately
                            </li>
                            <li class="list-group-item">Structure arguments logically
                            </li>
                            <li class="list-group-item">Maintain formal academic writing standards
                            </li>
                        </ul>
                        <p class="content-description">
                            This personalised approach ensures academic alignment and quality submissions.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Maintaining Originality and Integrity
                        </h2>
                        <p class="content-description">
                            Academic integrity is crucial. Students must submit original content and avoid plagiarism.
                            Ghostwriting or academic support services ensure that content is unique, customised to
                            assignment requirements, and properly referenced.
                        </p>
                        <p class="content-description">
                            Assignments are developed from scratch, reflecting student-specific instructions and learning
                            outcomes. Proper referencing, paraphrasing, and citation practices are always followed.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Time Management and Academic Efficiency
                        </h2>
                        <p class="content-description">
                            HRM assignments require significant planning, research, and writing. Effective time management
                            is critical for producing high-quality submissions. Academic support helps students organise
                            tasks systematically, allowing sufficient time for research, writing, revision, and
                            proofreading.
                        </p>
                        <p class="content-description">
                            Structured guidance reduces stress and ensures that assignments are submitted on time, meeting
                            university standards without compromising quality.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Learning Through Academic Support
                        </h2>
                        <p class="content-description">
                            Using professionally written HRM assignments as references helps students learn structure,
                            argument development, and referencing techniques. This exposure improves independent writing
                            skills, enhances conceptual understanding, and increases confidence in management studies.
                        </p>
                        <p class="content-description">
                            Students can review examples to develop:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Analytical writing skills
                            </li>
                            <li class="list-group-item">Logical argumentation
                            </li>
                            <li class="list-group-item">Clear presentation of ideas
                            </li>
                            <li class="list-group-item">Effective referencing practices
                            </li>
                        </ul>
                        <p class="content-description">
                            This ensures long-term academic development and better performance in future assignments.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Conclusion
                        </h2>
                        <p class="content-description">
                            Human Resource Management Assignment Help UK provides structured, research-driven, and
                            academically aligned support for students in business and management programmes. HRM assignments
                            require critical thinking, practical application, and clear academic writing.
                        </p>
                        <p class="content-description">
                            With professional guidance, students can enhance their understanding of HR concepts, develop
                            well-structured assignments, maintain originality, and meet UK university standards confidently.
                            Academic support not only reduces stress but also promotes learning, helping students achieve
                            consistent success in Human Resource Management coursework.
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
                <h2 class="section-title">Frequently Asked Questions For Human Resource Management Assignment Help UK</h2>

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