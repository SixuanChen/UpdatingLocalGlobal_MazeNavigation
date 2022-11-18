<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
  <title>Feedback Form</title>
  <?php
    $icon = (rand(1,10) > 5) ? "img/reddot.png" : "img/bluedot.png";
    echo '<link rel="icon" href="' . $icon . '">';
  ?>
</head>
<body bgcolor="#ffffff">

    <form action="submitfeedback.php" method="post">
    
      <input type="hidden" id="sonaid" name="sonaid" value=<?php echo $_POST['sonaid']?> >

     

    <div id="wrapper">
        <h1>Feedback Form/Letter of Appreciation</h1>
        <h2>Updating Local and Global Probability Events During Maze Navigation</h2>

       
       <p>Thank you for your participation in this study and for spending the time to help us with our research. For your involvement, you will receive 1.0 research participation credits in your psychology course.</p>
       <p>This study was designed to investigate the way people encode local and global probability events into their mental representation of the environment. 
       People might rely more on local cues (the colored circles in our task) or global cues (the direction to the exit).
       In our study, we were interested in understanding how people’s estimates of events change with experience. We will analyze how your
choices varied with the color of the local cues and the location of the exit. We will
also compare how your slider estimate of the probabilities agreed with what we set
behind the scenes when we programmed the mazes.</p>

   
        <p>As we navigate the world, we usually encode information about landmarks, scenes,
and events into our mental model of the world. We do this rapidly and without
seeming effort. We make decisions based on this information, these mental models.
This study will help us test how accurate those estimates really are and to track
how quickly they are estimated in practice. Further, we would like to understand
how different sources of probability information organized at different scales
(nearby or distant; whether in space or time). Having both a local and global cue
will help us make this comparison. Eventually we hope these data will allow us to
formulate a mathematical model that predicts human behavior during spatial navi-
gation.</p>

       <p>I would like to remind you that your identity will be confidential. Data collected during this study will be maintained for a minimum of 7 years in a locked lab, in a restricted area of the university. In this locked lab, your electronic data will be kept on a password-protected computer, to which only researchers associated with this study have access. De-identified data may also be submitted to a journal or deposited in online public repositories to support our federal granting agency's policy on open data, and will be presented in aggregate form only in publications.</p>
       <p>
       In the future, should you be interested in the outcome of this study, or the reasons for conducting the experiment in general, please contact Sixuan Chen by email at s743chen@uwaterloo.ca or Britt Anderson at britt@uwaterloo.ca.
</p> 
        <p>As a reminder, this study has been reviewed and received ethics clearance through a University of Waterloo Research Ethics Committee (ORE #43113). If you have questions for the Committee contact the Office of Research Ethics, at 1-519-888-4567 ext. 36005 or ore-ceo@uwaterloo.ca. If you have any questions for the researchers, contact Sixuan Chen
(s743chen@uwaterloo.ca) or Britt Anderson (britt@uwaterloo.ca). Your participation is very much appreciated, and it is our hope that this experience has been informative for you.</p>
        <p>If you are interested in this type of research these two references will give you more details:</p>
        <p>Brunec, I. K., & Momennejad, I. (2019). Predictive representations in hippocampal and prefrontal hierarchies.
BioRxiv, 786434</p>
        <p> Peer, M., Brunec, I. K., Newcombe, N. S., & Epstein, R. A. (2020). Structuring Knowledge with Cognitive Maps and Cognitive Graphs. Trends in Cognitive Sciences.</p>

    

      <blockquote>
        <div>
          <label for "strategies">Did you use any updating strategies during the task?:</label><br>
          <textarea id="strategies" name="strategies" rows="4" cols="50" style=\"display:inline-block\"></textarea>
          <br>
        </div>
        <div>
          <label for "comments">Do you have any comment about your experience of doing the task?:</label><br>
          <textarea id="comments" name="comments" rows="4" cols="50" style=\"display:inline-block\"></textarea>
          <br>
        </div>
       <script>
       var sonaid = <?php echo json_encode($_REQUEST['sonaid']); ?>;
       //alert(sonaid)
       </script>
      <p> I am ready to return to SONA </p>
      <input id="submitbttn" type="submit" value="Submit">
      </blockquote>
    </form>
  </body>
</html>
