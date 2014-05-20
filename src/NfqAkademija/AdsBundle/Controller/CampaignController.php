<?php

namespace NfqAkademija\AdsBundle\Controller;

use NfqAkademija\AdsBundle\Form\Type\CampaignType;
use NfqAkademija\AdsBundle\Entity\Ad;
use NfqAkademija\AdsBundle\Entity\Campaign;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;
use AdWordsUser;

class CampaignController extends Controller
{
    public function listAllAction()
    { 
		$userId = $this->getUser()->getId();
		$user = $this->getDoctrine()->getRepository('NfqAkademijaAdsBundle:User')->findOneById($userId);
		//$logger = $this->get('logger');
		$userGoogleId = $user->getAdWordsUserId();
		$campaignsDb = $user->getCampaigns();
        //$campaigns = $this->getDoctrine()->getRepository('NfqAkademijaAdsBundle:Campaign')->findAllByUser($userGoogleId);
        
        $googleAdsUser = new AdWordsUser();
        $googleAdsUser->SetClientCustomerId($userGoogleId);
        
        // Get the service, which loads the required classes.
        $campaignService = $googleAdsUser->GetService('CampaignService');

        // Create selector.
        $selector = new \Selector();
        $selector->fields = array('Id', 'Name');
        $selector->ordering[] = new \OrderBy('Name', 'ASCENDING');
        
        // Make the get request.
        $campaigns = $campaignService->get($selector);
        $campaignArray = array();
        $i = 0;
        foreach ($campaigns->entries as $campaign) {
			if (strstr($campaign->name, "Deleted") == FALSE){
				$campaignArray[$i]['name']=$campaign->name;
				$campaignArray[$i]['id']=$campaign->id;
				$campaignArray[$i]['budget']=$campaign->budget;
				$i++;
			}
		}
		
        return $this->render('NfqAkademijaAdsBundle:Default:campaign.html.twig', array('campaigns' => $campaignArray));
    }
     public function campaignAction(Request $request)
    {

        $default = array('name' => 'Campaign name', 'budget' => 0);
        $form = $this->createFormBuilder($default)
            ->add('name', 'text')
            ->add('budget', 'number')
            ->add('save', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
					$userId = $this->getUser()->getId();
					$user = $this->getDoctrine()->getRepository('NfqAkademijaAdsBundle:User')->findOneById($userId);
					$userGoogleId = $user->getAdWordsUserId();
					$googleAdsUser = new AdWordsUser();
					$googleAdsUser->SetClientCustomerId($userGoogleId);
					
					// Get the BudgetService, which loads the required classes.
					$budgetService = $googleAdsUser->GetService('BudgetService');

					// Create the shared budget (required).
					$budget = new \Budget();
					$budget->name = 'NFQ #' . uniqid();
					$budget->period = 'DAILY';
					$money = $form->get('budget')->getData() * 1000000;
					$budget->amount = new \Money($money);
					$budget->deliveryMethod = 'STANDARD';

					$operations = array();

					// Create operation.
					$operation = new \BudgetOperation();
					$operation->operand = $budget;
					$operation->operator = 'ADD';
					$operations[] = $operation;

					// Make the mutate request.
					$result = $budgetService->mutate($operations);
					$budget = $result->value[0];

					// Get the CampaignService, which loads the required classes.
					$campaignService = $googleAdsUser->GetService('CampaignService');

					$numCampaigns = 2;
					$operations = array();
					for ($i = 0; $i < $numCampaigns; $i++) {
						// Create campaign.
						$campaign = new \Campaign();
						$campaign->name = $form->get('name')->getData() .' #' . uniqid();
						$campaign->advertisingChannelType = 'SEARCH';

						// Set shared budget (required).
						$campaign->budget = new \Budget();
						$campaign->budget->budgetId = $budget->budgetId;

						// Set bidding strategy (required).
						$biddingStrategyConfiguration = new \BiddingStrategyConfiguration();
						$biddingStrategyConfiguration->biddingStrategyType = 'MANUAL_CPC';

						// You can optionally provide a bidding scheme in place of the type.
						$biddingScheme = new \ManualCpcBiddingScheme();
						$biddingScheme->enhancedCpcEnabled = FALSE;
						$biddingStrategyConfiguration->biddingScheme = $biddingScheme;

						$campaign->biddingStrategyConfiguration = $biddingStrategyConfiguration;

						// Set keyword matching setting (required).
						$keywordMatchSetting = new \KeywordMatchSetting();
						$keywordMatchSetting->optIn = TRUE;
						$campaign->settings[] = $keywordMatchSetting;

						// Set network targeting (optional).
						$networkSetting = new \NetworkSetting();
						$networkSetting->targetGoogleSearch = TRUE;
						$networkSetting->targetSearchNetwork = TRUE;
						$networkSetting->targetContentNetwork = TRUE;
						$campaign->networkSetting = $networkSetting;

						// Set additional settings (optional).
						$campaign->status = 'ACTIVE';
						$campaign->endDate = date('Ymd', strtotime('+1 month'));
						$campaign->adServingOptimizationStatus = 'ROTATE';

						// Set frequency cap (optional).
						$frequencyCap = new \FrequencyCap();
						$frequencyCap->impressions = 5;
						$frequencyCap->timeUnit = 'DAY';
						$frequencyCap->level = 'ADGROUP';
						$campaign->frequencyCap = $frequencyCap;

						// Set advanced location targeting settings (optional).
						$geoTargetTypeSetting = new \GeoTargetTypeSetting();
						$geoTargetTypeSetting->positiveGeoTargetType = 'DONT_CARE';
						$geoTargetTypeSetting->negativeGeoTargetType = 'DONT_CARE';
						$campaign->settings[] = $geoTargetTypeSetting;

						// Create operation.
						$operation = new \CampaignOperation();
						$operation->operand = $campaign;
						$operation->operator = 'ADD';
						$operations[] = $operation;
					}

					// Make the mutate request.
					$result = $campaignService->mutate($operations);
					
					return $this->redirect($this->generateUrl('nfq_akademija_ads_listAll'));
		}
		
        return $this->render(
            'NfqAkademijaAdsBundle:Default:campaigncreate.html.twig',
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
	public function removeAction($campaignId)
	{
		$userId = $this->getUser()->getId();
		$user = $this->getDoctrine()->getRepository('NfqAkademijaAdsBundle:User')->findOneById($userId);
		$userGoogleId = $user->getAdWordsUserId();
		$googleAdsUser = new AdWordsUser();
		$googleAdsUser->SetClientCustomerId($userGoogleId);
		
        // Get the service, which loads the required classes.
        $campaignService = $googleAdsUser->GetService('CampaignService');

        // Create campaign with DELETED status.
        $campaign = new \Campaign();
        $campaign->id = $campaignId;
        $campaign->status = 'DELETED';
        // Rename the campaign as you delete it, to avoid future name conflicts.
        $campaign->name = 'Deleted ' . date('Ymd his');

        // Create operations.
        $operation = new \CampaignOperation();
        $operation->operand = $campaign;
        $operation->operator = 'SET';

        $operations = array($operation);

        // Make the mutate request.
        $result = $campaignService->mutate($operations);

        return $this->redirect($this->generateUrl('nfq_akademija_ads_listAll'));
   
    }
}
