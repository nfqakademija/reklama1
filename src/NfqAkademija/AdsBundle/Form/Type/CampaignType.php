<?php
namespace NfqAkademija\AdsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CampaignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('name', 'text')
			->add('budget', 'number')
			->add('save', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NfqAkademija\AdsBundle\Entity\Campaign'
        ));
    }

    public function getName()
    {
        return 'campaign';
    }
}

?>
