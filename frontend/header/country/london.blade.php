@extends('frontend-layouts.app')

@section('content')


    {{-- create common-section --}}

    <x-common-section.hero title="Assignment Help London - High Quality with Affordable Prices"
        subtitle="Achieve A+ grades with our online assignment writing services London, supporting students across the UK. We deliver high-quality, 100% original, no-AI papers with a free plagiarism report and 24/7 expert academic assistance." />

    <x-common-section.services h1="How Our Assignment Help London Service Work For Students" step1="Submit Your Assignment"
        step1Content="Tell us what you need by filling out the 'Order Now' form. Include details like the topic, citation style, deadline, word count, and any other requirements."
        step2="Get The Price"
        step2Content="After assessing your assignment, we'll provide the final price for your paper. Be sure to check out our exclusive discounts on Assignment in Need Offers ."
        step3="Get Your Assignment"
        step3Content="Once we get your payment, we start working on your assignment right away. We'll deliver a well-written paper to you before the deadline." />

    <x-common-section.expert-section :expert="$data['expert']" :title="'Meets Our Native British Assignment Writers Help Services London'" />
    @include('components.common-section.promo-banner')


    <x-common-section.academic--writing-cards {{-- Type of Academic Writing Help We Provide in Uk --}}
        heading="Types of Assignment Writing Help Services in London"
        paragraph="You might find yourself searching for someone to write your essay in London if you’re studying at a college or university in the city. At Assignment in Need, we offer the best assignment writing services London, created specifically to meet the unique academic needs of students. Our range of assignment writing services in London is designed to help you improve your grades."
        :cards="[
            [
                'heading' => 'Assignment Writing Help London',
                'paragraph' =>
                    'Get affordable and the best assignment help London at Assignment in Need. You can buy assignment online London to avoid assignments dragging down your grades. With our expert help with assignments London, you get through research and precise writing to meet academic standards.',
            ],
            [
                'heading' => 'Homework Help London',
                'paragraph' =>
                    'If you have a part time job, there\'s a high chance all you think is “I want to pay someone to write my assignment London so that I can focus on other important parts of my life”. Well don\'t worry, we at Assignment in Need with our specialized team of homework writing help London experts, provide quick and effective solutions to manage your workload while achieving academic success.',
            ],

            [
                'heading' => 'Essay Help London',
                'paragraph' =>
                    'Our essay writing help London offer flexible and cost-effective options whether you are tackling a lengthy essay or simply need assistance in starting from scratch. With our cost effective essay writing help London experts you\'ll get well-crafted essays tailored to your needs.',
            ],

            [
                'heading' => 'Research Paper Help London',
                'paragraph' =>
                    'Our team of dedicated homework writers help London experts provide comprehensive support if you are someone who is facing time constraints or struggling with the complexities of a research paper.',
            ],

            [
                'heading' => 'Dissertation Help London',
                'paragraph' =>
                    'Our dissertation help services UK provide personalized guidance and support throughout the research process because sometimes creating a dissertation from scratch requires meticulous planning and expertise. Our dissertation help services London ensure your dissertation meets the highest standards of quality.',
            ],
        ]" />


    <x-common-section.benifits-of-assignment-cards title="Guaranteed Features of Assignment Help for Students in London, UK"
        :items="[
            [
                'icon' => 'fas fa-clock-o',
                'heading' => 'Round-the-Clock Support for London Students',
                'paragraph' =>
                    'Have an urgent query at midnight? Our 24/7 support team is ready to assist students across London anytime you need help.',
            ],

            [
                'icon' => 'fas fa-edit',
                'heading' => 'PhD Writers Covering London Universities',
                'paragraph' =>
                    'From Imperial College to University College London, our PhD experts specialize in delivering assignments tailored to your institution\'s expectations.',
            ],

            [
                'icon' => 'fas fa-headset',
                'heading' => 'Excelling Academically in London',
                'paragraph' =>
                    'Our tailored assignments help London-based students secure better grades and make a lasting impression on professors.',
            ],

            [
                'icon' => 'fas fa-ban',
                'heading' => 'Plagiarism-Free Assignments for London Academics',
                'paragraph' =>
                    'Receive assignments with guaranteed originality. We include plagiarism reports for full transparency and academic integrity.',
            ],

            [
                'icon' => 'fas fa-line-chart',
                'heading' => 'Tailored Learning Resources for London',
                'paragraph' =>
                    'Access additional materials and expert tips designed to help you grasp complex subjects and ace your London courses.',
            ],

            [
                'icon' => 'fas fa-lock',
                'heading' => 'Confidential & Secure Services for London Students',
                'paragraph' =>
                    'We prioritize your privacy by keeping all your data confidential and providing a safe, secure experience.',
            ],
        ]" />



    <x-common-section.check-out-subjects
        heading="Get Expert Assignment Help London in 200+ Subjects and Academic Disciplines" paragraph="" :subjects="[
            ['text' => 'Math', 'href' => '/math-assignment-help'],
            ['text' => 'Law ', 'href' => '/law-assignment-writing-help'],
            [
                'text' => 'Nursing',
                'href' => '/nursing-assignment-writing-help',
            ],
            [
                'text' => 'English',
                'href' => '/english-assignment-writing-help',
            ],
            [
                'text' => 'Accounting',
                'href' => '/accounting-assignment-writing-help',
            ],
            [
                'text' => 'Geography',
                'href' => '/geography-assignment-writing-help',
            ],
            [
                'text' => 'Computer Science ',
                'href' => '/computer-science-assignment-writing-help',
            ],
            ['text' => 'Chemistry ', 'href' => '/chemistry-assignment-writing-help'],
            [
                'text' => 'Marketing ',
                'href' => '/marketing-assignment-writing-help',
            ],
            [
                'text' => 'Business ',
                'href' => '/business-assignment-writing-help',
            ],
            [
                'text' => 'Programming ',
                'href' => '/programming-assignment-writing-help',
            ],
            [
                'text' => 'Management ',
                'href' => '/management-assignment-writing-help',
            ],
        ]" />


    @include('components.common-section.whatsapp')

    {{-- scrolling content --}}

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">
                <div class="column">
                    <div class="content-box">
                        <h2>Top Assignment Writing Help in London, UK: Assignment in Need</h2>
                        <p class="content-description">
                            It can be really frustrating to find reliable assignment writing services in London which are
                            specifically designed to fit your needs. But don't worry, and stop wasting your time. Hire
                            Assignment in Need! The best assignment help in London.
                            </br> We'll solve all your problems with our top-notch assignment writing help services in
                            London. Our dedicated team of experienced writers at Assignment in Need works really hard to
                            provide the best services so that you can sit back, relax, and focus on other important parts of
                            your academic life.
                        </p>

                    </div>

                    <div class="content-box">
                        <h2>Why Choose Our Assignment Writing Services in London?</h2>
                        <p class="content-description">We at Assignment in Need take away all of your academic stress with
                            our instant assignment help services in London and give you the peace of mind that you so well
                            deserve.
                            </br> Get plagiarism-free assignment papers and well-researched papers to boost your grades at
                            affordable prices that fit your budget.</p>

                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Step-by-Step Solution </li>
                            <li class="list-group-item">Quick Delivery Time</li>
                            <li class="list-group-item">Budget-Friendly Prices</li>
                            <li class="list-group-item">Custom Assignments</li>
                        </ul>

                    </div>

                    <div class="content-box">
                        <h2>Subject Areas Covered by Our Coursework Help London Experts</h2>
                        <p class="content-description">Here are the types of subjects <a
                                href="/coursework-writing-help"><b>coursework writing
                                    help</b></a> services for students in London</p>
                        <h3>Management Assignment Help</h3>
                        <p class="content-description">
                            Our team of experts simplify complex ideas and provides detailed explanations to help you with
                            all your management assignment help. Whether it's case study writing help, strategic analysis,
                            or research papers, they make it easier for you to understand and excel in your studies.
                        </p>
                        <h3>Computer Science Assignment Help</h3>
                        <p class="content-description">
                            Our team of experts can assist you with a variety of assignments to help with your computer
                            science assignment. Whether it is coding projects or technical reports, we ensure you grasp the
                            concepts and achieve higher grades.
                        </p>
                        <h3>Accounting Assignment Help</h3>
                        <p class="content-description">
                            With our accounting assignment help experts who provide the best assignment help services in
                            London, you can tackle your assignments with confidence. We provide accurate and
                            easy-to-understand support for topics like financial analysis, budgeting, accounting principles,
                            and much more.
                        </p>
                    </div>

                    <div class="content-box">

                        <h2>Our Unique Approach to Assignment Writing in London</h2>
                        <p class="content-description">We believe in providing assignment writing services that stand out.
                            That is the reason why we take a personalised approach where we understand your academic
                            requirements first and then match you with one of our experienced subject experts who'll assist
                            you all throughout your assignment journey:</p>

                        <h3>24/7 Customer Support:</h3>
                        <p class="content-description">Need to check on your assignment? Have an urgent question? Want to
                            place an order late at night? No problem! Our customer support team is available 24/7 to help
                            you anytime.</p>

                        <h3>Guaranteed On-Time Delivery</h3>
                        <p class="content-description">Worried about a deadline? Don't stress! Our writers know how
                            important it is to submit work on time, especially when it comes to a dissertation writing
                            service London. With Assignment in Need, you get on-time delivery where we send your completed
                            document well before the deadline, which gives you plenty of time to review it.</p>

                        <h3>Experienced PhD-Certified Writers</h3>
                        <p class="content-description">Our assignment writers are PhD-certified experts who have been
                            helping UK students with their assignments for a long time. They are great at research, write
                            excellent college papers in English, and are known for providing the best assignment writing
                            services London.</p>

                        <h3>High-Quality Work at Affordable Prices</h3>
                        <p class="content-description">Without compromising on quality, we offer assignment help in the UK
                            at reasonable prices. We promise that the work you receive will be top-notch assignment help.
                        </p>

                    </div>

                    <div class="content-box">
                        <h2>How Our London Assignment Help Enhances Your Academic Performance</h2>
                        <p class="content-description">Our university assignment help in London is designed to boost your
                            academic performance by providing the following:</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Academic Experts Guidance: We match you with knowledgeable writers
                                who understand your subject and academic requirements.</li>
                            <li class="list-group-item">Customised Assignment Support: We tailor our assistance to fit your
                                learning style and specific assignment needs.</li>
                            <li class="list-group-item">Quality Assurance: We ensure each assignment is well-researched,
                                structured, and free of errors.</li>
                            <li class="list-group-item">Learning Enhancement: You gain deeper insights into your subjects by
                                receiving clear explanations and well-crafted assignments, which improve your overall
                                understanding and grades.</li>
                        </ul>
                        </p>

                    </div>

                    <div class="content-box">
                        <h2>Affordable Assignment Help Without Compromising Quality</h2>
                        <p class="content-description">
                            We keep our prices low to make our services affordable because we understand that students often
                            face financial problems. We aim to provide quality assignments at fair prices that students can
                            afford.
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">
                                You need not pay dearly for quality.
                            </li>

                            <li class="list-group-item">
                                Professional writers form the core of our team to ensure that you get results without
                                draining your pocketbook.
                            </li>

                            <li class="list-group-item">
                                We'll write you any essay, research paper, or even project that'll be properly researched
                                and up to your requirements, all without breaking your wallet.
                            </li>
                        </ul>

                    </div>

                    <div class="content-box">
                        <h2>Boost Your Grade High With Our Best Assignment Writing Help In UK</h2>
                        <p class="content-description">College and university mean lots of homework. So getting assignment
                            writing services in London makes sense to handle writing challenges. Get top-notch papers for
                            high scores with our PhD-certified writers. Here's what we offer:</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">
                                Highly rated scholars ready to help
                            </li>

                            <li class="list-group-item">
                                No hidden pricing, only clear pricing
                            </li>

                            <li class="list-group-item">
                                Free revisions
                            </li>

                            <li class="list-group-item">
                                Guaranteed A-grade results
                            </li>
                        </ul>
                    </div>

                    <div class="content-box">
                        <h2>Best Exclusive Offers for Assignment Writing Service in London</h2>
                        <p class="content-description">
                            With our online assignment help in London, you not only get great services but also great
                            exclusive offers.
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Get up to 40% off based on your requirements!</li>
                            <li class="list-group-item">Get 1 assignment FREE of cost on ordering 5 assignments!</li>
                            <li class="list-group-item">Refer 4 friends and get a group project absolutely FREE!</li>
                        </ul>
                        <p class="content-description">
                            To know more about such offers, check out Assignment in Need Offers.
                        </p>
                    </div>

                </div>

                <div class="column">
                    <div class="content-box">
                        <h2>5 Reasons Why You Should Choose Assignment in Need</h2>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Top-Notch Help, Affordable Rates: You need not pay dearly for
                                quality. Professional writers ensure you get results without draining your pocketbook.</li>

                            <li class="list-group-item">Never Compromise on Standards: Our experts deliver well-structured,
                                plagiarism-free work that adheres to academic standards for excellence.</li>

                            <li class="list-group-item">Affordable for Every Student: With flexible pricing options,
                                discounts, and packages, you can access premium assignment help without the financial
                                strain.</li>

                            <li class="list-group-item">On-Time Delivery, Every Time: Deadlines mean everything, and we
                                ensure your work is completed and delivered right on the dot, every time.</li>

                            <li class="list-group-item">Your Success is Our Top Priority: When you choose us, it is not only
                                getting to pay someone to do your assignment written for you; it is having a partner
                                committed to your academic success.</li>
                        </ul>
                    </div>

                    <div class="content-box">
                        <h2>Quick Assignment Help for Tight Deadlines (4, 6, or 12 Hours)</h2>
                        <p class="content-description">
                            College life can be demanding, and assignments often get pushed aside. But there's no need to
                            stress-Assignment in Need is here to help! Our quick assignment writing service is tailored to
                            meet even the tightest deadlines with ease and professionalism.
                            </br> Whether you need an assignment done in 4, 6, or 12 hours, our experienced experts are
                            prepared to provide high-quality work, regardless of how urgent the task may be.
                            </br> Don't let deadlines control your grades. Place your order now with Assignment in Need and
                            protect your academic success at the last minute.
                        </p>

                    </div>

                    <div class="content-box">
                        <h2>How Do Our Writers Provide Exceptional Assignment Help?</h2>
                        <p class="content-description">
                            At Assignment in Need, we follow a streamlined process to ensure top-quality assignments from
                            start to finish.
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Understanding Your Requirements: Our writers examine every detail of
                                your specifications and instructions to ensure a clear understanding of the guidelines and
                                objectives.</li>

                            <li class="list-group-item">Custom Research & Writing: Your assigned writer conducts thorough
                                research, creates an outline, and crafts a well-structured, high-quality assignment tailored
                                to your needs.</li>

                            <li class="list-group-item">Quality Assurance & Delivery: Our QA team checks the finished work
                                for errors. The completed assignment is then sent to you before the deadline.</li>

                            <li class="list-group-item">Free Revisions: If you need any adjustments, let us know! Your
                                writer will revise the assignment to ensure you're 100% satisfied.</li>

                        </ul>
                    </div>
                    <div class="content-box">
                        <h2>London's Preferred Choice for Reliable Assignment Writing Assistance</h2>
                        <p class="content-description">
                            For assignment help in London, we are the students' go-to choice. We take care of tight
                            deadlines, challenging subjects, and difficult topics so that you do not have to face all this
                            stress.
                        </p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Exceptional Quality, Always: We give excellent work that makes an
                                impact. Your essays, reports, and research papers are well-researched, well-organised, and
                                100% original.</li>

                            <li class="list-group-item">Confidentiality You Can Trust: We respect your privacy and ensure
                                that all your assignments and personal information remain strictly confidential.</li>

                            <li class="list-group-item">All Assignments Receive Personal Attention: We provide individual
                                support to meet your needs, ensuring your work is a reflection of your requirements.</li>

                            <li class="list-group-item">Reliable Professional Always Available: We are just a click away and
                                ready to help you conquer any assignment, big or small.</li>

                        </ul>
                    </div>

                    <div class="content-box">
                        <h2>Our Mission: Providing Exceptional Dissertation and Homework Help in London</h2>
                        <p class="content-description">
                            We are here to ease your academic journey. Don't worry about submitting at the last minute of an
                            almost-tight deadline. We can handle dissertation, thesis writing help, and more-it'll all be
                            submitted just at the right time.
                        </p>

                        <h3>Why Students in London Trust Us:</h3>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Results-Oriented Approach: Our work is designed to boost your grades
                                and help you reach your long-term academic goals.</li>

                            <li class="list-group-item">Local Insight with a Global Perspective: Deep knowledge of the
                                standards in London's academia, along with international trends, ensures exceptional
                                quality.</li>

                            <li class="list-group-item">Step-by-Step Solutions: We tailor our help to suit your needs,
                                focusing on quality and relevance for every project.</li>

                        </ul>


                    </div>

                    <div class="content-box">
                        <h2>Beyond Pass Grades: Help for King's, UCL, and Imperial Students</h2>

                        <p class="content-description">
                            Studying in London means aiming higher. The academic bar at universities like Imperial College,
                            King's College London, and UCL is incredibly demanding. Our experts are here to bridge that gap.
                            We help students with papers that are ready for a London Distinction.
                        </p>

                        <ul class="custom-ordered-list">
                            <li class="list-group-item">We Know Their Rules: Our writers are familiar with the specific
                                marking schemes and research expectations of London's top faculties. This means your
                                assignment won't just follow general rules-it will meet their precise criteria.</li>

                            <li class="list-group-item">1st-Class Focus: We help you move past a 2:1 and aim for the top. We
                                focus on critical thinking, deep research, and presenting arguments in a sophisticated way
                                that high-ranking London institutions expect.</li>

                            <li class="list-group-item">Specialist Support: If your professor assigned a specific required
                                reading list or a unique formatting style (common in London), just send it to us, and we
                                make sure it’s followed perfectly.</li>

                        </ul>
                    </div>

                    <div class="content-box">
                        <h2>Designed for the London Rush: Quick Help for Busy Lives</h2>

                        <p class="content-description">
                            Life in London moves fast. We understand you might be spending hours commuting, working a
                            part-time job, or navigating a packed social calendar. Your time is precious. Our service is
                            built to be as quick and easy as possible, fitting around your schedule.
                        </p>

                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Commuter-Friendly Ordering: Place your order, talk to your expert,
                                and track your paper's progress right from your phone, perfect for when you’re on the Tube
                                or waiting between lectures.</li>

                            <li class="list-group-item">Urgent London Deadlines: Missed a deadline or just got a last-minute
                                instruction? Use our express service for urgent papers. We deliver high-quality work in the
                                short time frames London life often demands.</li>

                            <li class="list-group-item">We Handle the Hidden Costs: We save you time spent in the library,
                                correcting complex citations and fixing tricky grammar, giving you back hours every week to
                                focus on revision, rest, or your job.</li>

                        </ul>
                    </div>

                    <div class="content-box">
                        <h2>Industry-Ready Assignments: Experts in London Business and Law</h2>

                        <p class="content-description">
                            Studying in the financial and legal capital of the UK means your assignments need to feel
                            relevant to the real world. We connect you with experts who have professional backgrounds in
                            London's key sectors:
                        </p>

                        <ul class="custom-ordered-list">
                            <li class="list-group-item">City Finance: Get help with case studies that involve the London
                                Stock Exchange, UK tax law, and financial reporting standards used right here in the Square
                                Mile.</li>

                            <li class="list-group-item">UK Law & Government: Our writers are skilled in referencing UK
                                statutes and judicial precedents, ensuring your legal assignments are current and precise.
                            </li>

                            <li class="list-group-item">Tech & Digital: For coding or IT reports, we provide up-to-date
                                solutions relevant to the global tech scene thriving in London.</li>

                        </ul>
                    </div>

                    <div class="content-box">
                        <h2>Get Started Today for a Stress-free College Life!</h2>

                        <p class="content-description">
                            Your Success, Our Commitment. Let us be the partner you can rely on to turn academic challenges
                            into achievements. Get proofreading and editing writing help, or full assignment support.
                            </br>
                            Do not be overwhelmed by assignments. Contact us now and let our experts handle your
                            dissertation, homework, or any other academic challenge in front of you.
                        </p>
                    </div>


                </div>
            </div>
    </section>



    <x-common-section.faq heading="Frequently Asked Questions For Assignment Writing Help in the London, UK" :faqs="[
            [
                'text' => '1. Will my paper pass the AI-detection software used by UCL, King\'s, or LSE?',
                'paragraph' =>
                    'Yes, absolutely. We guarantee 100% human-written content. Our experts write everything from scratch, focusing on critical thinking and nuanced arguments that AI cannot replicate. This ensures your work passes all university-level AI and originality checks (like Turnitin\'s newer versions) with complete peace of mind.',
            ],

            [
                'text' => '2. How do I know the writer understands my specific London university\'s standards?',
                'paragraph' => 'We match your order with a writer who has a verified degree and experience in your specific subject area AND the UK academic system. For London universities, we prioritise experts who are familiar with the Russell Group\'s competitive marking rubrics to ensure your paper meets their elevated academic expectations, not just general standards.',
            ],

            [
                'text' => '3. What is your fastest turnaround time for students needing urgent assignment help in London?',
                'paragraph' =>
                    'We offer emergency assignment help with delivery in as little as 4 hours for shorter assignments. This is perfect for London students facing sudden deadlines, but we always recommend ordering with a little more time to give our experts the best chance to deliver a distinction-worthy paper.',
            ],

            [
                'text' => '4. I commute long distances in London. How can I easily communicate with my writer?',
                'paragraph' =>
                    'Our platform and support team are designed for London life on the go. You can chat with your assigned expert and our 24/7 support team instantly via our mobile site or WhatsApp. You\'ll get real-time updates and quick answers, meaning you can manage your assignment seamlessly, even during a long Tube journey.',
            ],

            [
                'text' => '5. Can you help me with a specialist topic like \'UK Financial Law\' or a \'City of London Case Study\'?',
                'paragraph' =>
                    'Yes. London is the capital of finance and law. We have subject-matter specialists (often former industry professionals or PhDs) specifically trained in complex UK legal, economic, and business assignments, ensuring your work includes current, region-specific references and case studies relevant to the City.',
            ],

            [
                'text' => '6. If my London professor asks for revisions, how quickly will you make the changes?',
                'paragraph' =>
                    'Our Unlimited Free Revision policy is fast. If your work requires adjustments based on your original instructions or tutor feedback, we prioritise those revisions immediately. Our goal is a swift turnaround to help you meet your resubmission deadline without extra stress.',
            ],

            [
                'text' => '7. I only need help with the methodology/citation section of my dissertation. Can I order just a partial assignment?',
                'paragraph' =>
                    'Absolutely. We offer flexible support. You can hire an expert just for Editing & Proofreading, Research & Literature Review, or to ensure your Harvard/APA citations are flawless according to British university guidelines. You only pay for the exact help you need.',
            ],

            [
                'text' => '8. What happens to my personal information after I submit my assignment?',
                'paragraph' =>
                    'Your privacy is guaranteed. We use encrypted servers and adhere to strict UK Data Protection laws (GDPR). Your identity, order details, and payment information are never shared with writers, universities, or third parties-ensuring 100% confidentiality.',
            ],
            [
                'text' => '9. Can I request a sample of work previously done for a student at a London university?',
                'paragraph' =>
                    'To protect client confidentiality, we cannot share work from specific universities. However, we provide access to topic-diverse, high-quality samples that demonstrate our standard of research, structure, and referencing. This allows you to judge the quality before you place your order.',
            ],
        ]" />


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const readMoreBtns = document.querySelectorAll('.read-more-btn');

            readMoreBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const textContainer = this.parentElement;
                    const partialText = textContainer.querySelector('.partial-text');
                    const fullText = textContainer.querySelector('.full-text');

                    if (fullText.style.display === 'none') {
                        fullText.style.display = 'block';
                        partialText.style.display = 'none';
                        this.innerText = 'Read Less';
                    } else {
                        fullText.style.display = 'none';
                        partialText.style.display = 'block';
                        this.innerText = 'Read More';
                    }
                });
            });
        });
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
    <script>
        var phone_number = window.intlTelInput(document.querySelector("#phone_number"), {
            separateDialCode: true,
            preferredCountries: ["gb"],
            hiddenInput: "full",
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });
    </script>
    <script>
        $(document).ready(function () {
            var county_code = $(".iti__selected-dial-code").text();
            $("#country_c").val(county_code);
        });


        $('.iti__selected-flag').on('DOMSubtreeModified', function () {
            var county_code = $(".iti__selected-dial-code").text();
            $("#country_c").val(county_code);
        });
    </script>
    <script>
        document.getElementById('orderForm').addEventListener('submit', function (event) {
            var files = document.getElementById('file-upload').files;
            var totalSize = 0;
            for (var i = 0; i < files.length; i++) {
                totalSize += files[i].size;
            }
            // Check if total size exceeds the limit (50 MB)
            if (totalSize > (50 * 1024 * 1024)) {
                // Prevent form submission
                event.preventDefault();
                // Clear selected files
                document.getElementById('file-upload').value = '';
                // Show SweetAlert popup
                Swal.fire({
                    icon: 'error',
                    title: 'File Size Exceeded',
                    html: 'Total file size should not exceed 50 MB.<br><br><button id="chooseFileAgain" class="btn btn-primary">Choose File Again</button>',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: false,
                    showConfirmButton: false
                });
                // Add event listener for the "Choose File Again" button
                document.getElementById('chooseFileAgain').addEventListener('click', function () {
                    // Clear selected files
                    document.getElementById('file-upload').value = '';
                    // Close the SweetAlert popup
                    Swal.close();
                });
            }
        });
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection