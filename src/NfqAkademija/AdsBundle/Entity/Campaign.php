<?php

namespace NfqAkademija\AdsBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * NfqAkademija\AdsBundle\Entity\Campaign
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="NfqAkademija\AdsBundle\Entity\CampaignRepository")
 * @UniqueEntity("googleId")
 */
class Campaign
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $userId
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var text $googleId
     * @ORM\Column(name="google_id", type="text")
     */
    private $googleId;

    /**
     * @var text $name
     *
     * @ORM\Column(name="name", type="text")
     */
    private $name;

    /**
     * @var float $budget
     *
     * @ORM\Column(name="budget", type="float")
     */
    private $budget;
    
    /**
     * @ORM\OneToMany(targetEntity="NfqAkademija\AdsBundle\Entity\Ad", mappedBy="campaignId")
     */
    private $ads;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Campaign
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set googleId
     *
     * @param text $googleId
     * @return Campaign
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;
        return $this;
    }

    /**
     * Get googleId
     *
     * @return text 
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * Set name
     *
     * @param text $name
     * @return Campaign
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return text 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set budget
     *
     * @param float $budget
     * @return Campaign
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
        return $this;
    }

    /**
     * Get budget
     *
     * @return float 
     */
    public function getBudget()
    {
        return $this->budget;
    }
    public function __construct()
    {
        $this->ads = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add ads
     *
     * @param NfqAkademija\AdsBundle\Entity\Ad $ads
     * @return Campaign
     */
    public function addAd(\NfqAkademija\AdsBundle\Entity\Ad $ads)
    {
        $this->ads[] = $ads;
        return $this;
    }

    /**
     * Remove ads
     *
     * @param NfqAkademija\AdsBundle\Entity\Ad $ads
     */
    public function removeAd(\NfqAkademija\AdsBundle\Entity\Ad $ads)
    {
        $this->ads->removeElement($ads);
    }

    /**
     * Get ads
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAds()
    {
        return $this->ads;
    }
}
