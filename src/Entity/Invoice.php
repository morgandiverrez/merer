<?php

namespace App\Entity;

use App\Entity\Impression;
use App\Entity\InvoiceLine;
use App\Entity\PaymentDeadline;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $creationDate;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $acquitted = false;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $ready = false;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $confirm = false;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: PaymentDeadline::class)]
    private $paymentDeadlines;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceLine::class)]
    private $invoiceLines;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $credit = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    private ?association $association = null;

    public function __construct()
    {
        $this->paymentDeadlines = new ArrayCollection();
        $this->invoiceLines = new ArrayCollection();
        $this->impressions = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function isAcquitted(): ?bool
    {
        return $this->acquitted;
    }

    public function setAcquitted(?bool $acquitted): self
    {
        $this->acquitted = $acquitted;

        return $this;
    }

    public function isReady(): ?bool
    {
        return $this->ready;
    }

    public function setReady(?bool $ready): self
    {
        $this->ready = $ready;

        return $this;
    }

    public function isConfirm(): ?bool
    {
        return $this->confirm;
    }

    public function setConfirm(?bool $confirm): self
    {
        $this->confirm = $confirm;

        return $this;
    }

    /**
     * @return Collection<int, paymentDeadline>
     */
    public function getPaymentDeadlines(): Collection
    {
        return $this->paymentDeadlines;
    }

    public function addPaymentDeadline(paymentDeadline $paymentDeadline): self
    {
        if (!$this->paymentDeadlines->contains($paymentDeadline)) {
            $this->paymentDeadlines[] = $paymentDeadline;
            $paymentDeadline->setInvoice($this);
        }

        return $this;
    }

    public function removePaymentDeadline(paymentDeadline $paymentDeadline): self
    {
        if ($this->paymentDeadlines->removeElement($paymentDeadline)) {
            // set the owning side to null (unless already changed)
            if ($paymentDeadline->getInvoice() === $this) {
                $paymentDeadline->setInvoice(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InvoiceLine>
     */
    public function getInvoiceLines(): Collection
    {
        return $this->invoiceLines;
    }

    public function addInvoiceLine(InvoiceLine $invoiceLine): self
    {
        if (!$this->invoiceLines->contains($invoiceLine)) {
            $this->invoiceLines[] = $invoiceLine;
            $invoiceLine->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceLine(InvoiceLine $invoiceLine): self
    {
        if ($this->invoiceLines->removeElement($invoiceLine)) {
            // set the owning side to null (unless already changed)
            if ($invoiceLine->getInvoice() === $this) {
                $invoiceLine->setInvoice(null);
            }
        }

        return $this;
    }

    public function isCredit(): ?bool
    {
        return $this->credit;
    }

    public function setCredit(?bool $credit): self
    {
        $this->credit = $credit;

        return $this;
    }


    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAssociation(): ?association
    {
        return $this->association;
    }

    public function setAssociation(?association $association): self
    {
        $this->association = $association;

        return $this;
    }
}
