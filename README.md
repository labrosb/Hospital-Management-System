<h1> Hospital Management I.S</h1>
<p>	The current project is my bachelor thesis and the largest project that I developed individually. 
A full working Hospital Management IS implemented with HTML, CSS3, AJAX (PHP, JavaScript-JQuery), MySQL. 
The system consists of 4 different sections which are the guest, administrative staff, doctor and patients section.</p>

<h2> Guest section </h2>
<p> Guest section includes general information about the hospital, a communication's form and a registration form for patients.</p>

<h2> Patient's section </h2>
<p> Users that are registered with patient's rights can book an online appointment for examinations choosing the doctor of their choice among the available doctors in the particular time that are responsibe for the chosen examination type. Patient can access doctor's information and resume before selecting. Alternatively the doctor can be chosen automatically from the system, choosing the doctor with the lightest workload in the particular day that the examination will take place. User recieves details about the examination such as building, ward (automatically chosen) etc in his personal e-mail.</p>
<p> In addition patients are notified for new examination results, can access the diagnosis online and can also access past examination results, browsing through all of them or filter results by date range, specific examination types or a combination of choices.</p>
<p> Finally user can access and update his personal information and use a comunication form similar to the one in the guest section but with the sender info pre-filled.</p>

<h2> Doctor's section </h2>
<p> Doctors are notified for examinations that they performed and need to enter diagnosis. Clicking on the pending examinations, doctor can enter his diagnosis. In addition doctor has access to his schedule, chosing between general schedule, daily schedule and work-shifts / call duties schedule.</p>
<p> Finally similar to patients, doctors have access to the communications form and can update their personal information.</p>

<h2> Manager's section </h2>
<p>	Managers can add search, access personal info and remove medical staff from the system. Also managers can access doctors and units schedule adding, updating and removing events such as examinations, work-shifts, call duties and days-off. Especially when a call duty is added, the system enters also a day-off, if an available date in the next 7 working days is found.</p>						
<p> Like in all functions also here all possible restrictions have been considered.For instance events can't be added in invalid date/time (like a work-shift during the weekend or a call duty in normal working hours). Also some events cannot overwrite others while in other cases manager's confirmation is needed to complete the action. In each schedule change, doctors are notified by e-mail (in the case of adding or deleting examination also patient is informed).</p> 	

<h2> Security Considerations </h2>
<p>	During the revision, security is added into the system securing it againsed sql injections and session hijacking. Sql injections protextion is acomplished using prepared dpo statements while session hijacking protextion by updating the session id after each action if previous id update time exceeds 5'. In addition, for each page loaded or action executed, the system checks if the user has the required access rights.</p>
<br>
<p>*To be implemented soon: XSS attacks protection</p>
<p class='specialTxt'>Year of implementation : 2012 (Revised in 2016)</p>
<p class='specialTxt'>Project : Individualy implemented</p>
