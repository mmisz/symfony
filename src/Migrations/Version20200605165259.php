<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200605165259 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE notes (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(150) NOT NULL, content LONGTEXT NOT NULL, creation DATETIME NOT NULL, last_update DATETIME DEFAULT NULL, INDEX IDX_11BA68C12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_note_tag (note_id INT NOT NULL, note_tag_id INT NOT NULL, INDEX IDX_63214BFD26ED0855 (note_id), INDEX IDX_63214BFDA20034C5 (note_tag_id), PRIMARY KEY(note_id, note_tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C12469DE2 FOREIGN KEY (category_id) REFERENCES note_categories (id)');
        $this->addSql('ALTER TABLE note_note_tag ADD CONSTRAINT FK_63214BFD26ED0855 FOREIGN KEY (note_id) REFERENCES notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_note_tag ADD CONSTRAINT FK_63214BFDA20034C5 FOREIGN KEY (note_tag_id) REFERENCES note_tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE list_tags CHANGE name name VARCHAR(64) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note_note_tag DROP FOREIGN KEY FK_63214BFD26ED0855');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C12469DE2');
        $this->addSql('ALTER TABLE note_note_tag DROP FOREIGN KEY FK_63214BFDA20034C5');
        $this->addSql('DROP TABLE notes');
        $this->addSql('DROP TABLE note_note_tag');
        $this->addSql('DROP TABLE note_categories');
        $this->addSql('DROP TABLE note_tags');
        $this->addSql('ALTER TABLE list_tags CHANGE name name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
