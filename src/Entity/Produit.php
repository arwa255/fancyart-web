<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_produit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProduit;

    /**
     * @var int
     *
     * @ORM\Column(name="nom_produit", type="integer", nullable=false)
     */
    private $nomProduit;

    /**
     * @var int
     *
     * @ORM\Column(name="categorie", type="integer", nullable=false)
     */
    private $categorie;

    /**
     * @var int
     *
     * @ORM\Column(name="prix_produit", type="integer", nullable=false)
     */
    private $prixProduit;

    /**
     * @var int
     *
     * @ORM\Column(name="description_p", type="integer", nullable=false)
     */
    private $descriptionP;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function getNomProduit(): ?int
    {
        return $this->nomProduit;
    }

    public function setNomProduit(int $nomProduit): self
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    public function getCategorie(): ?int
    {
        return $this->categorie;
    }

    public function setCategorie(int $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPrixProduit(): ?int
    {
        return $this->prixProduit;
    }

    public function setPrixProduit(int $prixProduit): self
    {
        $this->prixProduit = $prixProduit;

        return $this;
    }

    public function getDescriptionP(): ?int
    {
        return $this->descriptionP;
    }

    public function setDescriptionP(int $descriptionP): self
    {
        $this->descriptionP = $descriptionP;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }


}
