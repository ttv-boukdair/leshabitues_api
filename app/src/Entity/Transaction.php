<?php

namespace App\Entity;


use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]

#[ApiResource(
    attributes: ['denormalization_context' => ['groups' => ['transaction_write']]],
    collectionOperations: [
        'get',
        'post'=>["security"=>"is_granted('ROLE_CLIENT')"],
    ],
    itemOperations: [
        'get'=>["security"=>"is_granted('view', object)"],     
    ],
)]

#[ApiFilter(
    OrderFilter::class, properties: ['id' => 'ASC','montant' =>'DESC'])
    ]
#[ApiFilter(
    SearchFilter::class, properties: [ 'type' => 'exact','montant' => 'exact','offre'=>'exact','portefeuille'=>'exact'])
    ]

class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['transaction_write'])]
    #[ORM\Column(type: 'string', length: 255)]
        #[ApiProperty(
            attributes: [
                "openapi_context" => [
                    "type" => "string",
                    "enum" => ["credit", "debit"],
                    "example" => "credit",
                ],
            ],
        )]
    private $type;

   #[Groups(['transaction_write'])]
   #[ORM\Column(type: 'float')]
     #[ApiProperty(
        attributes: [
            "openapi_context" => [
                "type" => "float",
                "example" => "10.5", 
                "summary"=>"le champ montant est pris en compte en cas de transaction de type debit"
            ],
        ],
    )]
    private $montant;

    #[Groups(['transaction_write'])]
    #[ORM\ManyToOne(targetEntity: Offre::class)]
    #[ApiProperty(
        attributes: [
            "openapi_context" => [
                "type" => "string",
                "example" => "/api/offres/1",
                "summary"=>"le champ offre est pris en compte en cas de transaction de type credit"
            ],
        ],
    )]      
    private $offre;

    #[Groups(['transaction_write'])]
    #[ORM\ManyToOne(targetEntity: Portefeuille::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(
        attributes: [
            "openapi_context" => [
                "type" => "string",
                "example" => "/api/portefeuilles/1",
            ],
        ],
    )]    
    private $portefeuille;

    #[ORM\Column(type: 'datetime_immutable')]
    private $publishedAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }



    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getPortefeuille(): ?Portefeuille
    {
        return $this->portefeuille;
    }

    public function setPortefeuille(?Portefeuille $portefeuille): self
    {
        $this->portefeuille = $portefeuille;

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

}
