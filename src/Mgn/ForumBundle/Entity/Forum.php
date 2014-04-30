<?php

namespace Mgn\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;

use Mgn\ForumBundle\Entity\Category;
use Mgn\MessageBundle\Entity\Message;

/**
 * Mgn\ForumBundle\Entity\Forum
 *
 * @ORM\Table(name="mgn_forum")
 * @ORM\Entity(repositoryClass="Mgn\ForumBundle\Entity\ForumRepository")
 */
class Forum
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
     * @var smallint $category
     *
     * @ORM\ManyToOne(targetEntity="Mgn\ForumBundle\Entity\Category", inversedBy="forums")
     */
    private $category;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(length=128, unique=true)
    */
    private $slug;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var smallint $sort
     *
     * @ORM\Column(name="sort", type="smallint")
     */
    private $sort;

    /**
     * @var integer $countPosts
     *
     * @ORM\Column(name="countPosts", type="integer")
     */
    private $countPosts;

    /**
     * @var integer $countTopics
     *
     * @ORM\Column(name="countTopics", type="integer")
     */
    private $countTopics;
	
    /**
     * @var integer $publicAclView
     *
     * @ORM\Column(name="publicAclView", type="boolean")
     */
    private $publicAclView;
	
    /**
     * @var integer $publicAclPost
     *
     * @ORM\Column(name="publicAclPost", type="boolean")
     */
    private $publicAclPost;
	
    /**
     * @var integer $publicAclTopic
     *
     * @ORM\Column(name="publicAclTopic", type="boolean")
     */
    private $publicAclTopic;

    /**
     * @var integer $lastTopic
     *
     * @ORM\OneToOne(targetEntity="Mgn\ForumBundle\Entity\Topic")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lastTopic;

    /**
     * @var integer $lastMessage
     *
     * @ORM\OneToOne(targetEntity="Mgn\MessageBundle\Entity\Message")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lastMessage;

    /**
     * @ORM\OneToMany(targetEntity="Mgn\ForumBundle\Entity\Topic", mappedBy="forum")
     */
    private $topics;

    public function __construct()
    {
        $this->countPosts = 0;
        $this->countTopics = 0;
        $this->sort = 0;
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
     * Set name
     *
     * @param string $name
     * @return Forum
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Forum
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
     * Set description
     *
     * @param string $description
     * @return Forum
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
     * Set sort
     *
     * @param integer $sort
     * @return Forum
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    
        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set countPosts
     *
     * @param integer $countPosts
     * @return Forum
     */
    public function setCountPosts($countPosts)
    {
        $this->countPosts = $countPosts;
    
        return $this;
    }

    /**
     * Get countPosts
     *
     * @return integer 
     */
    public function getCountPosts()
    {
        return $this->countPosts;
    }

    /**
     * Set countTopics
     *
     * @param integer $countTopics
     * @return Forum
     */
    public function setCountTopics($countTopics)
    {
        $this->countTopics = $countTopics;
    
        return $this;
    }

    /**
     * Get countTopics
     *
     * @return integer 
     */
    public function getCountTopics()
    {
        return $this->countTopics;
    }

    /**
     * Set publicAclView
     *
     * @param boolean $publicAclView
     * @return Forum
     */
    public function setPublicAclView($publicAclView)
    {
        $this->publicAclView = $publicAclView;
    
        return $this;
    }

    /**
     * Get publicAclView
     *
     * @return boolean 
     */
    public function getPublicAclView()
    {
        return $this->publicAclView;
    }

    /**
     * Set publicAclPost
     *
     * @param boolean $publicAclPost
     * @return Forum
     */
    public function setPublicAclPost($publicAclPost)
    {
        $this->publicAclPost = $publicAclPost;
    
        return $this;
    }

    /**
     * Get publicAclPost
     *
     * @return boolean 
     */
    public function getPublicAclPost()
    {
        return $this->publicAclPost;
    }

    /**
     * Set publicAclTopic
     *
     * @param boolean $publicAclTopic
     * @return Forum
     */
    public function setPublicAclTopic($publicAclTopic)
    {
        $this->publicAclTopic = $publicAclTopic;
    
        return $this;
    }

    /**
     * Get publicAclTopic
     *
     * @return boolean 
     */
    public function getPublicAclTopic()
    {
        return $this->publicAclTopic;
    }

    /**
     * Set category
     *
     * @param \Mgn\ForumBundle\Entity\Category $category
     * @return Forum
     */
    public function setCategory(\Mgn\ForumBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Mgn\ForumBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set lastTopic
     *
     * @param \Mgn\ForumBundle\Entity\Topic $lastTopic
     * @return Forum
     */
    public function setLastTopic(\Mgn\ForumBundle\Entity\Topic $lastTopic = null)
    {
        $this->lastTopic = $lastTopic;
    
        return $this;
    }

    /**
     * Get lastTopic
     *
     * @return \Mgn\ForumBundle\Entity\Topic 
     */
    public function getLastTopic()
    {
        return $this->lastTopic;
    }

    /**
     * Set lastMessage
     *
     * @param \Mgn\MessageBundle\Entity\Message $lastMessage
     * @return Forum
     */
    public function setLastMessage(\Mgn\MessageBundle\Entity\Message $lastMessage = null)
    {
        $this->lastMessage = $lastMessage;
    
        return $this;
    }

    /**
     * Get lastMessage
     *
     * @return \Mgn\MessageBundle\Entity\Message 
     */
    public function getLastMessage()
    {
        return $this->lastMessage;
    }

    /**
     * Add topics
     *
     * @param \Mgn\ForumBundle\Entity\Topic $topics
     * @return Forum
     */
    public function addTopic(\Mgn\ForumBundle\Entity\Topic $topics)
    {
        $this->topics[] = $topics;
    
        return $this;
    }

    /**
     * Remove topics
     *
     * @param \Mgn\ForumBundle\Entity\Topic $topics
     */
    public function removeTopic(\Mgn\ForumBundle\Entity\Topic $topics)
    {
        $this->topics->removeElement($topics);
    }

    /**
     * Get topics
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTopics()
    {
        return $this->topics;
    }
}