<?php

namespace PostparcBundle\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Cocur\Slugify\Slugify;

/**
 * TerritoryTypeRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TerritoryTypeRepository extends NestedTreeRepository
{
    public function search($filter)
    {
        $dql = $this->createQueryBuilder('tt')->select('tt.id, tt.level, tt.name, tt.root, tt.lft, tt.rgt, tt.slug, u.id as creatorId, u.username')
            ->leftJoin('tt.createdBy', 'u')
            ->orderBy('tt.root, tt.lft', 'ASC');
        $slugify = new Slugify();
        if (isset($filter['name']) && '' != $filter['name']) {
            $slug = $slugify->slugify($filter['name'], '-');
            $dql->andwhere('tt.slug LIKE \'%' . $slug . '%\'');
        }
        (array_key_exists('updatedBy', $filter) && $filter['updatedBy']) ? $dql->andwhere("tt.updatedBy = '" . $filter['updatedBy'] . "'") : '';

        return $query = $this->_em->createQuery($dql);
    }

    public function batchDelete($ids = null, $entityId = null)
    {
        if ($ids) {
            $dql = $this->createQueryBuilder('tt')->delete('PostparcBundle\Entity\TerritoryType tt')->where('tt.id IN (' . implode(',', $ids) . ')');

            if ($entityId) {
                $dql->andWhere('tt.entity=' . $entityId);
            }

            //queries execution
            $this->_em->createQuery($dql)->execute();
        }
    }

    public function autoComplete($q, $page_limit = 30, $page = null)
    {
        $dql = $this->createQueryBuilder('tt')
            ->orderby('tt.name', 'ASC')
        ;
        if ($q) {
            $slugify = new Slugify();
            $slug = $slugify->slugify($q, '-');
            $dql->andwhere('tt.slug LIKE \'%' . $slug . '%\'');
        }
        $query = $this->_em->createQuery($dql);
        if ($page) {
            $query->setFirstResult(($page - 1) * $page_limit);
        }

        return $query->getResult();
    }
}
