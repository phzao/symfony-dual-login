<?php

namespace App\Repository;

use App\Entity\ApiToken;
use App\Repository\Interfaces\ApiTokenRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class UserRepository
 * @package App\Repository
 */
class ApiTokenRepository extends BaseRepository implements ApiTokenRepositoryInterface
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
        $this->objectRepository = $this
                                        ->entityManager
                                        ->getRepository(ApiToken::class);
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
     * @param string $user_id
     *
     * @return ApiToken|mixed
     */
    public function getTheLastTokenByUser(string $user_id): ?ApiToken
    {
        return $this->objectRepository->findOneBy(["usuario" => $user_id, "expired_at" => null]);
    }
}
