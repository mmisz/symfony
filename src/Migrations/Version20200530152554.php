<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200530152554 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE to_do_list_list_tag (to_do_list_id INT NOT NULL, list_tag_id INT NOT NULL, INDEX IDX_3FC5A086B3AB48EB (to_do_list_id), INDEX IDX_3FC5A0862302A13F (list_tag_id), PRIMARY KEY(to_do_list_id, list_tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE to_do_list_list_tag ADD CONSTRAINT FK_3FC5A086B3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_lists (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE to_do_list_list_tag ADD CONSTRAINT FK_3FC5A0862302A13F FOREIGN KEY (list_tag_id) REFERENCES list_tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE to_do_list_list_tag');
    }
}
