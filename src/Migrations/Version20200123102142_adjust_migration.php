<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200123102142_adjust_migration extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE api_tokens DROP CONSTRAINT fk_2cad560edb38439e');
        $this->addSql('ALTER TABLE api_tokens ADD CONSTRAINT FK_2CAD560EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CAD560E5F37A13B ON api_tokens (token)');
        $this->addSql('CREATE INDEX apitokens_users_expired_at_idx ON api_tokens (user_id, expired_at)');
        $this->addSql('ALTER INDEX idx_2cad560edb38439e RENAME TO apitokens_users_idx');
        $this->addSql('CREATE INDEX users_email_status_type_idx ON users (email, status)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX users_email_status_type_idx');
        $this->addSql('ALTER TABLE api_tokens DROP CONSTRAINT FK_2CAD560EA76ED395');
        $this->addSql('DROP INDEX UNIQ_2CAD560E5F37A13B');
        $this->addSql('DROP INDEX apitokens_users_expired_at_idx');
        $this->addSql('ALTER TABLE api_tokens ADD CONSTRAINT fk_2cad560edb38439e FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX apitokens_users_idx RENAME TO idx_2cad560edb38439e');
    }
}
