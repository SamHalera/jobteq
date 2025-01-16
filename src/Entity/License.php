<?php

namespace App\Entity;

use App\Repository\LicenseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LicenseRepository::class)]
class License
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $credits = null;

    #[ORM\ManyToOne(inversedBy: 'licenses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LicenseConfig $licenseConfig = null;


    #[ORM\Column(enumType: StatusEnum::class)]
    private StatusEnum $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCredits(): ?int
    {
        return $this->credits;
    }

    public function setCredits(int $credits): static
    {
        $this->credits = $credits;

        return $this;
    }

    public function getLicenseConfig(): ?LicenseConfig
    {
        return $this->licenseConfig;
    }

    public function setLicenseConfig(?LicenseConfig $licenseConfig): static
    {
        $this->licenseConfig = $licenseConfig;

        return $this;
    }
    public function getStatus(): StatusEnum
    {
        return $this->status;
    }

    public function setStatus(StatusEnum $status): self
    {
        $this->status = $status;
        return $this;
    }
    public function getStatusString(): string
    {
        return $this->status->value;
    }
}
