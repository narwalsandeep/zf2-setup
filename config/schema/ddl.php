
create table demo_user(
	
	id int(10) auto_increment,
	username varchar(100),
	password varchar(100),
	primary key(id)
	
)engine=innodb;

insert into demo_user(id,username,password) values(1,"su","su");

create table demo_article(
	
	id int(10) auto_increment,
	user_id int(10),
	article varchar(200),
	`blob` text,
	primary key(id),
	foreign key(user_id) references demo_user(id) on delete cascade on update cascade
	
)engine=innodb;


