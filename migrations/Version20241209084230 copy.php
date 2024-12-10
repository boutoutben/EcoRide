<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209084237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO mark(label) VALUES('Toyota'),('Volkswagen'),('Ford'),('Hando'),('Chevrolet'),('BMW'),('MERCEDES_Benz'),('Audi'),('Nissan'),('Peugeot'),('Renault'),('Ferrari'),('Ferrari'),('Porsche'),('Lamborghini'),('Tesla')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM mark");
    }
}
