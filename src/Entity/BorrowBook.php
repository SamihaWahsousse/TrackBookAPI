<?php

namespace App\Entity;

use App\Repository\BorrowBookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BorrowBookRepository::class)]
class BorrowBook
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['history:read'])]

    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['history:read'])]
    private ?\DateTimeInterface $borrowDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['history:read'])]
    private ?\DateTimeInterface $returnDate = null;

    #[ORM\ManyToOne(inversedBy: 'borrowBooks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['history:read'])]

    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'borrowBooks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['history:read'])]

    private ?Book $book = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorrowDate(): ?\DateTimeInterface
    {
        return $this->borrowDate;
    }

    public function setBorrowDate(\DateTimeInterface $borrowDate): self
    {
        $this->borrowDate = $borrowDate;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    public function setReturnDate(\DateTimeInterface $returnDate): self
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}