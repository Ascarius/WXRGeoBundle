<?php

namespace WXR\GeoBundle\Controller\Admin;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CityAdminController extends Controller
{
    public function listAction()
    {
        if (!$this->get('security.context')->isGranted('WXR_GEO_CITY_ADMIN_LIST')) {
            throw new AccessDeniedHttpException();
        }

        $cities = $this->getCityManager()->findAll();

        return $this->render('WXRGeoBundle:CityAdmin:list.html.twig', compact('cities'));
    }

    public function addAction()
    {
        if (!$this->get('security.context')->isGranted('WXR_GEO_CITY_ADMIN_ADD')) {
            throw new AccessDeniedHttpException();
        }

        $city = $this->getCityManager()->create();

        $handler = $this->get('wxr_geo.city.form.handler');
        $form = $handler->getForm();

        if ($handler->process($city)) {
            $this->get('session')->setFlash('success', 'wxr_geo.city.added');

            return $this->redirect($this->generateUrl('wxr_geo_city_admin_list'));
        }

        return $this->render('WXRGeoBundle:CityAdmin:add.html.twig', array(
            'city' => $city,
            'form' => $form->createView()
        ));
    }

    public function editAction($id)
    {
        if (!$this->get('security.context')->isGranted('WXR_GEO_CITY_ADMIN_EDIT')) {
            throw new AccessDeniedHttpException();
        }

        $city = $this->getCityManager()->find($id);

        if (null === $city) {
            throw $this->createNotFoundException('wxr_geo.city.not_found');
        }

        $handler = $this->get('wxr_geo.city.form.handler');
        $form = $handler->getForm();

        if ($handler->process($city)) {
            $this->get('session')->setFlash('success', 'wxr_geo.city.updated');

            return $this->redirect($this->generateUrl('wxr_geo_city_admin_list'));
        }

        return $this->render('WXRGeoBundle:CityAdmin:edit.html.twig', array(
            'city' => $city,
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id)
    {
        if (!$this->get('security.context')->isGranted('WXR_GEO_CITY_ADMIN_DELETE')) {
            throw new AccessDeniedHttpException();
        }

        $city = $this->getCityManager()->find($id);

        if (null === $city) {
            throw $this->createNotFoundException('wxr_geo.city.not_found');
        }

        if ($this->getRequest()->query->get('confirm')) {

            $this->getCityManager()->remove($city);
            $this->get('session')->setFlash('success', 'wxr_geo.city.deleted');

            return $this->redirect($this->generateUrl('wxr_geo_city_admin_list'));
        }

        return $this->render('WXRGeoBundle:CityAdmin:delete.html.twig', compact('country'));
    }

    /**
     * @return WXR\GeoBundle\Model\CityManagerInterface
     */
    protected function getCityManager()
    {
        return $this->get('wxr_geo.city.manager');
    }
}
