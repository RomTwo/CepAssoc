<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Account;
use App\Entity\Adherent;
use App\Entity\Category;
use App\Entity\Event;
use App\Entity\Activity;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $accountsNumber = 10;
        $adherentsNumber = 10;
        $categoriesNumber = 5;
        $eventsNumber = 5;
        $activitiesNumber = 5;
        
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
            $account->setFirstName('parent');
            $account->setLastName($i);
            
            if(($i % 2) == 0)
                $account->setSex('F');
            else
                $account->setSex('M');

            $account->setBirthDate(new \DateTime("11-11-1994"));
            $account->setZipCode(86000);
            $account->setAddress(87);
            $account->setEmail("parent".$i."@gmail.com");
            $account->setCity("Poitiers");
            $account->setPassWord("toto");

            $manager->persist($accounts[$i]);
        }

        /*******************
         * Loading adherents
         *******************/
        for ($i = 0; $i < $adherentsNumber; $i++) {
            $adherent = new Adherent();
            array_push($adherents, $adherent);
            $adherent->setFirstName('adherent');
            $adherent->setLastName($i);

            if(($i % 2) == 0)
                $adherent->setSex('F');
            else
                $adherent->setSex('M');

            $adherent->setBirthDate(new \DateTime("07-05-2019"));
            $adherent->setFirstNameRep1("dede");
            $adherent->setFirstNameRep2("dede");
            $adherent->setLastNameRep1("dede");
            $adherent->setLastNameRep2("dede");
            $adherent->setZipCodeRep1(75000);
            $adherent->setZipCodeRep2(75000);
            $adherent->setAddressRep1($i."rue Castor");
            $adherent->setAddressRep2($i."rue Castor");
            $adherent->setEmailRep1("adherent".$i."@gmail.com");
            $adherent->setEmailRep2("adherent".$i."@gmail.com");
            $adherent->setCityRep1("Paris");
            $adherent->setCityRep2("Paris");
            $adherent->setProfessionRep1("profession1");
            $adherent->setProfessionRep2("profession2");
            $adherent->setPhoneRep1("0000000000");
            $adherent->setPhoneRep2("0000000000");
            if(($i % 2) == 0)
                $adherent->setJudge(true);
            else
                $adherent->setJudge(false);

            if(($i % 3) == 0)
                $adherent->setIsGAFJudge(true);
            else
                $adherent->setIsGAFJudge(false);
            
            $adherent->setGAFJudgeLevel($i);
            
            if(($i % 4) == 0)
                $adherent->setIsGAMJudge(true);
            else
                $adherent->setIsGAMJudge(false);
            
            $adherent->setGAMJudgeLevel($i);

            if(($i % 4) == 0)
                $adherent->setIsTeamGYMJudge(true);
            else
                $adherent->setIsTeamGYMJudge(false);
            
            $adherent->setTeamGYMJudgeLevel($i);

            if(($i % 3) == 0)
                $adherent->setWantsAJudgeTraining(true);
            else
                $adherent->setWantsAJudgeTraining(false);


            $adherent->setRegistrationType("type".$i);
            $adherent->setRegistrationDate(new \DateTime("01-09-2019"));
            $adherent->setpaymentFeesArePaid(false);
            $adherent->setisRegisteredInGestGym(false);
            $adherent->setvolunteerForTrainingHelp(false);
            $adherent->setvolunteerForClubLife(false);

            $manager->persist($adherents[$i]);
        }

        /*******************
         * Loading Category
         *******************/
        for ($i = 0; $i < $categoriesNumber; $i++) {
            $category = new Category();
            array_push($categories, $category);
            $category->setName("group".$i);

            $manager->persist($categories[$i]);
        }


        /*******************
         * Loading Events
         *******************/
        for ($i = 0; $i < $categoriesNumber; $i++) {
            $event = new Event();
            array_push($events, $event);
            $event->setName("event".$i);
            $event->setDate(new \DateTime("09-05-2019"));
            $event->setAddress("address of event".$i);
            $event->setDescription("Description of event".$i);
            if(($i % 2) == 0)
                $event->setAuthorizationOfOrganization(true);
            else
            $event->setAuthorizationOfOrganization(false);
            
            $manager->persist($events[$i]);
        }


        // GIVING SOME CHILDREN TO SOME PARENTS
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
        $accounts[4]->addChild($adherents[6]);


        /*******************
         * Loading Activities
         *******************/
        // simple loading. one activity for each category

        for ($i = 0; $i < $activitiesNumber; $i++) {
            $activity = new Activity();
            array_push($activities, $activity);

            $activity->setName("activity".$i);
            $activity->setPrice(10 + $i*10);
            $activity->setStartDate(new \DateTime("09-05-2019"));
            $activity->setType("type of activity".$i);
            $activity->setCategory($categories[$i]);

            $manager->persist($activities[$i]);
        }

        
        // ADDING BETWEEN ONE AND THREE ACTIVITIES TO EACH ADHERENTS
        for ($i = 0; $i < $adherentsNumber; $i++) {

            $numberOfActivity = rand(1,3);

            for($j = 0; $j < $numberOfActivity; $j++){
                // get random index from array $activities
                $activityIndex = array_rand($activities);
 
                // get the value for the random index
                $activity = $activities[$activityIndex];

                $adherents[$i]->addActivity($activity);
            }
            
        }

        $manager->flush();
        
    }
}
