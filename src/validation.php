<?php

namespace Rodasnet\Portfolio;

/**
 * Validation class.
 */
class Validation extends \Fuel\Core\Validation
{
	/**
	 * Returns a csv of validation errors.
	 *
	 * @return string
	 */
	public function errors()
	{
		$errors = array();
		foreach ($this->errors as $key => $error) {
			if (($message = $this->get_message($key)) && is_array($message)) {
				foreach ($message as $msg) {
					$errors[] = "$key.$msg";
				}
				
			} else {
				$errors[] = $key;
			}
		}
		
		return implode(', ', $errors);
	}
	
	/**
	 * Validates that the provided value is numeric.
	 *
	 * @param string $val Value to validate.
	 *
	 * @return bool
	 */
	public function _validation_number($val)
	{
		return $this->_empty($val) || $this->_validation_valid_string($val, array('numeric'));
	}
	
	/**
	 * Validates that the provided value is allowed.
	 *
	 * @param string $value  Value to validate.
	 * @param array  $values Acceptable values.
	 *
	 * @return bool
	 */
	public function _validation_valid_value($value, array $values)
	{
		if (!in_array($value, $values)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Validates contact data.
	 *
	 * @param array  $data   Contact data to validate.
	 * @param string $type   Contact type.
	 * @param string $action Type of contact validation (create or update).
	 *
	 * @return bool
	 */
	public function _validation_contact($data, $type = 'customer', $action = 'create')
	{
		// Allow an existing contact ID to be used for payment methods.
		if ($type == 'paymentmethod' && is_numeric($data)) {
			if (Service_Contact::find_one($data)) {
				return true;
			} else {
				return false;
			}
		}
		
		$data = !is_array($data) ? (array) $data : $data;
		
		if (!in_array($action, array('create', 'update'))) {
			return false;
		}
		
		$validator = Validation_Contact::$action($type);
		if (!$validator->run($data)) {
			$this->set_message('contact', array_keys($validator->errors));
			return false;
		}
		
		return true;
	}

	/**
	 * Performs the Luhn formula against a numeric value.
	 * 
	 * @see http://en.wikipedia.org/wiki/Luhn_algorithm
	 *
	 * @param string $val Number to validate.
	 * 
	 * @return bool
	 */
	protected function luhn_check($val)
	{
		// Force the value to be a string as this method uses string functions.
		// Converting to an integer may pass PHP_INT_MAX and result in an error!
		$val = (string) $val;
		
		// Luhn can only be used on numbers!
		if (!ctype_digit($val)) {
			return false;
		}
		
		$length = strlen($val);
		
		$checksum = 0;
		
		for ($i = $length - 1; $i >= 0; $i -= 2) {
			// Add up every 2nd digit, starting from the right.
			$checksum += substr($val, $i, 1);
		}
		
		for ($i = $length - 2; $i >= 0; $i -= 2) {
			// Add up every 2nd digit doubled, starting from the right.
			$double = substr($val, $i, 1) * 2;
			
			// Subtract 9 from the double where value is greater than 10.
			$checksum += ($double >= 10) ? ($double - 9) : $double;
		}
		
		// If the checksum is a multiple of 10, the number is valid.
		return ($checksum % 10 === 0);
	}
	
	

}
