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


insert into user(username,name,telephone) values ("pj","per",90000); 
insert into user(username,name,telephone,address) values("mezz","lars",112,"hemma");
insert into user(username,telephone,name,address) values("per", 044123456,"pher holm", "malmö");

insert into theatre (name, seats) values ("SF-lund",9000);
insert into theatre (name, seats) values ("SF-malmö",2);
insert into theatre (name, seats) values ("Filmstaden-malmö",12);

insert into venue (date, movie, theatre) values ("2014-02-03","Night of the Day of the Dawn of the Son of the Bride of the Return of the Revenge of the Terror of the Attack of the Evil, Mutant, Hellbound, Flesh-Eating Subhumanoid Zombified Living Dead, Part 3 (2005)","SF-lund");
insert into venue (date,movie,theatre) values ("2013-03-13","U die!? 2 the raccoon","SF-lund");
insert into venue (date,movie,theatre) values ("2014-02-01","Movie","SF-Lund");
insert into venue (date,movie,theatre) values ("2014-02-01","Movie","SF-Malmö");
insert into venue (date,movie,theatre) values ("2014-02-03","Movie","SF-Malmö");
insert into venue (date,movie,theatre) values ("2014-02-02","Movie","SF-Lund");


# LIST ALL MOVIES THAT ARE SHOWN
SELECT movie FROM venue GROUP BY movie;


# LIST DATES WHEN A MOVIE IS SHOWN
SELECT date from venue where movie="Movie";
SELECT date from venue where movie="U die!? 2 the raccoon";

# LIST DATA ABOUT ALL VENUES (ignore long name)
select * from venue as v left join theatre as t on v.theatre = t.name where movie not like "N%";


# CREATE RESARVATION

INSERT INTO reservation(venue,user) VALUES (5,"mezz");
INSERT INTO reservation(venue,user) VALUES (5,"pj");
INSERT INTO reservation(venue,user) VALUES (5,"mEzz");

