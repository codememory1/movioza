<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261026155716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE catalog_genre_id_seq AS BIGINT INCREMENT BY 1 MINVALUE 1 START WITH 1');

        $this->addSql('CREATE TABLE genres (name VARCHAR(80) NOT NULL, slug VARCHAR(280) NOT NULL, emoji VARCHAR(32) NOT NULL, short_description VARCHAR(80) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, id BIGINT NOT NULL, PRIMARY KEY (id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE genres');

        $this->addSql('DROP SEQUENCE catalog_genre_id_seq');
    }
}
