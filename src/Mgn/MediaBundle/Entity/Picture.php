<?php
// src/Mgn/MediaBundle/Entity/Picture.php
namespace Mgn\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Entity
 * @ORM\Table(name="mgn_media_picture")
 * @ORM\Entity(repositoryClass="Mgn\MediaBundle\Entity\PictureRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Picture
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @Assert\File(maxSize="2M")
     */
    public $file;

    /**
     * @var integer $userUpload
     *
     * @ORM\Column(name="userUpload", type="integer", nullable=true)
     */
    private $userUpload;

    /**
     * @var string $userIp
     *
     * @ORM\Column(name="userIp", type="string", length=255, nullable=true)
     */
    private $userIp;

    /**
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    // propriété utilisé temporairement pour le nomage
    private $userIdForName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var boolean
     *
     * @ORM\Column(name="inArticle", type="boolean")
     */
    private $inArticle;

    /**
     * @var integer $gallery
     *
     * @ORM\ManyToOne(targetEntity="Mgn\MediaBundle\Entity\Gallery", inversedBy="pictures")
     */
    private $gallery;

    public function __construct()
    {
        $this->date = new \Datetime();
        $this->inArticle = false;
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
     * Set path
     *
     * @param string $path
     * @return Picture
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set userUpload
     *
     * @param integer $userUpload
     * @return Picture
     */
    public function setUserUpload($userUpload)
    {
        $this->userUpload = $userUpload;
    
        return $this;
    }

    /**
     * Get userUpload
     *
     * @return integer 
     */
    public function getUserUpload()
    {
        return $this->userUpload;
    }

    /**
     * Set userIp
     *
     * @param string $userIp
     * @return Picture
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
     * Set date
     *
     * @param \DateTime $date
     * @return Picture
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
     * Set description
     *
     * @param string $description
     * @return Picture
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
     * Set title
     *
     * @param string $title
     * @return Picture
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
     * Set inArticle
     *
     * @param boolean $inArticle
     * @return Picture
     */
    public function setInArticle($inArticle)
    {
        $this->inArticle = $inArticle;
    
        return $this;
    }

    /**
     * Get inArticle
     *
     * @return boolean 
     */
    public function getInArticle()
    {
        return $this->inArticle;
    }

    /**
     * Set gallery
     *
     * @param \Mgn\MediaBundle\Entity\Gallery $gallery
     * @return Picture
     */
    public function setGallery(\Mgn\MediaBundle\Entity\Gallery $gallery = null)
    {
        $this->gallery = $gallery;
    
        return $this;
    }

    /**
     * Get gallery
     *
     * @return \Mgn\MediaBundle\Entity\Gallery 
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * Set userIdForName
     *
     * @param string $userIdForName
     * @return Image
     */
    public function setUserIdForName($userIdForName)
    {
        $this->userIdForName = $userIdForName;
    
        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        if (null !== $this->file)
        {
            return $this->file->guessExtension();
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
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
        return 'uploads/medias/'.$this->date->format('Y').'/'.$this->date->format('m');
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            $this->path = $this->userIdForName.time().'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }
}