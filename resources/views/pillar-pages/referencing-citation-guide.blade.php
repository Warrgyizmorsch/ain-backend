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
                <h1>Mastering Referencing & Citation Styles for UK Students</h1>
                <p>
                    Referencing. It’s the one part of <a href="/academic-assignment-writing-help-service">academic writing</a> that causes more stress for UK students than almost any other. Get it right, and you demonstrate scholarly rigour. Get it wrong, and you risk losing marks or, worse, facing accusations of <a href="/blog/can-anyone-help-in-writing-an-assignment-without-plagiarism">plagiarism</a>.
                    <br>
                    This guide is built to solve that problem. We will demystify the entire process, moving from the why (the core principles) to the what (the main citation styles at your university) and the how (the practical skills of <a href="/blog/how-to-paraphrase-checking-a-complete-guide-with-examples">paraphrasing</a> and <a href="/blog/how-to-quote-citing-quotes-in-apa-mla-chicago">quoting</a>). Whether you're <a href="/essay-writing-help-services">writing your first undergraduate essay</a> or your final <a href="/dissertation-writing-help-services">dissertation</a>, consider this your definitive <a href="/academic-integrity">UK referencing handbook</a>.
                </p>
                
                {{-- BUTTONS UNDER HERO --}}
                <div class="pillar-hero-btns">
                    <a href="/" class="pillar-btn-bg">Request Assignment Help</a>
                    <a href="/writers" class="pillar-btn">Get Expert Help</a>
                </div>
            </div>

            <div class="pillar-hero-image">
                <img src="/images/pillar-pages/hero/Mastering Referencing & Citation Styles for UK Students.webp" alt="Mastering Referencing & Citation Styles for UK Students"
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
                    <h3>Referencing Citation Guide - Overview</h3>
                    <ul>
                        <li><a href="#section-1">The Core Principles: Why Referencing is Non-Negotiable</a></li>
                        <li><a href="#section-2">Deep Dive into the UK’s Top 4 Referencing Styles</a></li>
                        <li><a href="#section-3">The Technical Skill: Quotations, Paraphrasing, and Summarising</a></li>
                        <li><a href="#section-4">Tools and Final Checks (The Commercial Transition)</a></li>                        
                        <li><a href="#faq">Referencing Citation Guide FAQs</a></li>
                    </ul>
                </div>
            </aside>


            {{-- main content --}}
            <div class="pillar-content">

                {{-- section 1 --}}
                <section id="section-1" style="margin-bottom:40px;">
                    <h2>The Core Principles: Why Referencing is Non-Negotiable</h2>
                    <p class="mb10">Before you learn the rules of how to reference, you must understand why you're doing it. It’s not just a box-ticking exercise for your tutors.</p>

                    <h3>From Honesty to Authority: The Purpose of Academic Citation</h3>
                    <p>Referencing serves two primary, non-negotiable purposes in UK academia:</p>

                    <ul class="pillar-ul mb10">
                        <li><b>1. Academic Honesty: </b>This is the most critical reason. By <a href="/critical-thinking-guide">citing your sources</a>, you are giving full and proper credit to the original authors of the ideas, words, and research you have used. This is the fundamental way you <a href="/blog/what-is-self-plagiarism-definition-how-to-avoid-it">avoid plagiarism</a>, which is a serious academic offence.</li>
                        <li><b>2. Building Authority: </b>Your reference list is a map of your research. It proves to your marker that you have engaged deeply with the scholarly dialogue in your field. Citing high-quality, relevant sources (like <a href="/blog/how-to-cite-a-journal-article-apa-mla-chicago">journal articles</a>, <a href="/uk/academic/">academic books</a>, and official reports) lends credibility and authority to your own arguments.</li>
                        
                    </ul>

                    <p class="mb10">In short, <a href="/blog/mastering-harvard-referencing-a-guide-for-uk-students">referencing</a> is how you show you are a responsible member of the academic community, one who respects the work of others and builds upon it honestly.</p>

                    <h3>Citation vs. Reference List: Understanding the Two Halves</h3>
                    <p>
                        Many students confuse these terms, but they are two halves of the same system.
                    </p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>The In-Text Citation: </b>This is the short marker you place inside your main text, immediately after you have used an idea, quote, or piece of data from a source. It acts like a signpost.</li>
                        <li><b>The Reference List: </b>This is the full list at the end of your document. It contains the complete, detailed information for every source you cited. It's the "destination" that your in-text "signpost" was pointing to, allowing your reader to find the exact source themselves.</li>
                    </ul>

                    <p class="mb10"><b>Analogy: </b>The in-text citation (e.g., Smith, 2024) is the roadmap. The reference list entry (e.g., Smith, J. (2024) The Book of Everything. London: Publisher.) is the complete destination address. You need both to be useful.</p>

                    <h3>Common Referencing Mistakes UK Students Make (and How to Avoid Them)</h3>
                    <p>Markers see the same simple errors time and again. Avoiding these is the first step to a better grade:</p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>Inconsistency: </b>Switching formats halfway through (e.g., using (Smith, 2024) in one place and Smith (2024) in another without reason, or mixing up comma and full-stop rules).</li>
                        <li><b>Missing Page Numbers: </b>When you directly quote a source, you must include the page number (e.g., (Smith, 2024, p. 45)). Forgetting this is a major error.</li>
                        <li><b>Forgetting "Summary" Citations: </b>Even if you spend an entire paragraph summarising an author's argument (without quoting), you must still place a citation at the end of that paragraph.</li>
                        <li><b>Relying on Generators: </b>Using an online "citation generator" and copying the output without checking it. These tools frequently make small but obvious mistakes (e.g., wrong capitalisation, missing publisher details).</li>
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
                    <h2>Deep Dive into the UK’s Top 4 Referencing Styles</h2>
                    <p class="mb10">This is the core of your knowledge. While hundreds of styles exist, your UK university course will almost certainly require one of these four. Always check your module handbook for the exact style required.</p>

                    <h3>Harvard Referencing: The UK Standard for the Humanities</h3>
                    <p class="mb10">Harvard is the most common style in the UK, especially in the Humanities, Social Sciences, and <a href="/business-assignment-writing-help">Business</a>. It is an "author-date" system, which makes it very clear to read.</p>

                    <div class="table-responsive mb10">
                        <table class="table-style-two mt20">
                            <thead>
                                <tr>
                                    <th>Source Type</th>
                                    <th>In-Text Citation Example</th>
                                    <th>Reference List Example</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Book</td>
                                    <td>...as discussed by Smith (2023, p. 15).</td>
                                    <td>Smith, J. (2023) <i>A guide to referencing</i>. London: Academic Press.</td>
                                </tr>
                                <tr>
                                    <td>Journal</td>
                                    <td>Research shows (Jones et al., 2022)...</td>
                                    <td>Jones, A., Patel, B. and Chen, L. (2022) ‘A study of academic skills’, <i>Journal of University Studies</i>, 14(2), pp. 112-130.</td>
                                </tr>
                                <tr>
                                    <td>Website</td>
                                    <td>...according to the BBC (2024).</td>
                                    <td>BBC (2024) <i>University funding models</i>. Available at: <a href="https://www.bbc.co.uk/news/education-01234">https://www.bbc.co.uk/news/education-01234</a> (Accessed: 1 November 2025).</td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </div>


                    <h3>APA Style: A Must-Know for Psychology and Social Sciences</h3>
                    <p class="mb10">APA (<a href="/blog/the-ultimate-guide-to-citing-sources-in-apa-7th-edition">American Psychological Association</a>) 7th Edition is the global standard in Psychology, Education, and many other Social Sciences. It is also an "author-date" system but has very precise rules on punctuation and capitalisation.</p>

                    <div class="table-responsive mb10">
                        <table class="table-style-two mt20">
                            <thead>
                                <tr>
                                    <th>Source Type</th>
                                    <th>In-Text Citation Example</th>
                                    <th>Reference List Example</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Book</td>
                                    <td>...as discussed by Smith (2023, p. 15).</td>
                                    <td>Smith, J. (2023) <i>A guide to referencing</i>. Academic Press.</td>
                                </tr>
                                <tr>
                                    <td>Journal</td>
                                    <td>Research shows (Jones et al., 2022)...</td>
                                    <td>Jones, A., Patel, B. and Chen, L. (2022) ‘A study of academic skills’, <i>Journal of University Studies</i>, 14(2), 112-130. <a href="https://doi.org/10.1234/jus.0123">https://doi.org/10.1234/jus.0123</a></td>
                                </tr>
                                <tr>
                                    <td>Website</td>
                                    <td>...according to the BBC (2024).</td>
                                    <td>BBC (2024) <i>University funding models</i>. <a href="https://www.bbc.co.uk/news/education-01234">https://www.bbc.co.uk/news/education-01234</a></td>
                                </tr>
                            </tbody>
                        </table>
                        
                    </div>
                    

                    <h3>OSCOLA: The Essential Style for UK Law Students</h3>
                    <p>OSCOLA (Oxford University Standard for Citation of Legal Authorities) is used almost exclusively in <a href="/uk/law-dissertation-writing-services">UK Law schools</a>. It is a footnote system. You place a superscript number in your text (like this: ¹), and the full citation appears at the bottom of the page.</p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li>In-Text Example: The ruling in Smith v Jones confirmed this principle.¹</li>
                        <li>Footnote Example (Case Law): ¹Smith v Jones [2023] UKSC 15, [2023] 1 AC 104 at [45].</li>
                        <li>Footnote Example (Statute): ²Human Rights Act 1998, s 6(1).</li>
                    </ul>

                    <h3>MLA, Chicago, and Vancouver: Knowing When to Use Others</h3>
                    <p>You may encounter these in specific fields:</p>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>MLA (Modern Language Association): </b>Used in <a href="/blog/dissertation-and-thesis-topics-for-english-literature">English Literature</a> and other Arts subjects. It's an "author-page" system (e.g., (Smith 45)).</li>
                        <li><b>Chicago (Notes and Bibliography): </b>Used in <a href="/history-assignment-writing-help">History</a> and some Arts subjects. It's a footnote system, similar to OSCOLA but for non-legal sources.</li>
                        <li><b>Vancouver: </b>A numbered system used widely in Medicine and the hard sciences. You place a number in brackets (e.g., [1]) and your reference list is a numbered list.</li>
                    </ul>

                </div>

                <div class="mb10">
                    <img src="/images/pillar-pages/Mastering Referencing & Citation Styles for UK Students.webp" alt="Mastering Referencing & Citation Styles for UK Students"
                    loading="lazy">
                </div>

                <div id="section-3" style="margin-bottom:40px;">
                    <h2>The Technical Skill: Quotations, Paraphrasing, and Summarising</h2>
                    <p class="mb10">Knowing the format is easy. Knowing what to reference is the hard part. This skill is what truly separates great students from the rest and is the key to avoiding plagiarism.</p>

                    <h3>Mastering Paraphrasing: The Key to Original Thought</h3>
                    <p>Paraphrasing is restating an author's idea in your own words and your own sentence structure. Simply changing a few words with a thesaurus is not paraphrasing—it is plagiarism.
                        <br>
                        Use this 4-step method:
                    </p>

                    <ul class="pillar-ul mb10">                        
                        <li><b>1. Read: </b>Read the original passage or page until you understand its meaning 100%.</li>
                        <li><b>2. Close: </b>Close the book or minimise the web browser.</li>
                        <li><b>3. Write: </b>Write down the idea from your own memory in a new document, as if you were explaining it to a friend.</li>
                        <li><b>4. Check: </b>Open the original source again. Compare your version to the original to ensure you have not accidentally copied the sentence structure or unique phrasing. Finally, add your citation (e.g., (Smith, 2023)).</li>
                    </ul>

                    <h3>When to Quote and When to Summarise</h3>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>Quote (Use sparingly): </b>Use a direct quotation only when the exact wording is important. For example:
                            <ul class="pillar-ul custom-bullet" style="margin-top: 5px;">
                                <li>You are analysing an author's specific language or tone.</li>
                                <li>You are using a technical definition.</li>
                                <li>The original phrasing is so powerful that paraphrasing would lessen its impact.</li>
                                <li><b>Rule: </b>Short quotes (under 40 words) go in "quotation marks." Long quotes (over 40 words) should be in an indented block quote with no quotation marks.</li>
                            </ul>
                        </li>
                        <li><b>Paraphrase/Summarise (Use often): </b>This should be your default. It proves you have understood the idea, not just copied it. It is much better for the flow of your <a href="/essay-writing-help-services">essay</a> and shows a higher level of <a href="/blog/9-simple-ways-to-improve-your-academic-writing-skills">academic skill</a>.</li>
                    </ul>

                    <h3>Handling Complex Sources: Secondary Referencing & AI</h3>
                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>Secondary Referencing ("Cited in"): </b>What if you read a book by Smith, who quotes an older, original study by Jones? Ideally, you should find the Jones study and read it. If you can't, you must cite both.
                            <ul class="pillar-ul custom-bullet" style="margin-top: 5px;">
                                <li><b>Harvard Example: </b>Jones (2010, cited in Smith, 2023, p. 50) argues that...</li>
                                <li>In your reference list, you would only include Smith (2023), as that is the source you physically read.</li>
                            </ul>
                        </li>
                        <li><b>Citing Generative AI (e.g., ChatGPT): </b>University policies are changing fast, but most now require you to acknowledge AI use. You must check your department's specific policy.
                            <ul class="pillar-ul custom-bullet" style="margin-top: 5px;">
                                <li><b>APA 7th Example: </b>When paraphrasing text from <a href="/blog/how-to-write-an-essay-with-chatgpt-tips-and-examples">ChatGPT</a>, the APA advises treating it as a "personal communication" or citing the software developer.</li>
                                <li><b>Example Citation: </b>(OpenAI, 2024)</li>
                                <li><b>Reference List Entry: </b>OpenAI. (2024). *ChatGPT* (Oct 30 version) [Large language model]. https://chat.openai.com</li>
                            </ul>
                        </li>
                        
                    </ul>
                    {{-- BUTTONS-2 --}}
                    <div class="pillar-hero-btns">
                        <a href="/review" class="pillar-btn">Read Student Reviews</a>
                    </div>
                </div>

                <div id="section-4" style="margin-bottom:40px;">
                    <h2>Tools and Final Checks (The Commercial Transition)</h2>
                    <p class="mb10">You've learned the why, what, and how. Now, let's look at the tools that can help—and their critical limitations.</p>

                    <h3>Digital Tools to Aid Your Referencing Process</h3>
                    <p>Manually tracking 50+ sources is tough. Referencing management software can be a lifesaver for organising your research.</p>

                    <ul class="pillar-ul custom-bullet mb10">
                        <li><b>Mendeley & Zotero: </b>These are free, powerful tools. They act as a digital library for your PDFs and <a href="/research-paper-writing-services">research papers</a>. You can highlight, take notes, and then automatically generate a reference list in your chosen style.</li>
                        <li><b>Online Citation Generators (Use with Caution): </b>Quick, free tools are tempting. You paste a URL, and it gives you a citation.</li>
                    </ul>

                    <p><b>Warning: </b>These free generators are notoriously unreliable. They often miss publisher information, get <a href="/blog/capitalisation-rules-in-english-quick-guide-examples">capitalisation</a> wrong, or misidentify the source type. Never copy-paste from a generator without manually checking every single detail against the correct style guide.</p>

                    <h3>The Difference Between Good Referencing and Perfect Referencing</h3>
                    <p>
                        Good referencing is having all your sources listed. Perfect referencing is an art. It's about flawless consistency, correct punctuation down to the last comma, and ensuring every in-text citation perfectly matches the reference list.
                        <br>
                        This is where students lose easy marks. A tool can't spot that you've written (Smith & Jones, 2023) in one place and (Smith and Jones, 2023) in another. It won't know that your version of Harvard requires page numbers for paraphrases, but your friend's department doesn't.
                        <br>
                        This level of detail requires a human expert who understands the specific, nuanced rules of <a href="https://upen.ac.uk/the-academic-policy-ecosystem/">UK academic policy</a>.
                    </p>

                    <h3>Guaranteeing Referencing Perfection (Your Ethical Solution)</h3>
                    <p>
                        This is the 10% commercial hook, as requested. <br>
                        The final step before submission is the most stressful. You're tired, and staring at a 30-item <a href="/blog/how-to-write-an-annotated-bibliography-for-top-grades">bibliography</a> is the last thing you want to do. <br>
                        This is where we come in. Our service is not just about grammar; it's about <a href="/academic-integrity/">academic integrity</a>. <br>
                        Our expert editors, all educated in UK universities, specialise in this. We will meticulously go through your entire document, line by line, and best <a href="/">assignment help</a>:
                    </p>
                    <ul class="pillar-ul custom-bullet mb10">                        
                        <li>Correct every citation to be 100% consistent with your required style (<a href="/blog/mastering-harvard-referencing-a-guide-for-uk-students">Harvard</a>, APA, OSCOLA, etc.).</li>
                        <li>Cross-check every in-text citation against your reference list to ensure there are no missing or mismatched sources.</li>
                        <li>Fix all punctuation, capitalisation, and formatting errors that automated tools miss.</li>
                        <li>Flag any paraphrased sections that look too close to a source or any quotes missing a page number, giving you complete protection against accidental plagiarism.</li>
                    </ul>

                    <p>Don't risk your grade on a small, avoidable referencing error. Let an expert provide the final polish that guarantees your work is flawless.</p>
                    {{-- BUTTONS-3 --}}
                    <div class="pillar-hero-btns">
                        <a href="/blog" class="pillar-btn">Read the assignment help blog</a>
                    </div>
                </div>                

                <div id="faq" style="margin-bottom:40px;">
                    <h2>Frequently Asked Questions</h2>

                    <div class="faq-item">
                        <h3>1. How can I tell which referencing style my UK university uses?</h3>
                        <p>Each university, and often each department, has its own preferred style. Check your module handbook, assessment brief, or your department’s academic writing guide. If still unsure, ask your tutor before starting your assignment — using the wrong style can cost easy marks.</p>
                    </div>

                    <div class="faq-item">
                        <h3>2. What should I do if two of my lecturers ask for different referencing styles?</h3>
                        <p>It’s common across multi-module degrees. Always follow the style required for that specific module or individual assignment. Keep separate reference templates for each style to avoid confusion when switching between Harvard, APA, or OSCOLA formats.</p>
                    </div>

                    <div class="faq-item">
                        <h3>3. Can I mix different referencing styles in one essay?</h3>
                        <p>No. Mixing styles (e.g., using Harvard for books and APA for journals) looks inconsistent and unprofessional. Pick one style as instructed and apply it consistently throughout — including in-text citations, footnotes, and your bibliography.</p>
                    </div>

                    <div class="faq-item">
                        <h3>4. What if the source I want to reference doesn’t fit any standard format?</h3>
                        <p>This happens often with podcasts, YouTube videos, or online lectures. In such cases, use the closest possible source type and include all essential details (author/speaker, date, title, and URL). If uncertain, include as much verifiable information as possible and remain consistent across your work.</p>
                    </div>

                    <div class="faq-item">
                        <h3>5. Does referencing increase my word count?</h3>
                        <p>Generally, in-text citations are included in your word count, but the reference list or bibliography isn’t. However, policies vary by university, so always check your assignment brief or ask your module leader to confirm.</p>
                    </div>

                    <div class="faq-item">
                        <h3>6. Are footnotes and endnotes the same thing in academic writing?</h3>
                        <p>Not exactly. Footnotes appear at the bottom of each page, while endnotes are listed together at the end of your essay or chapter. OSCOLA and Chicago styles prefer footnotes, but some journals and postgraduate theses may request endnotes for cleaner formatting.</p>
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