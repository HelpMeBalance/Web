<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbrArticle = null;

    #[ORM\Column]
    private ?float $montantTot = null;

    #[ORM\OneToOne(mappedBy: 'panier', cascade: ['persist', 'remove'])]
    private ?Commande $commande = null;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'panier')]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrArticle(): ?int
    {
        return $this->nbrArticle;
    }

    public function setNbrArticle(int $nbrArticle): static
    {
        $this->nbrArticle = $nbrArticle;

        return $this;
    }

    public function getMontantTot(): ?float
    {
        return $this->montantTot;
    }

    public function setMontantTot(float $montantTot): static
    {
        $this->montantTot = $montantTot;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): static
    {
        // set the owning side of the relation if necessary
        if ($commande->getPanier() !== $this) {
            $commande->setPanier($this);
        }

        $this->commande = $commande;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->addPanier($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            $article->removePanier($this);
        }

        return $this;
    }
}
