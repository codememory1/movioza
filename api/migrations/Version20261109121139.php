<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261109121139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Collection schemas and their associated media have been added. A size parameter has been added for season and episode titles.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE catalog_collection_id_seq AS BIGINT INCREMENT BY 1 MINVALUE 1 START WITH 1');

        $this->addSql('CREATE TABLE collection_media (created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, media_id BIGINT NOT NULL, collection_id BIGINT NOT NULL, PRIMARY KEY (media_id, collection_id))');
        $this->addSql('CREATE INDEX IDX_2DD4195F514956FD ON collection_media (collection_id)');
        $this->addSql('CREATE TABLE collections (slug VARCHAR(280) NOT NULL, title VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, status VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, unpublished_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, archived_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, id BIGINT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('ALTER TABLE collection_media ADD CONSTRAINT FK_2DD4195F514956FD FOREIGN KEY (collection_id) REFERENCES collections (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episodes ALTER title TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE seasons ALTER title TYPE VARCHAR(100)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collection_media DROP CONSTRAINT FK_2DD4195F514956FD');
        $this->addSql('DROP TABLE collection_media');
        $this->addSql('DROP TABLE collections');
        $this->addSql('ALTER TABLE episodes ALTER title TYPE VARCHAR');
        $this->addSql('ALTER TABLE seasons ALTER title TYPE VARCHAR');

        $this->addSql('DROP SEQUENCE catalog_collection_id_seq');
    }
}
