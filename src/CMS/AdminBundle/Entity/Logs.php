<?php

namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Logs
 *
 * @ORM\Table(name="logs")
 * @ORM\Entity
 */
class Logs
{
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
     * @ORM\Column(name="ip_visitor", type="string", length=255, nullable=false)
     */
    private $ipVisitor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_visit", type="datetime", nullable=false)
     */
    private $timeVisit;

    /**
     * @var string
     *
     * @ORM\Column(name="log_action", type="string", length=255, nullable=true)
     */
    private $logAction;

    /**
     * @var string
     *
     * @ORM\Column(name="url_visit", type="string", length=255, nullable=true)
     */
    private $urlVisit;

    /**
     * @var string
     *
     * @ORM\Column(name="http_user_agent", type="string", length=255, nullable=true)
     */
    private $httpUserAgent;



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
     * Set ipVisitor
     *
     * @param string $ipVisitor
     * @return Logs
     */
    public function setIpVisitor($ipVisitor)
    {
        $this->ipVisitor = $ipVisitor;

        return $this;
    }

    /**
     * Get ipVisitor
     *
     * @return string 
     */
    public function getIpVisitor()
    {
        return $this->ipVisitor;
    }

    /**
     * Set timeVisit
     *
     * @param \DateTime $timeVisit
     * @return Logs
     */
    public function setTimeVisit($timeVisit)
    {
        $this->timeVisit = $timeVisit;

        return $this;
    }

    /**
     * Get timeVisit
     *
     * @return \DateTime 
     */
    public function getTimeVisit()
    {
        return $this->timeVisit;
    }

    /**
     * Set logAction
     *
     * @param string $logAction
     * @return Logs
     */
    public function setLogAction($logAction)
    {
        $this->logAction = $logAction;

        return $this;
    }

    /**
     * Get logAction
     *
     * @return string 
     */
    public function getLogAction()
    {
        return $this->logAction;
    }

    /**
     * Set urlVisit
     *
     * @param string $urlVisit
     * @return Logs
     */
    public function setUrlVisit($urlVisit)
    {
        $this->urlVisit = $urlVisit;

        return $this;
    }

    /**
     * Get urlVisit
     *
     * @return string 
     */
    public function getUrlVisit()
    {
        return $this->urlVisit;
    }

    /**
     * Set httpUserAgent
     *
     * @param string $httpUserAgent
     * @return Logs
     */
    public function setHttpUserAgent($httpUserAgent)
    {
        $this->httpUserAgent = $httpUserAgent;

        return $this;
    }

    /**
     * Get httpUserAgent
     *
     * @return string 
     */
    public function getHttpUserAgent()
    {
        return $this->httpUserAgent;
    }
}
