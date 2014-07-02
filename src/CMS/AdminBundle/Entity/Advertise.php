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
use CMS\AdminBundle\Api\ConvertToSlugApi;

/**
 * Advertise
 *
 * @ORM\Table(name="advertise")
 * @ORM\Entity(repositoryClass="CMS\AdminBundle\Entity\AdvertiseRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *  fields={"name"},
 *   errorPath="name",
 *   message="This name is already in use, please chose another one."
 * )
 */
class Advertise
{
    const ACTIVE_YES = true;
    const ACTIVE_NO = false;
    const POSITION_TOP = true;
    const POSITION_RIGHT = false;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_parent", type="integer", nullable=true)
     */
    private $idParent;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="cpc", type="integer", nullable=true)
     */
    private $cpc;

    /**
     * @var integer
     *
     * @ORM\Column(name="cpm", type="integer", nullable=true)
     */
    private $cpm;

    /**
     * @var integer
     *
     * @ORM\Column(name="budget", type="integer", nullable=true)
     */
    private $budget;

    /**
     * @var string
     *
     * @ORM\Column(name="key_words", type="string", length=255, nullable=true)
     */
    private $keyWords;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime", nullable=true)
     */
    private $createDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="views", type="integer", nullable=true)
     */
    private $views;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="click", type="integer", nullable=true)
     */
    private $click;

    /**
     * @var string
     *
     * @ORM\Column(name="is_active", type="smallint", length=1, nullable=true)
     */
    private $isActive;
    
    /**
     * @var string
     *
     * @ORM\Column(name="position", type="smallint", length=1, nullable=false)
     */
    private $position;

    /**
     * @ORM\ManyToMany(targetEntity="GroupArticle", inversedBy="advertises")
     */
    protected $groupArticle;

    /**
     * @Assert\File(
     *     maxSize = "6000000",
     *     mimeTypes = {"image/jpeg", "image/jpg", "image/png", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid Avatar (.png, .jpg, .jpeg, .gif) and smaller 2 Mb"
     * )
     */
    private $file;

    private $temp;
    
    public function __construct()
    {
        $this->isActive = self::ACTIVE_NO;
        $this->position = self::POSITION_RIGHT;
        $this->groupArticle = new ArrayCollection();
    }

    public  function  __toString(){
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
     * Set idParent
     *
     * @param integer $idParent
     * @return Advertise
     */
    public function setIdParent($idParent)
    {
        $this->idParent = $idParent;

        return $this;
    }

    /**
     * Get idParent
     *
     * @return integer 
     */
    public function getIdParent()
    {
        return $this->idParent;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Advertise
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
     * Set keyWords
     *
     * @param string $keyWords
     * @return Advertise
     */
    public function setKeyWords($keyWords)
    {
        $this->keyWords = $keyWords;

        return $this;
    }

    /**
     * Get keyWords
     *
     * @return string 
     */
    public function getKeyWords()
    {
        return $this->keyWords;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Advertise
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
     * Set url
     *
     * @param string $url
     * @return Advertise
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
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Advertise
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreateDateValue()
    {
        if (!$this->getCreateDate())
        {
            $this->createDate = new \DateTime();
        }
    }
    
    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return Advertise
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate()
     */
    public function setUpdateDateValue()
    {
        if (!$this->getUpdateDate())
        {
            $this->updateDate = new \DateTime();
        }
    }
    
    /**
     * Set views
     *
     * @param integer $views
     * @return Advertise
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
     * Set cpc
     *
     * @param integer $cpc
     * @return Advertise
     */
    public function setCpc($cpc)
    {
        $this->cpc = $cpc;

        return $this;
    }

    /**
     * Get cpc
     *
     * @return integer
     */
    public function getCpc()
    {
        return $this->cpc;
    }

    /**
     * Set cpm
     *
     * @param integer $cpm
     * @return Advertise
     */
    public function setCpm($cpm)
    {
        $this->cpm = $cpm;

        return $this;
    }

    /**
     * Get cpm
     *
     * @return integer
     */
    public function getCpm()
    {
        return $this->cpm;
    }

    /**
     * Set budget
     *
     * @param integer $budget
     * @return Advertise
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return integer
     */
    public function getBudget()
    {
        return $this->budget;
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
    
    /**
     * Set click
     *
     * @param integer $click
     * @return Advertise
     */
    public function setClick($click)
    {
        $this->click = $click;

        return $this;
    }

    /**
     * Get click
     *
     * @return integer 
     */
    public function getClick()
    {
        return $this->click;
    }

    /**
     * @ORM\PrePersist
     */
    public function setClickValue()
    {
        if (!$this->getClick())
        {
            $this->click = 0;
        }
    }

    /**
     * Set isActive
     *
     * @param string $isActive
     * @return Advertise
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
     * Set position
     *
     * @param string $position
     * @return Advertise
     */
    public function setPosition($position)
    {
        if (!in_array($position, array(self::POSITION_TOP, self::POSITION_RIGHT))) {
            throw new \InvalidArgumentException("Invalid position");
        }
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        if($this->position && $this->position == 1){
            $this->position = self::POSITION_TOP;
        }else
            $this->position = self::POSITION_RIGHT;

        return $this->position;
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
     * Add GroupArticle
     *
     * @param \CMS\AdminBundle\Entity\GroupArticle $groupArticle
     * @return GroupArticle
     */
    public function addGroupArticle(\CMS\AdminBundle\Entity\GroupArticle $groupArticle)
    {
        $this->groupArticle[] = $groupArticle;

        return $this;
    }

    /**
     * Remove GroupArticle
     *
     * @param \CMS\AdminBundle\Entity\GroupArticle $groupArticle
     */
    public function removeGroupArticle(\CMS\AdminBundle\Entity\GroupArticle $groupArticle)
    {
        $this->groupArticle->removeElement($groupArticle);
    }

    /**
     * Get GroupArticle
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupArticle()
    {
        return $this->groupArticle;
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
            : $this->getUploadRootDir().'/650x000/'.$this->image;
    }
    
    public function getAbsoluteSmallPath()
    {
        return null === $this->image
            ? null
            : $this->getUploadRootDir().'/300x300/'.$this->image;
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
        return 'uploads/advertises/';
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
        $thumbPath = $this->getUploadRootDir().'/650x000';
        $fs = new Filesystem();
        if(!$fs->exists($thumbPath)){
            try {
                $fs->mkdir($thumbPath);
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at ".$e->getPath();
            }
        }
        $thumb = new ImageResizeApi($imagePath, $thumbPath, $this->image, 'l_'.$this->image, 650, 0, 100);
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

            if(file_exists($this->getUploadRootDir().'/650x000/l_'.$this->temp))
                unlink($this->getUploadRootDir().'/650x000/l_'.$this->temp);

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
        
        if ($thumb = $this->getAbsoluteSmallPath()) {
            if(file_exists($thumb))
                unlink($thumb);
        }
    }
}
