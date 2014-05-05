create table if not exists tanks (
   id             int not null,
   name           varchar(100) not null,
   tier           smallint,
   class          smallint,
   premium        bit
);

create table if not exists wn8baselines (
   version              int not null,
   tankid               int not null,
   expectedKills        float not null,
   expectedDamage       float not null,
   expectedDetections   float not null,
   expectedDefense      float not null,
   expectedWinrate      float not null,

   foreign key ( tankid ) references tanks( id ) on delete cascade
);

create table if not exists users (
   id             int not null auto_increment primary key,
   playerid       int not null,
   name           varchar(100)
);

create table if not exists users_stats (
   id             int not null auto_increment primary key,
   userid         int not null,
   statsdate      int,
   battles        int,
   victories      int,
   detections     int,
   defense        int,
   kills          int,
   damage         int,

   foreign key ( userid ) references users( id ) on delete cascade
);

create table if not exists users_tanks (
   id             int not null auto_increment primary key,
   userstatid     int not null,
   tankid         int not null,
   kills          int,
   damage         int,
   detections     int,
   defense        int,
   winrate        float,

   foreign key ( userstatid ) references users_stats( id ) on delete cascade,
   foreign key ( tankid ) references tanks( id ) on delete cascade
);

