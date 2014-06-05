<?php
namespace CMS\AdminBundle\Api;

use Symfony\Component\Yaml\Yaml;
use CMS\AdminBundle\Entity\Logs;

class SaveLogsApi
{
    public function __construct(){

    }

    public function save($em, $url, $action) {

        $logs = new Logs();
        $logs->setIpVisitor($_SERVER['REMOTE_ADDR']);
        $logs->setTimeVisit(new \DateTime());
        $logs->setHttpUserAgent($this->getBrowser());
        $logs->setLogAction($url);
        $logs->setUrlVisit($action);

        $em->persist($logs);
        $em->flush();

    }

    public function getBrowser() {
        $browsers = array("firefox", "msie", "opera", "chrome", "safari",
            "mozilla", "seamonkey", "konqueror", "netscape",
            "gecko", "navigator", "mosaic", "lynx", "amaya",
            "omniweb", "avant", "camino", "flock", "aol");

        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
            foreach ($browsers as $browser) {
                if (preg_match("#($browser)[/ ]?([0-9.]*)#", $agent, $match)) {
                    $name = $match[1];
                    if ($name == null)
                        return "application";
                    return $name;
                }
            }
        }
        return "application";
    }
}