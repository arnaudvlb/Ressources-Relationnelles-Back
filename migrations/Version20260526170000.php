<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260526170000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the base schema for the current application model.';
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE adorer (id INT AUTO_INCREMENT NOT NULL, date_adorer DATETIME NOT NULL, utilisateur_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, INDEX IDX_8C697848FB88E14F (utilisateur_id), INDEX IDX_8C69784889329D25 (resource_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE AMIS (id_ami INT AUTO_INCREMENT NOT NULL, statut VARCHAR(20) NOT NULL, date_action DATETIME NOT NULL, id_utilisateur INT NOT NULL, id_utilisateur_2 INT NOT NULL, INDEX IDX_A9770AD550EAE44 (id_utilisateur), INDEX IDX_A9770AD511560304 (id_utilisateur_2), PRIMARY KEY (id_ami)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, utilisateur_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, commentaire_parent_id INT DEFAULT NULL, INDEX IDX_D9BEC0C4FB88E14F (utilisateur_id), INDEX IDX_D9BEC0C489329D25 (resource_id), INDEX IDX_D9BEC0C4FDED4547 (commentaire_parent_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE consultations (id INT AUTO_INCREMENT NOT NULL, date_consultation DATETIME NOT NULL, utilisateur_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, INDEX IDX_242D8F53FB88E14F (utilisateur_id), INDEX IDX_242D8F5389329D25 (resource_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, INDEX IDX_8933C432FB88E14F (utilisateur_id), INDEX IDX_8933C43289329D25 (resource_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE medias (id INT AUTO_INCREMENT NOT NULL, chemin_fichier VARCHAR(255) NOT NULL, nom_fichier VARCHAR(255) NOT NULL, date_upload VARCHAR(255) NOT NULL, taille INT NOT NULL, resource_id INT DEFAULT NULL, INDEX IDX_12D2AF8189329D25 (resource_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE MESSAGES (id_message INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, piece_jointe VARCHAR(255) DEFAULT NULL, date_envoie DATETIME NOT NULL, id_Utilisateurs_1 INT NOT NULL, id_Utilisateurs_2 INT NOT NULL, INDEX IDX_1D3182DAA8C0AF1B (id_Utilisateurs_1), INDEX IDX_1D3182DA31C9FEA1 (id_Utilisateurs_2), PRIMARY KEY (id_message)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE NOTIFICATIONS (id_notification INT AUTO_INCREMENT NOT NULL, type VARCHAR(100) NOT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, lue TINYINT NOT NULL, date_creation DATETIME NOT NULL, id_utilisateur INT NOT NULL, INDEX IDX_464CB23650EAE44 (id_utilisateur), PRIMARY KEY (id_notification)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE partages (id INT AUTO_INCREMENT NOT NULL, date_partage DATETIME NOT NULL, utilisateur_id INT DEFAULT NULL, utilisateur2_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, INDEX IDX_B18F11CAFB88E14F (utilisateur_id), INDEX IDX_B18F11CA2241569D (utilisateur2_id), INDEX IDX_B18F11CA89329D25 (resource_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE refresh_token (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, username VARCHAR(255) NOT NULL, utilisateur_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_C74F2195C74F2195 (refresh_token), INDEX IDX_C74F2195FB88E14F (utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE renitialisation_mdp (id INT AUTO_INCREMENT NOT NULL, token_reset VARCHAR(255) NOT NULL, date_demande DATETIME NOT NULL, date_expiration DATETIME NOT NULL, date_utilisation DATETIME DEFAULT NULL, utilisateur_id INT DEFAULT NULL, INDEX IDX_45B07F0CFB88E14F (utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE ressources (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, valide TINYINT NOT NULL, date_creation DATETIME NOT NULL, date_modification DATETIME DEFAULT NULL, est_visible TINYINT NOT NULL, visibilite VARCHAR(10) NOT NULL, utilisateur_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_6A2CD5C7FB88E14F (utilisateur_id), INDEX IDX_6A2CD5C7BCF5E72D (categorie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE roles_utilisateurs (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tags_ressources (id INT AUTO_INCREMENT NOT NULL, resource_id INT DEFAULT NULL, tag_id INT DEFAULT NULL, INDEX IDX_895C92689329D25 (resource_id), INDEX IDX_895C926BAD26311 (tag_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, photo_profil VARCHAR(255) DEFAULT NULL, status_compte TINYINT NOT NULL, date_creation DATETIME NOT NULL, role_id INT NOT NULL, INDEX IDX_497B315ED60322AC (role_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE adorer ADD CONSTRAINT FK_8C697848FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE adorer ADD CONSTRAINT FK_8C69784889329D25 FOREIGN KEY (resource_id) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE AMIS ADD CONSTRAINT FK_A9770AD550EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE AMIS ADD CONSTRAINT FK_A9770AD511560304 FOREIGN KEY (id_utilisateur_2) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C489329D25 FOREIGN KEY (resource_id) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4FDED4547 FOREIGN KEY (commentaire_parent_id) REFERENCES commentaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE consultations ADD CONSTRAINT FK_242D8F53FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE consultations ADD CONSTRAINT FK_242D8F5389329D25 FOREIGN KEY (resource_id) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43289329D25 FOREIGN KEY (resource_id) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE medias ADD CONSTRAINT FK_12D2AF8189329D25 FOREIGN KEY (resource_id) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE MESSAGES ADD CONSTRAINT FK_1D3182DAA8C0AF1B FOREIGN KEY (id_Utilisateurs_1) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE MESSAGES ADD CONSTRAINT FK_1D3182DA31C9FEA1 FOREIGN KEY (id_Utilisateurs_2) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE NOTIFICATIONS ADD CONSTRAINT FK_464CB23650EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE partages ADD CONSTRAINT FK_B18F11CAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE partages ADD CONSTRAINT FK_B18F11CA2241569D FOREIGN KEY (utilisateur2_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE partages ADD CONSTRAINT FK_B18F11CA89329D25 FOREIGN KEY (resource_id) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F2195FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE renitialisation_mdp ADD CONSTRAINT FK_45B07F0CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE tags_ressources ADD CONSTRAINT FK_895C92689329D25 FOREIGN KEY (resource_id) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE tags_ressources ADD CONSTRAINT FK_895C926BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id)');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315ED60322AC FOREIGN KEY (role_id) REFERENCES roles_utilisateurs (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('SET FOREIGN_KEY_CHECKS=0');
        $this->addSql('DROP TABLE IF EXISTS adorer');
        $this->addSql('DROP TABLE IF EXISTS AMIS');
        $this->addSql('DROP TABLE IF EXISTS commentaires');
        $this->addSql('DROP TABLE IF EXISTS consultations');
        $this->addSql('DROP TABLE IF EXISTS favoris');
        $this->addSql('DROP TABLE IF EXISTS medias');
        $this->addSql('DROP TABLE IF EXISTS MESSAGES');
        $this->addSql('DROP TABLE IF EXISTS NOTIFICATIONS');
        $this->addSql('DROP TABLE IF EXISTS partages');
        $this->addSql('DROP TABLE IF EXISTS refresh_token');
        $this->addSql('DROP TABLE IF EXISTS renitialisation_mdp');
        $this->addSql('DROP TABLE IF EXISTS tags_ressources');
        $this->addSql('DROP TABLE IF EXISTS ressources');
        $this->addSql('DROP TABLE IF EXISTS tags');
        $this->addSql('DROP TABLE IF EXISTS categories');
        $this->addSql('DROP TABLE IF EXISTS utilisateurs');
        $this->addSql('DROP TABLE IF EXISTS roles_utilisateurs');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1');
    }
}
