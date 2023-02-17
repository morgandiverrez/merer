<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215235500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fund_type_fund_box ADD id INT AUTO_INCREMENT NOT NULL, ADD horrodateur DATETIME NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fund_type_fund_box MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON fund_type_fund_box');
        $this->addSql('ALTER TABLE fund_type_fund_box DROP id, DROP horrodateur');
        $this->addSql('ALTER TABLE fund_type_fund_box ADD PRIMARY KEY (fund_type_id, fund_box_id)');
    }
}
