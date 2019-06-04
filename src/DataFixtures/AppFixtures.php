<?php

namespace App\DataFixtures;

use App\Entity\TimeSlot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Account;
use App\Entity\Adherent;
use App\Entity\Category;
use App\Entity\Event;
use App\Entity\Activity;
use phpDocumentor\Reflection\Types\Integer;

class AppFixtures extends Fixture
{

    private function loadAccount(Account $a, ObjectManager $o, string $firstName, string $lastName, string $sex, \DateTime $birthDate, int $zipCode, string $address,
                                 string $email, string $city, string $password)
    {
        $a->setFirstName($firstName);
        $a->setLastName($lastName);
        $a->setSex($sex);
        $a->setBirthDate($birthDate);
        $a->setZipCode($zipCode);
        $a->setAddress($address);
        $a->setEmail($email);
        $a->setCity($city);
        $a->setPassword($password);

        $o->persist($a);
    }

    private function loadAdherent1(Adherent $a, ObjectManager $o, string $firstName, string $lastName, string $sex, \DateTime $birthDate)
    {
        $a->setFirstName($firstName);
        $a->setLastName($lastName);
        $a->setSex($sex);
        $a->setBirthDate($birthDate);
    }

    private function loadAdherent2(Adherent $a, ObjectManager $o, string $firstNameRep1, string $lastNameRep1, int $zipCodeRep1, string $addressRep1, string $emailRep1,
                                   string $cityRep1, string $professionRep1, string $phoneRep1)
    {
        $a->setFirstNameRep1($firstNameRep1);
        $a->setLastNameRep1($lastNameRep1);
        $a->setZipCodeRep1($zipCodeRep1);
        $a->setAddressRep1($addressRep1);
        $a->setEmailRep1($emailRep1);
        $a->setCityRep1($cityRep1);
        $a->setProfessionRep1($professionRep1);
        $a->setPhoneRep1($phoneRep1);
    }

    private function loadAdherent3(Adherent $a, ObjectManager $o, string $firstNameRep2, string $lastNameRep2, int $zipCodeRep2, string $addressRep2, string $emailRep2,
                                   string $cityRep2, string $professionRep2, string $phoneRep2)
    {
        $a->setFirstNameRep1($firstNameRep2);
        $a->setLastNameRep1($lastNameRep2);
        $a->setZipCodeRep1($zipCodeRep2);
        $a->setAddressRep1($addressRep2);
        $a->setEmailRep1($emailRep2);
        $a->setCityRep1($cityRep2);
        $a->setProfessionRep1($professionRep2);
        $a->setPhoneRep1($phoneRep2);
    }

    private function loadAdherent4(Adherent $a, ObjectManager $o, bool $isJudge, bool $wantsAJudgeTraining, bool $isGAFJudge, int $gafJudgeLevel,
                                   bool $isGAMJudge, int $gamJudgeLevel,
                                   bool $isTeamGYMJudge, int $teamGYMJudgeLevel)
    {
        $a->setWantsAJudgeTraining($wantsAJudgeTraining);
        $a->setJudge($isJudge);
        $a->setIsGAFJudge($isGAFJudge);
        $a->setGAFJudgeLevel($gafJudgeLevel);
        $a->setIsGAMJudge($isGAMJudge);
        $a->setGAMJudgeLevel($gamJudgeLevel);
        $a->setIsTeamGYMJudge($isTeamGYMJudge);
        $a->setTeamGYMJudgeLevel($teamGYMJudgeLevel);
    }

    private function loadAdherent5(Adherent $a, ObjectManager $o)
    {
        $a->setisRegisteredInGestGym(false);
        $a->setStatus("EN ATTENTE");
        $a->setMedicalCertificateDate(new \DateTime("01-09-2019"));
        $a->setNationality("France");
        $a->setIsFFGInsurance(false);
        $a->setIsAllowEmail(false);
        $a->setIsLicenceHolderOtherClub(false);
        $a->setMaidenName("");


        $a->setHasBulletinN2Allianz(false);
        $a->setHasCompetitionCommitment(false);
        $a->setIsMutated(false);
        $a->setIsDeleted(false);
    }

    private function loadCategory(Category $c, ObjectManager $o, string $name)
    {
        $c->setName($name);
        $o->persist($c);
    }

    private function loadActivity(Activity $a, ObjectManager $o, string $name, float $priece, \DateTime $startDate, string $type, Category $category)
    {
        $a->setName($name);
        $a->setPrice($priece);
        $a->setStartDate($startDate);
        $a->setType($type);
        $a->setCategory($category);
    }

    private function loadTimeSlot(ObjectManager $o, int $day, \DateTime $startTime, \DateTime $endTime, Activity $activity, string $city)
    {
        $t = new TimeSlot();
        $t->setDay($day);
        $t->setStartTime($startTime);
        $t->setEndTime($endTime);
        $t->setCity($city);
        $t->setActivity($activity);
        $o->persist($t);
    }

    public function load(ObjectManager $manager)
    {
        $accountsNumber = 10;
        $adherentsNumber = 10;
        $categoriesNumber = 10;
        $eventsNumber = 5;
        $activitiesNumber = 36;

        $accounts = array();
        $adherents = array();
        $categories = array();
        $events = array();
        $activities = array();

        /*******************
         * Loading accounts
         *******************/

        for ($i = 0; $i < $accountsNumber; $i++) {
            $account = new Account();
            array_push($accounts, $account);
        }


        $this->loadAccount($accounts[0], $manager, "Jean Luc", "Chenes", "M", new \DateTime("18-10-1975"), 86000, "22 Rue de la Fièvre", "jeanB@gmail.com", "Poitiers", "Didier@86");
        $this->loadAccount($accounts[1], $manager, "Norris", "Gaulin", "M", new \DateTime("17-03-1943"), 86440, "111 Boulevard du Lac", "norris-gaulin@outlook.com", "Migne-auxances", "Didier@86");
        $this->loadAccount($accounts[2], $manager, "Aimée Rouze", "Dupont", "F", new \DateTime("21-11-1983"), 86000, "1 Avenue de Gaulle", "aime.rouze@gmail.com", "Poitiers", "Didier@86");
        $this->loadAccount($accounts[3], $manager, "Tempesete", "Lafontaine", "F", new \DateTime("14-11-1953"), 86360, "17 Rue Charles Tempestier", "jeanB@gmail.com", "Chasseneuil-du-Poitou", "Didier@86");
        $this->loadAccount($accounts[4], $manager, "Slainie", "Giroud", "F", new \DateTime("13-02-1953"), 86580, "4 Allée Mansart", "slainieG86@gmail.com", "Vouneuil-sous-biard", "Didier@86");
        $this->loadAccount($accounts[5], $manager, "Porter", "Fluet", "M", new \DateTime("07-07-1947"), 86280, "78 Rue de la Rivière", "fluetporter@free.fr", "Saint-Benoit", "Didier@86");
        $this->loadAccount($accounts[6], $manager, "Antoinette", "Berthiaume", "F", new \DateTime("20-01-1989"), 86240, "14 Avenue Claude Chenou", "antoinette.berthiaume@live.fxs", "Smarves", "Didier@86");
        $this->loadAccount($accounts[7], $manager, "Camille", "Dubé", "F", new \DateTime("04-07-1995"), 86550, "11 Rue des Bons Samaritins", "camille.dube@etu.univ-poitirs.fr", "Mignalloux-beauvoir", "Didier@86");
        $this->loadAccount($accounts[8], $manager, "Leroy", "Tachel", "M", new \DateTime("15-12-1977"), 86000, "2 Rue de l'Université", "l.tachelB@gmail.com", "Poitiers", "Didier@86");
        $this->loadAccount($accounts[9], $manager, "Raoul", "St-Jean", "M", new \DateTime("18-07-1997"), 86000, "3 Avenue René Monory", "raoul.s.j@gmail.com", "Poitiers", "Didier@86");

        /*******************
         * Loading adherents
         *******************/
        for ($i = 0; $i < $adherentsNumber; $i++) {
            $adherent = new Adherent();
            array_push($adherents, $adherent);
        }

        /* ADHERENT 0 */
        $this->loadAdherent1($adherents[0], $manager, "Arthur", "Belge", "M", new \DateTime("01-12-2003"));
        $this->loadAdherent2($adherents[0], $manager, "Martin", "Belge", 86000, "5 Rue René Descartes", "martin.belge@gmail.com", "Poitiers", "Charpentier", "0654789574");
        $this->loadAdherent3($adherents[0], $manager, "Agnès", "Belge", 86000, "5 Rue René Descartes", "agnes.belge@gmail.com", "Poitiers", "Développeur", "0798754125");
        $this->loadAdherent4($adherents[0], $manager, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->loadAdherent5($adherents[0], $manager);

        $adherents[0]->setRegistrationCost(0);
        $adherents[0]->setRegistrationDate(new \DateTime("01-09-2018"));
        $adherents[0]->setVolunteerForTrainingHelp("Jamais");
        $adherents[0]->setVolunteerForClubLife("Jamais");
        $adherents[0]->setImageRight(false);
        $adherents[0]->setPaymentType("cheque");
        $adherents[0]->setHasMedicalCertificate(false);
        $adherents[0]->setHasHealthQuestionnaire(false);


        $manager->persist($adherents[0]);


        /* ADHERENT 1 */
        $this->loadAdherent1($adherents[1], $manager, "Charlotte", "Fraise", "F", new \DateTime("01-12-2015"));
        $this->loadAdherent2($adherents[1], $manager, "Aurélie", "Fraise", 86360, "114 bis Avennue Pascal Vert", "aurelie.fraise@gmail.com", "Chasseneuil-du-Poitou", "Menuisier", "0741254628");
        $this->loadAdherent3($adherents[1], $manager, "Maxime", "Fraise", 86360, "114 bis Avennue Pascal Vert", "maxime.fraise@hotmail.com", "Chasseneuil-du-Poitou", "Enseignant", "0325487514");
        $this->loadAdherent4($adherents[1], $manager, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->loadAdherent5($adherents[1], $manager);

        $adherents[1]->setRegistrationCost(0);
        $adherents[1]->setRegistrationDate(new \DateTime("11-10-2018"));
        $adherents[1]->setVolunteerForTrainingHelp("Jamais");
        $adherents[1]->setVolunteerForClubLife("Jamais");
        $adherents[1]->setImageRight(false);
        $adherents[1]->setPaymentType("cheque");
        $adherents[1]->setHasMedicalCertificate(false);
        $adherents[1]->setHasHealthQuestionnaire(false);


        $manager->persist($adherents[1]);


        /* ADHERENT 2 */
        $this->loadAdherent1($adherents[2], $manager, "Salomon", "Dupuit", "M", new \DateTime("08-01-2017"));
        $this->loadAdherent2($adherents[2], $manager, "Gildas", "Dupuit", 75008, "1 Avenue Champs Elysées", "gildas.dupuit@gmail.com", "Paris", "Plombier", "0147587412");
        $this->loadAdherent4($adherents[2], $manager, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->loadAdherent5($adherents[2], $manager);

        $adherents[2]->setRegistrationCost(0);
        $adherents[2]->setRegistrationDate(new \DateTime("05-01-2019"));
        $adherents[2]->setVolunteerForTrainingHelp("Jamais");
        $adherents[2]->setVolunteerForClubLife("Jamais");
        $adherents[2]->setImageRight(false);
        $adherents[2]->setPaymentType("cheque");
        $adherents[2]->setHasMedicalCertificate(false);
        $adherents[2]->setHasHealthQuestionnaire(false);

        $manager->persist($adherents[2]);

        /* ADHERENT 3 */
        $this->loadAdherent1($adherents[3], $manager, "René", "Tourcoing", "M", new \DateTime("01-12-1995"));
        $this->loadAdherent2($adherents[3], $manager, "Martin", "Belge", 86000, "14 Rue Henry Sucré ", "rene.tourcoing@gmail.com", "Poitiers", "Charpentier", "0654789574");
        $this->loadAdherent4($adherents[3], $manager, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->loadAdherent5($adherents[3], $manager);

        $adherents[3]->setRegistrationCost(0);
        $adherents[3]->setRegistrationDate(new \DateTime("12-05-2019"));
        $adherents[3]->setVolunteerForTrainingHelp("Jamais");
        $adherents[3]->setVolunteerForClubLife("Jamais");
        $adherents[3]->setImageRight(false);
        $adherents[3]->setPaymentType("cheque");
        $adherents[3]->setHasMedicalCertificate(false);
        $adherents[3]->setHasHealthQuestionnaire(false);

        $manager->persist($adherents[3]);

        /* ADHERENT 4 */
        $this->loadAdherent1($adherents[4], $manager, "Khaled", "Ratatouille", "M", new \DateTime("01-12-2003"));
        $this->loadAdherent2($adherents[4], $manager, "Marine", "Ratatouille", 86360, "XLIM Chasseneuil", "marineR@gmail.com", "Chasseneuil-du-Poitou", "Chroniqueuse", "0128547841");
        $this->loadAdherent3($adherents[4], $manager, "Gilbert", "Ratattouille", 86360, "XLIM Chasseneuil", "gigi26@gmail.com", "Chasseneuil-du-Poitou", "Youtubeur", "0451245674");
        $this->loadAdherent4($adherents[4], $manager, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->loadAdherent5($adherents[4], $manager);

        $adherents[4]->setRegistrationCost(0);
        $adherents[4]->setRegistrationDate(new \DateTime("16-11-2018"));
        $adherents[4]->setVolunteerForTrainingHelp("Jamais");
        $adherents[4]->setVolunteerForClubLife("Jamais");
        $adherents[4]->setImageRight(false);
        $adherents[4]->setPaymentType("cheque");
        $adherents[4]->setHasMedicalCertificate(false);
        $adherents[4]->setHasHealthQuestionnaire(false);

        $manager->persist($adherents[4]);

        /* ADHERENT 5 */
        $this->loadAdherent1($adherents[5], $manager, "Clause", "Hermel", "F", new \DateTime("01-12-2003"));
        $this->loadAdherent2($adherents[5], $manager, "Junior", "Hermel", 86000, "5 Rue René Descartes", "martin.belge@gmail.com", "Poitiers", "Agent d'entretien", "0654789574");
        $this->loadAdherent3($adherents[5], $manager, "Judith", "Parker", 86000, "5 Rue René Descartes", "agnes.belge@gmail.com", "Poitiers", "Basketteur professionnelle", "0798754125");
        $this->loadAdherent4($adherents[5], $manager, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->loadAdherent5($adherents[5], $manager);

        $adherents[5]->setRegistrationCost(0);
        $adherents[5]->setRegistrationDate(new \DateTime("30-09-2018"));
        $adherents[5]->setVolunteerForTrainingHelp("Jamais");
        $adherents[5]->setVolunteerForClubLife("Jamais");
        $adherents[5]->setImageRight(false);
        $adherents[5]->setPaymentType("cheque");
        $adherents[5]->setHasMedicalCertificate(false);
        $adherents[5]->setHasHealthQuestionnaire(false);

        $manager->persist($adherents[5]);

        /* ADHERENT 6 */
        $this->loadAdherent1($adherents[6], $manager, "Marion", "Lachaise", "F", new \DateTime("01-12-2003"));
        $this->loadAdherent2($adherents[6], $manager, "Omarion", "Lachaise", 86000, "8 Rue de L'avoine", "marion.lachaise@gmail.com", "Poitiers", "Juriste d'affaires", "0654789574");
        $this->loadAdherent3($adherents[6], $manager, "Tartiflette", "Lachaise", 86000, "8 Rue de L'avoine", "tartiflette.lachaise@gmail.com", "Poitiers", "Commerçante", "0798754125");
        $this->loadAdherent4($adherents[6], $manager, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->loadAdherent5($adherents[6], $manager);

        $adherents[6]->setRegistrationCost(0);
        $adherents[6]->setRegistrationDate(new \DateTime("11-02-2019"));
        $adherents[6]->setVolunteerForTrainingHelp("Jamais");
        $adherents[6]->setVolunteerForClubLife("Jamais");
        $adherents[6]->setImageRight(false);
        $adherents[6]->setPaymentType("cheque");
        $adherents[6]->setHasMedicalCertificate(false);
        $adherents[6]->setHasHealthQuestionnaire(false);

        $manager->persist($adherents[6]);

        /* ADHERENT 7 */
        $this->loadAdherent1($adherents[7], $manager, "Mickael", "Bellard", "M", new \DateTime("01-12-2003"));
        $this->loadAdherent2($adherents[7], $manager, "Suzanne", "Bellard", 86000, "3 Rue de la Nuitée", "suzanne.bel@live.fr", "Poitiers", "Enseignant", "0654789574");
        $this->loadAdherent3($adherents[7], $manager, "Romaric", "Bellard", 75000, "5 Avenue Montainge", "r.bellard@outlook.com", "Paris", "Magasinier", "0798754125");
        $this->loadAdherent4($adherents[7], $manager, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->loadAdherent5($adherents[7], $manager);

        $adherents[7]->setRegistrationCost(0);
        $adherents[7]->setRegistrationDate(new \DateTime("07-10-2018"));
        $adherents[7]->setVolunteerForTrainingHelp("Jamais");
        $adherents[7]->setVolunteerForClubLife("Jamais");
        $adherents[7]->setImageRight(false);
        $adherents[7]->setPaymentType("cheque");
        $adherents[7]->setHasMedicalCertificate(false);
        $adherents[7]->setHasHealthQuestionnaire(false);

        $manager->persist($adherents[7]);

        /* ADHERENT 8 */
        $this->loadAdherent1($adherents[8], $manager, "Rachelle", "Lenglen", "F", new \DateTime("01-12-1995"));
        $this->loadAdherent2($adherents[8], $manager, "Rachelle", "Lenglen", 86000, "3 bis Rue François Mittérand", "rlenglenn@free.fr", "Poitiers", "Etudiante", "0654789574");
        $this->loadAdherent4($adherents[8], $manager, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->loadAdherent5($adherents[8], $manager);

        $adherents[8]->setRegistrationCost(0);
        $adherents[8]->setRegistrationDate(new \DateTime("01-04-2019"));
        $adherents[8]->setVolunteerForTrainingHelp("Jamais");
        $adherents[8]->setVolunteerForClubLife("Jamais");
        $adherents[8]->setImageRight(false);
        $adherents[8]->setPaymentType("cheque");
        $adherents[8]->setHasMedicalCertificate(false);
        $adherents[8]->setHasHealthQuestionnaire(false);

        $manager->persist($adherents[8]);

        /* ADHERENT 9 */
        $this->loadAdherent1($adherents[9], $manager, "Alexandre", "Chevalier", "M", new \DateTime("01-12-2003"));
        $this->loadAdherent2($adherents[9], $manager, "Damien", "Chevalier", 86000, "5 Rue René Descartes", "d.chevalier358@gmail.com", "Poitiers", "Cuisinier", "0654789574");
        $this->loadAdherent3($adherents[9], $manager, "Roxane", "Chevalier", 86000, "5 Rue René Descartes", "rocane.chevalier@outlook.com", "Poitiers", "Livreuse en restauration", "0798754125");
        $this->loadAdherent4($adherents[9], $manager, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->loadAdherent5($adherents[9], $manager);

        $adherents[9]->setRegistrationCost(0);
        $adherents[9]->setRegistrationDate(new \DateTime("02-07-2018"));
        $adherents[9]->setVolunteerForTrainingHelp("Jamais");
        $adherents[9]->setVolunteerForClubLife("Jamais");
        $adherents[9]->setImageRight(false);
        $adherents[9]->setPaymentType("cheque");
        $adherents[9]->setHasMedicalCertificate(false);
        $adherents[9]->setHasHealthQuestionnaire(false);

        $manager->persist($adherents[9]);

        /*******************
         * Loading Category
         *******************/
        for ($i = 0; $i < $categoriesNumber; $i++) {
            $category = new Category();
            array_push($categories, $category);
        }

        $this->loadCategory($categories[0], $manager, "Baby Gym");
        $this->loadCategory($categories[1], $manager, "Baby Gym (Fontaine-le-Compte)");
        $this->loadCategory($categories[2], $manager, "Ecole de Gym Féminine");
        $this->loadCategory($categories[3], $manager, "Ecole de Gym Masculine");
        $this->loadCategory($categories[4], $manager, "Ecole de Gym (Fontaine-le-Compte)");
        $this->loadCategory($categories[5], $manager, "Autres Activités");
        $this->loadCategory($categories[6], $manager, "Gymnastique Artistique Féminine (GAF)");
        $this->loadCategory($categories[7], $manager, "Gymnastique Artistique Masculine (GAM)");
        $this->loadCategory($categories[8], $manager, "GAF-GAM");
        $this->loadCategory($categories[9], $manager, "Teamgym");


        /*******************
         * Loading Events
         *******************/
        $names = array("Championnat de france", "Assemblée générale", "Championnat départemental");
        $start = array("09-06-2019", "09-07-2019", "15-06-2019");
        $end = array("12-06-2019", "11-07-2019", "18-06-2019");
        $adresse = array("9 rue des chênes 86000 Poitiers", "9 rue des chênes 86000 Poitiers", "9 rue des chênes 86000 Poitiers");
        $description = array("Championnat de france 2019", "Assemblée générale du club", "Championnat départemental de la vienne");

        for ($i = 0; $i < 3; $i++) {
            $event = new Event();
            array_push($events, $event);
            $event->setName($names[$i]);
            $event->setStartDate(new \DateTime($start[$i]));
            $event->setEndDate(new \DateTime($end[$i]));

            $event->setAddress($adresse[$i]);
            $event->setDescription($description[$i]);

            $manager->persist($events[$i]);
        }


        /*// GIVING SOME CHILDREN TO SOME PARENTS
        // 1 - Giving children number 0, 1 and 2 to parents number 0
        $accounts[0]->addChild($adherents[0]);
        $accounts[0]->addChild($adherents[1]);
        $accounts[0]->addChild($adherents[2]);

        // 2 - Adding children 0 to parent 1
        // child 0 in common with parent number 0
        $accounts[1]->addChild($adherents[0]);

        // 3 - Giving children number 3 and 4 to parent number 2
        $accounts[2]->addChild($adherents[3]);
        $accounts[2]->addChild($adherents[4]);

        // 4 - Adding children 3 and 5 to parent 3
        // child 3 in common with parent number 2
        $accounts[3]->addChild($adherents[3]);
        $accounts[3]->addChild($adherents[5]);

        // 5 - Major parent adherent -- one adherent, himself
        $accounts[4]->addChild($adherents[6]);*/


        /*******************
         * Loading Activities
         *******************/

        for ($i = 0; $i < $activitiesNumber; $i++) {
            $activity = new Activity();
            array_push($activities, $activity);
            $manager->persist($activities[$i]);
        }

        /*
        for ($i = 0; $i < $activitiesNumber; $i++) {
            $activity = new Activity();
            array_push($activities, $activity);
        }*/

        $this->loadActivity($activities[0], $manager, "Baby Gym (nés en 2017)", 160, new \DateTime("15-09-2018"), "SECTEUR LOISIRS", $categories[0]);
        $this->loadActivity($activities[1], $manager, "Baby Gym (nés en 2016 + de juillet à décembre 2015)", 160, new \DateTime("15-09-2018"), "SECTEUR LOISIRS", $categories[0]);
        $this->loadActivity($activities[2], $manager, "Baby Gym (nés de janvier à juin 2015 + 2014)", 160, new \DateTime("15-09-2018"), "SECTEUR LOISIRS", $categories[0]);
        $this->loadActivity($activities[3], $manager, "Eveil Gym (nés en 2013)", 160, new \DateTime("15-09-2018"), "SECTEUR LOISIRS", $categories[0]);


        $this->loadActivity($activities[4], $manager, "Baby Gym (nés en 2017 et 2016)", 120, new \DateTime("11-09-2018"), "SECTEUR LOISIRS", $categories[1]);
        $this->loadActivity($activities[5], $manager, "Baby Gym (nés de 2015 à 2013)", 120, new \DateTime("11-09-2018"), "SECTEUR LOISIRS", $categories[1]);


        $this->loadActivity($activities[6], $manager, "EG1 (2012 à 2007)", 180, new \DateTime("15-09-2018"), "SECTEUR LOISIRS", $categories[2]);
        $this->loadActivity($activities[7], $manager, "EG2 (2012 à 2007)", 180, new \DateTime("15-09-2018"), "SECTEUR LOISIRS", $categories[2]);
        $this->loadActivity($activities[8], $manager, "EG3 (2006 et avant)", 180, new \DateTime("15-09-2018"), "SECTEUR LOISIRS", $categories[2]);
        $this->loadActivity($activities[9], $manager, "EG4 (2009 et avant)", 205, new \DateTime("15-09-2018"), "SECTEUR LOISIRS", $categories[2]);

        $this->loadActivity($activities[10], $manager, "EGM (2012 et avant)", 180, new \DateTime("12-09-2018"), "SECTEUR LOISIRS", $categories[3]);

        $this->loadActivity($activities[11], $manager, "EG5 (2012 à 2010)", 165, new \DateTime("10-09-2018"), "SECTEUR LOISIRS", $categories[4]);
        $this->loadActivity($activities[12], $manager, "EG6 (2009 et avant)", 165, new \DateTime("10-09-2018"), "SECTEUR LOISIRS", $categories[4]);
        $this->loadActivity($activities[13], $manager, "EG7 (2012 et avant)", 165, new \DateTime("13-09-2018"), "SECTEUR LOISIRS", $categories[4]);

        $this->loadActivity($activities[14], $manager, "Adultes", 125, new \DateTime("12-09-2018"), "SECTEUR LOISIRS", $categories[5]);
        $this->loadActivity($activities[15], $manager, "Renforcement musculaire", 125, new \DateTime("12-09-2018"), "SECTEUR LOISIRS", $categories[5]);
        $this->loadActivity($activities[16], $manager, "Freestyle Gym", 125, new \DateTime("12-09-2018"), "SECTEUR LOISIRS", $categories[5]);
        $this->loadActivity($activities[17], $manager, "Gym Adultes aux Agrès", 125, new \DateTime("12-09-2018"), "SECTEUR LOISIRS", $categories[5]);
        $this->loadActivity($activities[18], $manager, "Gym Santé Bien-Etre", 125, new \DateTime("12-09-2018"), "SECTEUR LOISIRS", $categories[5]);
        $this->loadActivity($activities[19], $manager, "Fitness", 125, new \DateTime("12-09-2018"), "SECTEUR LOISIRS", $categories[5]);
        $this->loadActivity($activities[20], $manager, "Gym Spectacle", 0, new \DateTime("01-01-2011"), "SECTEUR LOISIRS", $categories[5]);
        $this->loadActivity($activities[21], $manager, "Pilates", 0, new \DateTime("01-01-2011"), "SECTEUR LOISIRS", $categories[5]);

        $this->loadActivity($activities[21], $manager, "Poussines 1 (2012)", 205, new \DateTime("03-09-2018"), "SECTEUR LOISIRS", $categories[6]);
        $this->loadActivity($activities[22], $manager, "Poussines 2 (2011 et 2010)", 225, new \DateTime("03-09-2018"), "SECTEUR LOISIRS", $categories[6]);
        $this->loadActivity($activities[23], $manager, "Benjamines/Minimes 1 (2009 à 2006)", 205, new \DateTime("04-09-2018"), "SECTEUR LOISIRS", $categories[6]);
        $this->loadActivity($activities[24], $manager, "Benjamines/Minimes 2 (2009 à 2006)", 225, new \DateTime("03-09-2018"), "SECTEUR LOISIRS", $categories[6]);
        $this->loadActivity($activities[25], $manager, "Toutes Catégories 1 (2005 et plus)", 205, new \DateTime("04-09-2018"), "SECTEUR LOISIRS", $categories[6]);
        $this->loadActivity($activities[26], $manager, "Toutes Catégories 2 (2005 et plus)", 205, new \DateTime("03-09-2018"), "SECTEUR LOISIRS", $categories[6]);
        $this->loadActivity($activities[27], $manager, "Toutes Catégories 3 (2005 et plus)", 225, new \DateTime("04-09-2018"), "SECTEUR LOISIRS", $categories[6]);
        $this->loadActivity($activities[28], $manager, "Toutes Catégories 4 (2005 et plus)", 225, new \DateTime("03-09-2018"), "SECTEUR LOISIRS", $categories[6]);
        $this->loadActivity($activities[29], $manager, "Groupe Performance", 240, new \DateTime("03-09-2018"), "SECTEUR LOISIRS", $categories[6]);


        $this->loadActivity($activities[30], $manager, "Poussins (2012 à 2010)", 0, new \DateTime("03-09-2018"), "SECTEUR LOISIRS", $categories[7]);
        $this->loadActivity($activities[31], $manager, "Benjamins/Minimes/Cadets (2009 à 2004)", 0, new \DateTime("03-09-2018"), "SECTEUR LOISIRS", $categories[7]);
        $this->loadActivity($activities[32], $manager, "Juniors/Seniors (2003 et avant)", 0, new \DateTime("03-09-2018"), "SECTEUR LOISIRS", $categories[7]);

        $this->loadActivity($activities[33], $manager, "GAF-GAM", 325, new \DateTime("03-09-2018"), "SECTEUR LOISIRS", $categories[8]);

        $this->loadActivity($activities[34], $manager, "Découvertes / Evolution (2006 et avant) / Adultes", 0, new \DateTime("06-09-2018"), "SECTEUR LOISIRS", $categories[9]);
        $this->loadActivity($activities[35], $manager, "Détente / Passion", 250, new \DateTime("07-09-2018"), "SECTEUR LOISIRS", $categories[9]);


        /*****************************************************************
         * LOADING TIMESLOTS AND AFFECTING THEM TO CORRESPONDING ACTIVITY
         *****************************************************************/
        // TimeSlot of activity 0 Baby Gym 2017
        $this->loadTimeSlot($manager, 6, new \DateTime('09:15'), new \DateTime('10:00'), $activities[0], "");

        // TimeSlot of activity 1 Baby Gym 2016 ... 2015
        $this->loadTimeSlot($manager, 3, new \DateTime('17:00'), new \DateTime('17:45'), $activities[1], "");
        $this->loadTimeSlot($manager, 6, new \DateTime('09:15'), new \DateTime('10:00'), $activities[1], "");

        // TimeSlot of activity 2 Baby Gym 2013
        $this->loadTimeSlot($manager, 3, new \DateTime('18:00'), new \DateTime('19:00'), $activities[2], "");
        $this->loadTimeSlot($manager, 6, new \DateTime('10:15'), new \DateTime('11:10'), $activities[2], "");
        $this->loadTimeSlot($manager, 4, new \DateTime('17:30'), new \DateTime('18:30'), $activities[2], "");

        // TimeSlot of activity 3 Eveil Gym 2013
        $this->loadTimeSlot($manager, 3, new \DateTime('13:30'), new \DateTime('14:30'), $activities[3], "");
        $this->loadTimeSlot($manager, 6, new \DateTime('11:30'), new \DateTime('12:30'), $activities[3], "");

        // TimeSlot of activity 4 Baby Gym 2017 et 2016
        $this->loadTimeSlot($manager, 2, new \DateTime('17:30'), new \DateTime('18:15'), $activities[4], "Fontaine-le-comte");

        // TimeSlot of activity 5 Baby Gym 2015 à 2013
        $this->loadTimeSlot($manager, 2, new \DateTime('16:30'), new \DateTime('17:30'), $activities[5], "Fontaine-le-comte");

        // TimeSlot of activity 6 EG1
        $this->loadTimeSlot($manager, 3, new \DateTime('14:00'), new \DateTime('15:15'), $activities[6], "");
        $this->loadTimeSlot($manager, 3, new \DateTime('15:30'), new \DateTime('16:45'), $activities[6], "");
        $this->loadTimeSlot($manager, 4, new \DateTime('17:15'), new \DateTime('18:30'), $activities[6], "");
        $this->loadTimeSlot($manager, 6, new \DateTime('13:30'), new \DateTime('14:45'), $activities[6], "");

        // TimeSlot of activity 7 EG2
        $this->loadTimeSlot($manager, 3, new \DateTime('15:30'), new \DateTime('16:45'), $activities[7], "");
        $this->loadTimeSlot($manager, 3, new \DateTime('17:00'), new \DateTime('18:15'), $activities[7], "");
        $this->loadTimeSlot($manager, 4, new \DateTime('17:15'), new \DateTime('18:30'), $activities[7], "");
        $this->loadTimeSlot($manager, 6, new \DateTime('13:30'), new \DateTime('14:45'), $activities[7], "");

        // TimeSlot of activity 8 EG3
        $this->loadTimeSlot($manager, 4, new \DateTime('18:30'), new \DateTime('20:00'), $activities[8], "");
        $this->loadTimeSlot($manager, 6, new \DateTime('13:30'), new \DateTime('14:45'), $activities[8], "");

        // TimeSlot of activity 9 EG4
        $this->loadTimeSlot($manager, 3, new \DateTime('19:00'), new \DateTime('20:30'), $activities[9], "");
        $this->loadTimeSlot($manager, 6, new \DateTime('15:00'), new \DateTime('16:15'), $activities[9], "");

        // TimeSlot of activity 10 EGM
        $this->loadTimeSlot($manager, 3, new \DateTime('15:30'), new \DateTime('17:00'), $activities[10], "");

        // TimeSlot of activity 11 EG5
        $this->loadTimeSlot($manager, 1, new \DateTime('16:45'), new \DateTime('18:15'), $activities[11], "Fontaine-le-Comte");

        // TimeSlot of activity 12 EG6
        $this->loadTimeSlot($manager, 1, new \DateTime('18:15'), new \DateTime('19:40'), $activities[12], "");

        // TimeSlot of activity 13 EG7
        $this->loadTimeSlot($manager, 4, new \DateTime('16:30'), new \DateTime('17:45'), $activities[13], "Fontaine-le-Compte");

        // TimeSlot of activity 14 Adultes
        $this->loadTimeSlot($manager, 3, new \DateTime('19:45'), new \DateTime('21:15'), $activities[14], "");

        // TimeSlot of activity 15 Renforcement musculaire
        $this->loadTimeSlot($manager, 3, new \DateTime('19:00'), new \DateTime('20:00'), $activities[15], "");

        // TimeSlot of activity 16 Freestyle Gym
        $this->loadTimeSlot($manager, 6, new \DateTime('15:00'), new \DateTime('16:30'), $activities[16], "");

        // TimeSlot of activity 17 Gym Adultes aux Agrès
        $this->loadTimeSlot($manager, 0, new \DateTime('10:00'), new \DateTime('12:00'), $activities[17], "");

        // TimeSlot of activity 18 Gym Santé Bien-Etre
        $this->loadTimeSlot($manager, 6, new \DateTime('11:30'), new \DateTime('12:30'), $activities[18], "");

        // TimeSlot of activity 19 Fitness
        $this->loadTimeSlot($manager, 1, new \DateTime('19:00'), new \DateTime('20:00'), $activities[19], "");


        // TimeSlot of activity 22 Poussines 1
        $this->loadTimeSlot($manager, 1, new \DateTime('17:30'), new \DateTime('19:00'), $activities[22], "");
        $this->loadTimeSlot($manager, 4, new \DateTime('17:30'), new \DateTime('19:00'), $activities[22], "");

        // TimeSlot of activity 23 Poussines 2
        $this->loadTimeSlot($manager, 1, new \DateTime('17:30'), new \DateTime('19:30'), $activities[23], "");
        $this->loadTimeSlot($manager, 4, new \DateTime('17:00'), new \DateTime('19:00'), $activities[23], "");

        // TimeSlot of activity 24 Benjamines/Minimes 1
        $this->loadTimeSlot($manager, 2, new \DateTime('17:30'), new \DateTime('19:30'), $activities[24], "");
        $this->loadTimeSlot($manager, 4, new \DateTime('17:30'), new \DateTime('19:30'), $activities[24], "");

        // TimeSlot of activity 25 Benjamines/Minimes 2
        $this->loadTimeSlot($manager, 1, new \DateTime('17:30'), new \DateTime('19:30'), $activities[25], "");
        $this->loadTimeSlot($manager, 3, new \DateTime('18:30'), new \DateTime('20:30'), $activities[25], "");
        $this->loadTimeSlot($manager, 5, new \DateTime('20:00'), new \DateTime('22:00'), $activities[25], "");

        // TimeSlot of activity 26 Toutes Catégories 1
        $this->loadTimeSlot($manager, 2, new \DateTime('18:30'), new \DateTime('20:30'), $activities[26], "");
        $this->loadTimeSlot($manager, 5, new \DateTime('19:30'), new \DateTime('21:30'), $activities[26], "");

        // TimeSlot of activity 27 Toutes Catégories 2
        $this->loadTimeSlot($manager, 1, new \DateTime('19:00'), new \DateTime('21:00'), $activities[27], "");
        $this->loadTimeSlot($manager, 5, new \DateTime('19:00'), new \DateTime('21:00'), $activities[27], "");

        // TimeSlot of activity 28 Toutes Catégories 3
        $this->loadTimeSlot($manager, 2, new \DateTime('19:15'), new \DateTime('21:15'), $activities[28], "");
        $this->loadTimeSlot($manager, 3, new \DateTime('18:30'), new \DateTime('20:30'), $activities[28], "");
        $this->loadTimeSlot($manager, 5, new \DateTime('20:00'), new \DateTime('22:00'), $activities[28], "");

        // TimeSlot of activity 29 Groupe Performance
        $this->loadTimeSlot($manager, 1, new \DateTime('19:30'), new \DateTime('21:00'), $activities[29], "");
        $this->loadTimeSlot($manager, 2, new \DateTime('18:00'), new \DateTime('20:30'), $activities[29], "");
        $this->loadTimeSlot($manager, 3, new \DateTime('18:00'), new \DateTime('20:30'), $activities[29], "");
        $this->loadTimeSlot($manager, 5, new \DateTime('18:30'), new \DateTime('20:00'), $activities[29], "");
        $this->loadTimeSlot($manager, 6, new \DateTime('10:30'), new \DateTime('13:00'), $activities[29], "");


        // TimeSlot of activity 31 Benjamins/Minimes/Cadets
        $this->loadTimeSlot($manager, 1, new \DateTime('17:45'), new \DateTime('19:45'), $activities[31], "");
        $this->loadTimeSlot($manager, 4, new \DateTime('17:45'), new \DateTime('19:45'), $activities[31], "");
        $this->loadTimeSlot($manager, 5, new \DateTime('17:45'), new \DateTime('19:45'), $activities[31], "");
        $this->loadTimeSlot($manager, 6, new \DateTime('10:30'), new \DateTime('13:00'), $activities[31], "");

        // TimeSlot of activity 32 Juniors/Seniors
        $this->loadTimeSlot($manager, 1, new \DateTime('19:30'), new \DateTime('21:30'), $activities[32], "");
        $this->loadTimeSlot($manager, 4, new \DateTime('19:30'), new \DateTime('21:30'), $activities[32], "");
        $this->loadTimeSlot($manager, 5, new \DateTime('19:30'), new \DateTime('21:30'), $activities[32], "");
        $this->loadTimeSlot($manager, 6, new \DateTime('10:30'), new \DateTime('13:00'), $activities[32], "");

        // TimeSlot of activity 33 Horaires aménagés (Isaac de l'Etoile)
        // Multi-créneaux ?
        //$this->loadTimeSlot($manager, 6, new \DateTime('09:15'), new \DateTime('10:00'), $activities[33], "");

        // TimeSlot of activity 34 Découverte / Evolution  - / Adultes
        $this->loadTimeSlot($manager, 4, new \DateTime('19:30'), new \DateTime('21:30'), $activities[34], "");

        // TimeSlot of activity 35 Détente passion
        $this->loadTimeSlot($manager, 5, new \DateTime('20:00'), new \DateTime('22:00'), $activities[35], "");


        /*$manager->persist($t);
        $t->setDay(6);
        $t->setStartTime(new \DateTime('12:30'));
        $t->setEndTime(new \DateTime('12:30'));

        $activities[0]->addTimeSlot($t);*/

        //$t->setCity();

        //array_push($activities, $activity);
        //$manager->persist($activities[$i]);


        // ADDING BETWEEN ONE AND THREE ACTIVITIES TO EACH ADHERENTS
        for ($i = 0; $i < $adherentsNumber; $i++) {

            $numberOfActivity = rand(1, 3);

            for ($j = 0; $j < $numberOfActivity; $j++) {
                // get random index from array $activities
                $activityIndex = array_rand($activities);

                // get the value for the random index
                $activity = $activities[$activityIndex];

            }

        }
        $manager->flush();
    }
}
