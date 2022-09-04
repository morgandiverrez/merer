<?php

namespace App\Entity;

use App\Repository\SeanceProfilRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeanceProfilRepository::class)]
class SeanceProfil
{
 
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $Horrodateur;

    #[ORM\ManyToOne(targetEntity: Profil::class, inversedBy: 'seanceProfil')]
    #[ORM\Id]
    #[ORM\JoinColumn(name: "profil_ID", referencedColumnName: "id")]
    private $profil;

    #[ORM\ManyToOne(targetEntity: Seance::class, inversedBy: 'seanceProfil')]
    #[ORM\Id]
    #[ORM\JoinColumn(name: "seance_ID", referencedColumnName: "id")]
    private $seance;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $attente;

    #[ORM\ManyToOne(targetEntity: Lieux::class, inversedBy: 'seanceProfils')]
    #[ORM\JoinColumn(nullable: false)]
    private $lieu;


    
    public function getHorrodateur(): ?\DateTimeInterface
    {
        return $this->Horrodateur;
    }

    public function setHorrodateur(?\DateTimeInterface $Horrodateur): self
    {
        $this->Horrodateur = $Horrodateur;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getSeance(): ?Seance
    {
        return $this->seance;
    }

    public function setSeance(?Seance $seance): self
    {
        $this->seance = $seance;

        return $this;
    }

    public function getAttente(): ?string
    {
        return $this->attente;
    }

    public function setAttente(?string $attente): self
    {
        $this->attente = $attente;

        return $this;
    }

    public function getLieu(): ?Lieux
    {
        return $this->lieu;
    }

    public function setLieu(?Lieux $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }



   
}
