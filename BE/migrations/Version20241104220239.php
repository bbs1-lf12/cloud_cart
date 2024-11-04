<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104220239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B78D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B78D9F6D38 ON cart (order_id)');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f52993981ad5cdbf');
        $this->addSql('DROP INDEX uniq_f52993981ad5cdbf');
        $this->addSql('ALTER TABLE "order" DROP cart_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT FK_BA388B78D9F6D38');
        $this->addSql('DROP INDEX UNIQ_BA388B78D9F6D38');
        $this->addSql('ALTER TABLE cart DROP order_id');
        $this->addSql('ALTER TABLE "order" ADD cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f52993981ad5cdbf FOREIGN KEY (cart_id) REFERENCES cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_f52993981ad5cdbf ON "order" (cart_id)');
    }
}
