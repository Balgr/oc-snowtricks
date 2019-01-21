<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findByEmail($email): ?User
    {
        // the "p" is an alias you'll use in the rest of the query
        return $this->createQueryBuilder('p')
            ->andWhere('p.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;
        // to get just one result:
        // $product = $qb->setMaxResults(1)->getOneOrNullResult();
    }

    public function findByToken($token): ?User
    {
        // the "p" is an alias you'll use in the rest of the query
        return $this->createQueryBuilder('p')
            ->andWhere('p.resetToken = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult()
            ;
        // to get just one result:
        // $product = $qb->setMaxResults(1)->getOneOrNullResult();
    }

    public function getAll() {
        return $this->createQueryBuilder('p')
            ->getQuery()
            ->getArrayResult();
    }

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
