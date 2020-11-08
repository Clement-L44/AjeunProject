<?php
// src/DataPersister

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class CategorieDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;
    private $request;
    /**
     * @param Security
     */
    private $security;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $Request, Security $_security){
        $this->em = $entityManager;
        $this->request = $Request->getCurrentRequest();
        $this->security = $_security;
    }


    /*
    Cette méthode défini si ce persister supporte l'entité.
    Au fait c'est cette méthode qui dira si ce persister est pour l'entité Article ou pas
    Le paramètre $data est un objet qui représente l'entité
    */

    public function supports($data, array $context = []): bool
    {

        /*On veut persister l'entité Article*/
        return $data instanceof Categorie;
    }

    /*
    Cette méthode va créer ou modifier les données, c'est donc cette méthode qui sera appelée
    à chaque opération POST, PUT ou PATCH
    */

    /**
     * @param Categorie $data
     * @param array $context
     */
    public function persist($data, array $context = [])
    {
        $data->setAime(0);

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