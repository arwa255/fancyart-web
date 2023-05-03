<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lignecommande
 *
 * @ORM\Table(name="lignecommande")
 * @ORM\Entity
 */
class Lignecommande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_ligne_commande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLigneCommande;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_commande", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $idCommande = NULL;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quantité", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $quantité = NULL;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prix_unitaire", type="float", precision=10, scale=0, nullable=true, options={"default"="NULL"})
     */
    private $prixUnitaire = NULL;

    public function getIdLigneCommande(): ?int
    {
        return $this->idLigneCommande;
    }

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function setIdCommande(?int $idCommande): self
    {
        $this->idCommande = $idCommande;

        return $this;
    }

    public function getQuantité(): ?int
    {
        return $this->quantité;
    }

    public function setQuantité(?int $quantité): self
    {
        $this->quantité = $quantité;

        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(?float $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }


}
