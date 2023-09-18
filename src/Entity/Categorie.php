<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Article::class)]
    private Collection $lesarticles;

    public function __construct()
    {
        $this->lesarticles = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->libelle;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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
            $lesarticle->setCategorie($this);
        }

        return $this;
    }

    public function removeLesarticle(Article $lesarticle): static
    {
        if ($this->lesarticles->removeElement($lesarticle)) {
            // set the owning side to null (unless already changed)
            if ($lesarticle->getCategorie() === $this) {
                $lesarticle->setCategorie(null);
            }
        }

        return $this;
    }
}
