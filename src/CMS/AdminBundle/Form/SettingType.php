<?php

namespace CMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('websiteName')
            ->add('websiteDescription', null, array(
                    'attr' => array(
                        'class' => 'span8',
                        'style' => 'height: 100px'
                    ),
                    'label_attr' => array(
                        'class' => 'control-label'
                    ),
                )
            )
            ->add('file', null, array('label' => 'Banner website',
                    'attr' => array(
                        'class' => 'default',
                    ),
                    'label_attr' => array(
                        'class' => 'control-label hidden-phone'
                    )
                )
            )
            ->add('fileBottom', null, array('label' => 'Banner footer website',
                    'attr' => array(
                        'class' => 'default',
                    ),
                    'label_attr' => array(
                        'class' => 'control-label hidden-phone'
                    )
                )
            )
            ->add('fileLogo', null, array('label' => 'Logo website',
                    'attr' => array(
                        'class' => 'default',
                    ),
                    'label_attr' => array(
                        'class' => 'control-label hidden-phone'
                    )
                )
            )
            ->add('websiteCopyright', null, array(
                    'attr' => array(
                        'class' => 'span6',
                    ),
                    'label_attr' => array(
                        'class' => 'control-label'
                    ),
                )
            )
            ->add('websiteAppApiFacebook', null, array(
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
            'data_class' => 'CMS\AdminBundle\Entity\Setting'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'setting';
    }
}
