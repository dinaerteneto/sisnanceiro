<?php

namespace Sisnanceiro\Services;

abstract class Service
{

    public $validator;
    protected $repository;
    protected $rules;

    /**
     * return errors found in model
     * @return array
     */
    public function getErrors()
    {
        return $this->validator->getErrors();
    }

    /**
     * Save a model
     * @param array $input
     * @param array $rules
     * @return Model
     */
    public function store(array $input, $rules = false)
    {
        try {
            $this->validator->validate($input, false !== $rules ? $rules : (isset($this->rules['store']) ? $this->rules['store'] : []));
            return $this->repository->create($input);
        } catch (ValidationException $e) {
            return false;
        }
    }

    /**
     * Upate a model record
     * @param array $input
     * @param strint $rulesId
     * @return array
     */
    public function update(array $input, $rulesId = 'update')
    {
        try {
            $rules = isset($this->rules[$rulesId]) ? $this->rules[$rulesId] : [];

            foreach ($rules as $key => $ruleSet) {
                foreach ($ruleSet as $key2 => $rule) {
                    $rules[$key][$key2] = str_replace('{{id}}', $input['id'], $rule);
                }
            }

            $this->validator->validate($input, $rules);
            $item = $this->repository->update($input);

            if ($item) {
                return $item;
            } else {
                return false;
            }
        } catch (ValidationException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a record of model
     * @param array $input
     * @param string $rulesId
     * @return boolean
     */
    public function destroy(array $input, $rulesId = 'delete')
    {
        try {
            $this->validator->validate($input, isset($this->rules[$rulesId]) ? $this->rules[$rulesId] : []);
            return $this->repository->delete($input);
        } catch (ValidationException $e) {
            return false;
        }
    }

}
