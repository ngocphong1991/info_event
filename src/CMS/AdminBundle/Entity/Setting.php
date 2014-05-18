<?php

namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Setting
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CMS\AdminBundle\Entity\SettingRepository")
 * @ORM\HasLifecycleCallbacks()
 *  * @UniqueEntity(
 *     fields={"name", "path"},
 *     message="This information is already in your database."
 * )
 */
class Setting
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
     * @ORM\Column(type="string", length=255, unique=false, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=false)
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=255, unique=false, nullable=false)
     */
    private $path;


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
     * @return Setting
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
     * Set value
     *
     * @param string $value
     * @return Setting
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function geValue()
    {
        return $this->value;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return GroupArticle
     */
    public function setUrl($path)
    {
        // Lower case the string and remove whitespace from the beginning or end
        $str = trim(strtolower($path));

        // Remove single quotes from the string
        $str = str_replace("'", '', $str);

        // Every character other than a-z, 0-9 will be replaced with a single dash (-)
        $str = preg_replace("/[^a-z0-9.]+/", '_', $str);

        // Remove any beginning or trailing dashes
        $str = trim($str, '_');

        $this->path = $str;

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
     * @ORM\PrePersist
     */
    public function setPathValue()
    {
        // Lower case the string and remove whitespace from the beginning or end
        $str = trim(strtolower($this->name));

        // Remove single quotes from the string
        $str = str_replace("'", '', $str);

        // Every character other than a-z, 0-9 will be replaced with a single dash (-)
        $str = preg_replace("/[^a-z0-9.]+/", '_', $str);

        // Remove any beginning or trailing dashes
        $str = trim($str, '_');

        $this->path = $str;

    }
}
