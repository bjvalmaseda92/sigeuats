<?php

namespace SgvsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SgvsBundle\Validator\Constraints\exitPaciente as PacienteValidator;
use SgvsBundle\Validator\Constraints\exitEnfermedad as EnfermedadValidator;

/**
 * Caso
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Caso
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
     * @var \DateTime
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=255)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="text", nullable=true)
     */
    private $info;

    /**

     *
     * @ORM\ManyToOne(targetEntity="SgvsBundle\Entity\Paciente", inversedBy="casos")
     */
    private $paciente;


    /**
     *@Assert\NotBlank(message = "Debe seleccionar un tipo de caso")
     * @ORM\ManyToOne(targetEntity="SgvsBundle\Entity\TipoCaso", inversedBy="casos")
     */
    private $tipoCaso;

    /**
     *
     * @ORM\ManyToOne(targetEntity="SgvsBundle\Entity\Enfermedad", inversedBy="casos")
     */
    private $enfermedad;

    /**
     *
     * @ORM\ManyToOne(targetEntity="SgvsBundle\Entity\LugarIngreso", inversedBy="casos")
     */
    private $lugaringreso;
    /**
     *
     * @ORM\ManyToOne(targetEntity="SgvsBundle\Entity\AreaDeSalud", inversedBy="casos")
     */
    private $areadesalud;





    /*
     * campos auxiliares del formulario
     */
    /**
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     * @PacienteValidator
     * @Assert\Regex(pattern="/^([A-Z|Ñ]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s[A-Z]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s[A-Z|Ñ]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s\-\s\d{11})|([A-Z|Ñ]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s[A-Z|Ñ]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s[A-Z|Ñ]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s[A-Z|Ñ]{1}[a-z|á|é|í|ó|ú|ñ]{1,12}\s\-\s\d{11})$/", message="Formato de entrada incorrecto por favor verifícalo")
     */
    private $nombrepaciente;

    /**
     * @Assert\NotBlank(message = "El campo no puede estar vacío")
     * @EnfermedadValidator
     */
    private $nombreenfermedad;

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
     * @return mixed
     */
    public function getNombreenfermedad()
    {
        return $this->nombreenfermedad;
    }

    /**
     * @param mixed $nombreenfermedad
     */
    public function setNombreenfermedad($nombreenfermedad)
    {
        $this->nombreenfermedad = $nombreenfermedad;
    }



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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Caso
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
     * Set paciente
     *
     * @param \SgvsBundle\Entity\Paciente $paciente
     * @return Caso
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
     * Set tipoCaso
     *
     * @param \SgvsBundle\Entity\TipoCaso $tipoCaso
     * @return Caso
     */
    public function setTipoCaso(\SgvsBundle\Entity\TipoCaso $tipoCaso = null)
    {
        $this->tipoCaso = $tipoCaso;

        return $this;
    }

    /**
     * Get tipoCaso
     *
     * @return \SgvsBundle\Entity\TipoCaso 
     */
    public function getTipoCaso()
    {
        return $this->tipoCaso;
    }

    /**
     * Set enfermedad
     *
     * @param \SgvsBundle\Entity\Enfermedad $enfermedad
     * @return Caso
     */
    public function setEnfermedad(\SgvsBundle\Entity\Enfermedad $enfermedad = null)
    {
        $this->enfermedad = $enfermedad;

        return $this;
    }

    /**
     * Get enfermedad
     *
     * @return \SgvsBundle\Entity\Enfermedad 
     */
    public function getEnfermedad()
    {
        return $this->enfermedad;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Caso
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set info
     *
     * @param string $info
     * @return Caso
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string 
     */
    public function getInfo()
    {
        return $this->info;
    }


    public function __toString(){
        return $this->getCodigo();
    }

    /**
     * Set lugaringreso
     *
     * @param \SgvsBundle\Entity\LugarIngreso $lugaringreso
     * @return Caso
     */
    public function setLugaringreso(\SgvsBundle\Entity\LugarIngreso $lugaringreso = null)
    {
        $this->lugaringreso = $lugaringreso;

        return $this;
    }

    /**
     * Get lugaringreso
     *
     * @return \SgvsBundle\Entity\LugarIngreso 
     */
    public function getLugaringreso()
    {
        return $this->lugaringreso;
    }

    /**
     * Set areadesalud
     *
     * @param \SgvsBundle\Entity\AreaDeSalud $areadesalud
     * @return Caso
     */
    public function setAreadesalud(\SgvsBundle\Entity\AreaDeSalud $areadesalud = null)
    {
        $this->areadesalud = $areadesalud;

        return $this;
    }

    /**
     * Get areadesalud
     *
     * @return \SgvsBundle\Entity\AreaDeSalud 
     */
    public function getAreadesalud()
    {
        return $this->areadesalud;
    }
}
