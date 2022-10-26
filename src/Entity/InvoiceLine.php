<?php

namespace App\Entity;

use App\Repository\InvoiceLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceLineRepository::class)]
class InvoiceLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'float', precision: 10, scale: 0, nullable: true)]
    private $discount;

    #[ORM\ManyToOne(targetEntity: Invoice::class, inversedBy: 'invoiceLines')]
    private $invoice;

    #[ORM\ManyToOne(targetEntity: CatalogDiscount::class, inversedBy: 'invoiceLines')]
    private $catalogDiscount;

    #[ORM\ManyToOne(targetEntity: CatalogService::class, inversedBy: 'invoiceLines')]
    private $CatalogService;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $quote = null;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

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

    public function getCatalogDiscount(): ?CatalogDiscount
    {
        return $this->catalogDiscount;
    }

    public function setCatalogDiscount(?CatalogDiscount $catalogDiscount): self
    {
        $this->catalogDiscount = $catalogDiscount;

        return $this;
    }

    public function getCatalogService(): ?CatalogService
    {
        return $this->CatalogService;
    }

    public function setCatalogService(?CatalogService $CatalogService): self
    {
        $this->CatalogService = $CatalogService;

        return $this;
    }

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function setQuote(?string $quote): self
    {
        $this->quote = $quote;

        return $this;
    }
}
