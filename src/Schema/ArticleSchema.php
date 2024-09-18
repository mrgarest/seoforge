<?php

namespace MrGarest\SeoForge\Schema;

class ArticleSchema extends \MrGarest\SeoForge\Schema
{
    public const TYPE_ARTICLE = 'Article';
    public const TYPE_NEWS_ARTICLE = 'NewsArticle';
    public const TYPE_BLOG_POSTING = 'BlogPosting';

    protected $publisherType = ['Person', 'Organization'];

    /**
     * @param string $type     Article type (Article, NewsArticle, BlogPosting)
     * @throws Exception 
     */
    public function __construct($type = self::TYPE_ARTICLE)
    {
        if (!in_array($type, [self::TYPE_ARTICLE, self::TYPE_NEWS_ARTICLE, self::TYPE_BLOG_POSTING])) throw new \Exception('Invalid article type');
        parent::__construct($type);
    }

    /**
     * Set article title.
     * @param string $str     Article title
     */
    public function setTitle(string $str)
    {
        $this->JsonLD['headline'] = $str;
        $this->JsonLD['name'] = $str;
    }

    /**
     * Set article description.
     * @param string $str     Article description
     */
    public function setDescription(string $str)
    {
        $this->JsonLD['description'] = $str;
    }

    /**
     * Set the date and time the article was published and last modified.
     * @param string $published     The date and time the article was first published (ISO 8601)
     * @param string $modified      The date and time the article was most recently modified (ISO 8601)
     */
    public function setDate(string $published, string $modified = null)
    {
        $this->JsonLD['datePublished'] = $published;
        if ($modified != null) $this->JsonLD['dateModified'] = $modified;
    }

    /**
     * Set a link to the page with the article.
     * @param string $url     Link to article page
     */
    public function setUrl(string $url = null)
    {
        if ($url == null) {
            $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $url = explode('?', $url);
            $url = $url[0];
            $url = str_replace('.php', '', $url);
        }
        $this->JsonLD['mainEntityOfPage'] = [
            '@type' => 'WebPage',
            '@id' => $url
        ];
    }

    /**
     * Set article author.
     * @param string $type    Use the Person type for people, and the Organization type for organizations
     * @param string $name    The name of the author
     * @param string $url     A link to a web page that uniquely identifies the author of the article
     * @throws Exception 
     */
    public function setAuthor(string $type, string $name, string $url = null)
    {
        if (!in_array($type, $this->publisherType)) throw new \Exception('Invalid article author type');
        $this->JsonLD['author'] = [
            '@type' => $type,
            'name' => $name
        ];
        if ($url != null) $this->JsonLD['author']['url'] = $url;
    }

    /**
     * Set the article publisher.
     * @param string $type    Use the Person type for people, and the Organization type for organizations
     * @param string $name    The name of the author
     * @param string $url     Link to images with logo
     */
    public function setPublisher(string $type, string $name, string $logoUrl = null)
    {
        if (!in_array($type, $this->publisherType)) throw new \Exception('Invalid article publisher type');
        $this->JsonLD['publisher'] = [
            '@type' => 'Organization',
            'name' => $name
        ];
        if ($logoUrl != null) $this->JsonLD['publisher']['logo'] = ['@type' => 'ImageObject', 'url' => $logoUrl];
    }

    /**
     * Add image.
     * @param string $url     Direct link to image
     */
    public function addImage(string $url)
    {
        $this->JsonLD['image'][] = $url;
    }
}
