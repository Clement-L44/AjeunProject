<?php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;
    private $passwordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $_passwordEncoder
    ) {
        $this->em = $entityManager;
        $this->passwordEncoder = $_passwordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     * @param array $context
     */
    public function persist($data, array $context = [])
    {
        if ($data->getPlainPassword())
        {
            $data->setPassword(
                $this->passwordEncoder->encodePassword(
                    $data,
                    $data->getPlainPassword()
                )
            );

            $data->eraseCredentials();
        }

        $this->em->persist($data);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}