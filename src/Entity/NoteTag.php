<?php

namespace App\Entity;

use App\Repository\NoteTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NoteTagRepository::class)
 * @ORM\Table(name="note_tags")
 *
 * @UniqueEntity(fields={"name"})
 */
class NoteTag
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * name.
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
     *     min="1",
     *     max="64",
     * )
     */
    private $name;

    /**
     * Notes.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Entity\Note[] ToDoLists
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Note", mappedBy="noteTags")
     *
     * Assert\Type(type="Doctrine\Common\Collections\ArrayCollection")
     */
    private $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Note[] Notes collection
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    /**
     * @return $this
     */
    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->addNoteTag($this);
        }

        return $this;
    }

    /**
     * Remove Note from collection.
     *
     * @param \App\Entity\Note $note Note entity
     */
    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            $note->removeNoteTag($this);
        }

        return $this;
    }
}
