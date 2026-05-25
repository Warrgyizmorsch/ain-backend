@extends('frontend-layouts.app')

@section('content')

    <x-common-section.hero title="Ghostwriting Assignment Support to Maintain Confidentiality"
        subtitle="Ghostwriting assignments require strong writing skills and proper structure. We help students organise ideas, maintain clarity, and present content smoothly while matching university-level expectations and keeping the writing natural and student-friendly.">
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

    <x-common-section.services h1="How to Achieve A+ Grades with Ghostwriting Assignment Help UK in 4 Simple Steps"
        step1="Share Your Requirements"
        step1Content="Share your assignment details—subject, topic, word count, and deadline—or upload your requirement file. We use these precise instructions to match your order with the most qualified expert in your field."
        step2="Make Payment"
        step2Content="Complete your secure payment through your preferred method. Once payment is confirmed, your dedicated expert will begin working on your assignment immediately."
        step3="Get Expert Help"
        step3Content="Monitor your order in real time through your personalised dashboard, from the initial draft stage to the final quality check. You will be notified the moment your completed document is ready for download."
        step4="Receive Solution"
        step4Content="Receive your crafted document by your specified deadline. Review the content and, if necessary, request any minor refinements or revisions for free within the specified revision period." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meet Your Verified  Ghostwriting Assignment Help UK Expert'" />

    <x-common-section.choose-us
        title="Our Guarantees That Make Ghostwriting Assignment Help UK Stress-Free and Reliable" :features="[
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

    <x-common-section.writing-services-dynamic title="Explore Other Types of Ghostwriting Assignment Help UK"
        description="Ghostwriting assignments involve producing academic content, essays, or reports for students. Each task requires professional structure, clarity, and academic style. We help students present ideas logically, academically, and with full reasoning."
        title1="Academic Essay Ghostwriting Help UK"
        desc1="Essays require clear structure, coherent arguments, proper referencing, and analytical reasoning. We guide students in presenting ideas logically, maintaining professional tone, and ensuring full academic clarity throughout.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Content Structuring<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Argument Development<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Academic Writing<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Reference Management"
        title2="Research Paper Ghostwriting Help UK"
        desc2="Research papers involve literature review, data analysis, critical evaluation, and citation accuracy. We help students organise content clearly, present findings logically, and maintain full professional academic reasoning.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Literature Evaluation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Data Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Structured Writing<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Citation Accuracy"
        title3="Report Ghostwriting Help UK"
        desc3="Report assignments focus on clear explanation of data, conclusions. We assist students in presenting content logically, structuring information professionally, and ensuring complete academic reasoning.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Data Explanation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Methodology Detailing<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Logical Flow<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Conclusion Structuring"
        title4="Project Documentation Ghostwriting Help UK"
        desc4="Project documents require planning, explanation, results presentation, and conclusions. We guide students to structure reports clearly and present technical and academic details comprehensively.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Project Planning<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Method Explanation<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Outcome Reporting<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Structured Documentation"
        title5="Case Study Ghostwriting Help UK"
        desc5="Case studies require analysing scenarios, applying concepts, and evaluating outcomes. We assist students in presenting evaluation logically, applying theories accurately, and maintaining full academic reasoning throughout.<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Case Analysis<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Concept Application<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Evaluation Logic<br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ● Professional Writing"
        title6="Ghostwriting Assignment Review & Editing UK"
        desc6="Final review ensures content clarity, logical flow, and academic tone. We help students refine work professionally, check reasoning, and maintain structured presentation before submission.<br>
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
                        <h2>Ghostwriting Assignment Help UK - Confidential Writing Support for Students
                        </h2>
                        <p class="content-description">
                            Ghostwriting has become an important form of academic assistance for students studying in UK universities. Academic pressure, strict deadlines, complex subjects, and high expectations often make it difficult for students to complete assignments independently within limited time. Ghostwriting <a href="https://www.assignnmentinneed.com/">assignment help uk</a> offers structured academic support where experienced writers assist students in preparing well-researched, clearly written, and academically sound assignments while maintaining complete confidentiality.
                        </p>
                        <p class="content-description">
                            In the UK education system, academic writing standards are very high. Assignments are expected to reflect strong subject knowledge, logical structure, critical thinking, and proper academic language. Many students struggle not because they lack intelligence, but because they lack time, confidence in writing, or clarity in presenting ideas academically. Ghostwriting support helps students bridge this gap effectively.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Understanding Ghostwriting in Academic Context
                        </h2>
                        <p class="content-description">
                            Ghostwriting in academics refers to professional assistance in drafting assignments based on the student’s requirements, university guidelines, and subject expectations. The content is prepared in a way that supports learning, understanding, and academic growth while maintaining discretion.
                        </p>
                        <p class="content-description">
                            In UK universities, assignments are often lengthy, research-based, and require strong argument development. Ghostwriting support focuses on helping students understand how academic content is structured rather than simply delivering text. The goal is to provide guidance through professionally written academic material.
                        </p>
                        <p class="content-description">
                            Ghostwriting services are commonly used for essays, reports, case studies, reflective writing, dissertations, and other academic tasks that require extensive planning and clarity.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Why UK Students Seek Ghostwriting Assignment Help
                        </h2>
                        <p class="content-description">
                            UK students come from diverse academic backgrounds, including international students who may face language barriers or unfamiliar academic styles. Many students find it difficult to meet British academic writing standards, especially when assignments demand critical analysis and structured arguments.
                        </p>
                        <p class="content-description">
                            Other common reasons include workload pressure, part-time jobs, personal responsibilities, and overlapping submission deadlines. Ghostwriting assistance helps students manage academic responsibilities without compromising quality.
                        </p>
                        <p class="content-description">
                            Rather than rushing assignments or submitting poorly structured work, students prefer guided academic support that aligns with university expectations.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Academic Writing Expectations in UK Universities
                        </h2>
                        <p class="content-description">
                            UK academic writing emphasises clarity, argument development, and evidence-based discussion. Assignments are not judged solely on word count but on depth of understanding, originality of thought, and proper referencing.
                        </p>
                        <p class="content-description">
                            Ghostwriting assignment help focuses on:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Clear introduction and thesis development
                            </li>
                            <li class="list-group-item">Logical flow of ideas
                            </li>
                            <li class="list-group-item">Academic tone and vocabulary
                            </li>
                            <li class="list-group-item">Proper referencing and citation
                            </li>
                            <li class="list-group-item">Conclusion that reflects critical understanding
                            </li>
                        </ul>
                        <p class="content-description">
                            Understanding these expectations is essential for producing high-quality academic work.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Confidentiality and Ethical Boundaries
                        </h2>
                        <p class="content-description">
                            Confidentiality is a core aspect of ghostwriting support. Students expect their identity, academic details, and project information to remain secure. Professional ghostwriting services maintain strict privacy standards to ensure student data is protected.
                        </p>
                        <p class="content-description">
                            Ethically, ghostwriting support is designed to guide students academically. The content is prepared based on student-provided instructions, learning objectives, and academic guidelines. The aim is to help students understand how high-quality academic writing is developed.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Research-Driven Approach in Ghostwriting Assignments
                        </h2>
                        <p class="content-description">
                            Academic ghostwriting relies heavily on research. UK assignments require credible sources, recent studies, and scholarly references. Writers carefully analyse relevant academic materials to ensure arguments are supported by evidence.
                        </p>
                        <p class="content-description">
                            Research-driven ghostwriting includes:
                        </p>
                        <ul class="custom-bullet-list">
                            <li class="list-group-item">Understanding assignment questions thoroughly
                            </li>
                            <li class="list-group-item">Identifying relevant academic sources
                            </li>
                            <li class="list-group-item">Synthesising information logically
                            </li>
                            <li class="list-group-item">Presenting arguments in an academic manner
                            </li>
                        </ul>
                        <p class="content-description">
                            This approach ensures content quality and academic relevance.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Structuring Assignments for Academic Impact
                        </h2>
                        <p class="content-description">
                            Structure plays a vital role in academic writing. Ghostwriting assistance focuses on creating assignments with a clear framework that improves readability and coherence.
                        </p>
                        <p class="content-description">
                            Each section is carefully planned so that ideas connect logically. Introductions set the context, body paragraphs develop arguments, and conclusions summarise key insights. This structured approach helps meet marking criteria effectively.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2>Critical Thinking and Analysis in Ghostwritten Work
                        </h2>
                        <p class="content-description">
                            UK universities value critical analysis more than descriptive writing. Assignments are expected to evaluate ideas, compare perspectives, and present reasoned arguments.
                        </p>
                        <p class="content-description">
                            Ghostwriting assignment help focuses on developing analytical depth. Writers ensure that arguments are supported with evidence, counter-arguments are acknowledged, and conclusions are logically derived from discussion.
                        </p>
                        <p class="content-sescription">
                            This enhances the academic quality of the assignment and supports higher grading outcomes.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Language Accuracy and Academic Tone
                        </h2>
                        <p class="content-description">
                            Many students struggle with maintaining a formal academic tone. Grammar, sentence structure, and vocabulary play a significant role in how assignments are assessed.
                        </p>
                        <p class="content-description">
                            Ghostwriting support ensures language clarity, consistency, and academic appropriateness. Complex ideas are expressed clearly without unnecessary jargon, making content accessible and professional.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Customised Support for Different Academic Levels
                        </h2>
                        <p class="content-description">
                            Ghostwriting assistance is tailored according to the student’s academic level. Undergraduate assignments require basic critical discussion, while postgraduate work demands advanced analysis and independent thinking.
                        </p>
                        <p class="content-description">
                            Writers adapt content style, depth, and complexity to match:
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Undergraduate expectations
                            </li>
                            <li class="list-group-item">Postgraduate standards
                            </li>
                            <li class="list-group-item">Research-oriented assignments
                            </li>
                        </ul>
                        <p class="content-description">
                            This level-specific approach ensures academic alignment.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Time Management and Deadline Support
                        </h2>
                        <p class="content-description">
                            Meeting deadlines is a major challenge for students. Ghostwriting assignment help supports students by managing time-intensive research and writing processes efficiently.
                        </p>
                        <p class="content-description">
                            Students receive structured academic content within required timelines, allowing them to review, understand, and prepare for submission without last-minute stress.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Originality and Academic Integrity
                        </h2>
                        <p class="content-description">
                            Originality is a key concern in UK academia. Ghostwriting support focuses on creating original content tailored to each assignment. Writers avoid repetition, template-based writing, or generic material.
                        </p>
                        <p class="content-description">
                            Assignments are developed from scratch based on specific requirements, ensuring originality and academic integrity.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Learning Through Professionally Written Content
                        </h2>
                        <p class="content-description">
                            Ghostwriting is not just about submission; it is also a learning resource. Students can review professionally written <a href="/cheap-assignment-writing-help">cheap assignment help uk</a> to understand academic structure, argument development, and referencing style.
                        </p>
                        <p class="content-description">
                            This exposure helps students improve their own writing skills over time and gain confidence in academic communication.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Support Across Multiple Subjects
                        </h2>
                        <p class="content-description">
                            Ghostwriting assignment help in the UK covers a wide range of subjects, including humanities, management, social sciences, technical fields, and interdisciplinary areas. Writers with subject-specific knowledge ensure accuracy and relevance.
                        </p>
                        <p class="content-description">
                            This subject expertise allows assignments to reflect academic depth and contextual understanding.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Revision and Feedback Support
                        </h2>
                        <p class="content-description">
                            Academic assignments often require revisions based on feedback or updated instructions. Ghostwriting services provide flexibility for refinement, ensuring final content aligns with student expectations and academic guidelines.
                        </p>
                        <p class="content-description">
                            Revisions help improve clarity, argument strength, and presentation quality.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Reducing Academic Stress and Pressure
                        </h2>
                        <p class="content-description">
                            Academic stress can affect performance and well-being. Ghostwriting support helps reduce pressure by offering structured guidance and reliable academic assistance.
                        </p>
                        <p class="content-description">
                            Students can focus on understanding concepts and preparing for exams while receiving professional writing support for complex assignments.
                        </p>
                    </div>

                    <div class="content-box">
                        <h2>Conclusion
                        </h2>
                        <p class="content-description">
                            Ghostwriting Assignment Help UK provides confidential, research-driven, and academically structured support for students facing academic writing challenges. By focusing on clarity, originality, and academic standards, ghostwriting assistance helps students meet university expectations effectively.
                        </p>
                        <p class="content-description">
                            Rather than replacing learning, ghostwriting support enhances understanding by demonstrating how high-quality academic writing is developed. With proper guidance, students gain confidence, improve academic performance, and navigate university coursework more successfully.
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
                <h2 class="section-title">Frequently Asked Questions For Ghostwriting Assignment Help UK</h2>

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