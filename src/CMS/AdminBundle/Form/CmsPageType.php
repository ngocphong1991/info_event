<?php

namespace CMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use CMS\AdminBundle\Entity\CmsPage;

class CmsPageType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('sortDesciption', null, array(
                'attr' => array(
                    'class' => 'span8',
                    'row' => 3,
                    'style' => 'min-height: 100px;'
                    )
                )
            )
            ->add('description', null, array(
                    'attr' => array(
                        'class' => 'span12',
                        'row' => 5,
                        'style' => 'min-height: 200px;'
                    )
                )
            )
            ->add('url')
            ->add('keywords')
            ->add('isActive','choice', array(
                    'choices'   => CmsPage::getIsActiveTypes(),
                    'multiple'  => false,
                    'expanded'  => true
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
            'data_class' => 'CMS\AdminBundle\Entity\CmsPage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_adminbundle_cmspage';
    }
}
