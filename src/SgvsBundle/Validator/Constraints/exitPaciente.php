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
class exitPaciente extends Constraint
{
    public $message = 'El paciente no extiste en el sistema';


    public function validatedBy(){
        return 'exit_paciente';
    }
}