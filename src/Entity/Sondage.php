<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SondageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"sondageRead"}},
 *     denormalizationContext={"groups"={"sondageWrite"}},
 *     collectionOperations={
 *          "get" = {
 *              "method" = "GET",
 *              "normalization_context" = { "groups" = "userRead" }
 *          },
 *          "post" = {
 *              "method" = "POST",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits",
 *              "denormalization_context" = { "groups" = "sondageWrite" }
 *          }
 *     },
 *     itemOperations={
 *          "get" = {
 *              "method" = "GET",
 *              "normalization_context" = { "groups" = "sondageRead" }
 *          },
 *          "put" = {
 *              "method" = "PUT",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits",
 *              "denormalization_context" = { "groups" = "sondageWrite" }
 *          },
 *          "delete" = {
 *              "method" = "DELETE",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits"
 *          }
 *     },
 *     subresourceOperations={
            "api_sondages_articles_get_subresource"={
 *              "method"="GET",
 *              "path"="/sondages/{id}/article"
 *          }
 *     }
 * )
 *
 * @ApiFilter(OrderFilter::class, properties={ "nbr_votes" : "desc"}),
 * @ApiFilter(SearchFilter::class, properties={ "article.types" : "ipartial" }),
 *
 * @ORM\Entity(repositoryClass=SondageRepository::class)
 */
class Sondage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"sondageRead", "articleRead", "reponseRead"})
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"sondageRead", "articleRead", "reponseRead", "userRead"})
     */
    private $nbr_votes;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"sondageRead", "sondageWrite", "articleRead", "reponseRead", "userRead"})
     */
    private $titre;

    /**
     * @ApiSubresource
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="sondage", orphanRemoval=true, cascade="persist")
     * @Groups({"sondageRead", "sondageWrite", "articleRead", "userRead"})
     */
    private $reponse;

    /**
     * @ORM\OneToOne(targetEntity=Article::class, inversedBy="sondages", cascade="persist")
     * @Groups({"sondageRead"})
     * @ApiSubresource
     */
    private $article;


    public function __construct()
    {
        $this->reponse = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrVotes(): ?float
    {
        return $this->nbr_votes;
    }

    public function setNbrVotes(?float $nbr_votes): self
    {
        $this->nbr_votes = $nbr_votes;

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
     * @return Collection|Reponse[]
     */
    public function getReponse(): Collection
    {
        return $this->reponse;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponse->contains($reponse)) {
            $this->reponse[] = $reponse;
            $reponse->setSondage($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponse->contains($reponse)) {
            $this->reponse->removeElement($reponse);
            // set the owning side to null (unless already changed)
            if ($reponse->getSondage() === $this) {
                $reponse->setSondage(null);
            }
        }

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
