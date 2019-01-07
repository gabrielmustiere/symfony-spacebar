<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class UserSelectedTextType.
 */
class UserSelectedTextType extends AbstractType
{
    /**
     * @return string|null
     */
    public function getParent()
    {
        return TextType::class;
    }
}
