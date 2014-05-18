<?php
namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository
{
    public function findAllSql()
    {
         return $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM CMSAdminBundle:Role r ORDER BY r.id DESC'
            );
    }

    public function findByKeywordSql($keyword = '')
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT r FROM CMSAdminBundle:Role r  WHERE r.name LIKE :keyword ORDER BY r.id DESC"
            )->setParameter('keyword', '%'.$keyword.'%');
    }
}
?>