<?php

namespace CMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use CMS\AdminBundle\Entity\Article;

class ArticleType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('sortDescription', null, array(
                    'attr' => array(
                        'class' => 'span12',
                        'row' => 5,
                        'style' => 'min-height: 150px;'
                    )
                )
            )
            ->add('description', null, array( 
                        'attr' => array(
                            'class' => 'span12',
                            'row' => 5,
                            'style' => 'min-height: 600px;'
                        )
                )
            )
            ->add('url')
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
            ->add('specialGroupArticle', null, array(
                    'empty_value' => 'Add Special Group for Article',
                    'empty_data'  => null,
                    'attr' => array(
                        'class' => 'span4 chzn-select',
                        'data-placeholder'=> 'Add Special Group for Article',
                        'tabindex' => '1',
                    ),
                    'label_attr' => array(
                        'class' => 'control-label'
                    ),
                )
            )
            ->add('tags', null, array(
                    'empty_value' => 'Add tags for Article',
                    'empty_data'  => null,
                    'attr' => array(
                        'class' => 'span4 chzn-select',
                        'data-placeholder'=> 'Add tags for Article',
                        'tabindex' => '1',
                    ),
                    'label_attr' => array(
                        'class' => 'control-label'
                    ),
                )
            )
            ->add('file', null, array('label' => 'Avatar',
                        'attr' => array(
                            'class' => 'default',
                        ),
                        'label_attr' => array(
                            'class' => 'control-label hidden-phone'
                        )
                    )
            )
            ->add('isActive','choice', array(
                        'choices'   => Article::getIsActiveTypes(),
                        'multiple'  => false,
                        'expanded'  => true
                    )
            )
            ->add('isSpecial','choice', array(
                        'choices'   => Article::getIsSpecialTypes(),
                        'multiple'  => false,
                        'expanded'  => true
                    )
            )
            ->add('dateStart','datePicker', array())
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CMS\AdminBundle\Entity\Article'
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
