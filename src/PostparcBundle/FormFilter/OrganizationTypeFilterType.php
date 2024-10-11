<?php

namespace PostparcBundle\FormFilter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OrganizationTypeFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'OrganizationType.field.name', 'required' => false])

            ->add('updatedBy', EntityType::class, [
                'label' => 'updatedBy',
                'class' => 'PostparcBundle:User',
                'required' => false,
                'choice_label' => function ($user) {
                    return $user->getDisplayName();
                },
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'PostparcBundle\Entity\OrganizationType',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'organizationType_filter';
    }
}
