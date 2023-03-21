<?php
/**
 * Tag entity.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 *
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(name="tags")
 *
 * @UniqueEntity(fields={"title"})
 */
class Tag
{
    /**
     * Id.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Title.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=64,
     * )
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="64",
     * )
     */
    private $title;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $komentarz;

    /**
     * @ORM\ManyToMany(targetEntity=Trasa::class, mappedBy="tags",)
     */
    private $trasy;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->trasy = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for Title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for trasy.
     *
     * @return Collection Trasy collection
     */
    public function getTrasy(): Collection
    {
        return $this->trasy;
    }

    /**
     * Add trasa to collection.
     *
     * @param \App\Entity\Trasa $trasa Trasa entity
     */
    public function addTrasa(Trasa $trasa): void
    {
        if (!$this->trasy->contains($trasa)) {
            $this->trasy[] = $trasa;
            $trasa->addTag($this);
        }
    }

    /**
     * Remove trasa from collection.
     *
     * @param \App\Entity\Trasa $trasa Trasa entity
     */
    public function removeTrasa(Trasa $trasa): void
    {
        if ($this->trasy->contains($trasa)) {
            $this->trasy->removeElement($trasa);
            $trasa->removeTag($this);
        }
    }

    public function getKomentarz(): ?string
    {
        return $this->komentarz;
    }

    public function setKomentarz(?string $komentarz): self
    {
        $this->komentarz = $komentarz;

        return $this;
    }
}
