<?php
// Site Address extension for Bolt

namespace Bolt\Extension\DanielKulbe\SiteAddress;

use Bolt\Application;
use Bolt\BaseExtension;
use Symfony\Component\Intl\Intl as Intl;

class Extension extends BaseExtension
{
    /**
     * Extension name
     *
     * @var string
     */
    const NAME = 'Site Address';


    /**
     * Add Twig settings in 'frontend' environment
     *
     * @return void
     */
    public function initialize()
    {
        // Frontend
        if ($this->app['config']->getWhichEnd() == 'frontend')
        {
            // Add Twig template path
            $this->app['twig.loader.filesystem']->addPath(dirname(__FILE__) . '/assets');

            // Add Twig functions
            $this->addTwigFunction('address', 'siteAddress');

            // Add Twig filter
            $this->addTwigFilter('country', 'countryName');
        }
    }


    /**
     * Get the extension's human readable name
     *
     * @return string
     */
    public function getName()
    {
        return Extension::NAME;
    }


    /**
     * Set the defaults for configuration parameters
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return array(
            'name' => "John Bolt",
            'address' => array(
                "street" => "Collstreet 23",
                "zip" => "00000",
                "city" => "Example Vill",
                "country" => "US"
            ),
            'phone' => "+49 (0) 00.00 00 00 00",
            'mobile' => "+49 (0) 000.00 00 00",
            'mail' => "me@example.com",
            'template' => "address.twig"
        );
    }


    /**
     * Returns the human readable country name from a given country code.
     *
     * @return string
     */
    public function countryName($countryCode)
    {
        return Intl::getRegionBundle()->getCountryName($countryCode);
    }


    /**
     * Render the template
     *
     * @return string
     */
    public function siteAddress ()
    {
        $twigValues = $this->config;
        unset($twigValues['template']);

        $str = $this->app['render']->render($this->config['template'], $twigValues);

        return new \Twig_Markup($str, 'UTF-8');
    }
}
