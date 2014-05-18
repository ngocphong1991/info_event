<?php

namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use CMS\AdminBundle\Api\ImageResizeApi;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="CMS\AdminBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"title", "url"},
 *     message="This information is already in your database."
 * )
 */
class Article
{
    const ACTIVE_YES = 1;
    const ACTIVE_NO = 0;
    const SPECIAL_YES = 1;
    const SPECIAL_NO = 0;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
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
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="sort_description", type="text", nullable=false)
     */
    private $sortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false, unique=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    private $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="datetime", nullable=true)
     * @Assert\DateTime(
     *   message = "The date start '{{ value }}' is not a valid date time."
     * )
     */
    private $dateStart;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_group_article", type="integer", nullable=true)
     */
    private $idGroupArticle;

    /**
     * @var integer
     *
     * @ORM\Column(name="views", type="bigint", nullable=false)
     */
    private $views;
     
    /**
     * @var integer
     *
     * @ORM\Column(name="is_active", type="smallint", length=1, nullable=false)
     */
    private $isActive;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="is_special", type="smallint", length=1, nullable=false)
     */
    private $isSpecial;
    
    /**
    * @ORM\ManyToOne(targetEntity="GroupArticle", inversedBy="articles")
    * @ORM\JoinColumn(name="id_group_article", referencedColumnName="id")
    */
    protected $groupArticle;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles")
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity="SpecialGroupArticle", inversedBy="articles")
     *
     */
    private $specialGroupArticle;

    /**
     * @Assert\File(
     *     maxSize = "6000000",
     *     mimeTypes = {"image/jpeg", "image/jpg", "image/png"},
     *     mimeTypesMessage = "Please upload a valid Avatar (.png, .jpg, .jpeg) and smaller 2 Mb"
     * )
     */
    private $file;

    private $temp;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->specialGroupArticle = new ArrayCollection();
        $this->isActive = self::ACTIVE_NO;
        $this->isSpecial = self::ACTIVE_NO;
    }

    public  function  __toString(){
        return $this->title;
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
     * @return Article
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
     * Set description
     *
     * @param string $description
     * @return Article
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
     * Set sortDescription
     *
     * @param string $description
     * @return Article
     */
    public function setSortDescription($sortDescription)
    {
        $this->sortDescription = $sortDescription;

        return $this;
    }

    /**
     * Get sortDescription
     *
     * @return string
     */
    public function getSortDescription()
    {
        return $this->sortDescription;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Article
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
     * Set image
     *
     * @param string $image
     * @return Article
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     * @return Article
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
     * Set DateStart
     *
     * @param \DateTime $dateStart
     * @return Article
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get DateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set idGroupArticle
     *
     * @param integer $idGroupArticle
     * @return Article
     */
    public function setIdGroupArticle($idGroupArticle)
    {
        $this->idGroupArticle = $idGroupArticle;

        return $this;
    }

    /**
     * Get idGroupArticle
     *
     * @return integer
     */
    public function getIdGroupArticle()
    {
        return $this->idGroupArticle;
    }

    /**
     * Set views
     *
     * @param integer $views
     * @return Article
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set isActive
     *
     * @param string $isActive
     * @return Article
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
     * Set isSpecial
     *
     * @param string $isSpecial
     * @return Article
     */
    public function setIsSpecial($isSpecial)
    {
        if (!in_array($isSpecial, array(self::SPECIAL_YES, self::SPECIAL_NO))) {
            throw new \InvalidArgumentException("Invalid Special");
        }
        $this->isSpecial = $isSpecial;

        return $this;
    }

    /**
     * Get isSpecial
     *
     * @return string
     */
    public function getIsSpecial()
    {
        return $this->isSpecial;
    }

    /**
     * Get isSpecialType
     *
     * @return array
     */
    public static function getIsSpecialTypes()
    {
        return array(
            self::ACTIVE_YES => 'Yes',
            self::ACTIVE_NO => 'No'
        );
    }
    /**
     * Set groupArticle
     *
     * @param \CMS\AdminBundle\Entity\GroupArticle $groupArticle
     * @return Article
     */
    public function setGroupArticle(\CMS\AdminBundle\Entity\GroupArticle $groupArticle = null)
    {
        $this->groupArticle = $groupArticle;

        return $this;
    }

    /**
     * Get groupArticle
     *
     * @return \CMS\AdminBundle\Entity\GroupArticle
     */
    public function getGroupArticle()
    {
        return $this->groupArticle;
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
     * @ORM\PrePersist
     */
    public function setViewValue()
    {
        if (!$this->getViews())
        {
            $this->views = 0;
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->image
            ? null
            : $this->getUploadRootDir().'/'.$this->image;
    }

    public function getAbsoluteThumbPath()
    {
        return null === $this->image
            ? null
            : $this->getUploadRootDir().'/145x145/'.$this->image;
    }

    public function getWebPath()
    {
        return null === $this->image
            ? null
            : $this->getUploadDir().'/'.$this->image;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/article/';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->image)) {
            // store the old name to delete after the update
            $this->temp = $this->image;
            $this->image = null;
        } else {
            $this->image = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

   /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->image = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->image);

        // resize images to thumbnail
        $imagePath = $this->getUploadRootDir();
        $thumbPath = $this->getUploadRootDir().'/145x145';
        $fs = new Filesystem();
        if(!$fs->exists($thumbPath)){
            try {
                $fs->mkdir($thumbPath);
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at ".$e->getPath();
            }
        }
        $thumb = new ImageResizeApi($imagePath, $thumbPath, $this->image, 's_'.$this->image, 145, 1, 100);
        $thumb->resize();

        // resize images to small image
        $smallPath = $this->getUploadRootDir().'/300x300';
        $fs = new Filesystem();
        if(!$fs->exists($smallPath)){
            try {
                $fs->mkdir($smallPath);
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at ".$e->getPath();
            }
        }
        $small = new ImageResizeApi($imagePath, $smallPath, $this->image, 'm_'.$this->image, 300, 0, 100);
        $small->resize();

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            if(file_exists($this->getUploadRootDir().'/'.$this->temp))
                unlink($this->getUploadRootDir().'/'.$this->temp);

            if(file_exists($this->getUploadRootDir().'/145x145/s_'.$this->temp))
                unlink($this->getUploadRootDir().'/145x145/s_'.$this->temp);

            if(file_exists($this->getUploadRootDir().'/300x300/m_'.$this->temp))
                unlink($this->getUploadRootDir().'/300x300/m_'.$this->temp);

            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            if(file_exists($file))
            unlink($file);
        }

        if ($thumb = $this->getAbsoluteThumbPath()) {
            if(file_exists($thumb))
                unlink($thumb);
        }
    }

    /**
     * Add tags
     *
     * @param \CMS\AdminBundle\Entity\Tag $tags
     * @return Article
     */
    public function addTag(\CMS\AdminBundle\Entity\Tag $tags)
    {
        $tags->addArticle($this);
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \CMS\AdminBundle\Entity\Tag $tags
     */
    public function removeTag(\CMS\AdminBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add specialGroupArticle
     *
     * @param \CMS\AdminBundle\Entity\SpecialGroupArticle $tags
     * @return Article
     */
    public function addSpecialGroupArticle(\CMS\AdminBundle\Entity\SpecialGroupArticle $specialGroupArticle)
    {
        $this->specialGroupArticle[] = $specialGroupArticle;

        return $this;
    }

    /**
     * Remove specialGroupArticle
     *
     * @param \CMS\AdminBundle\Entity\SpecialGroupArticle $tags
     */
    public function removeSpecialGroupArticle(\CMS\AdminBundle\Entity\SpecialGroupArticle $specialGroupArticle)
    {
        $this->specialGroupArticle->removeElement($specialGroupArticle);
    }

    /**
     * Get specialGroupArticle
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecialGroupArticle()
    {
        return $this->specialGroupArticle;
    }
}