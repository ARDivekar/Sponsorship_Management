<?php 


require('DBconnect.php'); //does the same as the commented code below:

/*I got all the following by:
- Making my database in excel
- Saving as a CSV files (after removing the first row, which contains the column names, as I don't want that in my database)
- Setting up my table
- Importing the CSV into the corresponding table
- Exporting the the table as an SQL file, thus getting the queires
- Making them PHP queries and using mysql_query()
*/


$Event_insert_all="
	INSERT INTO `Event` (`SponsorshipOrganization`, `EventName`, `StartDate`, `EndDate`) VALUES
	('Technovanza', 'Technovanza', '2016-12-28', '2017-01-10');
";



$CommitteeMember_insert_all ="
	INSERT INTO `CommitteeMember` (`ID`, `EventID`, `Name`, `Department`, `Role`, `Mobile`, `Email`, `Year`, `Branch`) VALUES
	(121010001, 1, 'Parshva Shah ', 'Sponsorship', 'Sector Head', '8767121355', 'parshvashah2310@gmail.com', 3, 'Civil'),
	(121020001, 1, 'Prajakta Kulkarni ', 'Sponsorship', 'Sector Head', '9757349844', 'kprajakta29@gmail.com', 3, 'Mechanical'),
	(121020002, 1, 'Virkar Ashvini ', 'Sponsorship', 'Sector Head', '9594280472', 'ashwinivirkargpm@gmail.com', 3, 'Mechanical'),
	(121030001, 1, 'Pranay Patil ', 'Sponsorship', 'Sector Head', '9987163307', 'pranay.patil0@gmail.com', 3, 'Electrical'),
	(121030002, 1, 'Ganesh Kadarwal ', 'Sponsorship', 'Sector Head', '9673828538', 'ganeshkadarwad@gmail.com', 3, 'Electrical'),
	(121050001, 1, 'Rahul Rohra ', 'Sponsorship', 'Sector Head', '8097960273', 'rahulrohra01@gmail.com', 3, 'Textile'),
	(121060001, 1, 'Mohil Mehta ', 'Sponsorship', 'Sector Head', '9619642420', 'mohil95@hotmail.com', 3, 'Electronics'),
	(121060002, 1, 'Rohit Patil ', 'Sponsorship', 'Sector Head', '9405183330', 'rohitpatilvjti@gmail.com', 3, 'Electronics'),
	(121060003, 1, 'Pavan Birajdar ', 'Sponsorship', 'Sector Head', '9921440645', 'pavanbirajdar22@gmail.com', 3, 'Electronics'),
	(121060004, 1, 'Shaswat Desai ', 'Sponsorship', 'Sector Head', '9987331723', 'shasvat.desai@gmail.com', 3, 'Electronics'),
	(121060005, 1, 'Mulla Samin ', 'Sponsorship', 'Sector Head', '8452969901', 'samirraw@gmail.com', 3, 'Electronics'),
	(121070001, 1, 'Chaitanya Mahajan ', 'Sponsorship', 'Sector Head', '8793884806', 'chaitanya.mahajan13@gmail.com', 3, 'Comps'),
	(121080001, 1, 'Rishabd Dhoke ', 'Sponsorship', 'Sector Head', '7506137891', 'rishabhdhoke44@gmail.com', 3, 'IT'),
	(121080002, 1, 'Rahul Jeswani ', 'Sponsorship', 'Sector Head', '8007414276', 'rahuljeswani1995@gmail.com', 3, 'IT'),
	(121080003, 1, 'Soham Gandhi ', 'Sponsorship', 'Sector Head', '8805176637', 'sohamgandhi95@gmail.com', 3, 'IT'),
	(121090001, 1, 'Akash Janjal ', 'Sponsorship', 'Sector Head', '7506307407', 'akashjanjal7@gmail.com', 3, 'EXTC'),
	(131010003, 1, 'Mohan Sharma ', 'Sponsorship', 'Sponsorship Representative', '9022319471', 'mohan.sharma1092@gmail.com', 2, 'Civil'),
	(131010004, 1, 'Parth Parekh ', 'Sponsorship', 'Sponsorship Representative', '7506374795', 'parekhp1995@gmail.com', 2, 'Civil'),
	(131010006, 1, 'Ansari Khalid ', 'Sponsorship', 'Sponsorship Representative', '9967190263', 'ansarimohammadkhalid02@gmail.com', 2, 'Civil'),
	(131020006, 1, 'Riddhish Shah ', 'Sponsorship', 'Sponsorship Representative', '9664541489', 'shahriddhish1995@gmail.com', 2, 'Mechanical'),
	(131020007, 1, 'Siddhant Shah ', 'Sponsorship', 'Sponsorship Representative', '9930811934', 'siddhantnexus@gmail.com', 2, 'Mechanical'),
	(131020008, 1, 'Aayush Shah ', 'Sponsorship', 'Sponsorship Representative', '9769802616', 'coolboy9507@gmail.com', 2, 'Mechanical'),
	(131020012, 1, 'Sandip Pawar ', 'Sponsorship', 'Sponsorship Representative', '8888312768', 'sandipspawar88@gmail.com', 2, 'Mechanical'),
	(131030004, 1, 'Mehul Jain ', 'Sponsorship', 'Sponsorship Representative', '9168288742', 'mehuljain53@gmail.com', 2, 'Electrical'),
	(131040002, 1, 'Dhoomil Sheta ', 'Sponsorship', 'Sponsorship Representative', '9821363504', 'dbsheta@gmail.com', 2, 'Production'),
	(131050003, 1, 'Yash Mehta ', 'Sponsorship', 'Sponsorship Representative', '9757200894', 'yashmehta102@yahoo.in', 2, 'Textile'),
	(131050004, 1, 'Prathamesh Mhatre ', 'Sponsorship', 'Sponsorship Representative', '9920322970', 'prathameshmhatre48@gmail.com', 2, 'Textile'),
	(131050006, 1, 'Sushant Gaikwad ', 'Sponsorship', 'Sponsorship Representative', '9029416775', 'sushantgaikwad95@gmail.com', 2, 'Textile'),
	(131050007, 1, 'Saurabh Bhoy ', 'Sponsorship', 'Sponsorship Representative', '8793269530', 'saurabh.bhoy910@gmail.com', 2, 'Textile'),
	(131070002, 1, 'Anisha Motwani ', 'Sponsorship', 'Sponsorship Representative', '9757392237', 'anishamotwani16@gmail.com', 2, 'Comps'),
	(131070003, 1, 'Rushabh Patel ', 'Sponsorship', 'Sponsorship Representative', '9029312056', 'rushabhsp95@gmail.com', 2, 'Comps'),
	(131070004, 1, 'Parag Pachute ', 'Sponsorship', 'Sponsorship Representative', '8698030488', 'paragpachpute3@gmail.com', 2, 'Comps'),
	(131070006, 1, 'Isaivani Mathiyalagan', 'Sponsorship', 'Sponsorship Representative', '8898605263', 'isaimathiyalagan@gmail.com', 2, 'Comps'),
	(131080007, 1, 'Chetali Mahore ', 'Sponsorship', 'Sponsorship Representative', '9969586242', 'cutiechetali95@gmail.com', 2, 'IT'),
	(131080008, 1, 'Kajal Janghale ', 'Sponsorship', 'Sponsorship Representative', '8652112696', 'rajput.kajal1@gmail.com', 2, 'IT'),
	(131080012, 1, 'Madhura Tote ', 'Sponsorship', 'Sponsorship Representative', '9870712261', 'madhura201990@gmail.com', 2, 'IT'),
	(131080013, 1, 'Samvanshi Shital ', 'Sponsorship', 'Sponsorship Representative', '8691882532', 'somvanshi.shital19@gmail.com', 2, 'IT'),
	(131090002, 1, 'Priyanka Rajpal ', 'Sponsorship', 'Sponsorship Representative', '9769184355', 'pyka.rjpl@gmail.com', 2, 'EXTC'),
	(131090003, 1, 'Darshil Gada ', 'Sponsorship', 'Sponsorship Representative', '9702882767', 'darshilgada@yahoo.in', 2, 'EXTC'),
	(131090004, 1, 'Abhijit Gupta ', 'Sponsorship', 'Sponsorship Representative', '8879433235', 'guptaabhijit31@gmail.com', 2, 'EXTC'),
	(131090005, 1, 'Sarup Dhalwani ', 'Sponsorship', 'Sponsorship Representative', '8652122697', 'dalwanisarup@gmail.com', 2, 'EXTC'),
	(131090006, 1, 'Nishant Shah ', 'Sponsorship', 'Sponsorship Representative', '9930784543', 'shahnishant95@gmail.com', 2, 'EXTC'),
	(131090007, 1, 'Kaveri Kothe ', 'Sponsorship', 'Sponsorship Representative', '9619254013', 'kaverikothe1995@gmail.com', 2, 'EXTC'),
	(131090008, 1, 'Jayesh Bolke ', 'Sponsorship', 'Sponsorship Representative', '7776998441', 'jayesh.bolke@gmail.com', 2, 'EXTC'),
	(141010002, 1, 'Supriya Kakade ', 'Sponsorship', 'Sponsorship Representative', '9870079790', 'rgkakade@gmail.com', 1, 'Civil'),
	(141010005, 1, 'Apeksha Kirdat ', 'Sponsorship', 'Sponsorship Representative', '9892990302', 'apekshakirdat@gmail.com', 1, 'Civil'),
	(141020003, 1, 'Shailee Vora ', 'Sponsorship', 'Sponsorship Representative', '9967137615', 'shaileevora25@gmail.com', 1, 'Mechanical'),
	(141020004, 1, 'Komal Pingle ', 'Sponsorship', 'Sponsorship Representative', '9930531638', 'pinglejayant091@gmail.com', 1, 'Mechanical'),
	(141020005, 1, 'Rupali Gawali ', 'Sponsorship', 'Sponsorship Representative', '8082025103', 'rupaligawali95@gmail.com', 1, 'Mechanical'),
	(141020009, 1, 'Nidhit Pimple ', 'Sponsorship', 'Sponsorship Representative', '8149865655', 'iamnidhit1994pimple@gmail.com', 1, 'Mechanical'),
	(141020010, 1, 'Mridang Agarwal ', 'Sponsorship', 'Sponsorship Representative', '9930126019', 'mridang1611@gmail.com', 1, 'Mechanical'),
	(141020011, 1, 'Manan Shah ', 'Sponsorship', 'Sponsorship Representative', '9820657980', 'mananshah18111995@gmail.com', 1, 'Mechanical'),
	(141030003, 1, 'Chetana Patel ', 'Sponsorship', 'Sponsorship Representative', '9769761964', 'chetanapatel_26@yahoo.com', 1, 'Electrical'),
	(141030005, 1, 'Yash Rathi ', 'Sponsorship', 'Sponsorship Representative', '8087569097', 'yashrathi2511@gmail.com', 1, 'Electrical'),
	(141040001, 1, 'Saima Memon ', 'Sponsorship', 'Sponsorship Representative', '9819849201', 'saimamemon26@gmail.com', 1, 'Production'),
	(141040003, 1, 'Abhay Kamath ', 'Sponsorship', 'Sponsorship Representative', '9920622593', 'abhkamath@gmail.com', 1, 'Production'),
	(141050002, 1, 'Shama Kamat ', 'Sponsorship', 'Sponsorship Representative', '9930799011', 'shamak22430@gmail.com', 1, 'Textile'),
	(141050005, 1, 'Shubham Boob ', 'Sponsorship', 'Sponsorship Representative', '9833247892', 'shubhamboob1995@gmail.com', 1, 'Textile'),
	(141060006, 1, 'Sonia Martis ', 'Sponsorship', 'Sponsorship Representative', '9920179561', 'sonia_martis@yahoo.co.in', 1, 'Electronics'),
	(141060007, 1, 'Mayuri Thakare ', 'Sponsorship', 'Sponsorship Representative', '9004105075', 'mayuri30.vjti@gmail.com', 1, 'Electronics'),
	(141060008, 1, 'Kirti Narbag ', 'Sponsorship', 'Sponsorship Representative', '8082145319', 'narbagkirti@gmail.com', 1, 'Electronics'),
	(141060009, 1, 'Sandesh Shetty ', 'Sponsorship', 'Sponsorship Representative', '9004971452', 'sandeshshetty95@gmail.com', 1, 'Electronics'),
	(141060010, 1, 'Ajitesh Chandak ', 'Sponsorship', 'Sponsorship Representative', '8655495970', 'ajiteshchandak@gmail.com', 1, 'Electronics'),
	(141060011, 1, 'Paras Avkirkar ', 'Sponsorship', 'Sponsorship Representative', '7506055572', 'dhavalavkirkar6699@gmail.com', 1, 'Electronics'),
	(141070005, 1, 'Rutvik Parekh ', 'Sponsorship', 'Sponsorship Representative', '9833477415', 'rutvik_parekh87@yahoo.in', 1, 'Comps'),
	(141080004, 1, 'Vritti Rohira ', 'Sponsorship', 'Sponsorship Representative', '9821594514', 'vritti.rohira@gmail.com', 1, 'IT'),
	(141080005, 1, 'Richa Deshmukh ', 'Sponsorship', 'Sponsorship Representative', '9930539242', 'richa.deshmukh@yahoo.co.in', 1, 'IT'),
	(141080006, 1, 'Pratiksha Tipre ', 'Sponsorship', 'Sponsorship Representative', '8655721794', 'pratiksha.0073@gmail.com', 1, 'IT'),
	(141080009, 1, 'Chinmay Karmarkar ', 'Sponsorship', 'Sponsorship Representative', '9820855892', 'karmalkarchinmay@gmail.com', 1, 'IT'),
	(141080010, 1, 'Prathmesh Dahale ', 'Sponsorship', 'Sponsorship Representative', '7588706288', 'prathameshdahale@gmail.com', 1, 'IT'),
	(141080011, 1, 'Swachand Lokhande ', 'Sponsorship', 'Sponsorship Representative', '9930616962', 'swachhand95@gmail.com', 1, 'IT'),

	(131080022, 1, 'Harshita Bhosle', 'Management', 'Event Organizer', '9819712390', 'harshitabhosle@gmail.com', 3, 'IT'),
	(131080052, 1, 'Shivani Shinde', 'Management', 'Event Organizer', '9819712290', 'shivanirulez@gmail.com', 3, 'IT'),

	(131080051, 1, 'Abhishek Divekar', 'Sponsorship', 'CSO', '9819712190', 'abhishek.r.divekar@gmail.com', 3, 'IT'),
	(131080053, 1, 'Advik Shetty', 'Sponsorship', 'CSO', '9967240818', 'adviksshetty@gmail.com', 3, 'IT'),
	(131080055, 1, 'Janit Mehta', 'Sponsorship', 'CSO', '9920059045', 'janithmehta@gmail.com', 3, 'IT');
";



$Company_insert_all ="
	INSERT INTO `Company` (`CMPName`, `CMPStatus`, `Sector`, `CMPAddress`) VALUES
	('3i Infotech', 'Not called', 'IT', '6th Floor,Akruti Centre Point, M.I.D.C Central Road,  Next to Marol Telephone Exchange, Mumbai, Maharashtra 400093'),
	('ABB', 'Not interested', 'Electricals', 'Shah Industrial Estate; Janaki Centre, Mumbai'),
	('Adidas', 'Not interested', 'Clothes Retail', 'Lake Boulevard Road, Hiranandani Gardens, Mumbai'),
	('Afreen Music Company', 'Not interested', 'Music Stores', 'Holy Cross Road ,i.c.Colony,Borivali West, Mumbai'),
	('Aftek', 'Not called', 'IT', '216/A, Second Floor, Prabhadevi Industrial Estate, The Enterprises Co-operative Society Ltd, 408, Veer Saverkar Marg, Prabhadevi, Mumbai - 400 025, India'),
	('Alstom T & D', 'Not called', 'Electricals', '1st Floor, Vedant Delux,Plot No. 38A, Ram Krishna Co-op Society,Narendra Nagar, Nagpur-440010'),
	('American Megtrends India', 'Not called', 'IT', 'Kumaran Nagar, Semmenchery, Chennai - 600 119 India'),
	('Apollo Tyres', 'Not called', 'Tyres', 'Apollo House, 7 Institutional Area, Sector 32, Gurgaon 122001'),
	('Armada Records & Co.', 'Sponsored', 'Music Stores', 'Carter Road, Bandra West,Mumbai'),
	('Attari Travels', 'Not interested', 'Travel', 'Plot No 70, Room No 23, Gate No 7, Malwani Colony-Malad'),
	('Bajaj Electricals', 'Not interested', 'Electricals', '45/47, Veer Nariman Road, Mumbai-400001'),
	('Balkrishna Industries', 'Not called', 'Tyres', 'BKT House, C/15, Trade World, Kamala Mills Compound, Senapati Bapat Marg, Lower Parel (W), Mumbai ? 400013'),
	('Bharat Heavy Electricals', 'Not called', 'Electricals', 'BHEL House, Siri Fort, New Delhi - 110049, India.'),
	('BIG FM 92.7', 'Sponsored', 'Radio', '401, 4th Floor, Infiniti Mall, Oshiwara, New Link Road, Andheri West, Mumbai ? 400053'),
	('Biggaddi.com', 'Not interested', 'Ecommerce', 'NS phadke mg, Andheri East'),
	('Bollywood Music Company', 'Not called', 'Music Stores', 'Grant Road (E), Tribhuwan Marg, Girgaon, Mumbai'),
	('CEAT', 'Sponsored', 'Tyres', '509-510 Fifth Floor, Shop Zone, M.G.Road, Ghatkopar (W), Mumbai - 400 086'),
	('Chandan Steel Industries', 'Sponsored', 'Steel', 'N. S Patkar Marg, Grantroad West, Mumbai'),
	('Coca Cola', 'Not called', 'FMCG', 'One Coca-Cola Plaza, Atlanta, GA 30313'),
	('Cognizant India', 'Sponsored', 'IT', '12th & 13th Floor, A wing, Kensington Building, Hiranandani Business Park, Powai, Mumbai - 400 076   '),
	('Cotton Cottage', 'Not interested', 'Clothes Retail', 'Khar Dadar RD, Khar(W), Mumbai'),
	('Crompton Greaves', 'Not called', 'Electricals', 'CG House, 6th Floor, Dr. Annie Besant Road, Worli, Mumbai - 400 030    '),
	('Dim Mak Records', 'Not called', 'Music Stores', 'KL Walawalkar Marg, Phase D, Andheri West, Mumbai'),
	('DRJ Records & Music Co.', 'Not called', 'Music Stores', 'Link Road, Andheri (West) Mumbai, Mumbai'),
	('Eason Reyrolle', 'Not called', 'Electricals', '6th Floor, Temple Tower, 672, Anna Salai, Nandanam, Chennai - 600 035, INDIA'),
	('Essar Steel Ltd', 'Not interested', 'Steel', 'Keshavrao Khadye Marg, Mahalaxmi, Mumbai'),
	('Fabindia', 'Sponsored', 'Clothes Retail', 'Lal Bahadur Shastri Marg, Kurla'),
	('Falcon Tyres', 'Not interested', 'Tyres', 'K.R.S. Road, Metagalli, Mysore ? 570016, India'),
	('Fever 104', 'Not called', 'Radio', 'Tower 2, 7th Floor, Senapati Bapat Marg, Elphinstone Road, Mumbai - 400 013'),
	('Flipkart', 'Not called', 'Ecommerce', 'Suren Road, Andheri East'),
	('Friendship Sarees', 'Not called', 'Clothes Retail', 'Station Road, Santacruz West, Mumbai'),
	('Gajra Home makers Pvt Ltd', 'Not called', 'Builders', 'Dana Bazar, Apmc Market'),
	('Galazy Tours And Travels', 'Not called', 'Travel', 'Rabodi Apartment, Thane West, Thane - 400601'),
	('Ganraj Construction', 'Sponsored', 'Builders', 'Kalyan Road, Dombivli East'),
	('Goel Steel', 'Not interested', 'Steel', '89A, M.T.H Road, Ambattur Industrial Estate,Mumbai'),
	('Goodyear', 'Not called', 'Tyres', '1st Floor, ABW Elegance Tower, Jasola, New Delhi ? 110025'),
	('Gozoop Online Pvt. Ltd.', 'Not called', 'Ecommerce', 'Vakola, Santacruz East, Mumbai'),
	('GRL Tires', 'Not called', 'Tyres', '418, Creative Industrial Estate, Sitaram Mill Compound, 72-N. M. Joshi Marg, Lower Parel, Mumbai - 400 011 (India).'),
	('Gunjan Music Company', 'Not called', 'Music Stores', 'Dreamland Cinema, Grant Road (E), Girgaon, Mumbai'),
	('Heinz Co', 'Sponsored', 'FMCG', 'One PPG Place, Pittsburgh, PA 15219'),
	('Hershys Co', 'Not called', 'FMCG', '100 Crystal A Drive, Hershey, PA 17033'),
	('Hotel Sea Princess', 'Not called', 'Hotel', 'Juhu Tara Road, Juhu'),
	('IGATE', 'Not called', 'IT', 'IGATE Global Solutions Limited, Plot No: 158-162P & 165-170P, EPIP Phase II, Whitefield,  Bengaluru, 560066'),
	('ITSY', 'Not called', 'Ecommerce', 'Aarey Colony, Goregaon East, Mumbai,'),
	('J.D.Steel Corporation', 'Not called', 'Steel', 'Vertex Vikas Building, Near Railway Station, M V Road'),
	('Jvw', 'Not called', 'Ecommerce', 'Lt Dilip Gupte Marg, Shivaji Park, Mumbai'),
	('JW Marriott Hotel', 'Not called', 'Hotel', 'Juhu Tara Road, Juhu'),
	('Kalki', 'Not called', 'Clothes Retail', 'Swami Vivekanand Road, Santacruz West'),
	('Kellogs', 'Not called', 'FMCG', 'One Kellogg Square, Battle Creek, MI 49016-3599'),
	('Kesoram', 'Not called', 'Tyres', '9/1 R. N. Mukherjee Road, Kolkata ? 700 001'),
	('Krafts Co', 'Not called', 'FMCG', 'Three Lakes Drive, Northfield, IL 60093'),
	('Lloyds Steel Industries Limited', 'Not called', 'Steel', 'Mahalaxmi, Jacob Circle, Mumbai'),
	('Louis Philippe', 'Sponsored', 'Clothes Retail', 'SENAPATI BAPAT MARG, LOWER PAREL'),
	('Mahavir Steel Industries Ltd', 'Not called', 'Steel', 'Steel Centre, Ahmedabad Street,Mumbai'),
	('Mahindra Ugine Steel Company', 'Not interested', 'Steel', 'Jagdish Nagar, Khopoli - 410216, District - Raigad'),
	('Mars Inc.', 'Not interested', 'FMCG', '6885 Elm St., McLean, VA 22101-3810'),
	('McCain Co', 'Not called', 'FMCG', '359 King Street West, Fifth Floor, Toronto, Ontario, Canada'),
	('Metro Travels', 'Sponsored', 'Travel', '2/5, 1st Rabodi, Thane West, Thane - 400601'),
	('Michelin', 'Not interested', 'Tyres', 'No. 114, Greams Road, Chennai- 600006'),
	('Millionaire', 'Not called', 'Clothes Retail', 'Juhu Tara Road, Santacruz West, Mumbai,'),
	('Mphasis', 'Not called', 'IT', 'Bagmane World Technology Center, Marathahalli Outer Ring Road, Doddanakundi Village, Mahadevapura, Bangalore - 560 048.'),
	('Naitik Tours & Travels', 'Not called', 'Travel', 'Station Road, Bhandup West |'),
	('Nestle', 'Not interested', 'FMCG', '800 N. Brand Blvd., Glendale, CA 91203'),
	('Novotel Mumbai Juhu Beach', 'Not called', 'Hotel', 'Balraj Sahani Marg, Juhu'),
	('Onward', 'Not called', 'IT', '2nd Floor, Sterling Centre, Dr A.B. Road, Worli, Mumbai ? 400018'),
	('Oye 104.8 FM', 'Not called', 'Radio', '4TH Floor, Trade Avenue Building, Dr Suren Road, Opp. Land Mark Building, Chakala, Andheri-East,'),
	('Pancheel Tours', 'Not called', 'Travel', 'Shop No 16, Aashirwad Chs Ltd, Mrrda Marg, Andheri East'),
	('PepsiCo', 'Sponsored', 'FMCG', '700 Anderson Hill Road, Purchase, NY 10577-1444'),
	('Polaris', 'Not interested', 'IT', 'Polaris House, 244, Anna Salai Chennai - 600006, India.'),
	('PTL Enterprises', 'Not called', 'Tyres', 'Apollo House, 7, Institutional Area, Sector-32, Gurgaon - 122001 (Haryana)'),
	('Radio Mrichi 98.3', 'Not called', 'Radio', 'ENIL, Radio Mirchi, Matulya Centre, 4th floor, Senapati Bapat Marg, Lower Parel (W), Mumbai 400013'),
	('Radiocity 91.1', 'Not called', 'Radio', '5th Floor,RNA Corporate Park,Near Chetana College,Bandra East, Mumbai'),
	('RadioOne 94.3', 'Not called', 'Radio', '2nd Floor, Peninsula Centre, Dr. S.S Rao Road, Parel East, Mumbai - 400 012'),
	('Ralson', 'Not called', 'Tyres', 'Ralson Nagar, G.T. Road, Ludhiana - 141003, Punjab (INDIA)'),
	('Ram Travels And Tours', 'Not called', 'Travel', 'Nagar Bus Stop, Goregaon East'),
	('Red & Yellow Music', 'Not interested', 'Music Stores', 'Opp. Mega Mall, New Link Road, Andheri (West)'),
	('Red FM 93.5', 'Not called', 'Radio', 'B'' Wing, 3rd Floor, Sun Mill Compound, Lower Parel, Mumbai - 400013  '),
	('Ritu Kumar', 'Not interested', 'Clothes Retail', 'uhu Tara Road, Juhu, Mumbai, Maharashtra'),
	('Sahil Tours And Travels', 'Not called', 'Travel', 'Shop No 18, Western Express Highway, Vile Parle East'),
	('Schneider Electric', 'Not called', 'Electricals', '9th Floor, DLF Building No. 10, Tower C, DLF Cyber City, Phase II, Gurgaon ? 122002'),
	('Seasons', 'Not called', 'Clothes Retail', 'Danabhai Jewellers, Santacruz West, Mumbai'),
	('Siemens', 'sponsored', 'Electricals', '130, Pandurang Budhkar Marg, Worli, Maharashtra, Mumbai 400 018.'),
	('Snapdeal', 'Not called', 'Ecommerce', 'Lake Boulevard Road, Hiranandani Gardens, Mumbai'),
	('Steel Mart', 'Not called', 'Steel', 'Near Veena Killedar Industrial Estate, Mumbai'),
	('Stratedgy', 'Not called', 'Ecommerce', 'Hill road, Bandra West, Mumbai'),
	('Tejas Networks', 'Not interested', 'IT', '301, Sai Plaza, Junction of Jawahar Road, R.B. Mehta Marg, Opp. Ghatkopar Railway Station,?Ghatkopar (E), Mumbai - 400 077'),
	('The Lalit Hotel', 'Not interested', 'Hotel', 'Sahar Airport Road, Andheri East'),
	('The Park', 'Sponsored', 'Hotel', 'Sector 10, Cbd Belapur'),
	('The Raymond Shop', 'Not called', 'Clothes Retail', 'Ambedkar Garden, Mumbai, Maharashtra'),
	('The Regenza By Tunga', 'Not interested', 'Hotel', 'Sector 30 A, Vashi'),
	('Theme Music Co.', 'Not called', 'Music Stores', 'Jawahar Nagar, Road No. 12, Goreagon West, Mumbai'),
	('TransPacific Software Pvt. Ltd', 'Not called', 'Ecommerce', 'Behind MCA,Bandra East, Mumbai'),
	('TVS Tyres', 'Not called', 'Tyres', 'TVS SRICHAKRA LIMITED, VELLARIPATTI, MELUR TALUK, MADURAI - 625 122'),
	('Uttam Galva Steels Limited.', 'Not called', 'Steel', 'P D Mello Road, Carnac Bunder, Mumbai, Maharashtra'),
	('Vardhaman Group Of Company', 'Not interested', 'Builders', 'Viva College Road, Virar West'),
	('Varun Travels', 'Not called', 'Travel', 'Dadamiya bldg, Kurla West, Mumbai - 400070'),
	('Waterstones Hotel', 'Not called', 'Hotel', 'Sahar Road, Andheri East'),
	('Webmax Technologies', 'Sponsored', 'Ecommerce', 'Behind R city mall, Vikhroli (West), Mumbai'),
	('WebOne Creation', 'Not interested', 'Ecommerce', 'Aarey Colony, Goregaon East, Mumbai'),
	('Wipro Lightings', 'Not called', 'Electricals', 'Godrej Eternia, ''C'' Wing, 5th Floor, Old Pune-Mumbai Road, Wakadewadi, Pune ? 411005'),
	('WNS', 'Not called', 'IT', 'Plant No. 10 / 11, Gate No. 4, Godrej & Boyce Complex, Pirojshanagar, Vikhroli (West), Mumbai ? 400 079'),
	('Zenith Pcs', 'Not called', 'IT', 'Zenith Computers Ltd. Zenith House, 29 MIDC, Central Road,Andheri (E), Mumbai ? 400 093');
";



$CompanyExec_insert_all="
	INSERT INTO `CompanyExec` (`CEName`, `CMPName`, `CEMobile`, `CEEmail`, `CEPosition`) VALUES
	('Abhay Kamath ', 'Friendship Sarees', '022 2605 7801', 'abhkamath@gmail.com', 'Marketing Executive'),
	('Abhijeet Nachane ', 'Wipro Lightings', '020-66098700', 'Abhijeet.n@wipro.com', 'Marketing Officer'),
	('Abhijit Waje ', 'Lloyds Steel Industries Limited', '022 2308 0097', 'wajeabhijit66@gmail.com', 'Marketing Executive'),
	('Abhishek Sharma ', 'The Raymond Shop', '022 2521 1921', 'abhisharma95aes@gmail.com', 'Marketing Executive'),
	('Aditya Kale ', 'Kalki', '022 3267 8003', 'adityaskale@gmail.com', 'Marketing'),
	('Ajit Gulabchand ', 'Bharat Heavy Electricals', '+91 11 26493021', 'Ajit.gulabchand@bhel.com', 'Event Manager'),
	('Ajitesh Chandak ', 'TransPacific Software Pvt. Ltd', '022 3252 2901', 'ajiteshchandak@gmail.com', 'Marketing Manager'),
	('Akshay Bhambhani ', 'JW Marriott Hotel', '+(91)9861639304', 'Akshay_BB75@hotmail.com', 'Advertising Manager'),
	('Alka Agarwal ', 'Vardhaman Group Of Company', '+(91)9845106619', 'AlkaAgarwal@yahoo.com', 'Advertising Manager'),
	('Amruta Kamble ', 'Gunjan Music Company', '+(91)9961621651', 'amrutaprakash35@gmail.com', 'Marketing Officer'),
	('Ankita D Mali ', 'Mahavir Steel Industries Ltd', '022 2683 7310', 'mshrikant361@gmail.com', 'Advertising Manager'),
	('Ankita Malaney ', 'Gajra Home makers Pvt Ltd', '+(91)9861621651', 'MalaneyAnkita@gmail.com', 'Marketing'),
	('Ansari Khalid ', 'Essar Steel Ltd', '022 2495 0606', 'ansarimohammadkhalid02@gmail.com', 'Event Manager'),
	('Anuj Dharap ', 'Metro Travels', '+(91)9849173629', 'AnujDharap@gmail.com', 'Marketing'),
	('Apeksha Kirdat ', 'Flipkart', '022 2660 7492', 'apekshakirdat@gmail.com', 'Marketing Officer'),
	('Arnob Roy ', 'WNS', '+91 22 4095 210', 'arnob_roy@wns.in', 'Advertising Manager'),
	('Arun Rattan ', 'Naitik Tours & Travels', '+(91)9849180399', 'Arun.Rattan@yahoo.com', 'Marketing Executive'),
	('Ashok Jalan ', 'Siemens', '+91 22 3967 700', 'a_jalan@siemens.co.in', 'Marketing Executive'),
	('Ashok Vemuri ', 'IGATE', '+91 80 4104 000', 'avemuri@igatesolutions.com', 'Marketing Officer'),
	('Ayush Awasthi ', 'Attari Travels', '+(91)9849177405', 'AyushAwasthi@yahoo.com', 'Marketing Executive'),
	('Ayush Shah ', 'PepsiCo', '914-253-2000', ' Ayush_Shah @hotmail.com', 'Marketing Executive'),
	('Balakrishnan ', 'TVS Tyres', '0452 - 2420461', 'kbalakrishnan@tvsltd.com', 'Marketing Executive'),
	('Balu Ganesh Ayyar ', 'Mphasis', '+91 80 3352 500', 'bgayyar@mphasis.co.in', 'Marketing Executive'),
	('Bharat Jaitley ', 'Galazy Tours And Travels', '+(91)9845112534', 'Bharat.Jaitley79@gmail.com', 'Marketing'),
	('Bijoy Ghandhi ', 'Varun Travels', '+(91)9861636631', 'Bijoy_Ghandhi@gmail.com', 'Advertising Manager'),
	('C Dasgupta ', 'Goodyear', '0129-6611000', 'cdasgupta1@goodyear.co.in', 'Marketing Executive'),
	('Dalpat G Jain ', '3i Infotech', '022 6792 8000', 'dalpatgjain@3i-infotech.com', 'Marketing Executive'),
	('Deepa Shetty ', 'Sahil Tours And Travels', '+(91)9861620622', 'Deepa_Shetty@yahoo.com', 'Marketing Executive'),
	('Dhanshree Kolage ', 'Armada Records & Co.', '+(91)9949187582', 'dhanshreekolage@gmail.com', 'Marketing Executive'),
	('Drishti Lekhrajani ', 'The Park', '+(91)9866933000', 'Drishti_Lekhrajani_1981@gmail.com', 'Marketing Executive'),
	('Francisco D''souza ', 'Cognizant India', '+ 91-22-4422 80', 'francisco.dsouza@cognizantindia.com', 'Event Manager'),
	('Gayatri Mavani ', 'Hotel Sea Princess', '+(91)9849180309', 'GayatriDMavani@yahoo.com', 'Event Manager'),
	('Hardik Rana ', 'Fabindia', '022 6180 1456', 'hardikrana27@gmail.com', 'Marketing Executive'),
	('Harish Mehta ', 'Onward', '+91 22 6695 994', 'harish@onwards.com', 'Marketing Manager'),
	('Harish Patel ', 'Nestle', '818-549-6000', ' HarishPatel@gmail.com', 'Advertising Manager'),
	('Indu Shahani ', 'Crompton Greaves', '+91 22 2423 777', 'indu.sahani@cg-india.com', 'Marketing Executive'),
	('Isaivani ', 'Theme Music Co.', '+(91)9961621374', 'isaimathiyalagan@gmail.com', 'Marketing Executive'),
	('Jackie Matai ', 'McCain Co', '416-955-1700', 'JMatai1977@gmail.com', 'Marketing Manager'),
	('Janhavi Patole ', 'Dim Mak Records', '+(91)9961617144', 'janhavi.patole@rediffmail.com', 'Marketing'),
	('Jay Singha ', 'Polaris', '+91-44-3987 400', 'Jay.singha@polaris.com', 'Marketing Executive'),
	('Jyoti Kumar ', 'ABB', '+91 22 6671 727', 'jyoti.kumar99@abb.com', 'Marketing Executive'),
	('Kajol Galande ', 'Louis Philippe', '022 6617 2045', 'galandekajol55@gmail.com', 'Marketing Executive'),
	('Kaveri Kothe ', 'Afreen Music Company', '+(91)9945106619', 'kaverikothe1995@gmail.com', 'Marketing Executive'),
	('Kumar Sivrajan ', 'Zenith Pcs', '022-28377300', 'marketing@zenithpcs.com', 'Marketing Officer'),
	('Madhura Tote ', 'Biggaddi.com', '022 2495 7364', 'madhura201990@gmail.com', 'Marketing Manager'),
	('Mahaveer Bhansali ', 'Falcon Tyres', '+91-821-2582055', 'mahaveer.bhansali@falcontyres.com', 'Event Manager'),
	('Mahendra Jha ', 'Eason Reyrolle', '+91-44-24346425', 'sales@easonreyrolle.co.in', 'Marketing Executive'),
	('Mahesh Divekar ', 'Krafts Co', '847-646-2000', 'Mahesh_theboss_Divekar@yahoo.com', 'Marketing Executive'),
	('Manan Shah ', 'ITSY', '092213 00321', 'mananshah18111995@gmail.com', 'Marketing Executive'),
	('Mehul Shah ', 'GRL Tires', '+91-22-2309 564', 'mshah@grltires.com', 'Marketing Manager'),
	('Mitesh Swar ', 'Oye 104.8 FM', '+(91)9820215508', 'mitesh.swar@fortviewgroup.com', 'Marketing Executive'),
	('Mridang Agarwal ', 'WebOne Creation', '092213 00321', 'mridang1611@gmail.com', 'Event Manager'),
	('Murali R ', 'American Megtrends India', '[91] 44-6654092', 'muralir@ami.co.in', 'Marketing Executive'),
	('N Subramanian ', 'Radiocity 91.1', '91 22 6696 9100', 'nsubramanian@radiocity.com', 'Advertising Manager'),
	('Narendra Shinde ', 'Waterstones Hotel', '+(91)9849187582', 'NarendraPShinde@yahoo.com', 'Marketing Manager'),
	('Naveen Saxena ', 'BIG FM 92.7', '+(91)-22-306894', 'naveen.saxena@reliance-broadcasting.com', 'Advertising Manager'),
	('Neeta Khatib ', 'Heinz Co', '412-456-5700', 'Neeta.Khatib@hotmail.com', 'Marketing'),
	('Neetu Karnani ', 'Mars Inc.', '703-821-4900', 'NeetuKarnani1980@yahoo.com', 'Marketing'),
	('Neha Devdas ', 'Bollywood Music Company', '+(91)9967731586', 'nehadevdas238@gmail.com', 'Advertising Manager'),
	('Nidhit Pimple ', 'Seasons', '022 6145 9999', 'iamnidhit1994pimple@gmail.com', 'Marketing Executive'),
	('Nimesh Kulkanri ', 'Hershys Co', '717-534-4200', 'Nimesh_Kulkanri@gmail.com', 'Marketing Executive'),
	('Ojaswini Thakur ', 'RadioOne 94.3', '022 - 67015700', 'ojaswini.thakur@radioone.in', 'Marketing Executive'),
	('Onkar Chichkar ', 'Adidas', '098336 63322', 'onkarchichkar@yahoo.in', 'Event Manager'),
	('Paras Avkirkar ', 'Stratedgy', '099202 22098', 'dhavalavkirkar6699@gmail.com', 'Marketing Executive'),
	('Parul Mehta ', 'Coca Cola', '404-676-2121', 'ParulAMehta@hotmail.com', 'Marketing Officer'),
	('Paul Fernandes ', 'Apollo Tyres', '+91 124 2721000', 'paulf@apollotyres.com', 'Marketing Executive'),
	('Poonam Danse ', 'Chandan Steel Industries', '022 6543 6481', 'poonam.danse9@gmail.com', 'Marketing Executive'),
	('Poonam Jadhav ', 'Millionaire', '022 2660 4243', 'nandinijadhav75@gmail.com', 'Marketing'),
	('Prachi Sagwekar ', 'Goel Steel', '91-44-42914848', 'sagwekarp@gmail.com', 'Marketing Manager'),
	('Prakash Shiram ', 'Ganraj Construction', '+(91)9861615049', 'Prakash_Shiram@yahoo.com', 'Marketing'),
	('Pranay Jain ', 'Pancheel Tours', ' 22-49179714', 'JainPranay76@hotmail.com', 'Marketing'),
	('Pranoy Kapoor ', 'Fever 104', '022-33104104', 'Pranoy.kapoor@fever.fm', 'Marketing Officer'),
	('R P Singh ', 'Alstom T & D', '+91 674 2596439', 'rpsingh@alstomindia.com', 'Marketing Manager'),
	('R V Gupta ', 'Kesoram', '+ 91 33 2243 54', 'Raghavgupta@gmail.com', 'Marketing Executive'),
	('Rajeev Anand ', 'Michelin', '044-28292777', 'mrfshare@mrfmail.com', 'Advertising Manager'),
	('Ranjit Dhuru ', 'Aftek', '91-22-24211706', 'ranjit_dhuru@aftek.co.in', 'Marketing Manager'),
	('Ravina Vhalekar ', 'Uttam Galva Steels Limited.', '022 6656 3500', 'rvhalekar@gmail.com', 'Marketing Officer'),
	('Ray Fernandes ', 'The Lalit Hotel', '+(91)9861635346', 'RSFernandes@hotmail.com', 'Marketing Executive'),
	('Sammek Ovhal ', 'Cotton Cottage', '022 2605 4858', 'samikovhal@gmail.com', 'Marketing Officer'),
	('Samvanshi Shital ', 'Red & Yellow Music', '+(91)9961620622', 'ashwinivirkar1@gmail.com', 'Marketing Executive'),
	('Sandesh Shetty ', 'Webmax Technologies', '022 2517 3966', 'sandeshshetty95@gmail.com', 'Advertising Manager'),
	('Sandip Pawar ', 'Ritu Kumar', '022 6697 6932', 'sandipspawar88@gmail.com', 'Advertising Manager'),
	('Sanjay Nayak ', 'Tejas Networks', '+91 22 42498600', 'info@tejanetworks.com', 'Marketing'),
	('Sarvmeet Oberoi ', 'Radio Mrichi 98.3', '022 - 66620600', 'Sarvmeet.Oberoi@timesgroup.com', 'Advertising Manager'),
	('Saurabh Sehgal ', 'Red FM 93.5', '022 - 30935700', 'saurabh_sehgal@redfm.com', 'Marketing Officer'),
	('Seema Thappar ', 'PTL Enterprises', '(0124) ? 238300', 'seema.thappar@ptlenterprises.com', 'Marketing Executive'),
	('Sheeza Waghmare ', 'CEAT', '022-25100837', 'Sheeza.waghmare@ceat.in', 'Marketing Officer'),
	('Shital Bhangare ', 'Steel Mart', '022 2308 0096', 'shitalha.sb@gmail.com', 'Marketing Executive'),
	('Shreesha Ketkar ', 'Kellogs', '269-961-2000', 'ShreeshaCKetkar@hotmail.com', 'Marketing'),
	('Shubham Bob ', 'Gozoop Online Pvt. Ltd.', '022 6127 0416', 'shubhamboob1995@gmail.com', 'Advertising Manager'),
	('Sonali Gadkari ', 'DRJ Records & Music Co.', '+(91)9961636631', 'gadkarisonali949@gmail.com', 'Marketing Executive'),
	('Soumya Doshi ', 'Novotel Mumbai Juhu Beach', '+(91)9861634479', 'SoumyaDoshi@yahoo.com', 'Marketing Manager'),
	('Sudha Ravi ', 'Balkrishna Industries', '66663800', 'sudhar@balkrishnaltd.com', 'Marketing Officer'),
	('Suresh Chhada ', 'Schneider Electric', '+91 124 3940 40', 'suresh.chhada@schneider-electric.com', 'Event Manager'),
	('Sushant Gaikwad ', 'Mahindra Ugine Steel Company', '+91 9881124640', 'sushantgaikwad95@gmail.com', 'Marketing Executive'),
	('Sushma Prasad ', 'The Regenza By Tunga', '+(91)9861617144', 'Sushma_Prasad@yahoo.com', 'Marketing Executive'),
	('Swachand Lokhande ', 'Jvw', '098676 76363', 'swachhand95@gmail.com', 'Advertising Manager'),
	('Trisha Gupta ', 'Ram Travels And Tours', '+(91)9849180160', 'Trisha.G@hotmail.com', 'Marketing'),
	('V B Haribhakti ', 'Bajaj Electricals', '022-22043780', 'vb.haribhakti@bajaj_electricals.com', 'Marketing Manager'),
	('Vijaya Chintala ', 'J.D.Steel Corporation', '022 2683 7310', 'vijayachin15@gmail.com', 'Marketing'),
	('Yash Rathi ', 'Snapdeal', '092213 83728', 'yashrathi2511@gmail.com', 'Marketing'),
	('Yashwant Singh Yadav ', 'Ralson', '+91-161-2511501', 'marketing@ralson.com', 'Advertising Manager');

";



$SponsRep_insert_all="
	INSERT INTO `SponsRep` (`SponsID`, `Sector` , `DateAssigned`) VALUES
	(131010003, 'IT', '2015-01-01'),
	(131010004, 'Radio', '2015-01-01'),
	(131010006, 'Electricals', '2015-01-01'),
	(131020006, 'FMCG', '2015-01-01'),
	(131020007, 'Builders', '2015-01-01'),
	(131020008, 'Ecommerce', '2015-01-01'),
	(131020012, 'FMCG', '2015-01-01'),
	(131030004, 'Steel', '2015-01-01'),
	(131040002, 'Music Stores', '2015-01-01'),
	(131050003, 'Radio', '2015-01-01'),
	(131050004, 'Travel', '2015-01-01'),
	(131050006, 'Radio', '2015-01-01'),
	(131050007, 'Music Stores', '2015-01-01'),
	(131070002, 'Ecommerce', '2015-01-01'),
	(131070003, 'Clothes Retail', '2015-01-01'),
	(131070004, 'Electricals', '2015-01-01'),
	(131070006, 'Tyres', '2015-01-01'),
	(131080007, 'Steel', '2015-01-01'),
	(131080008, 'Clothes Retail', '2015-01-01'),
	(131080012, 'Hotel', '2015-01-01'),
	(131080013, 'Builders', '2015-01-01'),
	(131090002, 'Tyres', '2015-01-01'),
	(131090003, 'Electricals', '2015-01-01'),
	(131090004, 'Hotel', '2015-01-01'),
	(131090005, 'Tyres', '2015-01-01'),
	(131090006, 'IT', '2015-01-01'),
	(131090007, 'Hotel', '2015-01-01'),
	(131090008, 'IT', '2015-01-01'),
	(141010002, 'Music Stores', '2015-01-01'),
	(141010005, 'Travel', '2015-01-01'),
	(141020003, 'Clothes Retail', '2015-01-01'),
	(141020004, 'FMCG', '2015-01-01'),
	(141020005, 'Travel', '2015-01-01'),
	(141020009, 'Builders', '2015-01-01'),
	(141020010, 'IT', '2015-01-01'),
	(141020011, 'Radio', '2015-01-01'),
	(141030003, 'Builders', '2015-01-01'),
	(141030005, 'FMCG', '2015-01-01'),
	(141040001, 'Ecommerce', '2015-01-01'),
	(141040003, 'Music Stores', '2015-01-01'),
	(141050002, 'Hotel', '2015-01-01'),
	(141050005, 'Electricals', '2015-01-01'),
	(141060006, 'Tyres', '2015-01-01'),
	(141060007, 'IT', '2015-01-01'),
	(141060008, 'Radio', '2015-01-01'),
	(141060009, 'Steel', '2015-01-01'),
	(141060010, 'Clothes Retail', '2015-01-01'),
	(141060011, 'Ecommerce', '2015-01-01'),
	(141070005, 'FMCG', '2015-01-01'),
	(141080004, 'Music Stores', '2015-01-01'),
	(141080005, 'Steel', '2015-01-01'),
	(141080006, 'Electricals', '2015-01-01'),
	(141080009, 'Travel', '2015-01-01'),
	(141080010, 'Hotel', '2015-01-01'),
	(141080011, 'Tyres', '2015-01-01');

";



$SectorHead_insert_all="
	INSERT INTO `SectorHead` (`SponsID`, `Sector`) VALUES
	(121010001, 'Radio'),
	(121020001, 'Music Stores'),
	(121020002, 'FMCG'),
	(121030001, 'Tyres'),
	(121030002, 'Hotel'),
	(121050001, 'Radio'),
	(121060001, 'Steel'),
	(121060002, 'IT'),
	(121060003, 'Electricals'),
	(121060004, 'FMCG'),
	(121060005, 'Clothes Retail'),
	(121070001, 'IT'),
	(121080001, 'Clothes Retail'),
	(121080002, 'Ecommerce'),
	(121080003, 'Travel'),
	(121090001, 'Builders');

";



$Meeting_insert_all="

	INSERT INTO `Meeting` (`Date`, `Time`, `SponsID`, `MeetingType`, `CEName`, `CMPName`, `Outcome`, `Address`) VALUES
	('2015-01-01', '16:49:00', 141020003, 'Call', 'Kajol Galande', 'Louis Philippe', 'Followup Required Giving only 10,000', 'VJTI'),
	('2015-01-03', '14:11:00', 131040002, 'Call', 'Dhanshree Kolage', 'Armada Records & Co.', 'Followup Required after a few days sponsor interested', 'VJTI'),
	('2015-01-05', '11:34:00', 141060007, 'Call', 'Sandesh Shetty', 'Webmax Technologies', 'Followup Required sponsor needs 2 banners', 'VJTI'),
	('2015-01-06', '13:27:00', 131090005, 'Call', 'Sheeza Waghmare ', 'CEAT', 'Followup Required', 'VJTI'),
	('2015-01-07', '13:30:00', 131040002, 'Meet', 'Dhanshree Kolage', 'Armada Records & Co.', 'Very Interested Company', 'Carter Road, Bandra West,Mumbai'),
	('2015-01-08', '13:14:00', 141010002, 'Call', 'Samvanshi Shital ', 'Red & Yellow Music', 'Not Interested', 'VJTI'),
	('2015-01-09', '12:00:00', 131080008, 'Meet', 'Hardik Rana', 'Fabindia', 'Followup Required, wants branding of tshirt event ', 'VJTI'),
	('2015-01-11', '12:28:00', 131080007, 'Call', 'Poonam Danse', 'Chandan Steel Industries', 'Followup Required sponsor giving only 8000', 'VJTI'),
	('2015-01-14', '13:30:00', 141020003, 'Meet', 'Kajol Galande', 'Louis Philippe', 'Followup Required', 'SENAPATI BAPAT MARG, LOWER PAREL'),
	('2015-01-17', '10:36:00', 141010002, 'Call', 'Kaveri Kothe ', 'Afreen Music Company', 'Not Interested', 'VJTI'),
	('2015-01-18', '14:00:00', 131040002, 'Meet', 'Dhanshree Kolage', 'Armada Records & Co.', 'Followup Required want 4 banners', 'Carter Road, Bandra West,Mumbai'),
	('2015-01-20', '12:00:00', 141060007, 'Meet', 'Sandesh Shetty', 'Webmax Technologies', 'Followup Required', 'Behind R city mall, Vikhroli (West), Mumbai'),
	('2015-01-21', '12:39:00', 131030004, 'Call', 'Ansari Khalid ', 'Essar Steel Ltd', 'Not Interested', 'VJTI'),
	('2015-01-23', '14:38:00', 131080007, 'Call', 'Poonam Danse', 'Chandan Steel Industries', 'Followup Required ', 'VJTI'),
	('2015-01-24', '14:00:00', 141060007, 'Meet', 'Sandesh Shetty', 'Webmax Technologies', 'Unvailable For Meeting', 'Behind R city mall, Vikhroli (West), Mumbai'),
	('2015-01-25', '14:38:00', 141020003, 'Call', 'Kajol Galande', 'Louis Philippe', 'Followup Required , wants to be sole sponsor in sector', 'VJTI'),
	('2015-01-26', '13:30:00', 131090005, 'Meet', 'Sheeza Waghmare ', 'CEAT', 'Followup Required', '509-510 Fifth Floor, Shop Zone, M.G.Road, Ghatkopar (W), Mumbai - 400 086'),
	('2015-01-27', '10:14:00', 131040002, 'Call', 'Dhanshree Kolage', 'Armada Records & Co.', 'Unvailable For Meeting', 'VJTI'),
	('2015-01-28', '13:41:00', 131030004, 'Call', 'Sushant Gaikwad ', 'Mahindra Ugine Steel Company', 'Not Interested', 'VJTI'),
	('2015-01-30', '14:30:00', 131080008, 'Meet', 'Hardik Rana', 'Fabindia', 'Followup Required', 'Lal Bahadur Shastri Marg, Kurla'),
	('2015-02-01', '11:54:00', 141060007, 'Call', 'Sandesh Shetty', 'Webmax Technologies', 'Followup Required , wants 3 banners', 'VJTI'),
	('2015-02-03', '10:00:00', 131080007, 'Meet', 'Poonam Danse', 'Chandan Steel Industries', 'Very Interested Company', 'N. S Patkar Marg, Grantroad West, Mumbai'),
	('2015-02-05', '15:16:00', 131070003, 'Call', 'Sammek Ovhal ', 'Cotton Cottage', 'Not Interested', 'VJTI'),
	('2015-02-06', '14:00:00', 131080008, 'Meet', 'Hardik Rana', 'Fabindia', 'Followup Required', 'Lal Bahadur Shastri Marg, Kurla'),
	('2015-02-07', '12:58:00', 141020003, 'Call', 'Kajol Galande', 'Louis Philippe', 'Followup Required', 'VJTI'),
	('2015-02-08', '16:11:00', 131040002, 'Call', 'Dhanshree Kolage', 'Armada Records & Co.', 'Final Meeting , Sponsorship Confirmed', 'VJTI'),
	('2015-02-09', '11:35:00', 131080008, 'Call', 'Onkar Chichkar ', 'Adidas', 'Not Interested', 'VJTI'),
	('2015-02-10', '14:00:00', 131090005, 'Meet', 'Sheeza Waghmare ', 'CEAT', 'Followup Required , wants to title sponsor for IC engine event', '509-510 Fifth Floor, Shop Zone, M.G.Road, Ghatkopar (W), Mumbai - 400 086'),
	('2015-02-11', '12:00:00', 131080007, 'Meet', 'Poonam Danse', 'Chandan Steel Industries', 'Followup Required', 'N. S Patkar Marg, Grantroad West, Mumbai'),
	('2015-02-14', '14:12:00', 131070003, 'Call', 'Sandip Pawar ', 'Ritu Kumar', 'Not Interested', 'VJTI'),
	('2015-02-17', '14:53:00', 141060007, 'Call', 'Sandesh Shetty', 'Webmax Technologies', 'Followup Required', 'VJTI'),
	('2015-02-18', '15:13:00', 141060007, 'Call', 'Mridang Agarwal ', 'WebOne Creation', 'Not Interested', 'VJTI'),
	('2015-02-20', '13:30:00', 141020003, 'Meet', 'Kajol Galande', 'Louis Philippe', 'Followup Required , Company giving only 12500', 'SENAPATI BAPAT MARG, LOWER PAREL'),
	('2015-02-21', '15:16:00', 141080006, 'Call', 'Francisco D''souza ', 'Cognizant India', 'Followup Required , Sponsor wants a banner', 'VJTI'),
	('2015-02-23', '11:51:00', 131080007, 'Call', 'Poonam Danse', 'Chandan Steel Industries', 'Final Meeting , Sponsorship Confirmed', 'VJTI'),
	('2015-02-24', '15:59:00', 131080008, 'Call', 'Hardik Rana', 'Fabindia', 'Followup Required', 'VJTI'),
	('2015-02-25', '15:39:00', 131090006, 'Call', 'Madhura Tote ', 'Biggaddi.com', 'Not Interested', 'VJTI'),
	('2015-02-26', '12:37:00', 141020003, 'Call', 'Kajol Galande', 'Louis Philippe', 'Followup Required', 'VJTI'),
	('2015-02-27', '15:59:00', 131090002, 'Call', 'Rajeev Anand ', 'Michelin', 'Not Interested', 'VJTI'),
	('2015-02-28', '11:15:00', 131090002, 'Call', 'Jay Singha ', 'Polaris', 'Not Interested', 'VJTI'),
	('2015-02-28', '13:36:00', 131080008, 'Call', 'Hardik Rana', 'Fabindia', 'Followup Required', 'VJTI'),
	('2015-03-01', '11:19:00', 131010004, 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Followup Required', 'VJTI'),
	('2015-03-03', '13:30:00', 141020003, 'Meet', 'Kajol Galande', 'Louis Philippe', 'Followup Required , Sponsor wants a shop', 'SENAPATI BAPAT MARG, LOWER PAREL'),
	('2015-03-05', '16:49:00', 131020007, 'Call', 'Alka Agarwal ', 'Vardhaman Group Of Company', 'Not Interested', 'VJTI'),
	('2015-03-06', '12:00:00', 131080008, 'Meet', 'Hardik Rana', 'Fabindia', 'Followup Required', 'Lal Bahasdur Shastri Marg, Kurla'),
	('2015-03-07', '14:00:00', 141020003, 'Meet', 'Kajol Galande', 'Louis Philippe', 'Final Meeting , Sponsorship Confirmed', 'SENAPATI BAPAT MARG, LOWER PAREL'),
	('2015-03-08', '13:31:00', 131080012, 'Call', 'Sushma Prasad ', 'The Regenza By Tunga', 'Not Interested', 'VJTI'),
	('2015-03-09', '17:30:00', 141060007, 'Meet', 'Sandesh Shetty', 'Webmax Technologies', 'Followup Required', 'Behind R city mall, Vikhroli (West), Mumbai'),
	('2015-03-10', '13:12:00', 131020007, 'Call', 'Ray Fernandes ', 'The Lalit Hotel', 'Not Interested', 'VJTI'),
	('2015-03-11', '18:00:00', 131080008, 'Meet', 'Hardik Rana', 'Fabindia', 'Final Meeting , Sponsorship Confirmed', 'Lal Bahadur Shastri Marg, Kurla'),
	('2015-03-14', '16:28:00', 131080012, 'Call', 'Ayush Awasthi ', 'Attari Travels', 'Not Interested', 'VJTI'),
	('2015-03-17', '13:30:00', 141060007, 'Call', 'Sandesh Shetty', 'Webmax Technologies', 'Final Meeting , Sponsorship Confirmed', 'VJTI'),
	('2015-03-18', '12:32:00', 131010004, 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Unvailable For Meeting , Family Emergency', 'VJTI'),
	('2015-03-20', '13:23:00', 131090006, 'Call', 'Ashok Jalan ', 'Siemens', 'Followup Required', 'VJTI'),
	('2015-03-21', '15:42:00', 131010004, 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Followup Required , Company wants to become title sponsor', 'VJTI'),
	('2015-03-23', '13:30:00', 141020004, 'Call', 'Ayush Shah ', 'PepsiCo', 'Unvailable For Meeting', 'VJTI'),
	('2015-03-24', '10:00:00', 141080006, 'Meet', 'Francisco D''souza ', 'Cognizant India', 'Followup Required', '12th & 13th Floor, A wing, Kensington Building, Hiranandani Business Park, Powai, Mumbai - 400 076   '),
	('2015-03-25', '13:13:00', 141030005, 'Call', 'Neeta Khatib ', 'Heinz Co', 'Followup Required , comapny wants a stall', 'VJTI'),
	('2015-03-26', '12:30:00', 141030005, 'Meet', 'Neeta Khatib ', 'Heinz Co', 'Unvailable For Meeting', 'One PPG Place, Pittsburgh, PA 15219'),
	('2015-03-27', '12:34:00', 131050004, 'Call', 'Anuj Dharap ', 'Metro Travels', 'Followup Required', 'VJTI'),
	('2015-03-28', '13:30:00', 131090005, 'Meet', 'Sheeza Waghmare ', 'CEAT', 'Final Meeting , Sponsorship Confirmed', '509-510 Fifth Floor, Shop Zone, M.G.Road, Ghatkopar (W), Mumbai - 400 086'),
	('2015-03-30', '15:21:00', 141020004, 'Call', 'Ayush Shah ', 'PepsiCo', 'Followup Required', 'VJTI'),
	('2015-04-01', '14:45:00', 131010004, 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Unvailable For Meeting, setup another Meeting', 'VJTI'),
	('2015-04-03', '14:29:00', 141020009, 'Call', 'Prakash Shiram ', 'Ganraj Construction', 'Followup Required , wants 2 banners', 'VJTI'),
	('2015-04-05', '13:30:00', 131090006, 'Meet', 'Ashok Jalan ', 'Siemens', 'Followup Required', '130, Pandurang Budhkar Marg, Worli, Maharashtra, Mumbai 400 018.'),
	('2015-04-06', '12:52:00', 131010004, 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Very Interested Company', 'VJTI'),
	('2015-04-07', '12:00:00', 141030005, 'Meet', 'Neeta Khatib ', 'Heinz Co', 'Followup Required', 'One PPG Place, Pittsburgh, PA 15219'),
	('2015-04-08', '13:30:00', 141080010, 'Meet', 'Drishti Lekhrajani ', 'The Park', 'Unvailable For Meeting', 'Sector 10, Cbd Belapur'),
	('2015-04-09', '14:59:00', 141080006, 'Call', 'Francisco D''souza ', 'Cognizant India', 'Followup Required', 'VJTI'),
	('2015-04-10', '11:37:00', 131010004, 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Very Interested Company', 'VJTI'),
	('2015-04-11', '12:34:00', 131050004, 'Call', 'Anuj Dharap ', 'Metro Travels', 'Unvailable For Meeting', 'VJTI'),
	('2015-04-14', '12:00:00', 141080010, 'Meet', 'Drishti Lekhrajani ', 'The Park', 'Followup Required, wants to be lone sponsor in hotel sector', 'Sector 10, Cbd Belapur'),
	('2015-04-17', '16:38:00', 141030005, 'Call', 'Neeta Khatib ', 'Heinz Co', 'Unvailable For Meeting', 'VJTI'),
	('2015-04-18', '13:27:00', 141080006, 'Call', 'Francisco D''souza ', 'Cognizant India', 'Very Interested Company', 'VJTI'),
	('2015-04-20', '14:17:00', 141030005, 'Call', 'Neeta Khatib ', 'Heinz Co', 'Followup Required', 'VJTI'),
	('2015-04-21', '13:30:00', 131050004, 'Meet', 'Anuj Dharap ', 'Metro Travels', 'Very Interested Company , Company wants to become title sponsor', '2/5, 1st Rabodi, Thane West, Thane - 400601'),
	('2015-04-23', '15:45:00', 141020004, 'Call', 'Ayush Shah ', 'PepsiCo', 'Very Interested Company', 'VJTI'),
	('2015-04-24', '13:26:00', 141020009, 'Call', 'Prakash Shiram ', 'Ganraj Construction', 'Followup Required', 'VJTI'),
	('2015-04-25', '16:48:00', 131010004, 'Call', 'Naveen Saxena ', 'BIG FM 92.7', 'Unvailable For Meeting', 'VJTI'),
	('2015-04-26', '14:34:00', 141080006, 'Call', 'Francisco D''souza ', 'Cognizant India', 'Final Meeting , Sponsorship Confirmed', 'VJTI'),
	('2015-04-27', '12:56:00', 141030005, 'Call', 'Neeta Khatib ', 'Heinz Co', 'Followup Required', 'VJTI'),
	('2015-04-28', '13:30:00', 131010004, 'Meet', 'Naveen Saxena ', 'BIG FM 92.7', 'Final Meeting , Sponsorship Confirmed', '401, 4th Floor, Infiniti Mall, Oshiwara, New Link Road, Andheri West, Mumbai ? 400053'),
	('2015-04-30', '10:19:00', 141080010, 'Call', 'Drishti Lekhrajani ', 'The Park', 'Followup Required want 2 banners', 'VJTI'),
	('2015-05-01', '14:43:00', 141030005, 'Call', 'Neeta Khatib ', 'Heinz Co', 'Followup Required', 'VJTI'),
	('2015-05-03', '12:00:00', 131090006, 'Meet', 'Ashok Jalan ', 'Siemens', 'Final Meeting , Sponsorship Confirmed', '130, Pandurang Budhkar Marg, Worli, Maharashtra, Mumbai 400 018.'),
	('2015-05-05', '15:44:00', 141020009, 'Call', 'Prakash Shiram ', 'Ganraj Construction', 'Followup Required , wants 3000 for painting off quad wall', 'VJTI'),
	('2015-05-06', '11:55:00', 131070004, 'Call', 'Jyoti Kumar ', 'ABB', 'Not Interested', 'VJTI'),
	('2015-05-07', '16:00:00', 141020004, 'Meet', 'Ayush Shah ', 'PepsiCo', 'Final Meeting , Sponsorship Confirmed', '700 Anderson Hill Road, Purchase, NY 10577-1444'),
	('2015-05-08', '16:28:00', 131070004, 'Call', 'V B Haribhakti ', 'Bajaj Electricals', 'Not Interested', 'VJTI'),
	('2015-05-09', '14:00:00', 131050004, 'Meet', 'Anuj Dharap ', 'Metro Travels', 'Final Meeting , Sponsorship Confirmed', '2/5, 1st Rabodi, Thane West, Thane - 400601'),
	('2015-05-10', '12:22:00', 141080009, 'Call', 'Harish Patel ', 'Nestle', 'Not Interested', 'VJTI'),
	('2015-05-11', '14:21:00', 141080009, 'Call', 'Neetu Karnani ', 'Mars Inc.', 'Not Interested', 'VJTI'),
	('2015-05-14', '13:30:00', 141020009, 'Meet', 'Prakash Shiram ', 'Ganraj Construction', 'Final Meeting , Sponsorship Confirmed', 'Kalyan Road, Dombivli East'),
	('2015-05-18', '12:34:00', 141020010, 'Call', 'Sanjay Nayak ', 'Tejas Networks', 'Not Interested', 'VJTI'),
	('2015-05-20', '14:22:00', 141030005, 'Call', 'Neeta Khatib ', 'Heinz Co', 'Final Meeting , Sponsorship Confirmed', 'VJTI'),
	('2015-05-21', '13:55:00', 141060009, 'Call', 'Prachi Sagwekar ', 'Goel Steel', 'Not Interested', 'VJTI'),
	('2015-05-23', '14:46:00', 141080011, 'Call', 'Mahaveer Bhansali ', 'Falcon Tyres', 'Not Interested', 'VJTI'),
	('2015-05-24', '13:30:00', 141080010, 'Meet', 'Drishti Lekhrajani ', 'The Park', 'Final Meeting , Sponsorship Confirmed', 'Sector 10, Cbd Belapur');

";



$AccountLog_insert_all="

	INSERT INTO `AccountLog` (`EventID`, `Title`, `SponsID`, `Amount`, `TransType`, `Date`) VALUES
	(1, 'Armada Records & Co.', 131040002, 50000, 'Deposit', '2015-02-08'),
	(1, 'Chandan Steel Industries', 131080007, 10000, 'Deposit', '2015-02-23'),
	(1, 'Louis Philippe', 141020003, 20000, 'Deposit', '2015-03-07'),
	(1, 'Fabindia', 131080008, 12000, 'Deposit', '2015-03-11'),
	(1, 'Webmax Technologies', 141060007, 12800, 'Deposit', '2015-03-17'),
	(1, 'CEAT', 131090005, 17500, 'Deposit', '2015-03-28'),
	(1, 'Cognizant India', 141080006, 19000, 'Deposit', '2015-04-26'),
	(1, 'BIG FM 92.7', 131010004, 35000, 'Deposit', '2015-04-28'),
	(1, 'Siemens', 131090006, 29000, 'Deposit', '2015-05-03'),
	(1, 'PepsiCo', 141020004, 43000, 'Deposit', '2015-05-07'),
	(1, 'Metro Travels', 131050004, 21000, 'Deposit', '2015-05-09'),
	(1, 'The Park', 141080010, 41000, 'Deposit', '2015-05-17'),
	(1, 'Ganraj Construction', 141020009, 32000, 'Deposit', '2015-05-14'),
	(1, 'Heinz Co', 141030005, 18000, 'Deposit', '2015-05-20');

";



$SponsLogin_insert_all="
	INSERT INTO `SponsLogin` (`SponsID`, `Password`, `AccessLevel`) VALUES
	(121010001, '121010001test', 'SectorHead'),
	(121020001, '121020001test', 'SectorHead'),
	(121020002, '121020002test', 'SectorHead'),
	(121030001, '121030001test', 'SectorHead'),
	(121030002, '121030002test', 'SectorHead'),
	(121050001, '121050001test', 'SectorHead'),
	(121060001, '121060001test', 'SectorHead'),
	(121060002, '121060002test', 'SectorHead'),
	(121060003, '121060003test', 'SectorHead'),
	(121060004, '121060004test', 'SectorHead'),
	(121060005, '121060005test', 'SectorHead'),
	(121070001, '121070001test', 'SectorHead'),
	(121080001, '121080001test', 'SectorHead'),
	(121080002, '121080002test', 'SectorHead'),
	(121080003, '121080003test', 'SectorHead'),
	(121090001, '121090001test', 'SectorHead'),
	(131010003, '131010003testing', 'SponsRep'),
	(131010004, '131010004testing', 'SponsRep'),
	(131010006, '131010006testing', 'SponsRep'),
	(131020006, '131020006testing', 'SponsRep'),
	(131020007, '131020007testing', 'SponsRep'),
	(131020008, '131020008testing', 'SponsRep'),
	(131020012, '131020012testing', 'SponsRep'),
	(131030004, '131030004testing', 'SponsRep'),
	(131040002, '131040002testing', 'SponsRep'),
	(131050003, '131050003testing', 'SponsRep'),
	(131050004, '131050004testing', 'SponsRep'),
	(131050006, '131050006testing', 'SponsRep'),
	(131050007, '131050007testing', 'SponsRep'),
	(131070002, '131070002testing', 'SponsRep'),
	(131070003, '131070003testing', 'SponsRep'),
	(131070004, '131070004testing', 'SponsRep'),
	(131070006, '131070006testing', 'SponsRep'),
	(131080007, '131080007testing', 'SponsRep'),
	(131080008, '131080008testing', 'SponsRep'),
	(131080012, '131080012testing', 'SponsRep'),
	(131080013, '131080013testing', 'SponsRep'),
	(131090002, '131090002testing', 'SponsRep'),
	(131090003, '131090003testing', 'SponsRep'),
	(131090004, '131090004testing', 'SponsRep'),
	(131090005, '131090005testing', 'SponsRep'),
	(131090006, '131090006testing', 'SponsRep'),
	(131090007, '131090007testing', 'SponsRep'),
	(131090008, '131090008testing', 'SponsRep'),
	(141010002, '141010002testing', 'SponsRep'),
	(141010005, '141010005testing', 'SponsRep'),
	(141020003, '141020003testing', 'SponsRep'),
	(141020004, '141020004testing', 'SponsRep'),
	(141020005, '141020005testing', 'SponsRep'),
	(141020009, '141020009testing', 'SponsRep'),
	(141020010, '141020010testing', 'SponsRep'),
	(141020011, '141020011testing', 'SponsRep'),
	(141030003, '141030003testing', 'SponsRep'),
	(141030005, '141030005testing', 'SponsRep'),
	(141040001, '141040001testing', 'SponsRep'),
	(141040003, '141040003testing', 'SponsRep'),
	(141050002, '141050002testing', 'SponsRep'),
	(141050005, '141050005testing', 'SponsRep'),
	(141060006, '141060006testing', 'SponsRep'),
	(141060007, '141060007testing', 'SponsRep'),
	(141060008, '141060008testing', 'SponsRep'),
	(141060009, '141060009testing', 'SponsRep'),
	(141060010, '141060010testing', 'SponsRep'),
	(141060011, '141060011testing', 'SponsRep'),
	(141070005, '141070005testing', 'SponsRep'),
	(141080004, '141080004testing', 'SponsRep'),
	(141080005, '141080005testing', 'SponsRep'),
	(141080006, '141080006testing', 'SponsRep'),
	(141080009, '141080009testing', 'SponsRep'),
	(141080010, '141080010testing', 'SponsRep'),
	(141080011, '141080011testing', 'SponsRep'),
	(131080051, 'abhishekdivekar', 'CSO'),
	(131080053, 'advikshetty', 'CSO'),
	(131080055, 'janitmehta', 'CSO');
";







$insert_all_tables=array(
	array("Event", 				$Event_insert_all),
	array("CommitteeMember", 	$CommitteeMember_insert_all), 
	array("SponsRep", 			$SponsRep_insert_all), 
	array("SectorHead", 		$SectorHead_insert_all), 
	array("SponsLogin", 		$SponsLogin_insert_all), 
	array("AccountLog", 		$AccountLog_insert_all),
	array("Company", 			$Company_insert_all), 
	array("CompanyExec", 		$CompanyExec_insert_all), 
	array("Meeting", 			$Meeting_insert_all)
);  


echo "<h1> Populating tables...</h1>";
echo "<p align=\"left\">";
echo "<table width=\"50%\" border=\"0\">";
echo  "<tr bgcolor=\"#993333\"> ";
echo    "<td width=\"10%\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"-1\" color=\"#FFFFFF\">No.</font></td>";
echo    "<td width=\"25%\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"-1\" color=\"#FFFFFF\">Table name:</font></td>";
echo    "<td width=\"65%\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"-1\" color=\"#FFFFFF\">Status:</font></td>";
echo  "</tr>";



$i=0;
while ($i < count($insert_all_tables)){
	echo "<tr bgcolor=\"#CCCCCC\">";
	
	$table_name = $insert_all_tables[$i][0];
	$table_insert_query = $insert_all_tables[$i][1]; 
	echo    "<td>";
       print $i+1;
	echo    "</td>";
	echo    "<td>";
       print "$table_name\n";
	echo    "</td>";


	$status = "";

	if(mysql_query($table_insert_query)){ 	//$conn is gotten from:   require('DBconnect.php');

		//Check how many rows were inserted.
		$query="select * from `$table_name`";
		$result = mysql_query($query);
		if($result){
			$status = $status."<b>Entered successfully into table $table_name.</b>";
			$num_rows_inserted = mysql_num_rows($result);
			$status = $status."<br>Number of rows in table: $num_rows_inserted";			
		}
		else{
			$status = $status."<b>Could not insert into table $table_name.</b>";	
		}
	}
	else{
		$status = $status."<b>Could not insert into table $table_name.</b>";
	}

	echo    "<td>";	
    print "$status\n";
	echo    "</td>";

	$i=$i+1;
}



?>



