<?php
namespace Mgn\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Mgn\MessageBundle\Entity\Message;

/**
 * Mgn\ForumBundle\Entity\View
 *
 * @ORM\Table(name="mgn_forum_view")
 * @ORM\Entity(repositoryClass="Mgn\ForumBundle\Entity\ViewRepository")
 */
class View
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
     * @var integer $user
     *
     * @ORM\ManyToOne(targetEntity="Mgn\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var integer $topic
     *
     * @ORM\ManyToOne(targetEntity="Mgn\ForumBundle\Entity\Topic")
     */
    private $topic;

    /**
     * @var integer $forum
     *
     * @ORM\ManyToOne(targetEntity="Mgn\ForumBundle\Entity\Forum")
     */
    private $forum;

    /**
     * @var integer $message
     *
     * @ORM\ManyToOne(targetEntity="Mgn\MessageBundle\Entity\Message")
     */
    private $message;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

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
     * Set status
     *
     * @param integer $status
     * @return View
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return View
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
     * Set user
     *
     * @param \Mgn\UserBundle\Entity\User $user
     * @return View
     */
    public function setUser(\Mgn\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Mgn\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set topic
     *
     * @param \Mgn\ForumBundle\Entity\Topic $topic
     * @return View
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
     * @return View
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
     * Set message
     *
     * @param \Mgn\MessageBundle\Entity\Message $message
     * @return View
     */
    public function setMessage(\Mgn\MessageBundle\Entity\Message $message = null)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return \Mgn\MessageBundle\Entity\Message 
     */
    public function getMessage()
    {
        return $this->message;
    }
}