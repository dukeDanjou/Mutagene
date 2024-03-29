<?php

namespace Mgn\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ConfigRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConfigRepository extends EntityRepository
{
	public function FindConfigAndTheme()
	{
		$qb = $this->createQueryBuilder('c');
		$qb->leftjoin('c.theme', 't');
		$qb->where('c.cms = :cms')
			->setParameter('cms', 'mutagene');
	                // Enfin, on retourne le résultat.
	        return $qb->getQuery()
	                   ->getResult();
	}
}
