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

		.main-heading {
			font-weight: 600;
		}

		.sub-heading {
			font-weight: 600;
			font-size: 2.5rem;
			text-align: left;
		}

		.heading3 {
			font-weight: 500;
			font-size: 1.6rem;
			text-align: left;
		}

		ul {
			list-style-type: disc;
			/* Show bullet points */
			padding-left: 1.5rem;
			/* Indent bullets */
			margin-bottom: 1rem;
			/* Add space after the list */
			color: #2d2d2d;
			/* Text color (dark gray) */
			font-size: 1rem;
			/* Font size */
			line-height: 1.6;
			/* Better readability */
		}

		li {
			margin-bottom: 0.5rem;
			/* Space between list items */
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
			<h1 class="main-heading">Refund Policy</h1>
		</div>
	</section>

	<section class="privacy-policy-section">
		<div class="auto-container1">
			<div class="section">
				<ul>
					<li>
						At <b>Assignment In Need</b>, we are most concerned with your satisfaction. While we strive to
						deliver
						the best
						assignment help UK every time, we also understand that occasionally issues could arise. Our refund
						policy explains when you may be eligible for a refund and how we handle such matters transparently
						and
						fairly.
					</li>
				</ul>

				<h2 class="sub-heading">1. No Expert Assigned</h2>
				<ul>
					<li>
						If we are unable to provide a professional for your assignment after accepting full payment, you
						have the
						following options:
					</li>
				</ul>
				<ul>
					<li>1. You may request a rebooking of the same value as your initial payment.</li>
					<li>2. Additionally, we will provide a £10 credit free of charge as a gesture of goodwill, which can be
						used on your next order.</li>
				</ul>
				<ul>
					<li>
						This ensures that your funds are never lost and that you receive the best value for your money.
					</li>
				</ul>

				<h2 class="sub-heading">2. Missed Deadlines</h2>
				<ul>
					<li>
						If your assignment is not submitted on time due to unavailability or negligence on the part of the
						assigned expert, you are entitled to a full refund under the following conditions:
					</li>
				</ul>
				<ul>
					<li>1. The delay must be caused by our team member and not due to incomplete or unclear instructions
						from your side.</li>
					<li>2. You must notify our customer support team immediately regarding the missed deadline.</li>
				</ul>
				<ul>
					<li>
						You may choose between:
					</li>
				</ul>
				<ul>
					<li>1. A 100% refund via the original mode of payment, or
					</li>
					<li>2. Carrying forward the full amount to a future project.</li>
				</ul>
				<ul>
					<li>
						In such cases, appropriate action will be taken against the assigned expert, and a thorough review
						will
						be conducted before any conclusion is made.
					</li>
				</ul>
				<ul>
					<li>
						<strong>Alert:</strong> If the delay is caused by incorrect or incomplete information provided by
						the
						client, partial or full refunds may not be offered.
					</li>
				</ul>

				<h2 class="sub-heading">3. Failing Grades</h2>
				<ul>
					<li>
						If you receive a failing grade on the work we created, you may be eligible for a refund under the
						following conditions:
					</li>
				</ul>
				<ul>
					<li>1.You must submit a detailed feedback report or educational proof (from your tutor or institution)
						within 60 days of the order delivery.
					</li>
					<li>2. Our in-house quality team will assess the feedback to determine if the failure was due to a lack
						of writing quality or deviation from the original brief.
					</li>
				</ul>
				<ul>
					<li>
						Based on the findings:
					</li>
				</ul>
				<ul>
					<li>1. You may receive a 50% partial refund or a 100% full refund.</li>
					<li>2. Refund decisions are made by our customer support team after careful evaluation.</li>
				</ul>
				<ul>
					<li>
						We strongly recommend reviewing the submitted work in light of your tutor’s feedback to avoid
						potential
						grade issues.
					</li>
				</ul>

				<h2 class="sub-heading">4. Important Guidelines</h2>
				<ul>
					<li>
						Refund requests must be submitted within 60 days of order delivery; requests after this period will
						not
						be considered.
					</li>
				</ul>
				<ul>
					<li>1. Refunds will be processed within 7–10 business days.</li>
					<li>2. A 15% processing and transaction fee may be deducted from the refund amount.</li>
					<li>3. All refunds will be issued exclusively through our formal email support platform:
						<br><strong>info@assignnmentinneed.com</strong>.
					</li>
				</ul>
				<ul>
					<li>
						We are committed to fairness and professionalism in all our services. Our refund policy is designed
						to
						support students while maintaining high academic standards. If you encounter any issues with your
						order,
						our support team is available to assist you promptly.
					</li>
				</ul>
			</div>
		</div>
	</section>

@endsection