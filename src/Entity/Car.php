<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide")]
    #[Assert\Regex("/^[A-Z]{2}-\d{3}-[A-Z]{2}$/", "La plate d'immatriculation n'est pas conforme")]
    private ?string $licensePlate = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $firstRegistration = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide")]
    #[Assert\Regex("/^[A-Za-z0-9&'’.\- ]{2,50}$/", "Le modèle n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50")]
    private ?string $model = null;


    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide")]
    #[Assert\Regex("/^[A-Za-z0-9&'’.\- ]{2,50}$/", "La couleur n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50")]
    private ?string $color = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le champ ne peut pas être vide")]
    #[Assert\Regex("/^[A-Za-z0-9&'’.\- ]{2,50}$/", "Le modèle n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50")]
    private ?string $energie = null;

    #[ORM\Column]
    #[Assert\Positive(message: "Le nombre de passager doit être supérieur à 0")]
    #[Assert\LessThan(value:10, message:"Le nombre de passager doit être inférieur à 10")]
    private ?int $nbPassenger = null;


    #[ORM\ManyToOne(inversedBy: 'Car')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'car')]
    private ?Mark $mark = null;

    /**
     * @var Collection<int, Carpool>
     */
    #[ORM\OneToMany(targetEntity: Carpool::class, mappedBy: 'car')]
    private Collection $carpool;

    public function __construct()
    {
        $this->carpool = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLicensePlate(): ?string
    {
        return $this->licensePlate;
    }

    public function setLicensePlate(string $licensePlate): self
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    public function getFirstRegistration(): ?\DateTimeInterface
    {
        return $this->firstRegistration;
    }

    public function setFirstRegistration(\DateTimeInterface $firstRegistration): self
    {
        $this->firstRegistration = $firstRegistration;

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

    public function getNbPassenger(): ?int
    {
        return $this->nbPassenger;
    }
    public function setNbPassenger(int $nbPassenger): static
    {
        $this->nbPassenger = $nbPassenger;
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

    public function getMark(): ?Mark
    {
        return $this->mark;
    }

    public function setMark(?Mark $mark): static
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * @return Collection<int, Carpool>
     */
    public function getCarpool(): Collection
    {
        return $this->carpool;
    }

    public function addCarpool(Carpool $carpool): static
    {
        if (!$this->carpool->contains($carpool)) {
            $this->carpool->add($carpool);
            $carpool->setCar($this);
        }

        return $this;
    }

    public function removeCarpool(Carpool $carpool): static
    {
        if ($this->carpool->removeElement($carpool)) {
            // set the owning side to null (unless already changed)
            if ($carpool->getCar() === $this) {
                $carpool->setCar(null);
            }
        }

        return $this;
    }

   
}
