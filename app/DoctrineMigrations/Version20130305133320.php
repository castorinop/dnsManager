<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130305133320 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE ServerView DROP FOREIGN KEY FK_623014C0B26CDFEC");
        $this->addSql("DROP INDEX IDX_623014C0B26CDFEC ON ServerView");
        $this->addSql("ALTER TABLE ServerView CHANGE servers_id server_id VARCHAR(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE ServerView ADD CONSTRAINT FK_623014C01844E6B7 FOREIGN KEY (server_id) REFERENCES Server (id)");
        $this->addSql("CREATE INDEX IDX_623014C01844E6B7 ON ServerView (server_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE ServerView DROP FOREIGN KEY FK_623014C01844E6B7");
        $this->addSql("DROP INDEX IDX_623014C01844E6B7 ON ServerView");
        $this->addSql("ALTER TABLE ServerView CHANGE server_id servers_id VARCHAR(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE ServerView ADD CONSTRAINT FK_623014C0B26CDFEC FOREIGN KEY (servers_id) REFERENCES Server (id)");
        $this->addSql("CREATE INDEX IDX_623014C0B26CDFEC ON ServerView (servers_id)");
    }
}
