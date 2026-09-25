<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Next2Call Browser Phone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Expires" content="0" />

    <link rel="icon" type="image/x-icon" href="https://ringfy.next2call.com/softphone/Phone/favicon.ico" />

    <!-- External Assets from CDN & Next2Call -->
    <link rel="stylesheet" type="text/css" href="https://dtd6jl0d42sve.cloudfront.net/lib/Normalize/normalize-v8.0.1.css" />
    <link rel="stylesheet preload prefetch" type="text/css" as="style" href="https://dtd6jl0d42sve.cloudfront.net/lib/fonts/font_roboto/roboto.css" />
    <link rel="stylesheet preload prefetch" type="text/css" as="style" href="https://dtd6jl0d42sve.cloudfront.net/lib/fonts/font_awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="https://dtd6jl0d42sve.cloudfront.net/lib/jquery/jquery-ui-1.13.2.min.css" />
    <link rel="stylesheet" type="text/css" href="https://dtd6jl0d42sve.cloudfront.net/lib/Croppie/Croppie-2.6.4/croppie.css" />
    <link rel="stylesheet" type="text/css" href="https://ringfy.next2call.com/softphone/Phone/phone.css" />

    <script type="text/javascript">
      function getPhoneOptions() {
        let urlParams = new URLSearchParams(window.location.search);
        let profileName = urlParams.get("profileName") || "{{ $userId ?? '' }}";
        let SipDomain = urlParams.get("SipDomain") || "{{ $sipDomain ?? 'ringfy.next2call.com' }}";
        let SipUsername = urlParams.get("SipUsername") || "{{ $userId ?? '' }}";
        let SipPassword = urlParams.get("SipPassword") || "{{ $password ?? '' }}";

        var phoneOptions = {
          loadAlternateLang: false,
          VoiceMailSubscribe: false,
          EnableTextMessaging: false,
          DisableFreeDial: true,
          DisableBuddies: true,
          ChatEngine: "SIMPLE",
          profileName: profileName,
          wssServer: SipDomain,
          WebSocketPort: "8089",
          SipDomain: SipDomain,
          ServerPath: "/ws",
          SipUsername: SipUsername,
          SipPassword: SipPassword,
          hostingPrefix: "https://ringfy.next2call.com/softphone/Phone/",
        };
        return phoneOptions;
      }

      // Listen for commands from parent window
      window.addEventListener("message", function (event) {
        var data = event.data;
        if (data === "HANGUP" || data?.type === "HANGUP" || data?.action === "hangup") {
          console.log("[Next2Call Client] HANGUP received from parent CRM");
          try {
            if (typeof cancelSession === "function") {
              cancelSession(1);
            }
            if (typeof endSession === "function") {
              endSession(1);
            }
            if (typeof teardownSession === "function" && typeof FindLineByNumber === "function") {
              var lineObj = FindLineByNumber(1);
              if (lineObj) teardownSession(lineObj);
            }
          } catch (e) {
            console.warn("[Next2Call Client] Error while hanging up:", e);
          }
        }
      });

      var web_hook_on_register = function (ua) {
        try {
          window.parent.postMessage({ type: "SIP_REGISTERED" }, "*");
        } catch (e) {}

        let urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has("d")) {
          window.setTimeout(function () {
            console.log("Performing Auto Dial:", urlParams.get("d"));
            DialByLine("audio", null, urlParams.get("d"));
          }, 1000);
        }
      };

      var web_hook_on_registrationFailed = function (e) {
        try {
          window.parent.postMessage({ type: "SIP_REGISTRATION_FAILED", error: String(e) }, "*");
        } catch (err) {}
      };

      var web_hook_on_unregistered = function () {
        try {
          window.parent.postMessage({ type: "SIP_UNREGISTERED" }, "*");
        } catch (err) {}
      };

      var web_hook_on_terminate = function (session) {
        try {
          window.parent.postMessage({ type: "CALL_TERMINATED" }, "*");
          window.parent.postMessage({ type: "CLOSE_PHONE_POPUP" }, "*");
        } catch (e) {}
      };
    </script>
  </head>

  <body>
    <!-- Loading Animation -->
    <div class="loading">
      <span class="fa fa-circle-o-notch fa-spin"></span>
    </div>

    <!-- The Phone -->
    <div id="Phone"></div>
  </body>

  <!-- Loadable Scripts -->
  <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/jquery/jquery-3.6.1.min.js"></script>
  <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/jquery/jquery-ui-1.13.2.min.js"></script>
  <script type="text/javascript" src="https://ringfy.next2call.com/softphone/Phone/phone.js"></script>

  <!-- Deferred Scripts -->
  <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/jquery/jquery.md5-min.js" defer="true"></script>
  <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/Chart/Chart.bundle-2.7.2.min.js" defer="true"></script>
  <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/SipJS/sip-0.20.0.min.js" defer="true"></script>
  <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/FabricJS/fabric-2.4.6.min.js" defer="true"></script>
  <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/Moment/moment-with-locales-2.24.0.min.js" defer="true"></script>
  <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/Croppie/Croppie-2.6.4/croppie.min.js" defer="true"></script>
  <script type="text/javascript" src="https://dtd6jl0d42sve.cloudfront.net/lib/XMPP/strophe-1.4.1.umd.min.js" defer="true"></script>
</html>
