<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * @Annotation
 */
class TelephoneValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint) {

		$phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();

		try {
		  $phoneNumberObject = $phoneNumberUtil->parse($value, 'FR');

		  if ($phoneNumberUtil->isValidNumber($phoneNumberObject) === false ){
			return $this->context
			->buildViolation($constraint->message)
			->addViolation()
			;
		  }
		}
		catch(\Exception $e){
			return $this->context
			->buildViolation($constraint->message)
			->addViolation()
			;
		}
	}
  }
	
	
}