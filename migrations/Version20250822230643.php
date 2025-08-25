<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250822230643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE currency_rates ADD COLUMN league VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__currency_rates AS SELECT id, currency, value, fetchedAt, icon, listingCount FROM currency_rates');
        $this->addSql('DROP TABLE currency_rates');
        $this->addSql('CREATE TABLE currency_rates (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, currency VARCHAR(255) NOT NULL, value DOUBLE PRECISION NOT NULL, fetchedAt DATETIME NOT NULL, icon VARCHAR(255) NOT NULL, listingCount INTEGER NOT NULL)');
        $this->addSql('INSERT INTO currency_rates (id, currency, value, fetchedAt, icon, listingCount) SELECT id, currency, value, fetchedAt, icon, listingCount FROM __temp__currency_rates');
        $this->addSql('DROP TABLE __temp__currency_rates');
    }
}
