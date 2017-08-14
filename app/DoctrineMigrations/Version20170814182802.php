<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170814182802 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__communities AS SELECT id, name, password, private FROM communities');
        $this->addSql('DROP TABLE communities');
        $this->addSql('CREATE TABLE communities (id INTEGER NOT NULL, image_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, private BOOLEAN NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_ECE312B3DA5256D FOREIGN KEY (image_id) REFERENCES images (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO communities (id, name, password, private, created, updated) SELECT id, name, password, private, "2017-08-14 10:00:00", "2017-08-14 10:00:00" FROM __temp__communities');
        $this->addSql('DROP TABLE __temp__communities');
        $this->addSql('CREATE INDEX IDX_ECE312B3DA5256D ON communities (image_id)');
        $this->addSql('DROP INDEX IDX_A7DD463D3DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__competitions AS SELECT id, image_id, name, alias FROM competitions');
        $this->addSql('DROP TABLE competitions');
        $this->addSql('CREATE TABLE competitions (id INTEGER NOT NULL, image_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, alias VARCHAR(255) NOT NULL COLLATE BINARY, color VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_A7DD463D3DA5256D FOREIGN KEY (image_id) REFERENCES images (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO competitions (id, image_id, name, alias) SELECT id, image_id, name, alias FROM __temp__competitions');
        $this->addSql('DROP TABLE __temp__competitions');
        $this->addSql('CREATE INDEX IDX_A7DD463D3DA5256D ON competitions (image_id)');
        $this->addSql('DROP INDEX IDX_6A95D6FA99E6F5DF');
        $this->addSql('DROP INDEX IDX_6A95D6FAFDA7B0BF');
        $this->addSql('DROP INDEX IDX_6A95D6FA2ABEACD6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__forecasts AS SELECT id, player_id, community_id, match_id, local_goals, away_goals, risk, points, created, updated FROM forecasts');
        $this->addSql('DROP TABLE forecasts');
        $this->addSql('CREATE TABLE forecasts (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, community_id INTEGER DEFAULT NULL, match_id INTEGER DEFAULT NULL, local_goals INTEGER NOT NULL, away_goals INTEGER NOT NULL, risk BOOLEAN NOT NULL, points INTEGER NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_6A95D6FA99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6A95D6FAFDA7B0BF FOREIGN KEY (community_id) REFERENCES communities (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6A95D6FA2ABEACD6 FOREIGN KEY (match_id) REFERENCES matches (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO forecasts (id, player_id, community_id, match_id, local_goals, away_goals, risk, points, created, updated) SELECT id, player_id, community_id, match_id, local_goals, away_goals, risk, points, created, updated FROM __temp__forecasts');
        $this->addSql('DROP TABLE __temp__forecasts');
        $this->addSql('CREATE INDEX IDX_6A95D6FA99E6F5DF ON forecasts (player_id)');
        $this->addSql('CREATE INDEX IDX_6A95D6FAFDA7B0BF ON forecasts (community_id)');
        $this->addSql('CREATE INDEX IDX_6A95D6FA2ABEACD6 ON forecasts (match_id)');
        $this->addSql('DROP INDEX IDX_EA8CCCC499E6F5DF');
        $this->addSql('DROP INDEX IDX_EA8CCCC4FDA7B0BF');
        $this->addSql('DROP INDEX IDX_EA8CCCC43D90D21B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__general_classification AS SELECT id, player_id, community_id, matchday_id, total_points, hits_ten_points, hits_five_points, hits_three_points, hits_two_points, hits_one_points, hits_negative_points, position, times_first, times_second, times_third, created, updated FROM general_classification');
        $this->addSql('DROP TABLE general_classification');
        $this->addSql('CREATE TABLE general_classification (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, community_id INTEGER DEFAULT NULL, matchday_id INTEGER DEFAULT NULL, total_points INTEGER NOT NULL, hits_ten_points INTEGER NOT NULL, hits_five_points INTEGER NOT NULL, hits_three_points INTEGER NOT NULL, hits_two_points INTEGER NOT NULL, hits_one_points INTEGER NOT NULL, hits_negative_points INTEGER NOT NULL, position INTEGER NOT NULL, times_first INTEGER NOT NULL, times_second INTEGER NOT NULL, times_third INTEGER NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_EA8CCCC499E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_EA8CCCC4FDA7B0BF FOREIGN KEY (community_id) REFERENCES communities (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_EA8CCCC43D90D21B FOREIGN KEY (matchday_id) REFERENCES matchdays (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO general_classification (id, player_id, community_id, matchday_id, total_points, hits_ten_points, hits_five_points, hits_three_points, hits_two_points, hits_one_points, hits_negative_points, position, times_first, times_second, times_third, created, updated) SELECT id, player_id, community_id, matchday_id, total_points, hits_ten_points, hits_five_points, hits_three_points, hits_two_points, hits_one_points, hits_negative_points, position, times_first, times_second, times_third, created, updated FROM __temp__general_classification');
        $this->addSql('DROP TABLE __temp__general_classification');
        $this->addSql('CREATE INDEX IDX_EA8CCCC499E6F5DF ON general_classification (player_id)');
        $this->addSql('CREATE INDEX IDX_EA8CCCC4FDA7B0BF ON general_classification (community_id)');
        $this->addSql('CREATE INDEX IDX_EA8CCCC43D90D21B ON general_classification (matchday_id)');
        $this->addSql('DROP INDEX IDX_62615BA3D90D21B');
        $this->addSql('DROP INDEX IDX_62615BAB4B9DD23');
        $this->addSql('DROP INDEX IDX_62615BA45185D02');
        $this->addSql('DROP INDEX IDX_62615BA7E860E36');
        $this->addSql('DROP INDEX IDX_62615BA3DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__matches AS SELECT id, matchday_id, local_team_id, away_team_id, stadium_id, image_id, start_time, local_goals, away_goals, state, tag, city, created, updated FROM matches');
        $this->addSql('DROP TABLE matches');
        $this->addSql('CREATE TABLE matches (id INTEGER NOT NULL, matchday_id INTEGER DEFAULT NULL, local_team_id INTEGER DEFAULT NULL, away_team_id INTEGER DEFAULT NULL, stadium_id INTEGER DEFAULT NULL, image_id INTEGER DEFAULT NULL, start_time DATETIME NOT NULL, local_goals INTEGER NOT NULL, away_goals INTEGER NOT NULL, state INTEGER NOT NULL, tag VARCHAR(255) NOT NULL COLLATE BINARY, city VARCHAR(255) NOT NULL COLLATE BINARY, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_62615BA3D90D21B FOREIGN KEY (matchday_id) REFERENCES matchdays (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_62615BAB4B9DD23 FOREIGN KEY (local_team_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_62615BA45185D02 FOREIGN KEY (away_team_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_62615BA7E860E36 FOREIGN KEY (stadium_id) REFERENCES stadiums (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_62615BA3DA5256D FOREIGN KEY (image_id) REFERENCES images (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO matches (id, matchday_id, local_team_id, away_team_id, stadium_id, image_id, start_time, local_goals, away_goals, state, tag, city, created, updated) SELECT id, matchday_id, local_team_id, away_team_id, stadium_id, image_id, start_time, local_goals, away_goals, state, tag, city, created, updated FROM __temp__matches');
        $this->addSql('DROP TABLE __temp__matches');
        $this->addSql('CREATE INDEX IDX_62615BA3D90D21B ON matches (matchday_id)');
        $this->addSql('CREATE INDEX IDX_62615BAB4B9DD23 ON matches (local_team_id)');
        $this->addSql('CREATE INDEX IDX_62615BA45185D02 ON matches (away_team_id)');
        $this->addSql('CREATE INDEX IDX_62615BA7E860E36 ON matches (stadium_id)');
        $this->addSql('CREATE INDEX IDX_62615BA3DA5256D ON matches (image_id)');
        $this->addSql('DROP INDEX IDX_A2C144427B39D312');
        $this->addSql('DROP INDEX IDX_A2C1444299091188');
        $this->addSql('CREATE TEMPORARY TABLE __temp__matchdays AS SELECT id, competition_id, phase_id, name, alias, matchday_order, created, updated FROM matchdays');
        $this->addSql('DROP TABLE matchdays');
        $this->addSql('CREATE TABLE matchdays (id INTEGER NOT NULL, competition_id INTEGER DEFAULT NULL, phase_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, alias VARCHAR(255) NOT NULL COLLATE BINARY, matchday_order INTEGER NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_A2C144427B39D312 FOREIGN KEY (competition_id) REFERENCES competitions (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A2C1444299091188 FOREIGN KEY (phase_id) REFERENCES phases (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO matchdays (id, competition_id, phase_id, name, alias, matchday_order, created, updated) SELECT id, competition_id, phase_id, name, alias, matchday_order, created, updated FROM __temp__matchdays');
        $this->addSql('DROP TABLE __temp__matchdays');
        $this->addSql('CREATE INDEX IDX_A2C144427B39D312 ON matchdays (competition_id)');
        $this->addSql('CREATE INDEX IDX_A2C1444299091188 ON matchdays (phase_id)');
        $this->addSql('DROP INDEX IDX_37A81ADC99E6F5DF');
        $this->addSql('DROP INDEX IDX_37A81ADCFDA7B0BF');
        $this->addSql('DROP INDEX IDX_37A81ADC3D90D21B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__matchday_classification AS SELECT id, player_id, community_id, matchday_id, basic_points, points_for_position, total_points, hits_ten_points, hits_five_points, hits_three_points, hits_two_points, hits_one_points, hits_negative_points, position, created, updated FROM matchday_classification');
        $this->addSql('DROP TABLE matchday_classification');
        $this->addSql('CREATE TABLE matchday_classification (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, community_id INTEGER DEFAULT NULL, matchday_id INTEGER DEFAULT NULL, basic_points INTEGER NOT NULL, points_for_position INTEGER NOT NULL, total_points INTEGER NOT NULL, hits_ten_points INTEGER NOT NULL, hits_five_points INTEGER NOT NULL, hits_three_points INTEGER NOT NULL, hits_two_points INTEGER NOT NULL, hits_one_points INTEGER NOT NULL, hits_negative_points INTEGER NOT NULL, position INTEGER NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_37A81ADC99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_37A81ADCFDA7B0BF FOREIGN KEY (community_id) REFERENCES communities (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_37A81ADC3D90D21B FOREIGN KEY (matchday_id) REFERENCES matchdays (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO matchday_classification (id, player_id, community_id, matchday_id, basic_points, points_for_position, total_points, hits_ten_points, hits_five_points, hits_three_points, hits_two_points, hits_one_points, hits_negative_points, position, created, updated) SELECT id, player_id, community_id, matchday_id, basic_points, points_for_position, total_points, hits_ten_points, hits_five_points, hits_three_points, hits_two_points, hits_one_points, hits_negative_points, position, created, updated FROM __temp__matchday_classification');
        $this->addSql('DROP TABLE __temp__matchday_classification');
        $this->addSql('CREATE INDEX IDX_37A81ADC99E6F5DF ON matchday_classification (player_id)');
        $this->addSql('CREATE INDEX IDX_37A81ADCFDA7B0BF ON matchday_classification (community_id)');
        $this->addSql('CREATE INDEX IDX_37A81ADC3D90D21B ON matchday_classification (matchday_id)');
        $this->addSql('DROP INDEX IDX_7169709299E6F5DF');
        $this->addSql('DROP INDEX IDX_71697092FDA7B0BF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__participants AS SELECT id, player_id, community_id, created, updated FROM participants');
        $this->addSql('DROP TABLE participants');
        $this->addSql('CREATE TABLE participants (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, community_id INTEGER DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_7169709299E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_71697092FDA7B0BF FOREIGN KEY (community_id) REFERENCES communities (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO participants (id, player_id, community_id, created, updated) SELECT id, player_id, community_id, created, updated FROM __temp__participants');
        $this->addSql('DROP TABLE __temp__participants');
        $this->addSql('CREATE INDEX IDX_7169709299E6F5DF ON participants (player_id)');
        $this->addSql('CREATE INDEX IDX_71697092FDA7B0BF ON participants (community_id)');
        $this->addSql('DROP INDEX IDX_FD8B970E3DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__stadiums AS SELECT id, image_id, name, color FROM stadiums');
        $this->addSql('DROP TABLE stadiums');
        $this->addSql('CREATE TABLE stadiums (id INTEGER NOT NULL, image_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, color VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_FD8B970E3DA5256D FOREIGN KEY (image_id) REFERENCES images (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO stadiums (id, image_id, name, city) SELECT id, image_id, name, color FROM __temp__stadiums');
        $this->addSql('DROP TABLE __temp__stadiums');
        $this->addSql('CREATE INDEX IDX_FD8B970E3DA5256D ON stadiums (image_id)');
        $this->addSql('DROP INDEX IDX_96C222587E860E36');
        $this->addSql('DROP INDEX IDX_96C222583DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__teams AS SELECT id, stadium_id, image_id, name, alias, city, color FROM teams');
        $this->addSql('DROP TABLE teams');
        $this->addSql('CREATE TABLE teams (id INTEGER NOT NULL, stadium_id INTEGER DEFAULT NULL, image_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, alias VARCHAR(255) NOT NULL COLLATE BINARY, city VARCHAR(255) NOT NULL COLLATE BINARY, color VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_96C222587E860E36 FOREIGN KEY (stadium_id) REFERENCES stadiums (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_96C222583DA5256D FOREIGN KEY (image_id) REFERENCES images (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO teams (id, stadium_id, image_id, name, alias, city, color) SELECT id, stadium_id, image_id, name, alias, city, color FROM __temp__teams');
        $this->addSql('DROP TABLE __temp__teams');
        $this->addSql('CREATE INDEX IDX_96C222587E860E36 ON teams (stadium_id)');
        $this->addSql('CREATE INDEX IDX_96C222583DA5256D ON teams (image_id)');
        $this->addSql('DROP INDEX IDX_AA5A118E99E6F5DF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__tokens AS SELECT id, player_id, token_string, expire_at FROM tokens');
        $this->addSql('DROP TABLE tokens');
        $this->addSql('CREATE TABLE tokens (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, token_string VARCHAR(255) NOT NULL COLLATE BINARY, expire_at DATETIME NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_AA5A118E99E6F5DF FOREIGN KEY (player_id) REFERENCES players (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO tokens (id, player_id, token_string, expire_at) SELECT id, player_id, token_string, expire_at FROM __temp__tokens');
        $this->addSql('DROP TABLE __temp__tokens');
        $this->addSql('CREATE INDEX IDX_AA5A118E99E6F5DF ON tokens (player_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D64992FC23A8');
        $this->addSql('DROP INDEX UNIQ_8D93D649A0D96FBF');
        $this->addSql('DROP INDEX UNIQ_8D93D649C05FB297');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, isActive, roles FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER NOT NULL, username VARCHAR(180) NOT NULL COLLATE BINARY, username_canonical VARCHAR(180) NOT NULL COLLATE BINARY, email VARCHAR(180) NOT NULL COLLATE BINARY, email_canonical VARCHAR(180) NOT NULL COLLATE BINARY, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL COLLATE BINARY, password_requested_at DATETIME DEFAULT NULL, isActive BOOLEAN NOT NULL, roles CLOB NOT NULL --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO user (id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, isActive, roles) SELECT id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, isActive, roles FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
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

        $this->addSql('DROP INDEX IDX_ECE312B3DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__communities AS SELECT id, name, password, private FROM communities');
        $this->addSql('DROP TABLE communities');
        $this->addSql('CREATE TABLE communities (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, private BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO communities (id, name, password, private) SELECT id, name, password, private FROM __temp__communities');
        $this->addSql('DROP TABLE __temp__communities');
        $this->addSql('DROP INDEX IDX_A7DD463D3DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__competitions AS SELECT id, image_id, name, alias FROM competitions');
        $this->addSql('DROP TABLE competitions');
        $this->addSql('CREATE TABLE competitions (id INTEGER NOT NULL, image_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO competitions (id, image_id, name, alias) SELECT id, image_id, name, alias FROM __temp__competitions');
        $this->addSql('DROP TABLE __temp__competitions');
        $this->addSql('CREATE INDEX IDX_A7DD463D3DA5256D ON competitions (image_id)');
        $this->addSql('DROP INDEX IDX_6A95D6FA99E6F5DF');
        $this->addSql('DROP INDEX IDX_6A95D6FAFDA7B0BF');
        $this->addSql('DROP INDEX IDX_6A95D6FA2ABEACD6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__forecasts AS SELECT id, player_id, community_id, match_id, local_goals, away_goals, risk, points, created, updated FROM forecasts');
        $this->addSql('DROP TABLE forecasts');
        $this->addSql('CREATE TABLE forecasts (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, community_id INTEGER DEFAULT NULL, match_id INTEGER DEFAULT NULL, local_goals INTEGER NOT NULL, away_goals INTEGER NOT NULL, risk BOOLEAN NOT NULL, points INTEGER NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO forecasts (id, player_id, community_id, match_id, local_goals, away_goals, risk, points, created, updated) SELECT id, player_id, community_id, match_id, local_goals, away_goals, risk, points, created, updated FROM __temp__forecasts');
        $this->addSql('DROP TABLE __temp__forecasts');
        $this->addSql('CREATE INDEX IDX_6A95D6FA99E6F5DF ON forecasts (player_id)');
        $this->addSql('CREATE INDEX IDX_6A95D6FAFDA7B0BF ON forecasts (community_id)');
        $this->addSql('CREATE INDEX IDX_6A95D6FA2ABEACD6 ON forecasts (match_id)');
        $this->addSql('DROP INDEX IDX_EA8CCCC499E6F5DF');
        $this->addSql('DROP INDEX IDX_EA8CCCC4FDA7B0BF');
        $this->addSql('DROP INDEX IDX_EA8CCCC43D90D21B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__general_classification AS SELECT id, player_id, community_id, matchday_id, total_points, hits_ten_points, hits_five_points, hits_three_points, hits_two_points, hits_one_points, hits_negative_points, position, times_first, times_second, times_third, created, updated FROM general_classification');
        $this->addSql('DROP TABLE general_classification');
        $this->addSql('CREATE TABLE general_classification (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, community_id INTEGER DEFAULT NULL, matchday_id INTEGER DEFAULT NULL, total_points INTEGER NOT NULL, hits_ten_points INTEGER NOT NULL, hits_five_points INTEGER NOT NULL, hits_three_points INTEGER NOT NULL, hits_two_points INTEGER NOT NULL, hits_one_points INTEGER NOT NULL, hits_negative_points INTEGER NOT NULL, position INTEGER NOT NULL, times_first INTEGER NOT NULL, times_second INTEGER NOT NULL, times_third INTEGER NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO general_classification (id, player_id, community_id, matchday_id, total_points, hits_ten_points, hits_five_points, hits_three_points, hits_two_points, hits_one_points, hits_negative_points, position, times_first, times_second, times_third, created, updated) SELECT id, player_id, community_id, matchday_id, total_points, hits_ten_points, hits_five_points, hits_three_points, hits_two_points, hits_one_points, hits_negative_points, position, times_first, times_second, times_third, created, updated FROM __temp__general_classification');
        $this->addSql('DROP TABLE __temp__general_classification');
        $this->addSql('CREATE INDEX IDX_EA8CCCC499E6F5DF ON general_classification (player_id)');
        $this->addSql('CREATE INDEX IDX_EA8CCCC4FDA7B0BF ON general_classification (community_id)');
        $this->addSql('CREATE INDEX IDX_EA8CCCC43D90D21B ON general_classification (matchday_id)');
        $this->addSql('DROP INDEX IDX_37A81ADC99E6F5DF');
        $this->addSql('DROP INDEX IDX_37A81ADCFDA7B0BF');
        $this->addSql('DROP INDEX IDX_37A81ADC3D90D21B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__matchday_classification AS SELECT id, player_id, community_id, matchday_id, basic_points, points_for_position, total_points, hits_ten_points, hits_five_points, hits_three_points, hits_two_points, hits_one_points, hits_negative_points, position, created, updated FROM matchday_classification');
        $this->addSql('DROP TABLE matchday_classification');
        $this->addSql('CREATE TABLE matchday_classification (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, community_id INTEGER DEFAULT NULL, matchday_id INTEGER DEFAULT NULL, basic_points INTEGER NOT NULL, points_for_position INTEGER NOT NULL, total_points INTEGER NOT NULL, hits_ten_points INTEGER NOT NULL, hits_five_points INTEGER NOT NULL, hits_three_points INTEGER NOT NULL, hits_two_points INTEGER NOT NULL, hits_one_points INTEGER NOT NULL, hits_negative_points INTEGER NOT NULL, position INTEGER NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO matchday_classification (id, player_id, community_id, matchday_id, basic_points, points_for_position, total_points, hits_ten_points, hits_five_points, hits_three_points, hits_two_points, hits_one_points, hits_negative_points, position, created, updated) SELECT id, player_id, community_id, matchday_id, basic_points, points_for_position, total_points, hits_ten_points, hits_five_points, hits_three_points, hits_two_points, hits_one_points, hits_negative_points, position, created, updated FROM __temp__matchday_classification');
        $this->addSql('DROP TABLE __temp__matchday_classification');
        $this->addSql('CREATE INDEX IDX_37A81ADC99E6F5DF ON matchday_classification (player_id)');
        $this->addSql('CREATE INDEX IDX_37A81ADCFDA7B0BF ON matchday_classification (community_id)');
        $this->addSql('CREATE INDEX IDX_37A81ADC3D90D21B ON matchday_classification (matchday_id)');
        $this->addSql('DROP INDEX IDX_A2C144427B39D312');
        $this->addSql('DROP INDEX IDX_A2C1444299091188');
        $this->addSql('CREATE TEMPORARY TABLE __temp__matchdays AS SELECT id, competition_id, phase_id, name, alias, matchday_order, created, updated FROM matchdays');
        $this->addSql('DROP TABLE matchdays');
        $this->addSql('CREATE TABLE matchdays (id INTEGER NOT NULL, competition_id INTEGER DEFAULT NULL, phase_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, matchday_order INTEGER NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO matchdays (id, competition_id, phase_id, name, alias, matchday_order, created, updated) SELECT id, competition_id, phase_id, name, alias, matchday_order, created, updated FROM __temp__matchdays');
        $this->addSql('DROP TABLE __temp__matchdays');
        $this->addSql('CREATE INDEX IDX_A2C144427B39D312 ON matchdays (competition_id)');
        $this->addSql('CREATE INDEX IDX_A2C1444299091188 ON matchdays (phase_id)');
        $this->addSql('DROP INDEX IDX_62615BA3D90D21B');
        $this->addSql('DROP INDEX IDX_62615BAB4B9DD23');
        $this->addSql('DROP INDEX IDX_62615BA45185D02');
        $this->addSql('DROP INDEX IDX_62615BA7E860E36');
        $this->addSql('DROP INDEX IDX_62615BA3DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__matches AS SELECT id, matchday_id, local_team_id, away_team_id, stadium_id, image_id, start_time, local_goals, away_goals, state, tag, city, created, updated FROM matches');
        $this->addSql('DROP TABLE matches');
        $this->addSql('CREATE TABLE matches (id INTEGER NOT NULL, matchday_id INTEGER DEFAULT NULL, local_team_id INTEGER DEFAULT NULL, away_team_id INTEGER DEFAULT NULL, stadium_id INTEGER DEFAULT NULL, image_id INTEGER DEFAULT NULL, start_time DATETIME NOT NULL, local_goals INTEGER NOT NULL, away_goals INTEGER NOT NULL, state INTEGER NOT NULL, tag VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO matches (id, matchday_id, local_team_id, away_team_id, stadium_id, image_id, start_time, local_goals, away_goals, state, tag, city, created, updated) SELECT id, matchday_id, local_team_id, away_team_id, stadium_id, image_id, start_time, local_goals, away_goals, state, tag, city, created, updated FROM __temp__matches');
        $this->addSql('DROP TABLE __temp__matches');
        $this->addSql('CREATE INDEX IDX_62615BA3D90D21B ON matches (matchday_id)');
        $this->addSql('CREATE INDEX IDX_62615BAB4B9DD23 ON matches (local_team_id)');
        $this->addSql('CREATE INDEX IDX_62615BA45185D02 ON matches (away_team_id)');
        $this->addSql('CREATE INDEX IDX_62615BA7E860E36 ON matches (stadium_id)');
        $this->addSql('CREATE INDEX IDX_62615BA3DA5256D ON matches (image_id)');
        $this->addSql('DROP INDEX IDX_7169709299E6F5DF');
        $this->addSql('DROP INDEX IDX_71697092FDA7B0BF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__participants AS SELECT id, player_id, community_id, created, updated FROM participants');
        $this->addSql('DROP TABLE participants');
        $this->addSql('CREATE TABLE participants (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, community_id INTEGER DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO participants (id, player_id, community_id, created, updated) SELECT id, player_id, community_id, created, updated FROM __temp__participants');
        $this->addSql('DROP TABLE __temp__participants');
        $this->addSql('CREATE INDEX IDX_7169709299E6F5DF ON participants (player_id)');
        $this->addSql('CREATE INDEX IDX_71697092FDA7B0BF ON participants (community_id)');
        $this->addSql('DROP INDEX IDX_FD8B970E3DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__stadiums AS SELECT id, image_id, name, city FROM stadiums');
        $this->addSql('DROP TABLE stadiums');
        $this->addSql('CREATE TABLE stadiums (id INTEGER NOT NULL, image_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL COLLATE BINARY, alias VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO stadiums (id, image_id, name, color) SELECT id, image_id, name, city FROM __temp__stadiums');
        $this->addSql('DROP TABLE __temp__stadiums');
        $this->addSql('CREATE INDEX IDX_FD8B970E3DA5256D ON stadiums (image_id)');
        $this->addSql('DROP INDEX IDX_96C222587E860E36');
        $this->addSql('DROP INDEX IDX_96C222583DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__teams AS SELECT id, stadium_id, image_id, name, alias, city, color FROM teams');
        $this->addSql('DROP TABLE teams');
        $this->addSql('CREATE TABLE teams (id INTEGER NOT NULL, stadium_id INTEGER DEFAULT NULL, image_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO teams (id, stadium_id, image_id, name, alias, city, color) SELECT id, stadium_id, image_id, name, alias, city, color FROM __temp__teams');
        $this->addSql('DROP TABLE __temp__teams');
        $this->addSql('CREATE INDEX IDX_96C222587E860E36 ON teams (stadium_id)');
        $this->addSql('CREATE INDEX IDX_96C222583DA5256D ON teams (image_id)');
        $this->addSql('DROP INDEX IDX_AA5A118E99E6F5DF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__tokens AS SELECT id, player_id, token_string, expire_at FROM tokens');
        $this->addSql('DROP TABLE tokens');
        $this->addSql('CREATE TABLE tokens (id INTEGER NOT NULL, player_id INTEGER DEFAULT NULL, token_string VARCHAR(255) NOT NULL, expire_at DATETIME NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO tokens (id, player_id, token_string, expire_at) SELECT id, player_id, token_string, expire_at FROM __temp__tokens');
        $this->addSql('DROP TABLE __temp__tokens');
        $this->addSql('CREATE INDEX IDX_AA5A118E99E6F5DF ON tokens (player_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D64992FC23A8');
        $this->addSql('DROP INDEX UNIQ_8D93D649A0D96FBF');
        $this->addSql('DROP INDEX UNIQ_8D93D649C05FB297');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, isActive FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, isActive BOOLEAN NOT NULL, roles CLOB NOT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO user (id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, isActive) SELECT id, username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, isActive FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64992FC23A8 ON user (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A0D96FBF ON user (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C05FB297 ON user (confirmation_token)');
    }
}
