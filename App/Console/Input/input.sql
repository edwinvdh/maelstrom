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
