<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>
    <p><strong>Order Code:</strong> {{ $details['orderCode'] }}</p>
    <p><strong>Service:</strong> {{ $details['service'] }}</p>
    <p><strong>Work Type:</strong> {{ $details['workType'] }}</p>
    <p><strong>Subject:</strong> {{ $details['subject'] }}</p>
    <p><strong>Urgency:</strong> {{ $details['urgency'] }}</p>
    <p><strong>Word Count:</strong> {{ $details['wordCount'] }}</p>
    <p><strong>Topic:</strong> {{ $details['topic'] }}</p>
    <p><strong>Estimated Price:</strong> {{ $details['estimatedPrice'] }}</p>
    <p><strong>Discount:</strong> {{ $details['discount'] }}</p>
    <p><strong>Final Price:</strong> {{ $details['finalPrice'] }}</p>
    <p><strong>Requirements:</strong> {{ $details['requirements-msg'] }}</p>
    <p><strong>Email:</strong> {{ $details['email'] }}</p>
    <p><strong>Mobile:</strong> {{ $details['mobile'] }}</p>
    <p><strong>Country Code:</strong> {{ $details['countrycode'] }}</p>
    <p><strong>Delivery Date:</strong> {{ $details['deliveryDate'] }}</p>

    @if (!empty($details['files']))
        <h2>Uploaded Files:</h2>
        <ul>
            @foreach ($details['files'] as $file)
                <li>{{ $file['name'] }} - <a href="{{ asset($file['path']) }}">Download</a></li>
            @endforeach
        </ul>
    @endif
</body>
</html>
