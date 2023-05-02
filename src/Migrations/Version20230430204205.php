<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230430204205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add new location';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO locations (town, longitude, latitude) VALUES ('kirov', 49.6601, 58.5966)");

    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM locations WHERE town = 'kirov'");
    }
}
