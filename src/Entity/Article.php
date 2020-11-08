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
 * @ApiFilter (OrderFilter::class, properties= {"date" = "desc", "Aime" = "desc"})
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
     * @ORM\OneToMany(targetEntity=Type::class, mappedBy="article", cascade="persist")
     * @ApiSubresource
     * @Groups({"articleRead", "articleWrite", "userRead"})
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Sondage::class, mappedBy="article")
     * @Groups({"articleRead", "articleWrite", "userRead"})
     */
    private $sondages;

    /**
     * @ORM\ManyToMany(targetEntity=Image::class, inversedBy="articles", cascade="persist")
     * @Groups({"articleRead", "articleWrite", "userRead"})
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Categorie::class, inversedBy="articles", cascade="persist")
     * @ApiSubresource
     * @Groups({"articleRead", "articleWrite", "userRead"})
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"articleRead"})
     */
    private $Auteur;

    public function __construct()
    {
        $this->sondage = false;
        $this->date = new \DateTime();
        $this->categorie = new ArrayCollection();
        $this->type = new ArrayCollection();
        $this->sondages = new ArrayCollection();
        $this->image = new ArrayCollection();
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
     * @return Collection|Type[]
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(Type $type): self
    {
        if (!$this->type->contains($type)) {
            $this->type[] = $type;
            $type->setArticle($this);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->type->contains($type)) {
            $this->type->removeElement($type);
            // set the owning side to null (unless already changed)
            if ($type->getArticle() === $this) {
                $type->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sondage[]
     */
    public function getSondages(): Collection
    {
        return $this->sondages;
    }

    public function addSondage(Sondage $sondage): self
    {
        if (!$this->sondages->contains($sondage)) {
            $this->sondages[] = $sondage;
            $sondage->setArticle($this);
        }

        return $this;
    }

    public function removeSondage(Sondage $sondage): self
    {
        if ($this->sondages->contains($sondage)) {
            $this->sondages->removeElement($sondage);
            // set the owning side to null (unless already changed)
            if ($sondage->getArticle() === $this) {
                $sondage->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->image->contains($image)) {
            $this->image->removeElement($image);
        }

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



}
