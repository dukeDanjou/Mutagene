<?php

namespace Mgn\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Sylvain "KnoKosS" Meunier <knokoss@gmail.com>
 * @copyright 2013 Sylvain Meunier
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 *
 * @ORM\Entity
 * @ORM\Table(name="mgn_core_config")
 */
class Config {

	/**
	 * @var string
	 * @ORM\Id
	 * @ORM\Column(name="cms", type="string", nullable=false, unique=true)
	 * @Assert\NotBlank
	 */
	protected $cms;

	/**
	 * @var string
	 * @ORM\Column(name="siteTitle", type="string", nullable=true)
	 */
	protected $siteTitle;

	/**
	 * @var string
	 * @ORM\Column(name="siteDescription", type="string", nullable=true)
	 */
	protected $siteDescription;

	/**
	 * @var string
	 * @ORM\Column(name="email", type="string", nullable=true)
	 */
	protected $email;

    /**
     * @var boolean
     * @ORM\Column(name="cmsForum", type="boolean", nullable=true)
     */
    protected $cmsForum;

    /**
     * @var boolean
     * @ORM\Column(name="cmsArticle", type="boolean", nullable=true)
     */
    protected $cmsArticle;

    /**
     * @var string
     * @ORM\Column(name="homepage", type="string", nullable=true)
     */
    protected $homepage;

    /**
     * @var string
     * @ORM\Column(name="forumPostPage", type="integer", nullable=true)
     */
    protected $forumPostPage;

    /**
     * @var string
     * @ORM\Column(name="forumTopicPage", type="integer", nullable=true)
     */
    protected $forumTopicPage;

    /**
     * @var string
     * @ORM\Column(name="forumIndexLigne", type="integer", nullable=true)
     */
    protected $forumIndexLigne;

    /**
     * @var string
     * @ORM\Column(name="articleCountIndex", type="integer", nullable=true)
     */
    protected $articleCountIndex;

    /**
     * @var text $reglement
     *
     * @ORM\Column(name="reglement", type="text", nullable=true)
     */
    private $reglement;

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
     * @var boolean
     * @ORM\Column(name="profileFirstName", type="boolean", nullable=true)
     */
    protected $profileFirstName;

    /**
     * @var boolean
     * @ORM\Column(name="profileLastName", type="boolean", nullable=true)
     */
    protected $profileLastName;

    /**
     * @var boolean
     * @ORM\Column(name="profileBirthday", type="boolean", nullable=true)
     */
    protected $profileBirthday;

    /**
     * @var boolean
     * @ORM\Column(name="profileSignature", type="boolean", nullable=true)
     */
    protected $profileSignature;

    /**
     * @var boolean
     * @ORM\Column(name="profileTwitter", type="boolean", nullable=true)
     */
    protected $profileTwitter;

    /**
     * @var boolean
     * @ORM\Column(name="profileFacebook", type="boolean", nullable=true)
     */
    protected $profileFacebook;

    /**
     * @var boolean
     * @ORM\Column(name="profileGoogleplus", type="boolean", nullable=true)
     */
    protected $profileGoogleplus;

    /**
     * @var boolean
     * @ORM\Column(name="profileSteam", type="boolean", nullable=true)
     */
    protected $profileSteam;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalArticles", type="bigint", nullable=true)
     */
    private $totalArticles;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalArticlesPublish", type="bigint", nullable=true)
     */
    private $totalArticlesPublish;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalArticlesPending", type="bigint", nullable=true)
     */
    private $totalArticlesPending;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalArticlesDraft", type="bigint", nullable=true)
     */
    private $totalArticlesDraft;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalPosts", type="bigint", nullable=true)
     */
    private $totalPosts;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalTopics", type="bigint", nullable=true)
     */
    private $totalTopics;

	public function setCms($cms) {
		$this->cms = $cms;
	}

	public function getCms() {
		return $this->cms;
	}

	public function getSiteDescription() {
		return $this->siteDescription;
	}

    /**
     * Set siteDescription
     *
     * @param string $siteDescription
     * @return Config
     */
    public function setSiteDescription($siteDescription)
    {
        $this->siteDescription = $siteDescription;
    
        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Config
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
     * Set forumPostPage
     *
     * @param integer $forumPostPage
     * @return Config
     */
    public function setForumPostPage($forumPostPage)
    {
        $this->forumPostPage = $forumPostPage;
    
        return $this;
    }

    /**
     * Get forumPostPage
     *
     * @return integer 
     */
    public function getForumPostPage()
    {
        return $this->forumPostPage;
    }

    /**
     * Set reglement
     *
     * @param string $reglement
     * @return Config
     */
    public function setReglement($reglement)
    {
        $this->reglement = $reglement;
    
        return $this;
    }

    /**
     * Get reglement
     *
     * @return string 
     */
    public function getReglement()
    {
        return $this->reglement;
    }

    /**
     * Set cmsForum
     *
     * @param boolean $cmsForum
     * @return Config
     */
    public function setCmsForum($cmsForum)
    {
        $this->cmsForum = $cmsForum;
    
        return $this;
    }

    /**
     * Get cmsForum
     *
     * @return boolean 
     */
    public function getCmsForum()
    {
        return $this->cmsForum;
    }

    /**
     * Set homepage
     *
     * @param string $homepage
     * @return Config
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;
    
        return $this;
    }

    /**
     * Get homepage
     *
     * @return string 
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * Set cmsArticle
     *
     * @param boolean $cmsArticle
     * @return Config
     */
    public function setCmsArticle($cmsArticle)
    {
        $this->cmsArticle = $cmsArticle;
    
        return $this;
    }

    /**
     * Get cmsArticle
     *
     * @return boolean 
     */
    public function getCmsArticle()
    {
        return $this->cmsArticle;
    }

    /**
     * Set theme
     *
     * @param string $theme
     * @return Config
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    
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
     * @return Config
     */
    public function setThemeDate($themeDate)
    {
        $this->themeDate = $themeDate;
    
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
     * Set profileFirstName
     *
     * @param boolean $profileFirstName
     * @return Config
     */
    public function setProfileFirstName($profileFirstName)
    {
        $this->profileFirstName = $profileFirstName;
    
        return $this;
    }

    /**
     * Get profileFirstName
     *
     * @return boolean 
     */
    public function getProfileFirstName()
    {
        return $this->profileFirstName;
    }

    /**
     * Set profileLastName
     *
     * @param boolean $profileLastName
     * @return Config
     */
    public function setProfileLastName($profileLastName)
    {
        $this->profileLastName = $profileLastName;
    
        return $this;
    }

    /**
     * Get profileLastName
     *
     * @return boolean 
     */
    public function getProfileLastName()
    {
        return $this->profileLastName;
    }

    /**
     * Set profileBirthday
     *
     * @param boolean $profileBirthday
     * @return Config
     */
    public function setProfileBirthday($profileBirthday)
    {
        $this->profileBirthday = $profileBirthday;
    
        return $this;
    }

    /**
     * Get profileBirthday
     *
     * @return boolean 
     */
    public function getProfileBirthday()
    {
        return $this->profileBirthday;
    }

    /**
     * Set profileSignature
     *
     * @param boolean $profileSignature
     * @return Config
     */
    public function setProfileSignature($profileSignature)
    {
        $this->profileSignature = $profileSignature;
    
        return $this;
    }

    /**
     * Get profileSignature
     *
     * @return boolean 
     */
    public function getProfileSignature()
    {
        return $this->profileSignature;
    }

    /**
     * Set totalArticles
     *
     * @param integer $totalArticles
     * @return Config
     */
    public function setTotalArticles($totalArticles)
    {
        $this->totalArticles = $totalArticles;
    
        return $this;
    }

    /**
     * Get totalArticles
     *
     * @return integer 
     */
    public function getTotalArticles()
    {
        return $this->totalArticles;
    }

    /**
     * Set forumIndexLigne
     *
     * @param integer $forumIndexLigne
     * @return Config
     */
    public function setForumIndexLigne($forumIndexLigne)
    {
        $this->forumIndexLigne = $forumIndexLigne;
    
        return $this;
    }

    /**
     * Get forumIndexLigne
     *
     * @return integer 
     */
    public function getForumIndexLigne()
    {
        return $this->forumIndexLigne;
    }

    /**
     * Set totalPosts
     *
     * @param integer $totalPosts
     * @return Config
     */
    public function setTotalPosts($totalPosts)
    {
        $this->totalPosts = $totalPosts;
    
        return $this;
    }

    /**
     * Get totalPosts
     *
     * @return integer 
     */
    public function getTotalPosts()
    {
        return $this->totalPosts;
    }

    /**
     * Set totalTopics
     *
     * @param integer $totalTopics
     * @return Config
     */
    public function setTotalTopics($totalTopics)
    {
        $this->totalTopics = $totalTopics;
    
        return $this;
    }

    /**
     * Get totalTopics
     *
     * @return integer 
     */
    public function getTotalTopics()
    {
        return $this->totalTopics;
    }

    /**
     * Set forumTopicPage
     *
     * @param integer $forumTopicPage
     * @return Config
     */
    public function setForumTopicPage($forumTopicPage)
    {
        $this->forumTopicPage = $forumTopicPage;
    
        return $this;
    }

    /**
     * Get forumTopicPage
     *
     * @return integer 
     */
    public function getForumTopicPage()
    {
        return $this->forumTopicPage;
    }

    /**
     * Set profileTwitter
     *
     * @param boolean $profileTwitter
     * @return Config
     */
    public function setProfileTwitter($profileTwitter)
    {
        $this->profileTwitter = $profileTwitter;
    
        return $this;
    }

    /**
     * Get profileTwitter
     *
     * @return boolean 
     */
    public function getProfileTwitter()
    {
        return $this->profileTwitter;
    }

    /**
     * Set profileFacebook
     *
     * @param boolean $profileFacebook
     * @return Config
     */
    public function setProfileFacebook($profileFacebook)
    {
        $this->profileFacebook = $profileFacebook;
    
        return $this;
    }

    /**
     * Get profileFacebook
     *
     * @return boolean 
     */
    public function getProfileFacebook()
    {
        return $this->profileFacebook;
    }

    /**
     * Set profileGoogleplus
     *
     * @param boolean $profileGoogleplus
     * @return Config
     */
    public function setProfileGoogleplus($profileGoogleplus)
    {
        $this->profileGoogleplus = $profileGoogleplus;
    
        return $this;
    }

    /**
     * Get profileGoogleplus
     *
     * @return boolean 
     */
    public function getProfileGoogleplus()
    {
        return $this->profileGoogleplus;
    }

    /**
     * Set profileSteam
     *
     * @param boolean $profileSteam
     * @return Config
     */
    public function setProfileSteam($profileSteam)
    {
        $this->profileSteam = $profileSteam;
    
        return $this;
    }

    /**
     * Get profileSteam
     *
     * @return boolean 
     */
    public function getProfileSteam()
    {
        return $this->profileSteam;
    }

    /**
     * Set articleCountIndex
     *
     * @param integer $articleCountIndex
     * @return Config
     */
    public function setArticleCountIndex($articleCountIndex)
    {
        $this->articleCountIndex = $articleCountIndex;
    
        return $this;
    }

    /**
     * Get articleCountIndex
     *
     * @return integer 
     */
    public function getArticleCountIndex()
    {
        return $this->articleCountIndex;
    }

    /**
     * Set siteTitle
     *
     * @param string $siteTitle
     * @return Config
     */
    public function setSiteTitle($siteTitle)
    {
        $this->siteTitle = $siteTitle;
    
        return $this;
    }

    /**
     * Get siteTitle
     *
     * @return string 
     */
    public function getSiteTitle()
    {
        return $this->siteTitle;
    }

    /**
     * Set totalArticlesPublish
     *
     * @param integer $totalArticlesPublish
     * @return Config
     */
    public function setTotalArticlesPublish($totalArticlesPublish)
    {
        $this->totalArticlesPublish = $totalArticlesPublish;
    
        return $this;
    }

    /**
     * Get totalArticlesPublish
     *
     * @return integer 
     */
    public function getTotalArticlesPublish()
    {
        return $this->totalArticlesPublish;
    }

    /**
     * Set totalArticlesPending
     *
     * @param integer $totalArticlesPending
     * @return Config
     */
    public function setTotalArticlesPending($totalArticlesPending)
    {
        $this->totalArticlesPending = $totalArticlesPending;
    
        return $this;
    }

    /**
     * Get totalArticlesPending
     *
     * @return integer 
     */
    public function getTotalArticlesPending()
    {
        return $this->totalArticlesPending;
    }

    /**
     * Set totalArticlesDraft
     *
     * @param integer $totalArticlesDraft
     * @return Config
     */
    public function setTotalArticlesDraft($totalArticlesDraft)
    {
        $this->totalArticlesDraft = $totalArticlesDraft;
    
        return $this;
    }

    /**
     * Get totalArticlesDraft
     *
     * @return integer 
     */
    public function getTotalArticlesDraft()
    {
        return $this->totalArticlesDraft;
    }
}