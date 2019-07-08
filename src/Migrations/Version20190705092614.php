<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190705092614 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post ADD country_id INT DEFAULT NULL, ADD type VARCHAR(20) DEFAULT \'post\', CHANGE category_id category_id INT DEFAULT NULL, CHANGE show_home_page show_home_page TINYINT(1) DEFAULT \'0\', CHANGE featured_article featured_article TINYINT(1) DEFAULT \'0\', CHANGE published_at published_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF92F3E70 ON post (country_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF92F3E70');
        $this->addSql('DROP INDEX IDX_5A8A6C8DF92F3E70 ON post');
        $this->addSql('ALTER TABLE post DROP country_id, DROP type, CHANGE category_id category_id INT NOT NULL, CHANGE show_home_page show_home_page TINYINT(1) NOT NULL, CHANGE featured_article featured_article TINYINT(1) NOT NULL, CHANGE published_at published_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
