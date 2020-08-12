create table User
(
	id int auto_increment primary key,
	userIdentifier varchar(250) not null,
	userEmail varchar(250) not null,
	userName varchar(250) not null,
    userPassword varchar(250) not null,
    createdOn datetime not null,
    updatedOn datetime null,
    deletedOn datetime null
);

create unique index UserIdentifier
	on User (userIdentifier);

create index UserEmailPw
	on User (userEmail, userPassword);

create unique index UserEmail
	on User (userEmail);
