<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToMany(targetEntity=Article::class, inversedBy="types")
     * @ApiSubresource
     * @Groups({"typeRead"})
     */
    private $article;

    public function __construct()
    {
        $this->article = new ArrayCollection();
    }


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

    /**
     * @return Collection|Article[]
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->article->contains($article)) {
            $this->article->removeElement($article);
        }

        return $this;
    }

}
