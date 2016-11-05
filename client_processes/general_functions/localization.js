	// Internationalization : System supports English and Greek language
	
	var login_field_values;
	var langPack = undefined;
	var langPack_EN = {	//Guest
						guestBannerTitle: "Guest Area",
						guestBannerText: "Here you will find general information about the hospital. "
										 +"You can also register as a patient to obtain "
										 +"access to additional functionality or log in as member of the medical "
										 +"or administrative staff.</br>",						
						login: "Login",
						register: "Register",
						home: "Home",
						aboutUs: "About us",
						services: "Services",
						contactUs: "Contact us",
						guestHomeTitle: "Welcome!",
						guestHomeContent: "<h1> Welcome to the Guest Area. </h1>"		
									+"<p> The hospital is on duty daily. </br> </p>"
									+"<h1> News </h1>"
									+"<p> You can register as a patient in order to access additional features or " 
									+"log in as member of the medical or administrative staff.</p>",
						aboutUsTitle: "About us.",
						aboutUsContent: "<h1>Who we are</h1>"
										+"<p>The General Hospital 'xxx' employs 1500 workers and developed beds have "
										+"declined in 402 of the 700 beds provided for the operation of the Agency, " 
										+"because of structural inadequacy of several buildings, a result of their " 
										+"age and the last earthquake of 1999. With the implementation of Law. " 
										+"3329/05 xxx gradually changed by a specialist in Orthopaedic Hospital, " 
										+"able to cover the increased and real needs of the wider xxx. Serving " 
										+"approximately 1.5 million. Inhabitants. In this direction the hospital "
										+"has turned a significant number of orthopedic beds in beds of Sector of "
										+"Pathology, paying them in Medicine as in Cardiology and Cardiac Intensive "
										+"Care Unit. Alongside the hospital proceeded with the development of new "
										+"departments, such as Maxillofacial Surgeons, the Endocrinology and Dietetic. "
										+"At the same time, designed and start immediately Increased Care Unit, " 
										+"Department of Gastroenterology.</p>",
						servicesTitle: "Our Services",
						servicesContent: "<p> You can now subscribe to the system and access the features provided by "
										+"the system such as:"
										+"<ul>"
											+"<li style='color: #566c71'> Make online exam appointment. </li>"
											+"<li style='color: #566c71'> Get informed online about new test results. </li>"
											+"<li style='color: #566c71'> Access your exams history. </li>"
										+"</ul> </p> </p>",
						contactUsTitle: "Contact Us",
						contactUsContent: "<h1> Contact us...  </h1>"
										+"<p> Using the form below. </br>"
										+"We will answer to you as soon as possible! </p>",
						Map :"Map",
						Address :"Address",
						Phone :"Phone",
						Email :"E-mail",						
						
						loginMsg :"Insert Username and Password!",
						pwdError :"The Username or Password is invalid! </br> Please try again! ",
						username :"  Username",
						Password :"Password",
						password :"Password",
						cpassword :"Password",						
						loginBtn :"Login",	
						message : "  Message",
						sendMailBtn: "Send",
						sendSucceed: "Message sent!",	
						error: "Error",	
						ERROR : "ERROR",	
						registerTitle: "Patient registration",
						firstStep : "First step </br> Personal Information",
						secondStep : "Second step</br> Contact Information",
						thirdStep : "Third step</br>Password & Insurance",	
						thirdStepDoc : "Third step</br>Profession info & Password",
						starFields : "Fields with (*) are mandatory.",
						Completed_0 : "0% Completed",
						Completed_33 : "33% Completed",
						Completed_66 : "66% Completed",
						Completed_100 : "100% Completed",	
						name : "* Name",
						surname :"* Surname",
						fathersName : "* Father's name",
						male : "  Male",
						female : "  Female",
						Sex : "Sex",
						birthDate : "* Birth date",
						address : "* Address",
						city : "* City",
						postCode : "  Post code",						
						phone : "  Ηome Phone",
						cellPhone : "  Mobile Phone",
						email :"* E-mail",
						passConfirm : "Confirm Password",
						insurance : " Insurance",
						insuranceOption1 : "None",
						insuranceOption2 : "Choice A",
						insuranceOption3 : "Choice B",
						insuranceOption4 : "Other",
						insuranceCode : "* Insurance code",
						regCompleted : "Your registration is now completed!",					
						regSubMsg : "You can now log into the system!",
						regFailed : "An error came up!",
						regSubFaied : "Please try again.",
						regFailed2 : "An error came up. Please try again",
						footer: "My Bachelor Thesis ",			
						
						//buttons
						submit_first : "Next",
						submit_second : "Next",
						back_second : "Back",
						submit_fourth : "Submit",
						back_third : "Back",
						
						//error messages
						formError1 :"The username must be longer than 3 characters",
						formError2 :"Insert username",
						formError3 :"The password must be longer than 5 characters",
						formError4 :"Insert password",
						formError5 :"This field is mandatory",
						formError6 :"Invalid E-mail value",
						formError7 :"Error",
						formError8 :"Enter value with more than 3 characters",
						formError9 :"Insert a valid date (dd/mm/yyyy)",
						formError10 :"Insert at least 1 phone number",
						formError11 :"Invalid value",
						formError12 :"The e-mail address is already used!",
						formError13 :"Insert a password with at least 5 characters",
						formError14 :"The password and the confirmation are not the same",
						formError15 :"Insert a valid time (hh:mm)",
						formError16 :"Invalid dates",
						formError17 :"Invalid date",
						formError18 :"Invalid start date",
						formError19 :"Invalid end date",
						
						sessionExpired:"Session Expired",
						loginAgain:"Login again to continue..",
						
						////////DOCTOR////////
		
						doctorBannerTitle : "Medical Staff Area",
						doctorBannerText : "<p>You are logged in as Doctor.<br>"
										+"You can now access your daily and weekly appointment,"
										+"shift, on call, holidays and days off schedule and you can"
										+"post results for examinations.</p>",
						doctorLabel: "Doctor",
						news : "News",
						examinations : "Examinations",
						insertResults : "New diagnosis",
						schedule : "Schedule",
						daily : "Daily",
						weekly : "Weekly",
						duties_Shifts : "Call duties / Work shifts",
						editProfile : "  Edit My Profile",
						editProfileTitle : "Edit profile",
						doctorNewsTitle : "News",
						doctorNewsContent : "You can now access your daily and weekly appointment, shift, on call,"				
									+"holidays and days off schedule and you can post results for examinations.",
						insertDiagnosisTitle : "Insert diagnosis",
						type : "Type",
						patient : "Patient",
						date : "Date",
						time : "Time",
						resultsTitle: "Results:",
						resultsMsg: "<i>Click here to insert your diagnosis...:</i>",
						results : "Results",
						insertDiagnosisMsg : "Insert your diagnosis:",
						submit_result : "Submit",
						cancel_result : "Cancel",
						noMoreExams : "No more exams",
						noNewExams : "There are corrently no examinations for diagnosis...",
						more : "More...",
						contactInfo : "Contact info",
						infoEdit : "Personal info",
						moreInfo : "More info",
						contactInfoEdit : "Contact info edit",
						passEdit : "Change password",
						passEditMsg : "Your previous password is required",
						cityLabel : "City",
						oldPass : "Old password",
						newPass : "New password",
						newPassconfirm : "Confirm new password",
						oldPassword : "Password",
						newPassword : "Password",
						passwordConf : "Password",						
						editSuccess : "Changes succesfully submitted!",
						wrongPass: "Invalid password",
						submit_edit_first : "Update Profile",
						submit_edit_second : "Update Profile",
						submit_edit_third : "Update Profile",
						logout : "Logout",
						
						////////PATIENT////////
						patientBannerTitle: "Patient Area",
						patientBannerText: "You are logged in as patient.</br>"
										+ "Now you can book your appointments for examinations selecting "
										+ "the doctor of your choice, see the results of your tests and " 
										+ "access your examinations history.",	
						patientLabel : "Welcome",						
						newExam : "New Exam",
						newResult : "Results",
						select : "Select",
						exams_type : "Εxamination type",
						history : "History",
						examsHistory : "Examinations",
						examsHistoryLabel : "Examinations History",
						doctor : "Doctor",
						submit_exam : "Submit",
						EmailEdit : "E-mail edit",
						passForMail : "Your password is required to edit your e-mail.",
						newExamTitle: "New examination",
						useInsuranceMsg : "Use your insurance:",
						bookExamMsg : "Fill the form  on the left to book your appointment for examinations.",
						bookExamSubMsg : "If you leave the doctor field blank, the doctor will be chosen automatically by the system.",
						appointmentComplete : "Your appointment is succesfully submitted!", 
						appointmentCompleteSub : "You will soon recieve an e-mail with your appointement details!! ",
						availableDoctors : "Available doctors",
						warningPopup1 : "Please choose valid</br>examination category...",
						warningPopup2 : "Please choose </br> date and time first...",
						warningPopup3 : "Sorry!</br>There is no available doctor! </br> Choose another date or time...",
						examWarning1 : "Invalid examination type!",
						examWarningSub1 : "Choose a type from the list.",
						examWarning2 : "Invalid doctor!",
						examWarningSub2 : "Choose a doctor from the list.",
						examWarning3 : "The doctor is no loner available!",
						examWarning4 : "We are sorry!</br>There is no available doctor!",
						examWarningSub4 : "Choose different date or time.",	
						examWarning5 : "We are sorry!</br>There is no available ward!",
						examWarning6 : "CONNECTION ERROR",
						examsResultsTitle : "Examinations Results",
						examsHistoryTitle : "Examinations History",
						exams : "Exams",
						from : "From",
						to : "To",
						submit_filter : "OK",
						noNewResults : "There are no results available...",
						noMoreResults : "No more results...",
						selectCategory: "Select category :",
						
						radiological_exam : "Radiological",
						dermatologic_exam :	"Dermatologic", 
						gynecological_exam : "Gynecological",
						cardiac_exam : "Cardiac",
						pathological_exam :	"Pathological",		
						ophthalmological_exam :	"Ophthalmological",	
						orthopaedic_exam : "Orthopaedic",
						endocrine_exam : "Endocrine",
						neurological_exam :	"Neurological",		
						
						radiologist : "Radiologist",
						dermatologist : "Dermatologist",
						gynecologist : "Gynecologist",   
						cardiologist : "Cardiologist",
						pathologist : "Pathologist",			
						oculist : "Oculist",	
						orthopedist : "Orthopedist",
						endocrinologist : "Endocrinologist",
						neurologist : "Neurologist",
						
						radiology_unit : "Radiology",
						dermatology_unit : "Dermatology",	
						gynecology_unit : "Gynecologic",      
						cardiology_unit : "Cardiology",
						pathology_unit : "Pathology",			
						opthalmology_unit : "Ophthalmology",	
						orthopedic_unit : "Orthopaedic",
						endocrine_unit : "Endocrine",
						neurological_unit : "Neurological",						

						////////MANAGER////////
						managerBannerTitle : "Administrative Staff Area",
						managerBannerText : "You are logged in as Administrative Staff. </br>"
											+"You can now access the daily and weekly schedule "
											+"of medical staff and medical units. You can also " 
											+"search, insert or delete medical staff if its required.",
						managerLabel: "Admin Staff",						
						news : "News",
						medicalStaff : "Medical Staff",
						insertStaff : "Insert New Medical Staff",
						newMedicalStaffTitle : "Insert New Medical Staff",
						searchStaff : "Search Medical Staff",
						schedule : "Schedule",
						medicalStaff2 : "Medical Staff",						
						units : "Units",
						contactUs: "Contact us",
						news :"News", 
						managerHomeTitle : "Hospital News",
						managerHomeContent : "You can now access the daily and weekly schedule of medical staff "
										+"and medical units. You can also search, insert or delete medical staff "
										+"if its required",
						mothersName : "  Mother's name",	
						workPhone : " Work Phone",
						unitsBoxTitle : "CHOOSE UNIT",						
						unitsBoxSubmit : "OK",
						unitsBoxCancel : "Cancel",
						unitsBoxTitle : "CHOOSE UNIT",
						cardTitle : " DOCTOR'S PROFILE",
						cardName : "Name: ",
						cardSurname : "Surname: ",
						cardSpecialty : "Specialty: ",
						cardAge : "Birth Date: ",
						cardSex : "Sex: ",
						cardFatherName : "Father's Name: ",
						cardMotherName : "Mother's Name: ",
						cardWorkPhone : "Work Phone: ",
						cardHomePhone : "Home Phone: ",
						cardMobilePhone : "Mobile Phone: ",
						cardEmail : "E-mail: ",		
						cardAddress : "Address: ",
						cardCity : "City: ",
						cardPostCode : "Post Code: ",
						cardHireDate : "Hire Date: ",
						cardMore : "More Information: ",
						cardDeleteBtn : "Delete Doctor",
						specialtyLabel : "Speciality",
						AgeLabel : "Age",
						moreInfoLabel : "More info",
						passwordLabel : "Password",
						cPasswordLabel : "Confirm Password",
						workPhoneStaff	: "* Work Phone",					
						docRegCompleted : "Doctor's registration is now completed!"						
					};

	
						


    var langPack_GR = { guestBannerTitle: "Περιοχή Επισκεπτών",
						guestBannerText: "Εδω μπορείτε να δείτε γενικές πληροφορίες για το νοσοκομείο. "
										+"Μπορείτε επίσης να εγγραφείτε ως ασθενής ωστε να αποκτήσετε "
										+"πρόσβαση σε επιπλέον δυνατότητες ή να συνδεθείτε ως μέλος ιατρικού "
										+"και διοικητικού προσωπικού, προσωπικού γραμματείας ή ως διαχειριστής.",
						login: "Σύνδεση",
						register: "Εγγραφή",
						home: "Αρχική",
						aboutUs: "Σχετικά με εμάς",
						services: "Υπηρεσίες",
						contactUs: "Επικοινωνία",
						guestHomeTitle: "Καλως ήλθατε!",
						guestHomeContent: "<h1> Καλως ηλθατε στην περιοχή επισκεπτών. </h1>"		
									+"<p> Το νοσοκομείο εφημερεύει καθημερινά. </br> </p>"
									+"<h1> Νέα </h1>"
									+"<p> Μπορείτε να εγγραφείτε ως ασθενής ωστε να αποκτήσετε πρόσβαση σε επιπλέον "
									+"δυνατότητες ή να συνδεθείτε ως μέλος ιατρικού ή διοικητικού προσωπικού.</p>",
						aboutUsTitle: "Σχετικα με εμάς ",
						aboutUsContent: "<h1>Ποιοί είμαστε</h1>"
										+"<p>Το Γενικό Νοσοκομείο «χχχ» απασχολεί 1500 εργαζόμενους και οι ανεπτυγμένες κλίνες "
										+"έχουν περιοριστεί σε 402 από τις 700 κλίνες που προβλέπει ο Οργανισμός λειτουργίας "
										+"του, εξαιτίας της στατικής ακαταλληλότητας αρκετών κτιρίων, αποτέλεσμα της παλαιότητας "
										+"αυτών αλλά και των τελευταίων σεισμών του 1999. Με την εφαρμογή του Ν. 3329/05 το χχχ "
										+"μεταβάλλεται σταδιακά από ειδικό Ορθοπαιδικό σε Γενικό Νοσοκομείο, ικανό να καλύπτει "
										+"τις αυξημένες και πραγματικές ανάγκες της ευρύτερης περιοχής της χχχ. Εξυπηρετεί " 
										+"περίπου 1,5 εκατ. κατοίκους. Προς την κατεύθυνση αυτή το Νοσοκομείο μετέτρεψε σημαντικό "
										+"αριθμό ορθοπαιδικών κλινών σε κλίνες του Παθολογικού Τομέα, αποδίδοντας τες στις " 
										+"Παθολογικές κλινικές καθώς στην Καρδιολογική Κλινική και στην Καρδιολογική Μονάδα " 
										+"Εντατικής Παρακολούθησης. Παράλληλα το Νοσοκομείο προχώρησε στην ανάπτυξη νέων τμημάτων, " 
										+"όπως το Γναθοχειρουργικό, το Ενδοκρινολογικό και το Διαιτολογικό. Παράλληλα, σχεδιάζεται " 
										+"και η άμεση έναρξη λειτουργίας της Μονάδας Αυξημένης Φροντίδας, του Γαστρεντερολογικού  "
										+"Τμήματος. </p>",
						servicesTitle: "Υπηρεσίες",
						servicesContent: "<p> Τώρα μπορείτε να εγγραφείτε στο σύστημα και να αποκτήσετε πρόσβαση "
										+"στις δυνατότητες που παρέχει το σύστημα όπως:"
										+"<ul>"
											+"<li style='color: #566c71'>Πραγματοποίηση online ραντεβού εξετάσεων. </li>"
											+"<li style='color: #566c71'>Ενημέρωση online για νέα αποτελέσματα εξετάσεων.</li>"
											+"<li style='color: #566c71'>Πρόσβαση στο ιστορικό εξετάσεων </li>"
										+"</ul> </p> </p>",
						contactUsTitle: "Επικοινωνία",
						contactUsContent: "<h1> Επικοινωνήστε μαζί μας... </h1>"
										+"<p> Χρησιμοποιώντας την παρακάτω φόρμα. </br>"
										+"Θα επικοινωνίσουμε μαζί σας το συντομότερο δυνατό</p>",						
						Map :"Χάρτης",
						Address :"Διεύθυνση",
						Phone :"Τηλέφωνο οικίας",		
						Email :"E-mail",
						
						loginMsg :"Πληκτρολογήστε όνομα χρήστη και κωδικό πρόσβασης! ",
						pwdError :"Το όνομα χρήστη ή ο κωδικός πρόσβασης δεν ειναι έγκυρα! Προσπαθηστε ξανά!",
						username :"  Όνομα Χρήστη",
						Password :"Κωδικός Πρόσβασης",
						password :"Κωδικός",
						cpassword :"Κωδικός",
						loginBtn :"Είσοδος",
						name : "'* Ονομα",
						message : "  Μήνυμα",
						sendMailBtn: "Αποστολή",
						sendSucceed : "Επιτυχής αποστολή!",
						error : "Σφάλμα",
						ERROR : "ΣΦΑΛΜΑ",
						registerTitle: "Εγγραφή Ασθενή",
						firstStep : "Βήμα 1ο </br> Προσωπικά Στοιχεία.",
						secondStep : "Βήμα 2ο </br> Στοιχεία Επικοινωνίας.",
						thirdStep : "Βήμα 3ο </br> Κωδικός & Στοιχεία Ασφάλισης.",	
						thirdStepDoc : "Βήμα 3ο </br>Βιογραφικό & Κωδικός Πρόσβασης",
						starFields :"Τα πεδία με (*) είναι υποχρεωτικά.",
						Completed_0 : "0% Ολοκληρώθηκε",
						Completed_33 : "33% Ολοκληρώθηκε",
						Completed_66 : "66% Ολοκληρώθηκε",
						Completed_100 : "100% Ολοκληρώθηκε",						
						name : "* Όνομα",
						surname : "* Eπώνυμο",
						fathersName : "* Όνομα Πατρώς",
						male : "  Άντρας",
						female : "  Γυναίκα",
						Sex : "Φύλο",
						birthDate : "* Ημερομηνία Γέννησης",
						address : "* Διεύθυνση",
						city : "* Πόλη",
						postCode : "  Τ.Κ",	
						phone : "  Τηλέφωνο Οικίας",
						cellPhone : "  Κινητό Τηλέφωνο",
						email :"* E-mail",
						passConfirm : "  Επιβεβαίωση κωδικού",
						insurance : " Ασφάλιση",
						insuranceOption1 : "Κανένα",
						insuranceOption2 : "ΙΚΑ",
						insuranceOption3 : "ΤΕΒΕ",
						insuranceOption4 : "Άλλο",
						insuranceCode : "* Κωδικός Φορέα",
						regCompleted : "Η εγγραφή σας ολοκληρώθηκε",
						regSubMsg : "Τώρα μπορείτε να εισέλθετε στο σύστημα!",
						regFailed : " Προέκυψε κάποιο σφάλμα κατά την εγγραφή! ",
						regSubFaied : "Προσπαθήστε ξανά αργότερα.",
						regFailed2 : "Προέκυψε κάποιο σφάλμα. Προσπαθήστε ξανά αργότερα.",													
						footer: "Πτυχιακή εργασία",		

						//buttons
						submit_first : "Επόμενο",
						submit_second : "Επόμενο",
						back_second : "Προηγούμενο",
						submit_fourth : "Υποβολή",
						back_third : "Προηγούμενο",						
						
						//error messages
						formError1 :"Το όνομα χρήστη πρεπει να είναι μεγαλύτερο από 3 χαρακτήρες",
						formError2 :"Παρακαλώ εισάγετε όνομα χρήστη",
						formError3 :"Το όνομα χρήστη πρεπει να είναι μεγαλύτερο από 5 χαρακτήρες",
						formError4 :"Παρακαλώ εισάγετε κωδικό πρόσβασης",
						formError5 :"Το πεδίο αυτό είναι υποχρεωτικό",
						formError6 :"Εσφαλμένη διεύθυνση E-mail",
						formError7 :"ΣΦΑΛΜΑ",
						formError8 :"Παρακαλώ εισάγετε τιμή μεγαλύτερη των 3 χαρακτήρων",
						formError9 :"Παρακαλώ εισάγετε έγκυρη ημερομηνία (dd/mm/yyyy)",
						formError10 :"Εισάγετε τουλάχιστον 1 αριθμό τηλεφώνου.",
						formError11 :"Μή έγκυρη τιμή",
						formError12 :"Η διεύθυνση e-mail χρησιμοποιείται ήδη!",
						formError13 :"Εισάγετε κωδικό τουλάχιστον 5 χαρακτήρων",
						formError14 :"Ο κωδικός σας δεν είναι ο ίδιος με το πεδίο επαλήθευσης",
						formError15 :"Παρακαλώ εισάγετε έγκυρη ώρα (hh:mm)",
						formError16 :"Invalid dates",
						formError17 :"Invalid date",
						formError18 :"Invalid start date",
						formError19 :"Invalid end date",
						
						sessionExpired:"To Session σας εχει λήξει",
						loginAgain:"Συνδεθείτε ξανά για να συνεχίσετε..",
						
						////////DOCTOR////////
		
						doctorBannerTitle :"Περιοχή Επισκεπτών ",
						doctorBannerText :"<p>Έχετε συνδεθεί ως ιατρός.<br>"
										+"Τώρα μπορείτε να δείτε το ημερήσιο και εβδομαδιαίο πρόγραμμα"
										+"ραντεβού, βαρδιών, εφημεριών, αδειών και ρεπό καθώς και να "
										+"αναρτήσετε αποτελέματα για εξετάσεις της οποίες εκτελέσατε.",
						doctorLabel: "Ιατρός",
						news :"Νέα", 
						examinations: "Εξετάσεις",
						insertResults :"Νέα διάγνωση",
						schedule :"Πρόγραμμα",
						daily :"Ημέρας",
						weekly :"Εβδομάδας",
						duties_Shifts :"Βαρδιών / Εφημεριών",
						editProfile: "Eπεξεργασία προφίλ",
						editProfileTitle: "Αλλαγή στοιχείων",
						doctorNewsTitle :"Νέα",
						doctorNewsContent :"Τώρα μπορείτε να δείτε το ημερήσιο και εβδομαδιαίο πρόγραμμα ραντεβού, βαρδιών, εφημεριών, αδειών και ρεπό καθώς και να αναρτήσετε διάγνωση για εξετάσεις της οποίες εκτελέσατε.",
						insertDiagnosisTitle :"Εισαγωγή διάνωσης",
						type :"Τύπος",
						patient :"Ασθενής",
						date :"Ημερομηνία",
						time :"Ώρα",
						resultsTitle: "Διάγνωση:",
						resultsMsg: "<i>Κάντε Click εδώ για να εισάγετε διάγνωση...:</i>",
						results :"Διάγνωση",
						insertDiagnosisMsg :"Εισάγετε διάγνωση:",
						submit_result :"Υποβολή",
						cancel_result :"Ακύρωση",
						noMoreExams :"Δεν υπάρχουν περισσότερες εξετάσεις",
						noNewExams :"Δεν έχετε εξετάσεις προς διάγνωση",		
						more :"Περισσότερα...",				
						contactInfo :"Στοιχεία επικοινωνίας",
						infoEdit :"Ενημέρωση Βιογραφικού",
						moreInfo :"Βιογραφικό",
						contactInfoEdit :"Αλλαγή στοιχείων",
						passEdit :"Αλλαγή Κωδικού Πρόσβασης",
						passEditMsg :"Απαιτείται η εισαγωγή του παλιού κωδικού σας.",
						cityLabel : "Πόλη",
						oldPass :"Παλιός Κωδικός",
						newPass :"Νέος Κωδικός",
						newPassconfirm :"Επιβεβαίωση Κωδικού",
						oldPassword : "Κωδικός",
						newPassword : "Κωδικός",
						passwordConf : "Κωδικός",						
						editSuccess : "Οι αλλαξές σας καταχωρήθηκαν!",
						wrongPass : "Εσφαλμένος κωδικός",
						submit_edit_first : "Αλλαγή Στοιχείων",
						submit_edit_second : "Αλλαγή Στοιχείων",
						submit_edit_third : "Αλλαγή Στοιχείων",
						logout: "Αποσύνδεση",
						
						////////PATIENT////////
						patientBannerTitle: "Περιοχή Ασθενή ",
						patientBannerText: "Έχετε συνδεθεί στο σύστημα ώς ασθενής.</br>"
										+ "Τώρα μπορείτε να κλήσετε ραντεβού για εξετάσεις επιλέγοντας τον "
										+ "ιατρό της αρεσκείας σας, να δείτε τα αποτελέσματα των εξετάσεων σας, "
										+ "όπως επίσης και να δείτε το ιστορικό των εξετάσεων σας. ",
						patientLabel : "Χρήστης",										
						newExam : "Νέα Εξέταση",
						newResult : "Αποτελέσματα",
						select : "Επιλογή",
						exams_type : "Εξετάσεις",
						history : "Ιστορικό",
						examsHistory : "Εξετάσεων",
						examsHistoryLabel : "Ιστορικό Εξετάσεων",
						doctor : "Ιατρός",
						submit_exam : "Υποβολή",
						EmailEdit : "Αλλαγή E-mail",
						passForMail : "Ο κωδικός πρόσβασης χρειάζεται για την αλλαγή e-mail.",
						newExamTitle: "Νέα Εξέταση",
						useInsuranceMsg : "Χρήση ασφάλισης:",
						bookExamMsg : "Συμπληρώστε την φόρμα για να κλήσετε ραντεβού για εξετάσεις.",
						bookExamSubMsg : "Εάν αφήσετε το πεδίο ιατρού κενό, ο ιατρός θα επιλεχθεί αυτόματα απο το σύστημα",
						appointmentComplete : "Το ραντεβού σας καταχωρήθηκε με επιτυχία", 	
						appointmentCompleteSub : "Θα λάβετε σύντομα e-mail με τα στοιχεία του ραντεβού σας!!",
						availableDoctors : "Διαθέσιμοι ιατροί",
						warningPopup1 : "Παρακαλώ επιλέξτε έγκυρη κατηγορία εξετάσεων...",
						warningPopup2 : "Παρακαλώ επιλέξτε πρώτα ημερομηνίαία και ώρα...",
						warningPopup3 : "Λειπούμαστε!</br>Δεν υπάρχει διαθέσιμος ιατρός! </br> Παρακαλώ επιλέξτε διαφορετική ημερομηνία ή ώρα ...",
						examWarning1 : "Εσφαλμένος τύπος εξέτασης!",
						examWarningSub1 : "Επιλέξτε εξέταση απο τη λίστα.",
						examWarning2 : "Ο ιατρός δεν υπάρχει!",
						examWarningSub2 : "Επιλέξτε ιατρό απο τη λίστα.",		
						examWarning3 : "Ο ιατρός δεν είναι πλέον διαθέσιμος!",
						examWarning4 : "Λυπουμαστε!</br>Δεν υπάρχει διαθέσιμος ιατρός!",
						examWarningSub4 : "Επιλέξτε διαφορετική ημέρα ή ώρα.",
						examWarning5 : "Λυπουμαστε!</br>Δεν υπάρχει διαθέσιμος θάλαμος!",
						examWarning6 : "ΠΡΟΒΛΗΜΑ ΣΥΝΔΕΣΗΣ",
						examsResultsTitle : "Αποτελέσματα Εξετάσεων",
						examsHistoryTitle : "Ιστορικό εξετάσεων",
						exams : "Εξετάσεις",
						from : "Από",
						to : "Εώς",
						submit_filter : "OK",
						noNewResults : "Δεν υπάρχουν αποτελέσματα εξετάσεων...",
						noMoreResults : "Δεν υπάρχουν άλλα αποτελέσματα...",
						selectCategory : "Επιλέξτε κατηγορία :",
						
						radiological_exam : "Ακτινολογικές",
						dermatologic_exam : "Δερματολογικές",
						gynecological_exam : "Γυναικολογικές",      
						cardiac_exam : "Καρδιολογικές",
						pathological_exam : "Παθολογικές",			
						ophthalmological_exam : "Οφθαλμολογικές",	
						orthopaedic_exam : "Ορθοπεδικές",
						endocrine_exam : "Ενδοκρινολογικές",
						neurological_exam : "Νευρολογικές",							
						
						radiologist : "Ακτινολόγος", 
						dermatologist : "Δερματολόγος",
						gynecologist : "Γυναικολόγος",
						cardiologist : "Καρδιολόγος",	
						pathologist : "Παθολόγος",
						oculist : "Ωτορινολαρυγγολόγος",
						orthopedist : "Ορθοπεδικός",
						endocrinologist : "Ενδοκρινολόγος",
						neurologist : "Νευρολόγος",
						
						radiology_unit : "Ακτινολoγικό",
						dermatology_unit : "Δερματολoγικό",	
						gynecology_unit : "Γυναικολoγικό",      
						cardiology_unit : "Καρδιολoγικό",
						pathology_unit : "Παθολoγικό",			
						opthalmology_unit : "Οφθαλμολογικό",	
						orthopedic_unit : "Ορθοπεδικό",
						endocrine_unit : "Ενδοκρινολoγικό",
						neurological_unit : "Νευρολoγικό",
						
						////////MANAGER////////
						managerBannerTitle : "Περιοχή Διοικητικού Προσωπικού </br>",
						managerBannerText : "Τώρα μπορείτε να επεξεργαστείτε το πρόγραμμα τμημάτων, " 
											+"και προσωπικού. Μπορείτε επίσης να, εισάγετε αναζητήσετε και διαγράψετε "
											+"ιατρικό προσωπικό.",
						managerLabel: "Διαχειριστής",
						news : "Νέα",
						medicalStaff : "Προσωπικό",
						insertStaff : "Εισαγωγή Προσωπικού",
						newMedicalStaffTitle : "Εισαγωγή Νέου Προσωπικού",
						searchStaff : "Αναζήτηση Προσωπικού",												
						schedule : "Πρόγραμμα",
						medicalStaff2 : "Προσωπικού",						
						units : "Τμημάτων",
						contactUs: "Επικοινωνία",
						news :"Νέα", 
						managerHomeTitle : "Τα νέα του Νοσοκομείου",
						managerHomeContent : "Έχετε συνδεθεί στο σύστημα ώς Διοίκηση Προσωπικού. "
											+"Τώρα μπορείτε να δείτε το ημερήσιο και εβδομαδιαίο πρόγραμμα τμημάτων, " 
											+"και προσωπικού. Μπορείτε επίσης να αναζητήσετε, να εισάγετε και να διαγράψετε "
											+"ιατρικό προσωπικό αν αυτό απαιτείται.",
						mothersName : " Όνομα μητρός",	
						workPhone : " Τηλέφωνο Εργασίας",
						okButton : "OK",
						cancelButton : "Ακύρωση",
						unitsBoxTitle : "ΕΠΙΛΟΓΗ ΤΜΗΜΑΤΟΣ",
						cardTitle : "ΚΑΡΤΑ ΙΑΤΡΟΥ",
						cardName : "Όνομα: ",
						cardSurname : "Επώνυμο: ",
						cardSpecialty : "Ειδικότητα: ",
						cardSex : "Φύλο: ",
						cardAge : "Ημ/νια Γέννησης: ",
						cardFatherName : "Όνομα Πατρός: ",
						cardMotherName : "Όνομα Μητρός: ",
						cardWorkPhone : "Τηλέφωνο Εργασίας: ",
						cardHomePhone : "Τηλέφωνο Οικίας: ",
						cardMobilePhone : "Κινητό Τηλέφωνο: ",
						cardEmail : "E-mail: ",						
						cardAddress : "Διεύθυνση: ",
						cardCity : "Πόλη: ",
						cardPostCode : "Τ.Κ: ",
						cardHireDate : "Ημ/νια Πρόσληψης: ",
						cardMore : "Βιογραφικό: ",
						cardDeleteBtn : "Διαγραφή Ιατρού",
						specialtyLabel : "Ειδικότητα",
						AgeLabel : "Ηλικία",
						moreInfoLabel : "Βιογραφικό",
						passwordLabel : "Κωδικός Πρόσβασης",
						cPasswordLabel : "Eπιβεβαίωση Κωδικού",
						workPhoneStaff	: "* Τηλέφωνο Εργασίας",
						docRegCompleted : "Η εγγραφή ιατρού καταχωρήθηκε επιτυχώς!"
						
					};
	
	function changeLang(lang)
    {		
        var data = getAllElementsWithAttribute();

        if(data.length == 0)
            return;

		if(lang == "en")				// If language is set to English
		{
			langPack = langPack_EN;
			defaultLang = 'en';
		}
		else{							// If language is set to Greek
            langPack = langPack_GR;			
			defaultLang = 'gr';
		}
		
        for(var i = 0; i < data.length; i++)	// Handles all elements containing the data-inner attribute
		{										// to switch language
            data[i].innerHTML = langPack[data[i].getAttribute("data-inter")];		
		}
		
		if ( $('#speciality').length ) 			// Doctor's menu label
		{	
			$('#speciality').html(speciality[defaultLang]);
		}	
		
		if (document.forms[0])					// Forms input text is handled independently
		{
			if ($(document.forms).attr('class') != "noDefault"){
				var formId = document.forms[0].id;
				var fields = $('#'+formId+' input[type=text],#'+formId+' input[type=password],#'+formId+' input[type=submit],#'+formId+' input[type=button]');			
				fields.each(function()
				{
					if (document.getElementById(this.id) != null)
					{
						document.getElementById(this.id).value = langPack[this.id];				
					}
				});
			}
		}
	}

    function getAllElementsWithAttribute()
    {
		var matchingElements = [];
		var allElements = document.getElementsByTagName('*');	
		for (var i = 0, n = allElements.length; i < n; i++)
		{
			if (allElements[i].getAttribute("data-inter") !== null)
				matchingElements.push(allElements[i]);
		}
		return matchingElements;
    }
	
	$( "#english" ).click(function() {			// Switch to English
		changeLang('en');
	});
	$( "#greek" ).click(function() {			// Switch to Greek
		changeLang('gr');
	});