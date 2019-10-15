<?php

namespace App\Entity;

use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Author
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $surname;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Book", mappedBy="author")
     */
    private $books;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    public function __construct(string $name, string $surname)
    {
        $this->name    = $name;
        $this->surname = $surname;
        $this->books   = new ArrayCollection();
    }

    public function addBook(Book $book)
    {
        $this->books->add($book);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getFullName(): string
    {
        return sprintf("%s %s", $this->name, $this->surname);
    }

    public function getBooks(): ?array
    {
        return $this->books->toArray();
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    /**
     * @ORM\PrePersist
     * @return void
     */
    public function onPrePersist(): void
    {
        $this->created = Carbon::now();
    }

    /**
     * @ORM\PreUpdate
     * @return void
     */
    public function onPreUpdate(): void
    {
        $this->updated = Carbon::now();
    }
}
