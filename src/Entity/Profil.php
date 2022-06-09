<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilRepository::class)]
class Profil
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
    private $last_name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $pronom;

    #[ORM\Column(type: 'date', nullable: true)]
    private $date_of_birth;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $score;

    #[ORM\OneToMany(mappedBy: 'profil', targetEntity: Retour::class)]
    private $retour;

    #[ORM\ManyToMany(targetEntity: EquipeElue::class, mappedBy: 'profil')]
    private $equipeElue;

    #[ORM\ManyToMany(targetEntity: Association::class, mappedBy: 'profil')]
    private $association;

    #[ORM\ManyToMany(targetEntity: Badge::class, mappedBy: 'profil')]
    private $badge;

    #[ORM\ManyToMany(targetEntity: seance::class, inversedBy: 'profil')]
    private $formateurice;

    public function __construct()
    {
        $this->retour = new ArrayCollection();
        $this->equipeElue = new ArrayCollection();
        $this->association = new ArrayCollection();
        $this->badge = new ArrayCollection();
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

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getPronom(): ?string
    {
        return $this->pronom;
    }

    public function setPronom(?string $pronom): self
    {
        $this->pronom = $pronom;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?\DateTimeInterface $date_of_birth): self
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

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
            $retour->setProfil($this);
        }

        return $this;
    }

    public function removeRetour(Retour $retour): self
    {
        if ($this->retour->removeElement($retour)) {
            // set the owning side to null (unless already changed)
            if ($retour->getProfil() === $this) {
                $retour->setProfil(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EquipeElue>
     */
    public function getEquipeElue(): Collection
    {
        return $this->equipeElue;
    }

    public function addEquipeElue(EquipeElue $equipeElue): self
    {
        if (!$this->equipeElue->contains($equipeElue)) {
            $this->equipeElue[] = $equipeElue;
            $equipeElue->addProfil($this);
        }

        return $this;
    }

    public function removeEquipeElue(EquipeElue $equipeElue): self
    {
        if ($this->equipeElue->removeElement($equipeElue)) {
            $equipeElue->removeProfil($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Association>
     */
    public function getAssociation(): Collection
    {
        return $this->association;
    }

    public function addAssociation(Association $association): self
    {
        if (!$this->association->contains($association)) {
            $this->association[] = $association;
            $association->addProfil($this);
        }

        return $this;
    }

    public function removeAssociation(Association $association): self
    {
        if ($this->association->removeElement($association)) {
            $association->removeProfil($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Badge>
     */
    public function getBadge(): Collection
    {
        return $this->badge;
    }

    public function addBadge(Badge $badge): self
    {
        if (!$this->badge->contains($badge)) {
            $this->badge[] = $badge;
            $badge->addProfil($this);
        }

        return $this;
    }

    public function removeBadge(Badge $badge): self
    {
        if ($this->badge->removeElement($badge)) {
            $badge->removeProfil($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, seance>
     */
    public function getFormateurice(): Collection
    {
        return $this->formateurice;
    }

    public function addFormateurice(seance $formateurice): self
    {
        if (!$this->formateurice->contains($formateurice)) {
            $this->formateurice[] = $formateurice;
        }

        return $this;
    }

    public function removeFormateurice(seance $formateurice): self
    {
        $this->formateurice->removeElement($formateurice);

        return $this;
    }
}
