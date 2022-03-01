<?php

namespace matintayebi\phpmvc;

use matintayebi\phpmvc\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}