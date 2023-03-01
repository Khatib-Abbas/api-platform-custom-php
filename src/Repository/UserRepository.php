<?php

namespace App\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\ApiResource\UserApi;
use App\Attribute\SearchFilter;
use App\Entity\User;
use App\Enum\SearchFilterMethodSearchEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\Persistence\ManagerRegistry;
use ReflectionClass;


/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    const ITEMS_PER_PAGE = 30;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchFilter(QueryBuilder $qb,$queryParams): QueryBuilder
    {

        /**
         * Retrieve Argument ATTRIBUTE
         */
        $class = new ReflectionClass(UserApi::class);
        $attributes = $class->getAttributes(SearchFilter::class);
        if(count($queryParams) && count($attributes)){
            $activatedFields = $attributes[0]->getArguments()[0];
            $alias =$qb->getAllAliases()[0];
            /**
             * Filters Activated on UserApi
             */
            $queryString ="";
            foreach ($activatedFields as $attributeName =>$methodSearch){
                #QueryParams declared on Uri
                foreach ($queryParams as $queryAttributeName =>$queryValues ){
                    /**
                     * Match Filters &  QueryParams
                     */
                    if($attributeName === $queryAttributeName){

                        foreach ($queryValues as $queryValue){
                            $queryString .= "LOWER($alias.$attributeName) LIKE  LOWER(:$queryValue$attributeName)";
                            $qb->setParameter("$queryValue$attributeName",$this->selectFilterLike($queryValue,$methodSearch));
                            $queryString .= " OR ";
                        }
                    }
                }
            }
            if($queryString){
                $last_space_position = strrpos($queryString, ' OR ');
                $text = substr($queryString, 0, $last_space_position);
                $qb->andWhere($text);
            }
        }
        return  $qb;
    }

    public function selectFilterLike(string $queryValue, SearchFilterMethodSearchEnum $methodSearch): string
    {
        return match($methodSearch){
            SearchFilterMethodSearchEnum::SEARCH_FILTER_METHOD_SEARCH_PARTIAL =>'%'.$queryValue.'%',
            SearchFilterMethodSearchEnum::SEARCH_FILTER_METHOD_SEARCH_END =>'%'.$queryValue,
            SearchFilterMethodSearchEnum::SEARCH_FILTER_METHOD_SEARCH_START =>$queryValue.'%',
        };
    }
    public function filterPerPage(QueryBuilder $qb,int $page = 1): Paginator
    {
        $firstResult = ($page -1) * self::ITEMS_PER_PAGE;
        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::ITEMS_PER_PAGE);
        $qb->addCriteria($criteria);
        $doctrinePaginator = new DoctrinePaginator($qb);
        return new Paginator($doctrinePaginator);
    }

    public function getOrCreateQueryBuilder(QueryBuilder $qb = null): QueryBuilder
    {
        return $qb ?: $this->createQueryBuilder('user');
    }
//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
