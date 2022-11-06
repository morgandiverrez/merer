<?php

namespace App\Entity;

use App\Repository\ExpenseReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpenseReportRepository::class)]
class ExpenseReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1024)]
    private ?string $Motif = null;

    #[ORM\Column]
    private ?int $code = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $document = null;

    #[ORM\OneToMany(mappedBy: 'expenseReport', targetEntity: ExpenseReportRouteLine::class, orphanRemoval: true)]
    private Collection $expenseReportRouteLines;

    #[ORM\OneToMany(mappedBy: 'expenseReport', targetEntity: ExpenseReportLine::class, orphanRemoval: true)]
    private Collection $expenseReportLines;

    #[ORM\ManyToOne(inversedBy: 'expenseReports')]
    private ?Transaction $transaction = null;

    #[ORM\ManyToOne(inversedBy: 'expenseReports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(nullable: true)]
    private ?bool $comfirm = null;

    #[ORM\ManyToOne(inversedBy: 'expenseReports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercice $exercice = null;

    public function __construct()
    {
        $this->expenseReportRouteLines = new ArrayCollection();
        $this->expenseReportLines = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotif(): ?string
    {
        return $this->Motif;
    }

    public function setMotif(string $Motif): self
    {
        $this->Motif = $Motif;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): self
    {
        $this->document = $document;

        return $this;
    }

    /**
     * @return Collection<int, ExpenseReportRouteLine>
     */
    public function getExpenseReportRouteLines(): Collection
    {
        return $this->expenseReportRouteLines;
    }

    public function addExpenseReportRouteLine(ExpenseReportRouteLine $expenseReportRouteLine): self
    {
        if (!$this->expenseReportRouteLines->contains($expenseReportRouteLine)) {
            $this->expenseReportRouteLines->add($expenseReportRouteLine);
            $expenseReportRouteLine->setExpenseReport($this);
        }

        return $this;
    }

    public function removeExpenseReportRouteLine(ExpenseReportRouteLine $expenseReportRouteLine): self
    {
        if ($this->expenseReportRouteLines->removeElement($expenseReportRouteLine)) {
            // set the owning side to null (unless already changed)
            if ($expenseReportRouteLine->getExpenseReport() === $this) {
                $expenseReportRouteLine->setExpenseReport(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ExpenseReportLine>
     */
    public function getExpenseReportLines(): Collection
    {
        return $this->expenseReportLines;
    }

    public function addExpenseReportLine(ExpenseReportLine $expenseReportLine): self
    {
        if (!$this->expenseReportLines->contains($expenseReportLine)) {
            $this->expenseReportLines->add($expenseReportLine);
            $expenseReportLine->setExpenseReport($this);
        }

        return $this;
    }

    public function removeExpenseReportLine(ExpenseReportLine $expenseReportLine): self
    {
        if ($this->expenseReportLines->removeElement($expenseReportLine)) {
            // set the owning side to null (unless already changed)
            if ($expenseReportLine->getExpenseReport() === $this) {
                $expenseReportLine->setExpenseReport(null);
            }
        }

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): self
    {
        $this->transaction = $transaction;

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

    public function isComfirm(): ?bool
    {
        return $this->comfirm;
    }

    public function setComfirm(?bool $comfirm): self
    {
        $this->comfirm = $comfirm;

        return $this;
    }

    public function getExercice(): ?Exercice
    {
        return $this->exercice;
    }

    public function setExercice(?Exercice $exercice): self
    {
        $this->exercice = $exercice;

        return $this;
    }

}
