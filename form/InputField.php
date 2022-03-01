<?php

namespace app\core\form;

use app\core\Model;
use JetBrains\PhpStorm\Pure;

class InputField extends BaseField
{
    public string $type;

    #[Pure] public function __construct(Model $model, string $attribute, string $type)
    {
        parent::__construct($model, $attribute);
        $this->type = $type;
    }

    public function renderInput(): string
    {
        return sprintf(
            "<input  name='%s' value='%s' type='%s' id='%s' class='form-control %s'>",
            $this->attribute,
            $this->model->{$this->attribute},
            $this->type,
            $this->attribute,
            $this->model->hasError($this->attribute) ? "is-invalid" : "",);
    }
}