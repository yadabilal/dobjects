<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  @foreach($products as $product)

  <url>
      <loc>{{ route('home.product.show', ['url' => $product->name]) }}</loc>
      <lastmod>{{ \Carbon\Carbon::parse($product->created_at)->toAtomString() }}</lastmod>
      <changefreq>daily</changefreq>
      <priority>0.9</priority>
  </url>
  @endforeach
</urlset>
