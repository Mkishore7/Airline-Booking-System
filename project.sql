-- Table Creation Queries
CREATE TABLE Users
(
  User_ID VARCHAR(20) NOT NULL,
  First_Name VARCHAR(20) NOT NULL,
  Last_Name VARCHAR(20) NOT NULL,
  DOB DATE NOT NULL,
  Gender CHAR(1) NOT NULL,
  Email VARCHAR(20) NOT NULL,
  Phone_No VARCHAR(20) NOT NULL,
  Password VARCHAR(20) NOT NULL,
  PRIMARY KEY (User_ID)
);

CREATE TABLE Airport
(
  Name VARCHAR(20) NOT NULL,
  City VARCHAR(20) NOT NULL,
  Country VARCHAR(20) NOT NULL,
  Airport_ID VARCHAR(20) NOT NULL,
  PRIMARY KEY (Airport_ID)
);

CREATE TABLE Airlines
(
  Name VARCHAR(20) NOT NULL,
  Airline_ID VARCHAR(20) NOT NULL,
  Account_No VARCHAR(20) NOT NULL,
  PRIMARY KEY (Airline_ID)
);

CREATE TABLE Flights
(
  Flight_no VARCHAR(20) NOT NULL,
  Price INT NOT NULL,
  Economy INT NOT NULL,
  Buisness INT NOT NULL,
  FirstClass INT NOT NULL,
  Airline_ID VARCHAR(20) NOT NULL,
  PRIMARY KEY (Flight_no),
  FOREIGN KEY (Airline_ID) REFERENCES Airlines(Airline_ID)
);

CREATE TABLE Passes
(
  Flight_no VARCHAR(20),
  Airport_ID_Src VARCHAR(20) ,
  Airport_ID_Dst VARCHAR(20) ,
  DepartureTime VARCHAR(20) ,
  ArrivalTime VARCHAR(20) ,
  DepartureDays VARCHAR(20) ,
  ArrivalDays VARCHAR(20),
  PRIMARY KEY (Airport_ID_Dst,Airport_ID_Src,Flight_no,ArrivalTime,DepartureTime,DepartureDays,ArrivalDays),
  FOREIGN KEY (Airport_ID_Dst) REFERENCES Airport(Airport_ID),
  FOREIGN KEY (Airport_ID_Src) REFERENCES Airport(Airport_ID),
  FOREIGN KEY (Flight_no) REFERENCES Flights(Flight_no)
);

CREATE TABLE Ticket
(
  Booking_Status INT ,
  Cancellation_Status INT ,
  RefundStatus INT ,
  CancellationTime timestamp NULL default NULL,
  Ticket_ID VARCHAR(20) ,
  Class INT ,
  BookingTime timestamp NULL default NULL,
  Src VARCHAR(20) ,
  Dst VARCHAR(20) ,
  Flight_no VARCHAR(20) ,
  User_ID VARCHAR(20) ,
  PassengerName VARCHAR(20) ,
  PassengerEmail VARCHAR(20),
  PassengerContact VARCHAR(20),
  ArrivalTime timestamp NULL default NULL,
  DepartureTime timestamp NULL default NULL,
  PRIMARY KEY (Ticket_ID),
  FOREIGN KEY (Flight_no) REFERENCES Flights(Flight_no),
  FOREIGN KEY (User_ID) REFERENCES Users(User_ID)
);


CREATE TABLE Payment
(
  Payment_ID INT NOT NULL AUTO_INCREMENT,
  Account_credited VARCHAR(20) NOT NULL,
  Account_debited VARCHAR(20) NOT NULL,
  TimeOfPayment timestamp NULL default NULL,
  ModeOfPayment VARCHAR(20) NOT NULL,
  Amount INT NOT NULL,
  Ticket_ID VARCHAR(20) NOT NULL,
  FOREIGN KEY (Ticket_ID) REFERENCES Ticket(Ticket_ID),
  PRIMARY KEY (Payment_ID)
);



-- Entries


--Airport
LOAD DATA LOCAL INFILE 'C:\\Users\\hp\\Desktop\\airport-codes.csv' INTO TABLE
Airport FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' 
IGNORE 1 LINES (@col1,@col2,@col3,@col4,@col5,@col6) SET Name = @col3, City=@col1, Airport_ID=@col2,Country=@col4;
DELETE FROM Airport where Country!='India';

--Airlines
INSERT INTO Airlines VALUES
('Air Asia','I5'),
('Air India','AI'),
('Alliance Air','AAS'),
('Go Air','GOW'),
('Indigo','IND'),
('SpiceJet','SG'),
('Star Air','OG'),
('TrueJet','TRJ'),
('Vistara','UK'),
('Deccan Charters','DN'),
('Pawan Hans','PH');

--Flights 
LOAD DATA  INFILE 'C:\\Users\\hp\\Desktop\\project_data\\Indigo_WS2019-converted.csv' IGNORE INTO TABLE
Flights FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' 
IGNORE 1 LINES (@col1,@col2,@col3) SET Flight_no = @col2,Airline_ID=@col3;

--Passes

delimiter $$
CREATE TRIGGER before_temp_insert 
before insert on temp 
for each row
begin
DECLARE rowcount INT;
DECLARE A_ID VARCHAR(20);
SELECT COUNT(*) INTO rowcount FROM Airport where City=new.Serial_No;
IF rowcount > 0 THEN
Update str set ID = (SELECT Airport_ID from Airport where City = new.Serial_No);
END IF;
SELECT ID INTO A_ID FROM str;
IF new.ArrivalFrom='' THEN
SET new.ArrivalFrom = A_ID;
END IF;
IF new.DestinationTo='' THEN
SET new.DestinationTo = A_ID;
END IF;
end $$
delimiter ;

drop table if exists temp;

CREATE TABLE temp(Serial_No VARCHAR(20),Flight_no VARCHAR(20),Frequency VARCHAR(20),ArrivalFrom VARCHAR(20),
ArrivalTime VARCHAR(20) ,DestinationTo VARCHAR(20) ,DepartureTime VARCHAR(20) ,PRIMARY KEY(Flight_no,Frequency,ArrivalFrom,ArrivalTime,DestinationTo,DepartureTime));


LOAD DATA  INFILE 'C:\\Users\\hp\\Desktop\\project_data\\SpiceJet_WS2019-converted.csv' IGNORE INTO TABLE
temp FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' 
IGNORE 1 LINES (@col1,@col2,@col3,@col4,@col5,@col6,@col7,@col8,@col9)
SET Serial_No =@col1,Flight_no = @col2, Frequency=@col5,ArrivalFrom=@col6,ArrivalTime=@col7,
DestinationTo =@col8,DepartureTime=@col9;

Update temp set Frequency = '1234567' where Frequency='Daily';


INSERT IGNORE INTO Passes(DepartureTime,Days,Airport_ID_Src,Airport_ID_Dst,Flight_no) SELECT DepartureTime,Frequency,ArrivalFrom,DestinationTo,Flight_no 
from temp where ArrivalTime='';


INSERT IGNORE INTO Passes(ArrivalTime,Days,Airport_ID_Src,Airport_ID_Dst,Flight_no)   SELECT ArrivalTime,Frequency,ArrivalFrom,DestinationTo,Flight_no 
from temp where DepartureTime='';

INSERT INTO Passes1 SELECT * FROM Passes;

INSERT INTO Passes SELECT Passes1.Flight_no,Passes1.Airport_ID_Src,Passes1.Airport_ID_Dst,
Passes1.DepartureTime,Passes2.ArrivalTime,Passes1.Days,Passes2.Days  FROM Passes1,Passes2 
WHERE Passes1.Flight_no=Passes2.Flight_no && Passes1.Airport_ID_Src=Passes2.Airport_ID_Src &&
Passes1.Airport_ID_Dst = Passes2.Airport_ID_Dst && Passes1.ArrivalTime='' && Passes2.DepartureTime='';


delimiter //
CREATE FUNCTION isValid(ArrivalDays VARCHAR(20),DepartureDays VARCHAR(20)) RETURNS INT DETERMINISTIC
BEGIN 
DECLARE INT x=0;
IF Position('1' in ArrivalDays) && Position('1' in DepartureDays) THEN SET x=1;
END IF;
IF Position('2' in ArrivalDays) && Position('2' in DepartureDays) THEN SET x=1;
END IF;
IF Position('3' in ArrivalDays) && Position('3' in DepartureDays) THEN SET x=1;
END IF;
IF Position('4' in ArrivalDays) && Position('4' in DepartureDays) THEN SET x=1;
END IF;
IF Position('5' in ArrivalDays) && Position('5' in DepartureDays) THEN SET x=1;
END IF;
IF Position('6' in ArrivalDays) && Position('6' in DepartureDays) THEN SET x=1;
END IF;
IF Position('7' in ArrivalDays) && Position('7' in DepartureDays) THEN SET x=1;
END IF;
RETURN x;
END //
delimiter ;

DELETE FROM Passes where isValid(ArrivalDays,DepartureDays)=0;


-- Query for direct flights btw source and dst
SELECT Flight_no,DepartureTime,ArrivalTime FROM Passes where Airport_ID_Src='COK' && Airport_ID_Dst='AGX' 
&& Position('2' in ArrivalDays) && Position('2' in DepartureDays); 


--Query for 1-stop flights btw source and dst
SELECT Table1.Flight_no,Table1.DepartureTime,Table1.ArrivalTime,Table1.Airport_ID_Dst,Table2.Flight_no,Table2.DepartureTime,Table2.ArrivalTime FROM Passes as Table1, Passes as Table2 
WHERE Table1.Airport_ID_Dst = Table2.Airport_ID_Src  && Table1.Airport_ID_Src='DEL' && Table2.Airport_ID_Dst='CCU'
&& Position('2' in Table1.DepartureDays) && Position('2' in Table1.ArrivalDays) && Position('2' in Table2.DepartureDays)
&& Position('2' in Table2.DepartureDays) && time(Table1.ArrivalTime)<(Table2.DepartureTime);

------

-- Check Availiblity 


SELECT Flights.Economy from Flights where Flights.Flight_no='I5 948';
SELECT count(*) from Ticket where Ticket.Flight_no='I5 948' && Ticket.ArrivalTime='00:05' && Ticket.DepartureTime='01:25' && Ticket.Class=0;
;


-- Booking
$Ticket_ID = SELECT Airline_ID FROM Flights where Flight_no = 'I5 948';
$var = SELECT count(*) from Ticket where Flight_no = 'I5 948';
$Ticket_ID = $Ticket_ID." ".$var;
-- how to find User_ID

insert into Ticket VALUES(1,0,0,NULL,'Economy',currtime(),'src','dst','I5 948','User_ID',
'Name','Email','7777777','ArrivalTime','DepartureTime');

--reservation payment
insert into Payment(Account_credited,Account_debited,TimeOfPayment,ModeOfPayment,Amount,Ticket_ID) 
  VALUES((SELECT Account_No from Flights where Flight_no='I5 948'),'$Account_debited',currtime(),'Mode','$amount','$Ticket_ID');


-- cancellation payment
$DepartureTime = SELECT DepartureTime from Ticket where Ticket_ID = '$Ticket_ID';
if($DepartureTime - currtime >= minTime)
{
Update Ticket set Cancellation_Status = '1' and CancellationTime='currtime' and BookingStatus = '0' where Ticket_ID = "$Ticket_ID";
-- send request to Airline to refund
}
else
echo "Cannot be cancelled";