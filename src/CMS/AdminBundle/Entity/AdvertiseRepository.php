<?php
namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

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
        if($idGroup === null){
            return $this->getEntityManager()
                ->createQuery(
                    'SELECT a FROM CMSAdminBundle:Advertise a WHERE a.idParent IS NULL AND a.position = :pos AND a.isActive = 1 ORDER BY a.createDate DESC'
                )->setParameter('pos', $position)
                ->setMaxResults(1);
        }else{
            return $this->getEntityManager()
                ->createQuery(
                    'SELECT a FROM CMSAdminBundle:Advertise a WHERE a.idParent = :idGroup AND a.position = :pos AND a.isActive = 1 ORDER BY a.createDate DESC'
                )->setParameter('pos', $position)
                ->setParameter('idGroup', $idGroup)
                ->setMaxResults(1);
        }
    }

    public function findGlobalSql($position = 0)
    {
        $em = $this->getEntityManager();
        $statement =  $em->getConnection()->prepare('SELECT DISTINCT advertise_id FROM advertise_grouparticle');
        $statement->execute();
        $listLocalAdvertise = implode(',', $statement->fetchAll(7));
        if(!$listLocalAdvertise) $listLocalAdvertise = 0;

        return $em->createQuery(
                'SELECT a FROM CMSAdminBundle:Advertise a WHERE a.id NOT IN ('.$listLocalAdvertise.') AND a.position = :pos AND a.isActive = 1 ORDER BY a.createDate DESC'
            )
            ->setParameter('pos', $position)
            ->setMaxResults(1);
    }

    public function findHomeAdvertiseSql($position = 0)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM CMSAdminBundle:Advertise a WHERE a.idParent IS NULL AND a.position = :pos  AND a.isHomePage = 1 AND a.isActive = 1 ORDER BY a.createDate DESC'
            )->setParameter('pos', $position)
            ->setMaxResults(1);
    }

    public function findByGroupSql($idGroup  = null, $position = 0,  $maxEntries = 1)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('a, g')
            ->from('CMSAdminBundle:Advertise', 'a')
            ->innerJoin('a.groupArticle', 'g')
            ->where('g.id = :idGroup AND :pos = a.position AND a.isActive = 1')
            ->orderBy('a.createDate','DESC')
            ->setParameter('idGroup', $idGroup)
            ->setParameter('pos', $position)
            ->setMaxResults($maxEntries)
            ->getQuery();
    }
}
?>