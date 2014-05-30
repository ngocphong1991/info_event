<?php

namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * GroupArticle
 *
 * @ORM\Table(name="special_group_article")
 * @ORM\Entity(repositoryClass="CMS\AdminBundle\Entity\SpecialGroupArticleRepository")
 * @UniqueEntity(
 *     fields={"name"},
 *     message="This name is already in your database."
 * )
 */
class SpecialGroupArticle
{
    const ACTIVE_YES = true;
    const ACTIVE_NO = false;

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
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=false)
     */
    private $position;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_entries", type="integer", nullable=false)
     */
    private $maxEntries;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_active", type="smallint", length=1, nullable=false)
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="specialGroupArticle")
     */
    protected $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->isActive = self::ACTIVE_NO;
    }

    public function __toString()
    {
        return $this->name;
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
     * @return SpecialGroupArticle
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get maxEntries
     *
     * @return integer
     */
    public function getMaxEntries()
    {
        return $this->maxEntries;
    }

    /**
     * Set maxEntries
     *
     * @param integer $maxEntries
     * @return SpecialGroupArticle
     */
    public function setMaxEntries($maxEntries)
    {
        $this->maxEntries = $maxEntries;

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
     * Set isActive
     *
     * @param string $isActive
     * @return SpecialGroupArticle
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
        if($this->isActive && $this->isActive == 1){
            $this->isActive = self::ACTIVE_YES;
        }else
            $this->isActive = self::ACTIVE_NO;

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
     * Set parentId
     *
     * @param integer $position
     * @return SpecialGroupArticle
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
     * Add articles
     *
     * @param \CMS\AdminBundle\Entity\Article $articles
     * @return SpecialGroupArticle
     */
    public function addArticle(\CMS\AdminBundle\Entity\Article $articles)
    {
        $this->articles[] = $articles;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \CMS\AdminBundle\Entity\Article $articles
     */
    public function removeArticle(\CMS\AdminBundle\Entity\Article $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }
}