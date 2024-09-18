<?php

namespace MrGarest\SeoForge;

use Illuminate\Support\Facades\Response;

class Sitemap
{
    public const CHANGEFREQ_ALWAYS = 'always';
    public const CHANGEFREQ_HOURLY = 'hourly';
    public const CHANGEFREQ_DAILY = 'daily';
    public const CHANGEFREQ_WEEKLY = 'weekly';
    public const CHANGEFREQ_MONTHLY = 'monthly';
    public const CHANGEFREQ_NEVER = 'never';

    protected $url = [];
    protected $pattern = ['ISO8601' => '/^(\d{4})-(\d{2})-(\d{2})T(\d{2})\:(\d{2})\:(\d{2})[+-](\d{2})\:(\d{2})$/'];
    protected $changefreq = [self::CHANGEFREQ_ALWAYS, self::CHANGEFREQ_HOURLY, self::CHANGEFREQ_DAILY, self::CHANGEFREQ_WEEKLY, self::CHANGEFREQ_MONTHLY, self::CHANGEFREQ_NEVER];

    /**
     * @param string $url            Page links (https://www.example.com/file1.html)
     * @param string $lastmod        Date the page was last modified (ISO 8601)
     * @param string $changefreq     Page refresh rate (always, hourly, daily, weekly, monthly, yearly, never)
     * @param float $priority        Page priority (0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1)
     * @throws Exception
     */
    public function addItem(string $url, string $lastmod, string $changefreq = null, float $priority = null)
    {
        if (!preg_match($this->pattern['ISO8601'], $lastmod)) throw new \Exception('Date must be in ISO 8601 format');
        if ($changefreq != null && !in_array($changefreq, $this->changefreq)) throw new \Exception('The page refresh rate value is incorrect.');
        if ($priority != null && ($priority < 0 || $priority > 1)) throw new \Exception('Priority must be in the format (0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1)');
        elseif ($priority != null && ($priority == 0 || $priority == 1)) $priority = $priority . '.0';
        $this->url[] = [
            'loc' => $url,
            'lastmod' => $lastmod,
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    protected function arrayUrlToStr(array $arr)
    {
        $str = '';
        foreach ($arr as $url) {
            $str .= '<url>';
            $str .= "<loc>{$url['loc']}</loc>";
            $str .= "<lastmod>{$url['lastmod']}</lastmod>";
            if ($url['changefreq'] != null) $str .= "<changefreq>{$url['changefreq']}</changefreq>";
            if ($url['priority'] != null) $str .= "<priority>{$url['priority']}</priority>";
            $str .= '</url>';
        }
        return $str;
    }

    /**
     * Build sitemap.
     * @param bool $xmlDocument   Displaying a page as an XML document
     * @return string             Sitemap
     */
    public function build($xmlDocument = true)
    {

        $str = '<?xml version="1.0" encoding="UTF-8"?>';
        $str .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" seoforge="https://github.com/mrgarest/seoforge">';
        $str .= $this->arrayUrlToStr($this->url);
        $str .= '</urlset>';
        return $xmlDocument ? Response::make($str, 200, ['Content-Type' => 'application/xml']) : $str;
    }
    /**
     * Delete added data.
     */
    public function clear()
    {
        $this->url = [];
    }
}
