<?php

namespace App\Entity;

use App\Repository\InfoClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfoClientRepository::class)]
class InfoClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $mail_pro = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_societe = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?int $num_pro = null;

    #[ORM\Column]
    private ?int $num = null;

    #[ORM\Column]
    private ?int $cp = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column]
    private ?int $siret = null;

    #[ORM\OneToMany(mappedBy: 'id_info_client', targetEntity: FichierDemande::class)]
    private Collection $fichierDemandes;

    #[ORM\ManyToOne(inversedBy: 'infoClients')]
    private ?User $id_user = null;

    public function __construct()
    {
        $this->fichierDemandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMailPro(): ?string
    {
        return $this->mail_pro;
    }

    public function setMailPro(string $mail_pro): static
    {
        $this->mail_pro = $mail_pro;

        return $this;
    }

    public function getNomSociete(): ?string
    {
        return $this->nom_societe;
    }

    public function setNomSociete(string $nom_societe): static
    {
        $this->nom_societe = $nom_societe;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumPro(): ?int
    {
        return $this->num_pro;
    }

    public function setNumPro(int $num_pro): static
    {
        $this->num_pro = $num_pro;

        return $this;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): static
    {
        $this->num = $num;

        return $this;
    }

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(int $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getSiret(): ?int
    {
        return $this->siret;
    }

    public function setSiret(int $siret): static
    {
        $this->siret = $siret;

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
            $fichierDemande->setIdInfoClient($this);
        }

        return $this;
    }

    public function removeFichierDemande(FichierDemande $fichierDemande): static
    {
        if ($this->fichierDemandes->removeElement($fichierDemande)) {
            // set the owning side to null (unless already changed)
            if ($fichierDemande->getIdInfoClient() === $this) {
                $fichierDemande->setIdInfoClient(null);
            }
        }

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

    public function __toString(): string
    {
        $nom = $this->getNom() ?? '';
        $prenom = $this->getPrenom() ?? '';
        return $nom . ' ' . $prenom;
    }

}
