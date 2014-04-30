<?php
namespace Mgn\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;

use Mgn\ForumBundle\Entity\Forum;
use Mgn\MessageBundle\Entity\Message;

/**
 * Mgn\ForumBundle\Entity\Topic
 *
 * @ORM\Table(name="mgn_forum_topic")
 * @ORM\Entity(repositoryClass="Mgn\ForumBundle\Entity\TopicRepository")
 */
class Topic
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
     * @var integer $forum
     *
     * @ORM\ManyToOne(targetEntity="Mgn\ForumBundle\Entity\Forum", inversedBy="topics")
     */
    private $forum;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
    * @Gedmo\Slug(fields={"title"})
    * @ORM\Column(length=128, unique=true)
    */
    private $slug;

    /**
     * @var bigint $countViews
     *
     * @ORM\Column(name="countViews", type="bigint")
     */
    private $countViews;

    /**
     * @var bigint $countPosts
     *
     * @ORM\Column(name="countPosts", type="bigint")
     */
    private $countPosts;

    /**
     * @var string $typeTopic
     *
     * @ORM\Column(name="typeTopic", type="string", length=255)
     */
    private $typeTopic;

    /**
     * @var boolean $topicLock
     *
     * @ORM\Column(name="topicLock", type="boolean")
     */
    private $topicLock;

    /**
     * @var integer $firstMessage
     *
     * @ORM\OneToOne(targetEntity="Mgn\MessageBundle\Entity\Message")
     * @ORM\JoinColumn(nullable=true)
     */
    private $firstMessage;

    /**
     * @var integer $lastMessage
     *
     * @ORM\OneToOne(targetEntity="Mgn\MessageBundle\Entity\Message", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $lastMessage;

    /**
     * @ORM\OneToMany(targetEntity="Mgn\MessageBundle\Entity\Message", mappedBy="topic")
     */
    private $messages;
	
	public function __construct()
    {
    	$this->countViews = '0';
		$this->countPosts = '1';
		$this->typeTopic = 'normal';
		$this->topicLock = false;
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
     * Set title
     *
     * @param string $title
     * @return Topic
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Topic
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
     * Set countViews
     *
     * @param integer $countViews
     * @return Topic
     */
    public function setCountViews($countViews)
    {
        $this->countViews = $countViews;
    
        return $this;
    }

    /**
     * Get countViews
     *
     * @return integer 
     */
    public function getCountViews()
    {
        return $this->countViews;
    }

    /**
     * Set countPosts
     *
     * @param integer $countPosts
     * @return Topic
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
     * Set typeTopic
     *
     * @param string $typeTopic
     * @return Topic
     */
    public function setTypeTopic($typeTopic)
    {
        $this->typeTopic = $typeTopic;
    
        return $this;
    }

    /**
     * Get typeTopic
     *
     * @return string 
     */
    public function getTypeTopic()
    {
        return $this->typeTopic;
    }

    /**
     * Set topicLock
     *
     * @param boolean $topicLock
     * @return Topic
     */
    public function setTopicLock($topicLock)
    {
        $this->topicLock = $topicLock;
    
        return $this;
    }

    /**
     * Get topicLock
     *
     * @return boolean 
     */
    public function getTopicLock()
    {
        return $this->topicLock;
    }

    /**
     * Set forum
     *
     * @param \Mgn\ForumBundle\Entity\Forum $forum
     * @return Topic
     */
    public function setForum(\Mgn\ForumBundle\Entity\Forum $forum = null)
    {
        $this->forum = $forum;
    
        return $this;
    }

    /**
     * Get forum
     *
     * @return \Mgn\ForumBundle\Entity\Forum 
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Set firstMessage
     *
     * @param \Mgn\MessageBundle\Entity\Message $firstMessage
     * @return Topic
     */
    public function setFirstMessage(\Mgn\MessageBundle\Entity\Message $firstMessage = null)
    {
        $this->firstMessage = $firstMessage;
    
        return $this;
    }

    /**
     * Get firstMessage
     *
     * @return \Mgn\MessageBundle\Entity\Message 
     */
    public function getFirstMessage()
    {
        return $this->firstMessage;
    }

    /**
     * Set lastMessage
     *
     * @param \Mgn\MessageBundle\Entity\Message $lastMessage
     * @return Topic
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
     * Add messages
     *
     * @param \Mgn\MessageBundle\Entity\Message $messages
     * @return Topic
     */
    public function addMessage(\Mgn\MessageBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;
    
        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Mgn\MessageBundle\Entity\Message $messages
     */
    public function removeMessage(\Mgn\MessageBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }
}