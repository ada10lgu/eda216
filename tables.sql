set foreign_key_checks = 0;
drop table if exists reservation;
drop table if exists venue;
drop table if exists theatre;
drop table if exists user;
set foreign_key_checks = 1;

CREATE TABLE user (
  userName varchar(20) not null,
  name varchar(20) not null,
  telephone int not null,
  address varchar(255),
  primary key (userName)
);

CREATE TABLE theatre (
  name varchar(20) not null,
  seats int not null,
  primary key (name)
);

CREATE TABLE venue (
  id int auto_increment,
  date date not null,
  movie varchar(248) not null, # So that the following movie title can fit - Night of the Day of the Dawn of the Son of the Bride of the Return of the Revenge of the Terror of the Attack of the Evil, Mutant, Hellbound, Flesh-Eating Subhumanoid Zombified Living Dead, Part 3 (2005)
  theatre varchar(20) not null,
  primary key (id),
  foreign key (theatre) references theatre(name),
  unique (date,movie)
);

CREATE TABLE reservation (
  id int auto_increment,
  venue int not null,
  user varchar(20) not null,
  primary key (id),
  foreign key (venue) references venue(id),
  foreign key (user) references user(username)
);


INSERT INTO user(username,name,telephone) VALUES ("pj","per",90000); 
INSERT INTO user(username,name,telephone,address) VALUES ("mezz","lars",112,"hemma");
INSERT INTO user(username,telephone,name,address) VALUES ("per", 044123456,"pher holm", "malmö");

INSERT INTO theatre (name, seats) VALUES ("SF-lund",9000);
INSERT INTO theatre (name, seats) VALUES ("SF-malmö",2);
INSERT INTO theatre (name, seats) VALUES ("Filmstaden-malmö",12);

INSERT INTO venue (date, movie, theatre) VALUES ("2014-02-03","Night of the Day of the Dawn of the Son of the Bride of the Return of the Revenge of the Terror of the Attack of the Evil, Mutant, Hellbound, Flesh-Eating Subhumanoid Zombified Living Dead, Part 3 (2005)","SF-lund");
INSERT INTO venue (date,movie,theatre) VALUES ("2013-03-13","U die!? 2 the raccoon","SF-lund");
INSERT INTO venue (date,movie,theatre) VALUES ("2014-02-01","Movie","SF-Lund");
INSERT INTO venue (date,movie,theatre) VALUES ("2014-02-01","Movie","SF-Malmö");
INSERT INTO venue (date,movie,theatre) VALUES ("2014-02-03","Movie","SF-Malmö");
INSERT INTO venue (date,movie,theatre) VALUES ("2014-02-02","Movie","SF-Lund");


# LIST ALL MOVIES THAT ARE SHOWN
SELECT movie FROM venue GROUP BY movie;


# LIST DATES WHEN A MOVIE IS SHOWN
SELECT date from venue where movie="Movie";
SELECT date from venue where movie="U die!? 2 the raccoon";

# LIST DATA ABOUT ALL VENUES (ignore long name)
SELECT * 
FROM venue AS v 
LEFT JOIN theatre AS t ON v.theatre = t.name 
WHERE movie NOT LIKE "N%";


# CREATE RESARVATION

INSERT INTO reservation(venue,user) VALUES (5,"mezz");
INSERT INTO reservation(venue,user) VALUES (5,"pj");
INSERT INTO reservation(venue,user) VALUES (5,"mEzz");

