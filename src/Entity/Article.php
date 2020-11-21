<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;

/**
 * @ApiResource(
 *
 *     normalizationContext = { "groups" = { "articleRead" } },
 *     denormalizationContext = { "groups" = { "articleWrite" } },
 *     collectionOperations = {
            "get" = {
 *              "method" = "GET",
 *              "normalization_context" = { "groups" = "articleRead" }
 *          },
 *          "post" = {
 *              "method" = "POST",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas l'autorisation d'acccéder à cette ressource",
 *              "denormalization_context" = { "groups" = "articleWrite" }
 *          }
 *     },
 *     itemOperations={
 *          "get" = {
 *              "method" = "GET",
 *              "normalization_context" = { "groups" = "articleRead" }
 *          },
 *          "put" = {
 *              "method" = "PUT",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits",
 *              "denormalization_context" = { "groups" = "articleWrite" }
 *          },
 *          "delete" = {
 *              "method" = "DELETE",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits"
 *          }
 *     }
 * )
 * @ApiFilter (SearchFilter::class, properties = { "titre" : "ipartial", "categorie" : "exact", "type" : "exact" } ),
 * @ApiFilter (BooleanFilter::class, properties= { "sondage" }),
 * @ApiFilter (OrderFilter::class, properties= {"date" = "desc", "Aime" = "desc"}),
 * @ApiFilter (DateFilter::class, properties={"date"})
 *
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */

class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"articleRead", "categorieRead", "imageRead", "sondageRead", "typeRead", "userRead"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"articleRead", "articleWrite", "categorieRead", "imageRead", "sondageRead", "typeRead"})
     */
    private $sondage;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Groups({"articleRead", "categorieRead", "imageRead", "sondageRead", "typeRead", "userRead"})
     */
    private $Aime;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"articleRead", "articleWrite", "userRead"})
     */
    private $Contenu;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Groups({"articleRead", "articleWrite", "categorieRead", "imageRead", "sondageRead", "typeRead", "userRead"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"articleRead", "articleWrite", "userRead"})
     */
    private $conclusion;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"articleRead", "categorieRead", "imageRead", "sondageRead", "typeRead", "userRead"})
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity=Categorie::class, inversedBy="articles", cascade="persist")
     * @ApiSubresource
     * @Groups({"articleRead", "articleWrite", "userRead", "sondageRead"})
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"articleRead"})
     */
    private $Auteur;

    /**
     * @ORM\ManyToOne(targetEntity=Image::class, inversedBy="article")
     * @Groups({"articleRead", "articleWrite", "userRead"})
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, mappedBy="article")
     * @ApiSubresource
     * @Groups({"articleRead", "articleWrite", "userRead"})
     */
    private $types;

    /**
     * @ORM\OneToOne(targetEntity=Sondage::class, mappedBy="article", cascade={"persist", "remove"})
     * @ApiSubresource
     * @Groups({"articleRead", "articleWrite", "userRead"})
     */
    private $sondages;

    public function __construct()
    {
        $this->sondage = false;
        $this->date = new \DateTime();
        $this->categorie = new ArrayCollection();
        $this->types = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSondage(): ?bool
    {
        return $this->sondage;
    }

    public function setSondage(bool $sondage): self
    {
        $this->sondage = $sondage;

        return $this;
    }

    public function getAime(): ?float
    {
        return $this->Aime;
    }

    public function setAime(?float $Aime): self
    {
        $this->Aime = $Aime;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->Contenu;
    }

    public function setContenu(string $Contenu): self
    {
        $this->Contenu = $Contenu;

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

    public function getConclusion(): ?string
    {
        return $this->conclusion;
    }

    public function setConclusion(string $conclusion): self
    {
        $this->conclusion = $conclusion;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(Categorie $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie[] = $categorie;
        }

        return $this;
    }

    public function removeCategorie(Categorie $categorie): self
    {
        if ($this->categorie->contains($categorie)) {
            $this->categorie->removeElement($categorie);
        }

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->Auteur;
    }

    public function setAuteur(?User $Auteur): self
    {
        $this->Auteur = $Auteur;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
            $type->addArticle($this);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->contains($type)) {
            $this->types->removeElement($type);
            $type->removeArticle($this);
        }

        return $this;
    }

    public function getSondages(): ?Sondage
    {
        return $this->sondages;
    }

    public function setSondages(?Sondage $sondages): self
    {
        $this->sondages = $sondages;

        // set (or unset) the owning side of the relation if necessary
        $newArticle = null === $sondages ? null : $this;
        if ($sondages->getArticle() !== $newArticle) {
            $sondages->setArticle($newArticle);
        }

        return $this;
    }



}
