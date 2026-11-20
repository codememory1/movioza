<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261120221505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Replace the comment_votes ID with a composite primary key on comment_id and guest_id.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_votes DROP CONSTRAINT comment_votes_pkey');
        $this->addSql('ALTER TABLE comment_votes DROP id');
        $this->addSql('ALTER TABLE comment_votes ADD PRIMARY KEY (comment_id, guest_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_votes DROP CONSTRAINT comment_votes_pkey');
        $this->addSql('ALTER TABLE comment_votes ADD id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE comment_votes ADD PRIMARY KEY (id)');
    }
}
