<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260527120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Prevent duplicate friendship relations by enforcing a unique canonical pair in AMIS.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE AMIS ADD relation_min_id INT AS (LEAST(id_utilisateur, id_utilisateur_2)) STORED');
        $this->addSql('ALTER TABLE AMIS ADD relation_max_id INT AS (GREATEST(id_utilisateur, id_utilisateur_2)) STORED');
        $this->addSql('ALTER TABLE AMIS ADD UNIQUE INDEX uniq_amis_relation_pair (relation_min_id, relation_max_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE AMIS DROP INDEX uniq_amis_relation_pair');
        $this->addSql('ALTER TABLE AMIS DROP COLUMN relation_max_id');
        $this->addSql('ALTER TABLE AMIS DROP COLUMN relation_min_id');
    }
}
