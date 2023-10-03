<?php

namespace App\Entity;

use App\Repository\AnneeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnneeRepository::class)]
class Annee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $annee_bilan = null;

    #[ORM\OneToMany(mappedBy: 'id_annee', targetEntity: FichierBilan::class)]
    private Collection $fichierBilans;

    public function __construct()
    {
        $this->fichierBilans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnneeBilan(): ?int
    {
        return $this->annee_bilan;
    }

    public function setAnneeBilan(int $annee_bilan): static
    {
        $this->annee_bilan = $annee_bilan;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getAnneeBilan() ?? '';
    }
//
//    /**
//     * @return Collection<int, FichierBilan>
//     */
//    public function getFichierBilans(): Collection
//    {
//        return $this->fichierBilans;
//    }
//
//    public function addFichierBilan(FichierBilan $fichierBilan): static
//    {
//        if (!$this->fichierBilans->contains($fichierBilan)) {
//            $this->fichierBilans->add($fichierBilan);
//            $fichierBilan->setIdAnnee($this);
//        }
//
//        return $this;
//    }
//
//    public function removeFichierBilan(FichierBilan $fichierBilan): static
//    {
//        if ($this->fichierBilans->removeElement($fichierBilan)) {
//            // set the owning side to null (unless already changed)
//            if ($fichierBilan->getIdAnnee() === $this) {
//                $fichierBilan->setIdAnnee(null);
//            }
//        }
//
//        return $this;

}
