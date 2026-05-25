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


		*/ ul {
			padding-left: 20px;
			margin-top: -10px;
			margin-bottom: 20px;
		}

		ul li {
			margin-bottom: 10px;
			color: #444;
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

			.blog-card {
				padding: 20px;
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
			<h1 class="main-heading">Privacy Policy</h1>
		</div>
	</section>

	<section class="privacy-policy-section">
		<div class="auto-container1">

			<div class="section">
				<h2 class="sub-heading">1. Introduction & Who We Are</h2>
				<ul>
					<li>
						At Assignment In Need, we prioritise the security and privacy of our students and clients. This
						policy
						outlines how we handle your personal data in strict compliance with the UK General Data Protection
						Regulation (UK GDPR) and the Data Protection Act 2018.
					</li>
				</ul>
				<ul>
					<li><strong>• Website:</strong> <a
							href="https://www.assignmentinneed.com/"><b>www.assignmentinneed.com</b></a>
					</li>
					<li><strong>• Data Controller:</strong> Assignment In Need Ltd.
					</li>
					<li><strong>• Location:</strong> London, United Kingdom
					</li>
				</ul>
				<ul>
					<li>
						By using our Services, you acknowledge the practices described in this policy. We believe in
						"Privacy by
						Design," meaning we only collect what is necessary to help you succeed.
					</li>
				</ul>
			</div>

			<div class="section">
				<h2 class="sub-heading">2. At-a-Glance: Data Collection Summary</h2>
				@include('components.common-section.policy-table')
			</div>

			<div class="section">
				<h2 class="sub-heading">3. Information We Collect</h2>
				<ul>
					<li>
						To provide expert academic assistance, we collect two categories of information:
					</li>
				</ul>
				<h3>3.1 Personally Identifiable Information (PII)</h3>
				<ul>
					<li><strong>• Identity & Contact:</strong> Name, email address, phone number, and mailing address.
					</li>
					<li><strong>• Academic Data:</strong> Assignment requirements, subject matter, deadlines, and uploaded
						reference files.
					</li>
					<li><strong>• Communication:</strong> Logs of live chats, support tickets, and email exchanges.
					</li>
					<li><strong>• Transaction Data:</strong> Billing history and order status (we do not store full credit
						card numbers).
					</li>
				</ul>
				<h3>3.2 Technical & Usage Data (Non-PII)</h3>
				<ul>
					<li><strong>• Device Info:</strong> Browser type, IP address (for UK security monitoring), and operating
						system.
					</li>
					<li><strong>• Usage Patterns:</strong> Clickstream data, pages viewed, and time spent on site via
						cookies.
					</li>
				</ul>
			</div>

			<div class="section">
				<h2 class="sub-heading">4. How We Use Your Data</h2>
				<ul>
					<li>
						We process your data based on <b>Contractual Necessity, Legitimate Interests,</b> or <b>Explicit
							Consent:</b>
					</li>
				</ul>
				<ul>
					<li><strong>• Service Delivery:</strong> To assign the right expert to your task and deliver quality
						work.
					</li>
					<li><strong>• Trust & Safety:</strong> To prevent academic fraud and secure our platform.
					</li>
					<li><strong>• Customer Support:</strong> To provide 24/7 assistance to our UK and international
						students.
					</li>
					<li><strong>• Marketing:</strong> With your consent, we send discounts and academic tips (Opt-out
						anytime).
					</li>
				</ul>
			</div>

			<div class="section">
				<h2 class="sub-heading">5. Secure Data Sharing</h2>
				<ul>
					<li>
						We do <b>not</b> sell your personal data. Sharing is limited to:
					</li>
				</ul>
				<ul>
					<li><strong>1. Academic Experts:</strong> Only the specific details required to complete your
						assignment.
					</li>
					<li><strong>2. Service Providers:</strong> Trusted partners for payment processing (e.g., PayPal) and
						website hosting.
					</li>
					<li><strong>3. Legal Compliance:</strong> If required by UK law or to protect our legal rights.
					</li>
				</ul>
			</div>

			<div class="section">
				<h2 class="sub-heading">6. Your Rights Under UK GDPR</h2>
				<ul>
					<li>
						As a user in the United Kingdom, you have significant control over your data:
					</li>
				</ul>
				<ul>
					<li><strong>• Right to Access:</strong> Request a copy of all data we hold about you.
					</li>
					<li><strong>• Right to Rectification:</strong> Correct any errors in your personal records. <a
							href="https://ico.org.uk/"><b>Right to restrict processing | ICO.</b></a>
					</li>
					<li><strong>• Right to Erasure:</strong> Request the deletion of your account and data ("Right to be
						Forgotten").
					</li>
					<li><strong>• Right to Object:</strong> Stop your data from being used for direct marketing.
					</li>
					<li><strong>• Data Portability:</strong> Move your data to another service provider.
					</li>
				</ul>
				<ul>
					<li>
						To exercise these rights: Email our Data Protection team at <br><a
							href="info@assignmentinneed.com"><b>info@assignmentinneed.com.</b></a> We respond to all
						requests
						within 30 days.
					</li>
				</ul>
			</div>

			<div class="section">
				<h2 class="sub-heading">7. Security Measures</h2>
				<ul>
					<li>
						We employ industry-standard security to protect your academic integrity and personal identity:
					</li>
				</ul>
				<ul>
					<li><strong>• SSL/TLS Encryption:</strong> All data transmitted is encrypted.
					</li>
					<li><strong>• Firewalls & Access Control:</strong> Internal data access is restricted to authorised
						personnel only.
					</li>
					<li><strong>• Anonymity:</strong> Our writers do not see your full identity unless necessary for the
						task.
					</li>
				</ul>
			</div>

			<div class="section">
				<h2 class="sub-heading">8. Cookies & Tracking</h2>
				<ul>
					<li>
						We use cookies to improve your experience. You can manage these via your browser settings.
					</li>
				</ul>
				<ul>
					<li><strong>• Essential Cookies:</strong> Required for logging into your account.
					</li>
					<li><strong>• Performance Cookies:</strong> Help us understand which academic subjects are most in
						demand.
					</li>
				</ul>
			</div>

			<div class="section">
				<h2 class="sub-heading">9. Contact Our Privacy Team</h2>
				<ul>
					<li>
						For any concerns regarding your data or this policy, please reach out to us:
					</li>
				</ul>
				<ul>
					<li><strong>• Address:</strong> International House, Constance Street, London E16, United Kingdom
					</li>
					<li><strong>• Email:</strong> <a href="info@assignmentinneed.com"><b>info@assignmentinneed.com </b></a>
					</li>
					<li><strong>• Phone:</strong> +44 2037695831
					</li>
				</ul>
			</div>
		</div>
	</section>



@endsection