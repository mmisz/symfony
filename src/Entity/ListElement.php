<?php

namespace App\Entity;

use App\Repository\ListElementRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ListElementRepository", repositoryClass=ListElementRepository::class)
 * @ORM\Table(name="list_elements")
 */
class ListElement
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
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="1",
     *     max="255",
     * )
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=ToDoList::class, inversedBy="listElements")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type(type="App\Entity\ToDoList")
     */
    private $to_do_list;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $done_date;

    /**
     * @ORM\ManyToOne(targetEntity=ListElementStatus::class, inversedBy="listElements")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type(type="App\Entity\ListElementStatus")
     */
    private $status;

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
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for Content.
     *
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return ToDoList|null
     */
    public function getToDoList(): ?ToDoList
    {
        return $this->to_do_list;
    }

    /**
     * Setter for ToDoList.
     *
     * @param ToDoList|null $to_do_list
     */
    public function setToDoList(?ToDoList $to_do_list): void
    {
        $this->to_do_list = $to_do_list;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreation(): ?DateTimeInterface
    {
        return $this->creation;
    }

    /**
     * Setter for Creation.
     * @param DateTimeInterface $creation
     */
    public function setCreation(DateTimeInterface $creation): void
    {
        $this->creation = $creation;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDoneDate(): ?DateTimeInterface
    {
        return $this->done_date;
    }

    /**
     * Setter for DoneDate.
     *
     * @param DateTimeInterface|null $done_date
     */
    public function setDoneDate(?DateTimeInterface $done_date): void
    {
        $this->done_date = $done_date;
    }

    /**
     * @return ListElementStatus|null
     */
    public function getStatus(): ?ListElementStatus
    {
        return $this->status;
    }

    /**
     * Setter for Status.
     *
     * @param ListElementStatus|null $status\
     */
    public function setStatus(?ListElementStatus $status): void
    {
        $this->status = $status;
    }
}
