<?php

namespace Mgn\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mgn\ForumBundle\Entity\Mark
 *
 * @ORM\Table(name="mgn_forum_mark")
 * @ORM\Entity(repositoryClass="Mgn\ForumBundle\Entity\MarkRepository")
 */
class Mark
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
     * @var integer $forum
     *
     * @ORM\ManyToOne(targetEntity="Mgn\ForumBundle\Entity\Forum")
     */
    private $forum;

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
     * Set date
     *
     * @param \DateTime $date
     * @return Mark
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
     * @return Mark
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
     * Set forum
     *
     * @param \Mgn\ForumBundle\Entity\Forum $forum
     * @return Mark
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
}