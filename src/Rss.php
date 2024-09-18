<?php

namespace MrGarest\SeoForge;

use Illuminate\Support\Facades\Response;

class Rss
{
    private $site = [];
    private $items = [];

    /**
     * Set channel title.
     * @param string $str     Сhannel title
     */
    public function setTitle(string $str)
    {
        $this->site[] = $this->tag('title', $str, true);
    }

    /**
     * Set channel description.
     * @param string $str     Сhannel description
     */
    public function setDescription(string $str)
    {
        $this->site[] = $this->tag('description', $str, true);
    }

    /**
     * Set channel link.
     * @param string $url     Сhannel link
     */
    public function setUrl(string $url)
    {
        $this->site[] = $this->tag('link', $url, false);
    }

    /**
     * Set Atom link.
     * @param string $url     Atom link
     */
    public function setAtomLink(string $url)
    {
        $this->site[] = '<atom:link href="' . $url . '" rel="self" type="application/rss+xml"/>';
    }

    /**
     * Set channel image.
     * @param string $url     Image url
     * @param string $title   Title
     * @param string $link    Links to the site
     */
    public function setImage(string $url, string $title, string $link)
    {
        $str = '<image>';
        $str .= $this->tag('url', $url, false);
        $str .= $this->tag('title', $title, false);
        $str .= $this->tag('link', $link, false);
        $str .= '</image>';
        $this->site[] = $str;
    }

    /**
     * Set page language.
     * @param string $language   Language code
     */
    public function setLanguage(string $language)
    {
        $this->site[] = $this->tag('language', $language, true);
    }

    /**
     * Adds an item to the feed.
     * @param string $title          Title
     * @param string $description    Description
     * @param string $text           Full text
     * @param string $url            Link to the page
     * @param string $pubDate        Date of publication (ISO 8601)
     * @param string $image          Image url (optional)
     */
    public function addItem(string $title, string $description, string $text, string $url, string $pubDate, $image = null)
    {
        $this->items[] = [
            'title' => $title,
            'description' => $description,
            'text' => $text,
            'url' => $url,
            'pubDate' => date('D, d M Y H:i:s O', strtotime($pubDate)),
            'image' => $image,
        ];
    }

    protected function tag(string $tag, string $value, bool $cdata = false)
    {
        $str = "<$tag>";
        if ($cdata) $str .= '<![CDATA[ ';
        $str .= $value;
        if ($cdata) $str .= ' ]]>';
        $str .= "</$tag>";
        return $str;
    }

    protected function arrayItemsToStr(array $arr)
    {
        $str = '';
        foreach ($arr as $value) {
            $str .= '<item>';
            $str .= $this->tag('title', strip_tags($value['title']), false);
            $str .= $this->tag('link', $value['url'], false);
            $str .= $this->tag('guid', $value['url'], false);
            $str .= $this->tag('description', strip_tags($value['description']), false);
            $str .= $this->tag('content:encoded', $value['text'], true);
            $str .= $this->tag('pubDate', $value['pubDate'], false);
            if ($value['image'] != null) $str .= '<media:content medium="image" url="' . $value['image'] . '"/>';
            $str .= '</item>';
        }
        return $str;
    }

    /**
     * Build RSS.
     * @param bool $xmlDocument   Displaying a page as an XML document
     * @return string             RSS
     */
    public function build($xmlDocument = true)
    {

        $str = '<rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" seoforge="https://github.com/mrgarest/seoforge" version="2.0">';
        $str .= '<channel>';
        foreach ($this->site as $value) {
            $str .= $value;
        }
        $str .= $this->arrayItemsToStr($this->items);
        $str .= '</channel>';
        $str .= '</rss>';
        return $xmlDocument ? Response::make($str, 200, ['Content-Type' => 'application/xml']) : $str;
    }

    /**
     * Delete added data.
     */
    public function clear()
    {
        $this->site = [];
        $this->items = [];
    }
}
