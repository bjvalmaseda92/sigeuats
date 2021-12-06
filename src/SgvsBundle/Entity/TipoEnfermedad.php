<?php

namespace SgvsBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;


use Doctrine\ORM\Mapping as ORM;

/**
 * TipoEnfermedad
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TipoEnfermedad
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
     * @Assert\NotBlank(message="El campo no puede estar vacío")
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * Lado inverso
     * @ORM\OneToMany(targetEntity="SgvsBundle\Entity\Enfermedad", mappedBy="tipoEnfermedad" , orphanRemoval=true);
     */
    private $enfermedades;


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
     * @return TipoEnfermedad
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

    public function __toString(){
        return $this->nombre;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->enfermedades = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add enfermedades
     *
     * @param \SgvsBundle\Entity\Enfermedad $enfermedades
     * @return TipoEnfermedad
     */
    public function addEnfermedade(\SgvsBundle\Entity\Enfermedad $enfermedades)
    {
        $this->enfermedades[] = $enfermedades;

        return $this;
    }

    /**
     * Remove enfermedades
     *
     * @param \SgvsBundle\Entity\Enfermedad $enfermedades
     */
    public function removeEnfermedade(\SgvsBundle\Entity\Enfermedad $enfermedades)
    {
        $this->enfermedades->removeElement($enfermedades);
    }

    /**
     * Get enfermedades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEnfermedades()
    {
        return $this->enfermedades;
    }
}
