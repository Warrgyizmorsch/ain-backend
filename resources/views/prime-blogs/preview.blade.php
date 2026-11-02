<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $blog->meta_title ?: $blog->title }}</title>
    <meta name="description" content="{{ $blog->meta_description }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        *{box-sizing:border-box}
        body{margin:0;font-family:'Plus Jakarta Sans',Inter,Arial,sans-serif;color:#1e293b;background:#f8fafc;line-height:1.7}
        .preview-bar{background:#fef3c7;color:#92400e;text-align:center;padding:10px;font-size:13px;font-weight:600;border-bottom:1px solid #fde68a}
        .container{max-width:900px;margin:40px auto;padding:0 24px}
        .article-card{background:#ffffff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.04)}
        .article-header{padding:40px 48px 24px}
        .category-badge{display:inline-block;background:#eff6ff;color:#2563eb;font-size:12px;font-weight:700;letter-spacing:0.5px;padding:6px 14px;border-radius:20px;text-transform:uppercase;margin-bottom:18px}
        h1{font-size:38px;line-height:1.25;color:#0f172a;margin:0 0 20px;font-weight:800}
        .meta-bar{display:flex;align-items:center;gap:18px;font-size:14px;color:#64748b;padding-bottom:24px;border-bottom:1px solid #f1f5f9}
        .featured-img-wrap{padding:0 48px 30px}
        .featured-img{width:100%;max-height:480px;object-fit:cover;border-radius:12px}
        .article-body{padding:0 48px 48px;font-size:16px;color:#334155}
        .article-body h2,.article-body h3{color:#0f172a;margin-top:32px;margin-bottom:16px;font-weight:700}
        .article-body p{margin-bottom:20px;line-height:1.8}
        .article-body ul,.article-body ol{margin-bottom:20px;padding-left:24px}
        .article-body li{margin-bottom:8px;line-height:1.7}
        .article-body img{max-width:100%;height:auto;border-radius:8px;margin:20px 0}
        .faqs-section{margin-top:40px;padding-top:32px;border-top:1px solid #e2e8f0}
        .faq-item{background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:18px 22px;margin-bottom:14px}
        .faq-q{font-weight:700;color:#0f172a;font-size:16px;margin-bottom:8px}
        .faq-a{color:#475569;margin:0}
        .author-box{display:flex;align-items:center;gap:16px;background:#f8fafc;border-radius:12px;padding:20px;margin-top:40px}
        .author-avatar{width:56px;height:56px;border-radius:50%;background:#e2e8f0;display:grid;place-items:center;font-size:24px;color:#64748b}
        @media(max-width:768px){
            .article-header,.featured-img-wrap,.article-body{padding-left:24px;padding-right:24px}
            h1{font-size:28px}
        }
    </style>
</head>
<body>
    <div class="preview-bar">Prime Assignment Blog Preview — Draft articles are not visible on the public frontend.</div>

    <div class="container">
        <article class="article-card">
            <header class="article-header">
                @if($blog->category)
                    <span class="category-badge">{{ $blog->category }}</span>
                @endif
                <h1>{{ $blog->title }}</h1>
                <div class="meta-bar">
                    <span><i class="far fa-user me-1"></i> {{ $blog->author_name ?: 'Prime Assignment Team' }}</span>
                    <span><i class="far fa-calendar-alt me-1"></i> {{ ($blog->created_at ?: now())->format('M d, Y') }}</span>
                    <span><i class="far fa-clock me-1"></i> 5 min read</span>
                </div>
            </header>

            @if($blog->image)
                <div class="featured-img-wrap">
                    <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="featured-img">
                </div>
            @endif

            <div class="article-body">
                {!! $blog->content !!}

                @if(!empty($blog->faqs) && count($blog->faqs) > 0)
                    <div class="faqs-section">
                        <h2 style="margin-top:0;">Frequently Asked Questions</h2>
                        @foreach($blog->faqs as $faq)
                            <div class="faq-item">
                                <div class="faq-q">Q: {{ $faq['question'] ?? '' }}</div>
                                <p class="faq-a">{{ $faq['answer'] ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="author-box">
                    <div class="author-avatar"><i class="fas fa-user-edit"></i></div>
                    <div>
                        <strong style="color:#0f172a; font-size:16px;">{{ $blog->author_name ?: 'Prime Academic Writer' }}</strong>
                        <div style="font-size:13px; color:#64748b;">Subject Specialist & Academic Researcher at Prime Assignment</div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</body>
</html>

