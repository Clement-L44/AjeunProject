<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"categorieRead"}},
 *     denormalizationContext={"groups"={"categorieWrite"}},
 *     collectionOperations = {
 *          "get" = {
 *              "method" = "GET",
 *              "normalization_context" = { "groups" = "categorieRead" }
 *          },
 *          "post" = {
 *              "method" = "POST",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits",
 *              "denormalization_context" = { "groups" = "categorieWrite" }
 *          }
 *     },
 *     itemOperations = {
 *          "get" = {
 *              "method" = "GET",
 *              "normalization_context" = { "groups" = "categorieRead" }
 *          },
 *          "put" = {
 *              "method" = "PUT",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits",
 *              "denormalization_context" = { "groups" = "categorieWrite" }
 *         },
 *         "delete" = {
 *             "method" = "DELETE",
 *             "access_control" = "is_granted('ROLE_ADMIN')",
 *             "access_control_message" = "Vous n'avez pas les droits"
 *         }
 *     }
 * )
 * @ApiFilter(OrderFilter::class, properties= {"aime" = "desc"})
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"categorieRead", "articleRead"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"categorieRead", "categorieWrite", "articleRead", "userRead"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="categorie", cascade="persist")
     * @ApiSubresource
     * @Groups({"categorieRead"})
     */
    private $articles;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"categorieRead"})
     */
    private $aime;



    public function __construct()
    {
        $this->articles = new ArrayCollection();
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
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addCategorie($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removeCategorie($this);
        }

        return $this;
    }

    public function getAime(): ?int
    {
        return $this->aime;
    }

    public function setAime(?int $aime): self
    {
        $this->aime = $aime;

        return $this;
    }

}
