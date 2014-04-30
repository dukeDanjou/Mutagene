<?php
namespace Mgn\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mgn\ForumBundle\Entity\Topic;

/**
 * Mgn\MessageBundle\Entity\Message
 *
 * @ORM\Table(name="mgn_message")
 * @ORM\Entity(repositoryClass="Mgn\MessageBundle\Entity\MessageRepository")
 */
class Message
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
     * @var integer $topic
     *
     * @ORM\ManyToOne(targetEntity="Mgn\ForumBundle\Entity\Topic", inversedBy="messages")
     * @ORM\JoinColumn(nullable=true)
     */
    private $topic;

    /**
     * @var integer $forum
     *
     * @ORM\ManyToOne(targetEntity="Mgn\ForumBundle\Entity\Forum")
     * @ORM\JoinColumn(nullable=true)
     */
    private $forum;
  
    /**
     * @ORM\ManyToOne(targetEntity="Mgn\ArticleBundle\Entity\Article")
     * @ORM\JoinColumn(nullable=true)
     */
    private $article;

    /**
     * @var integer $author
     *
     * @ORM\ManyToOne(targetEntity="Mgn\UserBundle\Entity\User")
     */
    private $author;

    /**
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string $userIp
     *
     * @ORM\Column(name="userIp", type="string", length=255, nullable=true)
     */
    private $userIp;

    /**
     * @var datetime $dateEdit
     *
     * @ORM\Column(name="dateEdit", type="datetime", nullable=true)
     */
    private $dateEdit;

    /**
     * @var integer $countEdit
     *
     * @ORM\Column(name="countEdit", type="integer")
     */
    private $countEdit;

    /**
     * @var text $contents
     *
     * @ORM\Column(name="contents", type="text")
     */
    private $contents;

    /**
     * @var boolean $messageLock
     *
     * @ORM\Column(name="messageLock", type="boolean")
     */
    private $messageLock;

    public function __construct()
    {
        $date = new \Datetime();

        $this->date = $date;
        $this->dateEdit = $date;
        $this->countEdit = 0;
        $this->messageLock = false;
        $this->topic = null;
        $this->forum = null;
        $this->article = null;
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
     * Set date
     *
     * @param \DateTime $date
     * @return Message
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set userIp
     *
     * @param string $userIp
     * @return Message
     */
    public function setUserIp($userIp)
    {
        $this->userIp = $userIp;
    
        return $this;
    }

    /**
     * Get userIp
     *
     * @return string 
     */
    public function getUserIp()
    {
        return $this->userIp;
    }

    /**
     * Set dateEdit
     *
     * @param \DateTime $dateEdit
     * @return Message
     */
    public function setDateEdit($dateEdit)
    {
        $this->dateEdit = $dateEdit;
    
        return $this;
    }

    /**
     * Get dateEdit
     *
     * @return \DateTime 
     */
    public function getDateEdit()
    {
        return $this->dateEdit;
    }

    /**
     * Set countEdit
     *
     * @param integer $countEdit
     * @return Message
     */
    public function setCountEdit($countEdit)
    {
        $this->countEdit = $countEdit;
    
        return $this;
    }

    /**
     * Get countEdit
     *
     * @return integer 
     */
    public function getCountEdit()
    {
        return $this->countEdit;
    }

    /**
     * Set contents
     *
     * @param string $contents
     * @return Message
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    
        return $this;
    }

    /**
     * Get contents
     *
     * @return string 
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set messageLock
     *
     * @param boolean $messageLock
     * @return Message
     */
    public function setMessageLock($messageLock)
    {
        $this->messageLock = $messageLock;
    
        return $this;
    }

    /**
     * Get messageLock
     *
     * @return boolean 
     */
    public function getMessageLock()
    {
        return $this->messageLock;
    }

    /**
     * Set topic
     *
     * @param \Mgn\ForumBundle\Entity\Topic $topic
     * @return Message
     */
    public function setTopic(\Mgn\ForumBundle\Entity\Topic $topic = null)
    {
        $this->topic = $topic;
    
        return $this;
    }

    /**
     * Get topic
     *
     * @return \Mgn\ForumBundle\Entity\Topic 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set forum
     *
     * @param \Mgn\ForumBundle\Entity\Forum $forum
     * @return Message
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
     * Set author
     *
     * @param \Mgn\UserBundle\Entity\User $author
     * @return Message
     */
    public function setAuthor(\Mgn\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return \Mgn\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set article
     *
     * @param \Mgn\ArticleBundle\Entity\Article $article
     * @return Message
     */
    public function setArticle(\Mgn\ArticleBundle\Entity\Article $article = null)
    {
        $this->article = $article;
    
        return $this;
    }

    /**
     * Get article
     *
     * @return \Mgn\ArticleBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }
}