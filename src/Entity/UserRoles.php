<?php

namespace App\Entity;

use App\Repository\UserRolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRolesRepository::class)]
class UserRoles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Roles>
     */
    #[ORM\OneToMany(targetEntity: Roles::class, mappedBy: 'userRoles')]
    private Collection $role;

    #[ORM\ManyToOne(inversedBy: 'userRoles')]
    private ?Roles $roles = null;

    #[ORM\ManyToOne(inversedBy: 'userRoles')]
    private ?User $user = null;

    public function __construct()
    {
        $this->role = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles(): ?Roles
    {
        return $this->roles;
    }

    public function setRoles(?Roles $roles): static
    {
        $this->roles = $roles;

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
