<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Utils\Enums\GeneralTypes;
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
        $parameters = [
            'email'  => $email,
            'status' => GeneralTypes::STATUS_ENABLE
        ];
        return $this->objectRepository->findOneBy($parameters);
    }

    /**
     * @param string $id
     *
     * @return User|mixed
     */
    public function getByID(string $id): ? User
    {
        $parameters = [
            'id'     => $id,
            'status' => GeneralTypes::STATUS_ENABLE
        ];

        return $this->objectRepository->findOneBy($parameters);
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
                    ->createQuery("
                    SELECT 
                        p.id, 
                        p.email, 
                        p.created_at,
                        p.status 
                    FROM App\Entity\User p
                    WHERE p.status = '".GeneralTypes::STATUS_ENABLE."' 
                    ORDER BY p.created_at ASC");

        return $res->getResult(Query::HYDRATE_ARRAY);
    }
}