<?php

namespace App\Entity;

use App\Repository\OdznakaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OdznakaRepository::class)
 * @ORM\Table(name="odznaki")
 */
class Odznaka
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $punkty;
    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="odznaki",)
     */
    private $users;

    /**
     * Odznaka constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPunkty(): ?int
    {
        return $this->punkty;
    }

    public function setPunkty(?int $punkty): self
    {
        $this->punkty = $punkty;

        return $this;
    }
    /**
     * Getter for users.
     *
     * @return Collection Users collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * Add user to collection.
     *
     * @param \App\Entity\User $user User entity
     */
    public function addUser(User $user): void
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addOdznaka($this);
        }
    }

    /**
     * Remove user from collection.
     *
     * @param \App\Entity\User $user User entity
     */
    public function removeUser(User $user): void
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeOdznaka($this);
        }
    }
}
