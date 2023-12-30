CREATE TABLE Stock_have(
	StockItemID	   INTEGER,
	Type	  	   char(20),
    Quantity	   INTEGER,
    ItemID		   INTEGER NOT NULL,
	Primary key(StockItemID,ItemID)
);

CREATE TABLE Product_take(
	ItemID	   	  INTEGER,
	Name	  	  char(20),
	Price	   	  INTEGER,
	COrderID	  INTEGER NOT NULL UNIQUE,
	Primary key(ItemID),
	StockItemID	  INTEGER,
    FOREIGN KEY(StockItemID,ItemID) REFERENCES Stock_have(StockItemID,ItemID) ON DELETE CASCADE --Stock_have has two Primary Keys
    --missed a FK
);

CREATE TABLE TrackingInfo (
	Street		char(30),
	City		char(20),
	Province	char(20),
	Primary Key(Street,City)
);

CREATE TABLE Order_Table(
	OrderID	   INTEGER Primary key,
	Quantity	   INTEGER,
	TrackingNumber INTEGER NOT NULL UNIQUE
    --missed a Foreign Key here
);

CREATE TABLE Deliverer(
	DelieverID	   INTEGER Primary key,
	DName	   char(20)
);

CREATE TABLE Tracking_ship_Deliverer_has_TrackInfo(
	Tracking_number INTEGER,
	Province 	   char(20),
	Phone 		   INTEGER,
	DelieverID	   INTEGER NOT NULL UNIQUE,
    OrderID	   INTEGER NOT NULL UNIQUE,
    Street 		   char(30) UNIQUE,
	City 		   char(20) UNIQUE,
	Primary Key(Tracking_number),
    foreign key(DelieverID) references Deliverer ON DELETE CASCADE,
    FOREIGN KEY(OrderID) REFERENCES Order_Table ON DELETE CASCADE, --change to order_table
    FOREIGN KEY(Street,City) REFERENCES TrackingInfo ON DELETE CASCADE
);



CREATE TABLE CurrentOrder(
	OrderID	   INTEGER,
	COrderID	   INTEGER,
	Quantity	   INTEGER,
	Primary key(OrderID,COrderID),
	ItemID		   INTEGER NOT NULL,
    FOREIGN KEY(OrderID) REFERENCES Order_Table(OrderID) ON DELETE CASCADE,
    FOREIGN KEY(ItemID) REFERENCES Product_take(ItemID) ON DELETE CASCADE
);

CREATE TABLE BackOrder(
	OrderID	   INTEGER,
	BOrderID	   INTEGER,
	StockItemID	   INTEGER,
	Quantity	   INTEGER,
	Primary key(OrderID,BOrderID,StockItemID),
    FOREIGN KEY(OrderID) REFERENCES Order_Table(OrderID) ON DELETE CASCADE
);

CREATE TABLE Supplier(
	SupplyerID	   INTEGER,
	Name		   char(20),
	Primary key(SupplyerID)
);

CREATE TABLE Supply(
	OrderID	   INTEGER NOT NULL,
	SupplyerID	   INTEGER NOT NULL,
	BOrderID	   INTEGER NOT NULL,
	StockItemID	   INTEGER NOT NULL,
	Primary key(OrderID,BOrderID,StockItemID, SupplyerID),
    FOREIGN KEY(SupplyerID) REFERENCES Supplier(SupplyerID) ON DELETE CASCADE,
    FOREIGN KEY(OrderID, BOrderID, StockItemID) REFERENCES BackOrder(OrderID, BOrderID, StockItemID) ON DELETE CASCADE	
);



CREATE TABLE Increase (
    	StockItemID	   INTEGER NOT NULL,
ItemID		   INTEGER NOT NULL,
BackOrderID 	   INTEGER NOT NULL, 
	BackItemID	   INTEGER NOT NULL, ---fixed from two StockItemID to BackItemID in back order
    Primary key (StockItemID,ItemID,BackOrderID,BackItemID),
    FOREIGN KEY(StockItemID,ItemID) REFERENCES Stock_have(StockItemID,ItemID) ON DELETE CASCADE,
    FOREIGN KEY(BackItemID,BackOrderID,StockItemID) REFERENCES BackOrder(OrderID,BOrderID,StockItemID)
		ON DELETE SET NULL
);

CREATE TABLE Category(
	CategoryID	   INTEGER,
Type		   char(20),
Name	   	   char(20),
	Primary key(CategoryID)
);

CREATE TABLE belongs(
	CategoryID	   INTEGER NOT NULL,
    ItemID		   INTEGER,
	Primary key(CategoryID,ItemID),
    FOREIGN KEY(ItemID) REFERENCES Product_take(ItemID) ON DELETE SET NULL,
	FOREIGN KEY(CategoryID) REFERENCES Category(CategoryID) ON DELETE CASCADE
);

CREATE TABLE Account(
	Login_name	char(20) Primary Key,
	password	char(20)
);

CREATE TABLE DBAdministrator_has(
	UserID	   	   INTEGER,
    Name		   char(20),
    Password	   char(20),
    Login_name       char(20) UNIQUE,
	Primary key(UserID),
    FOREIGN KEY(Login_name) REFERENCES Account ON DELETE CASCADE
);

CREATE TABLE Control(
	UserID		   INTEGER NOT NULL,
    ItemID		   INTEGER NOT NULL,
	Primary key(UserID,ItemID)
);





INSERT
INTO Stock_have(StockItemID, Type, Quantity, ItemID)
VALUES (000001, 'furniture', 1, 100001);

INSERT
INTO Stock_have(StockItemID, Type, Quantity, ItemID)
VALUES (000002, 'daily', 2, 100002);

INSERT
INTO Stock_have(StockItemID, Type, Quantity, ItemID)
VALUES (000003, 'kitchen', 3, 100003);

INSERT
INTO Stock_have(StockItemID, Type, Quantity, ItemID)
VALUES (000004, 'bedroom', 4, 100004);

INSERT
INTO Stock_have(StockItemID, Type, Quantity, ItemID)
VALUES (000005, 'food', 5, 100005);


INSERT
INTO Control(UserID, ItemID)
VALUES (320001, 100001);

INSERT
INTO Control(UserID, ItemID)
VALUES (320002, 100002);

INSERT
INTO Control(UserID, ItemID)
VALUES (320003, 100003);

INSERT
INTO Control(UserID, ItemID)
VALUES (320004, 100004);

INSERT
INTO Control(UserID, ItemID)
VALUES (320005, 100005);


INSERT 
INTO Account(Login_name,password)
VALUES ('LoginA',000000001);

INSERT 
INTO Account(Login_name,password)
VALUES ('LoginB',000000002);

INSERT 
INTO Account(Login_name,password)
VALUES ('LoginC',000000003);

INSERT 
INTO Account(Login_name,password)
VALUES ('LoginD',000000004);

INSERT 
INTO Account(Login_name,password)
VALUES ('LoginE',000000005);

INSERT
INTO DBAdministrator_has(UserID, Name, Password, Login_name)
VALUES (320001, 'A', 000000001, 'LoginA');

INSERT
INTO DBAdministrator_has(UserID, Name, Password, Login_name)
VALUES (320002, 'B', 000000002, 'LoginB');

INSERT
INTO DBAdministrator_has(UserID, Name, Password, Login_name)
VALUES (320003, 'C', 000000003, 'LoginC');

INSERT
INTO DBAdministrator_has(UserID, Name, Password, Login_name)
VALUES (320004, 'D', 000000004, 'LoginD');

INSERT
INTO DBAdministrator_has(UserID, Name, Password, Login_name)
VALUES (320005, 'E', 000000005, 'LoginE');


INSERT
INTO Product_take(ItemID, Name, Price, COrderID, StockItemID)
VALUES (100001, 'A', 001, 0001, 000001);

INSERT
INTO Product_take(ItemID, Name, Price, COrderID, StockItemID)
VALUES (100002, 'B', 002, 0002, 000002);

INSERT
INTO Product_take(ItemID, Name, Price, COrderID, StockItemID)
VALUES (100003, 'C', 003, 0003, 000003);

INSERT
INTO Product_take(ItemID, Name, Price, COrderID, StockItemID)
VALUES (100004, 'D', 004, 0004, 000004);

INSERT
INTO Product_take(ItemID, Name, Price, COrderID, StockItemID)
VALUES (100005, 'E', 005, 0005, 000005);


INSERT 
INTO TrackingInfo(City, Street, Province)
VALUES ('Vancouver', '4th st', 'BC');

INSERT 
INTO TrackingInfo(City, Street, Province)
VALUES ('Burnaby', '5th st', 'BC');

INSERT 
INTO TrackingInfo(City, Street, Province)
VALUES ('Kelowna', '6th st', 'BC');

INSERT 
INTO TrackingInfo(City, Street, Province)
VALUES ('Surrey', '7th st', 'BC');

INSERT 
INTO TrackingInfo(City, Street, Province)
VALUES ('Delta', '8th st', 'BC');

INSERT
INTO Order_Table(OrderID, TrackingNumber, Quantity)
VALUES (000001, 0000001, 0001);

INSERT
INTO Order_Table(OrderID, TrackingNumber, Quantity)
VALUES (000002, 0000002, 0002);

INSERT
INTO Order_Table(OrderID, TrackingNumber, Quantity)
VALUES (000003, 0000003, 0003);

INSERT
INTO Order_Table(OrderID, TrackingNumber, Quantity)
VALUES (000004, 0000004, 0004);

INSERT
INTO Order_Table(OrderID, TrackingNumber, Quantity)
VALUES (000005, 0000005, 0005);

INSERT 
INTO Deliverer(DelieverID, DName)
VALUES (001, 'Anne');

INSERT 
INTO Deliverer(DelieverID, DName)
VALUES (002, 'Bob');

INSERT 
INTO Deliverer(DelieverID, DName)
VALUES (003, 'Cap');

INSERT 
INTO Deliverer(DelieverID, DName)
VALUES (004, 'Danny');

INSERT 
INTO Deliverer(DelieverID, DName)
VALUES (005, 'Edward');


INSERT
INTO Tracking_ship_Deliverer_has_TrackInfo(Tracking_number, Street, City, Phone, DelieverID, OrderID)
VALUES (0000001, '4th st', 'Vancouver', 6000000000, 001, 000001);

INSERT
INTO Tracking_ship_Deliverer_has_TrackInfo(Tracking_number, Street, City, Phone, DelieverID, OrderID)
VALUES (0000002, '5th st', 'Burnaby', 6100000000, 002, 000002);

INSERT
INTO Tracking_ship_Deliverer_has_TrackInfo(Tracking_number, Street, City, Phone, DelieverID, OrderID)
VALUES (0000003, '6th st', 'Kelowna', 6200000000, 003, 000003);

INSERT
INTO Tracking_ship_Deliverer_has_TrackInfo(Tracking_number, Street, City, Phone, DelieverID, OrderID)
VALUES (0000004, '7th st', 'Surrey', 6300000000, 004, 000004);

INSERT
INTO Tracking_ship_Deliverer_has_TrackInfo(Tracking_number, Street, City, Phone, DelieverID, OrderID)
VALUES (0000005, '8th st', 'Delta', 6400000000, 005, 000005);


INSERT
INTO CurrentOrder(OrderID, COrderID, Quantity,ItemID)
VALUES 	(000001, 100001, 0001,100001);

INSERT
INTO CurrentOrder(OrderID, COrderID, Quantity,ItemID)
VALUES 	(000002, 100002, 0002,100002);

INSERT
INTO CurrentOrder(OrderID, COrderID, Quantity,ItemID)
VALUES 	(000003, 100003, 0003,100003);

INSERT
INTO CurrentOrder(OrderID, COrderID, Quantity,ItemID)
VALUES 	(000004, 100004, 0004,100004);

INSERT
INTO CurrentOrder(OrderID, COrderID, Quantity,ItemID)
VALUES 	(000005, 100005, 0005,100005);


INSERT
INTO BackOrder(OrderID, BOrderID, StockItemID, Quantity)
VALUES (000001, 10001, 001,0001);

INSERT
INTO BackOrder(OrderID, BOrderID, StockItemID, Quantity)
VALUES (000002, 10002, 002,0002);

INSERT
INTO BackOrder(OrderID, BOrderID, StockItemID, Quantity)
VALUES (000003, 10003, 003,0003);

INSERT
INTO BackOrder(OrderID, BOrderID, StockItemID, Quantity)
VALUES (000004, 10004, 004,0004);

INSERT
INTO BackOrder(OrderID, BOrderID, StockItemID, Quantity)
VALUES (000005, 10005, 005,0005);

INSERT
INTO Supplier(SupplyerID, Name)
VALUES (01, 'A');

INSERT
INTO Supplier(SupplyerID, Name)
VALUES (02, 'B');

INSERT
INTO Supplier(SupplyerID, Name)
VALUES (03, 'C');

INSERT
INTO Supplier(SupplyerID, Name)
VALUES (04, 'D');

INSERT
INTO Supplier(SupplyerID, Name)
VALUES (05, 'E');

INSERT
INTO Supply(SupplyerID, BOrderID, StockItemID, OrderID)
VALUES (01, 10001, 001, 000001);

INSERT
INTO Supply(SupplyerID, BOrderID, StockItemID, OrderID)
VALUES (02, 10002, 002, 000002);

INSERT
INTO Supply(SupplyerID, BOrderID, StockItemID, OrderID)
VALUES (03, 10003, 003, 000003);

INSERT
INTO Supply(SupplyerID, BOrderID, StockItemID, OrderID)
VALUES (04, 10004, 004, 000004);

INSERT
INTO Supply(SupplyerID, BOrderID, StockItemID, OrderID)
VALUES (05, 10005, 005, 000005);


INSERT
INTO Increase(StockItemID, ItemID, BackOrderID,BackItemID)
VALUES (000001, 100001, 10001, 000001);

INSERT
INTO Increase(StockItemID, ItemID, BackOrderID, BackItemID)
VALUES (000002, 100002, 10002, 000002);

INSERT
INTO Increase(StockItemID, ItemID, BackOrderID, BackItemID)
VALUES (000003, 100003, 10003, 000003);

INSERT
INTO Increase(StockItemID, ItemID, BackOrderID, BackItemID)
VALUES (000004, 100004, 10004, 000004);

INSERT
INTO Increase(StockItemID, ItemID, BackOrderID, BackItemID)
VALUES (000005, 100005, 10005, 000005);

INSERT
INTO Category(CategoryID, Type, Name)
VALUES (00001, 001, 'A');

INSERT
INTO Category(CategoryID, Type, Name)
VALUES (00002, 002, 'B');

INSERT
INTO Category(CategoryID, Type, Name)
VALUES (00003, 003, 'C');

INSERT
INTO Category(CategoryID, Type, Name)
VALUES (00004, 004, 'D');

INSERT
INTO Category(CategoryID, Type, Name)
VALUES (00005, 005, 'E');


INSERT
INTO belongs(CategoryID, ItemID)
VALUES (00001, 100001);

INSERT
INTO belongs(CategoryID, ItemID)
VALUES (00002, 100002);

INSERT
INTO belongs(CategoryID, ItemID)
VALUES (00003, 100003);

INSERT
INTO belongs(CategoryID, ItemID)
VALUES (00004, 100004);

INSERT
INTO belongs(CategoryID, ItemID)
VALUES (00005, 100005);

INSERT
INTO Control(UserID, ItemID)
VALUES (320001, 100002);

INSERT
INTO Control(UserID, ItemID)
VALUES (320001, 100003);

INSERT
INTO Control(UserID, ItemID)
VALUES (320001, 100004);

INSERT
INTO Control(UserID, ItemID)
VALUES (320001, 100005);




ALTER TABLE Product_take ADD CONSTRAINT fk_cid FOREIGN KEY (COrderID,ItemID) REFERENCES CurrentOrder(OrderID,COrderID);
ALTER TABLE Order_Table ADD CONSTRAINT fk_tracking FOREIGN KEY (OrderID) REFERENCES Tracking_ship_Deliverer_has_TrackInfo(Tracking_number);

COMMIT WORK;