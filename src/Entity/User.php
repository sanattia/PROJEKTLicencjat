<?php
/**
 * User entity.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="email_idx",
 *              columns={"email"},
 *          )
 *     }
 * )
 *
 * @UniqueEntity(fields={"email"})
 *
 * @method string getUserIdentifier()
 */
class User implements UserInterface, \Serializable
{
    /**
     * A non-persisted field that's used to create the encoded password.
     *
     * @var string
     */
    private $plainPassword;

    /**
     * Role user.
     *
     * @var string
     */
    public const ROLE_USER = 'ROLE_USER';

    /**
     * Role admin.
     *
     * @var string
     */
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     nullable=false,
     *     options={"unsigned"=true},
     * )
     */
    private $id;

    /**
     * E-mail.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=180,
     *     unique=true,
     * )
     *
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * Username.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * Roles.
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * The hashed password.
     *
     * @var string
     *
     * @ORM\Column(type="string")

     * @Assert\NotBlank
     * @Assert\Type(type="string")
     *
     * @SecurityAssert\UserPassword
     */
    private $password;

    /**
     * Odznaki.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity=Odznaka::class, inversedBy="users")
     * @ORM\JoinTable(name="users_odznaki")
     */
    private $odznaki;

    /**
     * @ORM\OneToOne(targetEntity=Avatar::class, mappedBy="author", cascade={"persist", "remove"})
     */
    private $avatar;

    public function __construct()
    {
        $this->odznaki = new ArrayCollection();
    }

    /**
     * Getter for the Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for the E-mail.
     *
     * @return string|null E-mail
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for the E-mail.
     *
     * @param string $email E-mail
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Getter for the Username.
     *
     * @return string|null Username
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }


    /**
     * Setter for the Username.
     *
     * @param string $username Username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Getter for the Roles.
     *
     * @see UserInterface
     *
     * @return array Roles
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = static::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * Setter for the Roles.
     *
     * @param array $roles Roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Getter for the Password.
     *
     * @see UserInterface
     *
     * @return string|null Password
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * Setter for the Password.
     *
     * @param string $password Password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * Getter for Plain Password.
     *
     * @return string|null Result
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Setter for Plain Password.
     *
     * @param string $plainPassword Plain Password
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return email email
     *
     */
    public function __toString(): string
    {
        return $this->email;
    }

    /**
     * Getter for odznaki.
     *
     * @return Collection Odznaki collection
     */
    public function getOdznaki(): Collection
    {
        return $this->odznaki;
    }

    /**
     * Add odznaka to collection.
     *
     * @param \App\Entity\Odznaka $odznaka Odznaka entity
     */
    public function addOdznaka(Odznaka $odznaka): void
    {
        if (!$this->odznaki->contains($odznaka)) {
            $this->odznaki[] = $odznaka;
        }
    }

    /**
     * Remove odznaka from collection.
     *
     * @param \App\Entity\Odznaka $odznaka Odznaka entity
     */
    public function removeOdznaka(Odznaka $odznaka): void
    {
        if ($this->odznaki->contains($odznaka)) {
            $this->odznaki->removeElement($odznaka);
        }
    }

    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    public function setAvatar(Avatar $avatar): self
    {
        // set the owning side of the relation if necessary
        if ($avatar->getUser() !== $this) {
            $avatar->setUser($this);
        }

        $this->avatar = $avatar;

        return $this;
    }

    public function serialize(): ?string
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            $this->username,
            $this->roles,
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            $this->username,
            $this->roles,
            ) = unserialize($serialized);
    }
}
