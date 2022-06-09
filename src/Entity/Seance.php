<?php

namespace App\Entity;

use App\Repository\SeanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeanceRepository::class)]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $code;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $datetime;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $nombreplace;

    #[ORM\ManyToOne(targetEntity: Formation::class, inversedBy: 'seance')]
    private $formation;

    #[ORM\ManyToMany(targetEntity: Lieux::class, mappedBy: 'seance')]
    private $lieux;

    #[ORM\OneToMany(mappedBy: 'seance', targetEntity: Retour::class)]
    private $retour;

    #[ORM\ManyToMany(targetEntity: Profil::class, mappedBy: 'formateurice')]
    private $formateurice;

    public function __construct()
    {
        $this->lieux = new ArrayCollection();
        $this->retour = new ArrayCollection();
        $this->formateurice = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(?\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getNombreplace(): ?int
    {
        return $this->nombreplace;
    }

    public function setNombreplace(?int $nombreplace): self
    {
        $this->nombreplace = $nombreplace;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection<int, Lieux>
     */
    public function getLieux(): Collection
    {
        return $this->lieux;
    }

    public function addLieux(Lieux $lieux): self
    {
        if (!$this->lieux->contains($lieux)) {
            $this->lieux[] = $lieux;
            $lieux->addSeance($this);
        }

        return $this;
    }

    public function removeLieux(Lieux $lieux): self
    {
        if ($this->lieux->removeElement($lieux)) {
            $lieux->removeSeance($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Retour>
     */
    public function getRetour(): Collection
    {
        return $this->retour;
    }

    public function addRetour(Retour $retour): self
    {
        if (!$this->retour->contains($retour)) {
            $this->retour[] = $retour;
            $retour->setSeance($this);
        }

        return $this;
    }

    public function removeRetour(Retour $retour): self
    {
        if ($this->retour->removeElement($retour)) {
            // set the owning side to null (unless already changed)
            if ($retour->getSeance() === $this) {
                $retour->setSeance(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Profil>
     */
    public function getFormateurice(): Collection
    {
        return $this->formateurice;
    }

    public function addFormateurice(Profil $formateurice): self
    {
        if (!$this->formateurice->contains($formateurice)) {
            $this->formateurice[] = $formateurice;
            $formateurice->addFormateurice($this);
        }

        return $this;
    }

    public function removeFormateurice(Profil $formateurice): self
    {
        if ($this->formateurice->removeElement($formateurice)) {
            $formateurice->removeFormateurice($this);
        }

        return $this;
    }
}
