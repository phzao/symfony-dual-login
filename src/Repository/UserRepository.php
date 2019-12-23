<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @var ObjectRepository
     */
    protected $objectRepository;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $conn;

    /**
     * UserRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->conn             = $this->entityManager->getConnection();
        $this->objectRepository = $this->entityManager
                                       ->getRepository(User::class);
    }

    /**
     * @param $entity
     *
     * @return mixed|void
     */
    public function save($entity)
    {
        parent::save($entity);
    }

    /**
     * @param string $email
     *
     * @return User|mixed
     */
    public function getByEmail(string $email): ?User
    {
        return $this->objectRepository->findOneBy(['email' => $email]);
    }

    /**
     * @param string $id
     *
     * @return User|mixed
     */
    public function getByID(string $id): ? User
    {
        return $this->objectRepository->findOneBy(["id" => $id]);
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function getList(array $parameters = []): array
    {
        $res = $this
                    ->entityManager
                    ->createQuery('SELECT p.id, p.email, p.created_at FROM App\Entity\User p ORDER BY p.created_at ASC');

        return $res->getResult(Query::HYDRATE_ARRAY);
    }
}