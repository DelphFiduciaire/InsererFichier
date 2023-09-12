<?php

namespace App\Entity;

use App\Repository\FichierDemandeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: FichierDemandeRepository::class)]
#[Vich\Uploadable]
class FichierDemande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_fichier_demande = null;

    #[ORM\ManyToOne(inversedBy: 'fichierDemandes')]
    private ?User $id_user = null;

    #[ORM\ManyToOne(inversedBy: 'fichierDemandes')]
    private ?Fichier $id_fichier = null;

    #[ORM\ManyToOne(inversedBy: 'fichierDemandes')]
    private ?InfoClient $id_info_client = null;

    #[ORM\Column]
    private ?bool $verif = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFichierDemande(): ?string
    {
        return $this->nom_fichier_demande;
    }

    public function setNomFichierDemande(string $nom_fichier_demande): static
    {
        $this->nom_fichier_demande = $nom_fichier_demande;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdFichier(): ?Fichier
    {
        return $this->id_fichier;
    }

    public function setIdFichier(?Fichier $id_fichier): static
    {
        $this->id_fichier = $id_fichier;

        return $this;
    }

    public function getIdInfoClient(): ?InfoClient
    {
        return $this->id_info_client;
    }

    public function setIdInfoClient(?InfoClient $id_info_client): static
    {
        $this->id_info_client = $id_info_client;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNomFichierDemande() ?? '';
    }

    public function isVerif(): ?bool
    {
        return $this->verif;
    }

    public function setVerif(bool $verif): static
    {
        $this->verif = $verif;

        return $this;
    }
}
