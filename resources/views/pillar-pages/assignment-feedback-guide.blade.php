@extends('frontend-layouts.app')

@section('content')
    <style>
        /* TYPOGRAPHY FOR THIS PAGE ONLY */
        h1 {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 0.5rem;
        }

        h2 {
            font-size: 28px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 0.5rem;
        }

        h3 {
            font-size: 22px;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 0.5rem;
        }

        h4 {
            font-size: 20px;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 0.5rem;
        }

        p {
            font-size: 17px;
            line-height: 1.2;
            margin-bottom: 0.5rem;
        }

        .mb10 {
            margin-bottom: 1.8rem;
        }

        .mt10 {
            margin-top: 1.8rem;
        }

        .custom-bullet {
            list-style: none;
            padding-left: 0;
            margin-left: 0;
        }

        .custom-bullet li {
            position: relative;
            padding-left: 15px;
        }

        .custom-bullet li::before {
            content: "•";
            position: absolute;
            left: 0;
            top: 2px;
            font-size: 18px;
            line-height: 1;
            font-weight: bold;
            color: #000;
        }


        /* responsive tablets */
        @media(max-width: 768px) {
            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 24px;
            }

            h3 {
                font-size: 20px;
            }

            h4 {
                font-size: 18px;
            }

            p {
                font-size: 16px;
            }
        }

        /* responsive mobile */
        @media(max-width: 480px) {
            h1 {
                font-size: 24px;
            }

            h2 {
                font-size: 22px;
            }

            h3 {
                font-size: 18px;
            }

            h4 {
                font-size: 17px;
            }

            p {
                font-size: 15px;
            }
        }
    </style>

    <style>
        .pillar-hero {
            padding: 60px 20px;
            background: #f6f6f6;
        }

        .pillar-hero h1 {
            margin-bottom: 20px;
        }

        .pillar-hero p {
            color: #444;
            max-width: 900px;
        }

        /* MAIN WRAP */
        .pillar-main-wrap {
            display: flex;
            gap: 20px;
            padding: 30px 20px;
            align-items: flex-start;
            margin: auto;
        }

        /* SIDEBAR */
        .pillar-sidebar {
            width: 280px;
            position: sticky;
            top: 200px;
            flex-shrink: 0;
            height: max-content;
        }

        .pillar-sidebar-box {
            /* background: #f1f1f1; */
            /* border: 1px solid #ddd; */
            /* border-radius: 10px; */
            /* padding: 15px; */
        }

        .pillar-sidebar-box h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .pillar-sidebar-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
            line-height: 1.6;
        }

        .pillar-sidebar-box ul li {
            border-left: 4px solid #e5e5e5;
            padding: 10px 10px;
            cursor: pointer;
            transition: 0.2s;
        }

        .pillar-sidebar-box ul li:hover {
            background: #f7f7f7;
            border-left-color: blue;
            text-decoration: underline;
            text-decoration-color: blue;
        }

        .pillar-sidebar-box ul li:hover a {
            color: blue;
        }

        .pillar-sidebar-box ul li.active {
            background: #eef2ff;
            border-left-color: blue;
            text-decoration: underline;
            text-decoration-color: blue;
        }

        .pillar-sidebar-box ul li.active a {
            color: blue;
        }

        .pillar-sidebar-box ul li a {
            color: #000;
        }

        .pillar-sidebar-box a {
            text-decoration: none;
            color: #000;
            transition: 0.2s;
        }

        .pillar-sidebar-box a:hover {
            color: blue;
        }

        /* CONTENT */
        .pillar-content {
            flex: 1;
            min-width: 0;
            max-width: 820px;
        }

        .pillar-content h2 {
            /* margin-bottom: 15px; */
        }

        .pillar-hero-grid {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .pillar-hero-text {
            flex: 1;
        }

        .pillar-hero-image {
            flex: 0 0 350px;
        }

        .pillar-hero-image img {
            width: 100%;
            border-radius: 10px;
            display: block;
            object-fit: cover;
        }

        .pillar-hero-btns {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .pillar-btn {
            display: inline-block;
            padding: 6px 12px;
            border: 2px solid blue;
            border-radius: 6px;
            color: blue;
            font-weight: 500;
            text-decoration: none !important;
            transition: 0.3s ease;
            font-size: 16px;
        }

        .pillar-btn:hover {
            background: blue;
            color: #fff;
        }

        .pillar-btn-bg {
            display: inline-block;
            padding: 6px 12px;
            border: 2px solid blue;
            border-radius: 6px;
            font-weight: 500;
            text-decoration: none !important;
            transition: 0.3s ease;
            font-size: 16px;
            background: blue;
            color: #fff;
        }

        .pillar-btn-bg:hover {
            color: blue;
            background: transparent;
        }

        .pillar-btn-1 {
            background: #d7e5e6; /* same soft green/blue tone like sample */
            padding: 30px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin: 30px 0;
        }

        .pillar-btn-1 p {
            font-size: 18px;
            line-height: 1.4;
            font-weight: 400;
            margin: 0;
        }

        .pillar-btn-1 .pillar-hero-btns {
            flex-shrink: 0;
        }

        /* responsive */
        @media(max-width:768px){
            .pillar-btn-1 {
                flex-direction: column;
                text-align: left;
                align-items: flex-start;
            }
        }

        .pillar-ul {
            padding-left: 0;
            list-style: none;
            line-height: 1.2rem;
            /* margin-top: 1rem; */
            /* margin-bottom: 1rem; */
        }

        .pillar-ul li {
            font-size: 15px;
            line-height: 1.2;
            margin-bottom: 0.5rem;

        }


        /* responsive */
        @media(max-width: 992px) {
            .pillar-hero-grid {
                flex-direction: column;
                text-align: left;
            }

            .pillar-hero-image {
                flex: 0 0 auto;
                width: 100%;
            }

            .pillar-sidebar {
                display: none;
            }
        }


        /* RESPONSIVE */
        @media(max-width:1024px) {
            .pillar-main-wrap {
                gap: 15px;
            }
        }

        @media(max-width:768px) {
            .pillar-main-wrap {
                flex-direction: column;
            }

            .pillar-sidebar {
                width: 100%;
                position: static;
                top: auto;
            }

            .pillar-content {
                width: 100%;
                max-width: 100%;
            }
        }

        @media(max-width:480px) {


            .pillar-hero {
                padding: 60px 15px;
            }
        }

        @media only screen and (max-width: 1340px) {
            .page-wrapper {
                overflow: visible;
            }
        }
    </style>

    <style>
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .table-style-two {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px; /* makes table readable */
        }

        .table-style-two th,
        .table-style-two td {
            border: 1px solid #e1e1e1;
            padding: 10px 12px;
            font-size: 16px;
        }

        .table-style-two th {
            background: #f6f6f6;
            font-weight: 600;
        }

    </style>
    {{-- HERO SECTION --}}
    <section class="pillar-hero">
        <div class="container pillar-hero-grid">

            <div class="pillar-hero-text">
                <h1>The Definitive Guide to Effective Feedback and Assignment Redrafting</h1>
                <p>
                    Receiving your marked assignment back can be a moment of anxiety for any student. But that document, covered in your marker's comments, is the single most valuable tool you have for raising your grade from a 2:2 to a <a href="/blog/how-to-structure-a-21-standard-assignment-for-uk-universities">2:1</a>, or a 2:1 to a First.
                    <br>
                    The problem? Most students don't know how to use it.
                    <br>
                    Academic feedback is a specialised language. Tutors use it to provide a roadmap, but students often only see a critique. This guide will teach you how to decode that language, turn it into an actionable plan, and effectively redraft your work to <a href="/blog/how-to-write-great-college-assignments-to-get-good-grades">secure a higher mark</a>.
                </p>
                
                {{-- BUTTONS UNDER HERO --}}
                <div class="pillar-hero-btns">
                    <a href="/" class="pillar-btn-bg">Request Assignment Help</a>
                    <a href="/writers" class="pillar-btn">Get Expert Help</a>
                </div>
            </div>

            <div class="pillar-hero-image">
                <img src="/images/pillar-pages/hero/The Definitive Guide to Effective Feedback and Assignment Redrafting.webp" alt="Mastering Referencing & Citation Styles for UK Students"
                    loading="lazy">
            </div>

        </div>
    </section>

    {{-- MAIN WRAP --}}
    <section class="container">
        <div class="pillar-main-wrap">

            {{-- sidebar --}}
            <aside class="pillar-sidebar">
                <div class="pillar-sidebar-box pillar-toc">
                    <h3>Assignment Feedback Guide - Overview</h3>
                    <ul>
                        <li><a href="#section-1">Decoding the Marker's Language</a></li>
                        <li><a href="#section-2">Applying Feedback for Immediate Grade Improvement</a></li>
                        <li><a href="#section-3">Feedback Loops and Dialogue</a></li>
                        <li><a href="#section-4">Transforming Feedback into a First-Class Final Draft</a></li>                        
                        <li><a href="#faq">Assignment Feedback Guide FAQs</a></li>
                    </ul>
                </div>
            </aside>


            {{-- main content --}}
            <div class="pillar-content">

                {{-- section 1 --}}
                <section id="section-1" style="margin-bottom:40px;">
                    <h2>Decoding the Marker's Language</h2>
                    <p class="mb10">Before you can act, you must understand. Markers often use shorthand phrases to diagnose common (and major) issues in <a href="/academic-assignment-writing-help-service">academic work</a>.</p>

                    <h3>The Hidden Meaning Behind UK Grading Terminology</h3>
                    <p class="mb10">
                        Your feedback isn't personal; it's a <a href="/blog/important-things-to-remember-while-writing-an-assignment">technical assessment</a>. Top university guides (like those from Sheffield and Bath) consistently show that students struggle most with vague terms. Here’s what they really mean:
                    </p>
                    
                    <ul class="pillar-ul mb10">
                        <li>
                            <b>1. When your marker says: </b>"Too descriptive" or "Lacks description."
                            <ul class="pillar-ul custom-bullet">
                                <li><b>What it really means: </b>You have simply listed facts, quotes, or theories without explaining why they matter or how they answer the question. This is the most common barrier to breaking past a 2:2 (Lower Second).</li>
                                <li><b>What to do: </b>For every point you make, apply the "So what?" test. Add a sentence that explains the implication or significance of your evidence.</li>
                            </ul>
                        </li>
                        <li>
                            <b>2. When your marker says: </b>"Needs more criticality" or "Lacks <a href="/uk/critical-essay-writing-help-online">critical analysis</a>."
                            <ul class="pillar-ul custom-bullet">
                                <li><b>What it really means: </b>You've accepted what you've read at face value. You haven't questioned the author's argument, compared conflicting theories, or evaluated the strengths and weaknesses of the evidence. This is the key skill required for a 2:1 (Upper Second) or a First.</li>
                                <li><b>What to do: </b>Introduce academic debate. Use phrases like, "However, this view is challenged by..." or "While this model is useful, it fails to account for..."</li>
                            </ul>
                        </li>
                        <li>
                            <b>3. When your marker says: </b>"Inconsistent" or "Weak structure."
                            <ul class="pillar-ul custom-bullet">
                                <li><b>What it really means: </b>Your argument is hard to follow. Your paragraphs may be in the wrong order, or your introduction doesn't match your conclusion. This confuses the reader and hides the value of your research.</li>
                                <li><b>What to do: </b>Create a "reverse outline." Read your <a href="/essay-writing-help-services">essay</a> and write down the one main point of each paragraph. If you can't, or if the points don't flow logically, your <a href="/blog/how-to-structure-a-cover-letter-with-example">structure</a> is the problem.</li>
                            </ul>
                        </li>
                    </ul>
                    
                    <h3>Categorising Feedback: Structure, Content, and Presentation</h3>
                    <p>
                        Don't be overwhelmed by a sea of red ink. Organise the comments into three simple categories to identify the most urgent problem:
                    </p>   
                    <ul class="pillar-ul mb10">
                        <li><b>1. Content (The "What"): </b>Comments on your argument, analysis, criticality, and use of sources. (e.g., "Needs more criticality," "Source is outdated"). This is the highest priority.</li>
                        <li><b>2. Structure (The "How"): </b>Comments on the flow, <a href="/blog/academic-paper-structure-step-by-step-guide-and-examples">paragraphing</a>, <a href="/blog/how-to-write-a-good-assignment">introduction</a>, and <a href="/blog/how-to-write-an-assignment-conclusion-easy-tips-examples">conclusion</a>. (e.g., "Unclear topic sentence," "Argument drifts"). This is the second-highest priority.</li>
                        <li><b>3. Presentation (The "Polish"): </b>Comments on <a href="/blog/mastering-harvard-referencing-a-guide-for-uk-students">referencing</a>, spelling, grammar, and <a href="/blog/what-are-best-essay-format-learn-how-to-format-an-essay">formatting</a>. (e.g., "Typo," "Incorrect citation"). These are easy wins, but rarely the cause of a major grade drop on their own.</li>
                    </ul>
                    <p class="mb10">By categorising, you'll see if you have one big problem (e.g., all Content) or several small ones.</p>

                    <h3>The Emotional Barrier: Receiving and Internalising Critique</h3>
                    <p>
                        As guides from Imperial College and Newcastle University advise, it's vital to separate your work from your worth. A 58% is not a comment on your intelligence; it's a technical score for a piece of work that didn't meet specific criteria.
                        <br>
                        It's tempting to shove a disappointing mark in a drawer. Do not do this.
                        <br>
                        Give yourself 24 hours to feel annoyed or disappointed. Then, sit down with a coffee and this guide, and prepare to look at the feedback objectively as a set of <a href="/blog/new-tips-to-empower-your-assignment-writing-skills">instructions for your next assignment</a>.
                    </p>
                    
                </section>

                {{-- BUTTONS-1 --}}
                <div class="pillar-btn-1">
                    <p>Our service goes beyond basic writing. We specialise in specific referencing styles (Harvard, APA, OSCOLA) and subject complexities, ensuring your work meets the high expectations of your module leader.</p>
                    <div class="pillar-hero-btns">
                        <a href="/pricing" class="pillar-btn">Get Your Free Quote</a>
                    </div>
                </div>

                <div id="section-2" style="margin-bottom:40px;">
                    <h2>Applying Feedback for Immediate Grade Improvement</h2>
                    <p class="mb10">Understanding is half the battle. Application is where you win the war. This section provides the actionable steps to turn critique into a concrete plan.</p>

                    <h3>The Rule of Three: Identifying Reoccurring Patterns</h3>
                    <p class="mb10">
                        Look back at your last three assignments, even from different modules. Do you see the same comments appearing?
                    </p>   
                    <ul class="pillar-ul custom-bullet mb10">
                        <li>"Too descriptive."</li>
                        <li>"Argument is unclear."</li>
                        <li>"Poor referencing."</li>
                    </ul> 
                    <p class="mb10">
                        This is your feedback "pattern." This recurring mistake is the single biggest thing holding your grade back. Your tutors are trying to tell you what it is. If you can fix this one pattern, you can unlock a <a href="/blog/how-to-score-better-in-coursework-assignments-expert-study-tips">higher grade</a> boundary across all your modules.
                    </p>                                    
                    
                    <h3>Creating a Feedback-Driven Improvement Action Plan</h3>
                    <p class="mb10">
                        Top <a href="/blog/9-simple-ways-to-improve-your-academic-writing-skills">academic skills</a> centres (like those at Leeds and Sheffield) provide "feedback logs" for a reason: they work. Turn your vague feedback into specific, measurable, achievable, relevant, and time-bound (SMART) goals.
                        <br>
                        Don't just write "be more critical." Create an action plan.
                    </p>
                    <div class="table-responsive mb10">
                        <table class="table-style-two mt20">
                            <thead>
                                <tr>
                                    <th>Marker's Comment</th>
                                    <th>What it Really Means</th>
                                    <th>My SMART Action for Next Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>"Needs more criticality"</td>
                                    <td>I'm describing theories, not evaluating them.</td>
                                    <td>(S) For my next essay, (M) every body paragraph will include at least one sentence that compares or contrasts two different authors. (A) I will use the "critical questions" checklist from my university library. (R) This will move my writing from descriptive to analytical. (T) I will do this in my first draft, due next week.</td>
                                </tr>
                                <tr>
                                    <td>"Weak structure"</td>
                                    <td>My points are in a random order.</td>
                                    <td>(S) I will write my introduction last. (M) I will first write all my body paragraphs on separate cards and physically arrange them on my desk until the flow is logical. (A) This is an easy structural fix. (R) This will ensure my argument builds logically from one point to the next. (T) I will do this at the outlining stage.</td>
                                </tr>                                
                            </tbody>
                        </table>
                        
                    </div>

                    <h3>Redrafting Strategies: Selective Revision vs. Total Rewrite</h3>
                    <p>
                        Once you have your action plan, how do you apply it to a redraft (or a future assignment)?
                    </p>                    
                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>Selective Revision (The "Scalpel"): </b>This is for when your Presentation or Structure is the main problem. You can fix typos, correct referencing, and re-order paragraphs without changing your core argument. This is a focused revision.</li>
                        <li><b>Total Rewrite (The "Sledgehammer"): </b>This is required when your Content feedback is the problem. If you've misunderstood the question, your research is too thin, or your entire argument is "too descriptive," you cannot fix it with minor edits. You must rewrite the core argument from the ground up. This is daunting, and it's where most students get stuck.</li>
                    </ul>
                </div>

                <div class="mb10">
                    <img src="/images/pillar-pages/The Definitive Guide to Effective Feedback and Assignment Redrafting.webp" alt="Mastering Referencing & Citation Styles for UK Students"
                    loading="lazy">
                </div>

                <div id="section-3" style="margin-bottom:40px;">
                    <h2>Feedback Loops and Dialogue</h2>
                    <p class="mb10">You are not in this alone. Feedback is the start of a conversation, not the end of one.</p>

                    <h3>The Follow-Up: Speaking to Your Tutor Ethically</h3>
                    <p>
                        Tutors want to help you improve. But there is a right and a wrong way to approach them.
                    </p>

                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>DO NOT SAY: </b>"Why did you give me a 62? I worked really hard and I think I deserve a 2:1." This is argumentative and focuses on the past.</li>
                        <li><b>DO SAY: </b>"Thank you for the feedback on my essay. I see you've mentioned I need to be more critical. I've created an action plan to address this, and I was hoping to quickly discuss my plan for the next assignment with you in your office hours."</li>
                    </ul>
                    <p class="mb10">
                        This approach is professional, respectful, and "feeds forward," showing you are focused on improvement, not arguing.
                    </p>

                    <h3>The Power of Forward Feedback (Draft Submissions)</h3>
                    <p class="mb10">
                        If your tutor or module offers the chance to submit a draft, outline, or 500-word sample, always take it. This is "forward feedback" (or "formative feedback"), and it is the single best way to prevent a disappointing final mark. It allows you to get guidance before the final push, when you still have time to make major changes.
                    </p>                    

                    <h3>Self-Assessment: Using the Rubric Before Submission</h3>
                    <p class="mb10">
                        The marker's feedback is written against a set of criteria. That criteria is almost always available to you from day one in your module handbook, known as the marking rubric.
                        <br>
                        Before you submit any assignment, read the rubric for a First-class mark. Does your work honestly meet that criteria? Then read the criteria for a 2:2. Does your work contain any of those faults? Be your own first marker. This self-assessment check will catch 80% of potential problems before your tutor ever sees them.
                    </p>
                    {{-- BUTTONS-2 --}}
                    <div class="pillar-hero-btns">
                        <a href="/review" class="pillar-btn">Read Student Reviews</a>
                    </div>
                </div>

                <div id="section-4" style="margin-bottom:40px;">
                    <h2>Transforming Feedback into a First-Class Final Draft</h2>
                    <p class="mb10">This is the commercial transition, linking the high-value informational content to the targeted service.</p>

                    <h3>The Gap Between Interpretation and Flawless Execution</h3>
                    <p class="mb10">
                        You've read this guide. You understand what "needs more criticality" means. You've even created an action plan.
                        <br>
                        Now what?
                        <br>
                        This is the gap where most students stay stuck. Understanding the problem is one thing. Flawlessly executing the complex structural, critical, and linguistic changes required to fix it is another.
                        <br>
                        It's incredibly difficult to be objective about your own work. You may think you're being more critical, but you're too close to the text to see that you're still just describing. This is where an objective, expert third party becomes your most powerful tool.
                    </p>

                    <h3>Our Redrafting and Feedback Implementation Service</h3>
                    <p>
                        This is where we come in. Our <a href="/academic-assignment-writing-help-service">professional academic help service</a> is designed for students who have their tutor's feedback and need expert help applying it.
                        <br>
                        This isn't an "<a href="/essay-writing-help-services">essay writing</a>" mill. This is a high-level implementation service.
                        <br>
                        You provide us with your original draft (or a new draft) and the tutor's feedback. Our <a href="/writers">UK-based, subject-specialist editors</a> will:
                    </p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>Analyse Your Feedback: </b>We decode every comment and identify the root cause of the grade.</li>
                        <li><b>Execute Flawless Revisions: </b>We will professionally redraft your work, not just for grammar, but for structure, criticality, and flow.</li>
                        <li><b>Implement the "Fixes": </b>We ensure the new draft directly addresses every single point your marker raised.</li>
                        <li><b>Enhance Academic Standards: </b>We elevate your language, strengthen your argument, and ensure the entire document meets the high standards of a UK university.</li>
                    </ul>
                    <p class="mb10">
                        We take the guesswork out of improvement and ensure your redraft or next assignment perfectly executes the required changes to maximise your mark.
                    </p>

                    <h3>Stop Guessing. Start Improving. Get Expert Feedback Implementation Help.</h3>
                    <p class="mb10">
                        Don't let valuable feedback go to waste. Stop struggling to interpret and start improving.
                    </p>
                    {{-- BUTTONS-3 --}}
                    <div class="pillar-hero-btns">
                        <a href="/blog" class="pillar-btn">Read the assignment help blog</a>
                    </div>
                </div>                

                <div id="faq" style="margin-bottom:40px;">
                    <h2>Frequently Asked Questions</h2>

                    <div class="faq-item">
                        <h3>1. Why is it important to review my feedback soon after receiving it?</h3>
                        <p>Because feedback is freshest in your mind right after submission, reviewing it promptly helps you connect the comments with the specific choices you made while writing. Waiting too long can make it harder to remember your reasoning or spot patterns across assignments.</p>
                    </div>

                    <div class="faq-item">
                        <h3>2. What should I do if I don’t understand my tutor’s feedback?</h3>
                        <p>Don’t guess. Highlight confusing comments and bring them to your tutor or academic advisor. You can email them politely or discuss them in office hours. Tutors appreciate students who seek clarification because it shows genuine engagement.</p>
                    </div>

                    <div class="faq-item">
                        <h3>3. Is it normal for different tutors to give conflicting feedback?</h3>
                        <p>Yes, especially across modules or disciplines. Each tutor values different aspects (e.g., theory depth vs. clarity). If this happens, prioritise the expectations of the specific module marker and ask for clarification on what they consider “strong work.”</p>
                    </div>

                    <div class="faq-item">
                        <h3>4. Can peer feedback help me improve, or should I rely only on tutors?</h3>
                        <p>Peer feedback can be incredibly useful for clarity, flow, and argument strength. While peers can’t assess you like a tutor, they represent your target reader—someone intelligent but not inside your head. If they can’t follow your logic, your structure likely needs improvement.</p>
                    </div>

                    <div class="faq-item">
                        <h3>5. How soon should I start redrafting after getting feedback?</h3>
                        <p>Ideally, within a week—while the feedback and assignment are still fresh. The earlier you apply lessons from one assignment to the next, the stronger your academic writing will become across the term.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3>6. What if I consistently get good marks but still feel I’m not improving?</h3>
                        <p>Grades don’t always show progress in skill. Ask for qualitative feedback on your analytical depth, originality, and argument sophistication. Even strong students plateau—pushing beyond that requires targeted improvement, not just grade maintenance.</p>
                    </div>
                </div>

            </div>
    </section>


    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const menuItems = document.querySelectorAll(".pillar-sidebar-box ul li");

            // click scroll
            menuItems.forEach(li => {
                li.addEventListener("click", function (e) {
                    e.preventDefault();
                    let anchor = this.querySelector("a").getAttribute("href");
                    let target = document.querySelector(anchor);
                    window.scrollTo({ top: target.offsetTop - 180, behavior: "smooth" });
                });
            });

            // active on scroll
            window.addEventListener("scroll", function () {
                let fromTop = window.scrollY + 180;
                menuItems.forEach(li => {
                    let section = document.querySelector(li.querySelector("a").getAttribute("href"));
                    if (section.offsetTop <= fromTop && (section.offsetTop + section.offsetHeight) > fromTop) {
                        menuItems.forEach(i => i.classList.remove("active"));
                        li.classList.add("active");
                    }
                });
            });

        });
    </script>

@endsection