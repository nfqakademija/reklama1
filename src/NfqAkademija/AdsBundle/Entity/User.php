<?php
namespace NfqAkademija\AdsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser;
/**
 * NfqAkademija\AdsBundle\Entiy\User
 *
 * @ORM\Table(name="User")
 * @ORM\Entity(repositoryClass="NfqAkademija\AdsBundle\Entity\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User extends OAuthUser implements EquatableInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     *
     * @ORM\Column(name="google_id", type="string", length=255, unique=true, nullable=true)
     */
    protected $googleId;
    /**
     * @ORM\ManyToMany(targetEntity="NfqAkademija\AdsBundle\Entity\Role", inversedBy="users")
     *
     */
    protected $roles;
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    protected $username;
    /**
     * @var string
     *
     * @ORM\Column(name="realname", type="string", length=255, nullable=true)
     */
    protected $realname;
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=32)
     */
    protected $salt;
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    protected $email;
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;
     /**
     * @ORM\Column(name="adwordsuserid", type="string", length=255)
     */
    protected $adWordsUserId;
    /**
     * @ORM\OneToMany(targetEntity="NfqAkademija\AdsBundle\Entity\Campaign", mappedBy="userId")
     */
    protected $campaigns;
    
    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }
    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->campaigns = new ArrayCollection();
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
    }
    /**
     * @param string $realname
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;
    }
    /**
     * @return string
     */
    public function getRealname()
    {
        return $this->realname;
    }
    /**
     * @param string $googleId
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;
    }
    /**
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }
    public function getRoles()
    {
        return $this->roles->toArray();
    }
    public function getRolesAsCollection()
    {
        return $this->roles;
    }
    public function setRoles($role)
    {
        return $this->roles->add($role);
    }
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }
    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * @inheritDoc
     */
    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            ) = unserialize($serialized);
    }
    public function isEqualTo(UserInterface $user)
    {
        if ((int)$this->getId() === $user->getId()) {
            return true;
        }
        return false;
    }

    /**
     * Add roles
     *
     * @param \NfqAkademija\AdsBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\NfqAkademija\AdsBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \NfqAkademija\AdsBundle\Entity\Role $roles
     */
    public function removeRole(\NfqAkademija\AdsBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Set adWordsUserId
     *
     * @param string $adWordsUserId
     * @return User
     */
    public function setAdWordsUserId($adWordsUserId)
    {
        $this->adWordsUserId = $adWordsUserId;
        return $this;
    }

    /**
     * Get adWordsUserId
     *
     * @return string 
     */
    public function getAdWordsUserId()
    {
        return $this->adWordsUserId;
    }

    /**
     * Add campaigns
     *
     * @param NfqAkademija\AdsBundle\Entity\Campaign $campaigns
     * @return User
     */
    public function addCampaign(\NfqAkademija\AdsBundle\Entity\Campaign $campaigns)
    {
        $this->campaigns[] = $campaigns;
        return $this;
    }

    /**
     * Remove campaigns
     *
     * @param NfqAkademija\AdsBundle\Entity\Campaign $campaigns
     */
    public function removeCampaign(\NfqAkademija\AdsBundle\Entity\Campaign $campaigns)
    {
        $this->campaigns->removeElement($campaigns);
    }

    /**
     * Get campaigns
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCampaigns()
    {
        return $this->campaigns;
    }
}
