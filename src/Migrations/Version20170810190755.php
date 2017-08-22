<?php

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170810190755 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE communities (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, private BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE competitions (id INTEGER NOT NULL, image_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A7DD463D3DA5256D ON competitions (image_id)');
        $this->addSql('CREATE TABLE forecasts (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, community_id INTEGER DEFAULT NULL, match_id INTEGER DEFAULT NULL, local_goals INTEGER NOT NULL, away_goals INTEGER NOT NULL, risk BOOLEAN NOT NULL, points INTEGER NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A95D6FA99E6F5DF ON forecasts (player_id)');
        $this->addSql('CREATE INDEX IDX_6A95D6FAFDA7B0BF ON forecasts (community_id)');
        $this->addSql('CREATE INDEX IDX_6A95D6FA2ABEACD6 ON forecasts (match_id)');
        $this->addSql('CREATE TABLE general_classification (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, community_id INTEGER DEFAULT NULL, matchday_id INTEGER DEFAULT NULL, total_points INTEGER NOT NULL, hits_ten_points INTEGER NOT NULL, hits_five_points INTEGER NOT NULL, hits_three_points INTEGER NOT NULL, hits_two_points INTEGER NOT NULL, hits_one_points INTEGER NOT NULL, hits_negative_points INTEGER NOT NULL, position INTEGER NOT NULL, times_first INTEGER NOT NULL, times_second INTEGER NOT NULL, times_third INTEGER NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EA8CCCC499E6F5DF ON general_classification (player_id)');
        $this->addSql('CREATE INDEX IDX_EA8CCCC4FDA7B0BF ON general_classification (community_id)');
        $this->addSql('CREATE INDEX IDX_EA8CCCC43D90D21B ON general_classification (matchday_id)');
        $this->addSql('CREATE TABLE images (id INTEGER NOT NULL, image_type VARCHAR(255) NOT NULL, description CLOB NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE matches (id INTEGER NOT NULL, matchday_id INTEGER DEFAULT NULL, local_team_id INTEGER DEFAULT NULL, away_team_id INTEGER DEFAULT NULL, stadium_id INTEGER DEFAULT NULL, image_id INTEGER DEFAULT NULL, start_time DATETIME NOT NULL, local_goals INTEGER NOT NULL, away_goals INTEGER NOT NULL, state INTEGER NOT NULL, tag VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62615BA3D90D21B ON matches (matchday_id)');
        $this->addSql('CREATE INDEX IDX_62615BAB4B9DD23 ON matches (local_team_id)');
        $this->addSql('CREATE INDEX IDX_62615BA45185D02 ON matches (away_team_id)');
        $this->addSql('CREATE INDEX IDX_62615BA7E860E36 ON matches (stadium_id)');
        $this->addSql('CREATE INDEX IDX_62615BA3DA5256D ON matches (image_id)');
        $this->addSql('CREATE TABLE matchdays (id INTEGER NOT NULL, competition_id INTEGER DEFAULT NULL, phase_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, matchday_order INTEGER NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A2C144427B39D312 ON matchdays (competition_id)');
        $this->addSql('CREATE INDEX IDX_A2C1444299091188 ON matchdays (phase_id)');
        $this->addSql('CREATE TABLE matchday_classification (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, community_id INTEGER DEFAULT NULL, matchday_id INTEGER DEFAULT NULL, basic_points INTEGER NOT NULL, points_for_position INTEGER NOT NULL, total_points INTEGER NOT NULL, hits_ten_points INTEGER NOT NULL, hits_five_points INTEGER NOT NULL, hits_three_points INTEGER NOT NULL, hits_two_points INTEGER NOT NULL, hits_one_points INTEGER NOT NULL, hits_negative_points INTEGER NOT NULL, position INTEGER NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_37A81ADC99E6F5DF ON matchday_classification (player_id)');
        $this->addSql('CREATE INDEX IDX_37A81ADCFDA7B0BF ON matchday_classification (community_id)');
        $this->addSql('CREATE INDEX IDX_37A81ADC3D90D21B ON matchday_classification (matchday_id)');
        $this->addSql('CREATE TABLE participants (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, community_id INTEGER DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7169709299E6F5DF ON participants (player_id)');
        $this->addSql('CREATE INDEX IDX_71697092FDA7B0BF ON participants (community_id)');
        $this->addSql('CREATE TABLE phases (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, multiplier_factor DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE players (id INTEGER NOT NULL, nickname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, avatar INTEGER NOT NULL, color VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE stadiums (id INTEGER NOT NULL, image_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FD8B970E3DA5256D ON stadiums (image_id)');
        $this->addSql('CREATE TABLE teams (id INTEGER NOT NULL, stadium_id INTEGER DEFAULT NULL, image_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_96C222587E860E36 ON teams (stadium_id)');
        $this->addSql('CREATE INDEX IDX_96C222583DA5256D ON teams (image_id)');
        $this->addSql('CREATE TABLE tokens (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, token_string VARCHAR(255) NOT NULL, expire_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AA5A118E99E6F5DF ON tokens (player_id)');
        $this->addSql('CREATE TABLE user (id INTEGER NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles CLOB NOT NULL, isActive BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64992FC23A8 ON user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A0D96FBF ON user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C05FB297 ON user (confirmation_token)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE communities');
        $this->addSql('DROP TABLE competitions');
        $this->addSql('DROP TABLE forecasts');
        $this->addSql('DROP TABLE general_classification');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE matches');
        $this->addSql('DROP TABLE matchdays');
        $this->addSql('DROP TABLE matchday_classification');
        $this->addSql('DROP TABLE participants');
        $this->addSql('DROP TABLE phases');
        $this->addSql('DROP TABLE players');
        $this->addSql('DROP TABLE stadiums');
        $this->addSql('DROP TABLE teams');
        $this->addSql('DROP TABLE tokens');
        $this->addSql('DROP TABLE user');
    }
}
