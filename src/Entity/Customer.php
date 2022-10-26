<?php

namespace App\Entity;

use App\Entity\Contact;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $name = null; 

    #[ORM\ManyToOne(inversedBy: 'customers')]
    private ?ChartOfAccounts $chartOfAccounts = null;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Invoice::class)]
    private Collection $invoices;
   
    #[ORM\ManyToOne(inversedBy: 'customers')]
    private ?Location $location = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    private ?AdministrativeIdentifier $administrativeIdentifier = null;


    #[ORM\ManyToMany(targetEntity: Contact::class, mappedBy: 'customer')]
    private Collection $contacts;

    #[ORM\OneToOne(inversedBy: 'customer', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Impression::class)]
    private Collection $impressions;

    #[ORM\Column]
    private ?bool $impressionAccess = false;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: ExpenseReport::class)]
    private Collection $expenseReports;

 

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->impressions = new ArrayCollection();
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

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setCustomer($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getCustomer() === $this) {
                $invoice->setCustomer(null);
            }
        }

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


    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->addCustomer($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            $contact->removeCustomer($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Impression>
     */
    public function getImpressions(): Collection
    {
        return $this->impressions;
    }

    public function addImpression(Impression $impression): self
    {
        if (!$this->impressions->contains($impression)) {
            $this->impressions->add($impression);
            $impression->setCustomer($this);
        }

        return $this;
    }

    public function removeImpression(Impression $impression): self
    {
        if ($this->impressions->removeElement($impression)) {
            // set the owning side to null (unless already changed)
            if ($impression->getCustomer() === $this) {
                $impression->setCustomer(null);
            }
        }

        return $this;
    }

    public function isImpressionAccess(): ?bool
    {
        return $this->impressionAccess;
    }

    public function setImpressionAccess(bool $impressionAccess): self
    {
        $this->impressionAccess = $impressionAccess;

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
