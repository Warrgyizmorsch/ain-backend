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
			<h1 class="main-heading">Cancellation Policy</h1>
		</div>
	</section>

	<section class="privacy-policy-section">
		<div class="auto-container1">
			<div class="section">

				<ul>
					<li>
						We understand that students in the United Kingdom may sometimes need to cancel an order due to
						academic
						changes or personal emergencies. At Assignment In Need, we aim to handle cancellations fairly and
						transparently while respecting both our expert team and our UK-based clients. Order cancellations
						are
						accepted under the following circumstances:
					</li>
				</ul>

				<ul>
					<li><strong>1. Duplicate Orders:</strong> If a student accidentally places the same order more than
						once,
						the duplicate order can be cancelled upon request, provided work has not already begun on both
						orders.
					</li>
					<li><strong>2. Change of Academic Requirements:</strong> If your tutor or university in the UK requires
						an
						urgent change in assignment requirements after you have placed the order, you may request a
						cancellation, as long as our expert has not yet started the work.
					</li>
					<li><strong>3. Non-availability of Experts:</strong> If, for any reason, we are unable to allocate a
						suitable subject expert for your assignment, we reserve the right to cancel the order and issue a
						full refund in accordance with UK consumer fairness principles.
					</li>
					<li><strong>4. Deadline Constraints:</strong> If we are unable to deliver your assignment within the
						agreed
						deadline, you may choose to cancel the order rather than proceed.
					</li>
					<li><strong>5. Violation of Terms and Conditions:</strong> We also reserve the right to cancel an order
						if
						a client is found
						to be in breach of our Terms and Conditions, including misuse of services or unethical conduct.
					</li>
				</ul>

				<h2 class="sub-heading">
					Important Points:
				</h2>
				<ul>
					<li>Cancellation requests must be made within 48 hours (two working days) from the date of order
						confirmation.</li>
					<li>For urgent orders with deadlines of less than four days, cancellation is permitted without
						additional charges, provided the request is made promptly.</li>
					<li>Cancellation requests submitted after the allowed timeframe may not be accepted, as part of the work
						may already be in progress.</li>
					<li>All cancellation requests must be communicated to our support team as soon as possible via email or
						live chat to ensure timely processing.</li>
				</ul>



			</div>
		</div>
	</section>

@endsection