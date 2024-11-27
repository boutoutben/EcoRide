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
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide", groups: ['SearchTravel'])]
    #[Assert\Length(min: 2, max: 50, minMessage: "Il n'y a pas assez de caractère, il en faut au moins 2", maxMessage: "Il y a trop de caractère, il faut maximum 50 caractère", groups: ['SearchTravel'])]
    private ?string $startPlace;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide", groups: ['SearchTravel'])]
    #[Assert\Length(min: 2, max: 50, groups: ['SearchTravel'])]
    private ?string $endPlace;

    #[ORM\Column]
    #[Assert\Positive]
    #[Assert\LessThan(10)]
    private ?int $placeLeft;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: "Start date is required.", groups: ['SearchTravel'])]
    #[Assert\Type("\DateTimeInterface")]
    private ?\DateTime $startDate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: "End date is required.")]
    #[Assert\Type("\DateTimeInterface")]
    private ?\DateTime $endDate;

    #[ORM\Column]
    private ?bool $isEcologique = null;

    #[ORM\Column]
    private ?bool $isGreat = null;

    #[ORM\Column]
    private ?bool $isStart = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(2)]
    private ?float $price = null;

    /**
     * @var Collection<int, Car>
     */
    #[ORM\OneToMany(targetEntity: Car::class, mappedBy: 'carpool')]
    private Collection $Car;

    #[ORM\ManyToOne(inversedBy: 'carpool')]
    private ?User $user = null;


    public function __construct()
    {
        $this->Car = new ArrayCollection();
        $this->startDate = new \DateTime();
        $this->endDate = (new \DateTime())->modify('+1 hour');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartPlace(): ?string
    {
        return $this->startPlace;
    }

    public function setStartPlace(string $startPlace): static
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

    public function isGreat(): ?bool
    {
        return $this->isGreat;
    }

    public function setGreat(bool $isGreat): static
    {
        $this->isGreat = $isGreat;

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

    /**
     * @return Collection<int, Car>
     */
    public function getCar(): Collection
    {
        return $this->Car;
    }

    public function addCar(Car $car): static
    {
        if (!$this->Car->contains($car)) {
            $this->Car->add($car);
            $car->setCarpool($this);
        }

        return $this;
    }

    public function removeCar(Car $car): static
    {
        if ($this->Car->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getCarpool() === $this) {
                $car->setCarpool(null);
            }
        }

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

}
