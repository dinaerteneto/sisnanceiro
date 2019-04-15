<?php
namespace Sisnanceiro\Helpers;

use \Validator as V;
use App\Exceptions\ValidationException;

class Validator
{
    /**
     * Validator instance
     *
     * @var Validator
     */
    protected $validator;

    /**
     * Erors instance
     *
     * @var Array
     */
    protected $errors;

    protected $errorCodes = [
        'email' => 'invalid',
        'min' => 'too_short',
        'max' => 'too_long',
        'unique' => 'not_unique',
        'uniquewith' => 'not_unique',
        'notin' => 'not_allowed',
        'exists' => 'not_found',
        'mimes' => 'invalid',
        'numeric' => 'invalid',
        'invalid' => 'invalid',
    ];

    /**
     * Validate the data with the give rules
     *
     * @param Array $data
     * @param Array $rules
     * @return boolean
     */
    public function validate($data, $rules)
    {
        $validation = V::make($data, $rules);

        if ($validation->fails()) {
            $messages = $validation->messages();

            foreach ($validation->failed() as $field => $failure) {
                foreach ($failure as $rule => $array) {
                    $this->addError($rule, $field, $messages->first($field));
                }
            }

            throw new ValidationException();
        }

        return true;
    }

    /**
     * Get the validator errors
     *
     * @return Array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Check if user exists
     *
     * @param integer $key
     * @return boolean
     */
    public function hasError($key)
    {
        $checkError = array_filter($this->errors, function ($error) use ($key) {
            return $error['field'] === $key;
        });

        return count($checkError) > 0;
    }

    /**
     * Add a new rule to validator
     *
     * @param string $rule
     * @param string $field
     * @param string $message
     * @return void
     */
    public function addError($rule, $field, $message)
    {
        $this->errors[] = ['field' => $field, 'error' => $this->mapErrorCode($rule), 'message' => $message];
    }

    /**
     * Add a new rule to validator
     *
     * @param string $message
     * @return void
     */
    public function addErrorMessage($message)
    {
        $this->errors[] = ['message' => $message];
    }

    /**
     * Map the error given for the ones from this function
     *
     * @param string $code
     * @return string
     */
    private function mapErrorCode($code)
    {
        $code = strtolower($code);

        if (isset($this->errorCodes[$code])) {
            return $this->errorCodes[$code];
        } else {
            return $code;
        }
    }
}
