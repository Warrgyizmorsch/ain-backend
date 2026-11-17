# AIN Mobile App - Flutter Push Notification Integration Guide

This guide is for the **Flutter Mobile App Developer** to integrate Firebase Cloud Messaging (FCM) Push Notifications with the **AIN Backend**.

---

## 1. Firebase Project Details
* **Firebase Project ID:** `assignment-f88ce`
* **Backend Credentials:** Google Service Account configured with FCM HTTP v1 API.

### Required Firebase Configuration Files:
1. **Android:** Download `google-services.json` from the Firebase Console (`assignment-f88ce`) and place it in:
   ```
   android/app/google-services.json
   ```
2. **iOS:** Download `GoogleService-Info.plist` from the Firebase Console (`assignment-f88ce`) and place it in:
   ```
   ios/Runner/GoogleService-Info.plist
   ```
   *(Ensure APNs Authentication Key `.p8` is uploaded in Firebase Console under Project Settings -> Cloud Messaging -> Apple app configuration).*

---

## 2. Flutter Dependencies (`pubspec.yaml`)

Add the following packages to your Flutter app:

```yaml
dependencies:
  flutter:
    sdk: flutter
  firebase_core: ^3.6.0
  firebase_messaging: ^15.1.3
  flutter_local_notifications: ^17.2.3
  http: ^1.2.2 # or dio
```

Run:
```bash
flutter pub get
```

---

## 3. Android Setup

### A. `android/build.gradle` (Project level)
```groovy
buildscript {
    dependencies {
        // Add Google services classpath
        classpath 'com.google.gms:google-services:4.4.2'
    }
}
```

### B. `android/app/build.gradle` (App level)
```groovy
apply plugin: 'com.android.application'
apply plugin: 'com.google.gms.google-services' // Add this at bottom or in plugins block
```

### C. `android/app/src/main/AndroidManifest.xml`
Inside `<application>`:
```xml
<!-- Default Notification Channel for heads-up display -->
<meta-data
    android:name="com.google.firebase.messaging.default_notification_channel_id"
    android:value="ain_notifications" />

<!-- Optional notification icon -->
<meta-data
    android:name="com.google.firebase.messaging.default_notification_icon"
    android:resource="@mipmap/ic_launcher" />
```

---

## 4. Backend API Endpoints

Base URL:
* **UAT/Dev:** `https://ain.warrgyizmorsch.com/api/app`
* **Production:** `https://portal.assignmentinneed.com/api/app` (or your live domain)

### Endpoint 1: Register / Save Device Token
Call this endpoint:
1. Immediately after user logs in or registers successfully.
2. If token refreshes via `FirebaseMessaging.instance.onTokenRefresh`.

* **Method:** `POST`
* **Path:** `/api/app/save-device-token`
* **Headers:**
  ```http
  Authorization: Bearer <SANCTUM_USER_TOKEN>
  Accept: application/json
  Content-Type: application/json
  ```
* **Request Body:**
  ```json
  {
    "fcm_token": "eXample_FCM_Token_From_Firebase...",
    "device_type": "android", 
    "device_name": "Samsung Galaxy S23"
  }
  ```
  *(Note: `device_type` should be `"android"`, `"ios"`, or `"web"`).*

* **Response (HTTP 200):**
  ```json
  {
    "success": true,
    "message": "Device token registered successfully.",
    "data": {
      "id": 12,
      "user_id": 14212,
      "device_type": "android",
      "is_active": true,
      "updated_at": "2026-11-17T11:15:00.000000Z"
    }
  }
  ```

---

### Endpoint 2: Remove Device Token (On Logout)
Call this endpoint before clearing local app state when the user taps **Logout**:

* **Method:** `POST`
* **Path:** `/api/app/remove-device-token`
* **Headers:**
  ```http
  Authorization: Bearer <SANCTUM_USER_TOKEN>
  Accept: application/json
  Content-Type: application/json
  ```
* **Request Body:**
  ```json
  {
    "fcm_token": "eXample_FCM_Token_From_Firebase..."
  }
  ```

* **Response (HTTP 200):**
  ```json
  {
    "success": true,
    "message": "Device token removed successfully."
  }
  ```

---

## 5. Complete Flutter Implementation Code

### File: `lib/services/fcm_service.dart`

```dart
import 'dart:convert';
import 'dart:io';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:http/http.dart' as http;

// Top-level background handler (must be outside of any class)
@pragma('vm:entry-point')
Future<void> firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp();
  print("FCM Background Message Received: ${message.messageId}");
}

class FCMService {
  static final FirebaseMessaging _messaging = FirebaseMessaging.instance;
  static final FlutterLocalNotificationsPlugin _localNotifications = FlutterLocalNotificationsPlugin();

  static const String channelId = 'ain_notifications';
  static const String channelName = 'AIN Notifications';
  static const String channelDescription = 'Assignment In Need updates, orders, and notifications';

  /// Initialize Firebase Messaging & Local Notification Channel
  static Future<void> initialize() async {
    // 1. Request user permission (Critical for iOS and Android 13+)
    NotificationSettings settings = await _messaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
      provisional: false,
    );

    if (settings.authorizationStatus == AuthorizationStatus.authorized) {
      print('User granted notification permission');
    }

    // 2. Initialize Android High-Importance Notification Channel
    const AndroidNotificationChannel channel = AndroidNotificationChannel(
      channelId,
      channelName,
      description: channelDescription,
      importance: Importance.max,
      playSound: true,
    );

    await _localNotifications
        .resolvePlatformSpecificImplementation<AndroidFlutterLocalNotificationsPlugin>()
        ?.createNotificationChannel(channel);

    // 3. Initialize local notification plugin
    const InitializationSettings initSettings = InitializationSettings(
      android: AndroidInitializationSettings('@mipmap/ic_launcher'),
      iOS: DarwinInitializationSettings(),
    );

    await _localNotifications.initialize(
      initSettings,
      onDidReceiveNotificationResponse: (NotificationResponse response) {
        // Handle notification click when app is in foreground
        if (response.payload != null) {
          _handlePayloadNavigation(response.payload!);
        }
      },
    );

    // 4. Foreground Message Listener
    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      RemoteNotification? notification = message.notification;
      AndroidNotification? android = message.notification?.android;

      if (notification != null && android != null) {
        _localNotifications.show(
          notification.hashCode,
          notification.title,
          notification.body,
          NotificationDetails(
            android: AndroidNotificationDetails(
              channel.id,
              channel.name,
              channelDescription: channel.description,
              importance: Importance.max,
              priority: Priority.high,
              icon: android.smallIcon ?? '@mipmap/ic_launcher',
            ),
            iOS: const DarwinNotificationDetails(
              presentAlert: true,
              presentBadge: true,
              presentSound: true,
            ),
          ),
          payload: jsonEncode(message.data),
        );
      }
    });

    // 5. Handle Notification tap when app is in Background
    FirebaseMessaging.onMessageOpenedApp.listen((RemoteMessage message) {
      _handlePayloadNavigation(jsonEncode(message.data));
    });

    // 6. Handle Notification tap when app was completely Terminated
    RemoteMessage? initialMessage = await _messaging.getInitialMessage();
    if (initialMessage != null) {
      _handlePayloadNavigation(jsonEncode(initialMessage.data));
    }
  }

  /// Send Device Token to AIN Backend
  static Future<void> registerDeviceToken({
    required String baseUrl,
    required String authToken,
  }) async {
    try {
      String? token = await _messaging.getToken();
      if (token == null) return;

      print("FCM Token: $token");

      final response = await http.post(
        Uri.parse('$baseUrl/save-device-token'),
        headers: {
          'Authorization': 'Bearer $authToken',
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: jsonEncode({
          'fcm_token': token,
          'device_type': Platform.isAndroid ? 'android' : (Platform.isIOS ? 'ios' : 'web'),
          'device_name': Platform.operatingSystem,
        }),
      );

      print("Token Sync Status: ${response.statusCode}");

      // Listen for token refreshes and sync with server
      _messaging.onTokenRefresh.listen((newToken) async {
        await http.post(
          Uri.parse('$baseUrl/save-device-token'),
          headers: {
            'Authorization': 'Bearer $authToken',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
          },
          body: jsonEncode({
            'fcm_token': newToken,
            'device_type': Platform.isAndroid ? 'android' : 'ios',
          }),
        );
      });
    } catch (e) {
      print("Error registering FCM token: $e");
    }
  }

  /// Remove Device Token on User Logout
  static Future<void> unregisterDeviceToken({
    required String baseUrl,
    required String authToken,
  }) async {
    try {
      String? token = await _messaging.getToken();
      if (token == null) return;

      await http.post(
        Uri.parse('$baseUrl/remove-device-token'),
        headers: {
          'Authorization': 'Bearer $authToken',
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: jsonEncode({
          'fcm_token': token,
        }),
      );
    } catch (e) {
      print("Error removing FCM token: $e");
    }
  }

  /// Route user based on notification payload data
  static void _handlePayloadNavigation(String rawPayload) {
    try {
      final Map<String, dynamic> data = jsonDecode(rawPayload);
      print("Notification Payload Data: $data");

      // Example navigation:
      if (data['type'] == 'order_placed' || data.containsKey('order_id')) {
        String orderId = data['order_id'];
        print("Navigate to Order Screen: $orderId");
        // Navigator.pushNamed(context, '/order-details', arguments: orderId);
      }
    } catch (e) {
      print("Error parsing payload: $e");
    }
  }
}
```

---

### In `lib/main.dart`

```dart
void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  // Initialize Firebase
  await Firebase.initializeApp();
  
  // Register background messaging handler
  FirebaseMessaging.onBackgroundMessage(firebaseMessagingBackgroundHandler);

  // Initialize FCM Service
  await FCMService.initialize();

  runApp(const MyApp());
}
```

### On User Login:
```dart
// After login API returns sanctum token:
await FCMService.registerDeviceToken(
  baseUrl: 'https://ain.warrgyizmorsch.com/api/app',
  authToken: userSanctumToken,
);
```

### On User Logout:
```dart
// Before clearing token locally:
await FCMService.unregisterDeviceToken(
  baseUrl: 'https://ain.warrgyizmorsch.com/api/app',
  authToken: userSanctumToken,
);
```

---

## 6. How Backend Sends Payloads

When the backend triggers a notification (e.g. on order placed), it sends:
```json
{
  "notification": {
    "title": "Order Placed Successfully! 🎓",
    "body": "Your order #UKS12345 has been received..."
  },
  "data": {
    "type": "order_placed",
    "order_id": "UKS12345"
  }
}
```
The Flutter app receives `message.notification` for display, and `message.data` for deep linking.

---

## 7. Testing Your Setup

Once the Flutter app registers its token, you or the backend team can send a direct test ping using the Artisan command:

```bash
# Test by User ID
php artisan fcm:test 14212 --title="Hello User" --body="This is a test notification"

# Or test directly with the device's FCM token
php artisan fcm:test "eXample_FCM_Token_From_Device" --title="Test Ping" --body="Test message"
```

---

## 8. Google Sign-In Setup & Troubleshooting for Flutter

If Google Login is failing in the Flutter app, check these **4 mandatory requirements**:

### Step 1: Add SHA-1 Fingerprint in Firebase Console (Mandatory for Android)
Google Sign-In on Android will fail with `ApiException: 10` or `12500` if the signing certificate is not added to Firebase.

1. Generate your debug SHA-1 & SHA-256 fingerprint in terminal:
   ```bash
   cd android
   ./gradlew signingReport
   ```
   *(Look for `Variant: debugAndroidTest` or `Task :app:signingReport` -> `SHA1:` and `SHA-256:`).*
2. Open [Firebase Console](https://console.firebase.google.com/) -> Select project **`assignment-f88ce`**.
3. Go to **Project Settings (Gear icon)** -> Scroll down to **Your apps** -> Select your Android app (`com.assignmentinneed`).
4. Click **Add fingerprint** -> Paste your **SHA-1** and click Save. Also paste **SHA-256**.
5. *(For production/Play Store release, also add the Google Play App Signing SHA-1 from Google Play Console).*

---

### Step 2: Enable Google Provider in Firebase Authentication
1. In Firebase Console -> Go to **Build** -> **Authentication** -> **Sign-in method**.
2. Click on **Google**.
3. Toggle **Enable**.
4. Set project support email and click **Save**.

---

### Step 3: Download Updated `google-services.json`
* After adding the SHA-1 fingerprint and enabling Google Sign-In, download the new `google-services.json` from Firebase Console.
* Replace the file at:
  ```
  android/app/google-services.json
  ```
* Run in Flutter project root:
  ```bash
  flutter clean
  flutter pub get
  ```

---

### Step 4: Configure `GoogleSignIn` with `serverClientId` in Flutter

In your Flutter app (`google_sign_in` package):

```dart
import 'package:google_sign_in/google_sign_in.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class AuthService {
  // Web Client ID from Firebase Console -> Authentication -> Sign-in method -> Google -> Web SDK configuration
  static const String webClientId = '876740242049-r98097ovv704bel16q34ancr5eacr673.apps.googleusercontent.com';

  static final GoogleSignIn _googleSignIn = GoogleSignIn(
    serverClientId: webClientId, // CRITICAL: Required so Google returns an idToken for backend verification
    scopes: ['email', 'profile'],
  );

  static Future<Map<String, dynamic>?> signInWithGoogle({
    required String backendUrl, // e.g. https://ain.warrgyizmorsch.com/api/app
  }) async {
    try {
      // 1. Trigger Google popup
      final GoogleSignInAccount? googleUser = await _googleSignIn.signIn();
      if (googleUser == null) {
        print("User cancelled Google Sign-In");
        return null;
      }

      // 2. Obtain auth details (idToken)
      final GoogleSignInAuthentication googleAuth = await googleUser.authentication;
      final String? idToken = googleAuth.idToken;

      if (idToken == null) {
        print("Error: idToken is null. Ensure serverClientId is set correctly.");
        return null;
      }

      // 3. Send idToken to AIN Backend
      final response = await http.post(
        Uri.parse('$backendUrl/google-login'),
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: jsonEncode({
          'id_token': idToken,
          'email': googleUser.email,
          'name': googleUser.displayName,
        }),
      );

      final Map<String, dynamic> resData = jsonDecode(response.body);

      if (response.statusCode == 200 && resData['success'] == true) {
        print("Google Login Success! Token: ${resData['token']}");
        return resData;
      } else {
        print("Google Login Failed on Backend: ${resData['message']} - ${resData['reason'] ?? ''}");
        return null;
      }
    } catch (e) {
      print("Google Sign-In Exception: $e");
      return null;
    }
  }
}
```

