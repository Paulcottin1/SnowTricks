<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /**
         * Create new user
         */
        $user = new User();
        $password = $this->encoder->encodePassword($user, 'admin');
        $user
            ->setFirstname('Admin')
            ->setLastname('Admin')
            ->setEmail('admin@gmail.com')
            ->setPassword($password)
            ->setAvatar('default-avatar.png')
            ->setCreatedDate(new \DateTime())
            ->setRole('ROLE_ADMIN');

        /**
         * Create category
         */
        $categoryGrabs = new Category();
        $categoryGrabs
            ->setTitle('Grabs')
            ->setDescription('Un grab consiste à attraper la planche avec la main pendant le saut.');
        $manager->persist($categoryGrabs);

        $categoryRotation = new Category();
        $categoryRotation
            ->setTitle('Rotation')
            ->setDescription('On désigne par le mot « rotation » uniquement des rotations horizontales');
        $manager->persist($categoryRotation);

        $categorySlide= new Category();
        $categorySlide
            ->setTitle('Slide')
            ->setDescription('Un slide consiste à glisser sur une barre de slide');
        $manager->persist($categorySlide);

        /**
         * Create first trick
         */
        $trick = new Trick();
        $trick->setTitle('Stalefish')
            ->setChapo('Saisie de la carre backside de la planche entre les deux pieds avec la main arrière')
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas semper quam mi, quis accumsan lectus pretium quis. Sed pulvinar sapien feugiat purus dictum vestibulum. Ut volutpat ante eget finibus feugiat. Donec quis luctus eros, eget efficitur eros. Vivamus sapien quam, maximus elementum laoreet non, ultricies in ante. Praesent neque massa, luctus eget condimentum non, tincidunt at arcu. Donec congue, ex nec ornare euismod, tellus magna varius nisi, eu pretium metus mauris et enim. Morbi in malesuada erat. Pellentesque diam arcu, sodales eu tincidunt et, iaculis in lacus. Nam porttitor tellus vitae tristique feugiat. In sollicitudin, tellus nec efficitur porttitor, sem mauris accumsan magna, et accumsan dolor dui non diam. Curabitur quis massa tellus. Pellentesque porta nunc nulla, et pulvinar quam consequat ut.'
            )
            ->setImageName('stalefish.jpg')
            ->setUser($user)
            ->setCategory($categoryGrabs)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $manager->persist($trick);

        /**
         * Create second trick
         */
        $trick = new Trick();
        $trick->setTitle('360')
            ->setChapo('Rotation à 360 degrès')
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas semper quam mi, quis accumsan lectus pretium quis. Sed pulvinar sapien feugiat purus dictum vestibulum. Ut volutpat ante eget finibus feugiat. Donec quis luctus eros, eget efficitur eros. Vivamus sapien quam, maximus elementum laoreet non, ultricies in ante. Praesent neque massa, luctus eget condimentum non, tincidunt at arcu. Donec congue, ex nec ornare euismod, tellus magna varius nisi, eu pretium metus mauris et enim. Morbi in malesuada erat. Pellentesque diam arcu, sodales eu tincidunt et, iaculis in lacus. Nam porttitor tellus vitae tristique feugiat. In sollicitudin, tellus nec efficitur porttitor, sem mauris accumsan magna, et accumsan dolor dui non diam. Curabitur quis massa tellus. Pellentesque porta nunc nulla, et pulvinar quam consequat ut.'
            )
            ->setImageName('360.jpg')
            ->setUser($user)
            ->setCategory($categoryRotation)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $manager->persist($trick);

        /**
         * Create 3th trick
         */
        $trick = new Trick();
        $trick->setTitle('Nose Slide')
            ->setChapo('Slide sur l\'avant de la planche')
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas semper quam mi, quis accumsan lectus pretium quis. Sed pulvinar sapien feugiat purus dictum vestibulum. Ut volutpat ante eget finibus feugiat. Donec quis luctus eros, eget efficitur eros. Vivamus sapien quam, maximus elementum laoreet non, ultricies in ante. Praesent neque massa, luctus eget condimentum non, tincidunt at arcu. Donec congue, ex nec ornare euismod, tellus magna varius nisi, eu pretium metus mauris et enim. Morbi in malesuada erat. Pellentesque diam arcu, sodales eu tincidunt et, iaculis in lacus. Nam porttitor tellus vitae tristique feugiat. In sollicitudin, tellus nec efficitur porttitor, sem mauris accumsan magna, et accumsan dolor dui non diam. Curabitur quis massa tellus. Pellentesque porta nunc nulla, et pulvinar quam consequat ut.'
            )
            ->setImageName('nose.jpg')
            ->setUser($user)
            ->setCategory($categorySlide)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $manager->persist($trick);

        /**
         * Create 4th trick
         */
        $trick = new Trick();
        $trick->setTitle('Mute')
            ->setChapo('Saisie de la carre frontside de la planche entre les deux pieds avec la main avant')
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas semper quam mi, quis accumsan lectus pretium quis. Sed pulvinar sapien feugiat purus dictum vestibulum. Ut volutpat ante eget finibus feugiat. Donec quis luctus eros, eget efficitur eros. Vivamus sapien quam, maximus elementum laoreet non, ultricies in ante. Praesent neque massa, luctus eget condimentum non, tincidunt at arcu. Donec congue, ex nec ornare euismod, tellus magna varius nisi, eu pretium metus mauris et enim. Morbi in malesuada erat. Pellentesque diam arcu, sodales eu tincidunt et, iaculis in lacus. Nam porttitor tellus vitae tristique feugiat. In sollicitudin, tellus nec efficitur porttitor, sem mauris accumsan magna, et accumsan dolor dui non diam. Curabitur quis massa tellus. Pellentesque porta nunc nulla, et pulvinar quam consequat ut.'
            )
            ->setImageName('mute.jpg')
            ->setUser($user)
            ->setCategory($categoryGrabs)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $manager->persist($trick);

        /**
         * Create 5th trick
         */
        $trick = new Trick();
        $trick->setTitle('Indy')
            ->setChapo('Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière')
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas semper quam mi, quis accumsan lectus pretium quis. Sed pulvinar sapien feugiat purus dictum vestibulum. Ut volutpat ante eget finibus feugiat. Donec quis luctus eros, eget efficitur eros. Vivamus sapien quam, maximus elementum laoreet non, ultricies in ante. Praesent neque massa, luctus eget condimentum non, tincidunt at arcu. Donec congue, ex nec ornare euismod, tellus magna varius nisi, eu pretium metus mauris et enim. Morbi in malesuada erat. Pellentesque diam arcu, sodales eu tincidunt et, iaculis in lacus. Nam porttitor tellus vitae tristique feugiat. In sollicitudin, tellus nec efficitur porttitor, sem mauris accumsan magna, et accumsan dolor dui non diam. Curabitur quis massa tellus. Pellentesque porta nunc nulla, et pulvinar quam consequat ut.'
            )
            ->setImageName('indy.jpg')
            ->setUser($user)
            ->setCategory($categoryGrabs)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $manager->persist($trick);

        /**
         * Create 6th trick
         */
        $trick = new Trick();
        $trick->setTitle('720')
            ->setChapo('Rotation sur soit même de deux tours complets')
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas semper quam mi, quis accumsan lectus pretium quis. Sed pulvinar sapien feugiat purus dictum vestibulum. Ut volutpat ante eget finibus feugiat. Donec quis luctus eros, eget efficitur eros. Vivamus sapien quam, maximus elementum laoreet non, ultricies in ante. Praesent neque massa, luctus eget condimentum non, tincidunt at arcu. Donec congue, ex nec ornare euismod, tellus magna varius nisi, eu pretium metus mauris et enim. Morbi in malesuada erat. Pellentesque diam arcu, sodales eu tincidunt et, iaculis in lacus. Nam porttitor tellus vitae tristique feugiat. In sollicitudin, tellus nec efficitur porttitor, sem mauris accumsan magna, et accumsan dolor dui non diam. Curabitur quis massa tellus. Pellentesque porta nunc nulla, et pulvinar quam consequat ut.'
            )
            ->setImageName('720.jpg')
            ->setUser($user)
            ->setCategory($categoryRotation)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $manager->persist($trick);

        /**
         * Create 7th trick
         */
        $trick = new Trick();
        $trick->setTitle('Japan Air')
            ->setChapo('Saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside.')
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas semper quam mi, quis accumsan lectus pretium quis. Sed pulvinar sapien feugiat purus dictum vestibulum. Ut volutpat ante eget finibus feugiat. Donec quis luctus eros, eget efficitur eros. Vivamus sapien quam, maximus elementum laoreet non, ultricies in ante. Praesent neque massa, luctus eget condimentum non, tincidunt at arcu. Donec congue, ex nec ornare euismod, tellus magna varius nisi, eu pretium metus mauris et enim. Morbi in malesuada erat. Pellentesque diam arcu, sodales eu tincidunt et, iaculis in lacus. Nam porttitor tellus vitae tristique feugiat. In sollicitudin, tellus nec efficitur porttitor, sem mauris accumsan magna, et accumsan dolor dui non diam. Curabitur quis massa tellus. Pellentesque porta nunc nulla, et pulvinar quam consequat ut.'
            )
            ->setImageName('japan.jpg')
            ->setUser($user)
            ->setCategory($categoryGrabs)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $manager->persist($trick);

        /**
         * Create 8th trick
         */
        $trick = new Trick();
        $trick->setTitle('Tail Grab')
            ->setChapo('Saisie de la partie arrière de la planche, avec la main arrière')
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas semper quam mi, quis accumsan lectus pretium quis. Sed pulvinar sapien feugiat purus dictum vestibulum. Ut volutpat ante eget finibus feugiat. Donec quis luctus eros, eget efficitur eros. Vivamus sapien quam, maximus elementum laoreet non, ultricies in ante. Praesent neque massa, luctus eget condimentum non, tincidunt at arcu. Donec congue, ex nec ornare euismod, tellus magna varius nisi, eu pretium metus mauris et enim. Morbi in malesuada erat. Pellentesque diam arcu, sodales eu tincidunt et, iaculis in lacus. Nam porttitor tellus vitae tristique feugiat. In sollicitudin, tellus nec efficitur porttitor, sem mauris accumsan magna, et accumsan dolor dui non diam. Curabitur quis massa tellus. Pellentesque porta nunc nulla, et pulvinar quam consequat ut.'
            )
            ->setImageName('tail.jpg')
            ->setUser($user)
            ->setCategory($categoryGrabs)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $manager->persist($trick);

        /**
         * Create 9th trick
         */
        $trick = new Trick();
        $trick->setTitle('Sad Grab')
            ->setChapo('Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant')
            ->setContent(
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas semper quam mi, quis accumsan lectus pretium quis. Sed pulvinar sapien feugiat purus dictum vestibulum. Ut volutpat ante eget finibus feugiat. Donec quis luctus eros, eget efficitur eros. Vivamus sapien quam, maximus elementum laoreet non, ultricies in ante. Praesent neque massa, luctus eget condimentum non, tincidunt at arcu. Donec congue, ex nec ornare euismod, tellus magna varius nisi, eu pretium metus mauris et enim. Morbi in malesuada erat. Pellentesque diam arcu, sodales eu tincidunt et, iaculis in lacus. Nam porttitor tellus vitae tristique feugiat. In sollicitudin, tellus nec efficitur porttitor, sem mauris accumsan magna, et accumsan dolor dui non diam. Curabitur quis massa tellus. Pellentesque porta nunc nulla, et pulvinar quam consequat ut.'
            )
            ->setImageName('sad.jpg')
            ->setUser($user)
            ->setCategory($categoryGrabs)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $manager->persist($trick);

        $manager->flush();
    }
}
