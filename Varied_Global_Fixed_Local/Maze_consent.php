<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
<title>Consent Form</title>
<style>
        body {
            font-family: sans-serif;
            background: #d3d3d3;
          }
          h2 {
            margin: 5px 0;
          }
          #wrapper {
            width: 600px;
            margin: 0 auto;
            background: white;
            padding: 10px 15px;
            border-radius: 10px;
          }
        </style>
 <?php
          $current_id = $_POST['sonaid']
          $tallyfilename = "id.json";
          if (!file_exists($tallyfilename)) {
            $startingarray = array('ID_Order' => array('ID'=> $current_id, 'condition'=>'012'));
            file_put_contents($tallyfilename, json_encode($startingarray));
          }
 ?>
</head>

<body bgcolor="#ffffff">



    <form action=<?= "./Maze_preQuestion.php"?> method="POST">
      <input type="hidden" id="sonaid" name="sonaid" value=<?php echo $_POST['sonaid']?> >
      <input type="hidden" id="protocol" name="protocol" value=<?php echo $_POST['protocol']?> >
     
      <div id="wrapper">
      <h1> Consent Form </h1>

      <h2>Information/Consent Form for SONA Participants</h2>
      <h3>Updating Local and Global Probability Events During Maze Navigation</h3>
      <b>Faculty Supervisors: Dr. Britt Anderson –University of Waterloo (Psychology), ON, Canada – britt@uwaterloo.ca
519-888-4567 x43056</b><br><br>
      <b>Student Investigator: Sixuan Chen–University of Waterloo (Psychology), ON, Canada–s743chen@uwaterloo.ca</b><br>
      
<!-- 
      <p>Participation in this study is voluntary and will take around X minutes total of your time. In appreciation of your time, you will receive X credits.  By volunteering for this study, you will learn about research in psychology in general and the topic of (Info here for learning.)  In addition, you will be offered a detailed feedback sheet about the nature and purpose of this study.  You may decide to withdraw from this study at any time by advancing through to the end without submitting answers and may do so without loss of remuneration. However, as the interests of this study are in general and not in individual differences, once data has been collected researchers may have no way of knowing which data belongs to which participant due to the de-identification of data. Your identity in this study will be kept strictly confidential; your name will not be included or in any other way associated with the data collected in the study.  All de-identified data will be securely stored on secure University of Waterloo servers, and as needed on encrypted external hard drives for a minimum of seven (7) years. Furthermore, all de-identified data may be posted to an online repository to help facilitate the ongoing reproducibility effort within Psychological Science.
      The risks associated with participation in the study are expected to be no greater than what you might experience in your day to day life.(This would be tailored to fit the risk level of your study)
      You will be completing the study by an online survey operated by SONA and hosted on University of Waterloo Servers. When information is transmitted over the internet, privacy cannot be guaranteed. There is always a risk your responses may be intercepted by a third party (e.g., government agencies, hackers). SONA uses your SONA ID to avoid duplicate responses in the dataset, but we will not collect information that could identify you personally. If you prefer not to submit your online survey responses through online platform, please do not participate in this study.</p>
      
       -->
       <h3>Purpose of the Research:</h3>
       <p>You are invited to participate in a research project in the Department of Psychology at the University of Waterloo. The study is designed to investigate how people use local and global information when representing an uncertain environment. To test this we will have you navigate a maze where the walls are invisible. To make your way through you will have to choose your next step based on local cues that are only partially reliable. In addition, you will know in which direction the exit is likely to be.<p/p>
       <h3>Procedures:</h3>
       
       <p>Participation in this study is voluntary and will take around 60 minutes of your time. In appreciation of your time, you will receive 1 credit.</p>

       <p>•	The session will start with pre-questions asking for your demographic information. Responses to these are optional; you do not need to answer them.  Detailed instructions about the task will be presented after this.
</p>
      
      <p>• You will be asked to navigate a maze that we program. At each choice point, there will be local cues provided by colored circles. Some colors will indicate that direction is probably shorter. Other colors will tell you which way is probably longer
 but you should not assume that any of the cues are 100% reliable.Sometimes you may even see colors that give you no information about which is the shortest route, but merely indicate which
choices are available to you. You will have to take all this into account when making your choices as you try to navigate to the exit in as few moves as possible. While you
will be told the likely direction to the exit,that information too is not always completely reliable. You will use the arrow keys to make your choice of route and “sliders” to give us your confidence in the local and global cues. You will have a few different mazes to
navigate.</p>
       <p>•	Once you finish the task, you will be presented with a feedback letter will be presented to you at the end of the experiment that will explain a bit more about the task and our experimental goals. You will also be given a chance to ask questions and provide comments about the experiment. 
      </p>
      <h3> Potential Risks:</h3>
      <p>• There are no known or anticipated risks from participating in this research. The experimental task itself requires you to focus and have consistent responses for roughly 45 minutes. The amount of concentration needed is similar to everyday tasks.</p>
      <p>• You will be completing the study using a program running on a University of Waterloo computer. When information is transmitted over the internet, privacy cannot be guaranteed. There is always a risk your responses may be intercepted by a third party (e.g. government agencies, hackers). SONA uses your SONA ID to avoid duplicate responses in the dataset, but we will not collect information that could identify you personally. If you prefer not to submit responses through an online platform please do not participate in this study.</p>
      
      <h3>Benefits:</h3>
      <p>• By volunteering for this study, you will learn about research in psychology in general and the topic of perception and encoding of probabilities. In addition, you will be offered a detailed feedback sheet about the nature and purpose of this study.</p>
      
      <h3>Remuneration:</h3>
      <p>• Participants will receive 1.0 research participation credits.</p>
 
     

      <h3>Anonymity/Confidentiality:</h3>
    
      <p>• The data in this study will be used for research purposes only, and your confidentiality is priority. You will be given an arbitrary I.D. number different from your REG identification. Your REG identification number will identify you in order to be awarded a credit for your participation. Your identity will not appear in any report, publication, or presentation resulting from the study.</p>
      <p>Data will be encrypted and sent to a UW server where it will be stored temporarily.
Daily, the data will be securely transferred from the externally accessible University
server to a separate computer inside the University’s firewall that is password protected.
Then data will be deleted from the experiment server.</p>
      <p>• All electronic data will be stored on a password protected computer in Professor Anderson’s lab PAS lab space at the University of Waterloo. Only the researchers involved in the study will have access to this data, as needed, data will be stored on external encrypted drives. Participant identification numbers are the only piece of identifying information collected, and these will persist in the raw data in order to keep track of the data's origin, and to prevent duplication or loss of data. No identifying information will be mentioned at any point in any report, publication, or presentation.</p>
      <p>• Data will be maintained for a minimum of 7 years before being destroyed.</p>
      <p>• De-identified data related to your participation may be submitted to an open access repository or journal (i.e., the data may be publically available). These data will be completely de-identified/anonymized prior to submission by removing all personally identifying information (names, email addresses, and certain identifying demographic information) before submission and will be presented in aggregate form in publications. This process is integral to the research process as it allows other researchers to verify results and avoid duplicating research. Other individuals may access these data by accessing the open access repository. Although the dataset without identifiers may be shared publicly, your identity will always remain confidential.</p>
      
      <h3>Right to withdraw (Voluntary participation):</h3>
      <p>• Your participation is voluntary and you only have to participate in activities that you are comfortable with.</p>
      <p>• You are free to withdraw or discontinue from this study at any point. Furthermore, there is no penalty if you choose to withdraw. We ask that, if you end your participation early, you use the button we provide, rather than exit out of the window so that we can implement our early-finish procedures.</p>
      
      <h3>Follow Up:</h3>
      <p>• To obtain results from the study, please contact the researchers.</p>
      
      <h3>Questions or Concerns:</h3>
      <p>• If you have any questions or concerns, please contact the researchers using the information Sixuan Chen (s743chen@uwaterloo.ca) or Britt Anderson (britt@uwaterloo.ca).</p>
     
     
  
     
      <h3>Questions or Concerns about Ethical Conduct:</h3>

      <p>This study has been reviewed and received ethics clearance through a University of Waterloo Research Ethics Board (ORE#43113). If you have questions for the Board contact the Office of Research Ethics, at 1-519-888-4567 ext. 36005 or ore-ceo@uwaterloo.ca<p>

<p>I have read the information presented in the information letter about a study being conducted by (Sixuan Chen) under the supervision of Britt Anderson of the Department of Psychology at the University of Waterloo, ON, Canada. I have had the opportunity to contact the Researchers with any questions related to this study, and have received satisfactory answers to my questions, and any additional details I wanted. I am aware that I may withdraw from the study without loss of participation remuneration at any time by clicking through to the end of the study.
</p>    

     
      <p>By agreeing to participate in this study, you are not waiving your legal rights or releasing the investigator(s) or involved institution(s) from their legal and professional responsibilities.</p>
      <p>With full knowledge all foregoing, I agree, off my own free will, to participate in this study.</p>
     
      <input type="radio" id="consent2" name="consent2" value="Accept">I agree to participate<br>
      <input type="radio" id="consent2" name="consent2" value="Decline">I do not wish to participate<br>


      <blockquote>
      <input type="submit" value="Submit">
      <input type="reset">
      </blockquote>

    </form>

  </body>
</html>
