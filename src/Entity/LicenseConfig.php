<?php

namespace App\Entity;

use App\Repository\LicenseConfigRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LicenseConfigRepository::class)]
class LicenseConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, License>
     */
    #[ORM\OneToMany(targetEntity: License::class, mappedBy: 'licenseConfig', orphanRemoval: true, cascade: ['persist'])]
    private Collection $licenses;

    public function __construct()
    {
        $this->licenses = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, License>
     */
    public function getLicenses(): Collection
    {
        return $this->licenses;
    }

    public function addLicense(License $license): static
    {
        if (!$this->licenses->contains($license)) {
            $this->licenses->add($license);
            $license->setLicenseConfig($this);
        }

        return $this;
    }

    public function removeLicense(License $license): static
    {
        if ($this->licenses->removeElement($license)) {
            // set the owning side to null (unless already changed)
            if ($license->getLicenseConfig() === $this) {
                $license->setLicenseConfig(null);
            }
        }

        return $this;
    }
}
