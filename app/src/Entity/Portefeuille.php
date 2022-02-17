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
#[ORM\Entity(repositoryClass: PortefeuilleRepository::class)]

#[ApiResource(
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
    private $commercant;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $client;

    #[ORM\Column(type: 'datetime_immutable')]
    private $publishedAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updatedAt;

    #[ORM\OneToMany(mappedBy: 'portefeuille', targetEntity: Transaction::class)]
    private $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
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

            // // UniqueEntity validation
            // $metadata->addConstraint(new PositiveOrZero([
            //     'fields'=> 'solde',
            //     'message'=>  "Il n'est pas possible d'avoir un solde négatif",
            // ]));
   
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setPortefeuille($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getPortefeuille() === $this) {
                $transaction->setPortefeuille(null);
            }
        }

        return $this;
    }
}
