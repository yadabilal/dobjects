<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
      <loc>{{ route('home') }}</loc>
      <lastmod>{{ \Carbon\Carbon::now()->toAtomString() }}</lastmod>
      <changefreq>daily</changefreq>
      <priority>1.0</priority>
  </url>
  <url>
      <loc>{{ route('home.book') }}</loc>
      <lastmod>{{ \Carbon\Carbon::now()->toAtomString() }}</lastmod>
      <changefreq>daily</changefreq>
      <priority>1.0</priority>
  </url>
  <url>
      <loc>{{ route('login') }}</loc>
      <lastmod>{{ \Carbon\Carbon::now()->toAtomString() }}</lastmod>
      <changefreq>weekly</changefreq>
      <priority>0.5</priority>
  </url>
  <url>
      <loc>{{ route('auth.first') }}</loc>
      <lastmod>{{ \Carbon\Carbon::now()->toAtomString() }}</lastmod>
      <changefreq>weekly</changefreq>
      <priority>0.5</priority>
  </url>
  <url>
      <loc>{{ route('forgotpassword') }}</loc>
      <lastmod>{{ \Carbon\Carbon::now()->toAtomString() }}</lastmod>
      <changefreq>weekly</changefreq>
      <priority>0.5</priority>
  </url>
  <url>
      <loc>{{ route('contract') }}</loc>
      <lastmod>{{ \Carbon\Carbon::now()->toAtomString() }}</lastmod>
      <changefreq>montly</changefreq>
      <priority>0.5</priority>
  </url>

  <url>
      <loc>{{route('contract.sub', ['url' => 'iptal-ve-iade-kosullari'])}}</loc>
      <lastmod>{{ \Carbon\Carbon::now()->toAtomString() }}</lastmod>
      <changefreq>montly</changefreq>
      <priority>0.5</priority>
  </url>

    <url>
        <loc>{{route('contract.sub', ['url' => 'kisisel-verilerin-korunmasi'])}}</loc>
        <lastmod>{{ \Carbon\Carbon::now()->toAtomString() }}</lastmod>
        <changefreq>montly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{route('contract.sub', ['url' => 'gizlilik-sozlesmesi'])}}</loc>
        <lastmod>{{ \Carbon\Carbon::now()->toAtomString() }}</lastmod>
        <changefreq>montly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{route('contract.sub', ['url' => 'kullanici-sozlesmesi'])}}</loc>
        <lastmod>{{ \Carbon\Carbon::now()->toAtomString() }}</lastmod>
        <changefreq>montly</changefreq>
        <priority>0.5</priority>
    </url>

</urlset>
