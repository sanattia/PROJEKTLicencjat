<?php
/**
 * Trasa Entity.
 */

namespace App\Entity;

use App\Repository\TrasaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=TrasaRepository::class)
 * @ORM\Table(name="trasy")
 * @Vich\Uploadable
 */
class Trasa
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
     * Name.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     * )
     *
     * @Assert\Type(type="string")

     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type(type="\DateTimeInterface")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Type(type="\DateTimeInterface")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    /**
     * punktStartowy.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=25,
     * )
     *
     * @Assert\Type(type="string")
     */
    private $test;


    /**
     * punktStartowy.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     * )
     *
     * @Assert\Type(type="string")
     */
    private $punktStartowy;

    /**
     * punktKoncowy.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     * )
     *
     * @Assert\Type(type="string")
     */
    private $punktKoncowy;


    /**
     * adres.
     *
     *
     * @ORM\Column(
     *     type="text",
     *     nullable=true,
     * )
     *
     */
    private $adres;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName", size="imageSize")
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"noPic.png"})
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    private $imageSize;


    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $czas;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class)
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity=Trudnosc::class)
     */
    private $trudnosc;

    /**
     * Author.
     *
     * @var User|null
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $author;

    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity=Tag::class, inversedBy="trasy",
     * )
     * @ORM\JoinTable(name="trasy_tags")
     */
    private $tags;


    /**
     * Trasa constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * Getter for Name.
     *
     * @return string|null Result
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param string $name Name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for Test.
     *
     * @return string|null Result
     */
    public function getTest(): ?string
    {
        return $this->test;
    }

    /**
     * Setter for Test.
     *
     * @param string $test Test
     */
    public function setTest(string $test): void
    {
        $this->test = $test;
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    /**
     * Getter for Adres.
     *
     * @return string|null Result
     */
    public function getAdres(): ?string
    {
        return $this->adres;
    }

    /**
     * Setter for adres.
     *
     * @param string $adres Adres
     */
    public function setAdres(string $adres): void
    {
        $this->adres = $adres;
    }
    /**
     * Getter for Created At.
     *
     * @return \DateTimeInterface|null Created at
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Setter for Created at.
     *
     * @param \DateTimeInterface $createdAt Created at
     *
     * @return \DateTimeInterface|null Created at
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * Getter for Updated At.
     *
     * @return \DateTimeInterface|null Updated at
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }


    /**
     * Setter for Updated at.
     *
     * @param \DateTimeInterface $updatedAt Updated at
     *
     * @return \DateTimeInterface|null Updated at
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Getter for PunktStartowy.
     *
     * @return string|null Result
     */
    public function getPunktStartowy(): ?string
    {
        return $this->punktStartowy;
    }

    /**
     * Setter for PunktStartowy.
     *
     * @param string $punktStartowy PunktStartowy
     */
    public function setPunktStartowy(string $punktStartowy): void
    {
        $this->punktStartowy = $punktStartowy;
    }

    /**
     * Getter for PunktKoncowy.
     *
     * @return string|null Result
     */
    public function getPunktKoncowy(): ?string
    {
        return $this->punktKoncowy;
    }

    /**
     * Setter for PunktKoncowy.
     *
     * @param string $punktKoncowy PunktKoncowy
     */
    public function setPunktKoncowy(string $punktKoncowy): void
    {
        $this->punktKoncowy = $punktKoncowy;
    }

    public function getCzas(): ?\DateTimeInterface
    {
        return $this->czas;
    }

    public function setCzas(?\DateTimeInterface $czas): self
    {
        $this->czas = $czas;

        return $this;
    }

    /**
     * Getter for region.
     *
     * @return \App\Entity\Region|null Region
     */
    public function getRegion(): ?Region
    {
        return $this->region;
    }

    /**
     * Setter for region.
     *
     * @return \App\Entity\Region|null Region
     *
     * @param \App\Entity\Region|null $region Region
     */
    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }


    /**
     * Getter for trudnosc.
     *
     * @return \App\Entity\Trudnosc|null Trudnosc
     */
    public function getTrudnosc(): ?Trudnosc
    {
        return $this->trudnosc;
    }

    /**
     * Setter for trudnosc.
     *
     * @return \App\Entity\Trudnosc|null Trudnosc
     *
     * @param \App\Entity\Trudnosc|null $trudnosc Trudnosc
     */
    public function setTrudnosc(?Trudnosc $trudnosc): self
    {
        $this->trudnosc = $trudnosc;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Getter for tags.
     *
     * @return Collection Tags collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add tag to collection.
     *
     * @param \App\Entity\Tag $tag Tag entity
     */
    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    /**
     * Remove tag from collection.
     *
     * @param \App\Entity\Tag $tag Tag entity
     */
    public function removeTag(Tag $tag): void
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
    }

}
