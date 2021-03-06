<?php

namespace CMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use CMS\AdminBundle\Entity\GroupArticle;
class GroupArticleType extends AbstractType
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
            ->add('isSpecial','checkbox', array(
                    'required'  => false,
                    'label_attr' => array(
                        'class' => 'control-label'
                    )
                )
            )
            ->add('isOnTop','checkbox', array(
                    'label' => 'Show on Menu Top',
                    'required'  => false,
                    'label_attr' => array(
                        'class' => 'control-label'
                    )
                )
            )
            ->add('isOnBot','checkbox', array(
                    'label' => 'Show on Menu Bottom',
                    'required'  => false,
                    'label_attr' => array(
                        'class' => 'control-label'
                    )
                )
            )
            ->add('name')
            ->add('file', null, array('label' => 'Avatar Group',
                    'attr' => array(
                        'class' => 'default',
                    ),
                    'label_attr' => array(
                        'class' => 'control-label hidden-phone'
                    )
                )
            )
            ->add('fileActive', null, array('label' => 'Avatar Group Active',
                    'attr' => array(
                        'class' => 'default',
                    ),
                    'label_attr' => array(
                        'class' => 'control-label hidden-phone'
                    )
                )
            )
            ->add('position', null,  array(
                    'label_attr' => array(
                        'class' => 'control-label'
                    ),
                )
            )
            ->add('url', null, array(
                'label_attr' => array(
                    'class' => 'control-label'
                ),
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\AdminBundle\Entity\GroupArticle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'article';
    }
}
