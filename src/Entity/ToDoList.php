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
 * @ORM\Entity(repositoryClass=ToDoListRepository::class)
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
     */
    private $title;

    /**
     * @ORM\Column(type="smallint")
     */
    private $done;

    /**
     * Created at.
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
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
     * @ORM\ManyToOne(targetEntity=ListCategory::class, inversedBy="to_do_lists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;
    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\ListTag",
     *     inversedBy="to_do_lists",
     *     orphanRemoval=true
     * )
     * @ORM\JoinTable(name="to_do_list_list_tag")
     */
    private $listTag;

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
     * @return int|null
     */
    public function getDone(): ?int
    {
        return $this->done;
    }

    /**
     * @param int $done
     * @return $this
     */
    public function setDone(int $done): self
    {
        $this->done = $done;

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
}
