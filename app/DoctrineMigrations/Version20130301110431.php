<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130301110431 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Record ADD domain_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE Record ADD CONSTRAINT FK_9C989AA7115F0EE5 FOREIGN KEY (domain_id) REFERENCES Record (id)");
        $this->addSql("CREATE INDEX IDX_9C989AA7115F0EE5 ON Record (domain_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Record DROP FOREIGN KEY FK_9C989AA7115F0EE5");
        $this->addSql("DROP INDEX IDX_9C989AA7115F0EE5 ON Record");
        $this->addSql("ALTER TABLE Record DROP domain_id");
    }
}
