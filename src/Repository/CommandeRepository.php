<?php

	namespace App\Repository;

	use App\Entity\Commande;
	use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
	use Doctrine\Persistence\ManagerRegistry;
	use Doctrine\ORM\Query;

	/**
	 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
	 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
	 * @method Commande[]    findAll()
	 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
	 */
	class CommandeRepository extends ServiceEntityRepository
	{
		public function __construct(ManagerRegistry $registry)
		{
			parent::__construct($registry, Commande::class);
		}
		
		public function findAllPaginate(): ?Query
		{
			
			$qb = $this->createQueryBuilder('c')
						->select('c')
						->where('c.status = false')
						->orderBy('c.created_at', 'ASC')
						->getQuery();
			
			return $qb;
			
		}

		// /**
		//  * @return Commande[] Returns an array of Commande objects
		//  */
		/*
		public function findByExampleField($value)
		{
			return $this->createQueryBuilder('c')
				->andWhere('c.exampleField = :val')
				->setParameter('val', $value)
				->orderBy('c.id', 'ASC')
				->setMaxResults(10)
				->getQuery()
				->getResult()
			;
		}
		*/

		/*
		public function findOneBySomeField($value): ?Commande
		{
			return $this->createQueryBuilder('c')
				->andWhere('c.exampleField = :val')
				->setParameter('val', $value)
				->getQuery()
				->getOneOrNullResult()
			;
		}
		*/
	}