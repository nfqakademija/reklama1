<?php

namespace NfqAkademija\AdsBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * NfqAkademija\AdsBundle\Entity\Ad
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="NfqAkademija\AdsBundle\Entity\AdRepository")
 * @UniqueEntity("googleId")
 */
class Ad
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
     * @var text $campaignId
     *
     * @ORM\ManyToOne(targetEntity="NfqAkademija\AdsBundle\Entity\Campaign", inversedBy="ads")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id")
     */
    private $campaignId;

    /**
     * @var text $name
     * 
     * @ORM\Column(name="name", type="text")
     */
    private $name;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var text $imageUrl
     *
     * @ORM\Column(name="image_url", type="text")
     */
    private $imageUrl;

    /**
     * @var text $url
     *
     * @ORM\Column(name="url", type="text")
     */
    private $url;

    /**
     * @var text $googleId
     *
     * @ORM\Column(name="google_id", type="text")
     */
    private $googleId;

    /**
     * @var text $keyword
     *
     * @ORM\Column(name="keyword", type="text")
     */
    private $keyword;


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
     * Set campaignId
     *
     * @param text $campaignId
     * @return Ad
     */
    public function setCampaignId($campaignId)
    {
        $this->campaignId = $campaignId;
        return $this;
    }

    /**
     * Get campaignId
     *
     * @return text 
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }

    /**
     * Set name
     *
     * @param text $name
     * @return Ad
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
     * Set description
     *
     * @param text $description
     * @return Ad
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imageUrl
     *
     * @param text $imageUrl
     * @return Ad
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return text 
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set url
     *
     * @param text $url
     * @return Ad
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return text 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set googleId
     *
     * @param text $googleId
     * @return Ad
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
     * Set keyword
     *
     * @param text $keyword
     * @return Ad
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * Get keyword
     *
     * @return text 
     */
    public function getKeyword()
    {
        return $this->keyword;
    }
}
