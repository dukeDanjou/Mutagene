<?php

namespace Mgn\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Gallery
 *
 * @ORM\Entity
 * @ORM\Table(name="mgn_media_gallery")
 * @ORM\Entity(repositoryClass="Mgn\MediaBundle\Entity\GalleryRepository")
 */
class Gallery
{
    /**
    * @var integer
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
    * @Gedmo\Slug(fields={"url"})
    * @ORM\Column(length=128, unique=true)
    */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="countPicture", type="bigint")
     */
    private $countPicture;

    /**
     * @ORM\OneToMany(targetEntity="Mgn\MediaBundle\Entity\Picture", mappedBy="gallery")
     */
    private $pictures;

    /**
     * @var boolean
     *
     * @ORM\Column(name="publicAclView", type="boolean")
     */
    private $publicAclView;

    public function __construct()
    {
        $this->countPicture = 0;
        $this->publicAclView = true;
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
     * @return Gallery
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
     * Set url
     *
     * @param string $url
     * @return Gallery
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
     * Set slug
     *
     * @param string $slug
     * @return Gallery
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
     * @return Gallery
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
     * Set countPicture
     *
     * @param integer $countPicture
     * @return Gallery
     */
    public function setCountPicture($countPicture)
    {
        $this->countPicture = $countPicture;
    
        return $this;
    }

    /**
     * Get countPicture
     *
     * @return integer 
     */
    public function getCountPicture()
    {
        return $this->countPicture;
    }

    /**
     * Add pictures
     *
     * @param \Mgn\MediaBundle\Entity\Picture $pictures
     * @return Gallery
     */
    public function addPicture(\Mgn\MediaBundle\Entity\Picture $pictures)
    {
        $this->pictures[] = $pictures;
    
        return $this;
    }

    /**
     * Remove pictures
     *
     * @param \Mgn\MediaBundle\Entity\Picture $pictures
     */
    public function removePicture(\Mgn\MediaBundle\Entity\Picture $pictures)
    {
        $this->pictures->removeElement($pictures);
    }

    /**
     * Get pictures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Set publicAclView
     *
     * @param boolean $publicAclView
     * @return Gallery
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

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/medias/'.$this->slug;
    }
}