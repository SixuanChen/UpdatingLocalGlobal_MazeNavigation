<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
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

          .button {
  background-color: #d3d3d3; /* Green */
  border: none;
  color: rgb(0, 0, 0);
  padding: 6px 18px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 12px;
  transition-duration: 0.4s;
  cursor: pointer;
}


.button:hover {
  background-color: #959695;
  color: rgb(3, 3, 3);
}

        </style>
</head>
   
<body>
  
    <form action="./Maze_task_cb.php" method="post">   
    <input type="hidden" id="sonaid" name="sonaid" value=<?php echo $_POST['sonaid']?> >


    <input type="hidden" id="myAge" name="myAge" value=<?php echo $_POST['myAge']?> >
    <input type="hidden" id="myYear" name="myYear" value=<?php echo $_POST['myYear']?> >
    <input type="hidden" id="myConcen" name="myConcen" value=<?php echo $_POST['myConcen']?> >
    <input type="hidden" id="gender" name="gender" value=<?php echo $_POST['gender']?> >
    <input type="hidden" id="hand" name="hand" value=<?php echo $_POST['hand']?> >
    <div id="wrapper">
        <h1>Instruction</h1>
        <h2>Welcome to the maze navigation experiment!</h2>
        <h3> Withdrawing from the experiment</h3>
       
        <p>As a reminder, this study has been reviewed and received ethics clearance through a University of Waterloo Research Ethics Committee (ORE #43113). Your participation in this task is voluntary. You can withdraw from the study at any time.</p>
        <p>If you press the 'ESC' key during the task, you will end the task. At this time, you will be given our feedback and thank you letter. You will be automatically assigned credit once you hit the submit button on the letter.</p>
        <p>If you exit from the task using any other method (e.g. closing the tab), we will not be able to conduct these follow-up procedures, and we will not be able to give you credit. If something happens to interrupt your task (e.g. power outage) please email the researchers with the date and time of your participation, so we can manually assign you credit.</p>
        <p> Email: s743chen@uwaterloo.ca</p>

        <h2>Introduction</h2>

        <p>You are an explorer who needs to navigate inside this maze. When the task begins, your position will be marked as a green square.
       The walls of the maze will be hidden from you. To move in the maze you will use the arrow keys. At each choice point the choices available to you (that is
       those not blocked by hidden walls) will be shown to you by small colored circles. To help you track your progress you will be given feedback after most of your choices to let you know if you
       are heading in the right direction or not. Please use as few steps as possible to find the exit.</p>

      <p> Here is one example of what the hidden structure of a maze might look like when the exit is at bottom right.</p>

       
   
        <img id="myImg" src="player.jpg" alt="Maze1" width="200" height="200" class="center">
 <p> While navigating in the maze, the structure will be hidden from you by mask and everything will 
       look gray. The mask here is transparent for demonstration purposes. Note that the two available
 choices are right and down (you are at the location of the green square)</p>
        <img id="myImg2" src="withcue.jpg" alt="Maze2" width="200" height="200" class="center">


        <p>
         When you are navigating in the mazes, colored circles will be your friendly guide. 
         Some colors will often indicate the shortest path to the exit. In this example, it is the blue circle that indicates the shorter direction, 
         and the red circle indicates the longer. 
         However, the colors are usually not completely trustworthy. You will have to take this into account when making your choices. 
         The probability of whether the blue circle is pointing to the shortest path can be considered as the <b>local probability</b>.</p>

         <p>Here is an image of what you will see in the experiment. Your position is indicated by the green
square. The three circles represent the possible directions that you can move. There is no circle
on the left, because there is a wall there (even though you can’t see it due to the mask). A lighter
green square shows your prior position. Here the lighter green square is underneath a red circle. 
This means choosing to go up would retrace your steps. The blue circle is indicating that moving
 down will probably give you the shortest path to the exit.</p>


       

        <img id="myImg3" src="maze_demo.jpg" alt="Maze3" width="200" height="200" class="center">

        <p>Across trials, the exit location might change, too. It will either be at the bottom right or top left.
The possibility of whether the exit is at the bottom right can be considered as the <b>global
probability</b>. Please update what you think of the <b>local probability</b> and <b>global probability</b> are by using
the sliders below. As you navigate the maze you may feel that the probability of the local cue (how reliable the color is) changes.
Or you might think that the likely location of the exit (global probability) has changed. If you do,please update your estimate on the sliders so that we
can track your impressions of the cues as you navigate the maze. You will be told once you have
found the exit. We will ask you to please click the "Next Maze" button to launch the next maze.</p>


<p>We know it may be hard to give a crisp estimate of how reliable the local and global cues are.
Therefore your progress through the task will be adapted to how accurate you are. When your
subjective estimate of the probabilities are withing a standard range of the correct we will
advance to the next stage of the task. If not, the next maze you navigate will probably have the
same local and global probability of the one you just completed. Thus, one way to shorten your
time on task is to track the global and local probabilities and provide accurate update with the
sliders when you perceive a change. <b style="color:Tomato;">The more precise your estimation is, the sonner
you will finish the experiment.</b></p>

        <h3> For this particular version of the study we can tell you that the exit will <b>always be at the bottom right</b>.
         Therefore the global probability will always be 100% correct. Make sure to update the global probability slider.</h3>
        <p>Press the 'Next' button to play one maze as a practice.</p>
        
      
<script>



</script>

<input type="submit" value="Next">
</form>
</body>

</html>

