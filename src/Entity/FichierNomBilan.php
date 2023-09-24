<?php

namespace App\Entity;

use App\Repository\FichierNomBilanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FichierNomBilanRepository::class)]
class FichierNomBilan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fichier_bilan = null;

    #[ORM\OneToMany(mappedBy: 'id_fichier_bilan', targetEntity: FichierBilan::class)]
    private Collection $fichierBilans;

    public function __construct()
    {
        $this->fichierBilans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFichierBilan(): ?string
    {
        return $this->fichier_bilan;
    }

    public function setFichierBilan(string $fichier_bilan): static
    {
        $this->fichier_bilan = $fichier_bilan;

        return $this;
    }

    /**
     * @return Collection<int, FichierBilan>
     */
    public function getFichierBilans(): Collection
    {
        return $this->fichierBilans;
    }

    public function addFichierBilan(FichierBilan $fichierBilan): static
    {
        if (!$this->fichierBilans->contains($fichierBilan)) {
            $this->fichierBilans->add($fichierBilan);
            $fichierBilan->setIdFichierBilan($this);
        }

        return $this;
    }

    public function removeFichierBilan(FichierBilan $fichierBilan): static
    {
        if ($this->fichierBilans->removeElement($fichierBilan)) {
            // set the owning side to null (unless already changed)
            if ($fichierBilan->getIdFichierBilan() === $this) {
                $fichierBilan->setIdFichierBilan(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getFichierBilan() ?? '';
    }
}
