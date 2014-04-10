<?php

namespace NfqAkademija\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {

        return $this->render('NfqAkademijaAdsBundle:Default:index.html.twig');
    }
    public function profileAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        return $this->render('NfqAkademijaAdsBundle:Default:profile.html.twig', array('user' => $user ));
    }

}
