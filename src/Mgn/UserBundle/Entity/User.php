<?php
// src/Mgn/UserBundle/Entity/User.php
 
namespace Mgn\UserBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
 
/**
 *
 * @author Sylvain 'KnoKosS' Meunier <knokoss@gmail.com>
 *
 * @ORM\Table(name="mgn_users")
 * @ORM\Entity(repositoryClass="Mgn\UserBundle\Entity\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements AdvancedUserInterface, \Serializable
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
 
  /**
   * @ORM\Column(name="username", type="string", length=255, unique=true)
   * @Assert\NotBlank()
   * @Assert\Length(
   *      min = "2",
   *      max = "20"
   * )
   */
  private $username;
 
  /**
   * @Gedmo\Slug(fields={"username"})
   * @ORM\Column(name="usernameCanonical", type="string", length=255, unique=true)
   */
  private $usernameCanonical;
 
  /**
   * @ORM\Column(name="password", type="string", length=255)
   * @Assert\NotBlank()
   * @Assert\Length(
   *      min = "6"
   * )
   */
  private $password;

  /**
   * @ORM\Column(type="string", length=255, unique=true)
   * @Assert\NotBlank()
   * @Assert\Email()
   */
  private $email;
    
    /**
     * @ORM\Column(name="avatar")
     * @var string
     */
    private $avatar;
    
    /**
     * @ORM\Column(name="avatarPath", nullable=true)
     * @var string
     */
    private $avatarPath;
    
    /**
     * @Assert\Image(maxSize="500k")
     */
    private $avatarFile;
 
  /**
   * @ORM\Column(name="salt", type="string", length=255)
   */
  private $salt;

  /**
   * @ORM\Column(name="is_active", type="boolean")
   */
  private $isActive;
 
  /**
   * @ORM\Column(name="roles", type="array")
   */
  private $roles;

  /**
   * @ORM\Column(name="registered", type="datetime")
   */
  private $registered;

  /**
   * @ORM\Column(name="lastLogin", type="datetime", nullable=true)
   */
  private $lastLogin;

  /**
   * @ORM\Column(name="confirmationToken", type="string", length=255, nullable=true)
   */
  private $confirmationToken;

  /**
   * @ORM\Column(name="passwordRequestedAt", type="datetime", nullable=true)
   */
  private $passwordRequestedAt;

  /**
   * @ORM\Column(name="locked", type="boolean")
   */
  private $locked;

  /**
   * @ORM\Column(name="lockedFor", type="boolean", nullable=true)
   */
  private $lockedFor;

  /**
   * @ORM\OneToMany(targetEntity="Mgn\UserBundle\Entity\UserToGroup", mappedBy="user", orphanRemoval=true)
   */
  protected $groups;

  /**
   * @var string
   * @ORM\Column(name="theme", type="string", nullable=false)
   */
  private $theme;

  /**
   * @ORM\Column(name="themeDate", type="datetime", nullable=false)
   */
  private $themeDate;

  /**
   * @var string $firstName
   *
   * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
   */
  private $firstName;

  /**
   * @var string $lastName
   *
   * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
   */
  private $lastName;

  /**
   * @ORM\Column(name="birthday", type="date", nullable=true)
   */
  private $birthday;

  /**
   * @ORM\Column(name="signature", type="text", nullable=true)
   */
  private $signature;

  /**
   * @var string $twitter
   *
   * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
   */
  private $twitter;

  /**
   * @var string $facebook
   *
   * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
   */
  private $facebook;

  /**
   * @var string $googleplus
   *
   * @ORM\Column(name="googleplus", type="string", length=255, nullable=true)
   */
  private $googleplus;

  /**
   * @var string $steam
   *
   * @ORM\Column(name="steam", type="string", length=255, nullable=true)
   */
  private $steam;
    
    /**
     * @ORM\Column(name="countArticle", type="integer")
     */
    private $countArticle;
    
    /**
     * @ORM\Column(name="countMessage", type="integer")
     */
    private $countMessage;

    /**
     * @ORM\Column(name="countTopic", type="integer")
     */
    private $countTopic;

    /**
     * @ORM\Column(name="countPost", type="integer")
     */
    private $countPost;

 
  public function __construct()
  {
    $this->roles = array();
    $this->isActive = false;
    $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    $this->locked = false;
    $this->registered = new \Datetime();
    $this->lastLogin = null;
    $this->addRole('ROLE_USER');
    //$this->groups = new ArrayCollection();
    $this->firstName = null;
    $this->lastName = null;
    $this->birthday = null;
    $this->signature = null;
    $this->twitter = null;
    $this->facebook = null;
    $this->googleplus = null;
    $this->steam = null;
    $this->countArticle = 0;
    $this->countMessage = 0;
    $this->countTopic = 0;
    $this->countPost = 0;
    $this->avatar = 'default';
  }
 
  public function getId()
  {
    return $this->id;
  }
 
  public function setUsername($username)
  {
    $this->username = $username;
    return $this;
  }
 
  public function getUsername()
  {
    return $this->username;
  }
 
  public function setPassword($password)
  {
    $this->password = $password;
    return $this;
  }
 
  public function getPassword()
  {
    return $this->password;
  }

  public function setEmail($email)
  {
      $this->email = $email;
  
      return $this;
  }

  public function getEmail()
  {
      return $this->email;
  }
 
  public function setSalt($salt)
  {
    $this->salt = $salt;
    return $this;
  }
 
  public function getSalt()
  {
    return $this->salt;
  }
 
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

  public function setIsActive($isActive)
  {
      $this->isActive = $isActive;
  
      return $this;
  }

  public function getIsActive()
  {
      return $this->isActive;
  }

  public function isEqualTo(UserInterface $user)
  {
      return $this->username === $user->getUsername();
  }

    /**
     * Set usernameCanonical
     *
     * @param string $usernameCanonical
     * @return User
     */
    public function setUsernameCanonical($usernameCanonical)
    {
        $this->usernameCanonical = $usernameCanonical;
    
        return $this;
    }

    /**
     * Get usernameCanonical
     *
     * @return string 
     */
    public function getUsernameCanonical()
    {
        return $this->usernameCanonical;
    }

    /**
     * Set registered
     *
     * @param \DateTime $registered
     * @return User
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;
    
        return $this;
    }

    /**
     * Get registered
     *
     * @return \DateTime 
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return User
     */
    public function setLastLogin()
    {
        $this->lastLogin = new \Datetime();
    
        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime 
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set confirmationToken
     *
     * @param string $confirmationToken
     * @return User
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    
        return $this;
    }

    /**
     * Get confirmationToken
     *
     * @return string 
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Set passwordRequestedAt
     *
     * @param \DateTime $passwordRequestedAt
     * @return User
     */
    public function setPasswordRequestedAt($passwordRequestedAt)
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
    
        return $this;
    }

    /**
     * Get passwordRequestedAt
     *
     * @return \DateTime 
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return User
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
    
        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean 
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set lockedFor
     *
     * @param boolean $lockedFor
     * @return User
     */
    public function setLockedFor($lockedFor)
    {
        $this->lockedFor = $lockedFor;
    
        return $this;
    }

    /**
     * Get lockedFor
     *
     * @return boolean 
     */
    public function getLockedFor()
    {
        return $this->lockedFor;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    public function addRole($role)
    {
        $role = strtoupper($role);
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        $roles = $this->roles;

        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('ROLE_SUPER_ADMIN');
    }

    public function setSuperAdmin($boolean)
    {
        if (true === $boolean) {
            $this->addRole('ROLE_SUPER_ADMIN');
        } else {
            $this->removeRole('ROLE_SUPER_ADMIN');
        }

        return $this;
    }

    public function isUser(UserInterface $user = null)
    {
        return null !== $user && $this->getId() === $user->getId();
    }

    /**
     * Add groups
     *
     * @param \Mgn\UserBundle\Entity\UserToGroup $groups
     * @return User
     */
    public function addGroup(\Mgn\UserBundle\Entity\UserToGroup $groups)
    {
        $this->groups[] = $groups;
    
        return $this;
    }

    /**
     * Remove groups
     *
     * @param \Mgn\UserBundle\Entity\UserToGroup $groups
     */
    public function removeGroup(\Mgn\UserBundle\Entity\UserToGroup $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set theme
     *
     * @param string $theme
     * @return User
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        $this->setThemeDate();
    
        return $this;
    }

    /**
     * Get theme
     *
     * @return string 
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set themeDate
     *
     * @param \DateTime $themeDate
     * @return User
     */
    public function setThemeDate()
    {
        $this->themeDate = new \Datetime();
    
        return $this;
    }

    /**
     * Get themeDate
     *
     * @return \DateTime 
     */
    public function getThemeDate()
    {
        return $this->themeDate;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    
        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set signature
     *
     * @param string $signature
     * @return User
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
    
        return $this;
    }

    /**
     * Get signature
     *
     * @return string 
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return User
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    
        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     * @return User
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    
        return $this;
    }

    /**
     * Get facebook
     *
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set googleplus
     *
     * @param string $googleplus
     * @return User
     */
    public function setGoogleplus($googleplus)
    {
        $this->googleplus = $googleplus;
    
        return $this;
    }

    /**
     * Get googleplus
     *
     * @return string 
     */
    public function getGoogleplus()
    {
        return $this->googleplus;
    }

    /**
     * Set steam
     *
     * @param string $steam
     * @return User
     */
    public function setSteam($steam)
    {
        $this->steam = $steam;
    
        return $this;
    }

    /**
     * Get steam
     *
     * @return string 
     */
    public function getSteam()
    {
        return $this->steam;
    }

    /**
     * Set countArticle
     *
     * @param integer $countArticle
     * @return User
     */
    public function setCountArticle($countArticle)
    {
        $this->countArticle = $countArticle;
    
        return $this;
    }

    /**
     * Get countArticle
     *
     * @return integer 
     */
    public function getCountArticle()
    {
        return $this->countArticle;
    }

    /**
     * Set countTopic
     *
     * @param integer $countTopic
     * @return User
     */
    public function setCountTopic($countTopic)
    {
        $this->countTopic = $countTopic;
    
        return $this;
    }

    /**
     * Get countTopic
     *
     * @return integer 
     */
    public function getCountTopic()
    {
        return $this->countTopic;
    }

    /**
     * Set countPost
     *
     * @param integer $countPost
     * @return User
     */
    public function setCountPost($countPost)
    {
        $this->countPost = $countPost;
    
        return $this;
    }

    /**
     * Get countPost
     *
     * @return integer 
     */
    public function getCountPost()
    {
        return $this->countPost;
    }

    /**
     * Set countMessage
     *
     * @param integer $countMessage
     * @return User
     */
    public function setCountMessage($countMessage)
    {
        $this->countMessage = $countMessage;
    
        return $this;
    }

    /**
     * Get countMessage
     *
     * @return integer 
     */
    public function getCountMessage()
    {
        return $this->countMessage;
    }

    public function upload()
  {
    // Si jamais il n'y a pas de fichier (champ facultatif)
    if (null === $this->avatarFile) {
      return;
    }
 
    // On garde le nom original du fichier de l'internaute
    $name = $this->id.'.'.$this->avatarFile->guessExtension();
 
    // On déplace le fichier envoyé dans le répertoire de notre choix
    $this->avatarFile->move($this->getUploadRootDir(), $name);
 
    // On sauvegarde le nom de fichier dans notre attribut $url
    $this->avatarPath = $name;

    $this->avatar = 'personal';
  }
 
  public function getUploadDir()
  {
    // On retourne le chemin relatif vers l'image pour un navigateur
    return 'uploads/avatars';
  }
 
  protected function getUploadRootDir()
  {
    // On retourne le chemin relatif vers l'image pour notre code PHP
    return __DIR__.'/../../../../web/'.$this->getUploadDir();
  }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        if (null !== $this->avatarFile)
        {
            return $this->avatarFile->guessExtension();
        }
    }
    
    public function setAvatar($Avatar)
    {
        $this->avatar = $Avatar;

        return $this;
    }
    
    public function getAvatar()
    {
        return $this->avatar;
    }
    
    public function setAvatarPath($AvatarPath)
    {
        $this->avatarPath = $AvatarPath;

        return $this;
    }
    
    public function getAvatarPath()
    {
        return $this->avatarPath;
    }
    
    public function setAvatarFile($AvatarFile)
    {
        $this->avatarFile = $AvatarFile;

        return $this;
    }
    
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }
}