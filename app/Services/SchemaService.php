<?php
namespace App\Services;

class SchemaService
{
    function generateSchema($title, $description, $canonical, $priceRanges)
{
    return json_encode([
        "@context" => "https://schema.org/",
        "@type" => "Product",
        "name" => $title,
        "description" => $description,
        "brand" => [
            "@type" => "EducationalOrganization",
            "name" => "Assignment In Need",
            "logo" => "https://www.assignnmentinneed.com/assets/media/avatars/assignment_logo.png"
        ], 
        "offers" => array_map(function ($range) {
            return [
                "@type" => "AggregateOffer",
                "url" => "https://www.assignnmentinneed.com/Offers",
                "priceCurrency" => $range['currency'],
                "lowPrice" => $range['low'],
                "highPrice" => $range['high'],
                "offerCount" => "6"
            ];
        }, $priceRanges),
        "aggregateRating" => [
            "@type" => "AggregateRating",
            "ratingValue" => "4.8",
            "ratingCount" => "5126"
        ],
        "review" => [
            [
                "@type" => "Review",
                "author" => [
                    "@type" => "Organization",
                    "name" => "Assignment In Need Website"
                ],
                "reviewRating" => [
                    "@type" => "Rating",
                    "ratingValue" => "4.8",
                    "bestRating" => 5
                ],
                "reviewBody" => "Assignment In Need is a great platform for students to get their assignments done. The quality of work is excellent and the customer service is very responsive. Highly recommend!"
            ],
          
        ],
        "url" => $canonical
    ], JSON_UNESCAPED_SLASHES);
}


function generateFaqSchema(array $faqEntries): string
{
    $mainEntity = array_map(function ($entry) {
        return [
            "@type" => "Question",
            "name" => $entry['question'],
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => $entry['answer']
            ]
        ];
    }, $faqEntries);

    return json_encode([
        "@context" => "https://schema.org",
        "@type" => "FAQPage",
        "mainEntity" => $mainEntity
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}


function generateWebsiteSchema()
{
    return json_encode([
        "@context" => "https://schema.org",
        "@type" => "WebSite",
        "name" => "Assignment In Need",
        "url" => "https://www.assignnmentinneed.com",
        "potentialAction" => [
            "@type" => "SearchAction",
            "target" => "https://www.assignnmentinneed.com/search?q={search_term_string}",
            "query-input" => "required name=search_term_string"
        ],
        "mainEntity" => [
            "@context" => "https://schema.org",
            "@type" => "WebPage",
            "@id" => "https://www.assignnmentinneed.com/#webpage",
            "url" => "https://www.assignnmentinneed.com/",
            "name" => "Online Assignment Help Services for UK & International Students | Assignment In Need",
            "description" => "Assignment In Need provides professional academic support, connecting UK students with expert writers for all types of assignment help. Our service helps students meet deadlines, improve grades, and reduce academic stress, guaranteeing 100% plagiarism-free content with timely delivery and 24/7 live support.",
            "inLanguage" => "en-GB",
            "isPartOf" => [
                "@type" => "WebSite",
                "url" => "https://www.assignnmentinneed.com"
            ]
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}


// Function to generate organization schema
function generateOrganizationSchema(): string
{
    return json_encode([
        "@context" => "https://schema.org",
        "@type" => "Organization",
        "name" => "Assignment In Need",
        "url" => "https://www.assignnmentinneed.com",
        "logo" => "https://www.assignnmentinneed.com/assets/media/avatars/assignment_logo.png",
        "contactPoint" => [
            "@type" => "ContactPoint",
            "telephone" => "+44 7435 256433",
            "contactType" => "Customer Service",
            "availableLanguage" => ["English"]
        ],
        "sameAs" => [
            "https://www.facebook.com/assignmentinneed",
            "https://www.instagram.com/p/CXN_t7ALYGH/",
            "https://twitter.com/assignment_in"
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}


function BreadcrumbList(array $breadcrumbs): string
{
    $breadcrumbSchema = [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => []
    ];

    foreach ($breadcrumbs as $position => $breadcrumb) {
        $breadcrumbSchema['itemListElement'][] = [
            "@type" => "ListItem",
            "position" => $position + 1,
            "name" => $breadcrumb['name'],
            "item" => $breadcrumb['url']
        ];
    }

    // Return as a JSON string
    return json_encode($breadcrumbSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}


function hrefFlagUrl()
{
    return'
      <link rel="alternate" hreflang="x-default" href="https://www.assignnmentinneed.com" />
      <link rel="alternate" href="https://www.assignnmentinneed.com/uk" hreflang="en-gb" />
      <link rel="alternate" href="https://www.assignnmentinneed.com/au" hreflang="en-au" />
      <link rel="alternate" href="https://www.assignnmentinneed.com/es" hreflang="en-es" />
      <link rel="alternate" href="https://www.assignnmentinneed.com/my" hreflang="en-my" />
      <link rel="alternate" href="https://www.assignnmentinneed.com/ae" hreflang="en-ae" />
      <link rel="alternate" href="https://www.assignnmentinneed.com/canada" hreflang="en-ca" />
  ';
}


public function reviewSchemaVideo($videos)
{
    if (!is_array($videos)) {
        return []; // or handle error appropriately
    }

    $schemas = array_map(function ($video) {
        return [
            "@context"     => "https://schema.org",
            "@type"        => "VideoObject",
            "name"         => $video["name"] ?? '',
            "description"  => $video["description"] ?? '',
            "thumbnailUrl" => $video["thumbnailUrl"] ?? '',
            "uploadDate"   => $video["uploadDate"] ?? '',
            "embedUrl"     => $video["embedUrl"] ?? '',
            "duration"     => $video["duration"] ?? '',
        ];
    }, $videos);

    return $schemas;
}


function generateUKServicesSchema($services)
{
    $itemList = [];
    foreach ($services as $index => $service) {
        $itemList[] = [
            "@type" => "ListItem",
            "position" => $index + 1,
            "item" => [
                "@type" => "Service",
                "name" => $service['name'],
                "url" => $service['url'],
                "description" => $service['description'],
                "areaServed" => [
                    "@type" => "Country",
                    "name" => "United Kingdom"
                ]
            ]
        ];
    }

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "ItemList",
        "name" => "UK Assignment Help Services by Assignment In Need",
        "description" => "Your trusted partner for academic success in the United Kingdom. We provide expert assignment writing, editing, and proofreading services tailored to UK university standards.",
        "itemListElement" => $itemList
    ];

    return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

// New method for Article schema
    public function generateArticleSchema($headline, $description, $image, $url): string
    {
        return json_encode([
            "@context" => "https://schema.org",
            "@type" => "Article",
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => $url
            ],
            "headline" => $headline,
            "image" => $image,
            "author" => [
                "@type" => "Organization",
                "name" => "Assignment In Need",
                "url" => "https://www.assignnmentinneed.com/"
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => "Assignment In Need",
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => "https://www.assignnmentinneed.com/assets/media/layout/assignment_logo.webp"
                ]
            ],
            "description" => $description
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
