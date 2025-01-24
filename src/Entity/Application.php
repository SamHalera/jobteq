<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;



    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JobOffer $jobOffer = null;

    #[ORM\Column(length: 255)]
    private ?string $resume = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motivationalMessage = null;

    #[ORM\Column(enumType: StatusApplicationEnum::class)]
    private StatusApplicationEnum $status;

    #[ORM\ManyToOne(inversedBy: 'examinerApplications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $examiner = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Candidate $candidate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $customPresentation = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }



    public function getJobOffer(): ?JobOffer
    {
        return $this->jobOffer;
    }

    public function setJobOffer(?JobOffer $jobOffer): static
    {
        $this->jobOffer = $jobOffer;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function getMotivationalMessage(): ?string
    {
        return $this->motivationalMessage;
    }

    public function setMotivationalMessage(?string $motivationalMessage): static
    {
        $this->motivationalMessage = $motivationalMessage;

        return $this;
    }

    public function getStatus(): StatusApplicationEnum
    {
        return $this->status;
    }

    public function setStatus(StatusApplicationEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusString(): string
    {
        return $this->status->value;
    }

    public function getExaminer(): ?User
    {
        return $this->examiner;
    }

    public function setExaminer(?User $examiner): static
    {
        $this->examiner = $examiner;

        return $this;
    }

    public function getCandidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function setCandidate(?Candidate $candidate): static
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getCustomPresentation(): ?string
    {
        return $this->customPresentation;
    }

    public function setCustomPresentation(?string $customPresentation): static
    {
        $this->customPresentation = $customPresentation;

        return $this;
    }
}
