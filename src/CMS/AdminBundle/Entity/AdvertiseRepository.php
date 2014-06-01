<?php
namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AdvertiseRepository extends EntityRepository
{
    public function findAllSql()
    {
         return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM CMSAdminBundle:Advertise a ORDER BY a.createDate DESC'
            );
    }

    public function findByKeywordSql($keyword = '')
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM CMSAdminBundle:Advertise a  WHERE a.name LIKE :keyword ORDER BY a.createDate DESC"
            )->setParameter('keyword', '%'.$keyword.'%');
    }
    
    public function findViewBestSql($position = 0, $idGroup = null)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM CMSAdminBundle:Advertise a WHERE a.idParent = :idGroup AN a.position = :position AND a.isActive = 1 ORDER BY a.createDate DESC'
            )->setParameter('position', $position)
            ->setParameter('idGroup', $idGroup)
            ->setMaxResults(1);
    }
}
?>