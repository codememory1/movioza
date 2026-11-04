<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261104153048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating schemas and ID sequences for the discussion context';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE discussion_comment_id_seq AS BIGINT INCREMENT BY 1 MINVALUE 1 START WITH 1');
        $this->addSql('CREATE SEQUENCE discussion_comment_vote_id_seq AS BIGINT INCREMENT BY 1 MINVALUE 1 START WITH 1');

        $this->addSql('CREATE TABLE comment_votes (comment_id BIGINT NOT NULL, guest_id VARCHAR(36) NOT NULL, type SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, id BIGINT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE comments (media_id BIGINT NOT NULL, guest_id VARCHAR(36) NOT NULL, parent_id BIGINT DEFAULT NULL, author_name VARCHAR(100) NOT NULL, comment VARCHAR(1024) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, id BIGINT NOT NULL, PRIMARY KEY (id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comment_votes');
        $this->addSql('DROP TABLE comments');

        $this->addSql('DROP SEQUENCE discussion_comment_id_seq');
        $this->addSql('DROP SEQUENCE discussion_comment_vote_id_seq');
    }
}
