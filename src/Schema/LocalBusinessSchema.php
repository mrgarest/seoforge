<?php

namespace MrGarest\SeoForge\Schema;

class LocalBusinessSchema extends \MrGarest\SeoForge\Schema
{
    protected $arrayDayOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunnday'];

    /**
     * @param string $type     Business type
     * @throws Exception 
     */
    public function __construct($type)
    {
        parent::__construct($type);
    }

    /**
     * Set company name
     * @param string $str     Сompany name
     */
    public function setName(string $str)
    {
        $this->JsonLD['name'] = $str;
    }

    /**
     * Set a link to the company page
     * @param string $url     Link to company page
     */
    public function setUrl(string $url = null)
    {
        if ($url == null) {
            $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $url = explode('?', $url);
            $url = $url[0];
            $url = str_replace('.php', '', $url);
        }
        $this->JsonLD['url'] = $url;
    }

    /**
     * Add image.
     * @param string $url     Direct link to image
     */
    public function addImage(string $url)
    {
        $this->JsonLD['image'][] = $url;
    }

    /**
     * Set company phone number.
     * @param string $str    Company phone number
     */
    public function setTelephone(string $str)
    {
        $this->JsonLD['telephone'] = $str;
    }

    /**
     * Set company address.
     * @param string $country
     * @param string $region
     * @param string $city
     * @param string $street
     * @param string $postCode
     * @param float $latitude
     * @param float $longitude
     */
    public function setAddress(string $country, string $region, string $city, string $street, string $postCode, float $latitude = null, float $longitude = null)
    {
        $this->JsonLD['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $street,
            'addressLocality' => $city,
            'addressRegion' => $region,
            'postalCode' => $postCode,
            'addressCountry' => $country
        ];

        if ($latitude != null && $longitude != null) $this->JsonLD['geo'] = [
            '@type' => 'GeoCoordinates',
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
    }

    /**
     * Set company opening hours.
     * @param array $dayOfWeek
     * @param string $timeOpens
     * @param string $timeCloses
     * @throws Exception 
     */
    public function addOpeningHours(array $dayOfWeek, string $timeOpens, string $timeCloses)
    {
        foreach ($dayOfWeek as $value) if (!in_array($value, $this->arrayDayOfWeek)) throw new \Exception('Incorrect weekday');
        if (count($dayOfWeek) == 1) $dayOfWeek = $dayOfWeek[0];
        $this->JsonLD['openingHoursSpecification'][] = [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => $dayOfWeek,
            'opens' => $timeOpens,
            'closes' => $timeCloses
        ];
    }
}
