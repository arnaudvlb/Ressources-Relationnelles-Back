<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260130080811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE AMIS (id_ami INT AUTO_INCREMENT NOT NULL, statut VARCHAR(20) NOT NULL, date_action DATETIME NOT NULL, id_utilisateur INT NOT NULL, id_utilisateur_2 INT NOT NULL, INDEX IDX_A9770AD550EAE44 (id_utilisateur), INDEX IDX_A9770AD511560304 (id_utilisateur_2), PRIMARY KEY (id_ami)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE MESSAGES (id_message INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, piece_jointe VARCHAR(255) DEFAULT NULL, date_envoie DATETIME NOT NULL, id_Utilisateurs_1 INT NOT NULL, id_Utilisateurs_2 INT NOT NULL, INDEX IDX_1D3182DAA8C0AF1B (id_Utilisateurs_1), INDEX IDX_1D3182DA31C9FEA1 (id_Utilisateurs_2), PRIMARY KEY (id_message)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, INDEX IDX_8933C432FB88E14F (utilisateur_id), INDEX IDX_8933C43289329D25 (resource_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE AMIS ADD CONSTRAINT FK_A9770AD550EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE AMIS ADD CONSTRAINT FK_A9770AD511560304 FOREIGN KEY (id_utilisateur_2) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE MESSAGES ADD CONSTRAINT FK_1D3182DAA8C0AF1B FOREIGN KEY (id_Utilisateurs_1) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE MESSAGES ADD CONSTRAINT FK_1D3182DA31C9FEA1 FOREIGN KEY (id_Utilisateurs_2) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43289329D25 FOREIGN KEY (resource_id) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE favorie DROP FOREIGN KEY `FK_7DE7716389329D25`');
        $this->addSql('ALTER TABLE favorie DROP FOREIGN KEY `FK_7DE77163FB88E14F`');
        $this->addSql('DROP TABLE favorie');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorie (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, INDEX IDX_7DE77163FB88E14F (utilisateur_id), INDEX IDX_7DE7716389329D25 (resource_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favorie ADD CONSTRAINT `FK_7DE7716389329D25` FOREIGN KEY (resource_id) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE favorie ADD CONSTRAINT `FK_7DE77163FB88E14F` FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE AMIS DROP FOREIGN KEY FK_A9770AD550EAE44');
        $this->addSql('ALTER TABLE AMIS DROP FOREIGN KEY FK_A9770AD511560304');
        $this->addSql('ALTER TABLE MESSAGES DROP FOREIGN KEY FK_1D3182DAA8C0AF1B');
        $this->addSql('ALTER TABLE MESSAGES DROP FOREIGN KEY FK_1D3182DA31C9FEA1');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432FB88E14F');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43289329D25');
        $this->addSql('DROP TABLE AMIS');
        $this->addSql('DROP TABLE MESSAGES');
        $this->addSql('DROP TABLE favoris');
    }
}
