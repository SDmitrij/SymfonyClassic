<?php


namespace App\DataFixtures;


use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Library;
use App\Entity\LiteraryType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Faker\Generator;

class LibraryFixture extends Fixture
{
    public const NUM_OF_ENTITIES = 100;

    /**
     * @var Generator
     */
    private $faker;

    private $literaryTypes = [
        'Drama', 'Prose', 'Myth', 'Short story', 'Novel', 'Folk tale', 'Poetry',
        'Autobiography and Biography', 'Essay', 'Literary Criticism', 'Travel Literature',
        'Diary', 'Journal', 'Frame Narrative', 'Outdoor Literature'
    ];

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $types   = [];
        $books   = [];
        $authors = [];

        foreach ($this->literaryTypes as $type)
        {
            $typeObj = new LiteraryType($type);
            $types[] = $typeObj;

            $manager->persist($typeObj);
        }

        for($i = 0; $i < self::NUM_OF_ENTITIES; $i++)
        {
            $author = new Author($this->faker->firstName, $this->faker->lastName);
            $book   = new Book($author, $this->faker->text(50), $this->faker->text(700),
                $this->literaryTypes[random_int(0, count($this->literaryTypes) - 1)]);

            $author->addBook($book);

            $authors[] = $author;
            $books[]   = $book;

            $manager->persist($author);
            $manager->persist($book);
        }

        $libra = new Library(
            new ArrayCollection($authors),
            new ArrayCollection($books),
            new ArrayCollection($types),
            $this->faker->address
        );

        $manager->persist($libra);
        $manager->flush();
    }
}
