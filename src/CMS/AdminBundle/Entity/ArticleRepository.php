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
            )->setMaxResults(10);
    }

    public function findRelatedSql($idGroup, $idArticle)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM CMSAdminBundle:Article a WHERE a.id != :idArticle and a.idGroupArticle = :idGroup AND a.isActive = 1 ORDER BY a.dateCreate DESC'
            )
            ->setParameter('idGroup', $idGroup)
            ->setParameter('idArticle', $idArticle)
            ->setMaxResults(10);
    }

    public function findNewestSql()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM CMSAdminBundle:Article a WHERE :dateNow >= a.dateStart AND a.isActive = 1 ORDER BY a.dateCreate DESC'
            )->setParameter('dateNow', date('Y-m-d H:i:s'));
    }

    public function findByKeywordSql($keyword = '')
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT a FROM CMSAdminBundle:Article a  WHERE a.title LIKE :keyword ORDER BY a.dateCreate DESC"
            )->setParameter('keyword', '%'.$keyword.'%');
    }
}
?>