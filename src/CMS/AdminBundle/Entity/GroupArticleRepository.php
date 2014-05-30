<?php
namespace CMS\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

class GroupArticleRepository extends EntityRepository
{
    public function findAllSql()
    {
         return $this->getEntityManager()
            ->createQuery(
                'SELECT g FROM CMSAdminBundle:GroupArticle g ORDER BY g.id DESC'
            );
    }

    public function findSpecialSql()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT g FROM CMSAdminBundle:GroupArticle g WHERE g.isSpecial = 1 AND g.isActive = 1 ORDER BY g.position ASC'
            );
    }

    public function findMenuTopSql()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT g FROM CMSAdminBundle:GroupArticle g WHERE g.isOnTop = 1 AND g.isActive = 1 ORDER BY g.position ASC'
            );
    }

    public function findMenuBotSql()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT g FROM CMSAdminBundle:GroupArticle g WHERE g.isOnBot = 1 AND g.isActive = 1 ORDER BY g.position ASC'
            );
    }

    public function findByKeywordSql($keyword = '')
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT g FROM CMSAdminBundle:GroupArticle g  WHERE g.name LIKE :keyword ORDER BY g.id DESC"
            )->setParameter('keyword', '%'.$keyword.'%');
    }
}
?>