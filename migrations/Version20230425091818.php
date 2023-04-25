<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425091818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie CHANGE date_heure_debut date_heure_debut DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE infos_sortie infos_sortie LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie CHANGE date_heure_debut date_heure_debut DATE NOT NULL, CHANGE infos_sortie infos_sortie LONGTEXT DEFAULT NULL');
    }
}
