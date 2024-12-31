<?php

namespace App\Entity;

use App\Repository\CarpoolRepository;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarpoolRepository::class)]
class Carpool
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide")]
    #[Assert\Length(min: 2, max: 50, minMessage: "Il n'y a pas assez de caractère, il en faut au moins 2", maxMessage: "Il y a trop de caractère, il faut maximum 50 caractère")]
    private ?string $startPlace = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide")]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $endPlace = null;

    #[ORM\Column]
    #[Assert\Positive]
    #[Assert\LessThan(10)]
    private ?int $placeLeft;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\NotBlank(message: "Start date is required.")]
    #[Assert\Type("\DateTimeInterface")]
    private ?\DateTime $startDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\NotBlank(message: "End date is required.")]
    #[Assert\Type("\DateTimeInterface")]
    private ?\DateTime $endDate;

    #[ORM\Column]
    private ?bool $isEcologique = null;

    #[ORM\Column]
    private ?bool $isStart = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(2)]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'carpool')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'carpool')]
    private ?Car $car = null;

    #[ORM\Column]
    private ?bool $isFinish = false;

    /**
     * @var Collection<int, Opinion>
     */
    #[ORM\OneToMany(targetEntity: Opinion::class, mappedBy: 'carpool')]
    private Collection $carpool;

    /**
     * @var Collection<int, CarpoolParticipation>
     */
    #[ORM\OneToMany(targetEntity: CarpoolParticipation::class, mappedBy: 'CarpoolParticipation')]
    private Collection $CarpoolParticipation;

    public function __construct()
    {
        $this->startDate = new \DateTime();
        $this->endDate = (new \DateTime());
        $this->CarpoolParticipation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartPlace(): ?string
    {
        return $this->startPlace;
    }

    public function setStartPlace(?string $startPlace): self
    {
        $this->startPlace = $startPlace;

        return $this;
    }

    public function getEndPlace(): ?string
    {
        return $this->endPlace;
    }

    public function setEndPlace(string $endPlace): static
    {
        $this->endPlace = $endPlace;

        return $this;
    }

    public function getPlaceLeft(): ?int
    {
        return $this->placeLeft;
    }

    public function setPlaceLeft(int $placeLeft): static
    {
        $this->placeLeft = $placeLeft;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate($startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function isEcologique(): ?bool
    {
        return $this->isEcologique;
    }

    public function setEcologique(bool $isEcologique): static
    {
        $this->isEcologique = $isEcologique;

        return $this;
    }

    public function isStart(): ?bool
    {
        return $this->isStart;
    }

    public function setStart(bool $isStart): static
    {
        $this->isStart = $isStart;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
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

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    public function isFinish(): ?bool
    {
        return $this->isFinish;
    }

    public function setFinish(bool $isFinish): static
    {
        $this->isFinish = $isFinish;

        return $this;
    }
    /**
     * @return Collection<int, Opinion>
     */
    public function getDriver(): Collection
    {
        return $this->carpool;
    }

    public function addDriver(Opinion $carpool): static
    {
        if (!$this->carpool->contains($carpool)) {
            $this->carpool->add($carpool);
            $carpool->setCarpool($this);
        }

        return $this;
    }

    public function removeDriver(Opinion $Carpool): static
    {
        if ($this->carpool->removeElement($Carpool)) {
            // set the owning side to null (unless already changed)
            if ($Carpool->getCarpool() === $this) {
                $Carpool->setCarpool(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CarpoolParticipation>
     */
    public function getCarpoolParticipation(): Collection
    {
        return $this->CarpoolParticipation;
    }
}
