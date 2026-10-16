# Gmail App Password Architecture, Revocation Causes & Security Lifecycle

> **Author / Research Document**  
> **Topic:** Why Google Gmail requires App Passwords, why they stop working (Error 535), and how the authentication lifecycle works.  
> **Date:** October 2026  
> **Scope:** AIN CRM Email Module (`assignmentinneedhelp@gmail.com` and other Gmail/Google Workspace channels)

---

## 1. Executive Summary

| Question | Answer |
| :--- | :--- |
| **Kya ye Gmail ki taraf se hota hai ya CRM ki taraf se?** | **100% Google/Gmail server-side security policy ki taraf se hota hai.** CRM sirf stored 16-character string ko Google ke official SMTP/IMAP server (`smtp.gmail.com:465` / `imap.gmail.com:993`) par bhejta hai. |
| **Error 535 ka technical matlab kya hai?** | `535 5.7.8 Username and Password not accepted` — Google mail server ne credentials ko reject kar diya. |
| **App Password kyu band/expire hota hai?** | Jab bhi Google account ka **Main Password change** hota hai, **2-Step Verification** modify hoti hai, ya Google ka automated risk system security trigger karta hai, Google turant saare purane App Passwords ko **permanently revoke** kar deta hai. |

---

## 2. Why Does Google Require App Passwords?

### A. The 2022 "Less Secure Apps" (LSA) Deprecation
* Prior to **May 30, 2022**, third-party software (like custom CRMs, Outlook, Thunderbird) could log into Gmail using the regular email address and regular account password.
* Google completely deprecated this under their global security compliance, because sharing main account passwords with third-party software exposed users to credential theft, phishing, and compromised 2FA.

### B. What is an App Password?
* A **Google App Password** is a 16-character cryptographic passkey (e.g. `abcd efgh ijkl mnop`).
* It allows legacy programmatic protocols (**SMTP** on port 465/587 and **IMAP** on port 993) to authenticate **without bypassing 2-Step Verification**.
* **Prerequisite:** Google mandates that **2-Step Verification (2FA)** MUST be active on the Google account to generate an App Password.

---

## 3. Why Did the App Password Stop Working? (Root Causes of Error 535)

Google documentation and RFC SMTP/IMAP protocol standards define 5 specific triggers where an App Password is automatically revoked or rejected:

### 1. Main Google Account Password Changed (Most Common)
* **Google Security Rule:** The exact second a user changes or resets the main password of their Google Account, **Google automatically revokes ALL existing App Passwords** created under that account.
* **Why Google does this:** If an account is ever compromised and the owner changes their password to regain control, Google ensures that unauthorized apps/devices cannot keep accessing the inbox using old tokens.

### 2. 2-Step Verification (2FA) Was Modified or Toggled
* An App Password is tied directly to the state of 2-Step Verification.
* If someone:
  - Toggled 2FA off and back on,
  - Changed the primary 2FA phone number or recovery email,
  - Replaced the Google Authenticator app,
* Google treats this as a security lifecycle event and **deletes all active App Passwords**.

### 3. Google Risk Engine / IP Security Flag
* When an application connects via a server IP (e.g., from a hosting server data center like `server.warrgyizmorsch.com` or foreign cloud IP):
* If Google's automated threat detection flags the traffic pattern (e.g., rapid connection bursts, sudden location shift, or temporary security hold), it may require a fresh token to verify ownership.

### 4. Manual Revocation
* A team member or administrator logged into `myaccount.google.com/security` and removed/deleted the app password from the list.

### 5. Google Workspace Admin Policy Reset
* For managed domains (`@company.com` on Google Workspace), if the workspace super-admin forces a session sign-out or modifies security policies (such as enforcing Security Keys or OAuth policies), existing App Passwords are invalidated.

---

## 4. How the CRM Connects to Gmail (Technical Flow)

In `app/Services/EmailService.php`:

```
[ AIN CRM ]  ─── SSL Socket (Port 465) ───>  smtp.gmail.com
             <── 220 smtp.gmail.com ESMTP
             ─── EHLO localhost
             <── 250 OK
             ─── AUTH LOGIN
             <── 334 VXNlcm5hbWU6 (base64 "Username:")
             ─── base64(email_address)
             <── 334 UGFzc3dvcmQ6 (base64 "Password:")
             ─── base64(app_password)
             <── 535-5.7.8 Username and Password not accepted.  <── [REJECTED BY GOOGLE]
```

* The CRM code is functional and standard.
* The rejection happens at the Google authentication gate before any mail command can be issued.
* As soon as a valid 16-character string is provided, Google immediately accepts with `235 2.7.0 Accepted`.

---

## 5. How to Regenerate and Restore Connection

1. Go to Google Account Security:  
   👉 **https://myaccount.google.com/apppasswords**  
   *(Login as `assignmentinneedhelp@gmail.com`)*
2. In the "App name" box, type **AIN CRM** (or **Writer Mail**).
3. Click **Create**.
4. Google will display a yellow box with a 16-character code (e.g., `xxxx xxxx xxxx xxxx`).
5. Copy the 16 characters (spaces do not matter).
6. Open **CRM Portal** -> **Setting** -> **Email Settings**.
7. Click **Edit** on **Writer**.
8. Paste the 16 characters in:
   - **SMTP Password**
   - **IMAP Password**
9. Click **Save / Update Configuration**.
10. Click **Test Connection** — Google will return `235 Authentication Succeeded`.

---

## 6. Long-Term Alternative: Google OAuth 2.0 Web Flow

If the team wants a solution where passwords **never** need to be manually updated even when Google passwords change:

| Method | Authentication | Password Change Behavior | Maintenance |
| :--- | :--- | :--- | :--- |
| **App Password (Current)** | Static 16-letter token via SMTP/IMAP | Must be regenerated if main account password or 2FA changes | Low setup, periodic re-generation |
| **Google OAuth 2.0 (Enterprise)** | `Client ID` + `Client Secret` + `Refresh Token` via Google Cloud Console | Automatically refreshes access tokens silently in background; never breaks on password change | One-time Google Cloud Console app setup |

---

*Document saved in AIN Backend Repository: `GMAIL_APP_PASSWORD_RESEARCH_AND_GUIDE.md`*
