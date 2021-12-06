<?php

namespace SgvsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * GrupoEdad
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class GrupoEdad
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
     * @Assert\Regex(pattern="/^(1|2|4|4|5|6|7|8|9|0){1,2}\-(1|2|4|4|5|6|7|8|9|0){1,2}$/", message="El formato no es correcto")
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
     * Lado inverso
     * @ORM\OneToMany(targetEntity="SgvsBundle\Entity\Paciente", mappedBy="grupoEdad", orphanRemoval=true);
     */
    private $pacientes;


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
     * @return GrupoEdad
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
     * @return GrupoEdad
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
        $this->pacientes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pacientes
     *
     * @param \SgvsBundle\Entity\Paciente $pacientes
     * @return GrupoEdad
     */
    public function addPaciente(\SgvsBundle\Entity\Paciente $pacientes)
    {
        $this->pacientes[] = $pacientes;

        return $this;
    }

    /**
     * Remove pacientes
     *
     * @param \SgvsBundle\Entity\Paciente $pacientes
     */
    public function removePaciente(\SgvsBundle\Entity\Paciente $pacientes)
    {
        $this->pacientes->removeElement($pacientes);
    }

    /**
     * Get pacientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPacientes()
    {
        return $this->pacientes;
    }
}
