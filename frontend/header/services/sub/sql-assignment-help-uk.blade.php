@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="SQL Assignment Help to Complete Your Database Projects Efficiently"
        subtitle="SQL coursework focuses on database queries, data retrieval, and table management. We support students in writing accurate queries, understanding database concepts, and presenting solutions in a clear and organised format.">
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

    <x-common-section.services h1="How to Achieve A+ Grades with SQL Assignment Help UK in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified SQL Assignment Help UK Expert'" />

    <x-common-section.choose-us
        title="Our Guarantees That Make Uk SQL Assignment Help  Stress-Free and Reliable" :features="[
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

    <x-common-section.writing-services-dynamic title="Explore Other Types of SQL Assignment Help UK"
        description="SQL assignments involve querying databases, managing data, and presenting results. Each task requires structured explanation professional academic presentation. We help students present SQL solutions clearly with full clarity and reasoning."
        title1="SQL Query Writing Assignment Help UK"
        desc1="Query writing tasks require accurate syntax, logical structure, and correct outputs. We guide students in presenting queries clearly, explaining purpose academically, and maintaining full professional reasoning throughout the assignment.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Query Structure<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Syntax Accuracy<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Result Verification<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Logical Flow"
        title2="SQL Database Design Assignment Help UK"
        desc2="Design assignments focus on creating schemas, tables, and relationships with proper integrity. We help students organise database structures logically and explain technical details with clear academic reasoning.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Schema Design<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Table Organisation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Relationship Mapping<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Technical Explanation"
        title3="SQL Data Analysis Assignment Help UK"
        desc3="Data analysis assignments involve filtering datasets, aggregating data, and interpreting results accurately. We guide students to present outcomes clearly, explain logic, and maintain structured academic clarity.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Data Filtering<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Aggregation Techniques<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Result Interpretation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Logical Reporting"
        title4="SQL Project Assignment Help UK"
        desc4="Project tasks require implementing databases, writing queries, testing functionality, and documenting processes. We assist students in presenting work logically and explaining methodology thoroughly and academically.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Project Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Query Implementation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Result Documentation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Structured Reporting"
        title5="SQL Performance & Optimisation Assignment Help UK"
        desc5="Optimisation tasks involve improving query performance, indexing strategies, and database efficiency. We guide students to present methods logically, explain solutions academically, and demonstrate full clarity.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Performance Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Indexing Techniques<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Query Optimisation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Logical Explanation"
        title6="SQL Assignment Review & Proofreading UK"
        desc6="Final review ensures queries, database structure, and results are correct, logically organised, and academically presented. We assist students in refining work for submission and maintaining professional standards.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Structure Verification<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Query Accuracy<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Logical Flow<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Submission Readiness" />
    <x-common-section.promo-banner />


    @include('components.common-section.video-testimonial')

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">

                <div class="column">
                    <div class="content-box">
                        <h2>SQL Assignment Help UK - Academic Support for Database Querying and Data Management Excellence
                        </h2>
                        <p class="content-description">
                            Structured Query Language is a fundamental subject in UK universities for students studying computer science, information technology, data analytics, business systems, and software-related programmes. SQL is used to store, retrieve, manage, and analyse data within relational database systems. Through academic assignments, students are expected to demonstrate not only technical query-writing skills but also conceptual understanding of database design and data handling principles.
                        </p>
                        <p class="content-description">
                            SQL <a href="https://www.assignnmentinneed.com/">assignment help uk</a> are academically demanding because they combine logical thinking, technical accuracy, and clear explanation. Students must write correct queries, understand how databases operate internally, and explain their solutions in a structured academic manner. Due to this balance of theory and practice, SQL coursework often becomes challenging for students across different academic levels.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Importance of SQL in UK Higher Education
                        </h2>
                        <p class="content-description">
                            UK universities treat SQL as a core subject because databases form the foundation of modern information systems. Almost every application relies on structured data storage and retrieval, making SQL an essential skill for students.
                        </p>
                        <p class="content-description">
                            SQL coursework helps students understand how data is organised, accessed, and manipulated efficiently. Universities focus on conceptual clarity, correct use of database principles, and logical explanation of queries rather than memorised syntax alone.
                        </p>
                        <p class="content-description">
                            SQL plays an academic role in teaching:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Data organisation and structure
                            </li>
                            <li class="list-group-item">Logical data relationships
                            </li>
                            <li class="list-group-item">Efficient information retrieval
                            </li>
                            <li class="list-group-item">Accuracy and consistency in data handling
                            </li>
                        </ul>
                        <p class="content-description">
                            Because SQL concepts are reused across multiple subjects, strong understanding is essential for long-term academic success.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Nature and Structure of SQL Assignments
                        </h2>
                        <p class="content-description">
                            SQL assignments vary depending on the academic programme and level of study. Introductory coursework focuses on basic query writing, while advanced assignments require students to analyse database structures and solve complex data-related problems.
                        </p>
                        <p class="content-description">
                            Most SQL assignments involve:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Understanding database schemas
                            </li>
                            <li class="list-group-item">Writing queries to retrieve or manipulate data
                            </li>
                            <li class="list-group-item">Applying conditions, joins, and grouping logic
                            </li>
                            <li class="list-group-item">Explaining query logic and outcomes
                            </li>
                        </ul>
                        <p class="content-description">
                            UK universities assess how well students justify their approach and explain why specific queries were used.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Understanding Core Database Concepts in SQL Coursework
                        </h2>
                        <p class="content-description">
                            SQL coursework is designed to build a strong foundation in database concepts. Assignments require students to demonstrate understanding of how data is stored, linked, and accessed within relational systems.
                        </p>
                        <p class="content-description">
                            Key academic concepts often covered include tables, relationships, keys, and constraints. Students must explain how data integrity is maintained and how relationships between tables affect query results.
                        </p>
                        <p class="content-description">
                            As coursework progresses, assignments become more complex and require deeper understanding of relational logic and query optimisation.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Query Design and Logical Thinking in SQL Assignments
                        </h2>
                        <p class="content-description">
                            Logical thinking plays a critical role in SQL assignments. Students must carefully analyse the problem, understand the data structure, and design queries that produce accurate results.
                        </p>
                        <p class="content-description">
                            SQL assignments test a student’s ability to:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Translate requirements into logical queries
                            </li>
                            <li class="list-group-item">Apply conditions and filters correctly
                            </li>
                            <li class="list-group-item">Combine multiple tables using joins
                            </li>
                            <li class="list-group-item">Interpret query outputs accurately
                            </li>
                        </ul>
                        <p class="content-description">
                            Even small logical errors can lead to incorrect results, making careful planning essential.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Common Challenges Faced by Students in SQL Coursework
                        </h2>
                        <p class="content-description">
                            Many students struggle with SQL assignments due to difficulty in understanding relational logic. Concepts such as joins, nested queries, and grouping operations often cause confusion.
                        </p>
                        <p class="content-description">
                            Another challenge involves interpreting results. Students may write queries that return data but struggle to explain what the output represents and how it answers the given problem.
                        </p>
                        <h3>Syntax Accuracy and Conceptual Understanding
                        </h3>
                        <p class="content-description">
                            While SQL syntax is relatively straightforward, academic assignments require precise usage. Incorrect syntax or misunderstood logic often results in errors or incomplete answers.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>Importance of Database Structure in SQL Assignments
                        </h2>
                        <p class="content-description">
                            Understanding database structure is essential for writing effective SQL queries. Universities expect students to analyse table relationships and use them correctly within queries.
                        </p>
                        <p class="content-description">
                            A strong SQL assignment demonstrates:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Clear understanding of database schema
                            </li>
                            <li class="list-group-item">Correct use of keys and relationships
                            </li>
                            <li class="list-group-item">Logical query construction
                            </li>
                            <li class="list-group-item">Accurate result interpretation
                            </li>
                        </ul>
                        <p class="content-description">
                            Poor understanding of structure often leads to inefficient or incorrect queries.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Writing Expectations in SQL Assignments
                        </h2>
                        <p class="content-description">
                            SQL assignments are not limited to query output. Students are often required to submit explanations, reports, or descriptive answers that explain their approach and reasoning.
                        </p>
                        <p class="content-description">
                            Academic writing in SQL coursework should be clear, structured, and formal. Students must explain why specific queries were chosen, how joins work, and what results indicate. Weak explanation can reduce marks even if queries are correct.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Practical Application of SQL in Academic Assignments
                        </h2>
                        <p class="content-description">
                            Many SQL assignments are based on realistic data scenarios. Students may work with organisational databases, customer records, or transactional systems.
                        </p>
                        <p class="content-description">
                            These tasks assess the ability to:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Analyse structured datasets
                            </li>
                            <li class="list-group-item">Extract meaningful information
                            </li>
                            <li class="list-group-item">Support decision-making through data
                            </li>
                        </ul>
                        <p class="content-description">
                            Connecting SQL queries with real-world context improves academic quality and demonstrates practical understanding.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Query Optimisation and Performance Understanding
                        </h2>
                        <p class="content-description">
                            At higher academic levels, SQL assignments may require students to consider efficiency and performance. Universities assess how well students understand query behaviour and data handling.
                        </p>
                        <p class="content-description">
                            Students are expected to explain how queries can be improved logically rather than focusing only on producing output.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Time Management in SQL Coursework
                        </h2>
                        <p class="content-description">
                            SQL assignments often require careful planning, testing, and explanation. Writing queries, verifying results, and preparing academic explanations can take longer than expected.
                        </p>
                        <p class="content-description">
                            Many students struggle with time management when balancing SQL coursework with other technical subjects. A structured approach helps reduce stress and improve overall quality.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Accuracy, Originality, and Academic Integrity
                        </h2>
                        <p class="content-description">
                            Accuracy is essential in SQL <a href="/instant-assignment-help">instant assignment help uk</a> because incorrect logic leads to wrong results. UK universities also place strong emphasis on originality and independent problem-solving.
                        </p>
                        <p class="content-description">
                            Students must demonstrate their own understanding and reasoning while following academic integrity guidelines. Clear academic support helps maintain both accuracy and originality.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Skill Development Through SQL Assignment help UK
                        </h2>
                        <p class="content-description">
                            SQL coursework helps students develop important skills such as logical reasoning, data analysis, and structured thinking. These skills are valuable across academic disciplines and professional roles.
                        </p>
                        <p class="content-description">
                            Understanding SQL strengthens a student’s ability to work with data-driven systems and supports future academic and career development.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Why Academic Support Matters in SQL Studies
                        </h2>
                        <p class="content-description">
                            Academic support helps students understand database concepts clearly, structure queries logically, and present explanations effectively. Guidance encourages confidence and consistency in SQL coursework.
                        </p>
                        <p class="content-description">
                            With structured support, students move beyond trial-and-error query writing and develop a deeper understanding of relational databases.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Conclusion
                        </h2>
                        <p class="content-description">
                            SQL assignments require a strong balance of logical reasoning, technical accuracy, and academic explanation. UK universities assess students on how effectively they understand databases, write queries, and explain their reasoning clearly.
                        </p>
                        <p class="content-description">
                            With SQL Assignment Help UK, students can improve conceptual clarity, strengthen academic structure, and approach SQL coursework with confidence. Proper guidance enables students to meet assessment standards and achieve consistent academic success.
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
                <h2 class="section-title">Frequently Asked Questions For SQL Assignment Help UK</h2>

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