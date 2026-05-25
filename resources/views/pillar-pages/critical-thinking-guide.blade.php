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
                <h1>The UK Student’s Ultimate Guide to Critical Thinking & Analysis</h1>
                <p>
                    At university, the line between a pass and a <a href="/blog/buy-assignment-to-beat-assignment-stress">high grade</a> is not about how much you know—it's about what you do with what you know. This is the challenge of <a href="/blog/important-things-to-remember-while-writing-an-assignment">critical thinking</a>. It’s the single most valued skill by UK markers, and it's the primary difference between a 2:2 and a <a href="/blog/how-to-structure-a-21-standard-assignment-for-uk-universities">2:1</a> or First.
                    <br>
                    Many students receive feedback like "too descriptive" or "needs more <a href="/blog/how-to-write-a-critical-analysis-for-business-with-examples">critical analysis</a>," but are never explicitly taught how to make that jump. This guide demystifies the process. We will move you from simply describing ideas to critically evaluating them, constructing powerful arguments that <a href="/blog/how-to-score-better-in-coursework-assignments-expert-study-tips">earn top marks.</a>
                </p>
                
                {{-- BUTTONS UNDER HERO --}}
                <div class="pillar-hero-btns">
                    <a href="/" class="pillar-btn-bg">Request Assignment Help</a>
                    <a href="/writers" class="pillar-btn">Get Expert Help</a>
                </div>
            </div>

            <div class="pillar-hero-image">
                <img src="/images/pillar-pages/hero/The UK Student’s Ultimate Guide to Critical Thinking & Analysis.webp" alt="Mastering Referencing & Citation Styles for UK Students"
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
                    <h3>Critical Thinking Guide - Overview</h3>
                    <ul>
                        <li><a href="#section-1">Defining Criticality in a UK Academic Context</a></li>
                        <li><a href="#section-2">Building Blocks of Critical Argumentation</a></li>
                        <li><a href="#section-3">Applying Criticality to Different Assignment Types</a></li>
                        <li><a href="#section-4">Elevating Your Analysis from Good to Exceptional</a></li>                        
                        <li><a href="#faq">Critical Thinking Guide FAQs</a></li>
                    </ul>
                </div>
            </aside>


            {{-- main content --}}
            <div class="pillar-content">

                {{-- section 1 --}}
                <section id="section-1" style="margin-bottom:40px;">
                    <h2>Defining Criticality in a UK Academic Context</h2>
                    <p class="mb10">Before you can apply it, you must understand what "criticality" truly means to a UK university marker. It is not about simply criticising; it is about objective, high-level evaluation.</p>

                    <h3>The Great Divide: Description vs. Analysis</h3>
                    <p>
                        This is the most common hurdle that traps students in the 2:2 bracket. The majority of your word count must be <a href="/blog/statistics-project-ideas-that-will-boost-your-analytical-skills">analytical</a>, not descriptive.
                    </p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li>
                            <b>Descriptive Writing (The 'What'): </b>
                            This is the foundation. It sets the scene, summarises facts, and outlines information. It answers questions like:
                            <ul class="pillar-ul custom-bullet">
                                <li><b>Who? </b>(e.g., "<a href="/writers/samuel-wright">Who proposed the theory?</a>")</li>
                                <li><b>What? </b>(e.g., "What is the main concept?")</li>
                                <li><b>When? </b>(e.g., "When did this event take place?")</li>
                                <li><b>Where? </b>(e.g., "Where was the study conducted?")</li>
                            </ul>
                        </li>
                        <li><b>Example (Description): </b>"The 2019 study by Smith conducted a survey of 500 university students to measure their weekly screen time. It found that the average student spent 25 hours per week on their phone."</li>
                        <li>
                            <b>Critical Analysis (The 'So What?'): </b>
                            This is where you earn a 2:1 or First. It deconstructs the information, examines its components, and judges its value. It answers questions like:
                            <ul class="pillar-ul custom-bullet">
                                <li><b>Why? </b>(e.g., "Why is this theory more convincing than another?")</li>
                                <li><b>How? </b>(e.g., "How does this evidence support the main argument?")</li>
                                <li><b>So What? </b>(e.g., "So what are the implications of these findings?")</li>
                                <li><b>What if? </b>(e.g., "What if the methodology was different?")</li>
                            </ul>
                        </li>
                        <li><b>Example (Critical Analysis): </b>"While Smith's (2019) findings on screen time are illuminating, the study's reliance on self-reported data raises questions of validity. Students may have inaccurately recalled their usage, suggesting the true figure could be higher. Furthermore, the study fails to differentiate between 'passive' screen time (viewing lectures) and 'active' social media use, a crucial omission that limits the conclusions we can draw about its impact on mental well-being."</li>
                    </ul>

                    <h3>The Three Pillars of Critical Thought (Evaluation, Synthesis, Argument)</h3>
                    <p>Critical thinking is a process made up of three core skills:</p>
                    <ul class="pillar-ul mb10">
                        <li><b>1. Evaluation: </b>This is the act of judging the quality, credibility, and relevance of a source or idea. You are not just accepting information; you are questioning its strength.</li>
                        <li><b>2. Synthesis: </b>This is the skill of connecting ideas. You don't treat each source in isolation. You weave them together, showing how they relate, agree, or disagree. Synthesis is essential for a <a href="/uk/literature-review-writing-help-online">literature review.</a></li>
                        <li><b>3. Argument: </b>This is your "voice" in the essay. It is the position you are taking, the central claim you are making in response to the essay question. Your argument is the "golden thread" that runs through your entire paper, supported by your evaluation and synthesis of evidence.</li>
                    </ul>

                    <h3>Why UK University Markers Value Critical Analysis Above All Else</h3>
                    <p>
                        UK universities are built on a tradition of research and debate. A marker doesn't just want you to prove you've done the reading; they want to see your mind at work.
                        <br>
                        Your mark is justified by your level of critical engagement:
                    </p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>Description (40%-50%): </b>You have understood the topic and can repeat information.</li>
                        <li><b>Some Analysis (50%-60%): </b>You are starting to interpret and make links, but your argument may be simple.</li>
                        <li><b>Consistent Analysis (60%-70%): </b>You are evaluating evidence, engaging with different viewpoints, and constructing a clear argument. This is the 2:1 threshold.</li>
                        <li><b>Exceptional Analysis (70%+): </b>You demonstrate originality, nuance, and a sophisticated grasp of the "so what." You can synthesise complex ideas and challenge assumptions (even those of the authors you read).</li>
                    </ul>
                </section>

                {{-- BUTTONS-1 --}}
                <div class="pillar-btn-1">
                    <p>Our service goes beyond basic writing. We specialise in specific referencing styles (Harvard, APA, OSCOLA) and subject complexities, ensuring your work meets the high expectations of your module leader.</p>
                    <div class="pillar-hero-btns">
                        <a href="/pricing" class="pillar-btn">Get Your Free Quote</a>
                    </div>
                </div>

                <div id="section-2" style="margin-bottom:40px;">
                    <h2>Building Blocks of Critical Argumentation</h2>
                    <p class="mb10">You can learn to be a critical thinker. It is a set of practical skills that, once mastered, will <a href="/blog/language-techniques-in-english-and-boost-your-assignment-writing-skills">transform your writing.</a></p>

                    <h3>Questioning the Source: The CRAP Test and Source Evaluation</h3>
                    <p>
                        Never accept a source at face value. Before you even read it deeply, apply a quick vetting process like the CRAP Test to judge its credibility for <a href="/academic-integrity/">academic</a> use.
                    </p>                                        
                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>C - Currency: </b>When was it published? Is the information still relevant, or has it been superseded (especially in science and tech)?</li>
                        <li><b>R - Relevance: </b>How deeply does this source cover your specific essay question? Does it provide a brief mention or a detailed analysis?</li>
                        <li><b>A - Authority: </b>Who wrote it? Are they a recognised expert, academic, or journalist? What are their credentials? Is it published by a reputable academic press or a random blog?</li>
                        <li><b>P - Purpose: </b>Why was this written? To inform (like an academic journal)? To persuade (like a political editorial)? To sell (like a company report)? The purpose reveals potential biases.</li>
                    </ul>
                    
                    <h3>Deconstructing an Argument: Identifying Assumptions and Flaws</h3>
                    <p>
                        When you read, you are not just reading for facts. You are reading to understand the author's argument. Ask yourself:
                    </p>                                        
                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>What is their main claim? </b>What do they want me to believe?</li>
                        <li><b>What evidence do they use? </b>Is it data, theory, or anecdote?</li>
                        <li><b>What are their hidden assumptions? </b>What do they take for granted? (e.g., "The author assumes that economic growth is always desirable, but fails to consider the environmental impact.")</li>
                        <li><b>Are there logical fallacies? </b>Do they jump to conclusions? Do they present a false dichotomy (only two options) when more exist?</li>
                    </ul>
                    <p class="mb10">Spotting these flaws and assumptions is critical analysis.</p>
                    
                    <h3>The Counter-Argument and Refutation: Engaging with Nuance</h3>
                    <p>
                        A First-Class <a href="/essay-writing-help-services">essay</a> does not ignore opposing viewpoints; it actively seeks them out. The strongest arguments are those that can anticipate and dismantle counter-arguments.
                        <br>
                        This shows your marker you have a 360-degree view of the topic.
                    </p>                                        
                    <ul class="pillar-ul mb10">
                        <li><b>1. Acknowledge: </b>"One might argue that..." or "Smith (2020) offers a conflicting view, suggesting that..."</li>
                        <li><b>2. Refute: </b>"...However, this perspective overlooks..." or "While this point has merit, it fails to account for..."</li>
                    </ul>
                    <p>This technique (known as refutation) moves your writing from a simple statement of your own opinion to a sophisticated academic debate.</p>
                </div>

                <div class="mb10">
                    <img src="/images/pillar-pages/The UK Student’s Ultimate Guide to Critical Thinking & Analysis.webp" alt="Mastering Referencing & Citation Styles for UK Students"
                    loading="lazy">
                </div>

                <div id="section-3" style="margin-bottom:40px;">
                    <h2>Applying Criticality to Different Assignment Types</h2>
                    <p class="mb10">Critical thinking is not one-size-fits-all. Here is how to apply it to the most common assignments you'll face.</p>

                    <h3>Critical Thinking in Essays and Reports: Structure and Flow</h3>
                    <p>
                        In an essay, your argument is everything. Your structure must be built to showcase it.
                    </p>

                    <ul class="pillar-ul custom-bullet mb10">                        
                        <li><b>Introduction: </b>Your introduction should end with a strong, clear <a href="/thesis-writing-help">thesis statement.</a> This is your answer to the essay question in one sentence.</li>
                        <li>
                            <b>Topic Sentences: </b>
                            The first sentence of every paragraph must be analytical, not descriptive. It should be a "signpost" that tells the marker what point you are making in that paragraph and how it proves your overall <a href="/thesis-writing-help">thesis.</a>
                            <ul class="pillar-ul custom-bullet">                        
                                <li><b>Weak (Descriptive): </b>"Shakespeare's Hamlet features the theme of revenge."</li>
                                <li><b>Strong (Analytical): </b>"Shakespeare uses the theme of revenge in Hamlet to critically explore the moral decay of the state."</li>
                            </ul>
                        </li>
                        <li><b>Conclusion: </b>Do not just summarise. Re-state your thesis, but then explicitly state the "so what." What are the wider implications of your argument? What is the final, powerful thought you want to leave with the marker?</li>
                    </ul>

                    <h3>Critically Reviewing Literature: The Literature Review Strategy</h3>
                    <p>
                        A critical <a href="/uk/literature-review-writing-help-online">literature review</a> is not a summary of "Book 1," then "Book 2," then "Book 3." That is a descriptive, "shopping list" approach.
                        <br>
                        A critical literature review is a synthesis. You must group your sources thematically.
                    </p>
                    <ul class="pillar-ul mb10">
                        <li><b>1. Identify Themes: </b>As you read, what are the main debates, topics, and disagreements in the field? (e.g., "The debate on 'nature vs. nurture' in childhood development.")</li>
                        <li><b>2. Structure by Theme: </b>Each paragraph or section should be about a theme, not an author.</li>
                        <li>
                            <b>3. Synthesise: </b>
                            Within each theme, you bring authors into conversation.
                            <ul class="pillar-ul">
                                <li>"Smith (2020) and Jones (2021) both agree that..."</li>
                                <li>"However, Brown (2022) offers a powerful counter-argument, highlighting..."</li>
                                <li>"This reveals a significant gap in the literature: while most research has focused on X, Y has been largely ignored."</li>
                            </ul>
                        </li>
                    </ul>

                    <h3>Criticality in The Dissertation: Originality and Contribution</h3>
                    <p>
                        Your dissertation's critical analysis rests on identifying that gap in the literature.
                        <br>
                        Your entire project is a critical argument.
                    </p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li>Your Literature Review argues that a gap exists.</li>
                        <li>Your <a href="/blog/how-to-write-methodology-for-dissertation">Methodology</a> argues that your chosen method is the most robust way to fill that gap.</li>
                        <li>Your Analysis/Discussion argues that your findings do successfully fill that gap.</li>
                        <li>Your <a href="/blog/how-to-write-an-assignment-conclusion-easy-tips-examples">Conclusion</a> argues why filling that gap matters and what it contributes to your field.</li>
                    </ul>
                    <p class="mb10">The dissertation is the ultimate expression of critical thought, demonstrating your ability to not just consume knowledge but to create it.</p>
                    {{-- BUTTONS-2 --}}
                    <div class="pillar-hero-btns">
                        <a href="/review" class="pillar-btn">Read Student Reviews</a>
                    </div>
                </div>

                <div id="section-4" style="margin-bottom:40px;">
                    <h2>Elevating Your Analysis from Good to Exceptional</h2>
                    <p class="mb10">You may have brilliant critical thoughts, but if they are not communicated with absolute clarity, they are invisible to your marker. This is the final hurdle that separates a 2:1 from a First.</p>

                    <h3>The Risk of Self-Assessment: When Good Analysis Isn't Clear Enough</h3>
                    <p class="mb10">
                        You know your argument inside and out. But does your marker?
                        <br>
                        Students often lose marks not because the critical thought is absent, but because it is implicit. You are so close to the work that you fail to see where your logic is muddled, your sentences are awkward, or your structure is confusing.
                        <br>
                        You think you've made a powerful analytical point, but the marker just sees a confusing paragraph. This "clarity gap" is often the missing 5% that makes all the difference.
                    </p>

                    <h3>Our Expert Review: Sharpening Your Critical Edge</h3>
                    <p>
                        This is where a final, objective review becomes your single greatest asset. Our <a href="/uk/proofreading-and-editing-writing-help">academic editing service</a> is not about <a href="/essay-writing-help-services">writing your essay</a>—it is about ensuring the brilliance of your critical argument is communicated with the precision and clarity expected of a First-Class submission.
                        <br>
                        Our experts review your work with one focus: sharpening your critical edge.
                    </p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li>We ensure your thesis is sharp, arguable, and present in every section.</li>
                        <li>We refine your paragraph structure, ensuring every topic sentence is analytical.</li>
                        <li>We check that your evidence is seamlessly integrated, not just "dropped" in.</li>
                        <li>We challenge any ambiguity, pushing you to make your points explicit.</li>
                        <li>We ensure your "so what" is powerfully stated, leaving no doubt as to the strength of your argument.</li>
                    </ul>

                    <p class="mb10">We act as the final, critical layer of review, checking that your logic is sound, your structure is robust, and your academic voice is clear, professional, and convincing <a href="/">assignment help.</a></p>

                    <h3>Ready to Move Beyond Description and Master Analysis?</h3>
                    <p>
                        Stop losing marks for being "too descriptive." Let our <a href="/expert-assignment-writing-help">experts help</a> you polish your hard work and ensure your critical analysis gets the <a href="/blog/crack-the-secret-of-how-to-get-good-grades">First-Class grade it deserves.</a>
                    </p>
                                        
                    {{-- BUTTONS-3 --}}
                    <div class="pillar-hero-btns">
                        <a href="/blog" class="pillar-btn">Read the assignment help blog</a>
                    </div>
                </div>                

                <div id="faq" style="margin-bottom:40px;">
                    <h2>Frequently Asked Questions</h2>

                    <div class="faq-item">
                        <h3>1. Why do UK universities put so much emphasis on “critical thinking”?</h3>
                        <p>Because UK higher education focuses on independent thought, not memorisation. Markers want to see that you can question evidence, challenge assumptions, and build your own argument — skills that reflect higher-order thinking, not rote learning.</p>
                    </div>

                    <div class="faq-item">
                        <h3>2. How can I develop critical thinking skills outside of essay writing?</h3>
                        <p>Practice analysing everyday content — news articles, podcasts, or documentaries. Ask: Who is speaking? What evidence supports their view? What’s missing? The more you apply this mindset beyond academia, the more natural it becomes in your writing.</p>
                    </div>

                    <div class="faq-item">
                        <h3>3. How can I maintain a critical voice without sounding informal or emotional?</h3>
                        <p>Use cautious, academic language. Phrases like “This suggests that…”, “However, it could be argued…”, or “The evidence indicates…” show critical evaluation while remaining objective and professional.</p>
                    </div>

                    <div class="faq-item">
                        <h3>4. How do I show “originality” in critical analysis if I’m using published research?</h3>
                        <p>Originality isn’t about discovering something new; it’s about showing your independent thought. You demonstrate this by connecting sources in new ways, highlighting overlooked implications, or framing the evidence to support a unique angle on your question.</p>
                    </div>

                    <div class="faq-item">
                        <h3>5. Why do some students think they’re analysing when they’re actually describing?</h3>
                        <p>Because they repeat information using academic language without evaluating it. Using phrases like “This study shows…” is descriptive. True analysis adds interpretation — for example, “This study shows X, but its limited sample size weakens the generalisability of the results.”</p>
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