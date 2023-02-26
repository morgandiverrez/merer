<?php

namespace App\Entity;

use App\Repository\RepayGridRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepayGridRepository::class)]
class RepayGrid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $travelMean = null;

    #[ORM\Column(length: 255)]
    private ?string $start = null;

    #[ORM\Column(length: 255)]
    private ?string $end = null;

    #[ORM\Column(precision: 10, scale: 0)]
    private ?float $amount = null;

    #[ORM\Column]
    private ?float $distance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function  __toString()
    {
        return ('de '.$this->getStart().' à '.$this->getEnd().' en '.$this->getTravelMean());
    }

    public function getTravelMean(): ?string
    {
        return $this->travelMean;
    }

    public function setTravelMean(string $travelMean): self
    {
        $this->travelMean = $travelMean;

        return $this;
    }

    public function getStart(): ?string
    {
        return $this->start;
    }

    public function setStart(?string $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?string
    {
        return $this->end;
    }

    public function setEnd(?string $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(?float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }
}
