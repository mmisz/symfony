<?php

namespace App\Entity;

use App\Repository\ListElementRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=ListElementRepository::class)
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
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=ToDoList::class, inversedBy="listElements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $to_do_list;


    /**
     * @ORM\Column(type="datetime")
     */
    private $creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $done_date;

    /**
     * @ORM\ManyToOne(targetEntity=ListElementStatus::class, inversedBy="listElements")
     * @ORM\JoinColumn(nullable=false)
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
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return ToDoList|null
     */
    public function getToDoList(): ?ToDoList
    {
        return $this->to_do_list;
    }

    /**
     * @param ToDoList|null $to_do_list
     * @return $this
     */
    public function setToDoList(?ToDoList $to_do_list): self
    {
        $this->to_do_list = $to_do_list;

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
    public function getDoneDate(): ?\DateTimeInterface
    {
        return $this->done_date;
    }

    /**
     * @param \DateTimeInterface|null $done_date
     * @return $this
     */
    public function setDoneDate(?\DateTimeInterface $done_date): self
    {
        $this->done_date = $done_date;

        return $this;
    }

    /**
     * @return ListElementStatus|null
     */
    public function getStatus(): ?ListElementStatus
    {
        return $this->status;
    }

    /**
     * @param ListElementStatus|null $status
     * @return $this
     */
    public function setStatus(?ListElementStatus $status): self
    {
        $this->status = $status;

        return $this;
    }
}
