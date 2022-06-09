<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $code;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $pre_requis;

    #[ORM\Column(type: 'time', nullable: true)]
    private $duration;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $public_cible;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $opg;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Seance::class)]
    private $seance;

    public function __construct()
    {
        $this->Seance = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPreRequis(): ?string
    {
        return $this->pre_requis;
    }

    public function setPreRequis(?string $pre_requis): self
    {
        $this->pre_requis = $pre_requis;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(?\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPublicCible(): ?string
    {
        return $this->public_cible;
    }

    public function setPublicCible(?string $public_cible): self
    {
        $this->public_cible = $public_cible;

        return $this;
    }

    public function getOpg(): ?string
    {
        return $this->opg;
    }

    public function setOpg(?string $opg): self
    {
        $this->opg = $opg;

        return $this;
    }

    /**
     * @return Collection<int, Seance>
     */
    public function getSeance(): Collection
    {
        return $this->seance;
    }

    public function addSeance(Seance $seance): self
    {
        if (!$this->seance->contains($seance)) {
            $this->seance[] = $seance;
            $seance->setFormation($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seance->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getFormation() === $this) {
                $seance->setFormation(null);
            }
        }

        return $this;
    }
}
