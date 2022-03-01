<?php

namespace matintayebi\phpmvc\form;

use matintayebi\phpmvc\Model;

abstract class BaseField
{
    abstract public function renderInput(): string;
    public Model $model;

    public string $attribute;

    public function __construct(Model $model, string $attribute)
    {
        $this->attribute = $attribute;
        $this->model = $model;
    }

    public function __toString(): string
    {
        return sprintf("   
             <div class='form-group mt-2'>
        <label class='mb-1' >%s: </label>
            %s
           <div class='invalid-feedback'>
              %s
           </div>
        </div>", $this->model->labels()[$this->attribute] ?? $this->attribute,
            $this->renderInput(),
            $this->model->getFirstError($this->attribute),
        );
    }
}