<?php

	namespace App\Repository;

	use App\Entity\Product;
	use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
	use Doctrine\Persistence\ManagerRegistry;
	use Doctrine\ORM\Query;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


	public function findByProductIdPaginate($id): Query
	{
		
		$qb = $this->createQueryBuilder('p')
					->select('p')
					->leftJoin('p.categorie', 'pc')
					->where('pc.id = :id')
					->setParameter('id', $id)
					->orderBy('p.updated_at', 'DESC')
					->getQuery();
		
		return $qb;
		
	}
	
	public function findAllPaginate(): Query
	{
		
		$qb = $this->createQueryBuilder('p')
					->select('p')
					->orderBy('p.updated_at', 'DESC')
					->getQuery();
		
		return $qb;
		
	}
    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
