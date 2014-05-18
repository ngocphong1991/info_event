<?php
namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function findAllSql()
    {
         return $this->getEntityManager()
            ->createQuery(
                'SELECT t FROM CMSAdminBundle:Tag t ORDER BY t.id DESC'
            );
    }

    public function findByKeywordSql($keyword = '')
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT t FROM CMSAdminBundle:Tag t  WHERE t.name LIKE :keyword ORDER BY t.id DESC"
            )->setParameter('keyword', '%'.$keyword.'%');
    }
}
?>