<?php

namespace App\Entity;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OffreRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
#[ORM\Entity(repositoryClass: OffreRepository::class)]

#[ApiResource(
    collectionOperations: [
        'get',
        'post'=>["security"=>"is_granted('ROLE_COMMERCANT')"],
    ],
    itemOperations: [
        'get',    
        'patch'=>["security"=>"is_granted('edit', object)"],    
    ],
)]

#[ApiFilter(SearchFilter::class, properties: [ 'commercant' => 'exact', 'isPublished' => 'exact'])]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $montant;

    #[ORM\Column(type: 'float')]
    private $remise;



    #[ORM\Column(type: 'boolean')]
    private $isPublished;

    #[ORM\Column(type: 'datetime_immutable')]
    private $publishedAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $commercant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(float $remise): self
    {
        $this->remise = $remise;

        return $this;
    }



    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

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

    public function getCommercant(): ?User
    {
        return $this->commercant;
    }

    public function setCommercant(?User $commercant): self
    {
        $this->commercant = $commercant;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {



        // UniqueEntity validation
        $metadata->addConstraint(new UniqueEntity([
            'fields'=> ['commercant', 'montant'],
            'errorPath'=>  'montant',
            'message'=>  "Cette offre existe déjà pour ce commerçant",
        ]));
   
    }
 
}
