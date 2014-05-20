<?php

namespace NfqAkademija\AdsBundle\Controller;

use NfqAkademija\AdsBundle\Form\Type\CampaignType;
use NfqAkademija\AdsBundle\Entity\Ad;
use NfqAkademija\AdsBundle\Entity\Campaign;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends Controller
{
    public function indexAction()
    {

        return $this->render('NfqAkademijaAdsBundle:Default:index.html.twig');
    }
    public function profileAction()
    {
        $user = $this->getUser();
        return $this->render('NfqAkademijaAdsBundle:Default:profile.html.twig', array('user' => $user ));
    }
}
