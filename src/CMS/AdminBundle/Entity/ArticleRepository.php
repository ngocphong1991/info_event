<?php
namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function findAllSql()
    {
         return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM CMSAdminBundle:Article a ORDER BY a.dateCreate DESC'
            );
    }

    public function findViewBestSql()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM CMSAdminBundle:Article a WHERE a.isActive = 1 ORDER BY a.views DESC'
            )->setMaxResults(8);
    }

    public function findRelatedSql($idGroup, $idArticle)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM CMSAdminBundle:Article a WHERE a.id != :idArticle and a.idGroupArticle = :idGroup AND :dateNow >= a.dateStart AND a.isActive = 1 ORDER BY a.dateCreate DESC'
            )
            ->setParameter('idGroup', $idGroup)
            ->setParameter('idArticle', $idArticle)
            ->setParameter('dateNow', date('Y-m-d H:i:s'))
            ->setMaxResults(5);
    }

    public function findByGroupSql($idGroup)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM CMSAdminBundle:Article a WHERE  a.idGroupArticle = :idGroup AND :dateNow >= a.dateStart AND a.isActive = 1 ORDER BY a.dateCreate DESC'
            )->setParameter('idGroup', $idGroup)
            ->setParameter('dateNow', date('Y-m-d H:i:s'));
    }

    public function findAllByGroupSql($idGroup)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM CMSAdminBundle:Article a WHERE  a.idGroupArticle = :idGroup ORDER BY a.dateCreate DESC'
            )->setParameter('idGroup', $idGroup);
    }

    public function findBySpecialGroupSql($idSpecialGroup, $maxEntries = 0)
    {
        if($maxEntries)
            return $this->getEntityManager()->createQueryBuilder()
                ->select('a, s')
                ->from('CMSAdminBundle:Article', 'a')
                ->innerJoin('a.specialGroupArticle', 's')
                ->where('s.id = :idSpecialGroup AND :dateNow >= a.dateStart AND a.isActive = 1')
                ->orderBy('a.dateCreate','DESC')
                ->setParameter('idSpecialGroup', $idSpecialGroup)
                ->setParameter('dateNow', date('Y-m-d H:i:s'))
                ->setMaxResults($maxEntries)
                ->getQuery();
        else
            return $this->getEntityManager()->createQueryBuilder()
                ->select('a, s')
                ->from('CMSAdminBundle:Article', 'a')
                ->innerJoin('a.specialGroupArticle', 's')
                ->where('s.id = :idSpecialGroup AND :dateNow >= a.dateStart AND a.isActive = 1')
                ->orderBy('a.dateCreate','DESC')
                ->setParameter('idSpecialGroup', $idSpecialGroup)
                ->setParameter('dateNow', date('Y-m-d H:i:s'))
                ->getQuery();
    }

    public function findNewestSql()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM CMSAdminBundle:Article a WHERE :dateNow >= a.dateStart AND a.isActive = 1 ORDER BY a.dateCreate DESC'
            )->setParameter('dateNow', date('Y-m-d H:i:s'));
    }

    public function findNewestNotSliderSql($listId)
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM CMSAdminBundle:Article a WHERE a.id NOT IN ('.$listId.') AND :dateNow >= a.dateStart AND a.isActive = 1 ORDER BY a.dateCreate DESC"
            )
            ->setParameter('dateNow', date('Y-m-d H:i:s'));
    }

    public function  findNewestForSliderSql($idGroup){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM CMSAdminBundle:Article a WHERE  a.idGroupArticle = :idGroup AND :dateNow >= a.dateStart AND a.isActive = 1 ORDER BY a.dateCreate DESC'
            )->setParameter('idGroup', $idGroup)
            ->setParameter('dateNow', date('Y-m-d H:i:s'))
            ->setMaxResults(2);
    }

    public function findByKeywordSql($keyword = '')
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM CMSAdminBundle:Article a  WHERE a.title LIKE :keyword ORDER BY a.dateCreate DESC"
            )->setParameter('keyword', '%'.$keyword.'%');
    }

    public function findByKeywordFrontEndSql($keyword = '')
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM CMSAdminBundle:Article a  WHERE (a.title LIKE :keyword OR a.tags LIKE :keyword) AND :dateNow >= a.dateStart AND a.isActive = 1 ORDER BY a.dateCreate DESC"
            )->setParameter('keyword', '%'.$keyword.'%')
            ->setParameter('dateNow', date('Y-m-d H:i:s'));
    }
}
?>