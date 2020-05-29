<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200528161826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE list_categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE to_do_lists ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE to_do_lists ADD CONSTRAINT FK_B2F8011012469DE2 FOREIGN KEY (category_id) REFERENCES list_categories (id)');
        $this->addSql('CREATE INDEX IDX_B2F8011012469DE2 ON to_do_lists (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE to_do_lists DROP FOREIGN KEY FK_B2F8011012469DE2');
        $this->addSql('DROP TABLE list_categories');
        $this->addSql('DROP INDEX IDX_B2F8011012469DE2 ON to_do_lists');
        $this->addSql('ALTER TABLE to_do_lists DROP category_id');
    }
}
