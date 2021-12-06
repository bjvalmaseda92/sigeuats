<?php
/**
 * Created by PhpStorm.
 * User: Bárbaro
 * Date: 19/05/2016
 * Time: 15:18
 */

namespace SgvsBundle\Validator\Constraints;



use Symfony\Component\Validator\Constraint;

/**
 * Class exitPaciente
 * @package Validator\Constraints
 * @Annotations
 */
class exitEnfermedad extends Constraint
{
    public $message = 'La enfermedad no se encuentra en la base de datos del sistema. Por favor registrela antes de continuar';


    public function validatedBy(){
        return 'exit_enfermedad';
    }
}