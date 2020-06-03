<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200528152647 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE list_comments (id INT AUTO_INCREMENT NOT NULL, to_do_list_id INT NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_21F3A54AB3AB48EB (to_do_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE list_elements (id INT AUTO_INCREMENT NOT NULL, to_do_list_id INT NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_3A27343DB3AB48EB (to_do_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE to_do_lists (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, creation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE list_comments ADD CONSTRAINT FK_21F3A54AB3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_lists (id)');
        $this->addSql('ALTER TABLE list_elements ADD CONSTRAINT FK_3A27343DB3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_lists (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE list_comments DROP FOREIGN KEY FK_21F3A54AB3AB48EB');
        $this->addSql('ALTER TABLE list_elements DROP FOREIGN KEY FK_3A27343DB3AB48EB');
        $this->addSql('DROP TABLE list_comments');
        $this->addSql('DROP TABLE list_elements');
        $this->addSql('DROP TABLE to_do_lists');
    }
}
