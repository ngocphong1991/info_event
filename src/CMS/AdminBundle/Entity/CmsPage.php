<?php

namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * CmsPage
 *
 * @ORM\Table(name="cms_page")
 * @ORM\Entity(repositoryClass="CMS\AdminBundle\Entity\CmsPageRepository")
 * @ORM\HasLifecycleCallbacks()
 *  * @UniqueEntity(
 *     fields={"title", "url"},
 *     message="This information is already in your database."
 * )
 */
class CmsPage
{
    const ACTIVE_YES = 1;
    const ACTIVE_NO = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="sort_desciption", type="string", length=500, nullable=false)
     */
    private $sortDesciption;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false, unique=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=255, nullable=false)
     */
    private $keywords;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    private $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @var string
     *
     * @ORM\Column(name="is_active", type="smallint", length=1, nullable=false)
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="options", type="text", nullable=true)
     */
    private $options;

    public function __construct()
    {
        $this->isActive = self::ACTIVE_NO;
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
     * @return CmsPage
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
     * Set sortDesciption
     *
     * @param string $sortDesciption
     * @return CmsPage
     */
    public function setSortDesciption($sortDesciption)
    {
        $this->sortDesciption = $sortDesciption;

        return $this;
    }

    /**
     * Get sortDesciption
     *
     * @return string 
     */
    public function getSortDesciption()
    {
        return $this->sortDesciption;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return CmsPage
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
     * Set url
     *
     * @param string $url
     * @return CmsPage
     */
    public function setUrl($url)
    {
        // Lower case the string and remove whitespace from the beginning or end
        $str = trim(strtolower($url));

        // Remove single quotes from the string
        $str = str_replace("'", '', $str);

        // Every character other than a-z, 0-9 will be replaced with a single dash (-)
        $str = preg_replace("/[^a-z0-9.]+/", '-', $str);

        // Remove any beginning or trailing dashes
        $str = trim($str, '-');

        $this->url = $str;

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
     * Set keywords
     *
     * @param string $keywords
     * @return CmsPage
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return CmsPage
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime 
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDateCreatedValue()
    {
        if (!$this->getDateCreate())
        {
            $this->dateCreate = new \DateTime();
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setDateUpdateValue()
    {
        if (!$this->getDateUpdate())
        {
            $this->dateUpdate = new \DateTime();
        }
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * @return CmsPage
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime 
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set isActive
     *
     * @param string $isActive
     * @return CmsPage
     */
    public function setIsActive($isActive)
    {
        if (!in_array($isActive, array(self::ACTIVE_YES, self::ACTIVE_NO))) {
            throw new \InvalidArgumentException("Invalid active");
        }
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return string 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Get isActiveType
     *
     * @return array
     */
    public static function getIsActiveTypes()
    {
        return array(
            self::ACTIVE_YES => 'Yes',
            self::ACTIVE_NO => 'No'
        );
    }

    /**
     * Set options
     *
     * @param string $options
     * @return CmsPage
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options
     *
     * @return string 
     */
    public function getOptions()
    {
        return $this->options;
    }
}
