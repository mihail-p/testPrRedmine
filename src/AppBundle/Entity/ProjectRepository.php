<?php

namespace AppBundle\Entity;

/**
 * ProjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllComments($idPr)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT c, p FROM AppBundle:Comment c
                 JOIN c.project p
                 WHERE p.id_pr = :id')
            ->setParameter('id', $idPr);
        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}