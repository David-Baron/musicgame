<?php
namespace App\DataFixtures\Install;

use App\Entity\User;
use App\Entity\Setting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InstallFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
         $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setEmail('admin@admin.com');
        $user->setPassword($this->passwordEncoder->hashPassword(
            $user,
            'admin'
        ));
        $user->setIsVerified(1);
        $manager->persist($user);
        $manager->flush();

        $settings=[
            ['name'=>'website_domain', 'value'=>'', 'description' => 'The domain name ex: mydomain.com.'],
            ['name'=>'website_name', 'value'=>'My website', 'description' => 'The website name.'],
            ['name'=>'website_title', 'value'=>'Beautiful website', 'description' => 'The website title.'],
            ['name'=>'website_theme', 'value'=>'default', 'description' => 'The templates name. By default: default.'],
            ['name'=>'website_icon', 'value'=>'musicgame.png', 'description' => 'The website icon. By default: musicgame.png.'],
        ];
        foreach ($settings as $entry) {
            $setting = new Setting;
            $setting->setName($entry['name']);
            $setting->setValue($entry['value']);
            $setting->setDescription($entry['description']);
            $manager->persist($setting);
            
        }
        $manager->flush();
    }
}
