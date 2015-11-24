<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151124170417 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app (id INT AUTO_INCREMENT NOT NULL, guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, UNIQUE INDEX UNIQ_C96E70CF2B6FCFB2 (guid), UNIQUE INDEX UNIQ_C96E70CF989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, app_id INT DEFAULT NULL, authkey VARCHAR(20) NOT NULL, secret VARCHAR(20) NOT NULL, created DATETIME NOT NULL, UNIQUE INDEX UNIQ_5F37A13B6C53EF05 (authkey), INDEX IDX_5F37A13B7987212D (app_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13B7987212D FOREIGN KEY (app_id) REFERENCES app (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13B7987212D');
        $this->addSql('DROP TABLE app');
        $this->addSql('DROP TABLE token');
    }
}
