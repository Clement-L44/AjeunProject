<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"typeRead"}},
 *     denormalizationContext={"groups"={"typeWrite"}},
 *     collectionOperations={
 *          "get" = {
 *              "method" = "GET",
 *              "normalization_context" = { "groups" = "typeRead" }
 *          },
 *          "post" = {
 *              "method" = "POST",
 *              "denormalization_context" = { "groups" = "typeWrite" },
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits"
 *          }
 *     },
 *     itemOperations={
 *          "get" = {
 *              "method" = "GET",
 *              "normalization_context" = { "groups" = "typeRead" }
 *          },
 *          "put" = {
 *              "method" = "PUT",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits",
 *              "denormalization_context" = { "groups" = "typeWrite" }
 *          },
 *          "delete" = {
 *              "method" = "DELETE",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits",
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"typeRead", "articleRead"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"typeRead", "typeWrite", "articleRead", "userRead"})
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="type")
     * @ApiSubresource
     * @Groups({"typeRead"})
     */
    private $article;

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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

}
