<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260627060640 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inbox_messages (idempotent_key VARCHAR(255) NOT NULL, status VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F635C51D4ADF0BEE ON inbox_messages (idempotent_key)');
        $this->addSql('CREATE TABLE outbox_messages (message_class VARCHAR(255) NOT NULL, payload JSON NOT NULL, status VARCHAR(32) NOT NULL, attempts INT DEFAULT 0 NOT NULL, last_error TEXT DEFAULT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sent_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, failed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, id UUID NOT NULL, PRIMARY KEY (id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE inbox_messages');
        $this->addSql('DROP TABLE outbox_messages');
    }
}
