<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Carbon\Carbon;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LibraryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Library
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Book")
     * @ORM\JoinTable(name="libra_books",
     *     joinColumns={@JoinColumn(name="libra_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="book_id", referencedColumnName="id")})
     */
    private $books;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Author")
     * @ORM\JoinTable(name="libra_authors",
     *     joinColumns={@JoinColumn(name="libra_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="author_id", referencedColumnName="id")})
     */
    private $authors;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\LiteraryType")
     * @ORM\JoinTable(name="libra_lit_types",
     *     joinColumns={@JoinColumn(name="libra_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="lit_type_id", referencedColumnName="id")})
     */
    private $literaryTypes;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $address;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * Library constructor.
     * @param ArrayCollection $authors
     * @param ArrayCollection $books
     * @param ArrayCollection $literaryTypes
     * @param string $address
     */
    public function __construct(ArrayCollection $authors,
                                ArrayCollection $books,
                                ArrayCollection $literaryTypes,
                                string $address)
    {
        $this->authors       = $authors;
        $this->books         = $books;
        $this->literaryTypes = $literaryTypes;
        $this->address       = $address;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooks(): ?array
    {
        return $this->books->toArray();
    }

    public function getAuthors(): ?array
    {
        return $this->authors->toArray();
    }

    public function getLitTypes(): ?array
    {
        return $this->literaryTypes->toArray();
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    public function addAuthor(Author $author)
    {
        $this->authors->add($author);
    }

    public function addBook(Book $book)
    {
        $this->books->add($book);
    }

    public function addLitType(LiteraryType $type)
    {
        $this->literaryTypes->add($type);
    }

    public function setAddress(string $addr)
    {
        $this->address = $addr;

        return $this;
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
