<?php

namespace Volan\Validator;
class MongoidValidator extends AbstractValidator
{
    public function isValid($nodeData)
    {
        return ($nodeData instanceof \MongoId);
    }
}
