<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/29/18
 * Time: 12:15 PM
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrackTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', TextType::class)//DateType::class, ['input' => 'string'])
            ->add('hours', IntegerType::class)
            ->add('comment', TextType::class)
            ->add('Activity', ChoiceType::class, ['choices' => [
                '8' => 'Design',
                '9' => 'Development',
                '10' => 'Management',
                '11' => 'Testing']])
            ->add('overtime', CheckboxType::class, ['required' => false])
            ->add('create', SubmitType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\TrackTime']);
    }

}