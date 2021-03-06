<?php

namespace App\Entity;

use App\Repository\ToDoListRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ToDoListRepository", repositoryClass=ToDoListRepository::class)
 * @ORM\Table(name="to_do_lists")
 */
class ToDoList
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
     * Title.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     * )
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="1",
     *     max="255",
     * )
     */
    private $title;

    /**
     * Created at.
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @Assert\DateTime
     */
    private $creation;

    /**
     * @ORM\OneToMany(targetEntity=ListElement::class, mappedBy="to_do_list", orphanRemoval=true)
     *
     * @Assert\All({
     * @Assert\Type(type="App\Entity\ListElement")
     * })
     */
    private $listElements;

    /**
     * @ORM\OneToMany(targetEntity=ListComment::class, mappedBy="to_do_list", orphanRemoval=true)
     *
     * @Assert\All({
     * @Assert\Type(type="App\Entity\ListComment")
     * })
     */
    private $listComments;

    /**
     * @ORM\ManyToOne(targetEntity=ListCategory::class, inversedBy="toDoLists")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type(type="App\Entity\ListCategory")
     */
    private $category;
    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\ListTag",
     *     inversedBy="toDoLists",
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true
     * )
     * @ORM\JoinTable(name="to_do_list_list_tag")
     *
     * @Assert\All({
     * @Assert\Type(type="App\Entity\ListTag")
     * })
     */
    private $listTag;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $done_date;

    /**
     * @ORM\ManyToOne(targetEntity=ListStatus::class, inversedBy="toDoLists")
     * @ORM\JoinColumn(nullable=false)
     *
     * Assert\Type(type="App\Entity\ListStatus")
     */
    private $status;

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

    /**
     * ToDoList constructor.
     */
    public function __construct()
    {
        $this->listElements = new ArrayCollection();
        $this->listComments = new ArrayCollection();
        $this->listTag = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreation(): DateTimeInterface
    {
        return $this->creation;
    }

    /**
     * setter for Creation.
     * @param DateTimeInterface $creation
     */
    public function setCreation(\DateTimeInterface $creation): void
    {
        $this->creation = $creation;
    }

    /**
     * @return Collection|ListElement[]
     */
    public function getListElements(): Collection
    {
        return $this->listElements;
    }

    /**
     * @return $this
     */
    public function addListElement(ListElement $listElement): self
    {
        if (!$this->listElements->contains($listElement)) {
            $this->listElements[] = $listElement;
            $listElement->setToDoList($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
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

    /**
     * @return $this
     */
    public function addListComment(ListComment $listComment): self
    {
        if (!$this->listComments->contains($listComment)) {
            $this->listComments[] = $listComment;
            $listComment->setToDoList($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
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

    /**
     * Setter for Category.
     *
     * @param ListCategory|null $category
     */
    public function setCategory(?ListCategory $category): void
    {
        $this->category = $category;
    }

    /**
     * Getter for listTag.
     *
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\ListTag[] ListTag collection
     */
    public function getListTag(): Collection
    {
        return $this->listTag;
    }

    /**
     * @return $this
     */
    public function addListTag(ListTag $listTag): self
    {
        if (!$this->listTag->contains($listTag)) {
            $this->listTag[] = $listTag;
            $listTag->addToDoList($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeListTag(ListTag $listTag): self
    {
        if ($this->listTag->contains($listTag)) {
            $this->listTag->removeElement($listTag);
            $listTag->removeToDoList($this);
        }

        return $this;
    }

    public function getDoneDate(): ?\DateTimeInterface
    {
        return $this->done_date;
    }

    /**
     * Setter for DoneDate.
     *
     * @param DateTimeInterface|null $done_date
     */
    public function setDoneDate(?\DateTimeInterface $done_date): void
    {
        $this->done_date = $done_date;
    }

    public function getStatus(): ?ListStatus
    {
        return $this->status;
    }

    /**
     * Setter for Status.
     * @param ListStatus|null $status
     */
    public function setStatus(?ListStatus $status): void
    {
        $this->status = $status;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for Author.
     * @param User|null $author
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }
}
