<?php

namespace WXR\GeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CountriesController extends Controller
{
    public function listAction()
    {
        $request = $this->getRequest();
        $format = $request->get('_format');

        $countries = $this->getCountryManager()->findAll();

        $serializer = $this->get('serializer');
        $data = $serializer->serialize($countries, $format);
        return new Response($data);
    }

    /**
     * @return WXR\GeoBundle\Model\CountryManagerInterface
     */
    protected function getCountryManager()
    {
        return $this->get('wxr_geo.country.manager');
    }
}
