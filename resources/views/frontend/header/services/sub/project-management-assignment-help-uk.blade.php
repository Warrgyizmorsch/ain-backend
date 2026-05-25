@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="Project Management Assignment Help for Successful Course Completion"
        subtitle="Project management assignments require students to plan tasks, manage resources, and justify decisions using recognised academic frameworks. This page supports UK university students in presenting structured, well-organised project work.">
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

    <x-common-section.services h1="How to Achieve A+ Grades with Project Management Assignment Help UK in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified UK Project Management Assignment Help Expert'" />

    <x-common-section.choose-us title="Our Guarantees That Make Uk Project Management Assignment Help  Stress-Free and Reliable"
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

    <x-common-section.writing-services-dynamic title="Explore Other Types of Project Management Assignment Help UK"
        description="Project management assignments require students to plan, organise, and supervise projects while meeting deadlines and quality standards. Each task demands strategic thinking, structured planning, and a professional academic presentation suitable for UK university requirements."
        title1="Project Planning Assignment Help UK"
        desc1="These assignments involve defining project scope, creating timelines, and setting milestones. We guide students to present planning methods clearly, maintain structured reporting, and ensure academic reasoning is applied throughout.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Scope Definition<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Timeline Creation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Milestone Setting<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Task Prioritisation"
        title2="Risk Assessment Assignment Help UK"
        desc2="Assignments focus on identifying potential risks, evaluating impact, and proposing mitigation strategies. Students are supported in explaining risk management processes, maintaining structured reporting, and applying logical academic analysis.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Risk Identification<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Impact Evaluation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Mitigation Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Contingency Strategy"
        title3="Resource Allocation Assignment Help UK"
        desc3="These assignments involve assigning personnel, budgeting, and managing equipment efficiently. We assist students in explaining allocation decisions, maintaining structured reporting, and ensuring clarity in academic presentation.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Personnel Assignment<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Budget Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Equipment Management<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Resource Optimisation"
        title4="Project Execution Assignment Help UK"
        desc4="Assignments require implementing project plans, monitoring tasks, and coordinating teams effectively. We help students explain execution methods clearly, maintain logical workflow, and present academic-level project documentation.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Task Implementation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Team Coordination<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Progress Monitoring<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Workflow Organisation"
        title5="Quality Management Assignment Help UK"
        desc5="These assignments involve ensuring outputs meet quality standards, monitoring processes, and performing evaluations. Students are guided to present quality assurance techniques clearly and maintain structured academic reporting.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Standards Compliance<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Process Monitoring<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Outcome Evaluation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ● Corrective Actions"
        title6="Project Review & Reflection Assignment Help UK"
        desc6="Assignments focus on evaluating project performance, analysing uccesses and challenges, and suggesting improvements. We assist students in presenting reflective insights logically and maintaining professional academic clarity.<br>
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
                        <h2>Project Management Assignment Help UK: Academic Support for Structured University Learning
                        </h2>
                        <p class="content-description">
                            Project management is an essential subject across many UK university programmes, including business, engineering, construction, and information systems. It equips students with the ability to plan, organise, and evaluate tasks within academic and professional frameworks. As university assessments increasingly focus on structured outcomes and clear justification, many students turn to project management <a href="https://www.assignnmentinneed.com/">assignment help uk</a> to better understand academic requirements and improve the quality of their submissions.
                        </p>
                        <p class="content-description">
                            Project management assignments are designed to assess more than basic knowledge. Students must demonstrate planning logic, analytical thinking, and the ability to explain decisions using formal academic language. These expectations often make project management coursework demanding, particularly for students managing multiple modules and deadlines.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Role of Project Management in UK Universities
                        </h2>
                        <p class="content-description">
                            In UK higher education, project management is treated as a structured academic discipline rather than a purely practical skill. Assignments are aligned with learning outcomes that assess a student’s understanding of planning methodologies, coordination processes, and evaluation techniques.
                        </p>
                        <p class="content-description">
                            Students are expected to show how project management principles apply to academic or simulated project scenarios. This includes explaining objectives, outlining structured processes, and demonstrating logical sequencing. Universities place strong emphasis on explanation and justification rather than descriptive writing.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Nature of Project Management Assignments
                        </h2>
                        <p class="content-description">
                            Project management assignments often involve multiple interconnected sections. These may include defining project objectives, outlining methodologies, explaining workflows, and analysing outcomes. Each section must remain consistent and aligned with the overall project context.
                        </p>
                        <p class="content-description">
                            Assignments are assessed on clarity, organisation, and academic discipline. Even when ideas are correct, a weak structure or unclear explanation can lead to lower grades. This makes academic presentation just as important as content accuracy.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Common Challenges Faced by Students
                        </h2>
                        <p class="content-description">
                            Many students find project management assignments challenging due to the level of detail and structure required. One common difficulty is interpreting assignment briefs accurately. Misunderstanding assessment criteria can result in content that does not fully address learning outcomes.
                        </p>
                        <p class="content-description">
                            Another challenge is maintaining logical flow across sections. Students may understand individual concepts but struggle to connect them cohesively. Time pressure further increases difficulty, especially when assignments require careful planning, writing, and revision.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Importance of Structure and Logical Flow
                        </h2>
                        <p class="content-description">
                            Structure plays a central role in project management coursework. UK universities assess how clearly students organise ideas and present project stages. Each section should build logically on the previous one, guiding the reader from planning to evaluation.
                        </p>
                        <p class="content-description">
                            A clear structure demonstrates analytical thinking and academic control. Well-organised assignments are easier to follow and more likely to meet assessment expectations. In contrast, poorly structured work often appears unfocused, even when the content is relevant.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Writing Expectations
                        </h2>
                        <p class="content-description">
                            Project management assignments must be written in formal academic English. Universities expect clarity, precision, and consistency throughout the assignment. Informal language, unsupported opinions, or vague explanations do not meet academic standards.
                        </p>
                        <p class="content-description">
                            Students are required to explain why certain approaches are selected and how they contribute to achieving project objectives. This emphasis on explanation distinguishes academic project management writing from general descriptions.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Planning and Methodology in Academic Assignments
                        </h2>
                        <p class="content-description">
                            Planning-focused assignments assess a student’s ability to define scope, objectives, and processes clearly. Academic evaluation focuses on reasoning rather than operational detail. Students must justify planning decisions using structured academic logic.
                        </p>
                        <p class="content-description">
                            Methodology sections require an explanation of how tasks are organised and managed within the project framework. Clear methodology demonstrates understanding of structured approaches and strengthens academic credibility.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Time Management and Academic Pressure
                        </h2>
                        <p class="content-description">
                            Project management students often balance demanding coursework with other academic and personal responsibilities. Overlapping deadlines across modules can make it difficult to allocate sufficient time to each assignment.
                        </p>
                        <p class="content-description">
                            Poor time management may lead to rushed submissions, incomplete explanations, or inconsistent structure. Academic support helps students organise work more effectively and approach assignments with greater confidence.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Support for International Students
                        </h2>
                        <p class="content-description">
                            International students studying project management in the UK often face additional challenges. Adjusting to UK academic writing standards, understanding marking criteria, and maintaining a formal tone can be difficult during the early stages of study.
                        </p>
                        <p class="content-description">
                            Clear academic guidance supports international learners in improving clarity, structure, and confidence while adapting to university expectations.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Development of Academic and Professional Skills
                        </h2>
                        <p class="content-description">
                            Project management coursework helps students develop transferable skills such as planning, organisation, and analytical writing. These skills support academic success while also preparing students for professional environments.
                        </p>
                        <p class="content-description">
                            Engaging with structured assignments encourages disciplined thinking, clearer communication, and stronger academic reasoning.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Application of Project Management Concepts in University Assessments
                        </h2>
                        <p class="content-description">
                            Project management assignments in UK universities require students to apply theoretical concepts within structured academic contexts. These concepts are not assessed in isolation; instead, students are evaluated on how effectively they integrate planning principles, coordination strategies, and evaluation methods into a coherent academic discussion.
                        </p>
                        <p class="content-description">
                            Assignments often require students to explain how project phases connect with each other and how decisions made during planning influence execution and outcomes. A clear explanation of concept application demonstrates deeper understanding and strengthens academic performance.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>Analytical Thinking and Decision Justification
                        </h2>
                        <p class="content-description">
                            Analytical reasoning is a critical component of project management coursework. Students are expected to justify decisions related to planning approaches, scheduling logic, and management strategies. Assignments frequently include sections where students must evaluate alternatives and explain why a specific approach is academically appropriate.
                        </p>
                        <p class="content-description">
                            UK universities reward assignments that go beyond description and demonstrate critical thinking. Clear justification supported by logical reasoning helps examiners assess a student’s understanding and analytical capability.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Evaluation and Review Within Project Assignments
                        </h2>
                        <p class="content-description">
                            Many project management modules include evaluation or review components. These sections assess a student’s ability to analyse project outcomes and reflect on the effectiveness of planning and execution strategies. Academic evaluation focuses on clarity, relevance, and structured reasoning.
                        </p>
                        <p class="content-description">
                            Students are expected to explain what outcomes were achieved, how effectively objectives were met, and what factors influenced project performance. This reflective analysis must remain academically grounded rather than opinion-based.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Importance of Documentation and Academic Presentation
                        </h2>
                        <p class="content-description">
                            Documentation is an essential part of project management coursework. Assignments often simulate professional project reporting while maintaining academic standards. Clear formatting, consistent terminology, and logical organisation are important for academic assessment.
                        </p>
                        <p class="content-description">
                            Universities assess not only the content but also how information is presented. Poor formatting, inconsistent structure, or unclear explanations can weaken an otherwise well-researched assignment. Effective documentation reflects academic discipline and communication skills.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Reflective Learning in Project Management Studies
                        </h2>
                        <p class="content-description">
                            Reflective writing is commonly included in project management assessments. Students may be asked to reflect on learning experiences, project challenges, or methodological decisions. Reflection helps demonstrate self-awareness and deeper engagement with the subject.
                        </p>
                        <p class="content-description">
                            Academic reflection requires structured explanation and evidence-based reasoning. Superficial reflection or unsupported statements usually result in lower evaluation. Well-written reflection shows maturity and academic growth.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Maintaining Academic Consistency Across Submissions
                        </h2>
                        <p class="content-description">
                            Project management programmes often involve multiple assignments across different modules. Maintaining consistency in structure, tone, and academic approach is important for long-term success. Students who develop a clear academic style early perform more confidently across assessments.
                        </p>
                        <p class="content-description">
                            Consistency helps students manage their workload efficiently and reduces confusion when addressing similar assessment criteria across modules.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Ethical Academic Support and Learning Development
                        </h2>
                        <p class="content-description">
                            Ethical academic support focuses on improving understanding rather than replacing learning. Responsible guidance helps students interpret briefs correctly, organise content effectively, and develop academic writing skills.
                        </p>
                        <p class="content-description">
                            This approach supports independent learning while ensuring that assignments meet university standards. Ethical support encourages confidence and academic integrity.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Growth Through Structured Support
                        </h2>
                        <p class="content-description">
                            Students who receive structured academic guidance often experience improved confidence and clarity. Understanding expectations, improving structure, and strengthening explanation skills contribute to steady academic improvement.
                        </p>
                        <p class="content-description">
                            Over time, students develop better control over academic writing, project planning, and analytical reasoning, which benefits overall academic progression.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Managing Academic Pressure and Expectations
                        </h2>
                        <p class="content-description">
                            Project management coursework can be demanding due to its structured nature and detailed requirements. Academic pressure increases when students face multiple deadlines or complex assessment criteria.
                        </p>
                        <p class="content-description">
                            Guided academic support helps students manage expectations, reduce stress, and approach assignments in a more organised manner. This leads to better time management and improved academic outcomes.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Preparing for Professional and Academic Progression
                        </h2>
                        <p class="content-description">
                            Project management assignments prepare students for both academic and professional environments. Skills developed through structured coursework include planning discipline, analytical thinking, and effective communication.
                        </p>
                        <p class="content-description">
                            These skills support future studies and career development, making project management an academically valuable subject.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Conclusion
                        </h2>
                        <p class="content-description">
                            Project management assignments in UK universities challenge students to combine planning, analysis, and structured academic writing. These tasks test both conceptual understanding and practical application, requiring clarity, logical flow, and disciplined presentation.
                        </p>
                        <p class="content-description">
                            With proper guidance, students can organise their work effectively, explain methodologies clearly, and reflect critically on project outcomes. Academic support helps manage workload, improve consistency, and develop transferable skills that benefit both university performance and future professional growth.
                        </p>
                        <p class="content-description">
                            Overall, structured guidance enables students to approach project management assignments confidently, meet academic expectations, and achieve stronger learning outcomes.
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
                <h2 class="section-title">Frequently Asked Questions For Project Management Assignment Help UK</h2>

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