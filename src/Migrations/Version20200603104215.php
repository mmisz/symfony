<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200603104215 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE list_element_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE list_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE list_elements ADD status_id INT NOT NULL, ADD creation DATETIME NOT NULL, ADD done_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE list_elements ADD CONSTRAINT FK_3A27343D6BF700BD FOREIGN KEY (status_id) REFERENCES list_element_status (id)');
        $this->addSql('CREATE INDEX IDX_3A27343D6BF700BD ON list_elements (status_id)');
        $this->addSql('ALTER TABLE to_do_lists ADD status_id INT NOT NULL, ADD done_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE to_do_lists ADD CONSTRAINT FK_B2F801106BF700BD FOREIGN KEY (status_id) REFERENCES list_status (id)');
        $this->addSql('CREATE INDEX IDX_B2F801106BF700BD ON to_do_lists (status_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE list_elements DROP FOREIGN KEY FK_3A27343D6BF700BD');
        $this->addSql('ALTER TABLE to_do_lists DROP FOREIGN KEY FK_B2F801106BF700BD');
        $this->addSql('DROP TABLE list_element_status');
        $this->addSql('DROP TABLE list_status');
        $this->addSql('DROP INDEX IDX_3A27343D6BF700BD ON list_elements');
        $this->addSql('ALTER TABLE list_elements DROP status_id, DROP date_done, DROP creation, DROP done_date');
        $this->addSql('DROP INDEX IDX_B2F801106BF700BD ON to_do_lists');
        $this->addSql('ALTER TABLE to_do_lists DROP status_id, DROP done_date');
    }
}
