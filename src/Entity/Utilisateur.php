<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $mdp = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Article::class)]
    private Collection $lesarticles;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Avis::class, orphanRemoval: true)]
    private Collection $avis;

    public function __construct()
    {
        $this->lesarticles = new ArrayCollection();
        $this->avis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function __toString()
    {
        return $this->pseudo;
    }
    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getLesarticles(): Collection
    {
        return $this->lesarticles;
    }

    public function addLesarticle(Article $lesarticle): static
    {
        if (!$this->lesarticles->contains($lesarticle)) {
            $this->lesarticles->add($lesarticle);
            $lesarticle->setUtilisateur($this);
        }

        return $this;
    }

    public function removeLesarticle(Article $lesarticle): static
    {
        if ($this->lesarticles->removeElement($lesarticle)) {
            // set the owning side to null (unless already changed)
            if ($lesarticle->getUtilisateur() === $this) {
                $lesarticle->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setAuteur($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getAuteur() === $this) {
                $avi->setAuteur(null);
            }
        }

        return $this;
    }
}
