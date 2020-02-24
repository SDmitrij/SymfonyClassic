<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191026103157 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Listen insertions to book table...';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS book_search
            (id INT AUTO_INCREMENT PRIMARY KEY, search_content TEXT NOT NULL, book_id INT NOT NULL) ENGINE=INNODB;");

        $this->addSql(
            "CREATE TRIGGER content_to_search 
                    AFTER INSERT ON book
                        FOR EACH ROW
                        BEGIN
                            INSERT INTO book_search (search_content, book_id) 
                            VALUES (CONCAT(NEW.title, ' ', NEW.content), new.id);
                        END");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DROP TABLE IF EXISTS book_search");
        $this->addSql("DROP TRIGGER IF EXISTS content_to_search");
    }
}
