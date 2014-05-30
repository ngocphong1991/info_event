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
 * GroupArticle
 *
 * @ORM\Table(name="group_article")
 * @ORM\Entity(repositoryClass="CMS\AdminBundle\Entity\GroupArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *  fields={"name"},
 *   errorPath="name",
 *   message="This name is already in use, please chose another one."
 * )
 * @UniqueEntity(
 *  fields={"url"},
 *   errorPath="url",
 *   message="This url is already in use, please chose another one."
 * )
 */
class GroupArticle
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
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="image_active", type="string", length=255, nullable=true)
     */
    private $imageActive;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true, unique=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="is_active", type="smallint", length=1, nullable=false)
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="is_special", type="smallint", length=1, nullable=false)
     */
    private $isSpecial;

    /**
     * @var string
     *
     * @ORM\Column(name="is_on_top", type="smallint", length=1, nullable=false)
     */
    private $isOnTop;

    /**
     * @var string
     *
     * @ORM\Column(name="is_on_bot", type="smallint", length=1, nullable=false)
     */
    private $isOnBot;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="groupArticle")
     */
    protected $articles;

    /**
     * @Assert\File(
     *     maxSize = "6000000",
     *     mimeTypes = {"image/jpeg", "image/jpg", "image/png"},
     *     mimeTypesMessage = "Please upload a valid Image (.png, .jpg, .jpeg) and smaller 6 Mb"
     * )
     */
    private $file;

    private $temp;

    /**
     * @Assert\File(
     *     maxSize = "6000000",
     *     mimeTypes = {"image/jpeg", "image/jpg", "image/png"},
     *     mimeTypesMessage = "Please upload a valid Image (.png, .jpg, .jpeg) and smaller 6 Mb"
     * )
     */
    private $fileActive;

    private $tempActive;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->isActive = self::ACTIVE_NO;
        $this->isSpecial = self::ACTIVE_NO;
        $this->isOnBot = self::ACTIVE_NO;
        $this->isOnTop = self::ACTIVE_NO;
        $this->position = 0;
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
     * @return GroupArticle
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
     * @return GroupArticle
     */
    public function setUrl($url)
    {
        $slug = new ConvertToSlugApi($url);
        $this->url = $slug->convert();

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
     * @ORM\PrePersist
     */
    public function setUrlValue()
    {
        if (!$this->getUrl())
        {
            $slug = new ConvertToSlugApi($this->name);
            $this->url = $slug->convert().'.html';
        }
    }

    /**
     * Set isActive
     *
     * @param string $isActive
     * @return GroupArticle
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
     * @return integer
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
     * Set isSpecial
     *
     * @param string $isSpecial
     * @return GroupArticle
     */
    public function setIsSpecial($isSpecial)
    {
        if (!in_array($isSpecial, array(self::ACTIVE_YES, self::ACTIVE_NO))) {
            throw new \InvalidArgumentException("Invalid special");
        }
        $this->isSpecial = $isSpecial;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return integer
     */
    public function getIsSpecial()
    {
        if($this->isSpecial && $this->isSpecial == 1){
            $this->isSpecial = self::ACTIVE_YES;
        }else
            $this->isSpecial = self::ACTIVE_NO;

        return $this->isSpecial;
    }

    /**
     * Set isOnTop
     *
     * @param string $isOnTop
     * @return GroupArticle
     */
    public function setIsOnTop($isOnTop)
    {
        if (!in_array($isOnTop, array(self::ACTIVE_YES, self::ACTIVE_NO))) {
            throw new \InvalidArgumentException("Invalid active");
        }
        $this->isOnTop = $isOnTop;

        return $this;
    }

    /**
     * Get isOnTop
     *
     * @return integer
     */
    public function getIsOnTop()
    {
        if($this->isOnTop && $this->isOnTop == 1){
            $this->isOnTop = self::ACTIVE_YES;
        }else
            $this->isOnTop = self::ACTIVE_NO;

        return $this->isOnTop;
    }

    /**
     * Set isOnBot
     *
     * @param string $isOnBot
     * @return GroupArticle
     */
    public function setIsOnBot($isOnBot)
    {
        if (!in_array($isOnBot, array(self::ACTIVE_YES, self::ACTIVE_NO))) {
            throw new \InvalidArgumentException("Invalid active");
        }
        $this->isOnBot = $isOnBot;

        return $this;
    }

    /**
     * Get isOnBot
     *
     * @return integer
     */
    public function getIsOnBot()
    {
        if($this->isOnBot && $this->isOnBot == 1){
            $this->isOnBot = self::ACTIVE_YES;
        }else
            $this->isOnBot = self::ACTIVE_NO;

        return $this->isOnBot;
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
     * Set Position
     *
     * @param string $position
     * @return GroupArticle
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get Position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     * @return GroupArticle
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Add articles
     *
     * @param \CMS\AdminBundle\Entity\Article $articles
     * @return GroupArticle
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

    /**
     * Set image
     *
     * @param string $image
     * @return GroupArticle
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
     * Set imageActive
     *
     * @param string $imageActive
     * @return GroupArticle
     */
    public function setImageActive($imageActive)
    {
        $this->imageActive = $imageActive;

        return $this;
    }

    /**
     * Get imageActive
     *
     * @return string
     */
    public function getImageActive()
    {
        return $this->imageActive;
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
        return 'uploads/group/';
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

        // resize images
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

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            if(file_exists($this->getUploadRootDir().'/'.$this->temp))
                unlink($this->getUploadRootDir().'/'.$this->temp);

            if(file_exists($this->getUploadRootDir().'/145x145/s_'.$this->temp))
                unlink($this->getUploadRootDir().'/145x145/s_'.$this->temp);
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

    public function getAbsolutePathActive()
    {
        return null === $this->imageActive
            ? null
            : $this->getUploadRootDir().'/'.$this->imageActive;
    }

    public function getAbsoluteThumbPathActive()
    {
        return null === $this->imageActive
            ? null
            : $this->getUploadRootDir().'/145x145/'.$this->imageActive;
    }

    public function getWebPathActive()
    {
        return null === $this->imageActive
            ? null
            : $this->getUploadDir().'/'.$this->imageActive;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFileActive(UploadedFile $file = null)
    {
        $this->fileActive = $file;
        // check if we have an old image path
        if (isset($this->imageActive)) {
            // store the old name to delete after the update
            $this->tempActive = $this->imageActive;
            $this->imageActive = null;
        } else {
            $this->imageActive = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFileActive()
    {
        return $this->fileActive;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadActive()
    {
        if (null !== $this->getFileActive()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->imageActive = $filename.'.'.$this->getFileActive()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadActive()
    {
        if (null === $this->getFileActive()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFileActive()->move($this->getUploadRootDir(), $this->imageActive);

        // resize images
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
        $thumb = new ImageResizeApi($imagePath, $thumbPath, $this->imageActive, 's_'.$this->imageActive, 145, 1, 100);
        $thumb->resize();

        // check if we have an old image
        if (isset($this->tempActive)) {
            // delete the old image
            if(file_exists($this->getUploadRootDir().'/'.$this->tempActive))
                unlink($this->getUploadRootDir().'/'.$this->tempActive);

            if(file_exists($this->getUploadRootDir().'/145x145/s_'.$this->tempActive))
                unlink($this->getUploadRootDir().'/145x145/s_'.$this->tempActive);
            // clear the temp image path
            $this->tempActive = null;
        }
        $this->fileActive = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUploadActive()
    {
        if ($file = $this->getAbsolutePathActive()) {
            if(file_exists($file))
                unlink($file);
        }

        if ($thumb = $this->getAbsoluteThumbPathActive()) {
            if(file_exists($thumb))
                unlink($thumb);
        }
    }

}