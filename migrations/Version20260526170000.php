<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260526170000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Invert category/resource relation: move FK from categories.resource_id to ressources.categorie_id.';
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE ressources ADD categorie_id INT DEFAULT NULL");
        $this->addSql("UPDATE ressources r SET r.categorie_id = (SELECT c.id FROM categories c WHERE c.resource_id = r.id LIMIT 1)");
        $this->addSql("ALTER TABLE ressources ADD INDEX IDX_RESSOURCES_CATEGORIE_ID (categorie_id)");
        $this->addSql("ALTER TABLE ressources ADD CONSTRAINT FK_RESSOURCES_CATEGORIE_ID FOREIGN KEY (categorie_id) REFERENCES categories (id)");
        $this->addSql("ALTER TABLE ressources MODIFY categorie_id INT NOT NULL");

        $this->addSql("SET @fk := (SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'categories' AND COLUMN_NAME = 'resource_id' AND REFERENCED_TABLE_NAME IS NOT NULL LIMIT 1)");
        $this->addSql("SET @sql := IF(@fk IS NOT NULL, CONCAT('ALTER TABLE categories DROP FOREIGN KEY `', @fk, '`'), 'SELECT 1')");
        $this->addSql('PREPARE stmt FROM @sql');
        $this->addSql('EXECUTE stmt');
        $this->addSql('DEALLOCATE PREPARE stmt');
        $this->addSql('ALTER TABLE categories DROP COLUMN resource_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE categories ADD resource_id INT DEFAULT NULL');
        $this->addSql("UPDATE categories c SET c.resource_id = (SELECT r.id FROM ressources r WHERE r.categorie_id = c.id LIMIT 1)");
        $this->addSql('ALTER TABLE categories ADD INDEX IDX_CATEGORIES_RESOURCE_ID (resource_id)');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_CATEGORIES_RESOURCE_ID FOREIGN KEY (resource_id) REFERENCES ressources (id)');
        $this->addSql('ALTER TABLE categories MODIFY resource_id INT NOT NULL');

        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_RESSOURCES_CATEGORIE_ID');
        $this->addSql('ALTER TABLE ressources DROP INDEX IDX_RESSOURCES_CATEGORIE_ID');
        $this->addSql('ALTER TABLE ressources DROP COLUMN categorie_id');
    }
}
