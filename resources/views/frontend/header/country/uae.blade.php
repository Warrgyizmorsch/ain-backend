@extends('frontend-layouts.app')

@section('content')


    <x-common-section.hero title="Avail the Best Assignment Help UAE by Assignment In Need" subtitle="" />


    <x-common-section.services h1="<span>Our Procedure</span>"
        p1="How Our Quality Assignment Writing Services Work in Assignment ?" step1="Submit Your Order"
        step1Content="Fill in the 'order now' form, mention your basic information and specific requirements that you want us to meet."
        step2="Make Secure Payment"
        step2Content="Pay an affordable price for the assignment help provided to you via our secure payment gateway that is fully protected from privacy infringements."
        step3="Receive Your Paper"
        step3Content="Get a high-quality assignment writing services by our expert writers within the given deadline and score better than your expectations." />

        
        <x-common-section.expert-section :expert="$data['expert']" />
        
        @include('components.common-section.promo-banner')
        

    <x-common-section.academic--writing-cards heading="Explore the Benefits of Our UAE Assignment Writing Services"
        paragraph="At Assignment in Need, we're proud to be the top assignment help services uae for students across the UAE seeking reliable and expert academic assistance. With a dedicated team of Dubai-based professionals, we provide a range of services designed to help you achieve academic success. Here's what makes us the go-to option for students:"
        :cards="[
            [
                'heading' => 'Local Expertise from Dubai-Based Writers',
                'paragraph' =>
                    'Our best assignment help uae writers are not only skilled in their fields but also understand the unique academic standards in the UAE. This ensures that your assignments are not just high-quality but also relevant to your specific educational environment.',
            ],
            [
                'heading' => 'Punctuality You Can Count On',
                'paragraph' =>
                    'We know how crucial it is to meet deadlines. That\'s why we guarantee that your assignments will be completed and delivered on time, giving you peace of mind and the ability to submit your work without any stress.',
            ],

            [
                'heading' => 'Around-the-Clock Support',
                'paragraph' =>
                    'Questions or concerns? Our customer support help in uae is available 24/7 to assist you. No matter when you need help, we\'re here to provide answers and support.',
            ],

            [
                'heading' => 'Original Content, Every Time',
                'paragraph' =>
                    'Plagiarism is a serious issue, and we take it very seriously. You can trust that the work we deliver is 100% original, written specifically for you, and free of any copied content.',
            ],

            [
                'heading' => 'Affordable for All Students',
                'paragraph' =>
                    'We believe that every student should have access to the best assignment help uae without breaking the bank. Our services are competitively priced to ensure they\'re accessible to everyone, regardless of their budget.',
            ],

            [
                'heading' => 'Your Privacy Matters',
                'paragraph' =>
                    'We prioritize your confidentiality. Your personal details and assignment information are kept completely secure and are never shared with third parties.',
            ],
            [
                'heading' => 'Free Revisions for Peace of Mind',
                'paragraph' =>
                    'We\'re committed to your satisfaction. If you need any changes to your assignment, our team is happy to make revisions at no extra charge. We want to ensure your work is flawless and ready for submission.',
            ],
        ]" />

    <x-common-section.check-out-subjects heading="Full Spectrum Of Subject Cover By Our Assignment Help Service"
        paragraph="Handling numerous assignments can be challenging, especially when each one demands careful attention and time. If you're struggling to keep up with your workload and are worried about meeting deadlines, you can pay someone to write your essay uae. Our uae assignment helper covers a vast range of subjects—over 100—all at budget-friendly rates. We cater to students at every academic level.
  Here's a glimpse of the subjects we cover:"
        :subjects="[
            ['text' => 'Philosophy', 'href' => '/philosophy-assignment-writing-help'],
            ['text' => 'Sociology', 'href' => '/sociology-assignment-writing-help'],
            ['text' => 'Nursing', 'href' => '/nursing-assignment-writing-help'],
            ['text' => 'Marketing', 'href' => '/marketing-assignment-writing-help'],
            ['text' => 'Finance', 'href' => '/finance-assignment-writing-help'],
            ['text' => 'Statistics', 'href' => '/statistics-assignment-writing-help'],
            ['text' => 'Accounting', 'href' => '/accounting-assignment-writing-help'],
            ['text' => 'Mathematics', 'href' => '/math-assignment-help'],
            ['text' => 'Psychology', 'href' => '/'],
            ['text' => 'English', 'href' => '/english-assignment-writing-help'],
            ['text' => 'Linguistic', 'href' => '/linguistic-assignment-writing-help'],
            ['text' => 'Management', 'href' => '/'],
            ['text' => 'Physics', 'href' => '/physics-assignment-writing-help'],
            ['text' => 'Science', 'href' => '/computer-science-assignment-writing-help'],
            ['text' => 'History', 'href' => '/history-assignment-writing-help'],
            ['text' => 'Business', 'href' => '/'],
            ['text' => 'Economics', 'href' => '/economic-assignment-writing-help'],
            ['text' => 'Geography', 'href' => '/geography-assignment-writing-help'],
            ['text' => 'Law', 'href' => '/law-assignment-writing-help'],
            ['text' => 'Chemistry', 'href' => '/law-assignment-writing-help'],
            ['text' => 'Programming', 'href' => '/programming-assignment-writing-help'],
        ]" />

    @include('components.common-section.whatsapp')

    <section class="page-section scrollables">
        <div class="scrollable-section">
            <div class="scrollable-container">
                <div class="column">
                    <div class="content-box">
                        <h2>UAE's No1 Assignment Help
                            and Writing Service Provider</h2>
                        <p class="content-description">At Assignment in Need, we pride ourselves on being one of the best providers of academic help. We
                            offer a comprehensive range of cheap assignment help uae, including essay writing help uae,
                            homework writing help uae, and <a
                                href="/dissertation-writing-help-services"><b>dissertation
                                    help services uae</b></a> for students at all academic levels. It doesn't matter where
                            you are studying in UAE, you can have access to the top academic help with Assignment in Need.
                        </p>
                        <p class="content-description">Our team includes over 1,000 highly qualified experts, including PhDs and Master's degree holders
                            across a wide range of subjects such as Business, Marketing, Nursing, Engineering, and more. We
                            ensure that every assignment is unique, plagiarism-free, and delivered on time.
                        </p>
                        <h3>Our Core assignment writing service UAE Include:
                        </h3>
                        <ul class="custom-ordered-list">
                        <li class="list-group-item">Assignment Writing</li>
                        <li class="list-group-item">ustom Essays</li>
                        <li class="list-group-item">Case Study Assistance</li>
                        <li class="list-group-item">Dissertation Writing</li>
                        </ul>

                    </div>

                    <div class="content-box">
                        <h2 >Why Do Students Seek
                            Assignment Help?</h2>
                        <p class="content-description">The academic landscape in the UAE is highly competitive. One of the significant aspects of the
                            academic curriculum in UAE universities is the inclusion of assignments. These assignments are
                            mandatory for students across all courses, which adds an extra burden to an already demanding
                            workload. Also, other factors like the complex subject matter, lack of time, and insufficient
                            understanding of assignment requirements impede a student's progress on assignments. As a
                            result, they find themselves overwhelmed and need professional assistance. This is where
                            Assignment In Need's assignment writing service UAE comes to the rescue. With our reliable
                            assignment help UAE, we offer students the support they need to excel in their studies.</p>

                    </div>

                    <div class="content-box">
                        <h2>Why are We Preferred for
                            the Assignment Writing Service in UAE?</h2>
                        <p class="content-description">If you're searching for reliable help with assignments UAE, particularly in Dubai, look no
                            further—we've become a trusted choice for students. Here's why so many learners turn to us and
                            spread the word to their classmates:
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Skilled Experts: </b> Our experienced uae assignment writers are equipped with the
                                expertise needed to tackle any academic challenge. </li>
                            <li class="list-group-item"><b>Customized Approach: </b> Every project is uniquely tailored to meet your exact
                                specifications. </li>
                            <li class="list-group-item"><b>Around-the-Clock Support: </b> No matter the time, our team is always on hand to help you
                                out.
                            </li>
                            <li class="list-group-item"><b>Comprehensive Services: </b> From essays to research papers writing help, we cover a
                                broad spectrum of academic needs. </li>
                            <li class="list-group-item"><b>Extra Resources:</b> Along with assignments, we offer helpful tools to elevate your
                                academic performance.
                            </li>
                        </ul>
                        </p>
                        <p class="content-description">Assignment in Need's dedicated uae assignment helper, you'll always have access to the top-tier
                            assistance you deserve, exactly when you need it.</p>
                        <h3>Common Challenges Faced by Students Seeking Assignment Help UAE</h3>
                        <p class="content-description">Writing impressive assignments is of utmost importance for students as it directly impacts their
                            academic performance and can significantly increase their scores. However, writing assignments
                            is often accompanied by numerous challenges. Most assignments given by UAE university professors
                            are complex and have intricate guidelines, making it difficult for students to meet
                            expectations. This gets even more difficult for students from abroad as they might face language
                            barriers, adding an extra layer of complexity to the assignment writing process. As a result,
                            students often turn to <a
                                href="/assignment-writing-help-services"><b>assignment
                                    writing help services</b></a> uae to ensure the timely submission of impressive and
                            authentic assignments.</p>

                    </div>

                    <div class="content-box">
                        <h2>Solutions For All Your
                            Academic Queries</h2>
                        <p class="content-description">You can pay someone to write your essay UAE using Assignment in Need. Our professional writers
                            are here to help with all your academic challenges. Here's how we make sure you're fully
                            prepared:
                        <ul class="custom-bullet-list">
                            <li class="list-group-item"><b>Direct Answers to Your Questions: </b> Get clear, concise answers to any academic query
                                you have.
                            </li>
                            <li class="list-group-item"><b>Reference Materials for Self-Study: </b> We provide valuable resources to help you study
                                more effectively.
                            </li>
                            <li class="list-group-item"><b>One-on-One Video Sessions: </b> Need a little extra help? Our experts are available for
                                personal tutoring sessions.
                            </li>
                            <li class="list-group-item"><b>Sample Papers for Guidance: </b> Use our sample papers to better understand how to
                                approach your assignments.</li>
                        </ul>
                        </p>
                        <p class="content-description">Many students have found our online coursework help uae invaluable, stating that their
                            preparation has become easier and more effective with our support. If you have any questions or
                            need assistance, our essay writing experts are here to help.</p>
                        <h3>Top Points to Remember While Choosing an Assignment Help UAE</h3>
                        <p class="content-description">The assignment help services uae provided by Assignment In Need ticks off all these points. We
                            have been helping countless students secure A+ scores. Our assignment help expert in UAE ensure
                            the assignments are well-researched, properly structured, and error-free. This is because we
                            understand the importance of meeting deadlines for students. Thus, we diligently deliver
                            assignments on time, relieving students of looming deadlines.
                        </p>
                        <h3>Do Not Just Take Our Word; Check Out Why You Should Choose Assignment In Need as Your Assignment
                            Help UAE.
                        </h3>
                        <p class="content-description">When choosing an assignment help expert in UAE, there are several key points to remember to
                            ensure a reliable and satisfactory experience.</p>
                        <ul class="custom-ordered-list">
                            <li class="list-group-item">Look for customer reviews, testimonials, and ratings to gain insights into the
                                experiences of other students.</li>
                            <li class="list-group-item">Check if the assignment help service has expert writers specializing in your field.</li>
                            <li class="list-group-item">Look for a service with a track record of delivering assignments within the agreed-upon
                                timeframes.</li>
                            <li class="list-group-item">Ensure guarantees of plagiarism-free content, error-free writing, and adherence to
                                academic standards.</li>
                            <li class="list-group-item">Consider if they offer proofreading and editing services to enhance the quality of your
                                assignment.</li>
                            <li class="list-group-item">Each assignment is unique, and choosing a personalized assistance service is important.
                            </li>
                            <li class="list-group-item">Compare the pricing of different assignment help services. Look for transparent pricing
                                policies without hidden charges.
                            </li>
                            <li class="list-group-item">Ensure the service provider offers reliable and accessible communication channels for
                                addressing concerns or queries.
                            </li>
                            <li class="list-group-item">Assignment help services should prioritize confidentiality and secure your personal info
                                and academic details. Check if they have a strict privacy policy in place.
                            </li>
                        </ul>
                        

                    </div>

                    <div class="content-box">
                        <h2>Get Ready To Get UAE
                            Assignment Help From Online Expert Writing Service & Remove All Your Troubles Once and For All!
                        </h2>
                        <p class="content-description">Struggling to keep up with your assignments in the UAE? You're not alone! Whether you're a
                            student balancing studies and part-time jobs or just trying to tackle a tough subject,
                            assignments can pile up and feel overwhelming. But don't worry—there's a solution!</p>
                        <p class="content-description">With expert UAE assignment help from an assignment writing service uae, you can wave goodbye to
                            all your academic worries and pay someone to do my assignment uae. Our team of professionals is
                            here to offer you top-notch, customized support that caters to your specific needs. Whether it's
                            essays, <a href="/research-paper-writing-services"><b>research
                                    paper writing help</b></a>, or case studies, we've got you covered.</p>

                        <p class="content-description">Why choose online assignment help? Because it saves you time, reduces stress, and ensures
                            high-quality, plagiarism-free work. Plus, you'll have the peace of mind knowing that experienced
                            writers are handling your project, helping you secure the grades you've always aimed for.</p>
                        <p class="content-description">Don't let academic pressure weigh you down any longer. Get ready to ace your assignments with
                            best online assignment helper in uae and eliminate your troubles once and for all.</p>

                    </div>

                    <div class="content-box">
                        <h2>Most of the Students in the
                            Dubai, Search for Assignment Help Online Service to Get Higher Grades</h2>
                            <ul class="custom-bullet-list">
                        <li class="list-group-item"><b>Budget-Friendly: </b> We know students often have limited funds, so we keep our prices
                            affordable without compromising on quality.</li>
                        <li class="list-group-item"><b>100% Unique Content: </b> We guarantee originality, ensuring your work is free from
                            plagiarism.
                        </li>
                        <li class="list-group-item"><b>Top-Quality Solutions:</b> Our experienced experts have been assisting UAE students for years,
                            consistently delivering top-tier, custom assignments.</li>
                            </ul>
                    </div>

                </div>

                <div class="column">

                    <div class="content-box">
                        <h2 >Expert Assignment Writing
                            Services Meets Deadlines Consistently</h2>
                        <p class="content-description">Over the years, we at Assignment in Need have never missed a deadline. Whether you need
                            last-minute help with assignments uae or a detailed project, our team is equipped to handle it
                            all. Once you place your order through our secure payment portal, you'll be assigned a writer
                            who will begin working on your assignment right away.</p>
                        <p class="content-description">Our uae assignment helper has access to extensive resources, making research quick and efficient.
                            Plus, we use advanced tools to ensure your assignment is grammatically correct and
                            plagiarism-free, all while maintaining a fast turnaround time.</p>
                        <p class="content-description">So, if you're looking for dependable help in uae that won't let you down, trust us to deliver
                            quality work on time, every time.</p>

                    </div>


                    <div class="content-box">
                        <h2 >Get Your Difficult Essay
                            Finished By Our Skilled Writers</h2>
                        <p class="content-description">At Assignment In Need, we not only turn essay writing from a daunting task into a seamless
                            experience but also provide the best essay proofreading service. So if you are looking for the
                            best website to pay someone to write your essay uae. Here's, Assignment in Need is here. how our
                            cheap <a href="/essay-writing-help-services"><b>essay writing
                                    help</b></a> can make it simple:</p>
                                    <ul class="custom-bullet-list">
                        <li class="list-group-item"><b>Effortless Essay Creation: </b> Share your essay topic and objectives with us, and watch as
                            our essay paper writer transforms your ideas into a compelling essay. You provide the vision; we
                            handle the details.
                        </li>
                        <li class="list-group-item"><b>Tailored Assistance for Every Need: </b> Whether you're juggling multiple assignments or just
                            need a boost, our team is equipped to handle any essay challenge, you just have to pay someone
                            to write your essay uae. From creative brainstorming to precise editing, we offer a full range
                            of services to meet your needs.
                        </li>
                        <li class="list-group-item"><b>Quick Turnaround with a Personal Touch: </b> We value your time and aim to deliver the best
                            essay writing services quickly without sacrificing quality. Our writers work efficiently while
                            ensuring that each essay reflects your unique voice and requirements.
                        </li>
                        <li class="list-group-item"><b>Real-Time Collaboration: </b> Stay involved in the process with our real-time updates and
                            feedback system. From the moment you ask us to “write a essay for me”, you can communicate
                            directly with your writer to make adjustments and ensure the final product aligns perfectly with
                            your vision.</li>
                        <li class="list-group-item"><b>Beyond the Basics: </b> We go beyond standard essay writing help uae to offer additional
                            resources like research tips, formatting guidance, and citation help. Our goal is to not only
                            provide great essay writing help but also empower you with skills for future success.
                        </li>
                        </ul>
                    </div>


                    <div class="content-box">
                        <h2 >Reliable UAE Assignment
                            Help Service with Plagiarism Free Guarantee</h2>
                        <p class="content-description">At Assignment in Need, we understand the importance of submitting original and easy and unique
                            assignments. Plagiarism is a serious academic offense, and we're committed to helping you avoid
                            it. Here's how our help in uae guarantee plagiarism-free assignment help services uae:</p>
                        <h3>Thorough Research:</h3>
                        <p class="content-description">Our writers conduct extensive research to ensure your assignment is well-informed and up-to-date
                            with the latest academic standards. This allows them to produce content that is not only unique
                            but also insightful and relevant to your field of study.</p>
                        <h3>Advanced Plagiarism Checks:</h3>
                        <p class="content-description">Before any assignment is delivered to you, it undergoes rigorous plagiarism detection using
                            advanced software tools. This process helps us identify and eliminate any traces of copied
                            content, ensuring that your work is 100% original.</p>
                        <h3>Proper Citation and Referencing:</h3>
                        <p class="content-description">One of the key elements of avoiding plagiarism is correctly citing sources. Our assignment
                            helpers are well-versed in various citation styles, including APA, MLA, Harvard, and Chicago.
                            They meticulously reference all sources used in your assignment, giving credit where it's due
                            and ensuring your work is free from plagiarism.</p>
                        <h3>Quality Assurance Team:</h3>
                        <p class="content-description">In addition to the efforts of our writers, we have a dedicated quality assurance team that
                            reviews every assignment before it reaches you. They double-check for originality, adherence to
                            guidelines, and overall quality, ensuring that you receive a flawless, plagiarism-free paper.
                        </p>
                        <h3>Your Academic Integrity Matters:</h3>
                        <p class="content-description">We take your academic integrity seriously when you ask us to “write my assignment uae.” By
                            choosing our online coursework help uae, you can be confident that your assignments will be
                            unique, well-researched, and completely free of plagiarism.</p>
                        <p class="content-description">Buy assignment online uae from Assignment in Need and trust our uae assignment writers to deliver
                            the best assignment help and original work that meets your academic standards. When you work
                            with us, you can focus on learning and achieving your goals without the worry of plagiarism
                            holding you back.</p>

                    </div>


                    <div class="content-box">
                        <h2>We Will Assist with Any
                            Type of Homework</h2>
                        <p class="content-description">Studying in the UAE comes with its unique set of challenges, especially for international
                            students. The academic journey can be tough, but the best assignment help uae - Assignment in
                            Need offers a lifeline, ensuring that students stay on track and reach their academic
                            milestones. Here's how Assignment in Need <a
                                href="/homework-writing-help-services"><b>Homework
                                    help</b></a> is a game-changer and why you should buy assignment online uae:</p>
                        <p class="content-description">Obstacles International Students Face in UAE:</p>
                        <h3>Adjusting to a Multicultural Setting</h3>
                        <p class="content-description">UAE's vibrant mix of cultures brings students from all corners of the globe. While this diversity
                            is exciting, it can also make settling into academic life tricky. Homework help and Assignment
                            help services provide much-needed support, easing students into this new environment and helping
                            them thrive in their studies.</p>
                        <h3>Language Difficulties</h3>
                        <p class="content-description">For those unfamiliar with the local dialects or struggling with different accents, keeping up
                            with lectures and reading materials can be a real struggle. Homework help and assignment writing
                            service uae can help by offering personalized help, breaking down difficult topics, and ensuring
                            that students submit well-written, clear assignments.</p>
                        <h3>Overwhelming Workload</h3>
                        <p class="content-description">Balancing multiple projects at once can quickly turn into a stressful ordeal. With deadlines
                            looming and a heavy workload, it's easy for students to feel buried. But when you take help with
                            assignments uae, you get access to the best dissertation help services uae, essay writing help
                            uae, and homework writing help uae which deliver top work at reasonable prices, allowing
                            students to focus on other aspects of their academic journey.</p>
                        <h3>Varied Education Systems</h3>
                        <p class="content-description">UAE's academic structure often follows British or American models, which can be unfamiliar to
                            many students. This can create confusion when it comes to meeting assignment standards and
                            expectations. With expert guidance from assignment services, students can better understand
                            these requirements and ensure their work aligns with their institution's standards.</p>
                        <h3>Complex and Diverse Curriculums
                        </h3>
                        <p class="content-description">Many institutions in Dubai follow international curricula, which can add another layer of
                            complexity for students unfamiliar with these programs. Homework help employ specialists who are
                            well-versed in these curriculums and can provide tailored support to help students succeed.</p>
                    </div>
                    <div class="content-box">
                        <h2 >Are You Studying at One of
                            the Top Universities in the UAE?</h2>
                        <p class="content-description">If you're studying at one of the top universities in the UAE, you already know how competitive
                            and demanding academics can be. With high expectations from professors and the pressure to
                            excel, managing coursework, assignments, and extracurricular activities can become overwhelming.
                            But you don't have to face these challenges alone!</p>
                        <p class="content-description">At our online coursework help UAE, we specialize in supporting students like you, who are
                            striving to achieve academic excellence at renowned universities. Our team of expert UAE
                            assignment helpers is here to assist you with any assignment, from essays to complex research
                            projects.</p>
                        <p class="content-description">We understand the academic standards of the UAE's leading universities and ensure that all
                            assignments are meticulously crafted to meet your university's requirements. So, if you ever
                            feel the workload piling up, know that you have a reliable partner ready to help you succeed.
                        </p>
                        <h3>Why Choose Assignment In Need?</h3>
                        <p class="content-description">Assignment in Need is dedicated to providing the best assignment writing service uae. Here's how
                            we support you in achieving academic success:</p>
                            <ul class="custom-ordered-list">
                        <li class="list-group-item"><b>Expert Writers:</b> We possess a team of experienced assignment help expert in UAE who
                            specialize in various disciplines and can provide essay writing help uae, dissertation help
                            services uae, homework writing help uae, and much more more. They possess in-depth knowledge and
                            understanding of the UAE educational system, ensuring that your assignments meet your
                            institution's specific requirements and standards.</li>
                        <li class="list-group-item"><b>Customized Solutions: </b> The experts at Assignment in Need offer personalized solutions
                            tailored to your unique assignment needs. We conduct thorough research, analyze the topic, and
                            create well-structured, original, and insightful assignments that showcase your understanding of
                            the subject matter.</li>
                        <li class="list-group-item"><b>Timely Delivery: </b> Meeting deadlines is crucial, and Assignment In Need understands the
                            importance of timely submissions. Our dedicated team works diligently to deliver your
                            assignments within the specified timeframe, allowing you to submit your work on time and avoid
                            mark deductions.
                        </li>
                        <li class="list-group-item"><b>Affordable Services: </b> We understand the financial constraints faced by students. We
                            have priced our services affordably to be accessible to all. Our assignment help in UAE does not
                            compromise on the quality of assistance provided, ensuring value for your investment.
                        </li>
                            </ul>
                            <p class="content-description">So, what are you waiting for? Do not let your homework stress you out—contact Assignment in Need
                            today for the best assignment help in UAE. Trust the experts at Assignment in Need to provide
                            you with the assistance you need to excel in your studies!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <style>
    .scrollables {
        max-width: 1600px;

    }

    .content-box h2 {
        font-size: 24px;
        font-weight: 600;
        color: #110000;
        text-align: center;
        padding-bottom: .5rem;
    }

    .content-box h3 {
        color: #110000;
        text-align: left;
        font-size: 18px;
        font-weight: 600;
        padding-bottom: .5rem;
    }

    .expert-btn {
        display: inline-block;
        background: linear-gradient(135deg, #5e2ced, #4821c3);
        color: white;
        width: auto;
        padding: 8px 18px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .expert-btn: hover {
        transform: translateY(-5px);
        background: linear-gradient(135deg, #4821c3, #35189f);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }

    /* Wrapper to contain the scrollable section */
    .scrollable-section {
        width: 90%;
        height: 500px;
        /* Adjust as needed */
        overflow-y: auto;
        border-top: 5px solid #4821c3;
        border-bottom: 5px solid #4821c3;
        border-radius: 10px;
        padding: 20px;
        margin: auto;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Flex container for two columns */
    .scrollable-container {
        display: flex;
        gap: 20px;
    }

    /* Individual column styles */
    .column {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Content boxes */
    .content-box {
        background: whitesmoke;
        color: black;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 6px rgba(94, 47, 237, .6);
        text-align: center;
    }

    .content-description {
        font-size: 14px;
        text-align: justify;
    }

    .custom-bullet-list {
        list-style: none;
        /* Removes default bullets */
        margin-top: 20px;
        padding-left: 0;
    }

    .custom-bullet-list .list-group-item {
        position: relative;
        /* Needed for absolute bullet */
        padding-left: 30px;
        /* Space for custom bullet */
        margin-bottom: 10px;
        font-size: 14px;
        font-weight: normal;
        color: #333;
        border: none;
        border-radius: .4rem;
        text-align: left;
    }

    .custom-bullet-list .list-group-item::before {
        content: "•";
        /* Unicode bullet character */
        position: absolute;
        left: 10px;
        top: 11px;
        color: #4821c3;
        /* Bullet color */
        font-size: 20px;
        /* Bullet size */
        font-weight: bold;
    }


    .custom-ordered-list {
        list-style: none;
        /* Removes default numbering */
        padding-left: 0;
        counter-reset: list-counter;
        /* Reset custom counter */
        margin-top: 20px;
    }

    .custom-ordered-list .list-group-item {
        position: relative;
        counter-increment: list-counter;
        /* Increments counter */
        padding-left: 30px;
        /* Space for custom number */
        margin-bottom: 10px;
        font-size: 14px;
        border: none;
        border-radius: .4rem;
        text-align: left;
    }

    .custom-ordered-list .list-group-item::before {
        content: counter(list-counter) ". ";
        position: absolute;
        left: 11px;
        color: #4821c3;
        /* Custom number color */
        font-weight: bold;
    }


    /* Responsive for small screens */
    @media (max-width: 768px) {
        .scrollable-section {
            width: 100%;
        }

        .content-box .content-description {
            font-size: 12px;
        }

        .custom-bullet-list .list-group-item {
            font-size: 12px;
            padding: ;
        }

        .scrollable-container {
            flex-direction: column;
        }
    }
</style>



    



    <x-common-section.faq heading="Frequently asked questions" :faqs="[
        [
            'text' => '1. What types of assignments can you help within the UAE?',
            'paragraph' =>
                'We provide assistance with a wide range of assignments for UAE students, including essays, research papers, case studies, dissertations, and lab reports. Our expert writers cover various subjects to meet your academic needs.',
        ],

        [
            'text' => '2. Are there any specific academic standards for assignments in the UAE?',
            'paragraph' =>
                'Yes, assignments in the UAE often have specific formatting and citation requirements based on local academic institutions\' guidelines. Providing clear instructions helps us ensure that your work adheres to these standards.',
        ],

        [
            'text' => '3. How do I ensure my homework is completed accurately in the UAE?',
            'paragraph' =>
                'To ensure your homework is completed accurately, provide detailed instructions and any specific guidelines when placing your order. Our UAE-based writers will follow these instructions closely to deliver high-quality work.',
        ],

        [
            'text' => '4. What is the process for getting help with a research paper in the UAE?',
            'paragraph' =>
                'For research paper assistance, submit your topic, research requirements, and deadline through our platform. We will assign a writer with expertise in your field to conduct thorough research and produce a well-structured paper that meets UAE academic standards.',
        ],

        [
            'text' => '5. Can I get help with essay writing in the UAE?',
            'paragraph' =>
                'Yes, we offer comprehensive essay writing help in the UAE. Whether you need help with brainstorming, drafting, or editing, our experienced writers will assist you in creating a compelling and well-organized essay.',
        ],

        [
            'text' => '6. What should I do if I need urgent assignment help in the UAE?',
            'paragraph' =>
                'If you need urgent assignment help, contact us immediately. We offer expedited services to ensure your assignment is completed on time, even under tight deadlines, while maintaining high quality.',
        ],

        [
            'text' => '7. How can I request revisions for my assignment or essay in the UAE?',
            'paragraph' =>
                'You can request revisions through our platform if you need changes to your assignment or essay. We offer free revisions to ensure the final work meets all your expectations and academic requirements.',
        ],

        [
            'text' => '8. How can I track the progress of my homework, assignment, or research paper?',
            'paragraph' =>
                'You can track the progress of your homework, assignment, or research paper through our secure platform, which provides regular updates. Additionally, you can communicate directly with your writer for any additional instructions or updates.',
        ],
    ]" />



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
        $(document).ready(function() {
            var county_code = $(".iti__selected-dial-code").text();
            $("#country_c").val(county_code);
        });


        $('.iti__selected-flag').on('DOMSubtreeModified', function() {
            var county_code = $(".iti__selected-dial-code").text();
            $("#country_c").val(county_code);
        });
    </script>
    <script>
        document.getElementById('orderForm').addEventListener('submit', function(event) {
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
                document.getElementById('chooseFileAgain').addEventListener('click', function() {
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
