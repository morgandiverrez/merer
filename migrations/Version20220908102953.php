<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220908102953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, profil_id INT DEFAULT NULL, name_demandeur VARCHAR(255) DEFAULT NULL, sur_name_demandeur VARCHAR(255) DEFAULT NULL, mail_demandeur VARCHAR(255) DEFAULT NULL, telephone_demandeur VARCHAR(255) DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, nombre_personne INT DEFAULT NULL, planning VARCHAR(255) DEFAULT NULL, double_maillage TINYINT(1) NOT NULL, INDEX IDX_2694D7A5275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_association (demande_id INT NOT NULL, association_id INT NOT NULL, INDEX IDX_5CA27EB880E95E18 (demande_id), INDEX IDX_5CA27EB8EFB9C8A5 (association_id), PRIMARY KEY(demande_id, association_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_equipe_elu (demande_id INT NOT NULL, equipe_elu_id INT NOT NULL, INDEX IDX_516BAE6C80E95E18 (demande_id), INDEX IDX_516BAE6C7DEF8F22 (equipe_elu_id), PRIMARY KEY(demande_id, equipe_elu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_formation (demande_id INT NOT NULL, formation_id INT NOT NULL, INDEX IDX_356CDB6A80E95E18 (demande_id), INDEX IDX_356CDB6A5200282E (formation_id), PRIMARY KEY(demande_id, formation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE demande_association ADD CONSTRAINT FK_5CA27EB880E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_association ADD CONSTRAINT FK_5CA27EB8EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_equipe_elu ADD CONSTRAINT FK_516BAE6C80E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_equipe_elu ADD CONSTRAINT FK_516BAE6C7DEF8F22 FOREIGN KEY (equipe_elu_id) REFERENCES equipe_elu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_formation ADD CONSTRAINT FK_356CDB6A80E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_formation ADD CONSTRAINT FK_356CDB6A5200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_association DROP FOREIGN KEY FK_5CA27EB880E95E18');
        $this->addSql('ALTER TABLE demande_equipe_elu DROP FOREIGN KEY FK_516BAE6C80E95E18');
        $this->addSql('ALTER TABLE demande_formation DROP FOREIGN KEY FK_356CDB6A80E95E18');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE demande_association');
        $this->addSql('DROP TABLE demande_equipe_elu');
        $this->addSql('DROP TABLE demande_formation');
    }
}
