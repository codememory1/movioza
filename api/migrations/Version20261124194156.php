<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261124194156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating the schema for the "downloads" table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE media_processing_download_id_seq AS BIGINT INCREMENT BY 1 MINVALUE 1 START WITH 1');

        $this->addSql('CREATE TABLE downloads (download_source_id BIGINT NOT NULL, destination_directory VARCHAR(255) NOT NULL, status VARCHAR(32) NOT NULL, downloader VARCHAR(32) DEFAULT NULL, external_download_id VARCHAR(255) DEFAULT NULL, error TEXT DEFAULT NULL, lease_token VARCHAR(32) DEFAULT NULL, lease_expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, paused_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, completed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, failed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, canceled_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, id BIGINT NOT NULL, PRIMARY KEY (id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE downloads');

        $this->addSql('DROP SEQUENCE media_processing_download_id_seq');
    }
}
