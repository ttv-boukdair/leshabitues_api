<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Annotation\ApiProperty;
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]

#[ApiResource(
    attributes: ['normalization_context' => ['groups' => ['read']],'denormalization_context' => ['groups' => ['write']]],
    collectionOperations: [
      
        'get'=>["security"=>"is_granted('ROLE_ADMIN')"],
        //register new user
        'post',
    ],
    itemOperations: [
        'get'=>["security"=>"is_granted('view', object)"],    
        'patch'=>["security"=>"is_granted('edit', object)"],    
    ],
)]



class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
     #[Groups(['read', 'write'])]
    private $id;
  

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['read', 'write'])]
    private $email;

    #[ORM\Column(type: 'json')]
     #[Groups(['read', 'write'])]
       #[ApiProperty(
        attributes: [
            "openapi_context" => [
                "type" => "array",
                "enum" => ["ROLE_CLIENT", "ROLE_COMMERCANT","ROLE_ADMIN"],
                "example" => ["ROLE_CLIENT"],
            ],
        ],
    )]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[Groups([ "user"=>"write"])]
    #[SerializedName("password")]
    private $plainPassword;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
     #[Groups(['read', 'write'])]
    private $nom;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
     #[Groups(['read', 'write'])]
    private $prenom;

    #[ORM\Column(type: 'text', nullable: true)]
     #[Groups(['read', 'write'])]
    private $adresse;

    #[ORM\Column(type: 'integer', length: 255, nullable: true)]
     #[Groups(['read', 'write'])]
    private $codePostal;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
     #[Groups(['read', 'write'])]
    private $ville;



    public function __construct()
    {
        // $this->offres = new ArrayCollection();
        // $this->portefeuillesClients = new ArrayCollection();
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
    public function getUserIdentifier(): string
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
    public function hasRoles(string $roles): bool
    {
        return in_array($roles, $this->roles);
    }
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getPlainPassword( ): ?string
    {
        return $this->plainPassword ;
    }

    public function setPlainPassword (string $plainPassword ): self
    {
        $this->plainPassword = $plainPassword ;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(?int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    // /**
    //  * @return Collection|Offre[]
    //  */
    // public function getOffres(): Collection
    // {
    //     return $this->offres;
    // }

    // public function addOffre(Offre $offre): self
    // {
    //     if (!$this->offres->contains($offre)) {
    //         $this->offres[] = $offre;
    //         $offre->setCommercant($this);
    //     }

    //     return $this;
    // }

    // public function removeOffre(Offre $offre): self
    // {
    //     if ($this->offres->removeElement($offre)) {
    //         // set the owning side to null (unless already changed)
    //         if ($offre->getCommercant() === $this) {
    //             $offre->setCommercant(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection|Portefeuille[]
    //  */
    // public function getPortefeuillesClients(): Collection
    // {
    //     return $this->portefeuillesClients;
    // }

    // public function addPortefeuillesClient(Portefeuille $portefeuillesClient): self
    // {
    //     if (!$this->portefeuillesClients->contains($portefeuillesClient)) {
    //         $this->portefeuillesClients[] = $portefeuillesClient;
    //         $portefeuillesClient->setCommercant($this);
    //     }

    //     return $this;
    // }

    // public function removePortefeuillesClient(Portefeuille $portefeuillesClient): self
    // {
    //     if ($this->portefeuillesClients->removeElement($portefeuillesClient)) {
    //         // set the owning side to null (unless already changed)
    //         if ($portefeuillesClient->getCommercant() === $this) {
    //             $portefeuillesClient->setCommercant(null);
    //         }
    //     }

    //     return $this;
    // }
}
