<?php

namespace App\Entity;

use App\Repository\AssociationRepository;
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
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $fede_filliere;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $fede_territoire;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $local;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $adresse_mail;

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
}
