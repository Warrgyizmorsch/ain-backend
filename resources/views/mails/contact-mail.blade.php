<h2>New Contact Form Submission</h2>

<p><strong>Name:</strong> {{ $details['name'] }}</p>
<p><strong>Email:</strong> {{ $details['email'] }}</p>
<p><strong>Mobile:</strong> {{ $details['mobile'] ?? '' }}</p>
<p><strong>Country Code:</strong> {{ $details['country_code'] ?? ($details['countrycode'] ?? '') }}</p>
<p><strong>Subject:</strong> {{ $details['subject'] ?? '' }}</p>
<p><strong>Inquiry Type:</strong> {{ $details['inquiry_type'] ?? '' }}</p>

<p><strong>Message:</strong></p>
<p>{{ $details['message'] }}</p>
