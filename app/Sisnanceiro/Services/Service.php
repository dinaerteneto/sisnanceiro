<?php

namespace Sisnanceiro\Services;

use Sisnanceiro\Exceptions\ValidationException;

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
        $this->validator->validate($input, false !== $rules ? $this->rules[$rules] : (isset($this->rules['store']) ? $this->rules['store'] : []));
        if ($this->validator->getErrors()) {
            return $this->validator;
        }
        return $this->repository->create($input);
    }

    /**
     * Upate a model record
     * @param array $data
     * @param strint $rulesId
     * @return array
     */
    public function update(array $data, $rules = 'update')
    {
        $this->validator->validate($data, false !== $rules ? $this->rules[$rules] : (isset($this->rules['update']) ? $this->rules['update'] : []));
        if ($this->validator->getErrors()) {
            return $this->validator;
        }
        return $this->repository->update($data);
    }

    /**
     * Delete a record of model
     * @param array $input
     * @param string $rulesId
     * @return boolean
     */
    public function destroy($id)
    {
        $model = $this->repository->find($id);
        return $model->delete();
    }

    /**
     * Find a model based id
     * @param int $id
     * @param mixed $with
     * @return Model
     */
    public function find($id, $with = false)
    {
        if ($with) {
            if (is_array($with)) {
                foreach ($with as $w) {
                    $this->repository->with($w);
                }
            } else {
                $this->repository->with($with);
            }
        }

        $item = $this->repository->find($id);
        $this->repository = $item;

        if ($item) {
            return $item;
        } else {
            $this->validator->addError('not_found', 'id', 'No record found for this id.');
            return false;
        }
    }

    /**
     * Find based columns
     * @param array $column
     * @param array $value
     * @return Collection
     */
    public function findBy($column, $value)
    {
        $item = $this->repository->findBy($column, $value);
        $this->repository = $item;

        if ($item) {
            return $item;
        } else {
            $this->validator->addError('not_found', $column, 'No record found for this ' . $column . '.');
            return false;
        }
    }

}
