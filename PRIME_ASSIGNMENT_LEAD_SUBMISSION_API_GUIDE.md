# Prime Assignment Website — Lead Form API Integration Guide

This guide explains how the **Prime Assignment frontend** (`primeassignmenthelp.co.uk`) should submit leads/orders so that they automatically appear under **Prime Assignment > Leads** (`/prime-leads`) in the CRM backend.

---

## 1. API Endpoint

- **URL:** `POST https://ain.warrgyizmorsch.com/api/prime/save-lead`
- **Alternative URL:** `POST https://ain.warrgyizmorsch.com/api/save-order`
- **Method:** `POST`
- **Content-Type:** `application/json` (or `multipart/form-data` / `application/x-www-form-urlencoded`)
- **Authentication:** Public (No API key / auth header strictly required for this endpoint)

---

## 2. Request Body Parameters

The backend supports both traditional field names and modern frontend/React naming conventions:

| Parameter | Type | Required | Description / Example |
| :--- | :--- | :--- | :--- |
| `name` (or `full_name`) | string | Yes | Client full name (e.g. `"John Doe"`) |
| `email` | string | Yes | Client email address (e.g. `"john@example.com"`) |
| `phone` (or `mobile_number` / `mobile`) | string | Yes | Phone / WhatsApp number with or without country code |
| `countryCode` (or `country`) | string | Optional | Country code or name (e.g. `"+44"` or `"United Kingdom"`) |
| `service` (or `services`) | string | Optional | Selected service (e.g. `"Dissertation"`, `"Assignment"`, `"Essay"`) |
| `subject` | string | Optional | Subject / Topic area (e.g. `"Economics"`, `"Computer Science"`) |
| `work_type` (or `workType`) | string | Optional | Type of work (e.g. `"Standard"`, `"First Class Work"`) |
| `deadline` (or `select_urgency` / `urgency`) | string | Optional | Urgency / deadline (e.g. `"3 Days"`, `"21+ Days"`) |
| `wordCount` (or `word_count` / `pages`) | integer/string | Optional | Word count (e.g. `2500`) or number of pages |
| `topic` (or `enter_topic`) | string | Optional | Specific assignment title or topic |
| `description` (or `requirements` / `message`) | string | Optional | Client's project description or instructions |
| `source_url` (or `source_page`) | string | **Important** | Full page URL where the form was submitted (e.g. `window.location.href`). Must contain `primeassignmenthelp.co.uk`. If omitted, the backend defaults to `https://primeassignmenthelp.co.uk/`. |

---

## 3. Example Request (JSON)

```json
{
  "name": "Alex Smith",
  "email": "alex.smith@example.com",
  "phone": "+447123456789",
  "countryCode": "+44",
  "service": "Assignment Help",
  "subject": "Business Management",
  "deadline": "5 Days",
  "wordCount": 1500,
  "topic": "Strategic Marketing Case Study",
  "description": "Please follow Harvard referencing style.",
  "source_url": "https://primeassignmenthelp.co.uk/services/assignment-help"
}
```

---

## 4. Example JavaScript / Next.js Implementation

```typescript
async function submitPrimeLead(formData: {
  name: string;
  email: string;
  phone: string;
  countryCode?: string;
  service?: string;
  subject?: string;
  deadline?: string;
  wordCount?: number;
  description?: string;
}) {
  const payload = {
    ...formData,
    source_url: typeof window !== 'undefined' ? window.location.href : 'https://primeassignmenthelp.co.uk/'
  };

  const response = await fetch('https://ain.warrgyizmorsch.com/api/prime/save-lead', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify(payload)
  });

  const data = await response.json();
  if (data.success || data.status) {
    console.log('Lead submitted successfully, ID:', data.lead_id);
    return data;
  } else {
    throw new Error(data.message || 'Submission failed');
  }
}
```

---

## 5. Success Response (HTTP 200)

```json
{
  "success": true,
  "status": true,
  "message": "Order saved successfully",
  "id": 74,
  "lead_id": 74
}
```

---

## 6. Where Does This Appear in the CRM?

Leads submitted to this endpoint will instantly appear under:
- **Left Sidebar:** `Prime Assignment` > `Leads`
- **URL:** `https://ain.warrgyizmorsch.com/prime-leads`
