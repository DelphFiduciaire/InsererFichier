<?php

namespace App\Entity;

use App\Repository\FichierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FichierRepository::class)]
class Fichier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_fichier = null;

    #[ORM\OneToMany(mappedBy: 'id_fichier', targetEntity: FichierDemande::class)]
    private Collection $fichierDemandes;

    public function __construct()
    {
        $this->fichierDemandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFichier(): ?string
    {
        return $this->nom_fichier;
    }

    public function setNomFichier(string $nom_fichier): static
    {
        $this->nom_fichier = $nom_fichier;

        return $this;
    }

    /**
     * @return Collection<int, FichierDemande>
     */
    public function getFichierDemandes(): Collection
    {
        return $this->fichierDemandes;
    }

    public function addFichierDemande(FichierDemande $fichierDemande): static
    {
        if (!$this->fichierDemandes->contains($fichierDemande)) {
            $this->fichierDemandes->add($fichierDemande);
            $fichierDemande->setIdFichier($this);
        }

        return $this;
    }

    public function removeFichierDemande(FichierDemande $fichierDemande): static
    {
        if ($this->fichierDemandes->removeElement($fichierDemande)) {
            // set the owning side to null (unless already changed)
            if ($fichierDemande->getIdFichier() === $this) {
                $fichierDemande->setIdFichier(null);
            }
        }

        return $this;
    }
}
