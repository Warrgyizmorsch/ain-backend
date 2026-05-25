@extends('frontend-layouts.app')

@section('content')

	<style>
		.privacy-policy-section {
			background: #fffaf4;
			padding: 3rem;
			border-radius: 1.5rem;
			box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
			max-width: 1000px;
			margin: 3rem auto;
			color: #000;
			text-align: justify;
		}

		.link {
			color: #3F159A;
			font-weight: 600;
		}

		.main-heading {
			font-weight: 600;
		}

		.sub-heading {
			font-weight: 600;
			font-size: 2rem;
			text-align: left;
		}

		.heading3 {
			font-weight: 500;
			font-size: 1.6rem;
			text-align: left;
		}

		ul {
			list-style-type: disc;
			padding-left: 1.5rem;
			margin-bottom: 1rem;
			color: #2d2d2d;
			font-size: 1rem;
			line-height: 1.6;
		}

		li {
			margin-bottom: 0.5rem;
		}

		.blog-header .cover {
			background: url('/assets/bg/blog-bg.png') no-repeat center center;
			background-size: cover;
			min-height: 210px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 30px;
			font-weight: 800;
			text-transform: uppercase;
			letter-spacing: 1.5px;
			color: white;
		}

		@media (max-width: 768px) {
			.privacy-policy-section {
				width: 90%;
			}

			.sub-heading {
				font-size: 2rem;
			}

			.heading3 {
				font-size: 1.4rem;
			}

			.blog-header .cover {
				font-size: 22px;
				min-height: 150px;
			}
		}

		@media (max-width: 425px) {
			.sub-heading {
				font-size: 1.5rem;
			}

			.heading3 {
				font-size: 1.2rem;
			}
		}
	</style>

	<section class="blog-header">
		<div class="cover">
			<h1 class="main-heading">Our Guaranty Policy</h1>
		</div>
	</section>

	<section class="privacy-policy-section">
		<div class="auto-container1">
			<div class="section">

				<h2 class="sub-heading">Our Commitment to Excellence: Trusted by UK Students, Delivered with Care</h2>
				<ul>
					<li>
						At Assignment In Need, we do not believe in making unrealistic promises. What we offer is clear,
						practical, and fully guaranteed. If tight deadlines are stressing you out, assignment requirements
						feel
						confusing, or you simply need reliable academic help in the UK, our team is always ready to support
						you.
					</li>
				</ul>
				<ul>
					<li>
						Our transparent assurance policies mean your work is handled by professionals who genuinely care
						about
						your academic success. From the moment you place an order, you are guided at every step to ensure
						clarity, confidence, and high-quality outcomes.
					</li>
				</ul>

				<h2 class="sub-heading">Highly Qualified and Certified UK Academic Writers You Can Trust</h2>
				<ul>
					<li>
						Your academic success deserves expert attention. That is why Assignment In Need works exclusively
						with
						experienced, certified, and highly qualified academic writers familiar with UK university standards.
					</li>
				</ul>
				<ul>
					<li>
						Our writers hold advanced qualifications such as Master’s and PhDs in their respective disciplines
						and
						have years of hands-on experience delivering essays, coursework, dissertations, and research papers
						for
						UK institutions. Beyond qualifications, they are passionate about academic excellence and continuous
						learning.
					</li>
				</ul>
				<ul>
					<li>
						Each assignment is matched with a subject-specific expert who understands your topic in depth. The
						result
						is well-researched, original, and academically sound work delivered within the agreed deadline.
					</li>
				</ul>

				<h2 class="sub-heading">Free Turnitin Report: Transparency You Can Rely On</h2>
				<ul>
					<li>
						Plagiarism concerns are common among UK students-and rightly so. To address this, we ensure every
						assignment is 100% original and checked using Turnitin, a trusted plagiarism-detection tool widely
						recognised by UK universities.
					</li>
				</ul>
				<ul>
					<li>
						Upon request, we provide a free Turnitin originality report, allowing you to verify the authenticity
						of
						your work. Our internal quality assurance team carefully reviews every assignment before delivery to
						ensure it meets academic standards and originality requirements. You can submit your work with
						complete
						confidence.
					</li>
				</ul>

				<h2 class="sub-heading">Guaranteed Discounts: Affordable Academic Help for UK Students</h2>
				<ul>
					<li>
						We understand that many students in the UK work within a budget. To make quality academic assistance
						accessible, we offer guaranteed discounts without compromising standards.
					</li>
				</ul>
				<ul>
					<li>
						New students receive attractive introductory discounts.
					</li>
				</ul>
				<ul>
					<li>
						Returning clients enjoy exclusive loyalty benefits.
					</li>
				</ul>
				<ul>
					<li>
						Referral rewards allow you to earn a 5% discount for every successful referral.
					</li>
				</ul>
				<ul>
					<li>
						Our goal is to deliver dependable, high-quality academic support that is also affordable.
					</li>
				</ul>


				<h2 class="sub-heading">Unlimited Revisions Until You Are Fully Satisfied</h2>
				<ul>
					<li>
						Your satisfaction matters the most to us. That is why we offer <b>unlimited free revisions</b>,
						ensuring
						your
						assignment meets your expectations down to the last detail.
					</li>
				</ul>
				<ul>
					<li>
						If you require changes such as restructuring, rewriting sections, or incorporating feedback, simply
						let
						us know. We continue working with you until you are completely satisfied with the final submission.
						Our
						revision policy is designed to support your academic confidence.
					</li>
				</ul>

				<h2 class="sub-heading">24/7 Customer Support: Always Available for UK Students</h2>
				<ul>
					<li>
						Academic challenges do not follow office hours, and neither do we. Our friendly and professional
						customer support team is available 24/7, including weekends and holidays.
					</li>
				</ul>
				<ul>
					<li>
						Whether you need help placing an order, tracking progress, or requesting revisions, we are always
						just a
						message away. Our support team is trained to respond quickly and effectively, ensuring a smooth and
						stress-free experience.
					</li>
				</ul>
				<ul>
					<li>
						<b>Email:</b> info@assignnmentinneed.com
					</li>
				</ul>



				<h2 class="sub-heading">100% Plagiarism-Free Work: Originality Is Non-Negotiable</h2>
				<ul>
					<li>
						Originality is not optional-it is essential. At Assignment In Need, every assignment goes through a
						strict originality-check process that includes both manual review and Turnitin verification.
					</li>
				</ul>
				<ul>
					<li>
						We focus on critical thinking, proper referencing, and genuine academic research rather than simply
						passing plagiarism checks. When you choose us, you receive work that adds real academic value and
						aligns
						with UK academic standards of integrity.
					</li>
				</ul>

				<h2 class="sub-heading">Why Choose Assignment In Need?</h2>

				<ul>
					<li>1. Experienced UK-focused writers across all subjects</li>
					<li>2. Guaranteed on-time delivery for every order</li>
					<li>3. Unlimited free edits until satisfaction</li>
					<li>4. 100% plagiarism-free, quality-checked work</li>
					<li>5. Affordable pricing with generous discounts</li>
					<li>6. 24/7 expert support whenever you need it</li>
				</ul>

				<h2 class="sub-heading">Final Thoughts</h2>
				<ul>
					<li>
						Choosing the right academic assistance service can make a significant difference to both your grades
						and
						peace of mind. At Assignment In Need, we do more than deliver assignments-we provide reliability,
						transparency, and student-first support trusted by UK learners.
					</li>
				</ul>
				<ul>
					<li>
						Why settle for less? Join the growing number of students who rely on us for timely, high-quality
						academic
						help. With our experienced writers, clear policies, and commitment to excellence, your educational
						journey is in safe hands.
					</li>
				</ul>


			</div>

		</div>
		</div>
	</section>

@endsection