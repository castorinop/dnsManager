<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130227135624 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Record ADD zone_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE Record ADD CONSTRAINT FK_9C989AA79F2C3FAB FOREIGN KEY (zone_id) REFERENCES Zone (id)");
        $this->addSql("CREATE INDEX IDX_9C989AA79F2C3FAB ON Record (zone_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Record DROP FOREIGN KEY FK_9C989AA79F2C3FAB");
        $this->addSql("DROP INDEX IDX_9C989AA79F2C3FAB ON Record");
        $this->addSql("ALTER TABLE Record DROP zone_id");
    }
}
