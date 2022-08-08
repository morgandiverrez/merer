<?php

namespace App\Entity;

use App\Repository\ConducteurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConducteurRepository::class)]
class Conducteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'time')]
    private $duration;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $objectif;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $activity;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $contenu;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $logistique;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $observation;

    #[ORM\OneToOne(inversedBy: 'conducteur', targetEntity: Seance::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $seance;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $categorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

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

    public function getObjectif(): ?string
    {
        return $this->objectif;
    }

    public function setObjectif(?string $objectif): self
    {
        $this->objectif = $objectif;

        return $this;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(?string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getLogistique(): ?string
    {
        return $this->logistique;
    }

    public function setLogistique(?string $logistique): self
    {
        $this->logistique = $logistique;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): self
    {
        $this->observation = $observation;

        return $this;
    }

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(Seance $seance): self
    {
        $this->seance = $seance;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
