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
            ->add('url')
            ->add('isActive','choice', array(
                        'choices'   => GroupArticle::getIsActiveTypes(),
                        'multiple'  => false,
                        'expanded'  => true
                    ))
            ->add('isSpecial','choice', array(
                'choices'   => GroupArticle::getIsActiveTypes(),
                'multiple'  => false,
                'expanded'  => true
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
