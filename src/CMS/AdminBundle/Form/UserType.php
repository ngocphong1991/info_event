<?php

namespace CMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password', 'repeated', array(
                        'type' => 'password',
                        'invalid_message' => 'Password and Password confirm do not match',
                        'first_name' => 'Password',
                        'second_name' => 'PasswordConfirm',
                )
            )
            ->add('email', 'email')
            ->add('isActive')
            ->add('roles', null, array(
                        'empty_value' => 'Choose your role',
                        'empty_data'  => null,
                        'attr' => array(
                            'class' => 'span4 chzn-select',
                            'data-placeholder'=> 'Choose your roles',
                            'tabindex' => '1',
                        ),
                        'label_attr' => array(
                            'class' => 'control-label'
                        ),
                    )
                )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\AdminBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_adminbundle_user';
    }
}
