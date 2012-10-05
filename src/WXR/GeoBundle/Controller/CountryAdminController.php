<?php

namespace WXR\GeoBundle\Controller\Admin;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CountryAdminController extends Controller
{
    public function listAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_WXR_GEO_COUNTRY_ADMIN_LIST')) {
            throw new AccessDeniedHttpException();
        }

        $countries = $this->getCountryManager()->findAll();

        return $this->render('WXRGeoBundle:Admin/Country:list.html.twig', compact('countries'));
    }

    public function addAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_WXR_GEO_COUNTRY_ADMIN_ADD')) {
            throw new AccessDeniedHttpException();
        }

        $country = $this->getCountryManager()->create();

        $handler = $this->get('wxr_geo.country.form.handler');
        $form = $handler->getForm();

        if ($handler->process($country)) {
            $this->get('session')->setFlash('success', 'wxr_geo.country.added');

            return $this->redirect($this->generateUrl('wxr_geo_country_admin_list'));
        }

        return $this->render('WXRGeoBundle:Admin/Country:add.html.twig', array(
            'country' => $country,
            'form' => $form->createView()
        ));
    }

    public function editAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_WXR_GEO_COUNTRY_ADMIN_EDIT')) {
            throw new AccessDeniedHttpException();
        }

        $country = $this->getCountryManager()->find($id);

        if (null === $country) {
            throw $this->createNotFoundException('wxr_geo.country.not_found');
        }

        $handler = $this->get('wxr_geo.country.form.handler');
        $form = $handler->getForm();

        if ($handler->process($country)) {
            $this->get('session')->setFlash('success', 'wxr_geo.country.updated');

            return $this->redirect($this->generateUrl('wxr_geo_country_admin_list'));
        }

        return $this->render('WXRGeoBundle:Admin/Country:edit.html.twig', array(
            'country' => $country,
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_WXR_GEO_COUNTRY_ADMIN_DELETE')) {
            throw new AccessDeniedHttpException();
        }

        $country = $this->getCountryManager()->find($id);

        if (null === $country) {
            throw $this->createNotFoundException('wxr_geo.country.not_found');
        }

        if ($this->getRequest()->query->get('confirm')) {

            $this->getCountryManager()->remove($country);
            $this->get('session')->setFlash('success', 'wxr_geo.country.deleted');

            return $this->redirect($this->generateUrl('wxr_geo_country_admin_list'));
        }

        return $this->render('WXRGeoBundle:CountryAdmin:delete.html.twig', compact('country'));
    }

    /**
     * @return WXR\GeoBundle\Model\CountryManagerInterface
     */
    protected function getCountryManager()
    {
        return $this->get('wxr_geo.country.manager');
    }
}
