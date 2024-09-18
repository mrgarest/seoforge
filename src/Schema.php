<?php

namespace MrGarest\SeoForge;

class Schema
{
    protected $JsonLD = null;

    /**
     * @param string $type     Article type
     * @throws Exception 
     */
    public function __construct(string $type)
    {
        $this->JsonLD = [
            '@context' => 'https://schema.org',
            '@type' => $type
        ];
    }

    public function build(bool $script = true)
    {
        $json = json_encode($this->JsonLD, JSON_UNESCAPED_UNICODE);
        return $script === true ? '<script type="application/ld+json" class="seoforge-schema">' . $json . '</script>' : $json;
    }
}
