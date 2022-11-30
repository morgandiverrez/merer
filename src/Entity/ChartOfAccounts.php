<?php

namespace App\Entity;

use App\Entity\Customer;
use App\Entity\TransactionLine;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\ChartOfAccountsRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ChartOfAccountsRepository::class)]
class ChartOfAccounts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   
    #[ORM\Column(length:1024, nullable: true)]
    private ?string $name = null;

    #[ORM\Column( nullable : false)]
    private ?bool $movable;

    #[ORM\OneToMany(mappedBy: 'chartOfAccounts', targetEntity: TransactionLine::class)]
    private Collection $transactionLines;

    #[ORM\Column( nullable: true)]
    private ?int $code = null;

    #[ORM\OneToMany(mappedBy: 'chartOfAccounts', targetEntity: Customer::class)]
    private Collection $customers;

    public function __construct()
    {
        $this->transactionLines = new ArrayCollection();
        $this->customers = new ArrayCollection();
    }

    public function  __toString()
    {
        return ($this->getName()).'|'.($this->getCode());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isMovable(): ?bool
    {
        return $this->movable;
    }

    public function setMovable(bool $movable): self
    {
        $this->movable = $movable;

        return $this;
    }

    /**
     * @return Collection<int, TransactionLine>
     */
    public function getTransactionLines(): Collection
    {
        return $this->transactionLines;
    }

    public function addTransactionLine(TransactionLine $transactionLine): self
    {
        if (!$this->transactionLines->contains($transactionLine)) {
            $this->transactionLines->add($transactionLine);
            $transactionLine->setChartOfAccounts($this);
        }

        return $this;
    }

    public function removeTransactionLine(TransactionLine $transactionLine): self
    {
        if ($this->transactionLines->removeElement($transactionLine)) {
            // set the owning side to null (unless already changed)
            if ($transactionLine->getChartOfAccounts() === $this) {
                $transactionLine->setChartOfAccounts(null);
            }
        }

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
            $customer->setChartOfAccounts($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getChartOfAccounts() === $this) {
                $customer->setChartOfAccounts(null);
            }
        }

        return $this;
    }
}
