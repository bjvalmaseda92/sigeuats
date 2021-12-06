<?php

namespace SgvsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Enfermedad
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Enfermedad
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
     * @ORM\Column(name="nombre", type="string", length=255,)
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     */
    private $nombre;

    /**
     *
     * @ORM\ManyToOne(targetEntity="SgvsBundle\Entity\TipoEnfermedad", inversedBy="enfermedades")
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     */
    private $tipoEnfermedad;

    /**
     * @ORM\OneToMany(targetEntity="SgvsBundle\Entity\Caso", mappedBy="enfermedad", orphanRemoval=true)
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
     * @return Enfermedad
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
     * Set tipoEnfermedad
     *
     * @param \SgvsBundle\Entity\TipoEnfermedad $tipoEnfermedad
     * @return Enfermedad
     */
    public function setTipoEnfermedad(\SgvsBundle\Entity\TipoEnfermedad $tipoEnfermedad = null)
    {
        $this->tipoEnfermedad = $tipoEnfermedad;

        return $this;
    }

    /**
     * Get tipoEnfermedad
     *
     * @return \SgvsBundle\Entity\TipoEnfermedad 
     */
    public function getTipoEnfermedad()
    {
        return $this->tipoEnfermedad;
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
     * @return Enfermedad
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
