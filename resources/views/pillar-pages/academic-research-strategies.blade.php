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
                <h1>Advanced Academic Research Strategies for UK University Databases (2025)</h1>
                <p>
                    Welcome to the definitive 2025 guide for <a
                        href="/blog/understanding-the-uk-university-grading-system-from-first-class-to-third-class-honours"> UK university
                    </a>  students. You’ve already mastered basic Google searches, but to achieve a<a
                        href="/blog/how-to-structure-a-21-standard-assignment-for-uk-universities"> 2:1 or a First Class</a>  on your
                         <a href="/dissertation-writing-help-services">dissertation </a> or 
                         <a href="/essay-writing-help-services"> final-year essays,</a>
                    you need to move beyond Google and leverage the powerful, high-quality databases your university pays for.
                </p>
                <p>
                   This guide is for students who want to elevate their research from finding sources to building an authoritative argument. We'll cover the advanced search techniques, core UK databases, and 
                   <a href="/blog/what-are-organisational-skills-types-and-examples">organisational</a> strategies that markers look for in top-tier work.          
                    </p>
                {{-- BUTTONS UNDER HERO --}}
                <div class="pillar-hero-btns">
                    <a href="/" class="pillar-btn-bg">Request Assignment Help</a>
                    <a href="/writers" class="pillar-btn">Get Expert Help</a>
                </div>
            </div>

            <div class="pillar-hero-image">
                <img src="/images/pillar-pages/hero/Advanced Academic Research Strategies for UK University Databases.webp" alt="The Definitive UK University Academic Integrity Handbook"
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
                    <h3>Academic Research Strategies - Overview</h3>
                    <ul>
                        <li><a href="#section-1">Moving Beyond Google: The Academic Ecosystem</a></li>
                        <li><a href="#section-2">Mastering Search Techniques (The Skill Gap)</a></li>
                        <li><a href="#section-3">Organizing, Synthesizing, and Using Your Sources</a></li>
                        <li><a href="#section-4">Expert-Led Research Foundations (Commercial Transition)</a></li>                        
                        <li><a href="#faq">Academic Research Strategies FAQs</a></li>
                    </ul>
                </div>
            </aside>


            {{-- main content --}}
            <div class="pillar-content">

                {{-- section 1 --}}
                <section id="section-1" style="margin-bottom:40px;">
                    <h2>Moving Beyond Google: The Academic Ecosystem</h2>
                    <p class="mb10">First, you must understand the landscape.<a href="/academic-assignment-writing-help-service">Academic research</a>
                        isn't just "finding stuff online"; it's about navigating a hierarchy of information and using the right tools to find the best sources.
                    </p>

                    <h3>The Hierarchy of Academic Sources: Primary, Secondary, and Grey Literature</h3>
                    <p>Not all sources are created equal. A marker will judge the quality of your work by the quality of your 
                     <a href="/blog/how-to-write-an-annotated-bibliography-for-top-grades">bibliography</a>
                     </p>

                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>Primary Sources: </b>This is the raw data or original material. It includes original research studies (e.g., a scientific experiment, a clinical trial),
                            <a href="/free-samples/History">historical documents </a>(e.g., letters, manuscripts), case law, and creative works (e.g., novels, poems).
                        </li>
                        <li><b>Secondary Sources: </b>These are sources that analyse or interpret primary sources. This is the category for peer-reviewed 
                             <a href="/blog/how-to-cite-a-journal-article-in-apa">journal articles </a>academic books, and critical reviews. This is where you will find the bulk of your sources for a UK
                             <a href="/university-assignment-writing-help"> university essay </a>.
                        </li>
                        <li><b>Tertiary Sources:</b>These summarise or compile secondary sources. Examples include textbooks, encyclopedias, and, yes,
                            <a href="/blog/how-to-cite-a-wikipedia-article-apa-mla-chicago">Wikipedia </a>.
                             Use these only for background reading to understand a topic, never as a citable source in your final essay.
                        </li>
                        <li><b>Grey Literature:</b>This is high-quality research from outside of traditional publishing. It includes government reports, policy briefs from think tanks, and corporate white papers. It can be excellent, but you must critically evaluate its
                            <a href="/blog/types-of-bias-in-research-definition-examples">bias</a>.
                       </li>
                        
                    </ul>

                    <h3>Navigating Your University’s Library Gateway (The Single Search)</h3>
                    <p>
                        Your university library website is the most powerful research tool you have. The main search bar (often called "Library Search," "Discover," or "Summon") is your gateway.
                    </p>
                    <p class="mb10"> This "single search" bar doesn't just search the library's physical books. It uses your institutional login to search across dozens of powerful databases at once, giving you access to millions of
                        <a href="https://core.ac.uk/"> full-text journal articles </a>, <a href="https://www.reading.ac.uk/library/e-resources/e-books/free-e-book-collections">e-books </a>, and reports that are hidden behind paywalls and invisible to Google. This should always be your starting point.
                    </p>


                    <h3>Leveraging External Research Tools for UK Students</h3>
                    <p >While your university gateway is #1, a few other tools are essential for comprehensive research:</p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>Google Scholar:</b>The main advantage here is speed and <a href="/referencing-citation-guide"> citation </a> chaining.
                        <ul class="pillar-ul custom-bullet ">
                            <li><b>Advanced Tip: </b> Use the "Advanced search" (in the menu) to filter by author, publication (e.g., find articles only from The Lancet), or a specific date range (e.g., 2020-2025) to find the most recent research.
                            </li> 
                        </ul>
                        </li>
                        <li><b>Core UK Academic Databases: </b>
                        <ul class="pillar-ul custom-bullet">
                            <li><b>JSTOR: </b> The best database for <a href="/blog/trending-humanities-research-topics-explore-culture-ideas"> humanities </a>and social sciences, especially for finding foundational or historical articles.</li> 
                            <li><b>ProQuest & Scopus:</b>  Enormous multi-disciplinary databases that are excellent for finding very current research in science, technology, business, and health.</li>
                            <li><b>Nexis UK:</b> An essential database for news, business, and law. It allows you to search a global archive of newspapers, trade publications, and company reports.</li>
                        </ul>
                       </li>
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
                    <h2>Mastering Search Techniques (The Skill Gap)</h2>
                    <p class="mb10">Finding some sources is easy. Finding the right sources quickly is a skill. This is how you stop wasting hours scrolling through irrelevant results.</p>

                    <h3>Boolean Operators: The Art of Precision Searching (AND, OR, NOT)</h3>
                    <p>Boolean operators are simple commands that tell the database exactly what you want.</p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li>
                            <b>AND </b>(Narrows your search): Finds results that must contain all your keywords.
                            <ul class="pillar-ul custom-bullet">
                                <li><b>Example: </b>(universities AND "mental health") will only find articles that mention both topics.</li>
                            </ul>
                        </li>
                        <li>
                            <b>OR </b>(Broadens your search): Finds results that contain at least one of your keywords. Use this for synonyms.
                            <ul class="pillar-ul custom-bullet">
                                <li><b>Example: </b>("mental health" OR wellbeing OR anxiety) will find articles that mention any of those terms.</li>
                            </ul>
                        </li>
                        <li>
                            <b>NOT </b>(Excludes a keyword): Removes a specific, irrelevant term.
                            <ul class="pillar-ul custom-bullet">
                                <li><b>Example: </b>(dolphins NOT "Miami Dolphins") will find articles about the animal, not the football team.</li>
                            </ul>
                        </li>
                    </ul>

                    <h3>Truncation, Wildcards, and Phrase Searching ("")</h3>
                    <p>These are the next-level techniques for precision.</p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li>
                            <b>Phrase Searching (""): </b>The most important trick. Enclosing your terms in "quotation marks" forces the database to search for that exact phrase, not just the individual words.
                            <ul class="pillar-ul custom-bullet">
                                <li><b>Example: </b>“<a href="/academic-integrity">academic integrity</a>” will find this exact phrase, while academic integrity (without quotes) will find any document that contains the word "academic" and the word "integrity" anywhere in the text.</li>
                            </ul>
                        </li>
                        <li>
                            <b>Truncation (*): </b>The asterisk $\left(^*\right)$ finds all variations of a word root. This saves you from having to use OR for every variation.
                            <ul class="pillar-ul custom-bullet">
                                <li><b>Example: </b>Econom* will find Economy, Economic, Economics, and Economist.</li>
                            </ul>
                        </li>
                        <li>
                            <b>Wildcards (? or !): </b>These substitute a single letter. They are useful for spelling variations (e.g., UK vs. US English).
                            <ul class="pillar-ul custom-bullet">
                                <li><b>Example: </b>Organisation will find both Organisation and Organisation.</li>
                            </ul>
                        </li>
                    </ul>

                    <h3>Citation Chaining: The Most Effective Way to Find Sources</h3>
                    <p>This is the number one strategy that A-grade students use. Once you find one perfect, highly relevant article, you can use it to find dozens more.</p>
                    <ul class="pillar-ul">
                        <li><b>1. Find one "seed" article.</b></li>
                        <li><b>2. Go "Backward" (Backward Citation Chaining): </b>Open the <a href="/blog/chicago-style-bibliography-format-examples">article's bibliography</a> or <b>reference list</b>. This is a curated list of all the foundational, important sources the author used. Check these sources—they are almost certainly relevant to your topic.</li>
                        <li><b>3. Go "Forward" (Forward Citation Chaining): </b>Use Google Scholar or Web of Science to see who has cited your "seed" article since it was published. Click the "Cited by" link. This shows you all the newer research that is building on this article. It's the fastest way to find the most current conversation in your field.</li>
                    </ul>
                </div>

                <div class="mb10">
                    <img src="/images/pillar-pages/Advanced Academic Research Strategies for UK University Databases.webp" alt="The Definitive UK University Academic Integrity Handbook"
                    loading="lazy">
                </div>

                <div id="section-3" style="margin-bottom:40px;">
                    <h2>Organizing, Synthesizing, and Using Your Sources</h2>
                    <p class="mb10">
                        You've found 50 articles. Now what? Your research grade isn't based on how many sources you find, but on how well you use them.
                    </p>

                    <h3>The Power of the Abstract: Quick Evaluation and Filtering</h3>
                    <p>
                        Don't read 50 full articles. You don't have time. Learn to evaluate a source in 60 seconds.
                    </p>

                    <ul class="pillar-ul mb10">
                        <li><b>1. Read the Abstract: </b>This is the author's summary. It will tell you their main argument (Thesis), their research methods (<a href="/blog/how-to-write-methodology-for-dissertation">Methodology</a>), and their main findings (Results).</li>
                        <li><b>2. Read the Introduction: </b>Skim this to confirm the research question and why it matters.</li>
                        <li><b>3. Read the Conclusion: </b>Jump to the end to see their final, summarised answer.</li>
                    </ul>

                    <p class="mb10">From these three sections, you can determine 95% of the time if the article is essential, relevant, or useless for your specific essay question.</p>

                    <h3>Creating a Source Synthesis Matrix (Linking to Criticality)</h3>
                    <p>
                        This is the ultimate tool for organising your notes and preparing to write. It prevents your essay from becoming a "shopping list" of sources.
                        <br>
                        Create a simple spreadsheet (Excel or Google Sheets).
                    </p>

                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>Column A: </b>Source (e.g., Smith, 2024).</li>
                        <li><b>Column B: </b>Main Argument (1-2 sentences).</li>
                        <li><b>Column C: </b>Methodology (How did they find this out?).</li>
                        <li><b>Column D: </b>Key Themes/Arguments related to your <a href="/essay-writing-help-services">essay</a> (e.g., "Theme 1: Policy," "Theme 2: Student Impact").</li>
                        <li><b>Column E: </b>Your Critique (e.g., "Good data, but small sample size," "Directly contradicts Jones, 2022").</li>
                    </ul>

                    <p class="mb10">
                        When you finish, you can sort this table by theme and instantly see which authors agree, who disagrees, and where the "gap" in the research is.
                    </p>

                    <h3>Ethical Use: Source Management and the Referencing Link</h3>
                    <p>Good research habits are the foundation of <a href="/academic-integrity">academic integrity.</a></p>

                    <ul class="pillar-ul custom-bullet mb10">
                        <li>As you download PDFs, immediately save them to a reference management tool like Zotero or Mendeley. Most are free for students.</li>
                        <li>These tools not only store your PDFs but also integrate with Microsoft Word to automatically create your in-text citations and bibliography in the correct style (e.g., Harvard, APA).</li>
                        <li>This process eliminates the risk of accidental <a href="/blog/can-anyone-help-in-writing-an-assignment-without-plagiarism">plagiarism</a> from poor note-taking and saves you hours of panic-typing your reference list at the end.</li>
                    </ul>
                    
                    {{-- BUTTONS-2 --}}
                    <div class="pillar-hero-btns">
                        <a href="/review" class="pillar-btn">Read Student Reviews</a>
                    </div>
                </div>

                <div id="section-4" style="margin-bottom:40px;">
                    <h2>Expert-Led Research Foundations (Commercial Transition)</h2>
                    <p class="mb10">
                        You now have the toolkit of an advanced researcher. But the single biggest difference between a 2:1 and a First is not just finding sources, but knowing you've found the right ones.
                    </p>

                    <h3>Why a Strong Research Foundation Guarantees a Higher Grade</h3>
                    <p>
                        Markers can tell in the first paragraph if you have engaged with the key literature.
                    </p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li>A 2:2 essay often describes a few, easily found sources.</li>
                        <li>A 2:1 essay compares and contrasts a good range of relevant sources.</li>
                        <li>A First Class essay identifies the most authoritative voices, critiques them, and uses them to build a new, original argument.</li>
                    </ul>
                    <p class="mb10">
                        Missing a single, foundational study that your marker knows you should have read can cap your grade. It makes your entire argument look incomplete.
                    </p>

                    <h3>How Our Experts Ensure Your Research is Authoritative</h3>
                    <p>
                        You're a student, not a 30-year expert in your field. How can you be 100% sure you haven't missed a critical source?
                        <br>
                        That's where we come in.
                        <br>
                        Our <a href="/research-paper-writing-services">Research paper help Service</a> is designed for students who are serious about their grades. Before you write a single word of your dissertation or major project, you can have a subject-specialist academic review your research plan.
                        <br>
                        Our experts will:
                    </p>

                    <ul class="pillar-ul custom-bullet mb10">
                        <li>Analyse your research question and bibliography.</li>
                        <li>Conduct a "gap analysis" to identify any key authors or foundational theories you've missed.</li>
                        <li>Suggest the most current, high-impact studies to ensure your research is truly cutting-edge.</li>
                    </ul>

                    <p class="mb10">
                        We don't do the research for you—we act as an <a href="/expert-assignment-writing-help">expert supervisor</a>, vetting your foundation so you can build your argument with absolute confidence.
                    </p>

                    <h3>Stop Searching, Start Writing: Get Your Research Vetted Now.</h3>
                    <p>Don't spend weeks worrying if your research is good enough. Spend a few minutes getting an expert guarantee. Ensure your dissertation is built on a First-Class foundation.</p>

                    {{-- BUTTONS-3 --}}
                    <div class="pillar-hero-btns">
                        <a href="/blog" class="pillar-btn">Read the assignment help blog</a>
                    </div>
                </div>

                <div id="faq" style="margin-bottom:40px;">
                    <h2>Frequently Asked Questions</h2>

                    <div class="faq-item">
                        <h3>1. What’s the difference between a university database and Google Scholar?</h3>
                        <p>
                            Google Scholar searches open web content and limited publisher data, while university databases provide full access to peer-reviewed, paid academic journals and materials behind paywalls. In short — Google Scholar shows what exists, your university databases give full access to it.
                        </p>
                    </div>

                    <div class="faq-item">
                        <h3>2. Why do some academic databases require a VPN or institutional login?</h3>
                        <p>
                            Most university databases are subscription-based. When off-campus, you must log in through your university portal or VPN so the system recognises you as an authorised user with paid access.
                        </p>
                    </div>

                    <div class="faq-item">
                        <h3>3. Are books still relevant in the digital age of academic research?</h3>
                        <p>
                            Absolutely. While journal articles provide current research, academic books offer depth, theoretical context, and authoritative perspectives — particularly important in humanities and social sciences.
                        </p>
                    </div>

                    <div class="faq-item">
                        <h3>4. How can I tell if a journal article is peer-reviewed?</h3>
                        <p>
                            Check the journal’s website or database record. Peer-reviewed journals will explicitly mention it. Databases like Scopus or ProQuest also have filters for “peer-reviewed” or “academic journal” articles.
                        </p>
                    </div>

                    <div class="faq-item">
                        <h3>5. How do I find “seminal” or “foundational” studies in my field?</h3>
                        <p>
                            Look for articles that are cited repeatedly by other researchers (you can see citation counts in Google Scholar). You can also ask your module leader, supervisor, or check literature review sections in recent articles for repeated author names or key theories.
                        </p>
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