<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Telephone extends Constraint
{
    public $message = 'Numéro de téléphone invalide';
	
	
	public function validatedBy()
	{
		return \get_class($this).'Validator';
	}
	
	
}