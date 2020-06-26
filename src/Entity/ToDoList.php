<?php

namespace App\Entity;

use App\Repository\ToDoListRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ToDoListRepository", repositoryClass=ToDoListRepository::class)
 * @ORM\Table(name="to_do_lists")
 *
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
     * @return DateTimeInterface
     */
    public function getCreation(): DateTimeInterface
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
     * @return Collection|ListElement[]
     */
    public function getListElements(): Collection
    {
        return $this->listElements;
    }

    /**
     * @param ListElement $listElement
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
     * @param ListElement $listElement
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
     * @param ListComment $listComment
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
     * @param ListComment $listComment
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

    /**
     * @return ListCategory|null
     */
    public function getCategory(): ?ListCategory
    {
        return $this->category;
    }

    /**
     * @param ListCategory|null $category
     * @return $this
     */
    public function setCategory(?ListCategory $category): self
    {
        $this->category = $category;

        return $this;
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
     * @param ListTag $listTag
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
     * @param ListTag $listTag
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

    /**
     * @return DateTimeInterface|null
     */
    public function getDoneDate(): ?\DateTimeInterface
    {
        return $this->done_date;
    }

    /**
     * @param DateTimeInterface|null $done_date
     * @return $this
     */
    public function setDoneDate(?\DateTimeInterface $done_date): self
    {
        $this->done_date = $done_date;

        return $this;
    }

    /**
     * @return ListStatus|null
     */
    public function getStatus(): ?ListStatus
    {
        return $this->status;
    }

    /**
     * @param ListStatus|null $status
     * @return $this
     */
    public function setStatus(?ListStatus $status): self
    {
        $this->status = $status;

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
