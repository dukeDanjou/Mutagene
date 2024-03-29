<?php

namespace Mgn\MediaBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GalleryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PictureRepository extends EntityRepository
{
	public function findPicturesByGallery($id)
	{
		$qb = $this->createQueryBuilder('p');
		$qb->leftjoin('p.gallery', 'g');
		$qb->where('g.id = :id')
			->setParameter('id', $id);
	                // Enfin, on retourne le résultat.
	        return $qb->getQuery()
	                   ->getResult();
       
	}
}