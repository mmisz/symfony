<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 * @ORM\Table(name="notes")
 *
 */
class Note
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Title.
     *
     * @var string
     *
     * @ORM\Column(type="string",
     *     length=150)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="1",
     *     max="150",
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $last_update;

    /**
     * NoteTags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\NoteTag",
     *     inversedBy="notes",
     *     orphanRemoval=true
     * )
     *
     * @Assert\All({
     * @Assert\Type(type="App\Entity\NoteTag")
     * })
     */
    private $noteTags;

    /**
     * Category.
     *
     * @var \App\Entity\NoteCategory NoteCategory
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\NoteCategory",
     *     inversedBy="notes",
     * )
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type(type="App\Entity\NoteCategory")
     */
    private $category;

    /**
     * Author.
     *
     * @var \App\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type(type="App\Entity\User")
     */
    private $author;

    public function __construct()
    {
        $this->noteTags = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    /**
     * @param \DateTimeInterface $creation
     * @return $this
     */
    public function setCreation(\DateTimeInterface $creation): self
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->last_update;
    }

    /**
     * @param \DateTimeInterface|null $last_update
     * @return $this
     */
    public function setLastUpdate(?\DateTimeInterface $last_update): self
    {
        $this->last_update = $last_update;

        return $this;
    }

    /**
     * Getter for noteTags.
     *
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\NoteTag[] NoteTags collection
     */
    public function getNoteTags(): Collection
    {
        return $this->noteTags;
    }

    /**
     * @param NoteTag $noteTag
     * @return $this
     */
    public function addNoteTag(NoteTag $noteTag): self
    {
        if (!$this->noteTags->contains($noteTag)) {
            $this->noteTags[] = $noteTag;
        }

        return $this;
    }

    /**
     * @param NoteTag $noteTag
     * @return $this
     */
    public function removeNoteTag(NoteTag $noteTag): self
    {
        if ($this->noteTags->contains($noteTag)) {
            $this->noteTags->removeElement($noteTag);
        }

        return $this;
    }

    /**
     * @return NoteCategory|null
     */
    public function getCategory(): ?NoteCategory
    {
        return $this->category;
    }

    /**
     * @param NoteCategory|null $category
     * @return $this
     */
    public function setCategory(?NoteCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     * @return $this
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
