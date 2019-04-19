<?php

namespace Sisnanceiro\Repositories;

abstract class Repository
{
    protected $model;
    protected $instance;

    public function __call($method, $attr)
    {
        return call_user_func_array([$this->model, $method], $attr);
    }

    public static function __callStatic($method, $attr)
    {
        self::getModel();
        return call_user_func_array([$this->$model, $method], $attr);
    }

    protected function setModel($model)
    {
        $this->$model = $model;
    }

    public function getModel()
    {
        if (!$this->model) {
            $service      = get_called_class();
            $model        = (new $service)->getModel();
            $this->$model = $model;
        }
        return $this->model;
    }

    /**
     * Return a model based column name
     * @param string $column
     * @param string $value
     * @return Illuminate\Database\Eloquent\Model
     */
    public function findBy($column, $value)
    {
        return $this->model->where($column, $value)->first();
    }

    /**
     * Return all records based column name
     * @param string $column
     * @param string $value
     * @return Collection
     */
    public function findAllBy($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

}
