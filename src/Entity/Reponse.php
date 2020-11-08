<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"reponseRead"}},
 *     denormalizationContext={"groups"={"reponseWrite"}},
 *     collectionOperations={
 *          "get" = {
 *              "method" = "GET",
 *              "normalization_context" = { "groups" = "reponseRead" }
 *          },
 *          "post" = {
 *              "method" = "POST",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits",
 *              "denormalization_context" = { "groups" = "reponseWrite" }
 *          }
 *     },
 *     itemOperations={
 *          "get" = {
 *              "method" = "GET",
 *              "normalization_context" = { "groups" = "reponseRead" }
 *          },
 *          "put" = {
 *              "method" = "PUT",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits",
 *              "denormalization_context" = { "groups" = "reponseWrite" }
 *          },
 *          "patch" = {
 *              "method" = "PATCH",
 *              "denormalization_context" = { "groups" = "reponseWriteVote" }
 *          },
 *          "delete" = {
 *              "method" = "DELETE",
 *              "denormalization_context" = { "groups" = "reponseWrite" },
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits"
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"reponseRead", "sondageRead"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"reponseRead", "reponseWrite", "sondageRead", "sondageWrite", "articleRead", "userRead"})
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Sondage::class, inversedBy="reponse")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"reponseRead"})
     */
    private $sondage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"reponseWriteVote", "reponseRead", "sondageRead", "articleRead", "userRead"})
     */
    private $vote;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getSondage(): ?Sondage
    {
        return $this->sondage;
    }

    public function setSondage(?Sondage $sondage): self
    {
        $this->sondage = $sondage;

        return $this;
    }

    public function getVote(): ?int
    {
        return $this->vote;
    }

    public function setVote(?int $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

}
