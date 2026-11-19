# Frontend Integration Guide: Subject Categories & Dynamic Dropdown

> **For Frontend / Next.js Developers**  
> **Backend System:** Assignment In Need (AIN) CRM Backend  
> **Backend Base URL:** `https://ain.warrgyizmorsch.com`

---

## 1. Overview of Backend Updates

In the CRM portal (`/subject-pages/{id}/edit` & `/subject-pages/create`), an admin can now:
1. Select an existing **Category** (e.g., *Management, Engineering, Computer Science, Programming, Economics, Mathematics, Finance, Business, Humanities, Science, Law, Marketing*).
2. **Create a new category on the fly** simply by typing a new category name.
3. Every subject page now stores its category, and the public APIs return both:
   - `category`: The human-readable name (e.g. `"Humanities"`, `"Management"`).
   - `category_slug`: The URL-friendly identifier (e.g. `"humanities"`, `"management"`).

---

## 2. API Endpoints Reference

### Endpoint 1: Navbar Dropdown & All Subjects Listing
- **Method:** `GET`
- **Internal Next.js Route:** `/api/subject-pages`
- **Direct Backend URL:** `https://ain.warrgyizmorsch.com/api/subject-pages`

#### Response Structure:
```json
{
  "status": "success",
  "success": true,
  "total": 159,
  "data": [
    {
      "id": 159,
      "title": "Anthropology Assignment Help UK",
      "slug": "subject/anthropology-assignment-help",
      "category": "Humanities",
      "category_slug": "humanities",
      "hasSubmenu": false,
      "children": []
    },
    {
      "id": 14,
      "title": "Project Management Assignment Help",
      "slug": "subject/project-management-assignment-help",
      "category": "Management",
      "category_slug": "management",
      "hasSubmenu": false,
      "children": []
    }
  ],
  "categories": [
    {
      "name": "Humanities",
      "slug": "humanities",
      "title": "Humanities",
      "count": 8,
      "hasSubmenu": true,
      "subjects": [
        {
          "id": 159,
          "title": "Anthropology Assignment Help UK",
          "slug": "subject/anthropology-assignment-help",
          "category": "Humanities",
          "category_slug": "humanities"
        }
      ],
      "children": [ /* exact alias of subjects for recursive menus */ ]
    },
    {
      "name": "Management",
      "slug": "management",
      "title": "Management",
      "count": 12,
      "hasSubmenu": true,
      "subjects": [ ... ],
      "children": [ ... ]
    }
  ]
}
```

> **Optional Tree Mode:**  
> If you call `GET /api/subject-pages?grouped=1` (or `?view=navbar`), the `data` array itself will directly return the categorized tree array!

---

### Endpoint 2: Subject Detail Page
- **Method:** `GET`
- **Direct Backend URL:** `https://ain.warrgyizmorsch.com/api/subject-pages/subject/{slug}`  
  *(Example: `https://ain.warrgyizmorsch.com/api/subject-pages/subject/anthropology-assignment-help`)*
- **Next.js Internal Route:** `/api/subject-pages/{slug}`

#### Response Structure:
```json
{
  "success": true,
  "data": {
    "page": {
      "id": 159,
      "subject_id": 2,
      "category": "Humanities",
      "category_slug": "humanities",
      "slug": "subject/anthropology-assignment-help",
      "meta_title": "Anthropology Assignment Help | Essays, Research & Dissertations",
      "meta_description": "Need help with anthropology assignments?...",
      "hero_heading": "Anthropology Assignment Help UK",
      "hero_highlight": "Instant Assistance",
      "hero_content": "Get reliable Anthropology Assignment Help UK...",
      "long_content": "<h2>Anthropology Assignment Help UK...</h2>",
      "why_heading": "...",
      "why_items": [ ... ],
      "faqs": [ ... ],
      "is_published": true
    },
    "experts": [ ... ],
    "reviews": [ ... ]
  }
}
```

---

### Endpoint 3: Free Samples & Essays Widget
- **Method:** `GET`
- **Direct Backend URL:** `https://ain.warrgyizmorsch.com/api/samples?category={category_slug}&page=1&limit=100`  
  *(Example: `https://ain.warrgyizmorsch.com/api/samples?category=humanities&page=1&limit=100`)*

When rendering the Subject Detail page, pass `page.category_slug` to this API to automatically load downloadable samples matching that subject's category.

---

### Endpoint 4: Standalone Categories List (Optional)
- **Method:** `GET`
- **Direct Backend URL:** `https://ain.warrgyizmorsch.com/api/subject-categories`

#### Response:
```json
{
  "status": "success",
  "success": true,
  "data": [
    { "name": "Business", "slug": "business" },
    { "name": "Computer Science", "slug": "computer-science" },
    { "name": "Economics", "slug": "economics" },
    { "name": "Engineering", "slug": "engineering" },
    { "name": "Finance", "slug": "finance" },
    { "name": "Humanities", "slug": "humanities" },
    { "name": "Law", "slug": "law" },
    { "name": "Management", "slug": "management" },
    { "name": "Marketing", "slug": "marketing" },
    { "name": "Mathematics", "slug": "mathematics" },
    { "name": "Programming", "slug": "programming" },
    { "name": "Science", "slug": "science" }
  ]
}
```

---

## 3. Ready-to-Use Frontend Implementation Snippets

### A. Navbar Dropdown Component (Next.js / React)

You can either consume `response.categories` directly, or group `response.data` by `category`:

```tsx
import React, { useEffect, useState } from 'react';
import Link from 'next/link';

interface SubjectItem {
  id: number;
  title: string;
  slug: string;
  category: string;
  category_slug: string;
}

interface CategoryGroup {
  name: string;
  slug: string;
  subjects: SubjectItem[];
}

export function NavbarSubjectsDropdown() {
  const [categories, setCategories] = useState<CategoryGroup[]>([]);
  const [activeCategory, setActiveCategory] = useState<CategoryGroup | null>(null);

  useEffect(() => {
    async function fetchSubjects() {
      try {
        const res = await fetch('/api/subject-pages'); // Or direct https://ain.warrgyizmorsch.com/api/subject-pages
        const json = await res.json();
        
        // Option 1: Use pre-grouped categories array directly from API
        if (json.categories && json.categories.length > 0) {
          setCategories(json.categories);
          setActiveCategory(json.categories[0]);
        } 
        // Option 2: Fallback grouping logic on frontend
        else if (json.data) {
          const map: { [key: string]: CategoryGroup } = {};
          json.data.forEach((sub: SubjectItem) => {
            const cat = sub.category || 'General';
            if (!map[cat]) {
              map[cat] = { name: cat, slug: sub.category_slug || 'general', subjects: [] };
            }
            map[cat].subjects.push(sub);
          });
          const list = Object.values(map);
          setCategories(list);
          setActiveCategory(list[0] || null);
        }
      } catch (err) {
        console.error('Failed to load subject categories', err);
      }
    }
    fetchSubjects();
  }, []);

  return (
    <div className="subjects-nav-dropdown d-flex">
      {/* Left Column: Categories List (Management >, Humanities >, etc.) */}
      <ul className="category-list list-unstyled">
        {categories.map((cat) => (
          <li
            key={cat.slug}
            onMouseEnter={() => setActiveCategory(cat)}
            className={`category-item p-2 ${activeCategory?.slug === cat.slug ? 'active' : ''}`}
          >
            <span>{cat.name}</span>
            <span className="arrow">&rsaquo;</span>
          </li>
        ))}
      </ul>

      {/* Right Column: Sub-Subjects under the hovered category */}
      <div className="subjects-sub-pane p-3">
        <h5>{activeCategory?.name} Assignments</h5>
        <div className="row">
          {activeCategory?.subjects.map((sub) => (
            <div key={sub.id} className="col-6 mb-2">
              <Link href={`/${sub.slug}`} className="text-decoration-none">
                {sub.title}
              </Link>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
```

---

### B. All Subjects Listing Page (`/subjects`)

No changes needed to breaking logic — just map over `data`:

```tsx
export default function AllSubjectsPage({ subjects }: { subjects: SubjectItem[] }) {
  // `subjects` comes from json.data (sorted A to Z)
  return (
    <div className="container py-5">
      <h1>All Academic Subjects</h1>
      <div className="row g-3">
        {subjects.map((item) => (
          <div key={item.id} className="col-md-4">
            <div className="card p-3 h-100">
              <span className="badge bg-light text-primary mb-2 align-self-start">
                {item.category}
              </span>
              <h6>{item.title}</h6>
              <a href={`/${item.slug}`} className="stretched-link">View Subject Details &rarr;</a>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
```

---

### C. Subject Detail Page: Linking Related Samples

In your Subject Detail page component:

```tsx
// Inside Subject Detail Page (e.g., /subjects/anthropology-assignment-help)
export async function getSubjectData(slug: string) {
  // 1. Fetch Subject Detail
  const subjectRes = await fetch(`https://ain.warrgyizmorsch.com/api/subject-pages/subject/${slug}`);
  const subjectJson = await subjectRes.json();
  const page = subjectJson.data.page;

  // 2. Fetch Matching Free Samples using page.category_slug
  const samplesRes = await fetch(
    `https://ain.warrgyizmorsch.com/api/samples?category=${page.category_slug}&page=1&limit=6`
  );
  const samplesJson = await samplesRes.json();
  const samples = samplesJson.data?.data || [];

  return { page, samples, experts: subjectJson.data.experts, reviews: subjectJson.data.reviews };
}
```

---

## 4. Summary Checklist for Frontend

| Task | Estimated Time | Complexity |
| :--- | :--- | :--- |
| 1. Update Navbar Dropdown component to read `item.category` or `json.categories` | ~10 mins | Very Low |
| 2. Pass `page.category_slug` to the samples API (`/api/samples?category={slug}`) | ~5 mins | Very Low |
| 3. Verify `/subjects` listing page renders normally | ~2 mins | Zero changes required |
