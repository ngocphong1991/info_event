<?php

namespace CMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use CMS\AdminBundle\Entity\Article;

class ArticleType extends AbstractType
{
    protected $isLocked;
    protected $em;
    protected $idGroup;

    public function  __construct($isLocked, $em = null, $idGroup = 0){
        $this->isLocked = $isLocked;
        $this->em = $em;
        $this->idGroup = $idGroup;
    }

     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($this->isLocked){
            $builder->add('isLocked','checkbox', array(
                    'required'  => false,
                    'label_attr' => array(
                        'class' => 'control-label'
                    )
                )
            )->add('isActive','choice', array(
                        'choices'   => Article::getIsActiveTypes(),
                        'multiple'  => false,
                        'expanded'  => false,
                        'label_attr' => array(
                            'class' => 'control-label'
                        )
                    )
                )
            ;
        }
        $builder
            ->add('title', null, array(
                    'attr' => array(
                        'class' => 'span8',
                    )
                )
            )
            ->add('sortDescription', null, array(
                    'attr' => array(
                        'class' => 'span12',
                        'row' => 5,
                        'style' => 'min-height: 150px;'
                    )
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
            ->add('description', null, array( 
                        'attr' => array(
                            'class' => 'span12',
                            'row' => 5,
                            'style' => 'min-height: 600px;'
                        )
                )
            )
            ->add('tags',null, array(
                'attr' => array(
                    'class' => 'tags',
                ),
                'label_attr' => array(
                    'class' => 'control-label'
                )
            ))
            ->add('url', null, array(
                'label_attr' => array(
                    'class' => 'control-label'
                ),
            ));

        if($this->idGroup){
            $builder->add('groupArticle', null, array(
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
                    'required'  => true,
                    'data' => $this->em->getReference("CMSAdminBundle:GroupArticle", $this->idGroup)
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
                        'required'  => false,
                    )
                )
                ->add('dateStart','datePicker', array())
            ;
        }else{
            $builder->add('groupArticle', null, array(
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
                    'required'  => true,
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
                        'required'  => false,
                    )
                )
                ->add('dateStart','datePicker', array())
            ;
        }

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
