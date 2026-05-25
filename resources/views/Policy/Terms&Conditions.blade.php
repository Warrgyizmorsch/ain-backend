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
			<h1 class="main-heading">Assignment in Need - Terms and Conditions</h1>
		</div>
	</section>

	<section class="privacy-policy-section">
		<div class="auto-container1">
			<div class="section">

				<div class="terms-wrapper">

					<ul>
						<li>
							Welcome to Assignment In Need. By accessing or using our website and services, you agree to be
							bound
							by these Terms and Conditions. Please read them carefully. If you do not agree with any part of
							these Terms, you must not use our services.
						</li>
					</ul>

					<h2 class="sub-heading">1. Definitions</h2>

					<ul>
						<li>
							<strong>1. Company, We, Our, Us:</strong> Refers to the Assignment in Need.
						</li>
						<li>
							<strong>2. Client, You, Your:</strong> Means any individual or entity using our Services.
						</li>
						<li>
							<strong>3. Services</strong> Refers to the academic support services and related offerings
							provided by the Company as described on our Website. This includes research assistance, writing
							guidance, and editing services.
						</li>
						<li>
							<strong>4. Website:</strong> Means our website is located at
							<a class="link" href="http://www.assignnmentinneed.com">www.assignnmentinneed.com</a>.
						</li>
						<li>
							<strong>5. Order:</strong> Refers to a request for Services placed by the Client through our
							Website, specifying the scope of work, requirements, and deadline.
						</li>
						<li>
							<strong>6. Service Deliverable:</strong> Refers to the written material or other output provided
							to the Client as a result of an Order.
						</li>
						<li>
							<strong>7. Revision:</strong> A request by the Client for modifications to the Service
							Deliverable based on the original Order requirements.
						</li>
						<li>
							<strong>8. Support Team:</strong> The department responsible for assisting Clients with
							inquiries and order-related matters.
						</li>
					</ul>

					<h2 class="sub-heading">2. Use of Services</h2>
					<ul>
						<li>
							<strong>2.1 Eligibility:</strong> By using our Services, you confirm that you are at least 18
							years of age or have the legal capacity to enter into a binding agreement in the United Kingdom,
							or are using the Services under the supervision of a parent or legal guardian with such
							capacity.
						</li>
						<li>
							<strong>2.2 Academic Integrity:</strong> Our Services are provided solely for academic support,
							research, and reference purposes. We strongly emphasise the importance of academic integrity and
							ethical conduct. The Service Deliverables are intended to serve as models or guides to aid your
							learning process. You are solely responsible for ensuring that any work you submit is your own
							original work and properly cited in accordance with UK academic standards. We do not endorse or
							facilitate plagiarism or any other form of academic dishonesty.
						</li>
					</ul>

					<h2 class="sub-heading">3. Ordering Process</h2>

					<ul>
						<li>
							<strong>3.1 Placing an Order:</strong> To place an Order, you must complete the order form
							available on our Website and provide all necessary information accurately and completely,
							including the subject, academic level, deadline, specific instructions, and any required
							materials. It is your responsibility to ensure that all information provided is clear, accurate,
							and sufficient for us to fulfil your request in the United Kingdom.
						</li>
						<li>
							<strong>3.2 Payment:</strong> Full payment for the Services is required at the time of placing
							the Order. We accept payments through the methods specified on our Website. By placing an Order,
							you agree to pay the total price indicated. Prices are as displayed on our Website and may be
							subject to change without notice, but changes will not affect Orders already placed.
						</li>
						<li>
							<strong>3.3 Order Confirmation:</strong> Upon successful payment, you will receive an email
							confirmation of your Order, including an order reference number and a summary of the details.
							This confirmation constitutes acceptance of your Order and forms a binding agreement between you
							and the Company, subject to these Terms and Conditions.
						</li>
					</ul>


					<h2 class="sub-heading">4. Revisions and Refunds</h2>

					<ul>
						<li>
							<strong>4.1 Revisions:</strong> We offer a revision period as specified on our Website ([Insert
							Number] days/weeks) from the date of delivery of the Service Deliverable. Revision requests must
							be based on the original Order instructions. Requests for revisions that deviate from the
							initial instructions may be subject to additional charges, at our sole discretion. We strive to
							address revision requests promptly and effectively for clients in the UK.
						</li>
						<li>
							<strong>4.2 Refunds:</strong> Our refund policy is outlined separately on our Website at the
							Refund Policy Page. Please refer to that policy for detailed information on refund eligibility,
							procedures, and timeframes. Refund requests will be considered on a case-by-case basis in
							accordance with our Refund Policy, which takes into account factors such as non-delivery (due to
							our error), significant quality issues that cannot be reasonably rectified by revision, and
							technical errors resulting in double payment.
						</li>
					</ul>

					<h2 class="sub-heading">5. Privacy and Data Protection</h2>

					<ul>
						<li>
							We are committed to protecting your privacy and handling your personal data in accordance with
							applicable UK data protection laws, including the UK General Data Protection Regulation (UK
							GDPR).
							Our Privacy Policy, available at the Privacy Policy Page, provides detailed information about
							how we
							collect, use, store, and protect your personal data. By using our Services, you consent to the
							processing of your personal data as described in our Privacy Policy. We will not share your
							personal
							information with third parties except as necessary to provide our Services or as required by UK
							law.
							We will obtain your explicit consent for marketing communications in accordance with PECR
							(Privacy
							and Electronic Communications Regulations).
						</li>
					</ul>

					<h2 class="sub-heading">6. Intellectual Property</h2>

					<ul>
						<li>
							All content on our Website, including text, graphics, logos, and software, is the property of
							Assignment In Need or its licensors and is protected by UK copyright and other intellectual
							property
							laws. The Service Deliverables provided to you are intended for your personal, non-commercial
							use
							for research and reference purposes only. You acknowledge that the Company retains copyright in
							the
							Service Deliverables. You may not reproduce, distribute, modify, or publicly display the Service
							Deliverables without our prior written consent, except for reasonable personal use for
							educational
							purposes with proper citation.
						</li>
					</ul>


					<h2 class="sub-heading">7. Limitation of Liability</h2>

					<ul>
						<li>
							To the maximum extent permitted by law in the United Kingdom, the Company shall not be liable
							for
							any indirect, incidental, special, consequential, or punitive damages arising out of or in
							connection with your use of our Services or the Service Deliverables. Our total liability to you
							for
							any claim arising out of or in connection with these Terms and Conditions or our Services shall
							not
							exceed the amount you paid for the specific Service in question. Nothing in these Terms and
							Conditions shall exclude or limit our liability for death or personal injury caused by our
							negligence, for fraud or fraudulent misrepresentation, or for any other liability that cannot be
							excluded or limited under applicable UK law.
						</li>
					</ul>

					<h2 class="sub-heading">8. Termination</h2>

					<ul>
						<li>
							We reserve the right to suspend or terminate your access to our Services at any time, with or
							without notice, if we believe you have violated these Terms and Conditions, engaged in illegal
							or
							unethical activities, or for any other reason at our sole discretion. You may also terminate
							your
							account at any time. Upon termination, your right to use the Services will cease immediately.
						</li>
					</ul>

					<h2 class="sub-heading">9. Amendments</h2>

					<ul>
						<li>
							We may update or modify these Terms and Conditions at any time without prior notice. Any changes
							will be effective upon the posting of the revised Terms on our Website with an updated "Date of
							Effectiveness." Your continued use of our Services after the posting of changes constitutes your
							acceptance of the revised Terms. We encourage you to review these Terms periodically for any
							updates.
						</li>
					</ul>

					<h2 class="sub-heading">10. Governing Law and Jurisdiction</h2>

					<ul>
						<li>
							These Terms and Conditions shall be governed by and construed in accordance with the laws of
							England
							and Wales. Any dispute arising out of or in connection with these Terms and Conditions shall be
							subject to the exclusive jurisdiction of the courts of England and Wales.
						</li>
					</ul>

					<h2 class="sub-heading">11. Contact Details</h2>

					<ul>
						<li>
							If you have any questions or concerns regarding these Terms and Conditions, please contact us
							at:
						</li>
					</ul>

					<ul>
						<li><strong>Email:</strong> <a class="link"
								href="mailto:info@assignnmentinneed.com">info@assignnmentinneed.com</a></li>
						<li><strong>Phone:</strong> +44 2037695831</li>
						<li><strong>Address:</strong> International House, Constance Street, London E16</li>
					</ul>


				</div>

			</div>
		</div>
	</section>

@endsection