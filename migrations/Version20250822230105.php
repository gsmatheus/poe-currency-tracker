<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250822230105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency_rates (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, currency VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, fetchedAt DATETIME NOT NULL, icon VARCHAR(255) NOT NULL, listingCount INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE items (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, directions VARCHAR(255) NOT NULL, stackSize INTEGER NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE currency_rates');
        $this->addSql('DROP TABLE items');
    }
}
