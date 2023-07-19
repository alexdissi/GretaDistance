<?php

namespace App\Entity;

use App\Repository\CoursFinishRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursFinishRepository::class)]
class CoursFinish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isFinish = null;

    #[ORM\ManyToOne(inversedBy: 'coursfinish')]
    private ?Cours $cours = null;

    #[ORM\ManyToOne(inversedBy: 'coursFinish')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsFinish(): ?bool
    {
        return $this->isFinish;
    }

    public function setIsFinish(bool $isFinish): static
    {
        $this->isFinish = $isFinish;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): static
    {
        $this->cours = $cours;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
