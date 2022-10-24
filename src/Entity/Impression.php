<?php

namespace App\Entity;

use App\Entity\Invoice;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImpressionRepository;

#[ORM\Entity(repositoryClass: ImpressionRepository::class)]
class Impression
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $datetime;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $format;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $rectoVerso = false;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $couleur = true;

    #[ORM\Column(type: 'integer')]
    private $quantite;

    #[ORM\Column(type: 'boolean')]
    private $factureFinDuMois = true;

    #[ORM\Column(type: 'boolean')]
    private $dejaPaye = false;

    #[ORM\ManyToOne(targetEntity: Invoice::class, inversedBy: 'impressions')]
    private $invoice;

    #[ORM\ManyToOne(inversedBy: 'impressions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    public function  __toString()
    {
        return $this->getName();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function isRectoVerso(): ?bool
    {
        return $this->rectoVerso;
    }

    public function setRectoVerso(?bool $rectoVerso): self
    {
        $this->rectoVerso = $rectoVerso;

        return $this;
    }

    public function isCouleur(): ?bool
    {
        return $this->couleur;
    }

    public function setCouleur(?bool $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function isFactureFinDuMois(): ?bool
    {
        return $this->factureFinDuMois;
    }

    public function setFactureFinDuMois(?bool $factureFinDuMois): self
    {
        $this->factureFinDuMois = $factureFinDuMois;

        return $this;
    }

    public function isDejaPaye(): ?bool
    {
        return $this->dejaPaye;
    }

    public function setDejaPaye(bool $dejaPaye): self
    {
        $this->dejaPaye = $dejaPaye;

        return $this;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
