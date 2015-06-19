DROP TABLE bocker;

CREATE TABLE `bocker` (
  `titel` varchar(100) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'x',
  `upplaga` varchar(50) COLLATE utf8_swedish_ci DEFAULT NULL,
  `utgivnings_ar` varchar(10) COLLATE utf8_swedish_ci DEFAULT NULL,
  `undertitel` varchar(100) COLLATE utf8_swedish_ci DEFAULT NULL,
  `forf_fornamn` varchar(25) COLLATE utf8_swedish_ci DEFAULT NULL,
  `forf_efternamn` varchar(25) COLLATE utf8_swedish_ci DEFAULT NULL,
  `antal` int(11) DEFAULT NULL,
  `pris` int(11) DEFAULT NULL,
  `arkiverad` tinyint(4) DEFAULT '0',
  `isbn` varchar(20) COLLATE utf8_swedish_ci NOT NULL DEFAULT 'x',
  `forlag` varchar(50) COLLATE utf8_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`isbn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

INSERT INTO bocker VALUES("ABC: svenska för gymnasieskolan: handbok","1. uppl.","","","","","1","0","0","91-24-16817-3","Akademiförlaget, 1996, tr");
INSERT INTO bocker VALUES("Blueprint A : version 2.0","2. uppl.","","","Lundfall","Christer","168","0","0","9789147080557","Liber, 2007, tr. 2012");
INSERT INTO bocker VALUES("Blueprint A, facit","2. uppl.","","","Lundfall","Christer","48","0","0","9789147080564","Liber");
INSERT INTO bocker VALUES("Blueprint B :  version 2.0","2. uppl.","","","Lundfall","Christer","107","0","0","9789147081967","Liber, 2008");
INSERT INTO bocker VALUES("Blueprint B, facit","2. uppl.","","","Lundfall","Christer","63","0","0","9789147081974","Liber");
INSERT INTO bocker VALUES("Blueprint C : version 2.0","2. uppl.","","","Lundfall","Christer","85","0","0","9789147091966","Liber");
INSERT INTO bocker VALUES("Chemistry in context","5. ed. (2000)","","","Hill","Graham","38","0","0","0-17-448276-0","Walton-on-Thames : Nelson");
INSERT INTO bocker VALUES("Chemistry : for the IB Diploma :","1. ed. ","","standard and higher level","Neuss","Geoffrey","30","0","0","9780199151424","Oxford University Press, ");
INSERT INTO bocker VALUES("Chemistry: Course companion","1. ed.","","","Neuss","Geoffrey","25","0","0","9780199151462","Oxford University Press, ");
INSERT INTO bocker VALUES("Dialog, 1900-talet, Antologi","1. uppl. ","","","Lindskog","Runo","163","0","0","91-27-59192-1","NoK, 2000");
INSERT INTO bocker VALUES("Dialog, 1900-talet, Litteraturhistoria","1. uppl.","","","Lindskog","Runo","160","0","0","91-27-59194-8","NoK, 2000");
INSERT INTO bocker VALUES("Digital bild 4.0","1. uppl.","","","Ohlsson","Stefan","86","0","0","9789186841102","Dext, 2012");
INSERT INTO bocker VALUES("Economics : course companion","2. ed.","","","Blink","Jocelyn","29","0","0","9780199184996","Oxford University, 2011");
INSERT INTO bocker VALUES("Environmental systems and societies  : course companion","1. ed.","","","Rutherford","Jill","15","0","0","9780198389149","Oxford University Press, ");
INSERT INTO bocker VALUES("Ergo fysik 1","4. uppl.","","","Pålsgård","Jan","664","0","0","9789147085538","Liber, 2011");
INSERT INTO bocker VALUES("Ergo fysik 2","3. uppl.","","","Pålsgård","Jan","409","0","0","9789147106721","Liber, 2012");
INSERT INTO bocker VALUES("European history 1848 - 1945","1. ed.","","","Morris","Terence Alan","23","0","0","0-00-327275-3","Collins educational, 1995");
INSERT INTO bocker VALUES("Geografi A","2. uppl.","","","Östman mfl","","40","0","0","91-21-21110-8","A&W, 2005");
INSERT INTO bocker VALUES("Grande escalade. 3.","1. uppl. ","","","Pettersson","Marie","85","","0","978-91-21-19726-4 ","A&W, 2003 och 2009 ");
INSERT INTO bocker VALUES("Grande escalade 3,  Facit","1. uppl.","","","Pettersson","Marie","83","0","0","91-21-19727-x","A&F, 2003");
INSERT INTO bocker VALUES("Grande escalade. 4.","","","","Pettersson ","Marie ","16","","0","978-91-21-20515-0","A&W, 2004 ");
INSERT INTO bocker VALUES("Grande escalade 4 elev-CD","1. uppl.","","","Grande escalade","","66","0","0","91-21-95350-3","Almqvist");
INSERT INTO bocker VALUES("Grande escalade 4. Facit","1. uppl.","","","Pettersson","Marie","15","0","0","91-21-20516-7","A&W, 2006 ");
INSERT INTO bocker VALUES("Grande escalade 3. Elev-CD ","","","","","","50","0","0","91-21-95228-0","A&W, 2003 ");
INSERT INTO bocker VALUES("Gymnasiekemi 1","4. uppl.","","","Andersson","Stig","354","0","0","9789147085576","Liber, 2012");
INSERT INTO bocker VALUES("Gymnasiekemi A","3. uppl.","","","Andersson","","76","0","0","9789147018758","Liber, 2007, tr. 2011");
INSERT INTO bocker VALUES("Gymnasiekemi B","5. uppl.","","","Andersson","","218","0","0","9789147085125","Liber, 2009, tr 2010");
INSERT INTO bocker VALUES("History : 20th Century World : ","","","The Cold War","Rogers","Keely","15","0","0","9780435994280","Heinemann, 2008");
INSERT INTO bocker VALUES("Introduction to research methods in psychology","3. ed.","","","Coolican","Hugh","25","0","0","9780340907573","Hodder Education, 2006");
INSERT INTO bocker VALUES("Japanese for young people. 1. Student book","1. uppl.","","","Association for Japanese-","","39","0","0","4-7700-2178-X","Kodansha International, 1");
INSERT INTO bocker VALUES("Java - steg för steg","1. uppl.","","","Skansholm","Jan","86","0","0","9789144085876","Studentlitteratur, 2012");
INSERT INTO bocker VALUES("Konstruktion","1. uppl.","","","Nyberg","Yngve","63","0","0","9789147017966","Liber, 2008");
INSERT INTO bocker VALUES("Kultur- och idéhistoria","1. uppl.","","","Nord","Eva","24","0","0","91-21-21092-6","A&W, 2005");
INSERT INTO bocker VALUES("Lieber Deutsch 3, Facit","1. uppl.","","","Karnland","Annika","91","0","0","9789147079377","Liber, 2008");
INSERT INTO bocker VALUES("Lieber Deutsch 3","1. uppl.","","","Karnland","Annika","101","0","0","9789147079308","Liber, 2006, tr. 2010");
INSERT INTO bocker VALUES("Lieber Deutsch 4","1. uppl.","","","Karnland","Annika","27","0","0","9789147081653","A&W, 2007, tr. 2009");
INSERT INTO bocker VALUES("Lieber deutsch 4, Facit","1. uppl.","","","Karnland","Annika","25","0","0","9789147081660","A&W, 2007, tr.2009");
INSERT INTO bocker VALUES("Linjär algebra med geometri","2. uppl.","","","Andersson","Lennart","25","0","0","9789144009728","Studentlitteratur, 1999, ");
INSERT INTO bocker VALUES("Matematik 5.000, kurs 3c","1. uppl.","","","Alfredsson","Lena","369","0","0","9789127426283","NoK, 2012");
INSERT INTO bocker VALUES("Matematik 5000, kurs 1c","1. uppl.","","","Alfredsson","Lena","413","0","0","9789127421608","NoK, 2011");
INSERT INTO bocker VALUES("Matematik 5000, kurs 2c","1. uppl.","","","Alfredsson","Lena","442","0","0","9789127422537","NoK, 2011, tr. 2012");
INSERT INTO bocker VALUES("Matematik 5000, kurs 4","1. utg. 1 tr.","","Kap. 3.6 Rotationsvolymer saknas i denna tryckning","Alfredsson","Lena","72","0","0","9789127426320","NoK, 2013");
INSERT INTO bocker VALUES("Mathematical studies for the IB Diploma","","","Textbok, CD-ROM","Pimentel","Ric","15","0","0","9780340987520","Hodder Education, 2010");
INSERT INTO bocker VALUES("Mathematics Higher Level : course companion","1. ed.","","Textbok, CD-ROM","Fensom","Jim","4","0","0","9780199129348","Oxford University Press, ");
INSERT INTO bocker VALUES("Mathematics standard level for the IB Diploma","","","","Smedley","Robert","34","0","0","9780199149797","Oxford University Press, ");
INSERT INTO bocker VALUES("Miljökunskap","1. uppl.","","","Björndahl","Gunnar","58","0","0","91-47-01731-7","Liber, 2003");
INSERT INTO bocker VALUES("Miniräknare T-83 plus","","","","","","270","630","0","9999999999999","");
INSERT INTO bocker VALUES("Miniräknare TI-84 plus","","","","","","152","750","0","8484848484848","");
INSERT INTO bocker VALUES("Modern engelsk grammatik","6. uppl.","","","Svartvik","Jan","39","0","0","9789147020881","Liber, 2010, tr. 2012");
INSERT INTO bocker VALUES("Modern tysk grammatik","4. uppl.","","","Rydén","Kerstin","19","0","0","91-21-17978-6","A&W, 2000");
INSERT INTO bocker VALUES("Möt Litteraturen","1. uppl.","","","Brodow","Bengt","120","0","0","91-40-62141-3","Gleerup, 1998, tr.2002");
INSERT INTO bocker VALUES("Möt litteraturen - antologi","1. uppl.","","","Brodow","Bengt","156","0","0","91-40-63126-5","Gleerup, 199, tr.2002");
INSERT INTO bocker VALUES("Peacemaking, peacekeeping - international relations 1918-36","1. ed.","","","Dailey","Andy","26","0","0","9781444156324","Hodder education, 2012");
INSERT INTO bocker VALUES("Perspektiv på historien 1b","1. uppl.","","","Nyström","Hans","214","0","0","9789140874449","Gleerup, 2011, tr. 2012");
INSERT INTO bocker VALUES("Perspektiv på historien 2/3","1. uppl.","","","Nyström","Hans","18","0","0","9789140665980","Gleerup, 2012");
INSERT INTO bocker VALUES("Perspektiv på historien 50 p","1. uppl.","","","Nyström","Hans","104","0","0","9789140671547","Gleerup, 201");
INSERT INTO bocker VALUES("Physics for the IB Diploma:","2nd ed.","","Standard and higher level ","Kirk","Tim","33","0","0","9780199151417","Oxford University Press, ");
INSERT INTO bocker VALUES("Physics : Course Companion","1. ed.","","","Kirk","Tim","20","0","0","9780199151448","Oxford University Press, ");
INSERT INTO bocker VALUES("Physics : developed specifically for the IB Diploma","","","Standard level","Hamper","Chris","17","0","0","9780435994471","Pearson, 2007");
INSERT INTO bocker VALUES("Planeter, Stjärnor, Galaxer","2. uppl.","","Grundläggande astronomi","Lagerkvist","Claes-Ingvar","72","0","0","91-47-01823-9","Liber, 2004");
INSERT INTO bocker VALUES("Privatjuridik & rättsvetenskap","1. uppl.","","Fakta och uppgifter","Andersson","Jan-Olof","32","0","0","9789147106202","Liber, 2012");
INSERT INTO bocker VALUES("Progress Gold B. Textbok, CD-ROM","","","","Hedencrona ","Eva ","100","","0","978-91-44-03561-1","Studentlitteratur, 2008, tr. 2010");
INSERT INTO bocker VALUES("Progress Gold C","2. uppl.","","","Hedencrona","Eva","33","0","0","9789144035659","Studentlitteratur, 2011");
INSERT INTO bocker VALUES("Progress Gold C, Bok, CD-ROM","1. uppl.","","","Hedencrona","Eva","113","0","0","91-44-03920-6","Studentlitteratur, 2005");
INSERT INTO bocker VALUES("Filosofi : Kurs A och B","2. uppl. ","","","Levander ","Martin ","23","","0","978-91-21-19693-9","A&W, 2002 ");
INSERT INTO bocker VALUES("Progress Gold A. Textbok, CD-ROM","2. uppl. ","","","Hedencrona","Eva","83","","0","978-91-44-02985-6","Studentlitteratur, 2007");
INSERT INTO bocker VALUES("Progress Gold B, Textbok, CD-ROM ","","","","Hedencrona","Eva","21","","0","978-91-44-03089-0","Studentlitteratur, 2003");
INSERT INTO bocker VALUES("Psychology: Course Companion","1. ed.","","","Crane","John","38","0","0","9780199151295","Oxford University 2012, ");
INSERT INTO bocker VALUES("Psykologi - för gymnasiet","","","","Ljunggren","Nadja","44","0","0","9769147092963","Liber, 2011, tr. 2013");
INSERT INTO bocker VALUES("Reflex 1, 2, 3","1. uppl.","","Samhällskunskap för gymnasieskolan","Almgren","Hans","65","0","0","9789140672094","Gleerups, 2012");
INSERT INTO bocker VALUES("Reflex plus","1. uppl.","","Samhällskunskap för gymnasieskolan ","Almgren","Hans","126","0","0","9789140673664","Gleerup, 2011, tr. 2012");
INSERT INTO bocker VALUES("Religion 1 för gymnasiet","1. uppl.","","","Göth","Lennart","187","0","0","9789127421820","NoK, 2012 ");
INSERT INTO bocker VALUES("Spira 1 :  Biologi","2. uppl.","","","Björndahl","Gunnar","203","0","0","9789147085378","Liber, 2011, tr. 2012");
INSERT INTO bocker VALUES("Spira 2 : Biologi","2. uppl.","","","Björndahl","Gunnar","249","0","0","9789147085897","Liber, 2012 ");
INSERT INTO bocker VALUES("Svenska i verkligheten","2. uppl.","","","Backman","Gunilla","35","0","0","9789173069113","Interskol");
INSERT INTO bocker VALUES("Svenska timmar - Antologin","3. uppl.","","","Skoglund","Svante","316","0","0","9789140673398","Gleerup, 2012");
INSERT INTO bocker VALUES("Svenska timmar - Litteraturen","3. uppl.","","","Skoglund","Svante","313","0","0","9789140673374","Gleerup, 2012");
INSERT INTO bocker VALUES("Svenska timmar - Språket ","4. uppl.","","","Waje","Lennart","335","0","0","9789140673367","Gleerup, 2011");
INSERT INTO bocker VALUES("Teknik 1","1. uppl.","","","Nyberg","Yngve","218","0","0","9789147085637","Liber, 2011");
INSERT INTO bocker VALUES("The Cold War","","","","Rayner","E. G.","17","0","0","0-340-56545-4","Hodder, 1992");
INSERT INTO bocker VALUES("The Cold war : 1945 - 1991","","","","Mason","John W","60","0","0","0-415-14278-4","Lancaster pamphlets");
INSERT INTO bocker VALUES("Venga,vamos!","1. uppl.","","","Vanäs-Hedberg","Margareta","62","0","0","91-21-18364-3","A&W, 2006 ");
INSERT INTO bocker VALUES("View points. 2.","1. uppl.","","","Gustafsson","Linda","30","0","0","9789140675255","Gleerup, 2012");
INSERT INTO bocker VALUES("Vistas 3","1. uppl.","","","Rönnmark","Inger","76","0","0","9789152303948","Sanoma utbildning, 2012");
INSERT INTO bocker VALUES("Blueprint C, Key","2.uppl.","","","","","60","","0","97891","Liber");
INSERT INTO bocker VALUES("Japanese for young people. 2.","","","Elevbok","","","13","","0","4-7700-2332-4","Kodansha Internationel, 1");
INSERT INTO bocker VALUES("Japanese for young people. 3.","","","Elevbok","","","1","","0","4-7700-2495-4","Kodansha International, 2");
INSERT INTO bocker VALUES("English Grammar in Use","4.ed.","","","Murphy","Raymond","42","","0","978-0-521-18906-4","Cambridge University Press,  2012");
INSERT INTO bocker VALUES("Java direkt med Swing","5.uppl.","","","Skansholm","Jan","46","","0","978-91-44-03843-8","Studentlitteratur, 2005, ");
INSERT INTO bocker VALUES("Kultur- och idéhistoria","2.uppl","","","Nord","Eva","9","","0","978-91-47-09289-5","Liber, 2009, tr. 2011");
INSERT INTO bocker VALUES("Lieber Deutsch 2","1.uppl","","Innehåller cd","Hofbauer","Christine","35","","0","978-91-21-21264-6","A&W, 2005");
INSERT INTO bocker VALUES("Lieber Deutsch 5","1. uppl.","","","Hofbauer","Christine","9","","0","978-91-47-01015-8","A&W, 2008, tr. 2009");
INSERT INTO bocker VALUES("Matematik 5.000, kurs 4","1. utg. 2. tr.","","Kap. 3.6 Rotationsvolymer ingår i denna tryckning","Alfredsson","Lena","313","","0","978-91-27-42632-0","NoK, 2013");
INSERT INTO bocker VALUES("Matematik 5.000, kurs 5","1 utg.","","","Alfredsson","Lena","174","","0","978-91-27-42633-7","NoK, 2013");
INSERT INTO bocker VALUES("Mathematical studies for the IB Diploma","2.ed","","","Pimentel","Ric","18","","0","978-1-4441-8017-6","Hodder Education, 2010");
INSERT INTO bocker VALUES("Mathematics Higher Level : course companion","","","Textbok, CD-ROM","Harcet","Josip","11","","0","978-0-19-839012-1","Oxford University Press, ");
INSERT INTO bocker VALUES("Mathematical Studies : course companion","","","","Bedding","Stephen","4","","0","978-0-19-915121-9","Oxford University Press, ");
INSERT INTO bocker VALUES("Mathematics standard level : course companion","","","Textbok, CD-ROM","Buchanan","Laurie","22","","0","978-0-19-839011-4","Oxford University Press,2");
INSERT INTO bocker VALUES("Modern engelsk grammatik.","4. uppl.","","","Svartvik ","Jan ","277","","0","91-21-11931-7","A&W, 1991, tr. 1998 ");
INSERT INTO bocker VALUES("Modern tysk grammatik ","3. uppl.","","","Rydén","Kerstin","94","","0","91-21-15201-2","A&W, 1993, tr. 1995");
INSERT INTO bocker VALUES("Physics : developed specifically for the IB Diploma ","","","Higher level  (plus standard level options)","Hamper","Chris","23","","0","978-0-435994-42-6","Pearson Education Limited");
INSERT INTO bocker VALUES("Psychology : Course Companion","","","","Crane","John","14","","0","978-0-19-838995-8","Oxford University, 2012");
INSERT INTO bocker VALUES("Svenska timmar - Språket","3.uppl.","","A+B","Waje","Lennart","44","","0","978-91-40-63572-3","Gleerup, 2003, tr. 2010");
INSERT INTO bocker VALUES("20th century world history : course companion","","","","Cannon","Martin","12","","0","978-0-19-915261-2","Oxford University Press, ");
INSERT INTO bocker VALUES("Svenska i verkligheten : för GY11","2. uppl.","","Svenska 1","Backman","Gunilla","172","","0","978-91-7306-911-3","Interskol 2011");
INSERT INTO bocker VALUES("Nya mål 3 : svenska som andra språk","1. uppl.","","","Ballardini ","Kerstin ","12","","0","978-91-27-50589-6","NoK, 2001, tr. 2010");
INSERT INTO bocker VALUES("Nya mål 3 : svenska som andra språk","1. uppl. ","","Övningsbok","Ballardini","Kerstin","19","","0","978-91-27-50590-1","NoK, 2002, tr. 2011");
INSERT INTO bocker VALUES("Nya mål 3 : svenska som andra språk ","1. uppl. ","","Facit - övningsbok","Ballardini","Kerstin","2","","0","978-91-27-50596-4","NoK, 2002, tr. 2003");
INSERT INTO bocker VALUES("Kom i mål","1. uppl.","","","Risérus","Harriet","8","","0","978-91-27-50429-5","NoK, 2006, tr. 2008");
INSERT INTO bocker VALUES("Svenska etc : ","1. uppl.","","kursbok i svenska och svenska som andra språk","Thörnroth","Annsofie","25","","0","978-91-47-07814-1","Liber, 2006, tr. 2012");
INSERT INTO bocker VALUES("Så byggdes staden","3.utg.","","","Björk","Cecilia","25","","0","978-91-7333-542-3","Svensk byggtjänst, 2012");
INSERT INTO bocker VALUES("Så byggdes staden","2. uppl. ","","","Björk","Cecilia","47","","0","978-91-7333-282-8","Svensk byggtjänst, 2008, ");
INSERT INTO bocker VALUES("Mathematics higher level","","","Calculus","Josip ","Harcet","6","","0","9780198304845","Oxfordf University Press");
INSERT INTO bocker VALUES("Calculus","7. ed. ","","","Adams","Robert","2","","0","978-0-321-54928-0","Christopher, 2010");
INSERT INTO bocker VALUES("Dialog. Klassikerna. Antologi","1. uppl.","","","Widing","Dick","206","","0","978-91-27-59191-2","NoK, 2000, tr. 2002");
INSERT INTO bocker VALUES("Dialog. Klassikerna. Antiken, litteraturhistoria","1. uppl.","","","Widing","Dick","217","","0","978-91-27-59193-6","NoK, 2001, tr. 2002");
INSERT INTO bocker VALUES("Heureka! : fysik. Kurs 3. Ledtrådar och lösningar","","","","Alphonce","Rune","28","","0","978-91-27-43555-1","NoK, 2014");
INSERT INTO bocker VALUES("Heureka! : fysik. Kurs 3","","","","Alphonce","Rune","30","","0","987-91-27-56729-0","NoK, 2013");
INSERT INTO bocker VALUES("Lieber Deutsch 1. Textbok, CD","","","","Hofbauer","Christine","17","","0","978-91-21-20568-6","Liber, 2004, tr.2011");
INSERT INTO bocker VALUES("Lieber Deutsch 1. Facit","","","","Hofbauer"," Christine ","15","","0","978-91-21-20569-3","A&W, 2004");
INSERT INTO bocker VALUES("Lieber Deutsch 2. Facit","","","","Hofbauer","Christine ","30","","0","91-21-21265-1","A&W, 2005 ");
INSERT INTO bocker VALUES("Buena idea 1. Textbok, CD-ROM","","","","Håkansson","Ulla","25","","0","978-91-622-6906-7","Bonnier, 2006");
INSERT INTO bocker VALUES("Buena idea 1. Elevfacit. ","","","","Håkansson","Ulla","25","","0","978-91-622-6910-4","Bonnier, 2006");
INSERT INTO bocker VALUES("Buena idea 1. Libro de trabajo","","","","Håkansson","Ulla","25","","0","978-91-622-6908-1","Bonnier, 2006Håkansson");
INSERT INTO bocker VALUES("Balanced science","","","","Jones","Geoffrey","15","","0","978-0-521-35689-3","Cambridge education, 1990");
INSERT INTO bocker VALUES("Biology","","","","Jones","Mary","31","","0","978-0-521-45618-0","Cambridge University Pres");
INSERT INTO bocker VALUES("Chemistry","2nd ed.","","","Jones","MAry","15","","0","0-521-59983-0","Cambridge University Pres");
INSERT INTO bocker VALUES("Byggteknik","1. uppl.","","","Jonsson","Jan","18","","0","978-91-47-08497-5","Liber, 2009, tr. 2011");
INSERT INTO bocker VALUES("Chemistry : course companion","2 ed.","","","Neuss","Geoffrey","4","","0","978-0-19-913955-2","Oxford University Press, ");
INSERT INTO bocker VALUES("Chemistry : course companion. Bok, DVD","Second ed.","","","Neuss","Geoffrey ","5","","0","978-0-19-839005-3","Oxford University Press, ");
INSERT INTO bocker VALUES("Chemistry : for the IB Diploma, ","","","","Neuss","Geoffrey ","5","","0","978-0-19-839002-2","Oxford University Press, ");
INSERT INTO bocker VALUES("Mathematics 1.","1.ed.","","","Nordin","Jan ","43","","0","978-91-973821-2-0","2012");
INSERT INTO bocker VALUES("Mathematics. Course B","2.ed.","","","Nordin","Jan","27","","0","91-973821-0-8","");
INSERT INTO bocker VALUES("Mathematics. Course A","3.ed. 2007","","","Nordin","Jan","10","","0","91-973821-1-6","");
INSERT INTO bocker VALUES("Psychology : the science of mind and behavour","European ed.","","","Passer","Michael","9","","0","978-0-07-711836-5","McGraw-Hill Higher educat");
INSERT INTO bocker VALUES("Psychology : the science of mind and behavour ","2. ed.","","","Holt","Nigel","2","","0","978-0-07-713640-6","McGraw-Hill Higher Educat");
INSERT INTO bocker VALUES("Miljö- och energikunskap","1. uppl. ","","","Pleijel","Karin","55","","0","978-91-40-67673-0","Gleerup, 2012");
INSERT INTO bocker VALUES("Grammatik-Trainer 2","","","Grammatikövningar för steg 4","Sturmhoefel","Horst","17","","0","978-91-44-03508-6","Studentlitteratur, 2006");
INSERT INTO bocker VALUES("Unsere Welt neu. 1. Textbok, CD","","","","Sturmhoefel ","Horst","14","","0","91-44-04720-7","Studentlitteratur, 2006");
INSERT INTO bocker VALUES("Mimniräknare TI-89","","","","","","46","","0","999999999999999","");
INSERT INTO bocker VALUES("Internationella relationer - i en ny tid","1. uppl. ","","","Agrell","Wilhelm","30","","0","978-9140-63838-0","Gleerup, 2002, tr. 2007");
INSERT INTO bocker VALUES("Internationella relationer - i en ny tid "," 2. uppl. ","","","Agrell","Wilhelm ","4","","0","978-91-40-65234-8","Gleerup, 2008, tr. 2009");
INSERT INTO bocker VALUES("Forum : samhällskunskap. 1.","","","","Brolin","Krister","32","","0","978-91-622-5177-2","Bonnier utbildning, 2006");
INSERT INTO bocker VALUES("Medicinsk fysik","1. uppl. ","","","Berglund","Eva","14","","0","978-91-44-03796-7","Studentlitteratur, 2007");
INSERT INTO bocker VALUES("Calculus for the IB Diploma : Mathematics Higher Level : Topic 9-option","","","","Fannon","Paul","7","","0","978-91-107-63289-9","Cambridge University, 2013");
INSERT INTO bocker VALUES("Biology for the IB diploma","","","","Clegg","Christopher James","17","","0","978-0-340-92652-9","Hodder Education, 2007");
INSERT INTO bocker VALUES("English as a global language","2. ed. ","","","Crystal","David ","4","","0","978-0-521-53032-3","Cambridge University, 2003");
INSERT INTO bocker VALUES("Abnormal Psychology","9. ed.","","","Davison","Gerald","6","","0","978-0-471-44910-2","Wiley, 2004");
INSERT INTO bocker VALUES("Abnormal Psychology ","8. ed. ","","","Davison ","Gerald  ","14","","0","978-0471-39221-7","Wiley, 2004");
INSERT INTO bocker VALUES("A dictionary of chemistry","3. ed. ","","","Daintith","John","10","","0","978-0-19-280031-2","Oxford University, 1996");
INSERT INTO bocker VALUES("A dictionary of chemistry ","4. ed.","","","Daintith ","John ","8","","0","978-0-19-280101-2","Oxford University, 2000");
INSERT INTO bocker VALUES("Diskret matematik för gymnasiet","1. uppl.","","","Wallin","Hans","40","","0","978-91-47-01697-6","Liber, 2002");
INSERT INTO bocker VALUES("Webbutveckling","","","","Gunther","Lars","32","","0","978-91-7379-175-5","Thelin läromedel, 2012");
INSERT INTO bocker VALUES("Viewpoints. 1.","1. uppl. ","","","Gustafsson","Linda","133","","0","978-91-40-67160-8","Gleerup, 2011, tr. 2012");
INSERT INTO bocker VALUES("Chemistry","","","","Harwood","Richard","17","","0","0-521-57628-8","Camebridge University Press, 1998");
INSERT INTO bocker VALUES("Chemistry","New ed.","","","Harwood ","Richard ","4","","0","978-0-521-53093-4","Cambridge University Press, 2002");
INSERT INTO bocker VALUES("Interaktiv multimedia med Flash CS3, Innehåller CD","","","","Haugland","Astrid","74","","0","978-91-636-0935-0","Pagina, 2008");
INSERT INTO bocker VALUES("Teknisk psykologi","1. uppl.","","","Danielsson","Mats","24","","0","91-27-70660-5","NoK, 2001");
INSERT INTO bocker VALUES("E2000 : baskurs i företagsekonomi. 1. Basbok","3. uppl.","","","Andersson","John-Olof","20","","0","978-91-47-06225-6","Liber");
INSERT INTO bocker VALUES("E2000 classic : baskurs i företagsekonomi 1. Problembok ","3. uppl.","","","Andersson ","John-Olof ","28","","0","978-91-47-06245-4 ","Liber, 2001, tr. 2002  ");
INSERT INTO bocker VALUES("Exponent A, Röd. Bok, DVD","1. uppl.","","","Gennow","","35","","0","91-40-63859-6","Gleerup, 2003");
INSERT INTO bocker VALUES("Eponent C. Röd","1.uppl. ","","","Gennow","","28","","0","978-91-40-64293-3","Gleerup, 2004, tr. 2010");
INSERT INTO bocker VALUES("Exponent D. Röd","1. uppl.","","","Gennow","","33","","0","978-91-40-64472-5","Gleerup, 2005, tr. 2008");
INSERT INTO bocker VALUES("Exponent E. Röd","1. uppl.","","","Gennow","","19","","0","978-91-40-644674-3","Gleerup, 2006, tr. 2007");
INSERT INTO bocker VALUES("Exponent C. Röd. Bok, DVD","1. uppl. ","","","Gennow","","28","","0","978-91-40-64293-6","Gleerup, 2004, tr. 2010");



