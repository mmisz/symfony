<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200530135236 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE list_tag_to_do_list');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE list_tag_to_do_list (list_tag_id INT NOT NULL, to_do_list_id INT NOT NULL, INDEX IDX_962D6D35B3AB48EB (to_do_list_id), INDEX IDX_962D6D352302A13F (list_tag_id), PRIMARY KEY(list_tag_id, to_do_list_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE list_tag_to_do_list ADD CONSTRAINT FK_962D6D352302A13F FOREIGN KEY (list_tag_id) REFERENCES list_tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE list_tag_to_do_list ADD CONSTRAINT FK_962D6D35B3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_lists (id) ON DELETE CASCADE');
    }
}