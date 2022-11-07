<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $beginAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column(length: 255)]
    private ?string $backgroundcolor = null;

    #[ORM\Column(length: 255)]
    private ?string $bordercolor = null;

    #[ORM\Column(length: 255)]
    private ?string $textcolor = null;

    #[ORM\ManyToOne(inversedBy: 'evenement')]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $public = null;

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

    public function getBeginAt(): ?\DateTimeImmutable
    {
        return $this->beginAt;
    }

    public function setBeginAt(\DateTimeImmutable $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getBackgroundcolor(): ?string
    {
        return $this->backgroundcolor;
    }

    public function setBackgroundcolor(string $backgroundcolor): self
    {
        $this->backgroundcolor = $backgroundcolor;

        return $this;
    }

    public function getBordercolor(): ?string
    {
        return $this->bordercolor;
    }

    public function setBordercolor(string $bordercolor): self
    {
        $this->bordercolor = $bordercolor;

        return $this;
    }

    public function getTextcolor(): ?string
    {
        return $this->textcolor;
    }

    public function setTextcolor(string $textcolor): self
    {
        $this->textcolor = $textcolor;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }
}
