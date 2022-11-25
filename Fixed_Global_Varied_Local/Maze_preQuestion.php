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
  
    <form action="./Maze_Instruction.php" method="post">   
    <input type="hidden" id="sonaid" name="sonaid" value=<?php echo $_POST['sonaid']?> >

    <div id="wrapper">
        <h1>Demographics Information</h1>
        <p>Before the task, please answer the following questions if you feel comfortable! </p>
     
            <label for="myAge">1. Please provide your age:</label><br>
            <input type="text" id="myAge" name="myAge" ><br>
            <label for="myYear">2. What is the number of years you have attended university?</label><br>
            <input type="text" id="myYear" name="myYear" ><br>
            <label for="myConcen">3. Please provide your concentration (e.g cognitive neuroscience): (if none please mark "none")</label><br>
            <input type="text" id="myConcen" name="myConcen">
          

          
           
        <p>4. Please provide your gender: </p>
        <label><input type="radio" id="gender" name="gender"  value="M">
         Male
          </label><br />
          <label><input type="radio"id="gender" name="gender" value="F">
        Female
          </label><br />
          <label><input type="radio" id="gender" name="gender"  value="O">
        Other
          </label><br />
          <label><input type="radio" id="gender" name="gender"  value="N">
        Prefer not to answer
          </label><br />
      


          <!-- Question 2 -->

          <p>5. Please provide your dominant hand: </p>
          <label><input type="radio" id="hand" name="hand"  value="R">
            Right
             </label><br />
             <label><input type="radio"id="hand" name="hand" value="L">
            Left
             </label><br />
             <label><input type="radio" id="hand" name="hand"  value="A">
            Ambidextrous
             </label><br />
           
       

<script>
    // var jssecondary = <?php echo json_encode($_REQUEST['consent']); ?>;

    var jsconsent = <?php echo json_encode($_REQUEST['consent2']); ?>;
 

    if (jsconsent == "Decline" ||jsconsent == null) {
              alert("Study aborted for lack of consent");
            
              window.location.href = "http://www.uwaterloo.ca/psychology/";
            }
    // if (jssecondary == "DeclineSecondData"||jssecondary == null) {
    //           alert("Study aborted for lack of consent on secondary use of the data");
            
    //           window.location.href = "http://www.uwaterloo.ca/psychology/";
    //         }
</script>




<script>

var age = document.getElementById("myAge").value;
var year = document.getElementById("myYear").value;
var concen = document.getElementById("myConcen").value;
var gender = document.getElementById("gender").value;
var hand = document.getElementById("hand").value;

</script>

<input type="submit" value="Submit">
</form>
</body>

</html>

