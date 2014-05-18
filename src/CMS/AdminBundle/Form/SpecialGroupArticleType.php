<?php

namespace CMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use CMS\AdminBundle\Entity\SpecialGroupArticle;

class SpecialGroupArticleType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('position')
            ->add('isActive','choice', array(
                'choices'   => SpecialGroupArticle::getIsActiveTypes(),
                'multiple'  => false,
                'expanded'  => true
            )
        );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\AdminBundle\Entity\SpecialGroupArticle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_adminbundle_specialgrouparticle';
    }
}
