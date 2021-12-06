<?php

namespace SgvsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Paciente
 * @UniqueEntity("ci", message="El carnet de identidad ya esta registrado")
 * @ORM\Table()
 * @ORM\Entity
 */
class Paciente
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
     * @Assert\Regex(pattern="/^[A-Z|Á|É|Í|Ó|Ú|Ñ]{1}[a-z|á|é|í|ó|ú|A-Z|Á|É|Í|Ó|Ú|ñ|Ñ|\s]{1,50}$/", message="El nombre debe  contener solo carateres alfabéticos y comenzar con mayúscula")
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="Apellidos", type="string", length=255)
     * @Assert\Regex(pattern="/^[A-Z|Á|É|Í|Ó|Ú|Ñ]{1}[a-z|á|é|í|ó|ú|A-Z|Á|É|Í|Ó|Ú|ñ|Ñ|\s]{1,75}$/", message="Los apellidos deben  contener solo carateres alfabéticos y comenzar con mayúscula")
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="ci", type="string", length=11)
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     * @Assert\Regex(pattern="/^\d{11}$/", message="El número de carnet de identidad no es válido")
     */
    private $ci;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     */
    private $direccion;

    /**
     *
     * @ORM\ManyToOne(targetEntity="SgvsBundle\Entity\GrupoEdad", inversedBy="pacientes")
     * @Assert\NotBlank(message = "Debe seleccionar un grupo de edad")
     */
    private $grupoEdad;

    /**
     * Lado inverso
     * @ORM\OneToMany(targetEntity="SgvsBundle\Entity\Examen", mappedBy="paciente", orphanRemoval=true);
     */
    private $examenes;

    /**
     * Lado inverso
     * @ORM\OneToMany(targetEntity="SgvsBundle\Entity\Caso", mappedBy="paciente", orphanRemoval=true);
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
     * @return Paciente
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
     * Set apellidos
     *
     * @param string $apellidos
     * @return Paciente
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set ci
     *
     * @param string $ci
     * @return Paciente
     */
    public function setCi($ci)
    {
        $this->ci = $ci;

        return $this;
    }

    /**
     * Get ci
     *
     * @return string 
     */
    public function getCi()
    {
        return $this->ci;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Paciente
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set grupoEdad
     *
     * @param \SgvsBundle\Entity\GrupoEdad $grupoEdad
     * @return Paciente
     */
    public function setGrupoEdad(\SgvsBundle\Entity\GrupoEdad $grupoEdad = null)
    {
        $this->grupoEdad = $grupoEdad;

        return $this;
    }

    /**
     * Get grupoEdad
     *
     * @return \SgvsBundle\Entity\GrupoEdad 
     */
    public function getGrupoEdad()
    {
        return $this->grupoEdad;
    }

    public function __toString(){
        return $this->nombre.' '.$this->apellidos;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examenes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->casos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add examenes
     *
     * @param \SgvsBundle\Entity\Examen $examenes
     * @return Paciente
     */
    public function addExamene(\SgvsBundle\Entity\Examen $examenes)
    {
        $this->examenes[] = $examenes;

        return $this;
    }

    /**
     * Remove examenes
     *
     * @param \SgvsBundle\Entity\Examen $examenes
     */
    public function removeExamene(\SgvsBundle\Entity\Examen $examenes)
    {
        $this->examenes->removeElement($examenes);
    }

    /**
     * Get examenes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExamenes()
    {
        return $this->examenes;
    }

    /**
     * Add casos
     *
     * @param \SgvsBundle\Entity\Caso $casos
     * @return Paciente
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
