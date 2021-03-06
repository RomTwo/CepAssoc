<?php

namespace App\Form;

use App\Entity\Adherent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdherentAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isGAFJudge', null, [
                'attr' => [
                    'value' => false,
                ],
                'required' => false,
                'label' => "Juge GAF",
            ])
            ->add('GAFJudgeLevel', NumberType::class, [
                'attr' => [
                    'value' => 0,
                    'placeholder' => "Niveau",
                ],
                'required' => false,
            ])
            ->add('isGAMJudge', null, [
                'attr' => [
                    'value' => false,
                ],
                'required' => false,
                'label' => "Juge GAM",
            ])
            ->add('GAMJudgeLevel', NumberType::class, [
                'attr' => [
                    'value' => 0,
                    'placeholder' => "Niveau",
                ],
                'required' => false,
            ])
            ->add('isTeamGYMJudge', null, [
                'attr' => [
                    'value' => false,
                ],
                'required' => false,
                'label' => "Juge TeamGYM",
            ])
            ->add('teamGYMJudgeLevel', NumberType::class, [
                'attr' => [
                    'value' => 0,
                    'placeholder' => "Niveau",
                ],
                'required' => false,
            ])
            ->add('wantsAJudgeTraining', ChoiceType::class, [
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
            ])
            ->add('volunteerForTrainingHelp', ChoiceType::class, [
                'choices' => [
                    'Jamais' => "jamais",
                    'Occasionnellement' => "occasionnellement",
                    'Régulièrement' => "regulierement",
                ],
                'multiple' => false,
                'expanded' => true,
                'data' => 'jamais',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
            ])
            ->add('volunteerForClubLife', ChoiceType::class, [
                'choices' => [
                    'Jamais' => "jamais",
                    'Occasionnellement' => "occasionnellement",
                    'Régulièrement' => "regulierement",
                ],
                'multiple' => false,
                'expanded' => true,
                'data' => 'jamais',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
            ])
            ->add('professionRep1', TextType::class, [
                'attr' => [
                    'placeholder' => "Profession"
                ],
            ])
            ->add('phoneRep1', TextType::class, [
                'attr' => [
                    'placeholder' => "Numéro de téléphone"
                ],
            ])
            ->add('nationality', ChoiceType::class, [
                'choices' => [
                    'AFGHANISTAN' => 'AFGHANISTAN',
                    'AFRIQUE DU SUD' => 'AFRIQUE DU SUD',
                    'ALBANIE' => 'ALBANIE',
                    'ALGERIE' => 'ALGERIE',
                    'ALLEMAGNE' => 'ALLEMAGNE',
                    'ANDORRE' => 'ANDORRE',
                    'ANGOLA' => 'ANGOLA',
                    'ANGUILLA' => 'ANGUILLA',
                    'ANTILLES NEERLANDAISES' => 'ANTILLES NEERLANDAISES',
                    'ARABIE SAOUDITE' => 'ARABIE SAOUDITE',
                    'ARGENTINE' => 'ARGENTINE',
                    'ARMENIE' => 'ARMENIE',
                    'ARUBA' => 'ARUBA',
                    'AUSTRALIE' => 'AUSTRALIE',
                    'AUTRICHE' => 'AUTRICHE',
                    'AZERBAIDJAN' => 'AZERBAIDJAN',
                    'BAHAMAS' => 'BAHAMAS',
                    'BAHREIN' => 'BAHREIN',
                    'BANGLADESH' => 'BANGLADESH',
                    'BARBADE' => 'BARBADE',
                    'BELARUS' => 'BELARUS',
                    'BELGIQUE' => 'BELGIQUE',
                    'BELIZE' => 'BELIZE',
                    'BENIN' => 'BENIN',
                    'BERMUDES' => 'BERMUDES',
                    'BHOUTAN' => 'BHOUTAN',
                    'BIRMANIE(Myanmar)' => 'BIRMANIE(Myanmar)',
                    'BOLIVIE' => 'BOLIVIE',
                    'BOSNIE - HERZEGOVINE' => 'BOSNIE - HERZEGOVINE',
                    'BOTSWANA' => 'BOTSWANA',
                    'BRESIL' => 'BRESIL',
                    'BRUNEI DARUSSALAM' => 'BRUNEI DARUSSALAM',
                    'BULGARIE' => 'BULGARIE',
                    'BURKINA FASO' => 'BURKINA FASO',
                    'BURUNDI' => 'BURUNDI',
                    'CAMBODGE' => 'CAMBODGE',
                    'CAMEROUN' => 'CAMEROUN',
                    'CANADA' => 'CANADA',
                    'CAP VERT' => 'CAP VERT',
                    'CHILI' => 'CHILI',
                    'CHINE' => 'CHINE',
                    'CHYPRE' => 'CHYPRE',
                    'COLOMBIE' => 'COLOMBIE',
                    'COMORES' => 'COMORES',
                    'CONGO' => 'CONGO',
                    'COREE(République populaire)' => 'COREE(République populaire)',
                    'COREE(République)' => 'COREE(République)',
                    'COSTA RICA' => 'COSTA RICA',
                    'COTE D\'IVOIRE' => 'COTE D\'IVOIRE',
                    'CROATIE' => 'CROATIE',
                    'CUBA' => 'CUBA',
                    'DANEMARK' => 'DANEMARK',
                    'DJIBOUTI' => 'DJIBOUTI',
                    'DOMINIQUE' => 'DOMINIQUE',
                    'EGYPTE' => 'EGYPTE',
                    'EL SALVADOR' => 'EL SALVADOR',
                    'EMIRATS ARABES UNIS' => 'EMIRATS ARABES UNIS',
                    'EQUATEUR' => 'EQUATEUR',
                    'ERYTHREE' => 'ERYTHREE',
                    'ESPAGNE' => 'ESPAGNE',
                    'ESTONIE' => 'ESTONIE',
                    'ETATS UNIS' => 'ETATS UNIS',
                    'ETHIOPIE' => 'ETHIOPIE',
                    'FIDJI' => 'FIDJI',
                    'FINLANDE' => 'FINLANDE',
                    'FRANCE' => 'FRANCE',
                    'GABON' => 'GABON',
                    'GAMBIE' => 'GAMBIE',
                    'GEORGIE' => 'GEORGIE',
                    'GHANA' => 'GHANA',
                    'GIBRALTAR' => 'GIBRALTAR',
                    'GRANDE BRETAGNE' => 'GRANDE BRETAGNE',
                    'GRECE' => 'GRECE',
                    'GRENADE' => 'GRENADE',
                    'GROENLAND' => 'GROENLAND',
                    'GUAM' => 'GUAM',
                    'GUATEMALA' => 'GUATEMALA',
                    'GUINEE' => 'GUINEE',
                    'GUINEE BISSAU' => 'GUINEE BISSAU',
                    'GUINEE EQUATORIAL' => 'GUINEE EQUATORIAL',
                    'GUYANA' => 'GUYANA',
                    'HAITI' => 'HAITI',
                    'HONDURAS' => 'HONDURAS',
                    'HONGRIE' => 'HONGRIE',
                    'HONK KONG' => 'HONK KONG',
                    'ILE MAURICE' => 'ILE MAURICE',
                    'ILE NORFOLK' => 'ILE NORFOLK',
                    'ILES CAIMANES' => 'ILES CAIMANES',
                    'ILES COOK' => 'ILES COOK',
                    'ILES FALKLAND' => 'ILES FALKLAND',
                    'ILES FEROE' => 'ILES FEROE',
                    'ILES MARIANNES SEPTales' => 'ILES MARIANNES SEPTales',
                    'ILES MARSHALL' => 'ILES MARSHALL',
                    'ILES SALOMON' => 'ILES SALOMON',
                    'ILES TURQUES ET CAIQUES' => 'ILES TURQUES ET CAIQUES',
                    'ILES VIERGES AMERICAINES' => 'ILES VIERGES AMERICAINES',
                    'ILES VIERGES BRITANIQUES' => 'ILES VIERGES BRITANIQUES',
                    'INDE' => 'INDE',
                    'INDONESIE' => 'INDONESIE',
                    'IRAK' => 'IRAK',
                    'IRAN' => 'IRAN',
                    'IRLANDE' => 'IRLANDE',
                    'ISLANDE' => 'ISLANDE',
                    'ISRAEL' => 'ISRAEL',
                    'ITALIE' => 'ITALIE',
                    'JAMAIQUE' => 'JAMAIQUE',
                    'JAPON' => 'JAPON',
                    'JORDANIE' => 'JORDANIE',
                    'KAZAKHSTAN' => 'KAZAKHSTAN',
                    'KENYA' => 'KENYA',
                    'KIRGHIZISTAN' => 'KIRGHIZISTAN',
                    'KIRIBATI' => 'KIRIBATI',
                    'KOSOVO' => 'KOSOVO',
                    'KOWEIT' => 'KOWEIT',
                    'LAOS' => 'LAOS',
                    'LESOTHO' => 'LESOTHO',
                    'LETTONIE' => 'LETTONIE',
                    'LIBAN' => 'LIBAN',
                    'LIBERIA' => 'LIBERIA',
                    'LIBYE' => 'LIBYE',
                    'LIECHTENSTEIN' => 'LIECHTENSTEIN',
                    'LITUANIE' => 'LITUANIE',
                    'LUXEMBOURG' => 'LUXEMBOURG',
                    'MACAO' => 'MACAO',
                    'MACEDOINE' => 'MACEDOINE',
                    'MADAGASCAR' => 'MADAGASCAR',
                    'MALAISIE' => 'MALAISIE',
                    'MALAWI' => 'MALAWI',
                    'MALDIVES' => 'MALDIVES',
                    'MALI' => 'MALI',
                    'MALTE' => 'MALTE',
                    'MAROC' => 'MAROC',
                    'MAURITANIE' => 'MAURITANIE',
                    'MEXIQUE' => 'MEXIQUE',
                    'MICRONESIE' => 'MICRONESIE',
                    'MOLDAVIE' => 'MOLDAVIE',
                    'MONACO' => 'MONACO',
                    'MONGOLIE' => 'MONGOLIE',
                    'MONTSERRAT' => 'MONTSERRAT',
                    'MOZAMBIQUE' => 'MOZAMBIQUE',
                    'NAMIBIE' => 'NAMIBIE',
                    'NAURU' => 'NAURU',
                    'NEPAL' => 'NEPAL',
                    'NICARAGUA' => 'NICARAGUA',
                    'NIGER' => 'NIGER',
                    'NIGERIA' => 'NIGERIA',
                    'NIOUE' => 'NIOUE',
                    'NORVEGE' => 'NORVEGE',
                    'NOUVELLE-ZELANDE' => 'NOUVELLE-ZELANDE',
                    'OMAN' => 'OMAN',
                    'OUGANDA' => 'OUGANDA',
                    'OUZBEKISTAN' => 'OUZBEKISTAN',
                    'PAKISTAN' => 'PAKISTAN',
                    'PALAOS' => 'PALAOS',
                    'PALESTINE' => 'PALESTINE',
                    'PANAMA' => 'PANAMA',
                    'PAPOUASIE NOUVELLE GUINEE' => 'PAPOUASIE NOUVELLE GUINEE',
                    'PARAGUAY' => 'PARAGUAY',
                    'PAYS BAS' => 'PAYS BAS',
                    'PEROU' => 'PEROU',
                    'PHILIPPINES' => 'PHILIPPINES',
                    'PITCAIRN' => 'PITCAIRN',
                    'POLOGNE' => 'POLOGNE',
                    'PORTO RICO' => 'PORTO RICO',
                    'PORTUGAL' => 'PORTUGAL',
                    'QATAR' => 'QATAR',
                    'REP. CENTRAFRICAINE' => 'REP. CENTRAFRICAINE',
                    'REP. DE MOLDOVA' => 'REP. DE MOLDOVA',
                    'REP. DEMOCR. DU CONGO' => 'REP. DEMOCR. DU CONGO',
                    'REP. DOMINICAINE' => 'REP. DOMINICAINE',
                    'REP. TCHEQUE' => 'REP. TCHEQUE',
                    'REP. UNIE DE TANZANIE' => 'REP. UNIE DE TANZANIE',
                    'ROUMANIE' => 'ROUMANIE',
                    'RUSSIE' => 'RUSSIE',
                    'RWANDA' => 'RWANDA',
                    'SAHARA OCCIDENTAL' => 'SAHARA OCCIDENTAL',
                    'SAINT SIEGE' => 'SAINT SIEGE',
                    'SAINTE HELENE' => 'SAINTE HELENE',
                    'SAINTE LUCIE' => 'SAINTE LUCIE',
                    'SAMAOS AMERICAINES' => 'SAMAOS AMERICAINES',
                    'SAMOA' => 'SAMOA',
                    'SAN MARINO' => 'SAN MARINO',
                    'SAO TOME ET PRINCIPE' => 'SAO TOME ET PRINCIPE',
                    'SENEGAL' => 'SENEGAL',
                    'SERBIE' => 'SERBIE',
                    'SEYCHELLES' => 'SEYCHELLES',
                    'SIERRA LEONE' => 'SIERRA LEONE',
                    'SINGAPORE' => 'SINGAPORE',
                    'SLOVAQUIE' => 'SLOVAQUIE',
                    'SLOVENIE' => 'SLOVENIE',
                    'SOMALIE' => 'SOMALIE',
                    'SOUDAN' => 'SOUDAN',
                    'SRI LANKA' => 'SRI LANKA',
                    'ST KITTS ET NEVIS' => 'ST KITTS ET NEVIS',
                    'ST VINCENT ET LES GRENAD.' => 'ST VINCENT ET LES GRENAD.',
                    'SUEDE' => 'SUEDE',
                    'SUISSE' => 'SUISSE',
                    'SURINAME' => 'SURINAME',
                    'SVALBARD ET ILE JEAN MAYEN' => 'SVALBARD ET ILE JEAN MAYEN',
                    'SWAZILAND' => 'SWAZILAND',
                    'SYRIE' => 'SYRIE',
                    'TADJIKISTAN' => 'TADJIKISTAN',
                    'TAIWAN' => 'TAIWAN',
                    'TCHAD' => 'TCHAD',
                    'TCHECOSLOVAQUIE' => 'TCHECOSLOVAQUIE',
                    'TCHETCHENIE' => 'TCHETCHENIE',
                    'THAILANDE' => 'THAILANDE',
                    'TIMOR ORIENTAL' => 'TIMOR ORIENTAL',
                    'TOGO' => 'TOGO',
                    'TOKELAOU' => 'TOKELAOU',
                    'TONGA' => 'TONGA',
                    'TRINITE ET TABAGO' => 'TRINITE ET TABAGO',
                    'TUNISIE' => 'TUNISIE',
                    'TURKMENISTAN' => 'TURKMENISTAN',
                    'TURQUIE' => 'TURQUIE',
                    'TUVALU' => 'TUVALU',
                    'UKRAINE' => 'UKRAINE',
                    'URUGAY' => 'URUGAY',
                    'VANUATA' => 'VANUATA',
                    'VENEZUELA' => 'VENEZUELA',
                    'VIETNAM' => 'VIETNAM',
                    'YEMEN' => 'YEMEN',
                    'YOUGOSLAVIE' => 'YOUGOSLAVIE',
                    'ZAMBIE' => 'ZAMBIE',
                    'ZIMBABWE' => 'ZIMBABWE',
                ],
                'data' => 'FRANCE'
            ])
            ->add('paymentType', ChoiceType::class, [
                'choices' => [
                    'Espèces' => "especes",
                    'Chèque' => "cheque",
                ],
                'multiple' => false,
                'expanded' => true,
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
            ])
            ->add('imageRight', ChoiceType::class, [
                'choices' => [
                    'J\'autorise' => true,
                    'Je n\'autorise pas' => false,
                ],
                'multiple' => false,
                'expanded' => true,
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
            ])
            ->add('volunteerComment', TextareaType::class, [
                'attr' => [
                    'placeholder' => "Plus d'informations sur vos disponibilités..."
                ]
            ])
            ->add('medicalCertificateFile', FileType::class, [
                'attr' => [
                    'placeholder' => "Choisissez votre certificat médical"
                ],
                'required' => false,
                'data_class' => null,
            ])
            ->add('bulletinN2AllianzFile', FileType::class, [
                'attr' => [
                    'placeholder' => "Choisissez votre bulletin N2 Allianz "
                ],
                'required' => false,
                'data_class' => null,
            ])
            ->add('healthQuestionnaireFile', FileType::class, [
                'attr' => [
                    'placeholder' => "Choisissez le questionnaire de santé",
                    'lang' => 'fr',
                ],
                'required' => false,
                'data_class' => null,

            ])
            ->add('healthQuestionnaire', HealthQuestionnaireType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adherent::class,
            'cascade_validation' => true,
        ]);
    }
}
