<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250723233531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment (
          id BYTEA NOT NULL,
          cart_id BYTEA NOT NULL,
          status VARCHAR(20) DEFAULT NULL,
          created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          PRIMARY KEY(id, cart_id)
        )');
        $this->addSql('COMMENT ON COLUMN payment.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.cart_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE payment');
    }
}
