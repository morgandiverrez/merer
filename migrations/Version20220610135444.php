<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220610135444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE association (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, fede_filliere VARCHAR(255) DEFAULT NULL, fede_territoire VARCHAR(255) DEFAULT NULL, local VARCHAR(255) DEFAULT NULL, adresse_mail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE association_profil (association_id INT NOT NULL, profil_id INT NOT NULL, INDEX IDX_D5A287E0EFB9C8A5 (association_id), INDEX IDX_D5A287E0275ED078 (profil_id), PRIMARY KEY(association_id, profil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badge (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, date_creation DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badge_profil (badge_id INT NOT NULL, profil_id INT NOT NULL, INDEX IDX_D46A11D2F7A2C2FC (badge_id), INDEX IDX_D46A11D2275ED078 (profil_id), PRIMARY KEY(badge_id, profil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe_elue (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, categorie VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, adresse_mail VARCHAR(255) DEFAULT NULL, etablissement VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe_elue_profil (equipe_elue_id INT NOT NULL, profil_id INT NOT NULL, INDEX IDX_EBF93C97471E1B0D (equipe_elue_id), INDEX IDX_EBF93C97275ED078 (profil_id), PRIMARY KEY(equipe_elue_id, profil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, pre_requis VARCHAR(255) DEFAULT NULL, duration TIME DEFAULT NULL, public_cible VARCHAR(255) DEFAULT NULL, opg VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE identification (id INT AUTO_INCREMENT NOT NULL, profil_id INT DEFAULT NULL, identifiant VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_49E7720D275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieux (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, salle VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postale VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieux_seance (lieux_id INT NOT NULL, seance_id INT NOT NULL, INDEX IDX_34794923A2C806AC (lieux_id), INDEX IDX_34794923E3797A94 (seance_id), PRIMARY KEY(lieux_id, seance_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, pronom VARCHAR(255) DEFAULT NULL, date_of_birth DATE DEFAULT NULL, score INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_seance (profil_id INT NOT NULL, seance_id INT NOT NULL, INDEX IDX_AE82745C275ED078 (profil_id), INDEX IDX_AE82745CE3797A94 (seance_id), PRIMARY KEY(profil_id, seance_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retour (id INT AUTO_INCREMENT NOT NULL, seance_id INT DEFAULT NULL, profil_id INT DEFAULT NULL, note_contenu INT DEFAULT NULL, remarque_contenu VARCHAR(255) DEFAULT NULL, note_animation INT DEFAULT NULL, remarque_animation VARCHAR(255) DEFAULT NULL, note_implication VARCHAR(255) DEFAULT NULL, remarque_implication VARCHAR(255) DEFAULT NULL, note_reponse_atente INT DEFAULT NULL, remarque_reponse_attente VARCHAR(255) DEFAULT NULL, note_niv_competence INT DEFAULT NULL, remarque_niv_competence VARCHAR(255) DEFAULT NULL, note_utilite INT DEFAULT NULL, remarque_utilite VARCHAR(255) DEFAULT NULL, note_generale INT DEFAULT NULL, remarque_generale VARCHAR(255) DEFAULT NULL, apport_generale VARCHAR(255) DEFAULT NULL, plus_aimer VARCHAR(255) DEFAULT NULL, moins_aimer VARCHAR(255) DEFAULT NULL, aimer_voir VARCHAR(255) DEFAULT NULL, mot_fin VARCHAR(255) DEFAULT NULL, INDEX IDX_ED6FD321E3797A94 (seance_id), INDEX IDX_ED6FD321275ED078 (profil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_permission (role_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_6F7DF886D60322AC (role_id), INDEX IDX_6F7DF886FED90CCA (permission_id), PRIMARY KEY(role_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_identification (date_peremption DATE DEFAULT NULL, identification_ID INT NOT NULL, role_ID INT NOT NULL, INDEX IDX_206E413BE3143EEF (identification_ID), INDEX IDX_206E413B78E926C6 (role_ID), PRIMARY KEY(identification_ID, role_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seance (id INT AUTO_INCREMENT NOT NULL, formation_id INT DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, datetime DATETIME DEFAULT NULL, nombreplace INT DEFAULT NULL, INDEX IDX_DF7DFD0E5200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seance_profil (horrodateur DATETIME DEFAULT NULL, profil_ID INT NOT NULL, seance_ID INT NOT NULL, INDEX IDX_99A3A93F89B4D412 (profil_ID), INDEX IDX_99A3A93F4D937EFE (seance_ID), PRIMARY KEY(profil_ID, seance_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE association_profil ADD CONSTRAINT FK_D5A287E0EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE association_profil ADD CONSTRAINT FK_D5A287E0275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE badge_profil ADD CONSTRAINT FK_D46A11D2F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE badge_profil ADD CONSTRAINT FK_D46A11D2275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipe_elue_profil ADD CONSTRAINT FK_EBF93C97471E1B0D FOREIGN KEY (equipe_elue_id) REFERENCES equipe_elue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipe_elue_profil ADD CONSTRAINT FK_EBF93C97275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE identification ADD CONSTRAINT FK_49E7720D275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE lieux_seance ADD CONSTRAINT FK_34794923A2C806AC FOREIGN KEY (lieux_id) REFERENCES lieux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lieux_seance ADD CONSTRAINT FK_34794923E3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_seance ADD CONSTRAINT FK_AE82745C275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_seance ADD CONSTRAINT FK_AE82745CE3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE retour ADD CONSTRAINT FK_ED6FD321E3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id)');
        $this->addSql('ALTER TABLE retour ADD CONSTRAINT FK_ED6FD321275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_identification ADD CONSTRAINT FK_206E413BE3143EEF FOREIGN KEY (identification_ID) REFERENCES identification (id)');
        $this->addSql('ALTER TABLE role_identification ADD CONSTRAINT FK_206E413B78E926C6 FOREIGN KEY (role_ID) REFERENCES role (id)');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E5200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE seance_profil ADD CONSTRAINT FK_99A3A93F89B4D412 FOREIGN KEY (profil_ID) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE seance_profil ADD CONSTRAINT FK_99A3A93F4D937EFE FOREIGN KEY (seance_ID) REFERENCES seance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association_profil DROP FOREIGN KEY FK_D5A287E0EFB9C8A5');
        $this->addSql('ALTER TABLE badge_profil DROP FOREIGN KEY FK_D46A11D2F7A2C2FC');
        $this->addSql('ALTER TABLE equipe_elue_profil DROP FOREIGN KEY FK_EBF93C97471E1B0D');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E5200282E');
        $this->addSql('ALTER TABLE role_identification DROP FOREIGN KEY FK_206E413BE3143EEF');
        $this->addSql('ALTER TABLE lieux_seance DROP FOREIGN KEY FK_34794923A2C806AC');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886FED90CCA');
        $this->addSql('ALTER TABLE association_profil DROP FOREIGN KEY FK_D5A287E0275ED078');
        $this->addSql('ALTER TABLE badge_profil DROP FOREIGN KEY FK_D46A11D2275ED078');
        $this->addSql('ALTER TABLE equipe_elue_profil DROP FOREIGN KEY FK_EBF93C97275ED078');
        $this->addSql('ALTER TABLE identification DROP FOREIGN KEY FK_49E7720D275ED078');
        $this->addSql('ALTER TABLE profil_seance DROP FOREIGN KEY FK_AE82745C275ED078');
        $this->addSql('ALTER TABLE retour DROP FOREIGN KEY FK_ED6FD321275ED078');
        $this->addSql('ALTER TABLE seance_profil DROP FOREIGN KEY FK_99A3A93F89B4D412');
        $this->addSql('ALTER TABLE role_permission DROP FOREIGN KEY FK_6F7DF886D60322AC');
        $this->addSql('ALTER TABLE role_identification DROP FOREIGN KEY FK_206E413B78E926C6');
        $this->addSql('ALTER TABLE lieux_seance DROP FOREIGN KEY FK_34794923E3797A94');
        $this->addSql('ALTER TABLE profil_seance DROP FOREIGN KEY FK_AE82745CE3797A94');
        $this->addSql('ALTER TABLE retour DROP FOREIGN KEY FK_ED6FD321E3797A94');
        $this->addSql('ALTER TABLE seance_profil DROP FOREIGN KEY FK_99A3A93F4D937EFE');
        $this->addSql('DROP TABLE association');
        $this->addSql('DROP TABLE association_profil');
        $this->addSql('DROP TABLE badge');
        $this->addSql('DROP TABLE badge_profil');
        $this->addSql('DROP TABLE equipe_elue');
        $this->addSql('DROP TABLE equipe_elue_profil');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE identification');
        $this->addSql('DROP TABLE lieux');
        $this->addSql('DROP TABLE lieux_seance');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE profil_seance');
        $this->addSql('DROP TABLE retour');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_permission');
        $this->addSql('DROP TABLE role_identification');
        $this->addSql('DROP TABLE seance');
        $this->addSql('DROP TABLE seance_profil');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
