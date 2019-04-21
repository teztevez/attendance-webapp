<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClockingsRepository")
 */
class Clockings
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
    private $emp_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $direction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $punctual;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmpId(): ?string
    {
        return $this->emp_id;
    }

    public function setEmpId(string $emp_id): self
    {
        $this->emp_id = $emp_id;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getPunctual(): ?string
    {
        return $this->punctual;
    }

    public function setPunctual(string $punctual): self
    {
        $this->punctual = $punctual;

        return $this;
    }
}
