<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230430180902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unique index column username to users table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX idx_username
ON users (username)');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users
  DROP INDEX idx_username');
    }
}
