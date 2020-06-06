<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 * @ORM\Table(name="notes")
 *
 * @UniqueEntity(fields={"title"})
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
     * @ORM\Column(type="string", length=150)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
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
     * @ORM\JoinTable(name="note_note_tag")
     */
    private $noteTags;

    /**
     * @ORM\ManyToOne(targetEntity=NoteCategory::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function __construct()
    {
        $this->noteTags = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): self
    {
        $this->creation = $creation;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->last_update;
    }

    public function setLastUpdate(?\DateTimeInterface $last_update): self
    {
        $this->last_update = $last_update;

        return $this;
    }

    /**
     * @return Collection|NoteTag[]
     */
    public function getNoteTags(): Collection
    {
        return $this->noteTags;
    }

    public function addNoteTag(NoteTag $noteTag): self
    {
        if (!$this->noteTags->contains($noteTag)) {
            $this->noteTags[] = $noteTag;
        }

        return $this;
    }

    public function removeNoteTag(NoteTag $noteTag): self
    {
        if ($this->noteTags->contains($noteTag)) {
            $this->noteTags->removeElement($noteTag);
        }

        return $this;
    }

    public function getCategory(): ?NoteCategory
    {
        return $this->category;
    }

    public function setCategory(?NoteCategory $category): self
    {
        $this->category = $category;

        return $this;
    }
}
