<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
  
        @foreach($sample as $sample)
            <url>
                <loc>{{ url('free-samples/'.$sample->categotyData->name . '/' . $sample->slug) }}</loc>
                <lastmod>{{ now()->toDateString() }}</lastmod>
                <priority>0.5</priority>
                <changefreq>weekly</changefreq>
            </url>
        @endforeach
        @foreach($categories as $category)
            <url>
                <loc>{{ url('free-samples/' . $category->name) }}</loc>
                <lastmod>{{ now()->toDateString() }}</lastmod>
                <priority>0.8</priority>
                <changefreq>daily</changefreq>
            </url>
        @endforeach
</urlset>
