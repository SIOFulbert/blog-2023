<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Article;
use App\Entity\Utilisateur;
use App\Entity\Avis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $catList = ["PHP", "Symfony", "JS", "PWA"];
        $fakeTitre = [" est magique", " c'est super", " est un super langage"];
        
        $user = new Utilisateur();
        $user->setPseudo("admin");
        $user->setMdp("mdp");
        $user->setMail("admin@blog.fr");
        $manager->persist($user);
        $faker = Factory::create();
        foreach($catList as $categ ){
            $cat = new Categorie();
            $cat->setLibelle($categ);
            $cat->setSlug(strtolower($categ));
            $manager->persist($cat);
            for($i=0;$i<=2;$i++){
                $article = new Article();
                $rand=rand(0, count($fakeTitre)-1);
                $article->setTitre($cat->getLibelle().$fakeTitre[$rand]);
                $article->setContenu($faker->paragraph(10));
                $article->setCrea($faker->dateTime());
                $article->setSlug($this->slugify($cat->getLibelle().$fakeTitre[$rand]));
                $article->setUtilisateur($user);
                $article->setCategorie($cat);
                $manager->persist($article);
                for($j=0;$j<=rand(0,10);$j++){
                    $avis = new Avis();
                    $avis->setContenu($faker->text());
                    $avis->setArticle($article);
                    $avis->setNote($faker->randomDigit());
                    $avis->setAuteur($user);
                    $manager->persist($avis);
                }
            }
        }
    
        $manager->flush();
        // $product = new Product();
        // $manager->persist($product);

        
    }
    public function slugify($text, string $divider = '-')
{
  // replace non letter or digits by divider
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, $divider);

  // remove duplicate divider
  $text = preg_replace('~-+~', $divider, $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}
}
