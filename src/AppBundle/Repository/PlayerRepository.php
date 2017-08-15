<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Player;
use AppBundle\Legacy\Model\Exception\NotFoundException;
use AppBundle\Legacy\Util\General\ErrorCodes;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * PlayerRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlayerRepository extends EntityRepository
{
    /**
     * Count by criteria.
     *
     * TODO remove
     * @param array $criteria
     * @return int
     */
    public function countBy(array $criteria)
    {
        return $this->getEntityManager()->getUnitOfWork()->getEntityPersister($this->getEntityName())->count($criteria);
    }

    /**
     * Checks if nickname exists.
     *
     * @param string $nickname
     * @return bool
     */
    public function checkNickameExists(string $nickname) : bool
    {
        return $this->countBy(['nickname' => $nickname]) > 0;
    }

    /**
     * Check if email exists.
     *
     * @param string $email
     * @return bool
     */
    public function checkEmailExists(string $email) : bool
    {
        return $this->countBy(['email' => $email]) > 0;
    }

    /**
     * Find player by nickname or email.
     *
     * @param string                $name
     * @return Player               The player.
     * @throws NotFoundException    Player not found.
     */
    public function findPlayerByNicknameOrEmail(string $name) : Player
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where($qb->expr()->eq('p.nickname', ':name'))
            ->orWhere($qb->expr()->eq('p.email', ':name'))
            ->setParameter('name', $name);

        try {
            /** @var Player $player */
            $player = $qb->getQuery()->getSingleResult();
        } catch (NonUniqueResultException | NoResultException $e) {
            $exception = new NotFoundException('Usuario no encontrado');
            $exception->addMessageWithCode(
                ErrorCodes::ENTITY_NOT_FOUND,
                "Nombre o email incorrectos"
            );

            throw $exception;
        }

        return $player;
    }
}
