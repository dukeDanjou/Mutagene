<?php

namespace Mgn\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Contents
 *
 * @ORM\Entity
 * @ORM\Table(name="mgn_content")
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 * @ORM\Entity(repositoryClass="Mgn\ContentBundle\Entity\ContentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Content
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
     * @var integer $article
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Mgn\ArticleBundle\Entity\Article", inversedBy="contents")
     * @ORM\JoinColumn(nullable=true)
     */
    private $article;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="paragraph", type="text", nullable=true)
     */
    private $paragraph;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=255, nullable=true)
     */
    private $video;

    /**
     * @var string
     *
     * @ORM\Column(name="quote", type="text", nullable=true)
     */
    private $quote;

    /**
     * @var string
     *
     * @ORM\Column(name="quoteName", type="string", length=255, nullable=true)
     */
    private $quoteName;

    /**
     * @var string
     *
     * @ORM\Column(name="quoteLink", type="string", length=255, nullable=true)
     */
    private $quoteLink;

    /**
     * @var string
     *
     * @ORM\Column(name="list", type="text", nullable=true)
     */
    private $list;

    /**
     * @var string
     *
     * @ORM\Column(name="listType", type="smallint", nullable=true)
     */
    private $listType;

    public function __construct()
    {
        $datetime = new \Datetime();
        $this->date = $datetime;
        $this->position = -1;
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
     * Set position
     *
     * @param integer $position
     * @return Content
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set paragraph
     *
     * @param string $paragraph
     * @return Content
     */
    public function setParagraph($paragraph)
    {
        $this->paragraph = $paragraph;
    
        return $this;
    }

    /**
     * Get paragraph
     *
     * @return string 
     */
    public function getParagraph()
    {
        return $this->paragraph;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Content
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
     * Set subtitle
     *
     * @param string $subtitle
     * @return Content
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    
        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Content
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set video
     *
     * @param string $video
     * @return Content
     */
    public function setVideo($video)
    {
        $this->video = $video;
    
        return $this;
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set quote
     *
     * @param string $quote
     * @return Content
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;
    
        return $this;
    }

    /**
     * Get quote
     *
     * @return string 
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * Set quoteName
     *
     * @param string $quoteName
     * @return Content
     */
    public function setQuoteName($quoteName)
    {
        $this->quoteName = $quoteName;
    
        return $this;
    }

    /**
     * Get quoteName
     *
     * @return string 
     */
    public function getQuoteName()
    {
        return $this->quoteName;
    }

    /**
     * Set quoteLink
     *
     * @param string $quoteLink
     * @return Content
     */
    public function setQuoteLink($quoteLink)
    {
        $this->quoteLink = $quoteLink;
    
        return $this;
    }

    /**
     * Get quoteLink
     *
     * @return string 
     */
    public function getQuoteLink()
    {
        return $this->quoteLink;
    }

    /**
     * Set list
     *
     * @param string $list
     * @return Content
     */
    public function setList($list)
    {
        $this->list = $list;
    
        return $this;
    }

    /**
     * Get list
     *
     * @return string 
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * Set listType
     *
     * @param integer $listType
     * @return Content
     */
    public function setListType($listType)
    {
        $this->listType = $listType;
    
        return $this;
    }

    /**
     * Get listType
     *
     * @return integer 
     */
    public function getListType()
    {
        return $this->listType;
    }

    /**
     * Set article
     *
     * @param \Mgn\ArticleBundle\Entity\Article $article
     * @return Content
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

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Content
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
     * Set type
     *
     * @param string $type
     * @return Content
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}