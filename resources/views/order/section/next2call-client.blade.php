@php
    $queryParams = request()->query();
    $d = request()->get('d');
    $domain = !empty($sipDomain) ? $sipDomain : 'ringfy.next2call.com';
    $path = !empty($d) ? '/softphone/Phone/click-to-dial.html' : '/softphone/Phone/index.html';
    
    // Ensure credentials are present in query params
    if (!isset($queryParams['profileName']) && !empty($userId)) $queryParams['profileName'] = $userId;
    if (!isset($queryParams['SipDomain']) && !empty($domain)) $queryParams['SipDomain'] = $domain;
    if (!isset($queryParams['SipUsername']) && !empty($userId)) $queryParams['SipUsername'] = $userId;
    if (!isset($queryParams['SipPassword']) && !empty($password)) $queryParams['SipPassword'] = $password;

    $targetUrl = "https://{$domain}{$path}?" . http_build_query($queryParams);
@endphp
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Next2Call Softphone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <script>
      // Forward messages from Next2Call to parent window
      window.addEventListener("message", function(event) {
        if (event.origin === "https://ringfy.next2call.com") {
          window.parent?.postMessage(event.data, "*");
        }
      });
    </script>
    <style>
      html, body {
        margin: 0; padding: 0; width: 100%; height: 100%; overflow: hidden; background: #ffffff;
      }
      iframe {
        width: 100%; height: 100%; border: 0; display: block;
      }
    </style>
  </head>
  <body>
    <iframe
      id="next2callDirectFrame"
      src="{{ $targetUrl }}"
      allow="microphone; camera; speaker-selection; display-capture; autoplay; fullscreen"
      allowfullscreen>
    </iframe>
  </body>
</html>
