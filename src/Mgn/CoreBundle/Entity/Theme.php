<?php

namespace Mgn\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @author Sylvain "KnoKosS" Meunier <knokoss@gmail.com>
 * @copyright 2013 Sylvain Meunier
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 *
 * @ORM\Entity
 * @ORM\Table(name="mgn_core_themes")
 */
class Theme {

	/**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

	/**
	 * @var string
	 * @ORM\Column(name="themeTitle", type="string", nullable=false)
	 */
	protected $themeTitle;
 
    /**
     * @Gedmo\Slug(fields={"themeTitle"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var integer
     * @ORM\Column(name="version", type="integer", nullable=true)
     */
    protected $version;

    /**
     * @var string
     * @ORM\Column(name="author", type="string", nullable=false)
     */
    protected $author;

    /**
     * @var string
     * @ORM\Column(name="url", type="string", nullable=false)
     */
    protected $url;

    /**
     * @var text $head
     *
     * @ORM\Column(name="head", type="text", nullable=true)
     */
    private $head;

    /**
     * @var text $layout
     *
     * @ORM\Column(name="layout", type="text", nullable=true)
     */
    private $layout;

    /**
     * @var text $menu
     *
     * @ORM\Column(name="menu", type="text", nullable=true)
     */
    private $menu;

    /**
     * @var text $javascript
     *
     * @ORM\Column(name="javascript", type="text", nullable=true)
     */
    private $javascript;

    /**
     * @var text $articleBundleArticleIndex
     *
     * @ORM\Column(name="articleBundleArticleIndex", type="text", nullable=true)
     */
    private $articleBundleArticleIndex;

    /**
     * @var text $articleBundleArticleRead
     *
     * @ORM\Column(name="articleBundleArticleRead", type="text", nullable=true)
     */
    private $articleBundleArticleRead;

    /**
     * @var text $forumBundleForumsAllClassic
     *
     * @ORM\Column(name="forumBundleForumsAllClassic", type="text", nullable=true)
     */
    private $forumBundleForumsAllClassic;

    /**
     * @var text $forumBundleForumsAllBlock
     *
     * @ORM\Column(name="forumBundleForumsAllBlock", type="text", nullable=true)
     */
    private $forumBundleForumsAllBlock;

    /**
     * @var text $forumBundleForumsViewClassic
     *
     * @ORM\Column(name="forumBundleForumsViewClassic", type="text", nullable=true)
     */
    private $forumBundleForumsViewClassic;

    /**
     * @var text $forumBundleForumsViewBlock
     *
     * @ORM\Column(name="forumBundleForumsViewBlock", type="text", nullable=true)
     */
    private $forumBundleForumsViewBlock;

    /**
     * @var text $forumBundleForumsReadClassic
     *
     * @ORM\Column(name="forumBundleForumsReadClassic", type="text", nullable=true)
     */
    private $forumBundleForumsReadClassic;

    /**
     * @var text $forumBundleForumsReadBlock
     *
     * @ORM\Column(name="forumBundleForumsReadBlock", type="text", nullable=true)
     */
    private $forumBundleForumsReadBlock;

    /**
     * @var text $userBundleUserProfile
     *
     * @ORM\Column(name="userBundleUserProfile", type="text", nullable=true)
     */
    private $userBundleUserProfile;

    /**
     * @var text $userBundleUserEditProfile
     *
     * @ORM\Column(name="userBundleUserEditProfile", type="text", nullable=true)
     */
    private $userBundleUserEditProfile;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->bootstrap = true;
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
     * Set themeTitle
     *
     * @param string $themeTitle
     * @return Theme
     */
    public function setThemeTitle($themeTitle)
    {
        $this->themeTitle = $themeTitle;
    
        return $this;
    }

    /**
     * Get themeTitle
     *
     * @return string 
     */
    public function getThemeTitle()
    {
        return $this->themeTitle;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Theme
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set layout
     *
     * @param string $layout
     * @return Theme
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    
        return $this;
    }

    /**
     * Get layout
     *
     * @return string 
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set version
     *
     * @param integer $version
     * @return Theme
     */
    public function setVersion($version)
    {
        $this->version = $version;
    
        return $this;
    }

    /**
     * Get version
     *
     * @return integer 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Theme
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Theme
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Theme
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set javascript
     *
     * @param string $javascript
     * @return Theme
     */
    public function setJavascript($javascript)
    {
        $this->javascript = $javascript;
    
        return $this;
    }

    /**
     * Get javascript
     *
     * @return string 
     */
    public function getJavascript()
    {
        return $this->javascript;
    }

    /**
     * Set head
     *
     * @param string $head
     * @return Theme
     */
    public function setHead($head)
    {
        $this->head = $head;
    
        return $this;
    }

    /**
     * Get head
     *
     * @return string 
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * Set menu
     *
     * @param string $menu
     * @return Theme
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
    
        return $this;
    }

    /**
     * Get menu
     *
     * @return string 
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set articleBundleArticleIndex
     *
     * @param string $articleBundleArticleIndex
     * @return Theme
     */
    public function setArticleBundleArticleIndex($articleBundleArticleIndex)
    {
        $this->articleBundleArticleIndex = $articleBundleArticleIndex;
    
        return $this;
    }

    /**
     * Get articleBundleArticleIndex
     *
     * @return string 
     */
    public function getArticleBundleArticleIndex()
    {
        return $this->articleBundleArticleIndex;
    }

    /**
     * Set articleBundleArticleRead
     *
     * @param string $articleBundleArticleRead
     * @return Theme
     */
    public function setArticleBundleArticleRead($articleBundleArticleRead)
    {
        $this->articleBundleArticleRead = $articleBundleArticleRead;
    
        return $this;
    }

    /**
     * Get articleBundleArticleRead
     *
     * @return string 
     */
    public function getArticleBundleArticleRead()
    {
        return $this->articleBundleArticleRead;
    }

    /**
     * Set forumBundleForumsAllBlock
     *
     * @param string $forumBundleForumsAllBlock
     * @return Theme
     */
    public function setForumBundleForumsAllBlock($forumBundleForumsAllBlock)
    {
        $this->forumBundleForumsAllBlock = $forumBundleForumsAllBlock;
    
        return $this;
    }

    /**
     * Get forumBundleForumsAllBlock
     *
     * @return string 
     */
    public function getForumBundleForumsAllBlock()
    {
        return $this->forumBundleForumsAllBlock;
    }

    /**
     * Set forumBundleForumsAllClassic
     *
     * @param string $forumBundleForumsAllClassic
     * @return Theme
     */
    public function setForumBundleForumsAllClassic($forumBundleForumsAllClassic)
    {
        $this->forumBundleForumsAllClassic = $forumBundleForumsAllClassic;
    
        return $this;
    }

    /**
     * Get forumBundleForumsAllClassic
     *
     * @return string 
     */
    public function getForumBundleForumsAllClassic()
    {
        return $this->forumBundleForumsAllClassic;
    }

    /**
     * Set forumBundleForumsViewClassic
     *
     * @param string $forumBundleForumsViewClassic
     * @return Theme
     */
    public function setForumBundleForumsViewClassic($forumBundleForumsViewClassic)
    {
        $this->forumBundleForumsViewClassic = $forumBundleForumsViewClassic;
    
        return $this;
    }

    /**
     * Get forumBundleForumsViewClassic
     *
     * @return string 
     */
    public function getForumBundleForumsViewClassic()
    {
        return $this->forumBundleForumsViewClassic;
    }

    /**
     * Set forumBundleForumsViewBlock
     *
     * @param string $forumBundleForumsViewBlock
     * @return Theme
     */
    public function setForumBundleForumsViewBlock($forumBundleForumsViewBlock)
    {
        $this->forumBundleForumsViewBlock = $forumBundleForumsViewBlock;
    
        return $this;
    }

    /**
     * Get forumBundleForumsViewBlock
     *
     * @return string 
     */
    public function getForumBundleForumsViewBlock()
    {
        return $this->forumBundleForumsViewBlock;
    }

    /**
     * Set forumBundleForumsReadClassic
     *
     * @param string $forumBundleForumsReadClassic
     * @return Theme
     */
    public function setForumBundleForumsReadClassic($forumBundleForumsReadClassic)
    {
        $this->forumBundleForumsReadClassic = $forumBundleForumsReadClassic;
    
        return $this;
    }

    /**
     * Get forumBundleForumsReadClassic
     *
     * @return string 
     */
    public function getForumBundleForumsReadClassic()
    {
        return $this->forumBundleForumsReadClassic;
    }

    /**
     * Set forumBundleForumsReadBlock
     *
     * @param string $forumBundleForumsReadBlock
     * @return Theme
     */
    public function setForumBundleForumsReadBlock($forumBundleForumsReadBlock)
    {
        $this->forumBundleForumsReadBlock = $forumBundleForumsReadBlock;
    
        return $this;
    }

    /**
     * Get forumBundleForumsReadBlock
     *
     * @return string 
     */
    public function getForumBundleForumsReadBlock()
    {
        return $this->forumBundleForumsReadBlock;
    }

    /**
     * Set userBundleUserProfile
     *
     * @param string $userBundleUserProfile
     * @return Theme
     */
    public function setUserBundleUserProfile($userBundleUserProfile)
    {
        $this->userBundleUserProfile = $userBundleUserProfile;
    
        return $this;
    }

    /**
     * Get userBundleUserProfile
     *
     * @return string 
     */
    public function getUserBundleUserProfile()
    {
        return $this->userBundleUserProfile;
    }

    /**
     * Set userBundleUserEditProfile
     *
     * @param string $userBundleUserEditProfile
     * @return Theme
     */
    public function setUserBundleUserEditProfile($userBundleUserEditProfile)
    {
        $this->userBundleUserEditProfile = $userBundleUserEditProfile;
    
        return $this;
    }

    /**
     * Get userBundleUserEditProfile
     *
     * @return string 
     */
    public function getUserBundleUserEditProfile()
    {
        return $this->userBundleUserEditProfile;
    }
}