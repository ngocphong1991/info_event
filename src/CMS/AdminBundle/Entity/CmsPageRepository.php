<?php
namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CmsPageRepository extends EntityRepository
{
    public function findAllSql()
    {
         return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM CMSAdminBundle:CmsPage c ORDER BY c.dateCreate DESC'
            );
    }

    public function findByKeywordSql($keyword = '')
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT c FROM CMSAdminBundle:CmsPage c  WHERE c.title LIKE :keyword ORDER BY c.dateCreate DESC"
            )->setParameter('keyword', '%'.$keyword.'%');
    }
}
?>