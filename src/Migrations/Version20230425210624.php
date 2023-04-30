<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425210624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add users and locations tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (
            id INT AUTO_INCREMENT NOT NULL,
            username VARCHAR(255),
            chat_id INT,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE locations (
            id INT AUTO_INCREMENT NOT NULL,
            town VARCHAR(255),
            longitude FLOAT,
            latitude FLOAT,
            PRIMARY KEY(id)
        )');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE locations');
    }
}