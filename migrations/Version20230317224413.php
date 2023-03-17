<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317224413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE code code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE impression ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE impression ADD CONSTRAINT FK_245BB1B171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_245BB1B171F7E88B ON impression (event_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event CHANGE code code CHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE impression DROP FOREIGN KEY FK_245BB1B171F7E88B');
        $this->addSql('DROP INDEX IDX_245BB1B171F7E88B ON impression');
        $this->addSql('ALTER TABLE impression DROP event_id');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
