<?php

namespace App\Form;

use App\Entity\BoardColumn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardColumnType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nazwa',
                    'class' => 'w-full px-2 py-1.5 border border-slate-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Dodaj kolumnę',
                'attr' => [
                    'class' => 'w-full bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold py-1.5 px-3 rounded transition cursor-pointer',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BoardColumn::class,
            'csrf_protection' => false,
        ]);
    }
}
