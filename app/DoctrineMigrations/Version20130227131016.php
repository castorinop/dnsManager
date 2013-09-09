<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130227131016 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE Record (id INT AUTO_INCREMENT NOT NULL, hostname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE ServerView (id INT AUTO_INCREMENT NOT NULL, servers_id VARCHAR(255) DEFAULT NULL, view_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_623014C0B26CDFEC (servers_id), INDEX IDX_623014C031518C7 (view_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE View (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE Server (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE RecordView (id INT AUTO_INCREMENT NOT NULL, records_id INT DEFAULT NULL, view_id INT DEFAULT NULL, destination VARCHAR(255) NOT NULL, recordtype VARCHAR(255) NOT NULL, mx INT DEFAULT NULL, ttl INT DEFAULT NULL, INDEX IDX_3ED6C757EE8A0C7B (records_id), INDEX IDX_3ED6C75731518C7 (view_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE Zone (id INT AUTO_INCREMENT NOT NULL, alias_id INT DEFAULT NULL, domain VARCHAR(255) NOT NULL, ttl VARCHAR(255) NOT NULL, soa VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, serial VARCHAR(255) NOT NULL, refresh INT NOT NULL, retry INT NOT NULL, expire INT NOT NULL, defttl INT NOT NULL, INDEX IDX_D96F395E564AE2 (alias_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE ServerView ADD CONSTRAINT FK_623014C0B26CDFEC FOREIGN KEY (servers_id) REFERENCES Server (id)");
        $this->addSql("ALTER TABLE ServerView ADD CONSTRAINT FK_623014C031518C7 FOREIGN KEY (view_id) REFERENCES View (id)");
        $this->addSql("ALTER TABLE RecordView ADD CONSTRAINT FK_3ED6C757EE8A0C7B FOREIGN KEY (records_id) REFERENCES Record (id)");
        $this->addSql("ALTER TABLE RecordView ADD CONSTRAINT FK_3ED6C75731518C7 FOREIGN KEY (view_id) REFERENCES View (id)");
        $this->addSql("ALTER TABLE Zone ADD CONSTRAINT FK_D96F395E564AE2 FOREIGN KEY (alias_id) REFERENCES Zone (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE RecordView DROP FOREIGN KEY FK_3ED6C757EE8A0C7B");
        $this->addSql("ALTER TABLE ServerView DROP FOREIGN KEY FK_623014C031518C7");
        $this->addSql("ALTER TABLE RecordView DROP FOREIGN KEY FK_3ED6C75731518C7");
        $this->addSql("ALTER TABLE ServerView DROP FOREIGN KEY FK_623014C0B26CDFEC");
        $this->addSql("ALTER TABLE Zone DROP FOREIGN KEY FK_D96F395E564AE2");
        $this->addSql("DROP TABLE Record");
        $this->addSql("DROP TABLE ServerView");
        $this->addSql("DROP TABLE View");
        $this->addSql("DROP TABLE Server");
        $this->addSql("DROP TABLE RecordView");
        $this->addSql("DROP TABLE Zone");
    }
}
