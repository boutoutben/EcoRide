<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $licence_plate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $first_registration = null;

    #[ORM\Column(length: 100)]
    private ?string $model = null;

    private ?Mark $mark = null;

    #[ORM\Column(length: 100)]
    private ?string $color = null;

    #[ORM\Column(length: 100)]
    private ?string $energie = null;

    #[ORM\ManyToOne(inversedBy: 'Car')]
    private ?Carpool $carpool = null;

    /**
     * @var Collection<int, Mark>
     */
    #[ORM\OneToMany(targetEntity: Mark::class, mappedBy: 'car')]
    private Collection $Mark;

    #[ORM\ManyToOne(inversedBy: 'Car')]
    private ?User $user = null;

    public function __construct()
    {
        $this->Mark = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLicencePlate(): ?string
    {
        return $this->licence_plate;
    }

    public function setLicencePlate(string $licence_plate): static
    {
        $this->licence_plate = $licence_plate;

        return $this;
    }

    public function getFirstRegistration(): ?\DateTimeInterface
    {
        return $this->first_registration;
    }

    public function setFirstRegistration(\DateTimeInterface $first_registration): static
    {
        $this->first_registration = $first_registration;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getMark(): ?Mark
    {
        return $this->mark;
    }

    public function setMark(Mark $mark): static
    {
        $this->mark = $mark;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getEnergie(): ?string
    {
        return $this->energie;
    }

    public function setEnergie(string $energie): static
    {
        $this->energie = $energie;

        return $this;
    }

    public function getCarpool(): ?Carpool
    {
        return $this->carpool;
    }

    public function setCarpool(?Carpool $carpool): static
    {
        $this->carpool = $carpool;

        return $this;
    }

    public function addMark(Mark $mark): static
    {
        if (!$this->Mark->contains($mark)) {
            $this->Mark->add($mark);
            $mark->setCar($this);
        }

        return $this;
    }

    public function removeMark(Mark $mark): static
    {
        if ($this->Mark->removeElement($mark)) {
            // set the owning side to null (unless already changed)
            if ($mark->getCar() === $this) {
                $mark->setCar(null);
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
