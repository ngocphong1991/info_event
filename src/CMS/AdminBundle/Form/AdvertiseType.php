<?php

namespace CMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdvertiseType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isActive','checkbox', array(
                    'required'  => false,
                    'label_attr' => array(
                        'class' => 'control-label'
                    )
                )
            )
            ->add('position','checkbox', array(
                    'label' => 'Position of Advertise',
                    'required'  => false,
                    'label_attr' => array(
                        'class' => 'control-label'
                    )
                )
            )
            ->add('groupArticle', null, array(
                    'empty_value' => 'Choose your Article Group',
                    'empty_data'  => null,
                    'attr' => array(
                        'class' => 'span4 chzn-select',
                        'data-placeholder'=> 'Choose a Article Group',
                        'tabindex' => '1',
                    ),
                    'label_attr' => array(
                        'class' => 'control-label'
                    ),
                )
            )
            ->add('name', null, array(
                    'label_attr' => array(
                        'class' => 'control-label'
                    ),    
                )
            )
            ->add('keyWords',null, array(
                'attr' => array(
                    'class' => 'tags',
                ),
                'label_attr' => array(
                    'class' => 'control-label'
                )
            ))
            ->add('file', null, array('label' => 'Avatar',
                    'attr' => array(
                        'class' => 'default',
                    ),
                    'label_attr' => array(
                        'class' => 'control-label hidden-phone'
                    )
                )
            )
            ->add('cpc', null, array(
                    'label' => 'CPC - Cost/ 1 click (VNĐ)',
                    'label_attr' => array(
                        'class' => 'control-label',
                    ),
                )
            )
            ->add('cpm', null, array(
                    'label' => 'CPM - Cost/ 1000 impression (VNĐ)',
                    'label_attr' => array(
                        'class' => 'control-label'
                    ),
                )
            )
            ->add('budget', null, array(
                    'label' => 'Budget for Advertise',
                    'label_attr' => array(
                        'class' => 'control-label'
                    ),
                )
            )
            ->add('url')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\AdminBundle\Entity\Advertise'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_adminbundle_advertise';
    }
}
