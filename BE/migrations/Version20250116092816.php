<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116092816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE guest (email VARCHAR(255) NOT NULL, billing_address VARCHAR(255) NOT NULL, shipping_address VARCHAR(255) NOT NULL, id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE cart ADD guest_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B79A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BA388B79A4AA658 ON cart (guest_id)');
        $this->addSql('ALTER TABLE cart_order ADD guest_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart_order ADD CONSTRAINT FK_AAC3FE909A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AAC3FE909A4AA658 ON cart_order (guest_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE guest');
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT FK_BA388B79A4AA658');
        $this->addSql('DROP INDEX IDX_BA388B79A4AA658');
        $this->addSql('ALTER TABLE cart DROP guest_id');
        $this->addSql('ALTER TABLE cart_order DROP CONSTRAINT FK_AAC3FE909A4AA658');
        $this->addSql('DROP INDEX IDX_AAC3FE909A4AA658');
        $this->addSql('ALTER TABLE cart_order DROP guest_id');
    }
}
