<?php

namespace App\Entity;

use App\Entity\Invoice;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private $code = null;

    #[ORM\Column]
    private ?bool $closure = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $quote = null;

    #[ORM\OneToMany(mappedBy: 'transaction', targetEntity: Invoice::class)]
    private Collection $invoices;

    #[ORM\OneToMany(mappedBy: 'transaction', targetEntity: TransactionLine::class)]
    private Collection $transactionLines;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
        $this->transactionLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function  __toString()
    {
        return $this->getCode();
    }
    

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function isClosure(): ?bool
    {
        return $this->closure;
    }

    public function setClosure(bool $closure): self
    {
        $this->closure = $closure;

        return $this;
    }

    public function getQuote(): ?string
    {
        return $this->quote;
    }

    public function setQuote(string $quote): self
    {
        $this->quote = $quote;

        return $this;
    }

    /**
     * @return Collection<int, invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setTransaction($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getTransaction() === $this) {
                $invoice->setTransaction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transactionline>
     */
    public function getTransactionLines(): Collection
    {
        return $this->transactionLines;
    }

    public function addTransactionLine(TransactionLine $transactionLine): self
    {
        if (!$this->transactionLines->contains($transactionLine)) {
            $this->transactionLines->add($transactionLine);
            $transactionLine->setTransaction($this);
        }

        return $this;
    }

    public function removeTransactionLine(Transactionline $transactionLine): self
    {
        if ($this->transactionLines->removeElement($transactionLine)) {
            // set the owning side to null (unless already changed)
            if ($transactionLine->getTransaction() === $this) {
                $transactionLine->setTransaction(null);
            }
        }

        return $this;
    }
}
