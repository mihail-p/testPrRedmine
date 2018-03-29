<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/29/18
 * Time: 12:15 PM
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user_name', TextType::class, ['disabled' => true])
            ->add('date', DateType::class, ['widget' => 'single_text', 'disabled' => true])
            ->add('comment', TextareaType::class, ['attr' => ['cols' => '78', 'rows' => '5']])
            ->add('project', EntityType::class, [
                'class' => 'AppBundle\Entity\Project',
                'property' => 'project_name',
                'attr' => [
                    'class' => 'chosen form-control', 'data-placeholder' => '-- choice Project --']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Comment']);
    }

}