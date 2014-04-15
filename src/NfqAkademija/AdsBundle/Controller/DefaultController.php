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
	public function campaignAction()
    {

        $campaign = new Campaign();
        $form = $this->createForm(new CampaignType(), $campaign, array(
            'action' => $this->generateUrl('nfq_akademija_ads_create_campaign'),
        ));

        return $this->render(
            'NfqAkademijaAdsBundle:Default:campaign.html.twig',
            array('form' => $form->createView())
        );
    }
    
    public function createCampaignAction(Request $request)
	{
		$logger = $this->get('logger');
		$user = $this->getUser();
		$logger->debug('klase' . get_class($user) . ' user obj ' . print_r($user, true));
		
		$userId = $user->getId();

		$em = $this->getDoctrine()->getManager();

		$form = $this->createForm(new CampaignType(), new Campaign());

		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$campaign = $form->getData();
			$campaign->setUserId((int) $userId);
			$logger->debug('klaida1' . $userId);
		    $campaign->setGoogleId('dummyGoogleId');
		    
			$em->persist($campaign);
			$em->flush();

			return $this->redirect($this->generateUrl('nfq_akademija_ads_create_campaign'));
		}

		return $this->render(
			'NfqAkademijaAdsBundle:Default:campaign.html.twig',
			array('form' => $form->createView())
		);
	}
      
}
