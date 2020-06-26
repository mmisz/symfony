<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200626093309 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE list_categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE list_comments (id INT AUTO_INCREMENT NOT NULL, to_do_list_id INT NOT NULL, content LONGTEXT NOT NULL, creation DATETIME NOT NULL, INDEX IDX_21F3A54AB3AB48EB (to_do_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE list_elements (id INT AUTO_INCREMENT NOT NULL, to_do_list_id INT NOT NULL, status_id INT NOT NULL, content VARCHAR(255) NOT NULL, creation DATETIME NOT NULL, done_date DATETIME DEFAULT NULL, INDEX IDX_3A27343DB3AB48EB (to_do_list_id), INDEX IDX_3A27343D6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE list_element_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE list_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE list_tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notes (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, author_id INT UNSIGNED NOT NULL, title VARCHAR(150) NOT NULL, content LONGTEXT NOT NULL, creation DATETIME NOT NULL, last_update DATETIME DEFAULT NULL, INDEX IDX_11BA68C12469DE2 (category_id), INDEX IDX_11BA68CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_note_tag (note_id INT NOT NULL, note_tag_id INT NOT NULL, INDEX IDX_63214BFD26ED0855 (note_id), INDEX IDX_63214BFDA20034C5 (note_tag_id), PRIMARY KEY(note_id, note_tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE to_do_lists (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, status_id INT NOT NULL, author_id INT UNSIGNED NOT NULL, title VARCHAR(255) NOT NULL, creation DATETIME NOT NULL, done_date DATETIME DEFAULT NULL, INDEX IDX_B2F8011012469DE2 (category_id), INDEX IDX_B2F801106BF700BD (status_id), INDEX IDX_B2F80110F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE to_do_list_list_tag (to_do_list_id INT NOT NULL, list_tag_id INT NOT NULL, INDEX IDX_3FC5A086B3AB48EB (to_do_list_id), INDEX IDX_3FC5A0862302A13F (list_tag_id), PRIMARY KEY(to_do_list_id, list_tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(200) NOT NULL, UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE list_comments ADD CONSTRAINT FK_21F3A54AB3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_lists (id)');
        $this->addSql('ALTER TABLE list_elements ADD CONSTRAINT FK_3A27343DB3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_lists (id)');
        $this->addSql('ALTER TABLE list_elements ADD CONSTRAINT FK_3A27343D6BF700BD FOREIGN KEY (status_id) REFERENCES list_element_status (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C12469DE2 FOREIGN KEY (category_id) REFERENCES note_categories (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68CF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE note_note_tag ADD CONSTRAINT FK_63214BFD26ED0855 FOREIGN KEY (note_id) REFERENCES notes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_note_tag ADD CONSTRAINT FK_63214BFDA20034C5 FOREIGN KEY (note_tag_id) REFERENCES note_tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE to_do_lists ADD CONSTRAINT FK_B2F8011012469DE2 FOREIGN KEY (category_id) REFERENCES list_categories (id)');
        $this->addSql('ALTER TABLE to_do_lists ADD CONSTRAINT FK_B2F801106BF700BD FOREIGN KEY (status_id) REFERENCES list_status (id)');
        $this->addSql('ALTER TABLE to_do_lists ADD CONSTRAINT FK_B2F80110F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE to_do_list_list_tag ADD CONSTRAINT FK_3FC5A086B3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_lists (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE to_do_list_list_tag ADD CONSTRAINT FK_3FC5A0862302A13F FOREIGN KEY (list_tag_id) REFERENCES list_tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE to_do_lists DROP FOREIGN KEY FK_B2F8011012469DE2');
        $this->addSql('ALTER TABLE list_elements DROP FOREIGN KEY FK_3A27343D6BF700BD');
        $this->addSql('ALTER TABLE to_do_lists DROP FOREIGN KEY FK_B2F801106BF700BD');
        $this->addSql('ALTER TABLE to_do_list_list_tag DROP FOREIGN KEY FK_3FC5A0862302A13F');
        $this->addSql('ALTER TABLE note_note_tag DROP FOREIGN KEY FK_63214BFD26ED0855');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C12469DE2');
        $this->addSql('ALTER TABLE note_note_tag DROP FOREIGN KEY FK_63214BFDA20034C5');
        $this->addSql('ALTER TABLE list_comments DROP FOREIGN KEY FK_21F3A54AB3AB48EB');
        $this->addSql('ALTER TABLE list_elements DROP FOREIGN KEY FK_3A27343DB3AB48EB');
        $this->addSql('ALTER TABLE to_do_list_list_tag DROP FOREIGN KEY FK_3FC5A086B3AB48EB');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CF675F31B');
        $this->addSql('ALTER TABLE to_do_lists DROP FOREIGN KEY FK_B2F80110F675F31B');
        $this->addSql('DROP TABLE list_categories');
        $this->addSql('DROP TABLE list_comments');
        $this->addSql('DROP TABLE list_elements');
        $this->addSql('DROP TABLE list_element_status');
        $this->addSql('DROP TABLE list_status');
        $this->addSql('DROP TABLE list_tags');
        $this->addSql('DROP TABLE notes');
        $this->addSql('DROP TABLE note_note_tag');
        $this->addSql('DROP TABLE note_categories');
        $this->addSql('DROP TABLE note_tags');
        $this->addSql('DROP TABLE to_do_lists');
        $this->addSql('DROP TABLE to_do_list_list_tag');
        $this->addSql('DROP TABLE users');
    }
}
