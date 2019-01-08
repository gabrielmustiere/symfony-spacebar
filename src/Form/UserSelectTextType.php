<?php

namespace App\Form;

use App\DataTransformer\EmailToUserTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UserSelectTextType.
 */
class UserSelectTextType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new EmailToUserTransformer());
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return TextType::class;
    }
}
