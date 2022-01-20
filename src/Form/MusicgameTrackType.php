<?php

namespace App\Form;

use App\Entity\MusicgameTrack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusicgameTrackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artist')
            ->add('title')
            //->add('isOnline')
            //->add('fullname')
            //->add('musicgame')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MusicgameTrack::class,
        ]);
    }
}
