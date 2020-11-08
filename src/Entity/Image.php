<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"imageRead"}},
 *     denormalizationContext={"groups"={"imageWrite"}},
 *     collectionOperations={
            "get" = {
 *              "method" = "GET",
 *              "normalization_context" = { "groups" = "imageRead" }
 *          },
 *          "post" = {
                "method" = "POST",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas l'autorisation",
 *              "denormalization_context" = { "groups" = "imageWrite" }
 *          }
 *     },
 *     itemOperations = {
            "get" = {
 *              "method" = "GET",
 *              "normalization_context" = { "groups" = "imageRead" },
 *          },
 *          "put" = {
 *              "method" = "PUT",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits",
 *              "denormalization_context" = { "groups" = "imageWrite" }
 *          },
 *          "delete" = {
 *              "method" = "DELETE",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits"
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"imageRead", "articleRead"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"imageRead", "imageWrite", "articleRead", "articleWrite", "userRead"})
     */
    private $File;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"imageRead", "imageWrite", "articleRead", "articleWrite"})
     */
    private $titre;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="image", cascade="persist")
     * @Groups({"imageRead"})
     */
    private $articles;
    

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->File;
    }

    public function setFile(string $File): self
    {
        $this->File = $File;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addImage($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removeImage($this);
        }

        return $this;
    }
}
