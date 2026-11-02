<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $sample->meta_title ?: $sample->title }} — Prime Assignment Sample</title>
    <meta name="description" content="{{ $sample->meta_description }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        *{box-sizing:border-box}
        body{margin:0;font-family:'Plus Jakarta Sans',Inter,Arial,sans-serif;color:#1e293b;background:#f8fafc;line-height:1.7}
        .preview-bar{background:#fef3c7;color:#92400e;text-align:center;padding:10px;font-size:13px;font-weight:600;border-bottom:1px solid #fde68a}
        .container{max-width:960px;margin:40px auto;padding:0 24px}
        .sample-card{background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.04)}
        .sample-header{padding:40px 48px 24px;border-bottom:1px solid #f1f5f9}
        .category-badge{display:inline-block;background:#eff6ff;color:#2563eb;font-size:12px;font-weight:700;letter-spacing:0.5px;padding:6px 14px;border-radius:20px;text-transform:uppercase;margin-bottom:16px}
        h1{font-size:32px;line-height:1.3;color:#0f172a;margin:0 0 20px;font-weight:800}
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-top:20px}
        .stat-box{background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px 18px}
        .stat-label{font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px}
        .stat-value{font-size:18px;font-weight:700;color:#0f172a;margin-top:4px}
        .sample-body{padding:40px 48px;font-size:16px;color:#334155}
        .sample-body h2,.sample-body h3{color:#0f172a;margin-top:32px;margin-bottom:16px;font-weight:700}
        .sample-body p{margin-bottom:20px;line-height:1.8}
        .sample-body ul,.sample-body ol{margin-bottom:20px;padding-left:24px}
        .sample-body li{margin-bottom:8px;line-height:1.7}
        .sample-body blockquote{margin:24px 0;padding:16px 24px;border-left:4px solid #3b82f6;background:#f0f7ff;border-radius:0 8px 8px 0;color:#1e3a8a;font-style:italic}
        .cta-banner{background:linear-gradient(135deg,#1e293b 0%,#0f172a 100%);color:#ffffff;padding:36px 48px;border-radius:16px;margin:32px 0;display:flex;justify-content:space-between;align-items:center;gap:24px;flex-wrap:wrap}
        .cta-title{font-size:22px;font-weight:700;margin:0 0 6px}
        .cta-sub{color:#94a3b8;font-size:14px;margin:0}
        .cta-btn{display:inline-block;background:#2563eb;color:#ffffff;padding:12px 28px;font-weight:700;border-radius:8px;text-decoration:none;transition:background 0.2s}
        .cta-btn:hover{background:#1d4ed8}
        @media(max-width:768px){
            .sample-header,.sample-body{padding-left:24px;padding-right:24px}
            h1{font-size:24px}
            .cta-banner{padding:24px}
        }
    </style>
</head>
<body>
    <div class="preview-bar">Prime Assignment Sample Preview — Draft samples are not visible on the public frontend.</div>

    <div class="container">
        <article class="sample-card">
            <header class="sample-header">
                <span class="category-badge">{{ $sample->category?->name ?? 'Academic Sample' }}</span>
                <h1>{{ $sample->title }}</h1>

                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-label">Category</div>
                        <div class="stat-value">{{ $sample->category?->name ?? 'General' }}</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">Length</div>
                        <div class="stat-value">{{ $sample->pages_count ?? 1 }} Pages</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">Word Count</div>
                        <div class="stat-value">{{ number_format($sample->words_count ?? 250) }} Words</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">Citation Style</div>
                        <div class="stat-value">Standard / APA</div>
                    </div>
                </div>
            </header>

            <div class="sample-body">
                {!! $sample->content !!}

                <div class="cta-banner">
                    <div>
                        <div class="cta-title">Need a custom paper like this?</div>
                        <div class="cta-sub">Get original, plagiarism-free academic assistance crafted by verified subject experts.</div>
                    </div>
                    <a href="javascript:void(0)" class="cta-btn">Order Your Paper</a>
                </div>
            </div>
        </article>
    </div>
</body>
</html>

