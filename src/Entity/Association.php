<?php

namespace App\Entity;

use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssociationRepository::class)]
class Association
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $code;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $sigle;

    #[ORM\Column(type: 'string', length: 2048, nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $fede_filliere;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $fede_territoire;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $local;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $adresse_mail;

    #[ORM\ManyToMany(targetEntity: Profil::class, inversedBy: 'association')]
    private $profil;

    #[ORM\Column(type: 'date', nullable: true)]
    private $date_election;

    #[ORM\ManyToMany(targetEntity: Demande::class, mappedBy: 'association')]
    private $demandes;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: Impression::class)]
    private $impressions;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: Invoice::class)]
    private Collection $invoices;

 
    public function __construct()
    {
        $this->profil = new ArrayCollection();
        $this->demandes = new ArrayCollection();
        $this->impressions = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function  __toString()
    {
        return $this->getSigle();
    }
    
    public function getId(): ?int
    {
        return $this->id;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): self
    {
        $this->sigle = $sigle;

        return $this;
    }
    

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFedeFilliere(): ?string
    {
        return $this->fede_filliere;
    }

    public function setFedeFilliere(?string $fede_filliere): self
    {
        $this->fede_filliere = $fede_filliere;

        return $this;
    }

    public function getFedeTerritoire(): ?string
    {
        return $this->fede_territoire;
    }

    public function setFedeTerritoire(?string $fede_territoire): self
    {
        $this->fede_territoire = $fede_territoire;

        return $this;
    }

    public function getLocal(): ?string
    {
        return $this->local;
    }

    public function setLocal(?string $local): self
    {
        $this->local = $local;

        return $this;
    }

    public function getAdresseMail(): ?string
    {
        return $this->adresse_mail;
    }

    public function setAdresseMail(?string $adresse_mail): self
    {
        $this->adresse_mail = $adresse_mail;

        return $this;
    }

    /**
     * @return Collection<int, Profil>
     */
    public function getProfil(): Collection
    {
        return $this->profil;
    }

    public function addProfil(Profil $profil): self
    {
        if (!$this->profil->contains($profil)) {
            $this->profil[] = $profil;
        }

        return $this;
    }

    public function removeProfil(Profil $profil): self
    {
        $this->profil->removeElement($profil);

        return $this;
    }

    public function getDateElection(): ?\DateTimeInterface
    {
        return $this->date_election;
    }

    public function setDateElection(?\DateTimeInterface $date_election): self
    {
        $this->date_election = $date_election;

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes[] = $demande;
            $demande->addAssociation($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            $demande->removeAssociation($this);
        }

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
            $this->impressions[] = $impression;
            $impression->setAssociation($this);
        }

        return $this;
    }

    public function removeImpression(Impression $impression): self
    {
        if ($this->impressions->removeElement($impression)) {
            // set the owning side to null (unless already changed)
            if ($impression->getAssociation() === $this) {
                $impression->setAssociation(null);
            }
        }

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
            $invoice->setAssociation($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getAssociation() === $this) {
                $invoice->setAssociation(null);
            }
        }

        return $this;
    }

 

}
