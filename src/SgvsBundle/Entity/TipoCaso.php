<?php

namespace SgvsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * TipoCaso
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TipoCaso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\Regex(pattern="/^[A-Z|Á|É|Í|Ó|Ú]{1}[a-z|á|é|í|ó|ú|A-Z|Á|É|Í|Ó|Ú|\s]{1,50}$/", message="El nombre debe  contener solo carateres alfabéticos y comenzar con mayúscula")
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     */
    private $nombre;


    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="SgvsBundle\Entity\Caso", mappedBy="tipoCaso", orphanRemoval=true)
     */
    private $casos;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TipoCaso
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return TipoCaso
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }


    public function __toString(){
        return $this->nombre;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->casos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add casos
     *
     * @param \SgvsBundle\Entity\Caso $casos
     * @return TipoCaso
     */
    public function addCaso(\SgvsBundle\Entity\Caso $casos)
    {
        $this->casos[] = $casos;

        return $this;
    }

    /**
     * Remove casos
     *
     * @param \SgvsBundle\Entity\Caso $casos
     */
    public function removeCaso(\SgvsBundle\Entity\Caso $casos)
    {
        $this->casos->removeElement($casos);
    }

    /**
     * Get casos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCasos()
    {
        return $this->casos;
    }
}
