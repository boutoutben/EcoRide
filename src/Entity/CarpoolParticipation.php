<?php

namespace App\Entity;

use App\Repository\CarpoolParticipationRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarpoolParticipationRepository::class)]
class CarpoolParticipation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'carpoolParticipation')]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $hasToValidate = false;

    #[ORM\ManyToOne(inversedBy: 'CarpoolParticipation')]
    private ?Carpool $carpool = null;

    #[ORM\Column(nullable:true)]
    private ?\DateTimeImmutable $createAt = null;

    public function __construct() {
        $this->createAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function hasToValidate(): ?bool
    {
        return $this->hasToValidate;
    }

    public function setHasToValidate(bool $hasToValidate): static
    {
        $this->hasToValidate = $hasToValidate;

        return $this;
    }

    public function getCarpool(): ?Carpool
    {
        return $this->carpool;
    }

    public function setCarpool(?Carpool $Carpool): static
    {
        $this->carpool = $Carpool;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }
}
