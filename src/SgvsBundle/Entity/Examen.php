<?php

namespace SgvsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SgvsBundle\Validator\Constraints\exitPaciente as MyValidator;

/**
 * Examen
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Examen
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
     * @var float
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     * @ORM\Column(name="fluorecencia", type="float")
     * @Assert\Regex(pattern="/^\d{1,2}|\d{1,2},\d{1,2}|\d{1,2}.\d{1,2}$/", message="Valor de flourecencia es incorrecto")
     *
     */
    private $fluorecencia;

    /**
     * @var float
     *
     * @ORM\Column(name="fecha", type="date")
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     */
    private $fecha;


    /**
     * @var float
     *
     * @ORM\Column(name="resultado", type="string", length=255)
     *
     */
    private $resultado="Negativo";

    /**
     *
     *
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     * @Assert\Regex(pattern="/^([A-Z|Ñ]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s[A-Z]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s[A-Z|Ñ]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s\-\s\d{11})|([A-Z|Ñ]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s[A-Z|Ñ]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s[A-Z|Ñ]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s[A-Z|Ñ]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s\-\s\d{11})$/", message="Formato de entrada incorrecto por favor verifícalo")
     * @MyValidator
     */
    private $nombrepaciente;


    /**
     *
     * @ORM\ManyToOne(targetEntity="SgvsBundle\Entity\Paciente", inversedBy="examenes")
     */
    private $paciente;


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
     * Set fluorecencia
     *
     * @param float $fluorecencia
     * @return Examen
     */
    public function setFluorecencia($fluorecencia)
    {
        $this->fluorecencia = $fluorecencia;

        return $this;
    }

    /**
     * Get fluorecencia
     *
     * @return float
     */
    public function getFluorecencia()
    {
        return $this->fluorecencia;
    }

    /**
     * Set paciente
     *
     * @param \SgvsBundle\Entity\Paciente $paciente
     * @return Examen
     */
    public function setPaciente(\SgvsBundle\Entity\Paciente $paciente = null)
    {
        $this->paciente = $paciente;

        return $this;
    }

    /**
     * Get paciente
     *
     * @return \SgvsBundle\Entity\Paciente
     */
    public function getPaciente()
    {
        return $this->paciente;
    }

    /**
     * @return mixed
     */
    public function getNombrepaciente()
    {
        return $this->nombrepaciente;
    }

    /**
     * @param mixed $nombrepaciente
     */
    public function setNombrepaciente($nombrepaciente)
    {
        $this->nombrepaciente = $nombrepaciente;
    }





    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Examen
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }





    /**
     * Set resultado
     *
     * @param string $resultado
     * @return Examen
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;

        return $this;
    }

    /**
     * Get resultado
     *
     * @return string 
     */
    public function getResultado()
    {
        return $this->resultado;
    }
}
