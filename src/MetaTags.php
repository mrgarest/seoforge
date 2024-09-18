<?php

namespace MrGarest\SeoForge;

class MetaTags
{
    protected $pattern = ['ISO8601' => '/^(\d{4})-(\d{2})-(\d{2})T(\d{2})\:(\d{2})\:(\d{2})[+-](\d{2})\:(\d{2})$/'];
    protected $Tags = [];
    protected $OpenGraph = [];
    protected $Twitter = [];
    protected $Google = [];

    /**
     * Set robots meta tags.
     * @param string $name
     * @param string $content
     */
    public function setRobots(string $str)
    {
        $this->Tags[] = $this->setMetaTag('robots', $str);
    }

    /**
     * Set permission for search robots to index the site page.
     * @param bool $index
     */
    public function setIndex(bool $index = true)
    {
        $this->Tags[] = $this->setMetaTag('robots', $index == true ? 'index, follow' : 'noindex, nofollow');
    }

    /**
     * Set page language.
     * @param string $str Language code
     */
    public function setLocale(string $str)
    {
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:locale', $str);
    }

    /**
     * Set alternate page languages.
     * @param array $arr Language code
     */
    public function setLocaleAlternate(array $arr)
    {
        foreach ($arr as $value) $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:locale:alternate', $value);
    }

    /**
     * Allows you to specify that the page contains adult content and will not be displayed in search engines when using SafeSearch.
     */
    public function setAdultPage()
    {
        $this->Tags[] = $this->setMetaTag('rating',  'adult');
    }

    /**
     * Set page title.
     * @param string $str        Page title
     * @param bool $strimwidth   Truncate string to 70 characters
     */
    public function setTitle(string $str, bool $strimwidth = false)
    {
        if ($strimwidth === true) $str = mb_strimwidth($str, 0, 70, '...');
        $this->Tags[] = $this->setArrayTag('title', null, $str);
        $str = e($str);
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:title',  $str);
        $this->Twitter[] = $this->setMetaTag('twitter:title',  $str);
    }

    /**
     * Set page description.
     * @param string $str        Page description
     * @param bool $strimwidth   Truncate string to 160 characters
     */
    public function setDescription(string $str, bool $strimwidth = false)
    {
        $str = e($str);
        if ($strimwidth === true) $str = mb_strimwidth($str, 0, 160, '...');
        $this->Tags[] = $this->setMetaTag('description',  $str);
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:description',  $str);
        $this->Twitter[] = $this->setMetaTag('twitter:description',  $str);
        $this->setTwitterCard('summary');
    }

    /**
     * Set page keywords.
     * @deprecated Most search engines do not support the keyword meta tag.
     */
    public function setKeywords(array $arr)
    {
        $keywords = '';
        foreach ($arr as $value) $keywords .= $value . ', ';
        $this->Tags[] = $this->setMetaTag('keywords',  preg_replace('/(, $|^, )/ms', '', $keywords));
    }

    /**
     * Set author.
     * @param string $str
     */
    public function setAuthor(string $str)
    {
        $this->Tags[] = $this->setMetaTag('author', $str);
    }

    /**
     * Set site name.
     * @param string $str
     */
    public function setSiteName(string $str)
    {
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:site_name',  $str);
    }

    /**
     * Set page type.
     * @param string $str
     */
    public function setType(string $str)
    {
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:type',  $str);
    }

    /**
     * Set phone number.
     * @param int|string $str
     */
    public function setPhoneNumber(int|string $str)
    {
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:phone_number',  $str);
    }

    /**
     * Set address.
     * @param string $country
     * @param string $region
     * @param string $city
     * @param string $street
     * @param int|string $postalCode
     */
    public function setAddress(string $country, string $region, string $city, string $street, int|string $postalCode)
    {
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:country-name', $country);
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:region', $region);
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:locality', $city);
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:street-address', $street);
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:postal-code', $postalCode);
    }

    /**
     * Set location coordinates.
     * @param float $latitude
     * @param float $longitude
     */
    public function setLocationСoordinates(float $latitude, float $longitude)
    {
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:latitude', $latitude);
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:longitude', $longitude);
    }

    /**
     * Set page link.
     * @param string $url
     */
    public function setUrl(string $url = null)
    {
        if ($url == null) {
            $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $url = explode('?', $url);
            $url = $url[0];
            $url = str_replace('.php', '', $url);
        }
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:url',  $url);
        $this->Twitter[] = $this->setMetaTag('twitter:url',  $url);
    }

    /**
     * Set image.
     * @param string $url     Direct link to image
     * @param int $width      Image width in pixels
     * @param int $height     Image height in pixels
     * @param string $alt     A description of what is in the image (not a caption)
     */
    public function setImage(string $url, int $width = null, int $height = null, string $alt = null)
    {
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:image',  $url);
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:image:secure_url', $url);
        preg_match('/.+?\.([a-z]+)($|\?)/s', $url, $matches);
        if (isset($matches[1])) $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:image:type', 'image/' . $matches[1]);
        if ($width != null && $height != null) {
            $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:image:width', $width);
            $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:image:height', $height);
        }
        $this->setTwitterCard('summary_large_image');
        $this->Twitter[] = $this->setMetaTag('twitter:image', $url);
        if ($alt != null) {
            $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:image:alt', $alt);
            $this->Twitter[] = $this->setMetaTag('twitter:image:alt', $alt);
        }
    }

    /**
     * Set video.
     * @param string $url     Direct link to video
     * @param string $type    Open Graph video type
     * @param int $width      Video width in pixels
     * @param int $height     Video height in pixels
     */
    public function setVideo(string $url, string $type, int $width = null, int $height = null)
    {
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:video', $url);
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:video:secure_url', $url);
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:video:type', $type);
        $this->setTwitterCard('player');
        $this->Twitter[] = $this->setMetaTag('twitter:player', $url);
        if ($width != null && $height != null) {
            $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:video:width', $width);
            $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:video:height', $height);
            $this->Twitter[] = $this->setOpenGraphMetaTag('twitter:player:width', $width);
            $this->Twitter[] = $this->setOpenGraphMetaTag('twitter:player:height', $height);
        }
    }

    /**
     * Set audio.
     * @param string $url   Direct link to audio
     * @param string $type  Open Graph audio type
     */
    public function setAudio(string $url, string $type)
    {
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:audio', $url);
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:audio:secure_url', $url);
        $this->OpenGraph[] = $this->setOpenGraphMetaTag('og:audio:type', $type);
    }

    /**
     * Set the date and time of publication and moderation of the article.
     * @param string $published_time   Article publication date (ISO 8601)
     * @param string $modified_time    Article last modified date (ISO 8601)
     * @throws Exception 
     */
    public function setArticleDateTime(string $published_time, string $modified_time = null)
    {
        if (!preg_match($this->pattern['ISO8601'], $published_time) || ($modified_time != null && !preg_match($this->pattern['ISO8601'], $modified_time))) throw new \Exception('Date must be in ISO 8601 format');

        $this->OpenGraph[] = $this->setOpenGraphMetaTag('article:published_time', $published_time);
        if ($modified_time != null) $this->OpenGraph[] = $this->setOpenGraphMetaTag('article:modified_time', $modified_time);
    }

    /**
     * Set Twitter Card. 
     * About Twitter Cards: https://developer.twitter.com/en/docs/twitter-for-websites/cards/overview/abouts-cards
     * @param string $value   summary, summary_large_image, app, player
     * @throws Exception
     */
    public function setTwitterCard(string $value)
    {
        $card = ['summary', 'summary_large_image', 'app', 'player'];
        if (!in_array($value, $card)) throw new \Exception('Invalid twitter card value');

        if ($this->сountAttribute('twitter:card', $this->Twitter) > 0) {
            $this->Twitter = $this->replaceAttributeContent('twitter:card', $value, $this->Twitter);
        } else {
            $this->Twitter[] = $this->setMetaTag('twitter:card', $value);
        }
    }

    /**
     * Set a meta tag.
     * @param string $name
     * @param string $content
     * @return array
     */
    public function setMetaTag(string $name, string $content)
    {
        return $this->setArrayTag('meta', [['name' => 'name', 'content' => $name], ['name' => 'content', 'content' => $content]]);
    }

    /**
     * Set a OpenGraph meta tag.
     * @param string $name
     * @param string $content
     * @return array
     */
    protected function setOpenGraphMetaTag(string $name, string $content)
    {
        return $this->setArrayTag('meta', [['name' => 'property', 'content' => $name], ['name' => 'content', 'content' => $content]]);
    }

    /**
     * Set the Open Graph meta tag.
     * @param string $name
     * @param array $attribute
     * @param string $content
     * @return array
     */
    protected function setArrayTag(string $name, array $attribute = null, string $content = null)
    {
        return [
            'name' => $name,
            'attribute' => $attribute,
            'content' => $content
        ];
    }

    /**
     * @param array $arr
     * @param bool $SingleLine
     * @return string
     * @throws Exception
     */
    protected function arrayTagToStr(array $arr, bool $SingleLine)
    {
        $str = '';
        foreach ($arr as $tag) {
            $att = '';
            if ($tag['attribute'] != null) foreach ($tag['attribute'] as $attribute) $att .= " {$attribute['name']}=\"{$attribute['content']}\"";

            switch ($tag['name']) {
                case 'title':
                    if ($SingleLine === false) $str .= "\r\n";
                    $str .= "<{$tag['name']}{$att}>";
                    if ($tag['content'] != null) $str .= $tag['content'];
                    $str .= "</{$tag['name']}>";
                    break;
                case 'meta':
                    if ($SingleLine === false) $str .= "\r\n";
                    $str .= "<{$tag['name']}{$att} />";
                    break;
                default:
                    throw new \Exception('tag name not supported');
            }
        }
        return $SingleLine === false ? $str . "\r\n" : $str;
    }

    /**
     * @param string $value
     * @param array $arr
     * @return int
     */
    protected function сountAttribute(string $value, array $arr)
    {
        $count = 0;
        foreach ($arr as $tag) {
            foreach ($tag['attribute'] as $attribute) {
                if ($attribute['content'] === $value) $count++;
            }
        }
        return $count;
    }

    /**
     * @param string $search
     * @param string $replace
     * @param array $arr
     */
    protected function replaceAttributeContent(string $search, string $replace, array $arr)
    {
        foreach ($arr as $key => $tag) {
            if ($tag['attribute'][0]['content'] === $search) {
                $arr[$key]['attribute'][1]['content'] = $replace;
                return $arr;
            }
        }
        return null;
    }

    /**
     * Build tags.
     * @param bool $SingleLine   Single line
     * @return string            Tags
     */
    public function build(bool $SingleLine = true)
    {
        $str = '<!-- Powered by SeoForge https://github.com/mrgarest/seoforge -->';
        $str .= $this->arrayTagToStr($this->Tags, $SingleLine);
        $str .= $this->arrayTagToStr($this->OpenGraph, $SingleLine);
        $str .= $this->arrayTagToStr($this->Twitter, $SingleLine);
        $str .= $this->arrayTagToStr($this->Google, $SingleLine);
        $str .= '<!-- / Powered by SeoForge https://github.com/mrgarest/seoforge -->';
        return $str;
    }

    /**
     * Delete added data.
     */
    public function clear()
    {
        $this->Tags = [];
        $this->OpenGraph = [];
        $this->Twitter = [];
        $this->Google = [];
    }
}
