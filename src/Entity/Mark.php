<?php

namespace App\Entity;

use App\Repository\MarkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MarkRepository::class)]
class Mark
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le champ ne peux pas être vide")]
    #[Assert\Regex("/^[A-Za-z0-9&'’.\- ]{2,50}$/", "Le modèle n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50")]
    private ?string $label = null;

    /**
     * @var Collection<int, Car>
     */
    #[ORM\OneToMany(targetEntity: Car::class, mappedBy: 'mark')]
    private Collection $car;

    public function __construct()
    {
        $this->car = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCar(): Collection
    {
        return $this->car;
    }

    public function addCar(Car $car): static
    {
        if (!$this->car->contains($car)) {
            $this->car->add($car);
            $car->setMark($this);
        }

        return $this;
    }

    public function removeCar(Car $car): static
    {
        if ($this->car->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getMark() === $this) {
                $car->setMark(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLabel();
    }
}
