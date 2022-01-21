<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220121164350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE short_url ALTER title DROP NOT NULL');
        $this->addSql('ALTER TABLE short_url ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE short_url ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE short_url ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE short_url ALTER updated_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN short_url.created_at IS NULL');
        $this->addSql('COMMENT ON COLUMN short_url.updated_at IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE short_url ALTER title SET NOT NULL');
        $this->addSql('ALTER TABLE short_url ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE short_url ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE short_url ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE short_url ALTER updated_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN short_url.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN short_url.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }
}
