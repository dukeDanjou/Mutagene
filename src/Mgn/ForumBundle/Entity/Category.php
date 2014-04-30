<?php

namespace Mgn\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;

use Mgn\ForumBundle\Entity\Forum;

/**
 * Section
 *
 * @ORM\Entity
 * @ORM\Table(name="mgn_forum_category")
 * @ORM\Entity(repositoryClass="Mgn\ForumBundle\Entity\CategoryRepository")
 */
class Category
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
     * @var smallint $sort
     *
     * @ORM\Column(name="sort", type="smallint")
     */
    private $sort;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Mgn\ForumBundle\Entity\Forum", mappedBy="category")
     */
    private $forums;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->forums = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sort = '0';
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
     * @return Category
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
     * @return Category
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
     * Add forums
     *
     * @param \Mgn\ForumBundle\Entity\Forum $forums
     * @return Category
     */
    public function addForum(\Mgn\ForumBundle\Entity\Forum $forums)
    {
        $this->forums[] = $forums;
    
        return $this;
    }

    /**
     * Remove forums
     *
     * @param \Mgn\ForumBundle\Entity\Forum $forums
     */
    public function removeForum(\Mgn\ForumBundle\Entity\Forum $forums)
    {
        $this->forums->removeElement($forums);
    }

    /**
     * Get forums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForums()
    {
        return $this->forums;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return Category
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
     * Set description
     *
     * @param string $description
     * @return Category
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
}