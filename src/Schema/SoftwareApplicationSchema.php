<?php

namespace MrGarest\SeoForge\Schema;

class SoftwareApplicationSchema extends \MrGarest\SeoForge\Schema
{
    public const CATEGORY_GAME = 'GameApplication';
    public const CATEGORY_SOCIAL_NETWORKING = 'SocialNetworkingApplication';
    public const CATEGORY_TRAVEL = 'TravelApplication';
    public const CATEGORY_SHOPPING = 'ShoppingApplication';
    public const CATEGORY_SPORT = 'SportsApplication';
    public const CATEGORY_LIFESTYLE = 'LifestyleApplication';
    public const CATEGORY_BUSINESS = 'BusinessApplication';
    public const CATEGORY_DESIGN = 'DesignApplication';
    public const CATEGORY_DEVELOPER = 'DeveloperApplication';
    public const CATEGORY_DRIVER = 'DriverApplication';
    public const CATEGORY_EDUCATION = 'EducationalApplication';
    public const CATEGORY_HEALTH = 'HealthApplication';
    public const CATEGORY_FINANCE = 'FinanceApplication';
    public const CATEGORY_SECURITY = 'SecurityApplication';
    public const CATEGORY_BROWSER = 'BrowserApplication';
    public const CATEGORY_COMMUNICATION = 'CommunicationApplication';
    public const CATEGORY_DESKTOP_ENHANCEMENT = 'DesktopEnhancementApplication';
    public const CATEGORY_ENTERTAINMENT = 'EntertainmentApplication';
    public const CATEGORY_MULTIMEDIA = 'MultimediaApplication';
    public const CATEGORY_HOME = 'HomeApplication';
    public const CATEGORY_UTILITIES = 'UtilitiesApplication';
    public const CATEGORY_REFERENCE = 'ReferenceApplication';

    /**
     * @param string $type     Business type
     * @throws Exception 
     */
    public function __construct()
    {
        parent::__construct('SoftwareApplication');
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
     * Set the operating system in which the application works
     * @param string $str     Operating system name
     */
    public function setOS(string $str)
    {
        $this->JsonLD['operatingSystem'] = $str;
    }

    /**
     * Set application category
     * @param string $str     Application category
     */
    public function setCategory(string $str)
    {
        $this->JsonLD['applicationCategory'] = $str;
    }

    /**
     * Set the page on which there is a link to download the application
     * @param string $url     link
     */
    public function setDownloadPage(string $url)
    {
        $this->JsonLD['installUrl'] = $url;
    }

    /**
     * Set the link to download the application installation file
     * @param string $url     link
     */
    public function setDownloadFile(string $url)
    {
        $this->JsonLD['downloadUrl'] = $url;
    }

    /**
     * Set the application rating
     * @param string $rating     Rating (0.0-5.0)
     * @param int $count         Number of people who left a rating
     */
    public function setRating(string $rating, int $count)
    {
        $this->JsonLD['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => "$rating",
            'ratingCount' => "$count",
        ];
    }

    /**
     * Set the cost of the application
     * @param float $price       Cost of the application (if the application is free, set the cost to 0)
     * @param string $currency   Currency (with a free application, the currency is not specified)
     */
    public function setCost(float $price, string $currency = null)
    {
        $this->JsonLD['offers'] = [
            '@type' => 'Offer',
            'price' => "$price",
        ];
        if ($currency != null && $price > 0) {
            $this->JsonLD['offers']['priceCurrency'] = $currency;
        } elseif ($currency == null && $price > 0) {
            throw new \Exception('The currency of the application cost is not specified');
        }
    }
}
