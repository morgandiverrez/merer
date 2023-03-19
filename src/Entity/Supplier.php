<?php

namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Il y a déjà un fournisseur avec ce nom')]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?ChartOfAccounts $chartOfAccounts = null;

    #[ORM\ManyToOne(inversedBy: 'suppliers')]
    private ?Location $location = null;

    #[ORM\ManyToOne(inversedBy: 'suppliers')]
    private ?AdministrativeIdentifier $administrativeIdentifier = null;

    #[ORM\Column(unique: true, length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(inversedBy: 'supplier', cascade: ['persist', 'remove'])]
    private ?Customer $customer = null;

    #[ORM\ManyToMany(targetEntity: Contact::class, mappedBy: 'supplier')]
    private Collection $contacts;

    #[ORM\OneToMany(mappedBy: 'supplier', targetEntity: ExpenseReport::class)]
    private Collection $expenseReports;

    

    public function __construct()
    {
        $this->expenseReports = new ArrayCollection();
    }

    public function  __toString()
    {
        return $this->getName();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChartOfAccounts(): ?ChartOfAccounts
    {
        return $this->chartOfAccounts;
    }

    public function setChartOfAccounts(?ChartOfAccounts $chartOfAccounts): self
    {
        $this->chartOfAccounts = $chartOfAccounts;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }



    public function getAdministrativeIdentifier(): ?AdministrativeIdentifier
    {
        return $this->administrativeIdentifier;
    }

    public function setAdministrativeIdentifier(?AdministrativeIdentifier $administrativeIdentifier): self
    {
        $this->administrativeIdentifier = $administrativeIdentifier;

        return $this;
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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

     /**
     * @return Collection<int, ExpenseReport>
     */
    public function getExpenseReports(): Collection
    {
        return $this->expenseReports;
    }

    public function addExpenseReport(ExpenseReport $expenseReport): self
    {
        if (!$this->expenseReports->contains($expenseReport)) {
            $this->expenseReports->add($expenseReport);
            $expenseReport->setCustomer($this);
        }

        return $this;
    }

    public function removeExpenseReport(ExpenseReport $expenseReport): self
    {
        if ($this->expenseReports->removeElement($expenseReport)) {
            // set the owning side to null (unless already changed)
            if ($expenseReport->getCustomer() === $this) {
                $expenseReport->setCustomer(null);
            }
        }

        return $this;
    }
}
