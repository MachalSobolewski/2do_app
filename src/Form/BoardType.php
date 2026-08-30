<?php

namespace App\Form;

use App\Entity\Board;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa tablicy',
                'attr' => [
                    'placeholder' => 'np. Projekt Sklepu, Domowe...',
                    'class' => 'w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition',
                ],
                'label_attr' => [
                    'class' => 'block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Utwórz tablicę',
                'attr' => [
                    'class' => 'w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg text-sm shadow-sm transition',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Board::class,
            'csrf_protection' => false,
        ]);
    }
}
