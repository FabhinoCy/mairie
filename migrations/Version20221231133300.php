<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221231133300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement CHANGE begin_at begin_at DATETIME NOT NULL, CHANGE end_at end_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement CHANGE begin_at begin_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE end_at end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
