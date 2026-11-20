# Prime Assignment — Forgot Password API Integration Guide

This guide explains how the **Prime Assignment frontend** (`primeassignmenthelp.co.uk`) interacts with the 3-Step OTP-based Forgot Password APIs.

All emails sent via this endpoint feature **100% Prime Assignment branding** (From Name: *Prime Assignment*, Subject: *Your Prime Assignment password reset OTP*, and clean Prime templates with zero mention of AIN).

---

## 1. Step 1: Send OTP to User Email

Triggered when the user enters their email and clicks "Send OTP" / "Forgot Password".

- **Endpoint:** `POST https://ain.warrgyizmorsch.com/api/prime/forgot-password`
- **Alternative:** `POST https://ain.warrgyizmorsch.com/api/app/forgot-password` *(with `"brand": "prime"`)*
- **Method:** `POST`
- **Headers:**
  ```http
  Content-Type: application/json
  Accept: application/json
  ```
- **Request Body (JSON):**
  ```json
  {
    "email": "student@example.com"
  }
  ```
- **Success Response (HTTP 200):**
  ```json
  {
    "success": true,
    "message": "Password reset OTP has been sent to your email.",
    "expires_in": 600
  }
  ```

---

## 2. Step 2: Verify OTP

Triggered when the user submits the 6-digit OTP received in their email. On success, it returns a 64-character `reset_token`.

- **Endpoint:** `POST https://ain.warrgyizmorsch.com/api/prime/verify-forgot-password-otp`
- **Alternative:** `POST https://ain.warrgyizmorsch.com/api/app/verify-forgot-password-otp`
- **Method:** `POST`
- **Headers:**
  ```http
  Content-Type: application/json
  Accept: application/json
  ```
- **Request Body (JSON):**
  ```json
  {
    "email": "student@example.com",
    "otp": "482910"
  }
  ```
- **Success Response (HTTP 200):**
  ```json
  {
    "success": true,
    "message": "OTP verified successfully.",
    "reset_token": "4f9d2c18b76e53a019e8c4f2d3a1b5c7e9f0123456789abcdef0123456789abc",
    "expires_in": 900
  }
  ```
- **Error Response (Wrong OTP, HTTP 422):**
  ```json
  {
    "success": false,
    "message": "Invalid OTP.",
    "attempts_remaining": 3
  }
  ```

---

## 3. Step 3: Set New Password

Triggered when the user enters and confirms their new password using the `reset_token`.

- **Endpoint:** `POST https://ain.warrgyizmorsch.com/api/prime/reset-password`
- **Alternative:** `POST https://ain.warrgyizmorsch.com/api/app/reset-password`
- **Method:** `POST`
- **Headers:**
  ```http
  Content-Type: application/json
  Accept: application/json
  ```
- **Request Body (JSON):**
  ```json
  {
    "email": "student@example.com",
    "reset_token": "4f9d2c18b76e53a019e8c4f2d3a1b5c7e9f0123456789abcdef0123456789abc",
    "password": "NewStrongPassword@123",
    "password_confirmation": "NewStrongPassword@123"
  }
  ```
- **Success Response (HTTP 200):**
  ```json
  {
    "success": true,
    "message": "Password reset successfully. Please login with your new password."
  }
  ```

---

## 4. Key Rules
- **OTP Expiration:** 10 minutes (`expires_in: 600` seconds).
- **Reset Token Expiration:** 15 minutes (`expires_in: 900` seconds).
- **Max OTP Attempts:** 5 attempts before a new OTP is required.
- **Passwords:** Must match `password_confirmation`.
