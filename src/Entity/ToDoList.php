<?php

namespace App\Entity;

use App\Repository\ToDoListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ToDoListRepository::class)
 * @ORM\Table(name="to_do_lists")
 */
class ToDoList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="smallint")
     */
    private $done;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation;

    /**
     * @ORM\OneToMany(targetEntity=ListElement::class, mappedBy="to_do_list", orphanRemoval=true)
     */
    private $listElements;

    /**
     * @ORM\OneToMany(targetEntity=ListComment::class, mappedBy="to_do_list", orphanRemoval=true)
     */
    private $listComments;

    /**
     * @ORM\ManyToOne(targetEntity=ListCategory::class, inversedBy="toDoLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=ListTag::class, mappedBy="toDoList")
     */
    private $listTags;

    public function __construct()
    {
        $this->listElements = new ArrayCollection();
        $this->listComments = new ArrayCollection();
        $this->listTags = new ArrayCollection();
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

    public function getDone(): ?int
    {
        return $this->done;
    }

    public function setDone(int $done): self
    {
        $this->done = $done;

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

    /**
     * @return Collection|ListElement[]
     */
    public function getListElements(): Collection
    {
        return $this->listElements;
    }

    public function addListElement(ListElement $listElement): self
    {
        if (!$this->listElements->contains($listElement)) {
            $this->listElements[] = $listElement;
            $listElement->setToDoList($this);
        }

        return $this;
    }

    public function removeListElement(ListElement $listElement): self
    {
        if ($this->listElements->contains($listElement)) {
            $this->listElements->removeElement($listElement);
            // set the owning side to null (unless already changed)
            if ($listElement->getToDoList() === $this) {
                $listElement->setToDoList(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ListComment[]
     */
    public function getListComments(): Collection
    {
        return $this->listComments;
    }

    public function addListComment(ListComment $listComment): self
    {
        if (!$this->listComments->contains($listComment)) {
            $this->listComments[] = $listComment;
            $listComment->setToDoList($this);
        }

        return $this;
    }

    public function removeListComment(ListComment $listComment): self
    {
        if ($this->listComments->contains($listComment)) {
            $this->listComments->removeElement($listComment);
            // set the owning side to null (unless already changed)
            if ($listComment->getToDoList() === $this) {
                $listComment->setToDoList(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?ListCategory
    {
        return $this->category;
    }

    public function setCategory(?ListCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|ListTag[]
     */
    public function getListTags(): Collection
    {
        return $this->listTags;
    }

    public function addListTag(ListTag $listTag): self
    {
        if (!$this->listTags->contains($listTag)) {
            $this->listTags[] = $listTag;
            $listTag->addToDoList($this);
        }

        return $this;
    }

    public function removeListTag(ListTag $listTag): self
    {
        if ($this->listTags->contains($listTag)) {
            $this->listTags->removeElement($listTag);
            $listTag->removeToDoList($this);
        }

        return $this;
    }
}
