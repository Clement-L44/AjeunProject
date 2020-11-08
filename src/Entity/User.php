<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"userRead"}},
 *     denormalizationContext={"groups"={"userWrite"}},
 *     collectionOperations={
 *          "get" = {
 *              "method" = "GET",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "access_control_message" = "Vous n'avez pas les droits",
 *              "normalization_context" = { "groups" = "userRead" }
 *          },
 *          "post" = {
 *              "method" = "POST",
 *              "denormalization_context" = { "groups" = "userWrite" }
 *          }
 *     },
 *     itemOperations={
 *          "get" = {
 *              "method" = "GET",
 *              "access_control" = "is_granted('ROLE_ADMIN')",
 *              "normalization_context" = { "groups" = "userRead" }
 *          },
 *          "put" = {
 *              "method" = "PUT",
 *              "access_control" = "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
 *              "access_control_message" = "Vous n'avez pas les droits",
 *              "denormalization_context" = { "groups" = "userWrite" }
 *          },
 *          "delete" = {
 *              "method" = "DELETE",
 *              "access_control" = "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
 *              "access_control_message" = "Vous n'avez pas les droits"
 *          }
 *     }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"userRead", "articleRead"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"userRead", "userWrite"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"userRead"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"userRead", "userWrite", "articleRead"})
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"userWrite"})
     */
    private $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="Auteur")
     * @Groups({"userRead"})
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
       $this->plainPassword = "null";
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

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
            $article->setAuteur($this);
        }

        return $this;

    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getAuteur() === $this) {
                $article->setAuteur(null);
            }
        }

        return $this;
    }
}
