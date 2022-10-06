<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
    private $acquitted;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $ready;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $confirm;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: paymentDeadline::class)]
    private $paymentDeadlines;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceLine::class)]
    private $invoiceLines;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $credit;

    public function __construct()
    {
        $this->paymentDeadlines = new ArrayCollection();
        $this->invoiceLines = new ArrayCollection();
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
}
