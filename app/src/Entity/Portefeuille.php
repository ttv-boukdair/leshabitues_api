<?php

namespace App\Entity;

use App\Repository\PortefeuilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiProperty;
#[ORM\Entity(repositoryClass: PortefeuilleRepository::class)]

#[ApiResource(
    attributes: ['denormalization_context' => ['groups' => ['portefeuille_write']]],
    collectionOperations: [
        'get',
        'post'=>["security"=>"is_granted('ROLE_CLIENT')"],
    ],
    itemOperations: [
        'get'=>["security"=>"is_granted('view', object)"],     
    ],
)]
#[ApiFilter(OrderFilter::class, properties: ['id' => 'ASC','solde' =>'DESC'])]
#[ApiFilter(SearchFilter::class, properties: [ 'commercant' => 'exact','client' => 'exact', 'solde' => 'exact'])]
class Portefeuille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'float')]
    #[Assert\PositiveOrZero]
    private $solde;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['portefeuille_write'])]
    #[ApiProperty(
            attributes: [
                "openapi_context" => [
                    "type" => "string",
                    "example" => "/api/users/1",
                ],
            ],
        )]
    private $commercant;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $client;

    #[ORM\Column(type: 'datetime_immutable')]
 
    private $publishedAt;

    #[ORM\Column(type: 'datetime_immutable')]

    private $updatedAt;



    public function __construct()
    {
      
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getCommercant(): ?User
    {
        return $this->commercant;
    }

    public function setCommercant(?User $commercant): self
    {
        $this->commercant = $commercant;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }
    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {



        // UniqueEntity validation
        $metadata->addConstraint(new UniqueEntity([
            'fields'=> ['client', 'commercant'],
            'errorPath'=>  'commercant',
            'message'=>  'ce client a déjà un portefeuill chez ce commerçant',
        ]));

    }
}

  
