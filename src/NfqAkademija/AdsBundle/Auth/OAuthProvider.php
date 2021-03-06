<?php
namespace NfqAkademija\AdsBundle\Auth;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use NfqAkademija\AdsBundle\Entity\User;
use NfqAkademija\AdsBundle\Entity\Role;
use AdWordsUser;


class OAuthProvider extends OAuthUserProvider
{
    protected $session, $doctrine, $admins;
    public function __construct($session, $doctrine, $service_container)
    {
        $this->session = $session;
        $this->doctrine = $doctrine;
        $this->container = $service_container;
    }
    public function createAccount(AdWordsUser $AdUser) 
    {
	    // Get the service, which loads the required classes.
	    $managedCustomerService =
	   	    $AdUser->GetService('ManagedCustomerService');

	    // Create customer.
	    $customer = new \ManagedCustomer();
	    $customer->name = 'Account #' . uniqid();
	    $customer->currencyCode = 'EUR';
	    $customer->dateTimeZone = 'Europe/Vilnius';

	    // Create operation.
	    $operation = new \ManagedCustomerOperation();
	    $operation->operator = 'ADD';
	    $operation->operand = $customer;

	    $operations = array($operation);

	    // Make the mutate request.
	    $result = $managedCustomerService->mutate($operations);

	    // Display result.
	    $customer = $result->value[0];
	    return $customer->customerId;
	}
    public function loadUserByUsername($username)
    {
        $qb = $this->doctrine->getManager()->createQueryBuilder();
        $qb->select('u')
            ->from('NfqAkademijaAdsBundle:User', 'u')
            ->where('u.googleId = :gid')
            ->setParameter('gid', $username)
            ->setMaxResults(1);
        $result = $qb->getQuery()->getResult();
        if (count($result)) {
            return $result[0];
        } else {
            return new User();
        }
    }
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        //Data from Google response
        $google_id = $response->getUsername(); /* An ID like: 112259658235204980084 */
        $email = $response->getEmail();
        $realname = $response->getRealName();
        $avatar = $response->getProfilePicture();
        //set data in session
        $this->session->set('email', $email);
        $this->session->set('realname', $realname);
        $this->session->set('avatar', $avatar);
        //Check if this Google user already exists in our app DB
        $qb = $this->doctrine->getManager()->createQueryBuilder();
        $qb->select('u')
            ->from('NfqAkademijaAdsBundle:User', 'u')
            ->where('u.googleId = :gid')
            ->setParameter('gid', $google_id)
            ->setMaxResults(1);
        $result = $qb->getQuery()->getResult();
        //add to database if doesn't exists
        if (!count($result)) {
            $user = new User();
            $user->setUsername($google_id);
            $user->setRealname($realname);
            $user->setEmail($email);
            $user->setGoogleId($google_id);
            $googleAdsUser = new AdWordsUser();
            $user->setAdWordsUserId($this->createAccount($googleAdsUser));
            $em = $this->doctrine->getManager();
            $roleRep = $em->getRepository('NfqAkademijaAdsBundle:Role');
            $user->addRole($roleRep->findOneBy(array('role' => 'ROLE_USER')));
            //Set some wild random pass since its irrelevant, this is Google login
            $factory = $this->container->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword(md5(uniqid()), $user->getSalt());
            $user->setPassword($password);
            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();
        } else {
            $user = $result[0]; /* return User */
        }
        //set id
        $this->session->set('id', $user->getId());
        return $this->loadUserByUsername($response->getUsername());
    }
    public function supportsClass($class)
    {
        return $class === 'NfqAkademija\\AdsBundle\\Entity\\User';
    }
}
