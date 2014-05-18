<?php

namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * CMS\AdminBundle\Entity\Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="CMS\AdminBundle\Entity\TagRepository")
 * @UniqueEntity(
 *     fields={"name"},
 *     message="This tag is already in your database."
 * )
 */
class Tag
{
    const ACTIVE_YES = 'Yes';
    const ACTIVE_NO = 'No';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55, unique=true, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="is_active", type="smallint", length=1, nullable=false)
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
     *
     */
    private $articles;

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
     * @param string %name
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Name
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
     * @return Tag
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
     * Add Article
     *
     * @param \CMS\AdminBundle\Entity\Article $articles
     * @return Tag
     */
    public function addArticle(\CMS\AdminBundle\Entity\Article $articles)
    {
        $this->articles[] = $articles;

        return $this;
    }

    /**
     * Remove Article
     *
     * @param \CMS\AdminBundle\Entity\Article $articles
     */
    public function removeArticle(\CMS\AdminBundle\Entity\Article $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get Article
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }
}