<?php

namespace App\Entity;

use App\Repository\FichierBilanRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FichierBilanRepository::class)]
class FichierBilan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_fichier_bilan = null;

    #[ORM\ManyToOne(inversedBy: 'fichierBilans')]
    private ?User $id_user = null;

    #[ORM\ManyToOne(inversedBy: 'fichierBilans')]
    private ?InfoClient $id_info_client = null;

    #[ORM\Column]
    private ?bool $verif_bilan = null;

    #[ORM\ManyToOne(inversedBy: 'fichierBilans')]
    private ?FichierNomBilan $id_fichier_bilan = null;

    #[ORM\ManyToOne(inversedBy: 'fichierBilans')]
    private ?Annee $id_annee = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFichierBilan(): ?string
    {
        return $this->nom_fichier_bilan;
    }

    public function setNomFichierBilan(string $nom_fichier_bilan): static
    {
        $this->nom_fichier_bilan = $nom_fichier_bilan;

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

    public function getIdInfoClient(): ?InfoClient
    {
        return $this->id_info_client;
    }

    public function setIdInfoClient(?InfoClient $id_info_client): static
    {
        $this->id_info_client = $id_info_client;

        return $this;
    }

    public function isVerifBilan(): ?bool
    {
        return $this->verif_bilan;
    }

    public function setVerifBilan(bool $verif_bilan): static
    {
        $this->verif_bilan = $verif_bilan;

        return $this;
    }

    public function getIdFichierBilan(): ?FichierNomBilan
    {
        return $this->id_fichier_bilan;
    }

    public function setIdFichierBilan(?FichierNomBilan $id_fichier_bilan): static
    {
        $this->id_fichier_bilan = $id_fichier_bilan;

        return $this;
    }



    public function getIdAnnee(): ?Annee
    {
        return $this->id_annee;
    }

    public function setIdAnnee(?Annee $id_annee): static
    {
        $this->id_annee = $id_annee;


        return $this;
    }

    public function __toString(): string
    {
        return $this->getNomFichierBilan() ?? '';
    }


}
