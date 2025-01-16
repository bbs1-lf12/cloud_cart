<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116100843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD guest_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D9A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6D28840D9A4AA658 ON payment (guest_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D9A4AA658');
        $this->addSql('DROP INDEX IDX_6D28840D9A4AA658');
        $this->addSql('ALTER TABLE payment DROP guest_id');
    }
}
