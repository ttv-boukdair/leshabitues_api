<?php

namespace App\Entity;

use App\Repository\PortefeuilleRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
#[ORM\Entity(repositoryClass: PortefeuilleRepository::class)]

#[ApiResource(
    collectionOperations: [
        'get'=>["security"=>"is_granted('ROLE_ADMIN')"],
        'post'=>["security"=>"is_granted('ROLE_CLIENT')"],
    ],
    itemOperations: [
        'get'=>["security"=>"is_granted('show', object)"],     
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

    #[ORM\Column(type: 'integer')]
    private $solde;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'portefeuillesClients')]
    #[ORM\JoinColumn(nullable: false)]
    private $commercant;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $client;

    #[ORM\Column(type: 'datetime_immutable')]
    private $publishedAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(int $solde): self
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
