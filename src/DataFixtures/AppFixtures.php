<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\OptionPurchase;
use App\Entity\Product;
use App\Entity\ProductParameter;
use App\Entity\Subcategory;
use App\Entity\Theme;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;


    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        //CREATION DE L'ADMIN
        $dateTime = new \DateTime();
        $admin = new User();
        $admin->setUsername('admin');
        //$password = $this->encoder->encodePassword($admin, 'password');
        $admin->setPassword('password');
        $admin->setIsactive(1);
        $admin->setCreatedAt($dateTime);
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        $manager->flush();

        //CREATION DE USERS
        for($j = 0; $j < 10; $j++)
        {
            $dateTime = new \DateTime();
            $user = new User();
            $user->setUsername('user'.$j);
            $user->setPassword($this->encoder->encodePassword(
                $user,
                'password'.$j
            ));
            $user->setIsactive(1);
            $user->setCreatedAt($dateTime);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        $manager->flush();

        //CREATION DE CATEGORIES
        $categorieH = new Category();
        $categorieH->setName('Homme');

        $categorieF = new Category();
        $categorieF->setName('Femme');

        $categorieE = new Category();
        $categorieE->setName('Enfant');

        $manager->persist($categorieH);
        $manager->persist($categorieF);
        $manager->persist($categorieE);

        $manager->flush();


        //CREATION DE SOUS CATEGORIES
        $subCategorieP = new Subcategory();
        $subCategorieP->setName('Pull');

        $subCategorieB = new Subcategory();
        $subCategorieB->setName('Bonnet');

        $subCategorieC = new Subcategory();
        $subCategorieC->setName('Chaussette');

        $manager->persist($subCategorieP);
        $manager->persist($subCategorieB);
        $manager->persist($subCategorieC);

        $manager->flush();

        //CREATION DE THEMES
        $themeSW = new Theme();
        $themeSW->setName('Star Wars');

        $themeJV = new Theme();
        $themeJV->setName('Jeux Vidéo');

        $themePP = new Theme();
        $themePP->setName('Pompoms');

        $manager->persist($themeSW);
        $manager->persist($themeJV);
        $manager->persist($themePP);
        $manager->flush();


        //CREATION D'OPTIONS
        $optionLE = new OptionPurchase();
        $optionLE->setName('Livraison Express');

        $manager->persist($optionLE);
        $manager->flush();

        //CREATION DE COMMENTAIRES
        $commentContent = array("Nulla facilisi. Cras non velit nec nisi vulputate nonummy. Maecenas tincidunt lacus at velit.", "Phasellus sit amet erat. Nulla tempus. Vivamus in felis eu sapien cursus vestibulum. Proin eu mi.",
            "Duis consequat dui nec nisi volutpat eleifend. Donec ut dolor. Morbi vel lectus in quam fringilla rhoncus. Mauris enim leo, rhoncus sed, vestibulum sit amet, cursus id, turpis. Integer aliquet, massa id lobortis convallis, tortor risus dapibus augue, vel accumsan tellus nisi eu orci.",
            "Curabitur in libero ut massa volutpat convallis. Morbi odio odio, elementum eu, interdum eu, tincidunt in, leo. Maecenas pulvinar lobortis est.","Morbi sem mauris, laoreet ut, rhoncus aliquet, pulvinar sed, nisl. Nunc rhoncus dui vel sem. Sed sagittis.",
            "Mauris lacinia sapien quis libero.", "In sagittis dui vel nisl. Duis ac nibh. Fusce lacus purus, aliquet at, feugiat non, pretium quis, lectus.", "Quisque ut erat. Curabitur gravida nisi at nibh. In hac habitasse platea dictumst. Aliquam augue quam, sollicitudin vitae, consectetuer eget, rutrum at, lorem. Integer tincidunt ante vel ipsum.",
            "Nulla tempus. Vivamus in felis eu sapien cursus vestibulum. Proin eu mi. Nulla ac enim.", "Integer ac leo. Pellentesque ultrices mattis odio. Donec vitae nisi. Nam ultrices, libero non mattis pulvinar, nulla pede ullamcorper augue, a suscipit nulla elit ac nulla.",
            "Integer tincidunt ante vel ipsum. Praesent blandit lacinia erat.", "Nam ultrices, libero non mattis pulvinar, nulla pede ullamcorper augue, a suscipit nulla elit ac nulla. Sed vel enim sit amet nunc viverra dapibus.", "Morbi non quam nec dui luctus rutrum.");
        $users = $manager->getRepository(User::class)->findAll();

        //CREATION DE PRODUITS
        $sizes = ['S', 'M', 'L', 'XL', 'XXL', 'Unique', '36 - 39', '40 - 43', '44 - 46'];
        $prices = [9.99, 19.99, 29.99, 39.99, 49.99, 59.99];
        $categories = $manager->getRepository(Category::class)->findAll();
        $subCategoriePull = $manager->getRepository(Subcategory::class)->findOneByName("Pull");
        $subCategorieBonnet = $manager->getRepository(Subcategory::class)->findOneByName("Bonnet");
        $subCategorieChaussette = $manager->getRepository(Subcategory::class)->findOneByName("Chaussette");
        $themes = $manager->getRepository(Theme::class)->findAll();

            //CREATION DE PULLS
            for ($i = 0; $i < 20; $i++) {
                $dateTime = new \DateTime();
                $comment = new Comment();
                $comment->setVisible(mt_rand(0, 1));
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName('Pull '.$i);
                $product->setPrice($prices[mt_rand(2, 5)]);
                $product->setDescription('Ceci est le pull '.$i);
                $product->setImage('0'.$i.'.jpg');
                $product->setCreatedAt($dateTime);
                $product->setVisible(mt_rand(0, 1));
                $product->addProductParameter(new ProductParameter($sizes[mt_rand(0, 4)], mt_rand(0, 10)));
                $product->addCategory($categories[mt_rand(0, 2)]);
                $product->addSubcategory($subCategoriePull);
                $product->addTheme($themes[mt_rand(0, 2)]);
                $product->addComment($comment);
                $manager->persist($product);
            }

            //CREATION DE BONNETS
            for ($i = 20; $i < 40; $i++) {
                $dateTime = new \DateTime();
                $comment = new Comment();
                $comment->setVisible(mt_rand(0, 1));
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName('Bonnet '.$i);
                $product->setPrice($prices[mt_rand(0, 1)]);
                $product->setDescription('Ceci est le bonnet '.$i);
                $product->setImage('0'.$i.'.jpg');
                $product->setCreatedAt($dateTime);
                $product->setVisible(mt_rand(0, 1));
                $product->addProductParameter(new ProductParameter($sizes[5], mt_rand(0, 10)));
                $product->addCategory($categories[mt_rand(0, 2)]);
                $product->addSubcategory($subCategorieBonnet);
                $product->addTheme($themes[mt_rand(0, 2)]);
                $product->addComment($comment);
                $manager->persist($product);
            }

            //CREATION DE CHAUSSETTES
            for ($i = 60; $i < 70; $i++) {
                $dateTime = new \DateTime();
                $comment = new Comment();
                $comment->setVisible(mt_rand(0, 1));
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName('Chaussette '.$i);
                $product->setPrice($prices[mt_rand(0, 1)]);
                $product->setDescription('Ceci est la paire de chaussette '.$i);
                $product->setImage('0'.$i.'.jpg');
                $product->setCreatedAt($dateTime);
                $product->setVisible(mt_rand(0, 1));
                $product->addProductParameter(new ProductParameter($sizes[mt_rand(6, 8)], mt_rand(0, 10)));
                $product->addCategory($categories[mt_rand(0, 2)]);
                $product->addSubcategory($subCategorieChaussette);
                $product->addTheme($themes[mt_rand(0, 2)]);
                $product->addComment($comment);
                $manager->persist($product);
            }

        $manager->flush();
    }
}
