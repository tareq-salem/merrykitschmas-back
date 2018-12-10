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

        //Tableaux de données
        $commentContent = array(
            "Un produit top qualité !.",
            "jrecomand!",
            "idée pas top. mon mari a fai la gueule",
            "produit bien emballé et conforme à la photo.",
            "délai de livraison beaucoup trop long car mon colis a été perdu",
            "J'adore ! c'est trop mignon :) !!",
            "Vous auriez pas le même en rose ?",
            "Comparé à H&M, ils sont plus beaux ! Les meilleurs du marchés !",
            "Je recommande ce produit. On se sent tellement bien avec",
            "C'est ultra kitsch mais ça me va tellement bien !",
            "se nettoie super bien à 30°",
            "Acheté pour mon cousin, il est dég !",
            "Parfait pour les collègues qui cassent les couilles au bureau"
        );

        $users = $manager->getRepository(User::class)->findAll();

        //CREATION DE PRODUITS
        $sizes = ['S', 'M', 'L', 'XL', 'XXL', 'Unique', '36 - 39', '40 - 43', '44 - 46', "3M", "6M", "1A", "2A", "4A", "6A", "8A"];
        $prices = [9.99, 19.99, 29.99, 39.99, 49.99, 59.99];
        $categories = $manager->getRepository(Category::class)->findAll();
        $subCategoriePull = $manager->getRepository(Subcategory::class)->findOneByName("Pull");
        $subCategorieBonnet = $manager->getRepository(Subcategory::class)->findOneByName("Bonnet");
        $subCategorieChaussette = $manager->getRepository(Subcategory::class)->findOneByName("Chaussette");
        $themes = $manager->getRepository(Theme::class)->findAll();

            //CREATION DE PULLS
        $pullHommeDescription = array(
            "Soyez le plus sexy à Noël !",
            "Il vous manque plus qu'un bonnet et vous serez parfait !",
            "Qui a dit 'pull kitsch' ? Avec ça, vous allez faire des envieux !",
            "Quand tu as perdu tout estime de soi, il te reste ce pull kitsch !",
            "Avant il y avait Axe... Maintenant la nouvelle norme, c'est le Pull Kitsch !"
        );

        $pullHommeStarwarsName = array(
            "Tie Fighter – Bataille de Yavin",
            "Dark Vador – Find your lack of",
            "Chewbacca de noël – Merry Fuzzball",
            "Embourbé – Chewbacca de noël",
            "Luke Vs Dark Vador",
        );
        $pullHommeStarwarsImage = array(
            "https://lepullmoche.shop/wp-content/uploads/2018/11/tie-fighter-bataille-de-yavin-pull-noel-star-wars-e1543179472588.jpg",
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-unisexe-17-e1539810916109.jpg",
            "https://lepullmoche.shop/wp-content/uploads/2018/12/chewbacca-de-noel-vintage-merry-christmas-fuzzball-star-wars-rouge.jpg",
            "https://lepullmoche.shop/wp-content/uploads/2018/12/unnamed-file-41-e1543698086952.jpg",
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-homme-152-e1539811597512.jpg",
        );

            // PULLS HOMME STARWARS
            for ($i = 0; $i < 5; $i++) {
                $dateTime = new \DateTime();
                $dateTime->add(new \DateInterval('P'.mt_rand(0, 10).'D'));
                $comment = new Comment();
                $comment->setVisible(1);
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName($pullHommeStarwarsName[$i]);
                $product->setPrice($prices[mt_rand(2, 5)]);
                $product->setDescription($pullHommeDescription[mt_rand(0,4)]);
                $product->setImage($pullHommeStarwarsImage[$i]);
                $product->setCreatedAt($dateTime);
                $product->setVisible(1);
                $product->addProductParameter(new ProductParameter($sizes[0], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[1], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[2], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[3], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[4], mt_rand(0, 10)));
                $product->addCategory($categories[0]);
                $product->addSubcategory($subCategoriePull);
                $product->addTheme($themes[0]);
                $product->addComment($comment);
                if($i > 2) {
                    $product->addOption($optionLE);
                }
                $manager->persist($product);
            }

        $pullHommeJeuvideoName = array(
            "Un noël atomique – « Happy holidays » – Pull de noël Fall out",
            "Le combat de Noël « Blanka Vs Bison » – Pull officiel Street Fighter – Rouge",
            "Mario Bros Vintage – « Merry Christmas » – Pull officiel Nintendo – Rouge",
            "Père « Link » Noël – Bandeau Zelda vintage – Vert",
            "Que la Triforce soit en toi – Pull officiel Zelda – Vert"
        );
        $pullHommeJeuvideoImage = array(
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pull-moche-noel-homme-387-e1543141934122.jpg",
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-unisexe-30.png",
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-homme-62.png",
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-homme-85-e1543599120138.png",
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-homme-66.png"
        );

            // PULLS HOMME JEUXVIDEO
            for ($i = 0; $i < 5; $i++) {
                $dateTime = new \DateTime();
                $dateTime->add(new \DateInterval('P'.mt_rand(0, 10).'D'));
                $comment = new Comment();
                $comment->setVisible(1);
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName($pullHommeJeuvideoName[$i]);
                $product->setPrice($prices[mt_rand(2, 5)]);
                $product->setDescription($pullHommeDescription[mt_rand(0,4)]);
                $product->setImage($pullHommeJeuvideoImage[$i]);
                $product->setCreatedAt($dateTime);
                $product->setVisible(1);
                $product->addProductParameter(new ProductParameter($sizes[0], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[1], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[2], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[3], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[4], mt_rand(0, 10)));
                $product->addCategory($categories[0]);
                $product->addSubcategory($subCategoriePull);
                $product->addTheme($themes[1]);
                $product->addComment($comment);
                if($i > 2) {
                    $product->addOption($optionLE);
                }
                $manager->persist($product);
            }

        $pullHommePompomName = array(
            "P’tite Crottes – Tête de Renne & Pompom – Pull de Noël Recto / Verso",
            "Petits Petons – Lutin avec Pieds 3D & Pompoms",
            "Dans son Pull – Pingouin & Bonnet",
            "#TeamRodolphe – Lunette de Soleil & Noël",
            "Au Fond des Chose – Père-Noël & Renne avec Pompom"
        );
        $pullHommePompomImage = array(
            "https://lepullmoche.shop/wp-content/uploads/2018/11/unnamed-file-11.jpg",
            "https://lepullmoche.shop/wp-content/uploads/2018/11/petits-petons-lutin-avec-pieds-3d-pompoms-pull-de-noel-homme-e1543598426590.jpg",
            "https://lepullmoche.shop/wp-content/uploads/2018/11/pull-noel-homme-gris-manchot-pull-moche-rouge1.jpg",
            "https://lepullmoche.shop/wp-content/uploads/2018/11/sweat-noel-moche-team-rudolph-vert-sapin1.jpg",
            "https://lepullmoche.shop/wp-content/uploads/2018/11/pull-moche-noel-pere-noel-renne-ski-gris-humour1.jpg"
        );

            // PULLS HOMME POMPOM
            for ($i = 0; $i < 5; $i++) {
                $dateTime = new \DateTime();
                $dateTime->add(new \DateInterval('P'.mt_rand(0, 10).'D'));
                $comment = new Comment();
                $comment->setVisible(1);
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName($pullHommePompomName[$i]);
                $product->setPrice($prices[mt_rand(2, 5)]);
                $product->setDescription($pullHommeDescription[mt_rand(0,4)]);
                $product->setImage($pullHommePompomImage[$i]);
                $product->setCreatedAt($dateTime);
                $product->setVisible(1);
                $product->addProductParameter(new ProductParameter($sizes[0], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[1], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[2], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[3], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[4], mt_rand(0, 10)));
                $product->addCategory($categories[0]);
                $product->addSubcategory($subCategoriePull);
                $product->addTheme($themes[2]);
                $product->addComment($comment);
                if($i > 2) {
                    $product->addOption($optionLE);
                }
                $manager->persist($product);
            }

        $pullFemmeDescription = array(
            "Y a pas à dire, tu seras MAGNIFIIIIKKKK !",
            "Offres-le à ta pire ennemi !",
            "Avec ça, c'est une soirée de Noël réussie !",
            "Toutes tes copines seront vertes de jalousie !",
            "Si tu fais pas tourner les têtes en portant ça, on change de métier !"
        );

        $pullFemmeStarwarsName = array(
            "Dark « Rodolphe le renne » Vador",
            "Kylo Ren (ne de noël) et Stormtroopers",
            "R2D2 est une femme"
        );
        $pullFemmeStarwarsImage = array(
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-unisexe-36-e1540143064911.png",
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-homme-154.png",
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-femme-97-e1540142974193.png"
        );

            // PULLS FEMME STARWARS
            for ($i = 0; $i < 3; $i++) {
                $dateTime = new \DateTime();
                $dateTime->add(new \DateInterval('P'.mt_rand(0, 10).'D'));
                $comment = new Comment();
                $comment->setVisible(1);
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName($pullFemmeStarwarsName[$i]);
                $product->setPrice($prices[mt_rand(2, 5)]);
                $product->setDescription($pullFemmeDescription[mt_rand(0,4)]);
                $product->setImage($pullFemmeStarwarsImage[$i]);
                $product->setCreatedAt($dateTime);
                $product->setVisible(1);
                $product->addProductParameter(new ProductParameter($sizes[0], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[1], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[2], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[3], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[4], mt_rand(0, 10)));
                $product->addCategory($categories[1]);
                $product->addSubcategory($subCategoriePull);
                $product->addTheme($themes[0]);
                $product->addComment($comment);
                if($i > 2) {
                    $product->addOption($optionLE);
                }
                $manager->persist($product);
            }

        $pullFemmeJeuvideoName = array(
            "Cammy vs. Guile – Pull officiel de Noël Street Fighter",
            "Chun-Li vs. Sagat – Pull officiel de Noël Street Fighter",
            "Vault « Girl » – Pull de Noël officiel Fallout",
            "△ ○ ✗ ☐ pour elle – Pull officiel Playstation",
        );
        $pullFemmeJeuvideoImage = array(
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-femme-100-e1540074531290.png",
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-femme-101-e1539811240826.jpg",
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-femme-99.png",
            "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-femme-39.jpg"
        );

            // PULLS FEMME JEUXVIDEO
            for ($i = 0; $i < 4; $i++) {
                $dateTime = new \DateTime();
                $dateTime->add(new \DateInterval('P'.mt_rand(0, 10).'D'));
                $comment = new Comment();
                $comment->setVisible(1);
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName($pullFemmeJeuvideoName[$i]);
                $product->setPrice($prices[mt_rand(2, 5)]);
                $product->setDescription($pullFemmeDescription[mt_rand(0,4)]);
                $product->setImage($pullFemmeJeuvideoImage[$i]);
                $product->setCreatedAt($dateTime);
                $product->setVisible(1);
                $product->addProductParameter(new ProductParameter($sizes[0], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[1], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[2], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[3], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[4], mt_rand(0, 10)));
                $product->addCategory($categories[1]);
                $product->addSubcategory($subCategoriePull);
                $product->addTheme($themes[1]);
                $product->addComment($comment);
                if($i > 2) {
                    $product->addOption($optionLE);
                }
                $manager->persist($product);
            }

            $pullFemmePompomName = array(
                "Comme Bridget Jones avec un Renne",
                "J’ai un colis pour toi – Lutin Kitsch Capuche & Pompoms",
                "Ours à Boule",
                "1001 Pompoms",
                "Sapin de noël en pompons"
            );
            $pullFemmePompomImage = array(
                "https://lepullmoche.shop/wp-content/uploads/2018/10/pull-noel-femme-rouge-tete-renne-pompom-anglais1-e1540135841634.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2017/11/pull-moche-noel-femme-205.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2017/11/pull-moche-noel-femme-304-e1539983708209.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/11/1001-pompoms-pull-vintage-noel-femme-rouge-e1543691709652.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2017/11/pull-moche-noel-femme-236-e1540106977824.jpg"
            );

            // PULLS FEMME POMPOM
            for ($i = 0; $i < 5; $i++) {
                $dateTime = new \DateTime();
                $dateTime->add(new \DateInterval('P'.mt_rand(0, 10).'D'));
                $comment = new Comment();
                $comment->setVisible(1);
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName($pullFemmePompomName[$i]);
                $product->setPrice($prices[mt_rand(2, 5)]);
                $product->setDescription($pullFemmeDescription[mt_rand(0,4)]);
                $product->setImage($pullFemmePompomImage[$i]);
                $product->setCreatedAt($dateTime);
                $product->setVisible(1);
                $product->addProductParameter(new ProductParameter($sizes[0], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[1], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[2], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[3], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[4], mt_rand(0, 10)));
                $product->addCategory($categories[1]);
                $product->addSubcategory($subCategoriePull);
                $product->addTheme($themes[2]);
                $product->addComment($comment);
                if($i > 2) {
                    $product->addOption($optionLE);
                }
                $manager->persist($product);
            }

            $pullEnfantDescription = array(
                "Oh mais c'est trop mignon !",
                "Si avec ça, on a pas envie de le croquer, c'est que c'est pas le pull qui est moche...",
                "Ça donne envie de faire des enfants. Des tas d'enfants !",
                "Prenez en deux ! Un enfant, ça se salit vite !",
                "Si vous avez pas d'enfants, vos frères et soeurs en ont non ?"
            );
            $pullEnfantName = array(
                "Comme les Vieux – Renne Vintage",
                "On My Way – Renne & Étoiles",
                "Princesse de Bois – Renne & Petit Nœud",
                "Petit Lutin – Habit de Noël",
                "Je suis un cadeau de noël (enfant) – Gros Nœud 3D – Kitsch",
                "T-Rennex – Dinosaure avec des bois de renne",
                "Adorable – Pull Noël Renne Enfant",
                "Pull combinaison Père-Noël bébé – Barboteuse"
            );
            $pullEnfantImage = array(
                "https://lepullmoche.shop/wp-content/uploads/2018/11/pull-noel-enfant-rennes-flocons-vintage-bleu-11.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/12/unnamed-file-67.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/12/unnamed-file-68.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/11/barboteuse-bebe-deguisement-noel-lutin-vert-bonnet1.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2017/11/pull-noel-moche-enfant-57.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2017/11/pull-noel-moche-enfant-58.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/10/pull-noel-enfant-rouge-tete-renne-pompom1.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2017/11/pullmoche-noel-enfant-34.png"
            );

            // PULLS ENFANT
            for ($i = 0; $i < 8; $i++) {
                $dateTime = new \DateTime();
                $dateTime->add(new \DateInterval('P'.mt_rand(0, 10).'D'));
                $comment = new Comment();
                $comment->setVisible(1);
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName($pullEnfantName[$i]);
                $product->setPrice($prices[mt_rand(0, 3)]);
                $product->setDescription($pullEnfantDescription[mt_rand(0,4)]);
                $product->setImage($pullEnfantImage[$i]);
                $product->setCreatedAt($dateTime);
                $product->setVisible(1);
                $product->addProductParameter(new ProductParameter($sizes[9], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[10], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[11], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[12], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[13], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[14], mt_rand(0, 10)));
                $product->addProductParameter(new ProductParameter($sizes[15], mt_rand(0, 10)));
                $product->addCategory($categories[2]);
                $product->addSubcategory($subCategoriePull);
                //$product->addTheme($themes[2]);
                $product->addComment($comment);
                if($i > 5) {
                    $product->addOption($optionLE);
                }
                $manager->persist($product);
            }

            $bonnetName = array(
                "Bonnet tricoté Sapin de noël vert pompon jaune",
                "Bonnet grosse maille Père noël – rouge et blanc",
                "Bonnet de Lutin de Noël avec Pompon et Oreilles de Lutin",
                "Bonnet Tricoté de Noël Oh Deer Tête de Renne à lunettes",
                "Bonnet Tricoté de Noël Fais moi un bisou sous le houx",
                "Bonnet Tricoté de Noël Rennes et Sapins lumineux",
                "Bonnet tricoté noël sapin de noël",
                "Bonnet tricoté Paysage de Noël Lumineux et coloré"
            );
            $bonnetImage = array(
                "https://lepullmoche.shop/wp-content/uploads/2018/11/bonnet-tricote-sapin-de-noel-vert-pompon-jaune-accessoires-de-noel-vert.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/11/bonnet-de-noel-bonnet-grosse-maille-pere-noel-rouge-et-blanc.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/11/bonnet-de-lutin-de-noel-avec-pompon-et-oreilles-de-lutin-accessoires-de-noel-rouge-et-vert-e1543742290174.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/11/bonnet-tricote-de-noel-oh-deer-tete-de-renne-a-lunettes-accessoires-de-noel-vert-fonce.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/11/bonnet-tricote-de-noel-fais-moi-un-bisou-sous-le-houx-accessoires-de-noel-noir-et-rouge.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/11/bonnet-tricote-de-noel-rennes-et-sapins-lumineux-accessoires-de-noel-bleu-marine-et-rouge.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/11/bonnet-tricote-noel-sapin-de-noel-accessoires-de-noel-rouge-et-blanc.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/11/bonnet-tricote-paysage-de-noel-lumineux-et-colore-accessoires-de-noel-bleu.jpg"
            );
            $bonnetDescription = array(
                "Tu rajoutes un grelot et tu pourras jouer Jingle Bells !",
                "Pour couvrir tes oreilles, y a pas mieux !",
                "A mettre seulement l'hiver ! L'été, les gens comprendront pas pourquoi tu le portes !",
                "Si j'étais toi, j'en offrirais à toute la famille ! Pour pas se sentir seul, tu vois ?",
                "C'est la classe à Dallas ! Au fait, il neige à Dallas ?"
            );

            //CREATION DE BONNETS
            for ($i = 0; $i < 8; $i++) {
                $dateTime = new \DateTime();
                $dateTime->add(new \DateInterval('P'.mt_rand(0, 10).'D'));
                $comment = new Comment();
                $comment->setVisible(1);
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName($bonnetName[$i]);
                $product->setPrice($prices[mt_rand(0, 1)]);
                $product->setDescription($bonnetDescription[mt_rand(0,4)]);
                $product->setImage($bonnetImage[$i]);
                $product->setCreatedAt($dateTime);
                $product->setVisible(1);
                $product->addProductParameter(new ProductParameter($sizes[5], mt_rand(0, 10)));
                $product->addCategory($categories[0]);
                $product->addCategory($categories[1]);
                $product->addSubcategory($subCategorieBonnet);
                //$product->addTheme($themes[mt_rand(0, 2)]);
                $product->addComment($comment);
                if($i > 4) {
                    $product->addOption($optionLE);
                }
                $manager->persist($product);
            }

            $chaussetteAdulteName = array(
                "Douceurs – Lot 6 Paires Chaussettes – Chaussette Noël Épaisses",
                "Fantastique Équipage – Lot 5 Paires Chaussettes – Chaussette Noël Coton",
                "La Norvégienne – Paires Chaussette avec Pompon",
                "Kitsch à Souhait – Lot 3 Paires Chaussettes",
                "Symboles Acidulés – Lot 3 Paires Chaussettes",
                "Tout Compris – Lot 6 Paires Chaussettes"
            );
            $chaussetteAdulteImage = array(
                "https://lepullmoche.shop/wp-content/uploads/2018/10/lot-6-paire-de-chaussettes-noel-douces-epaisses-vert-rouge-blanc1.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/10/box-de-5-paires-de-chaussettes-cadeau-noel1.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/10/paire-de-grosse-chaussettes-noel-rouge-style-norvegien-avec-pompom-et-polaire-rouge-blanc1.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/10/lot-de-3-paires-de-chaussettes-de-noel1.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/11/lot-3-paires-chaussettes-noel-bonnets-sapins-chaussettes-de-noel-multicolore-fond-noir1.jpg",
                "https://lepullmoche.shop/wp-content/uploads/2018/10/lot-6-paires-chaussettes-noel-separateurs-doigts-pieds-rouge-gris-vert-noir-bonhomme-neige1.jpg"
            );
            $chaussetteDescription = array(
                "Tes petits petons n'auront pas froid cet hiver !",
                "C'est quand même la classe non ?",
                "J'en connais qui seront bien au chaud avec ça :)",
                "Le cadeau idéal pour les collègues au bureau !",
                "Je serai vous, j'en prendrai 7 ! Une par jour !"
            );

            //CREATION DE CHAUSSETTES
            for ($i = 0; $i < 6; $i++) {
                $dateTime = new \DateTime();
                $dateTime->add(new \DateInterval('P'.mt_rand(0, 10).'D'));
                $comment = new Comment();
                $comment->setVisible(1);
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName($chaussetteAdulteName[$i]);
                $product->setPrice($prices[mt_rand(0, 1)]);
                $product->setDescription($chaussetteDescription[mt_rand(0, 4)]);
                $product->setImage($chaussetteAdulteImage[$i]);
                $product->setCreatedAt($dateTime);
                $product->setVisible(1);
                $product->addProductParameter(new ProductParameter($sizes[mt_rand(6, 8)], mt_rand(0, 10)));
                $product->addCategory($categories[1]);
                $product->addCategory($categories[0]);
                $product->addSubcategory($subCategorieChaussette);
                //$product->addTheme($themes[mt_rand(0, 2)]);
                $product->addComment($comment);
                $manager->persist($product);
            }

                //CREATION DE CHAUSSETTE ENFANT
                for ($i = 0; $i < 1; $i++) {
                $dateTime = new \DateTime();
                $dateTime->add(new \DateInterval('P'.mt_rand(0, 10).'D'));
                $comment = new Comment();
                $comment->setVisible(1);
                $comment->setCreatedAt($dateTime);
                $comment->setUser($users[mt_rand(0, 9)]);
                $comment->setContent($commentContent[mt_rand(0, 12)]);

                $product = new Product();
                $product->setName("Pilou Pidou – Lot 12 Paires Chaussettes");
                $product->setPrice($prices[0]);
                $product->setDescription($chaussetteDescription[mt_rand(0, 4)]);
                $product->setImage("https://lepullmoche.shop/wp-content/uploads/2018/10/chaussettes-de-noel-pilou-pidou-12paries-enfant1.jpg");
                $product->setCreatedAt($dateTime);
                $product->setVisible(1);
                $product->addProductParameter(new ProductParameter($sizes[mt_rand(9, 11)], mt_rand(0, 10)));
                $product->addCategory($categories[2]);
                $product->addSubcategory($subCategorieChaussette);
                //$product->addTheme($themes[mt_rand(0, 2)]);
                $product->addComment($comment);
                if($i > 3) {
                    $product->addOption($optionLE);
                }
                $manager->persist($product);
            }

        $manager->flush();
    }
}
