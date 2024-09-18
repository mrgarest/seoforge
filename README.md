# SeoForge

A simple SEO package for Laravel that allows you to quickly create meta tags, json-ld, sitemaps, and RSS feeds.

## Installation

You can install the package via composer:

```
composer require mrgarest/seoforge
```
## Example

### Meta tags

```php
use MrGarest\SeoForge\MetaTags;
```

```php
$seo = new MetaTags();
$seo->setLocale(str_replace('_', '-', app()->getLocale()));
$seo->setTitle('SeoForge');
$seo->setDescription('Simple Laravel SEO Package');
$seo->setType('website');
$seo->setUrl(route('home'));

return view('welcome', [
    'seo' => $seo->build()
]);
```

### Json-ld

```php
use MrGarest\SeoForge\Schema\ArticleSchema;
```

```php
$seo = new ArticleSchema(ArticleSchema::TYPE_ARTICLE);
$seo->setTitle('SeoForge');
$seo->setDescription('Simple Laravel SEO Package');
$seo->setDate('2024-09-18T15:24:18+00:00');
$seo->setUrl('https://example.com/seoforge');
$seo->setAuthor(ArticleSchema::AUTHOR_TYPE_PERSON, 'Garest', 'https://github.com/mrgarest');
$seo->setPublisher('Garest', 'https://example.com/seoforge.png');
$seo->addImage('https://example.com/seoforge.png');

return view('welcome', [
    'seo' => $seo->build()
]);
```

### Sitemap

```php
use MrGarest\SeoForge\Sitemap;
```

```php
$seo = new Sitemap();
$seo->addItem(route('home'), '2024-09-18T15:24:18+00:00', null, 1);
$seo->addItem(route('news'), '2024-09-18T15:24:18+00:00', null, 0.8);
$seo->addItem(route('gallery'), '2024-09-18T15:24:18+00:00', null, 0.6);
return $seo->build();
```

### RSS

```php
use MrGarest\SeoForge\Rss;
```

```php
$seo = new Rss();
$seo->setTitle('SeoForge');
$seo->setLanguage('en');
$seo->setDescription('Simple Laravel SEO Package');
$seo->setUrl(route('home'));
$seo->setImage('https://example.com/seoforge.png', 'SeoForge', route('home'));
$seo->addItem('SeoForge', 'Simple Laravel SEO Package', 'A simple SEO package for Laravel that allows you to quickly create meta tags, json-ld, sitemaps, and RSS feeds.', 'https://example.com/seoforge', '2024-09-18T15:24:18+00:00', 'https://example.com/seoforge1.png');

return $seo->build();
```
