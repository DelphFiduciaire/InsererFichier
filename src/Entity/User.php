<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: FichierDemande::class)]
    private Collection $fichierDemandes;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: InfoClient::class)]
    private Collection $infoClients;

    #[ORM\OneToMany(mappedBy: 'id_user', targetEntity: FichierBilan::class)]
    private Collection $fichierBilans;

    public function __construct()
    {
        $this->fichierDemandes = new ArrayCollection();
        $this->infoClients = new ArrayCollection();
        $this->fichierBilans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $fichierDemande->setIdUser($this);
        }

        return $this;
    }

    public function removeFichierDemande(FichierDemande $fichierDemande): static
    {
        if ($this->fichierDemandes->removeElement($fichierDemande)) {
            // set the owning side to null (unless already changed)
            if ($fichierDemande->getIdUser() === $this) {
                $fichierDemande->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, InfoClient>
     */
    public function getInfoClients(): Collection
    {
        return $this->infoClients;
    }

    public function addInfoClient(InfoClient $infoClient): static
    {
        if (!$this->infoClients->contains($infoClient)) {
            $this->infoClients->add($infoClient);
            $infoClient->setIdUser($this);
        }

        return $this;
    }

    public function removeInfoClient(InfoClient $infoClient): static
    {
        if ($this->infoClients->removeElement($infoClient)) {
            // set the owning side to null (unless already changed)
            if ($infoClient->getIdUser() === $this) {
                $infoClient->setIdUser(null);
            }
        }

        return $this;
    }

    public function __toString() : string {

        return $this->getEmail();
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
            $fichierBilan->setIdUser($this);
        }

        return $this;
    }

    public function removeFichierBilan(FichierBilan $fichierBilan): static
    {
        if ($this->fichierBilans->removeElement($fichierBilan)) {
            // set the owning side to null (unless already changed)
            if ($fichierBilan->getIdUser() === $this) {
                $fichierBilan->setIdUser(null);
            }
        }

        return $this;
    }



}
