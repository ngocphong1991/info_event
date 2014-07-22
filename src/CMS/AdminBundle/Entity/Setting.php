<?php

namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use CMS\AdminBundle\Api\ImageResizeApi;
/**
 * Setting
 *
 * @ORM\Table(name="setting")
 * @ORM\Entity(repositoryClass="CMS\AdminBundle\Entity\SettingRepository")
 * @ORM\HasLifecycleCallbacks()
 *  * @UniqueEntity(
 *     fields={"websiteName"},
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
     * @ORM\Column(name="website_name", type="string", length=255, unique=true, nullable=false)
     */
    private $websiteName;

    /**
     * @ORM\Column(name="website_description", type="text", length=255, unique=false, nullable=true)
     */
    private $websiteDescription;

    /**
     * @ORM\Column(name="website_banner", type="string", length=255, unique=true, nullable=true)
     */
    private $websiteBanner;

    /**
     * @ORM\Column(name="website_banner_bottom", type="string", length=255, unique=true, nullable=true)
     */
    private $websiteBannerBottom;

    /**
     * @ORM\Column(name="website_logo", type="string", length=255, unique=true, nullable=true)
     */
    private $websiteLogo;

    /**
     * @ORM\Column(name="website_copyright", type="text", nullable=true)
     */
    private $websiteCopyright;

    /**
     * @ORM\Column(name="website_app_api_facebook", type="string", length=255, unique=true, nullable=true)
     */
    private $websiteAppApiFacebook;

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
    private $fileBottom;

    private $tempBottom;

    /**
     * @Assert\File(
     *     maxSize = "6000000",
     *     mimeTypes = {"image/ico", "image/icon", "text/ico", "application/ico", "image/x-icon", "image/png"},
     *     mimeTypesMessage = "Please upload a valid Icon and smaller 6 Mb"
     * )
     */
    private $fileLogo;

    private $tempLogo;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $websiteAppApiFacebook
     */
    public function setWebsiteAppApiFacebook($websiteAppApiFacebook)
    {
        $this->websiteAppApiFacebook = $websiteAppApiFacebook;
    }

    /**
     * @return mixed
     */
    public function getWebsiteAppApiFacebook()
    {
        return $this->websiteAppApiFacebook;
    }

    /**
     * @param mixed $websiteBanner
     */
    public function setWebsiteBanner($websiteBanner)
    {
        $this->websiteBanner = $websiteBanner;
    }

    /**
     * @return mixed
     */
    public function getWebsiteBanner()
    {
        return $this->websiteBanner;
    }

    /**
     * @param mixed $websiteBannerBottom
     */
    public function setWebsiteBannerBottom($websiteBannerBottom)
    {
        $this->websiteBannerBottom = $websiteBannerBottom;
    }

    /**
     * @return mixed
     */
    public function getWebsiteBannerBottom()
    {
        return $this->websiteBannerBottom;
    }

    /**
     * @param mixed $websiteCopyright
     */
    public function setWebsiteCopyright($websiteCopyright)
    {
        $this->websiteCopyright = $websiteCopyright;
    }

    /**
     * @return mixed
     */
    public function getWebsiteCopyright()
    {
        return $this->websiteCopyright;
    }

    /**
     * @param mixed $websiteDescription
     */
    public function setWebsiteDescription($websiteDescription)
    {
        $this->websiteDescription = $websiteDescription;
    }

    /**
     * @return mixed
     */
    public function getWebsiteDescription()
    {
        return $this->websiteDescription;
    }

    /**
     * @param mixed $websiteLogo
     */
    public function setWebsiteLogo($websiteLogo)
    {
        $this->websiteLogo = $websiteLogo;
    }

    /**
     * @return mixed
     */
    public function getWebsiteLogo()
    {
        return $this->websiteLogo;
    }

    /**
     * @param mixed $websiteName
     */
    public function setWebsiteName($websiteName)
    {
        $this->websiteName = $websiteName;
    }

    /**
     * @return mixed
     */
    public function getWebsiteName()
    {
        return $this->websiteName;
    }

    public function getAbsolutePath()
    {
        return null === $this->websiteBanner
            ? null
            : $this->getUploadRootDir().'/'.$this->websiteBanner;
    }

    public function getAbsoluteThumbPath()
    {
        return null === $this->websiteBanner
            ? null
            : $this->getUploadRootDir().'/260xYYY/'.$this->websiteBanner;
    }

    public function getWebPath()
    {
        return null === $this->websiteBanner
            ? null
            : $this->getUploadDir().'/'.$this->websiteBanner;
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
        return 'uploads/banner';
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
        if (isset($this->websiteBanner)) {
            // store the old name to delete after the update
            $this->temp = $this->websiteBanner;
            $this->websiteBanner = null;
        } else {
            $this->websiteBanner = 'initial';
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
            $this->websiteBanner = $filename.'.'.$this->getFile()->guessExtension();
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
        $this->getFile()->move($this->getUploadRootDir(), $this->websiteBanner);

        // resize images
        $imagePath = $this->getUploadRootDir();
        $thumbPath = $this->getUploadRootDir().'/260xYYY';
        $fs = new Filesystem();
        if(!$fs->exists($thumbPath)){
            try {
                $fs->mkdir($thumbPath);
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at ".$e->getPath();
            }
        }
        $thumb = new ImageResizeApi($imagePath, $thumbPath, $this->websiteBanner, 's_'.$this->websiteBanner, 260, 0, 100);
        $thumb->resize();

        // check if we have an old image
        if (isset($this->temp) && $this->temp) {
            // delete the old image
            if(file_exists($this->getUploadRootDir().'/'.$this->temp))
                unlink($this->getUploadRootDir().'/'.$this->temp);

            if(file_exists($this->getUploadRootDir().'/260xYYY/s_'.$this->temp))
                unlink($this->getUploadRootDir().'/260xYYY/s_'.$this->temp);
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

    public function getAbsolutePathBottom()
    {
        return null === $this->websiteBannerBottom
            ? null
            : $this->getUploadRootDir().'/'.$this->websiteBannerBottom;
    }

    public function getAbsoluteThumbPathBottom()
    {
        return null === $this->websiteBannerBottom
            ? null
            : $this->getUploadRootDir().'/176xYYY/'.$this->websiteBannerBottom;
    }

    public function getWebPathBottom()
    {
        return null === $this->websiteBannerBottom
            ? null
            : $this->getUploadDir().'/'.$this->websiteBannerBottom;
    }

    protected function getUploadRootDirBottom()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDirBottom();
    }

    protected function getUploadDirBottom()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/banner/bottom';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFileBottom(UploadedFile $file = null)
    {
        $this->fileBottom = $file;
        // check if we have an old image path
        if (isset($this->websiteBannerBottom)) {
            // store the old name to delete after the update
            $this->tempBottom = $this->websiteBannerBottom;
            $this->websiteBannerBottom = null;
        } else {
            $this->websiteBannerBottom = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFileBottom()
    {
        return $this->fileBottom;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadBottom()
    {
        if (null !== $this->getFileBottom()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->websiteBannerBottom = $filename.'.'.$this->getFileBottom()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadBottom()
    {
        if (null === $this->getFileBottom()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFileBottom()->move($this->getUploadRootDirBottom(), $this->websiteBannerBottom);

        // resize images
        $imagePath = $this->getUploadRootDirBottom();
        $thumbPath = $this->getUploadRootDirBottom().'/176xYYY';
        $fs = new Filesystem();
        if(!$fs->exists($thumbPath)){
            try {
                $fs->mkdir($thumbPath);
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at ".$e->getPath();
            }
        }
        $thumb = new ImageResizeApi($imagePath, $thumbPath, $this->websiteBannerBottom, 's_'.$this->websiteBannerBottom, 176, 0, 100);
        $thumb->resize();

        // check if we have an old image
        if (isset($this->tempBottom) && $this->tempBottom) {
            // delete the old image
            if(file_exists($this->getUploadRootDirBottom().'/'.$this->tempBottom))
                unlink($this->getUploadRootDirBottom().'/'.$this->tempBottom);

            if(file_exists($this->getUploadRootDirBottom().'/176xYYY/s_'.$this->tempBottom))
                unlink($this->getUploadRootDirBottom().'/176xYYY/s_'.$this->tempBottom);
            // clear the temp image path
            $this->tempBottom = null;
        }
        $this->fileBottom = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUploadBottom()
    {
        if ($file = $this->getAbsolutePathBottom()) {
            if(file_exists($file))
                unlink($file);
        }

        if ($thumb = $this->getAbsoluteThumbPathBottom()) {
            if(file_exists($thumb))
                unlink($thumb);
        }
    }

    public function getAbsolutePathLogo()
    {
        return null === $this->websiteLogo
            ? null
            : $this->getUploadRootDir().'/'.$this->websiteLogo;
    }

    public function getAbsoluteThumbPathLogo()
    {
        return null === $this->websiteLogo
            ? null
            : $this->getUploadRootDir().'/logo/'.$this->websiteLogo;
    }

    public function getWebPathLogo()
    {
        return null === $this->websiteLogo
            ? null
            : $this->getUploadDir().'/'.$this->websiteLogo;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFileLogo(UploadedFile $file = null)
    {
        $this->fileLogo = $file;
        // check if we have an old image path
        if (isset($this->websiteLogo)) {
            // store the old name to delete after the update
            $this->tempLogo = $this->websiteLogo;
            $this->websiteLogo = null;
        } else {
            $this->websiteLogo = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFileLogo()
    {
        return $this->fileLogo;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadLogo()
    {
        if (null !== $this->getFileLogo()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->websiteLogo = $filename.'.'.$this->getFileLogo()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadActive()
    {
        if (null === $this->getFileLogo()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFileLogo()->move($this->getUploadRootDir(), $this->websiteLogo);

        // resize images
        $thumbPath = $this->getUploadRootDir().'/logo';
        $fs = new Filesystem();
        if(!$fs->exists($thumbPath)){
            try {
                $fs->mkdir($thumbPath);
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at ".$e->getPath();
            }
        }

        // check if we have an old image
        if (isset($this->tempLogo) && $this->tempLogo) {
            // delete the old image
            if(file_exists($this->getUploadRootDir().'/'.$this->tempLogo))
                unlink($this->getUploadRootDir().'/'.$this->tempLogo);

            if(file_exists($this->getUploadRootDir().'/logo/s_'.$this->tempLogo))
                unlink($this->getUploadRootDir().'/logo/s_'.$this->tempLogo);
            // clear the temp image path
            $this->tempLogo = null;
        }
        $this->fileLogo = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUploadLogo()
    {
        if ($file = $this->getAbsolutePathLogo()) {
            if(file_exists($file))
                unlink($file);
        }

        if ($thumb = $this->getAbsoluteThumbPathLogo()) {
            if(file_exists($thumb))
                unlink($thumb);
        }
    }

}
