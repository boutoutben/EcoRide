<?php

namespace App\Entity;

use App\Repository\RolesRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user:read')]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups('user:read')]

    #[Assert\NotBlank(message:"Le champ ne peux pas être vide")]
    #[Assert\Length(min:2, max:50, minMessage:"Le nombre de caractère doit être supérieur ou égal à 2", maxMessage:"Le nombre de caractère doit être inférieur à 50")]
    #[Assert\Regex(pattern: "/^^[a-zA-Z0-9_\-@.]+$/", message: "Le pseudo mis n'est pas conforme",)]
    private ?string $username = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups('user:read')]
    #[Assert\NotBlank(message: "Le champ ne peux pas être vide")]
    private ?string $password = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups('user:read')]
    #[Assert\NotBlank(message: "Le champ ne peux pas être vide")]
    #[Assert\Length(min: 2, max: 50, minMessage: "Le nombre de caractère doit être supérieur ou égal à 2", maxMessage: "Le nombre de caractère doit être inférieur à 50")]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9_\-@.]+$/", message: "Le pseudo mis n'est pas conforme",)]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups('user:read')]
    #[Assert\NotBlank(message: "Le champ ne peux pas être vide")]
    #[Assert\Length(min: 2, max: 50, minMessage: "Le nombre de caractère doit être supérieur ou égal à 2", maxMessage: "Le nombre de caractère doit être inférieur à 50")]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9_\-@.]+$/", message: "Le pseudo mis n'est pas conforme",)]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    #[Groups('user:read')]
    #[Assert\NotBlank(message: "Le champ ne peux pas être vide")]
    #[Assert\Length(max: 50, maxMessage: "Le nombre de caractère doit être inférieur à 50")]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9_\-@.]+$/", message: "Le pseudo mis n'est pas conforme",)]
    #[Assert\Email(message: "Ce champ donc contenir un email")]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    #[Groups('user:read')]
    #[Assert\NotBlank(message: "Le champ ne peux pas être vide")]
    #[Assert\Regex(pattern: "/^0[1-9]( [0-9]{2}){4}$/", message: "Le numéro n'est pas conforme")]
    private ?string $phone = null;
    
    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(message:"Le nombre ne peut pas être négatif")]
    private ?int $nbCredit = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le champ ne peux pas être vide")]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9_\-]+\.(png|jpg|jpeg)$/", message: "Le numéro n'est pas conforme")]
    private ?string $img = null;


    /**
     * @var Collection<int, Car>
     */
    #[ORM\OneToMany(targetEntity: Car::class, mappedBy: 'user')]
    private Collection $Car;

    /**
     * @var Collection<int, Carpool>
     */
    #[ORM\OneToMany(targetEntity: Carpool::class, mappedBy: 'user')]
    private Collection $carpool;

    #[ORM\Column(nullable: true)]
    #[Assert\Length(min: 2, max: 50, minMessage: "Le nombre de caractère doit être supérieur ou égal à 2", maxMessage: "Le nombre de caractère doit être inférieur à 50")]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9_\-@.]+$/", message: "Le type mis n'est pas conforme",)]
    private ?string $userType = null;

    #[Assert\NotBlank(message: "Le champ ne peux pas être vide")]
    #[Assert\Length(max: 50, maxMessage: "Le nombre de caractère doit être inférieur à 50")]
    #[Assert\Regex(pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", message: "Mot de passe non conforme")]
    private $plainPassword;

    #[ORM\Column(type: "json")]
    private $roles = [];

    #[ORM\Column(type: 'json')]
    private $preference = [];

    /**
     * @var Collection<int, CarpoolParticipation>
     */
    #[ORM\OneToMany(targetEntity: CarpoolParticipation::class, mappedBy: 'user')]
    private Collection $carpoolParticipation;

    /**
     * @var Collection<int, Opinion>
     */
    #[ORM\OneToMany(targetEntity: Opinion::class, mappedBy: 'user')]
    private Collection $Opinion;

    public function __construct()
    {
        $this->Car = new ArrayCollection();
        $this->carpool = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->preference = [
            ["Non-fumeur", false],
            ["Sans animaux", false],
        ];
        $this->carpoolParticipation = new ArrayCollection();
        $this->Opinion = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    #[Groups('user:read')]
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */


    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

   

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
    public function getNbCredit(): ?int
    {
        return $this->nbCredit;
    }

    public function setNbCredit(int $nb_credit): static
    {
        $this->nbCredit = $nb_credit;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): static
    {
        $this->img = $img;

        return $this;
    }
    public function getUserType(): ?string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): static
    {
        $this->userType = $userType;
        
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
            $car->setUser($this);
        }

        return $this;
    }

    public function removeCar(Car $car): static
    {
        if ($this->Car->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getUser() === $this) {
                $car->setUser(null);
            }
        }

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
            $carpool->setUser($this);
        }

        return $this;
    }

    public function removeCarpool(Carpool $carpool): static
    {
        if ($this->carpool->removeElement($carpool)) {
            // set the owning side to null (unless already changed)
            if ($carpool->getUser() === $this) {
                $carpool->setUser(null);
            }
        }

        return $this;
    }


    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = "";

        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        

        return $this;
    }

    public function getPreference(): array
    {
        return $this->preference;
    }

    public function setPreference(array $preference): self
    {
        $this->preference = $preference;
        return $this;
    }

    public function addPreference(string $preference): self
    {
        foreach ($this->preference as $item) {
            if ($item[0] === $preference) {
                return $this; // Preference already exists
            }
        }

        // Add the new preference with a default value of `false`
        $this->preference[] = [$preference, false];

        return $this;
    }

    /**
     * @return Collection<int, CarpoolParticipation>
     */
    public function getCarpoolParticipation(): Collection
    {
        return $this->carpoolParticipation;
    }

    public function addCarpoolParticipation(CarpoolParticipation $carpoolParticipation): static
    {
        if (!$this->carpoolParticipation->contains($carpoolParticipation)) {
            $this->carpoolParticipation->add($carpoolParticipation);
            $carpoolParticipation->setUser($this);
        }

        return $this;
    }

    public function removeCarpoolParticipation(CarpoolParticipation $carpoolParticipation): static
    {
        if ($this->carpoolParticipation->removeElement($carpoolParticipation)) {
            // set the owning side to null (unless already changed)
            if ($carpoolParticipation->getUser() === $this) {
                $carpoolParticipation->setUser(null);
            }
        }

        return $this;
    }

    

    /**
     * @return Collection<int, Opinion>
     */
    public function getOpinion(): Collection
    {
        return $this->Opinion;
    }

    public function addOpinion(Opinion $opinion): static
    {
        if (!$this->Opinion->contains($opinion)) {
            $this->Opinion->add($opinion);
            $opinion->setUser($this);
        }

        return $this;
    }

    public function removeOpinion(Opinion $opinion): static
    {
        if ($this->Opinion->removeElement($opinion)) {
            // set the owning side to null (unless already changed)
            if ($opinion->getUser() === $this) {
                $opinion->setUser(null);
            }
        }

        return $this;
    }
}
