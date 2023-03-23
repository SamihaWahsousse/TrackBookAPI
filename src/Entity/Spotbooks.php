<?php

namespace App\Entity;

use App\Repository\SpotbooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpotbooksRepository::class)]
class Spotbooks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['spotBooks:read'])]
    #[Assert\NotBlank]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Groups(['spotBooks:read'])]
    #[Assert\NotBlank]
    private ?string $street = null;

    #[ORM\Column]
    #[Groups(['spotBooks:read'])]
    #[Assert\NotBlank]
    private ?int $zipcode = null;

    #[ORM\Column(length: 255)]
    #[Groups(['spotBooks:read'])]
    #[Assert\NotBlank]
    private ?string $city = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['spotBooks:read'])]
    #[Assert\NotBlank]

    private array $goelocalisation = [];

    #[ORM\Column]
    #[Groups(['spotBooks:read'])]
    #[Assert\NotBlank]

    private ?int $capacity = null;

    #[ORM\OneToMany(mappedBy: 'spotBooks', targetEntity: Book::class)]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getGoelocalisation(): array
    {
        return $this->goelocalisation;
    }

    public function setGoelocalisation(array $goelocalisation): self
    {
        $this->goelocalisation = $goelocalisation;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setSpotBooks($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getSpotBooks() === $this) {
                $book->setSpotBooks(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->street; // Remplacer champ par une propriété "string" de l'entité
    }
}
