<?php

namespace App\Entity;

use App\Repository\ChequeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChequeRepository::class)]
class Cheque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateOfCollection = null;

    #[ORM\ManyToOne(inversedBy: 'cheques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ChequeBox $chequeBox = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDateOfCollection(): ?\DateTimeInterface
    {
        return $this->dateOfCollection;
    }

    public function setDateOfCollection(\DateTimeInterface $dateOfCollection): self
    {
        $this->dateOfCollection = $dateOfCollection;

        return $this;
    }

    public function getChequeBox(): ?ChequeBox
    {
        return $this->chequeBox;
    }

    public function setChequeBox(?ChequeBox $chequeBox): self
    {
        $this->chequeBox = $chequeBox;

        return $this;
    }

   
}
