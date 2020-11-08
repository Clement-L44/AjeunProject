<?php

// src/DataPersister

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Sondage;
use Doctrine\ORM\EntityManagerInterface;

class SondageDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager){
        $this->em = $entityManager;
    }


    /*
    Cette méthode défini si ce persister supporte l'entité.
    Au fait c'est cette méthode qui dira si ce persister est pour l'entité Article ou pas
    Le paramètre $data est un objet qui représente l'entité
    */

    public function supports($data, array $context = []): bool
    {

        /*On veut persister l'entité Article*/
        return $data instanceof Sondage;
    }

    /*
    Cette méthode va créer ou modifier les données, c'est donc cette méthode qui sera appelée
    à chaque opération POST, PUT ou PATCH
    */

    /**
     * @param Sondage $data
     * @param array $context
     */
    public function persist($data, array $context = [])
    {
        $data->setNbrVotes(0);
        $this->em->persist($data);
        $this->em->flush();
    }

    /*
    Cette méthode sera appelée pour l'opération DELETE
    */

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}



