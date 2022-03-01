<?php

namespace matintayebi\phpmvc\form;

use matintayebi\phpmvc\Model;
use JetBrains\PhpStorm\Pure;

class Form
{
    public static function begin($action, $method, $name): Form
    {
        echo sprintf("<form action='%s' method='%s' name='%s'>", $action, $method, $name);
        return new self();
    }

    public static function end(): string
    {
        return "</form>";
    }

    #[Pure] public function field(Model $model, $attribute, $type): InputField
    {
        return new InputField($model, $attribute, $type);
    }

    #[Pure] public function textareaField(Model $model, $attribute): TextareaField
    {
        return new TextareaField($model, $attribute);
    }

}