<?php

namespace NfqAkademija\AdsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;

class DefaultController extends Controller
{
    public function indexAction()
    {

        return $this->render('NfqAkademijaAdsBundle:Default:index.html.twig');
    }
    public function profileAction()
    {
        $realname = $session->get('realname');
        return $this->render('NfqAkademijaAdsBundle:Default:profile.html.twig', array('session_realname' => $realname ));
    }

}
