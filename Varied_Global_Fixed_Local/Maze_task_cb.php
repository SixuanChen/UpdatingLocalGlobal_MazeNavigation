<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>

h1 {
  color: black;
  text-align: center;
  font-size: 20px
}

#demo {
  color: rgb(206, 15, 15);
  text-align: center;
  font-size: 105%;

}
#steps {
  color: rgb(45, 115, 250);
  text-align: center;
  font-size: 105%;

}
#maze {
  color: rgb(44, 56, 110);
  text-align: center;
  font-size: 100%;

}

.slidecontainer {
  width: 100%;
  text-align:center
}

.slider {
  -webkit-appearance: none;
  width: 70%;
  height: 12px;
  background: #d3d3d3;
  outline: none;
  opacity: 0.7;
  -webkit-transition: .2s;
  transition: opacity .2s;

  
}

.slider:hover {
  opacity: 1;
}

.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 12px;
  height: 20px;
  background: #4f584f;
  cursor: pointer;
}

.slider::-moz-range-thumb {
  width: 15px;
  height: 20px;
  background: #252925;
  cursor: pointer;
}
#canvas-container {
   width: 100%;
   text-align:center;
}

myCanvas {
   display: inline;
}

.button {
  background-color: #d3d3d3; /* Green */
  border: none;
  color: rgb(0, 0, 0);
  padding: 8px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
  transition-duration: 0.4s;
  cursor: pointer;


}


.button:hover {
  background-color: #959695;
  color: rgb(3, 3, 3);
}

</style>

<?php
          $tallyfilename = "ppt_tally.json";
          if (!file_exists($tallyfilename)) {
            $startingarray = array('condition' => array('LowToHigh'=>'0', 'HighToLow'=>'0'));
            file_put_contents($tallyfilename, json_encode($startingarray));
          }
?>


</head>
<body>

    <form action="./Maze_feedback.php" method="post">   

    <input type="hidden" id="myAge" name="myAge" value=<?php echo $_POST['myAge']?> >

    <input type="hidden" id="myYear" name="myYear" value=<?php echo $_POST['myYear']?> >

    <input type="hidden" id="myConcen" name="myConcen" value=<?php echo $_POST['myConcen']?> >
    <input type="hidden" id="gender" name="gender" value=<?php echo $_POST['gender']?> >
    <input type="hidden" id="hand" name="hand" value=<?php echo $_POST['hand']?> >
    <input type="hidden" id="sonaid" name="sonaid" value=<?php echo $_POST['sonaid']?> >
  
    <!-- <input type="hidden" id="frompage" name="frompage" value="consent.php"> -->

    <!-- <h1>Maze Navigation</h1> -->

    <p id="maze"></p>

    <div id="canvas-container">
        <canvas id="myCanvas" width="220" height="220" style="border:1px solid #194981;" >
            Your browser does not support the HTML5 canvas tag.</canvas>
    
     </div>
     <p id="demo" ></p>
     </div>
     <p id="steps" ></p>

   



<div class="slidecontainer">
  <!-- <p>Default range slider:</p>
  <input type="range" min="1" max="100" value="50">
   -->
  <!-- <p>Global probability:</p> -->
  
  <p>[Global] The probability of whether the exit is at the bottom left: <span id="GlobalPro"></span></p>
  <input type="range" min="0" max="100" value="50" class="slider" id="GlobalRange">

  <!-- <p>Local probability:</p> -->
  
  <p>[Local] The probability of whether the blue circle is pointing to the shortest path: <span id="LocalPro"></span></p>
  <input type="range" min="0" max="100" value="50" class="slider" id="LocalRange">
</div>
<!-- <div id="menu">
    <p>
       If you are satified with your local and global probabilities estimations, please click the confirm button.
      </p>
   
    <button onclick ="confirmFuc();" type="button" class = "button" id="confirm">Confirm</button>
</div> -->

<div id="consent">
    <p>
      To continue, click the checkbox below
      and hit "Next Maze".
    </p>
    <p>
      <input type="checkbox" id="consent_checkbox" />
      I have confirmed that I reached the exit and want to finish the Maze.
    </p>
    <button onclick ="newMaze();" type="button" class = "button" id="NextMaze">Next Maze</button>
</div>


<script>
var tallyfilename = <?php echo json_encode($tallyfilename); ?>;
var tallyinfo = JSON.parse(JSON.stringify(<?php echo file_get_contents($tallyfilename); ?>))
var jscondition = {}


var local_condition = ""
var global_condition = ""
//jscondition = "HighToLow"


local_condition ="HL"



if (Number(tallyinfo.condition.LowToHigh) > Number(tallyinfo.condition.HighToLow)) {
            jscondition = "HighToLow"
            global_condition ="HL"
       
} 
else if (Number(tallyinfo.condition.LowToHigh) < Number(tallyinfo.condition.HighToLow)) {
            jscondition = "LowToHigh"
            global_condition ="LH"   


} 
else { //in case the saving system isnt working properly, you dont want it to default to either value if manual+automatic are equal (like if theyre both zero)
            jscondition = "LowToHigh"
            global_condition ="LH"
            if (Math.random() < 0.5) {
              jscondition = "HighToLow"
              global_condition ="HL"
            }
}

      
var x_cor=0;
var y_cor=0;
var x_cor_array=[]
var y_cor_array=[]
var x_cor_old=0;
var y_cor_old=0;
var maze_size=11; 
var maze
var player_size = 20;

var mydistance;
var localProbability = 50;
var globalProbability =50;
var MazeNum = 1;
var triple = 0;
var NumDirection =0;
var closeGroundTruth =1;
var closeGlobalGroundTruth =1;

var truefalse = "";
var player_direction="N";
var correct_direction ="";
var blue_direction ="";
var start_time;
var sonaid =9999;
var player_step = 0;
var maze_step=0;
var blue_correctness = 0;
var reaction_time=[];
var isDecisionPoint = false;
var minsteps =0;

var total_rt;
var init_time;
var blue_Total = 0;
var blue_True = 0;

// var current_time;


var sonaid = <?php echo json_encode($_REQUEST['sonaid']); ?>;

//alert("SONA#$#"+sonaid)
//Boolean//
var setUp = false;
var setDown = false;
var setLeft = false;
var setRight = false;

var isUp = false;
var isDown = false;
var isLeft = false;
var isRight = false;

var movetonext =false;

var fixed =false;
var age = document.getElementById("myAge").value;
var year = document.getElementById("myYear").value;
var concen = document.getElementById("myConcen").value;
var gender = document.getElementById("gender").value;
var hand = document.getElementById("hand").value;

function shuffle(array) {
  var currentIndex = array.length,  randomIndex;

  // While there remain elements to shuffle...
  while (currentIndex != 0) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex--;

    // And swap it with the current element.
    [array[currentIndex], array[randomIndex]] = [
      array[randomIndex], array[currentIndex]];
  }

  return array;
}
var results ="Age,Year,Concentration,Gender,Handness,SONAID,Maze,PracticeOrNot,Prior_Local,Prior_Global,Steps,CorX,CorY,Player_Direction,Total_Time(millsecond),Maze_Time(millsecond),Step_Time(millsecond),Local_Probability,Global_Probability,Correct_Direction,Blue,BlueIsCorrect,Blue_Correctness,Distance,Triple,Decision_Point,Local_Condition,Global_Condition,NumDirection,CloseLocalTrial,CloseGlobalTrial,MazeStep,LocalCount,GlobalCount,Section,MinSteps\n";
	
var maze_Matrix = [];


var mazePractice =
"#############\n" +
"#WXYZ[\\]#CBA#\n" +
"#V#######D###\n" +
"#U#STU#GFEFG#\n" +
"#T#R###H#####\n" +
"#S#QRS#IJK#e#\n" +
"#R#P###J###d#\n" +
"#QPONMLK#abc#\n" +
"#R#######`###\n" +
"#STUVW#]^_`a#\n" +
"#T#####\\###b#\n" +
"#UVWXYZ[\\]#c#\n" +
"#############" 

var maze1 =
"#############\n" +
"#ABC#UTS#UVW#\n" +
"###D###R#T###\n" +
"#GFE#O#QRSTU#\n" +
"###F#N#P#T###\n" +
"#Q#G#M#O#UVW#\n" +
"#P#H#L#N#####\n" +
"#O#IJKLMNOPQ#\n" +
"#N#J#########\n" +
"#MLK#UVWXYZ[#\n" +
"#N###T#######\n" +
"#OPQRSTUVWXY#\n" +
"#############" 

var maze21 =
"#############\n" +
"#A#G#QRSTUVW#\n" +
"#B#F#P#######\n" +
"#CDE#O#]#c#a#\n" +
"###F#N#\\#b#`#\n" +
"#Q#G#M#[#a`_#\n" +
"#P#H#L#Z###^#\n" +
"#O#IJK#Y#[\\]#\n" +
"#N#J###X#Z###\n" +
"#MLK#U#WXYZ[#\n" +
"#N###T#V###\\#\n" +
"#OPQRSTUVW#]#\n" +
"#############" 

var maze22 =
"#############\n" +
"#ABC#IJK#YXW#\n" +
"###D#H#####V#\n" +
"#S#EFG#Q#S#U#\n" +
"#R#F###P#R#T#\n" +
"#Q#GHI#OPQRS#\n" +
"#P#H###N#####\n" +
"#O#IJKLM#STU#\n" +
"#N#J###N#R###\n" +
"#MLKLM#OPQ#W#\n" +
"###L#N#####V#\n" +
"#ONM#OPQRSTU#\n" +
"#############" 

var maze23 =
"#############\n" +
"#A#KJIJK#YXW#\n" +
"#B###H#####V#\n" +
"#CDEFG#UTSTU#\n" +
"#D#######R###\n" +
"#E#KLM#O#QRS#\n" +
"#F#J###N#P###\n" +
"#GHIJKLMNOPQ#\n" +
"#H#J#####P###\n" +
"#I#KLMNO#QRS#\n" +
"###L###P#####\n" +
"#ONMNO#QRSTU#\n" +
"#############" 

var maze24 =
"#############\n" +
"#ABC#YXW#abc#\n" +
"###D###V#`###\n" +
"#GFEFG#U#_^]#\n" +
"###F###T###\\#\n" +
"#IHG#M#STU#[#\n" +
"###H#L#R###Z#\n" +
"#W#IJK#QRS#Y#\n" +
"#V###L#P###X#\n" +
"#U#S#MNO#UVW#\n" +
"#T#R#N###T###\n" +
"#SRQPOPQRSTU#\n" +
"#############" 

var maze25 =
"#############\n" +
"#A#G#IJKLMNO#\n" +
"#B#F#H#######\n" +
"#CDEFG#MNO#e#\n" +
"#####H#L###d#\n" +
"#Q#KJIJK#a#c#\n" +
"#P#L#####`#b#\n" +
"#ONMNO#]^_`a#\n" +
"#P#####\\#####\n" +
"#QRS#Y#[\\]^_#\n" +
"#R###X#Z###`#\n" +
"#STUVWXYZ[#a#\n" +
"#############" 

var maze26 =
"#############\n" +
"#A#G#MNOPQ#[#\n" +
"#B#F#L#####Z#\n" +
"#CDE#K#MNO#Y#\n" +
"###F#J#L###X#\n" +
"#]#GHIJKLM#W#\n" +
"#\\###J#####V#\n" +
"#[#]#KLMNO#U#\n" +
"#Z#\\###N###T#\n" +
"#YZ[\\]#OPQRS#\n" +
"#X#####P#####\n" +
"#WVUTSRQRSTU#\n" +
"#############" 

var maze27 =
"#############\n" +
"#A#G#MNO#abc#\n" +
"#B#F#L###`###\n" +
"#CDE#K#M#_#]#\n" +
"###F#J#L#^#\\#\n" +
"#U#GHIJK#]\\[#\n" +
"#T#H#######Z#\n" +
"#S#IJK#UVW#Y#\n" +
"#R#J###T###X#\n" +
"#Q#K#QRSTUVW#\n" +
"#P#L#P###V###\n" +
"#ONMNOPQ#WXY#\n" +
"#############"

var maze28 =
"#############\n" +
"#A#G#I#KLMNO#\n" +
"#B#F#H#J#####\n" +
"#CDEFGHIJK#U#\n" +
"###F#######T#\n" +
"#Q#G#MNO#QRS#\n" +
"#P#H#L###P###\n" +
"#O#IJKLMNOPQ#\n" +
"#N#J#L#######\n" +
"#MLK#MNOPQRS#\n" +
"#N###########\n" +
"#OPQRSTUVWXY#\n" +
"#############" 


var maze29 =
"#############\n" +
"#ABCDEFGHI#[#\n" +
"###D#######Z#\n" +
"#S#E#KLM#WXY#\n" +
"#R#F#J###V###\n" +
"#Q#GHI#S#U#W#\n" +
"#P#H###R#T#V#\n" +
"#O#IJK#QRSTU#\n" +
"#N#J###P#####\n" +
"#MLKLMNOPQ#W#\n" +
"#N#####P###V#\n" +
"#OPQRS#QRSTU#\n" +
"#############" 

var maze30 =
"#############\n" +
"#ABC#IJK#Q#S#\n" +
"###D#H###P#R#\n" +
"#_#EFG#MNOPQ#\n" +
"#^###H#L#P###\n" +
"#]#KJIJK#QRS#\n" +
"#\\#####L#####\n" +
"#[#U#S#M#STU#\n" +
"#Z#T#R#N#R###\n" +
"#Y#SRQPOPQRS#\n" +
"#X###R###R###\n" +
"#WVUTSTU#STU#\n" +
"#############" 

var maze31 =
"#############\n" +
"#ABCDEFGHIJK#\n" +
"#####F#H#####\n" +
"#[\\]#G#IJK#U#\n" +
"#Z###H#####T#\n" +
"#Y#[#IJK#Q#S#\n" +
"#X#Z#J###P#R#\n" +
"#WXY#KLMNOPQ#\n" +
"#V###L#N###R#\n" +
"#U#ONM#O#UTS#\n" +
"#T#P#####V###\n" +
"#SRQRSTU#WXY#\n" +
"#############" 

var maze32 =
"#############\n" +
"#ABCDEFG#MNO#\n" +
"#####F###L###\n" +
"#WXY#GHIJKLM#\n" +
"#V###H#######\n" +
"#U#O#IJKLMNO#\n" +
"#T#N#J#######\n" +
"#S#MLKLMNOPQ#\n" +
"#R###L#######\n" +
"#QPONM#WXY#[#\n" +
"###P###V###Z#\n" +
"#SRQRSTUVWXY#\n" +
"#############" 

var maze33 =
"#############\n" +
"#ABCDEFG#Q#S#\n" +
"#######H#P#R#\n" +
"#[ZY#O#I#OPQ#\n" +
"###X#N#J#N###\n" +
"#YXW#MLKLMNO#\n" +
"###V#N#######\n" +
"#[#U#O#UVWXY#\n" +
"#Z#T#P#T#####\n" +
"#Y#SRQRS#]#_#\n" +
"#X#T#####\\#^#\n" +
"#WVUVWXYZ[\\]#\n" +
"#############" 

var maze34 =
"#############\n" +
"#A#KLMNOPQRS#\n" +
"#B#J###P#R###\n" +
"#C#I#K#Q#STU#\n" +
"#D#H#J#######\n" +
"#EFGHIJK#Q#S#\n" +
"#####J###P#R#\n" +
"#SRQ#KLMNOPQ#\n" +
"###P#L#######\n" +
"#QPONMNOPQRS#\n" +
"#R#####P###T#\n" +
"#STUVW#QRS#U#\n" +
"#############" 

var maze35 =
"#############\n" +
"#ABC#Y#[#YZ[#\n" +
"###D#X#Z#X###\n" +
"#GFE#WXY#WXY#\n" +
"###F#V###V###\n" +
"#M#G#UTSTU#[#\n" +
"#L#H###R###Z#\n" +
"#KJI#O#QRS#Y#\n" +
"###J#N#P###X#\n" +
"#U#KLMNO#U#W#\n" +
"#T###N###T#V#\n" +
"#SRQPOPQRSTU#\n" +
"#############" 
var maze36 =
"#############\n" +
"#ABC#MNO#QRS#\n" +
"###D#L###P###\n" +
"#K#E#KLMNO#U#\n" +
"#J#F#J#####T#\n" +
"#IHGHIJK#Q#S#\n" +
"#####J###P#R#\n" +
"#ONMLKLMNOPQ#\n" +
"#P#####N#####\n" +
"#Q#WXY#OPQRS#\n" +
"#R#V#Z#####T#\n" +
"#STU#[\\]^_#U#\n" +
"#############" 

var maze37 =
"#############\n" +
"#ADE#M#OPQRS#\n" +
"#B###L#N###T#\n" +
"#CDE#KLMNO#U#\n" +
"###F#J#####V#\n" +
"#U#GHI#O#Q#W#\n" +
"#T###J#N#P###\n" +
"#S#MLKLMNOPQ#\n" +
"#R#N###N#####\n" +
"#QPOPQ#OPQRS#\n" +
"###P###P#R#T#\n" +
"#SRQRS#Q#S#U#\n" +
"#############" 

var maze38 =
"#############\n" +
"#ADE#Q#_^]#_#\n" +
"#B###P###\\#^#\n" +
"#C#MNOPQ#[\\]#\n" +
"#D#L#####Z###\n" +
"#E#K#MNO#YZ[#\n" +
"#F#J#L###X###\n" +
"#GHIJK#Q#W#Y#\n" +
"#####L#P#V#X#\n" +
"#U#ONMNO#UVW#\n" +
"#T###N###T###\n" +
"#SRQPOPQRSTU#\n" +
"#############" 
var maze39 =
"#############\n" +
"#ABCDEFGHI#S#\n" +
"#######H###R#\n" +
"#[#UTS#I#OPQ#\n" +
"#Z###R#J#N###\n" +
"#Y#W#Q#KLMNO#\n" +
"#X#V#P#L#####\n" +
"#W#U#ONMNOPQ#\n" +
"#V#T#P#N#####\n" +
"#UTSRQ#OPQRS#\n" +
"#V#####P###T#\n" +
"#WXY#SRQRS#U#\n" +
"#############" 

var maze40 =
"#############\n" +
"#ABCDEFG#]#_#\n" +
"#####F###\\#^#\n" +
"#KJIHG#]#[#]#\n" +
"#####H#\\#Z#\\#\n" +
"#QPO#I#[ZY#[#\n" +
"###N#J###X#Z#\n" +
"#W#MLK#UVWXY#\n" +
"#V###L#T#####\n" +
"#U#S#M#STUVW#\n" +
"#T#R#N#R###X#\n" +
"#SRQPOPQRS#Y#\n" +
"#############" 

var maze41 =
"#############\n" +
"#A#G#QPO#UVW#\n" +
"#B#F###N#T###\n" +
"#CDEFG#M#STU#\n" +
"#####H#L#R###\n" +
"#U#W#IJK#Q#W#\n" +
"#T#V#J###P#V#\n" +
"#STU#KLMNO#U#\n" +
"#R###L#N###T#\n" +
"#QPONM#OPQRS#\n" +
"#R###########\n" +
"#STUVWXYZ[\\]#\n" +
"#############" 
var maze42 =
"#############\n" +
"#A#G#M#O#QRS#\n" +
"#B#F#L#N#P###\n" +
"#CDE#KLMNOPQ#\n" +
"###F#J#######\n" +
"#M#GHIJK#QRS#\n" +
"#L#H#J###P###\n" +
"#KJI#KLMNOPQ#\n" +
"#####L#N#####\n" +
"#U#ONM#OPQRS#\n" +
"#T###N#####T#\n" +
"#SRQPOPQRS#U#\n" +
"#############" 
var maze43 =
"#############\n" +
"#A#KJI#KLMNO#\n" +
"#B###H#J#####\n" +
"#CDEFGHI#O#Q#\n" +
"#####H###N#P#\n" +
"#U#S#IJKLMNO#\n" +
"#T#R#J#L#N###\n" +
"#S#Q#K#M#OPQ#\n" +
"#R#P#L###P#R#\n" +
"#QPONM#[#Q#S#\n" +
"#R#####Z###T#\n" +
"#STUVWXYZ[#U#\n" +
"#############" 

var maze44 =
"#############\n" +
"#ABCDE#K#U#W#\n" +
"#####F#J#T#V#\n" +
"#_#IHGHI#S#U#\n" +
"#^#####J#R#T#\n" +
"#]#_`a#K#QRS#\n" +
"#\\#^###L#P###\n" +
"#[\\]^_#MNOPQ#\n" +
"#Z#####N###R#\n" +
"#YZ[\\]#OPQ#S#\n" +
"#X#####P###T#\n" +
"#WVUTSRQRS#U#\n" +
"#############" 

var maze45 =
"#############\n" +
"#A#G#Q#OPQ#W#\n" +
"#B#F#P#N###V#\n" +
"#CDE#ONM#S#U#\n" +
"###F###L#R#T#\n" +
"#Q#GHIJK#Q#S#\n" +
"#P###J###P#R#\n" +
"#ONMLKLMNOPQ#\n" +
"###N#########\n" +
"#QPOPQRSTUVW#\n" +
"###P###T#####\n" +
"#SRQRS#UVWXY#\n" +
"#############" 

var maze46 =
"#############\n" +
"#ABCDEFG#a`_#\n" +
"#####F#####^#\n" +
"#[#M#G#Q#S#]#\n" +
"#Z#L#H#P#R#\\#\n" +
"#Y#KJI#OPQ#[#\n" +
"#X###J#N###Z#\n" +
"#W#MLKLM#WXY#\n" +
"#V###L###V###\n" +
"#UVW#M#S#U#W#\n" +
"#T###N#R#T#V#\n" +
"#SRQPOPQRSTU#\n" +
"#############" 
var maze47 =
"#############\n" +
"#ADEFGHIJK#W#\n" +
"#B###H#####V#\n" +
"#CDE#I#QRSTU#\n" +
"###F###P#T###\n" +
"#IHGHI#O#U#[#\n" +
"###H###N###Z#\n" +
"#W#IJKLM#[#Y#\n" +
"#V#J#####Z#X#\n" +
"#U#KLM#S#YXW#\n" +
"#T###N#R###V#\n" +
"#SRQPOPQRSTU#\n" +
"#############" 

var maze48 =
"#############\n" +
"#ABCDEFG#MNO#\n" +
"#######H#L###\n" +
"#SRQPO#IJK#Q#\n" +
"#####N#J###P#\n" +
"#QPONMLKLMNO#\n" +
"#######L#####\n" +
"#[\\]#ONMNO#U#\n" +
"#Z#####N###T#\n" +
"#Y#SRQPOPQRS#\n" +
"#X#T###P#####\n" +
"#WVUVW#QRSTU#\n" +
"#############" 


var maze49 =
"#############\n" +
"#A#STUVWXY#[#\n" +
"#B#R#######Z#\n" +
"#C#QPO#Y#WXY#\n" +
"#D###N#X#V###\n" +
"#EFG#M#WVUVW#\n" +
"###H#L###T###\n" +
"#S#IJKLM#STU#\n" +
"#R###L###R###\n" +
"#QPONMNOPQRS#\n" +
"###P#########\n" +
"#SRQRSTUVWXY#\n" +
"#############" 

var maze50 =
"#############\n" +
"#A#G#QRS#UVW#\n" +
"#B#F#P###T###\n" +
"#CDE#O#Q#S#U#\n" +
"###F#N#P#R#T#\n" +
"#IHG#MNO#QRS#\n" +
"###H#L###P###\n" +
"#KJIJKLMNOPQ#\n" +
"#####L#####R#\n" +
"#UVW#M#STU#S#\n" +
"#T###N#R#####\n" +
"#SRQPOPQRSTU#\n" +
"#############" 

var maze51 =
"#############\n" +
"#A#ONMNO#UVW#\n" +
"#B###L###T###\n" +
"#C#I#K#Q#S#e#\n" +
"#D#H#J#P#R#d#\n" +
"#EFGHI#O#Q#c#\n" +
"#####J#N#P#b#\n" +
"#S#MLKLMNO#a#\n" +
"#R#N#######`#\n" +
"#QPO#Y#[#]#_#\n" +
"#R###X#Z#\\#^#\n" +
"#STUVWXYZ[\\]#\n" +
"#############" 

var maze52 =
"#############\n" +
"#ABCDEFGHI#W#\n" +
"#####F#####V#\n" +
"#[#IHGHI#S#U#\n" +
"#Z###H###R#T#\n" +
"#Y#[#I#O#Q#S#\n" +
"#X#Z#J#N#P#R#\n" +
"#WXY#KLMNOPQ#\n" +
"#V###L#######\n" +
"#U#S#MNO#UVW#\n" +
"#T#R#N#P#T###\n" +
"#SRQPO#QRSTU#\n" +
"#############" 

var maze61 =
"#############\n" +
"#Y#[#QRSTUVW#\n" +
"#X#Z#P#T#####\n" +
"#W#Y#O#UVWXY#\n" +
"#V#X#N#######\n" +
"#UVW#MNOPQRS#\n" +
"#T###L#####T#\n" +
"#S#MLKJIHG#U#\n" +
"#R#N#####F###\n" +
"#QPOPQRS#EDC#\n" +
"#R#######F#B#\n" +
"#STUVWXY#G#A#\n" +
"#############" 

var maze62 =
"#############\n" +
"#]#[#]#_`a#W#\n" +
"#\\#Z#\\#^###V#\n" +
"#[#Y#[#]#S#U#\n" +
"#Z#X#Z#\\#R#T#\n" +
"#YXWXYZ[#Q#S#\n" +
"###V#####P#R#\n" +
"#STU#KLMNOPQ#\n" +
"#R###J#######\n" +
"#QPO#IHGFEFG#\n" +
"###N#J#H#D###\n" +
"#ONMLK#I#CBA#\n" +
"#############" 

var maze63 =
"#############\n" +
"#UVW#U#W#Y#[#\n" +
"#T###T#V#X#Z#\n" +
"#STU#STU#W#Y#\n" +
"#R###R###V#X#\n" +
"#Q#O#Q#S#UVW#\n" +
"#P#N#P#R#T###\n" +
"#O#MNOPQRS#E#\n" +
"#N#L#######D#\n" +
"#MLKJIHGFEDC#\n" +
"#N#L#J###F#B#\n" +
"#O#M#KLM#G#A#\n" +
"#############"



var maze64 =
"#############\n" +
"#[ZY#[#]^_`a#\n" +
"###X#Z#\\#`###\n" +
"#U#WXYZ[#abc#\n" +
"#T#V#######d#\n" +
"#STU#OPQ#S#e#\n" +
"#R###N###R#f#\n" +
"#Q#O#MNOPQ#g#\n" +
"#P#N#L#######\n" +
"#ONMLKJIHG#C#\n" +
"#P#N#####F#B#\n" +
"#Q#OPQRS#EDA#\n" +
"#############"

var maze65 =
"#############\n" +
"#Y#[#]^_#abc#\n" +
"#X#Z#\\###`###\n" +
"#WXYZ[\\]^_#Y#\n" +
"#V#########X#\n" +
"#U#OPQRSTUVW#\n" +
"#T#N#####V###\n" +
"#S#MLKJI#W#E#\n" +
"#R#N###H###D#\n" +
"#QPO#Y#GFEDC#\n" +
"#R###X#####B#\n" +
"#STUVWXY#EDA#\n" +
"#############" 


var maze66 =
"#############\n" +
"#YXW#Y#O#QRS#\n" +
"###V#X#N#P###\n" +
"#S#U#W#MNOPQ#\n" +
"#R#T#V#L#####\n" +
"#QRSTU#K#IHG#\n" +
"#P#####J###F#\n" +
"#ONMLKJIHGFE#\n" +
"#P#########D#\n" +
"#QRSTUVWXY#C#\n" +
"#R#########B#\n" +
"#STUVWXYZ[#A#\n" +
"#############"

var maze67 =
"#############\n" +
"#Y#[\\]#_`abc#\n" +
"#X#Z###^#####\n" +
"#WXYZ[\\]#OPQ#\n" +
"#V#######N###\n" +
"#U#WXY#KLMNO#\n" +
"#T#V###J###P#\n" +
"#STU#KJIHG#Q#\n" +
"#R###L#J#F###\n" +
"#QPONM#K#EFG#\n" +
"#R#####L#D###\n" +
"#STUVW#M#CBA#\n" +
"#############" 

var maze68 =
"#############\n" +
"#U#WXYZ[\\]^_#\n" +
"#T#V#########\n" +
"#S#U#OPQ#KJI#\n" +
"#R#T#N#####H#\n" +
"#QRS#M#KLM#G#\n" +
"#P###L#J###F#\n" +
"#ONMLKJIHGFE#\n" +
"###N#######D#\n" +
"#U#O#UVWXY#C#\n" +
"#T#P#T###Z#B#\n" +
"#SRQRSTU#[#A#\n" +
"#############" 

var maze69 =
"#############\n" +
"#W#U#S#U#[\\]#\n" +
"#V#T#R#T#Z###\n" +
"#UTS#Q#S#YXW#\n" +
"###R#P#R###V#\n" +
"#S#Q#O#Q#WVU#\n" +
"#R#P#N#P###T#\n" +
"#QPO#MNOPQRS#\n" +
"###N#L#######\n" +
"#ONMLKJIJK#C#\n" +
"#P#####H###B#\n" +
"#QRSTU#GFEDA#\n" +
"#############" 

var maze70 =
"#############\n" +
"#UVWXY#[ZYZ[#\n" +
"#T#######X###\n" +
"#S#QRSTUVWXY#\n" +
"#R#P#########\n" +
"#Q#OPQ#KLMNO#\n" +
"#P#N###J###P#\n" +
"#ONMLKJIJK#Q#\n" +
"#P###L#H###R#\n" +
"#QRS#M#GFE#S#\n" +
"#R#T#####D###\n" +
"#S#UVWXY#CBA#\n" +
"#############" 


var maze71 =
"#############\n" +
"#U#S#UVW#YZ[#\n" +
"#T#R#T###X###\n" +
"#SRQRSTUVWXY#\n" +
"###P#########\n" +
"#QPONMLKLMNO#\n" +
"#R###N#J#N###\n" +
"#STU#O#I#OPQ#\n" +
"#T#####H#####\n" +
"#UVWXY#GFEFG#\n" +
"#V###Z#H#D###\n" +
"#WXY#[#I#CBA#\n" +
"#############" 

var maze72 =
"#############\n" +
"#]#[#a`_#a#c#\n" +
"#\\#Z###^#`#b#\n" +
"#[ZYZ[\\]^_`a#\n" +
"###X#########\n" +
"#Y#W#QRS#IHG#\n" +
"#X#V#P###J#F#\n" +
"#W#U#ONMLK#E#\n" +
"#V#T#P#####D#\n" +
"#UTSRQRSTU#C#\n" +
"#V#########B#\n" +
"#WXYZ[\\]^_#A#\n"+
"#############" 


var maze73 =
"#############\n" +
"#YXWXYZ[\\]^_#\n" +
"###V#Z#\\#^###\n" +
"#STU#[#]#_#I#\n" +
"#R#########H#\n" +
"#Q#OPQ#K#IHG#\n" +
"#P#N###J###F#\n" +
"#ONMLKJIHGFE#\n" +
"#P###L#####D#\n" +
"#Q#W#M#[\\]#C#\n" +
"#R#V###Z###B#\n" +
"#STUVWXYZ[#A#\n" +
"#############" 






var pracmazeDirec=["BR",mazePractice]

var mazeDirec21= ["BL",maze21]
var mazeDirec22= ["BL",maze22]
var mazeDirec23= ["BL",maze23]
var mazeDirec24= ["BL",maze24]
var mazeDirec25= ["BL",maze25]
var mazeDirec26= ["BL",maze26]
var mazeDirec27= ["BL",maze27]
var mazeDirec28= ["BL",maze28]
var mazeDirec29= ["BL",maze29]
var mazeDirec30= ["BL",maze30]
var mazeDirec31= ["BL",maze31]
var mazeDirec32= ["BL",maze32]
var mazeDirec33= ["BL",maze33]
var mazeDirec34= ["BL",maze34]
var mazeDirec35= ["BL",maze35]
var mazeDirec36= ["BL",maze36]
var mazeDirec37= ["BL",maze37]
var mazeDirec38= ["BL",maze38]
var mazeDirec39= ["BL",maze39]
var mazeDirec40= ["BL",maze40]
var mazeDirec41= ["BL",maze41]
var mazeDirec42= ["BL",maze42]
var mazeDirec43= ["BL",maze43]
var mazeDirec44= ["BL",maze44]
var mazeDirec45= ["BL",maze45]
var mazeDirec46= ["BL",maze46]
var mazeDirec47= ["BL",maze47]
var mazeDirec48= ["BL",maze48]
var mazeDirec49= ["BL",maze49]
var mazeDirec50= ["BL",maze50]
var mazeDirec51= ["BL",maze51]
var mazeDirec52= ["BL",maze52]

var mazeDirec61= ["TR",maze61]
var mazeDirec62= ["TR",maze62]
var mazeDirec63= ["TR",maze63]
var mazeDirec64= ["TR",maze64]
var mazeDirec65= ["TR",maze65]
var mazeDirec66= ["TR",maze66]
var mazeDirec67= ["TR",maze67]
var mazeDirec68= ["TR",maze68]
var mazeDirec69= ["TR",maze69]
var mazeDirec70= ["TR",maze70]
var mazeDirec71= ["TR",maze71]
var mazeDirec72= ["TR",maze72]
var mazeDirec73= ["TR",maze73]





var mazewhole = []

var maze60group = [mazeDirec61,mazeDirec62,mazeDirec63,mazeDirec64,mazeDirec71,mazeDirec72,mazeDirec21,mazeDirec22,mazeDirec23,mazeDirec24,mazeDirec25,mazeDirec26,mazeDirec41,mazeDirec42,mazeDirec43]

var maze60grouplength = maze60group.length
maze60group = shuffle(maze60group)


var maze73group = [mazeDirec65,mazeDirec66,mazeDirec67,mazeDirec68,mazeDirec27,mazeDirec28,mazeDirec29,mazeDirec30,mazeDirec31,mazeDirec32,mazeDirec44,mazeDirec45,mazeDirec46,mazeDirec47,mazeDirec48]
var maze73grouplength = maze73group.length
maze73group = shuffle(maze73group)

var maze80group = [mazeDirec69,mazeDirec70,mazeDirec73,mazeDirec33,mazeDirec34,mazeDirec35,mazeDirec36,mazeDirec37,mazeDirec38,mazeDirec39,mazeDirec40,mazeDirec49,mazeDirec50,mazeDirec51,mazeDirec52]
var maze80grouplength = maze80group.length
maze80group = shuffle(maze80group)



var mazelast = ["BL",maze1]



function pushMazeDirec(mazedirec_array){
    for(var i= 0; i< mazedirec_array.length; i++){
    var curr_mazedire =mazedirec_array[i]  
    mazewhole.push(curr_mazedire)

    }

}
pushMazeDirec(maze60group)
pushMazeDirec(maze73group )
pushMazeDirec(maze80group)

mazewhole.push(mazelast)


var globalpro_list_HL =[]
for (let i  =0 ; i<maze80grouplength ; i++){
    globalpro_list_HL.push(80)
}
for (let i  =0 ; i<maze73grouplength ; i++){
    globalpro_list_HL.push(73)
}
for (let i  =0 ; i<maze60grouplength ; i++){
    globalpro_list_HL.push(60)
}

globalpro_list_HL.push(60)
console.log("globalpro_list_HL: "+globalpro_list_HL)

var globalpro_list_LH =[]
for (let i  =0 ; i<maze60grouplength ; i++){
    globalpro_list_LH.push(60)
}
for (let i  =0 ; i<maze73grouplength ; i++){
    globalpro_list_LH.push(73)
}
for (let i  =0 ; i<maze80grouplength ; i++){
    globalpro_list_LH.push(80)
}

globalpro_list_HL.push(80)
console.log("globalpro_list_LH: "+globalpro_list_LH)

var sectionNum =1;

var distance_char =""


//var maze_array = [mazePractice,maze1,maze2,maze3,maze4,maze5,maze6,maze7,maze8,maze9,maze10,maze11,maze12,maze13,maze14,maze15,maze16,maze17,maze18,maze19,maze20,maze21,maze22,maze23,maze24,maze25,maze26,maze27,maze28,maze29,maze30,maze31,maze32,maze33,maze34,maze35,maze36,maze37,maze38,maze39,maze40,maze41,maze42,maze43,maze44,maze45,maze46,maze47,maze48,maze49,maze50]
var maze_array = [pracmazeDirec[1]]

for(var i= 0; i< mazewhole.length; i++){
    var curr_mazedire =mazewhole[i]
    var curr_maze = curr_mazedire[1]
    maze_array.push(curr_maze)

}

var exit_direction_list =[pracmazeDirec[0]]

for(var i= 0; i< mazewhole.length; i++){
    var curr_mazedire =mazewhole[i]
    var curr_exit = curr_mazedire[0]
    exit_direction_list.push(curr_exit)

}

var localCount =0
var globalCount =0

var localpro_list =[]
if (global_condition == "HL"){
    for (let i  =0 ; i<maze80grouplength ; i++){
        localpro_list.push(80)
    }
    for (let i  =0 ; i<maze73grouplength ; i++){
        localpro_list.push(80)
    }
    for (let i  =0 ; i<maze60grouplength ; i++){
        localpro_list.push(80)
    }
    localpro_list.push(80)
console.log("local_list"+localpro_list)
}
if (global_condition == "LH"){
    for (let i  =0 ; i<maze60grouplength ; i++){
        localpro_list.push(80)
    }
    for (let i  =0 ; i<maze73grouplength ; i++){
        localpro_list.push(80)
    }
    for (let i  =0 ; i<maze80grouplength ; i++){
        localpro_list.push(80)
    }
    localpro_list.push(80)
console.log("local_list"+localpro_list)
}




var globalpro_list =[]
if (global_condition == "HL"){
    globalpro_list =globalpro_list_HL
  
}
else if(global_condition == "LH") {
    globalpro_list =globalpro_list_LH

}

else{
    //alert(global_condition +"global condition")
    alert("No local condition assigned, please contact the researcher at s743chen@uwaterloo.ca")
}


var localmax = localpro_list.length
//console.log("localmax"+localmax)
var localpro = localpro_list[localCount];
var globalpro = globalpro_list[globalCount];


//prevent the key pressing change the sliders		
window.addEventListener("keydown", function(e) {
    if(["Space","ArrowUp","ArrowDown","ArrowLeft","ArrowRight"].indexOf(e.code) > -1) {
        e.preventDefault();
    }
}, false);

function MazeGenerate(){

    maze_Matrix = [];
    startMaze();
    var ctx = c.getContext("2d");
    ctx.fillStyle = "#EEEEEE";
    ctx.fillRect(0, 0, player_size*15, player_size*15);
    ctx.fillStyle = "#448544";
    ctx.fillRect(player_size*x_cor, player_size*y_cor, player_size*1, player_size*1);
    Guide();
    checkArror();
    drawArror(); 
    confirmDirection();
    //alert("New Maze has been generated!")
    //console.log("MazeNum!!@@"+MazeNum)
    document.getElementById("consent_checkbox").checked = false;
    document.getElementById("demo").innerHTML = '';
}

function mazeStop(){
    if(MazeNum >=(maze80grouplength+maze73grouplength+maze60grouplength+2)){       

      
                saveData(sonaid+'_exportResults',results); 
    }
}
function switchGlobalPro(){
   
    if (global_condition == "HL"){
        if(MazeNum ==(maze80grouplength+2)){
            alert("Congratulation! You have finished the first section! Pay attention, the local and global probabilities will probably change in the next section.")
         
          
        }
        else if(MazeNum ==(maze80grouplength+maze73grouplength+2)){
            alert("Congratulation! You have finished the second section! Pay attention, the local and global probabilities will probably change in the next section.")
           
            
        }
        else if(MazeNum ==(maze80grouplength+maze73grouplength+maze60grouplength+2)){
            alert("Congratulation! You have finished the whole experiment! Please hit the Submit Button!")
            document.getElementById("steps").innerHTML ="You have finished the whole experiment! Hit submit!"
           
        }
        
    }

    else if (global_condition == "LH"){
        if(MazeNum ==(maze60grouplength+2)){
            alert("Congratulation! You have finished the first section! Pay attention, the local and global probabilities will probably change in the next section.")
         
        }
        else if(MazeNum ==(maze60grouplength+maze73grouplength+2)){
            alert("Congratulation! You have finished the second section! Pay attention, the local and global probabilities will probably change in the next section.")
           
        }
        else if(MazeNum ==(maze60grouplength+maze73grouplength+maze80grouplength+2)){
            alert("Congratulation! You have finished the whole experiment! Please hit the Submit Button!")
            document.getElementById("steps").innerHTML ="You have finished the whole experiment! Hit submit!" 
        }
        

    }
 

}

function checkProbability(){
    if(MazeNum>1){   
        if(mydistance==0){
            //alert("current globalProbability"+ globalProbability+"current globalpro"+globalpro)

            if (globalProbability >=(globalpro-5) &&globalProbability <=(globalpro+5)){

                    alert("Good job! You estimation of the global probability is close to the ground truth! Within the +/-5% range.")
                   
                    globalCount ++;
                   
                    globalpro = globalpro_list[globalCount]
                    //console.log("update localpro"+localpro)
                 
                    closeGlobalGroundTruth =1;
              

            }
            else if (globalProbability <(globalpro-5) || globalProbability >(globalpro+5)){
                alert("Oh No! You estimation of the global probability is too far from the ground truth. Not within the +/-5% range.")
            
                globalCount ++;
                
                globalpro = globalpro_list[globalCount]
            
                closeGlobalGroundTruth= 0;   
        
            }

            if (localProbability >=(localpro-5) &&localProbability <=(localpro+5)){

                     alert("Good job! You estimation of the local probability is close to the ground truth! Within the +/-5% range.")
                        localCount ++;
                    
                        localpro = localpro_list[localCount]
                        
                        //console.log("update localpro"+localpro)
                        MazeNum++;
                        closeGroundTruth =1;
                    
                        MazeGenerate()  
                    

                }
            else if(localProbability <(localpro-5) ||localProbability >(localpro+5)){
                alert("Oh No! You estimation of the local probability is too far from the ground truth. Not within the +/-5% range.")
                localCount ++;

                localpro = localpro_list[localCount]

                MazeNum++;   
                closeGroundTruth= 0;   
                MazeGenerate() 

            }

        }
        else if(mydistance!=0){

            alert("You have not finished the current maze navigation.")
        }
        
    }

    
}
function updateSection(){

if (global_condition == "LH"){
    if(MazeNum ==(maze60grouplength+1)){
        sectionNum=2;
    }
    else if(MazeNum ==(maze60grouplength+maze73grouplength+1)){
        sectionNum=3;
    }
    else if(MazeNum ==(maze60grouplength+maze73grouplength+maze80grouplength+1)){
        sectionNum=4;
    }
   
}
if (global_condition == "HL"){
    if(MazeNum ==(maze80grouplength+1)){
        sectionNum=2;
    }
    else if(MazeNum ==(maze80grouplength+maze73grouplength+1)){
        sectionNum=3;
    }
    else if(MazeNum ==(maze80grouplength+maze73grouplength+maze60grouplength+1)){
        sectionNum=4;
    }
}


}

function newMaze() {

        
    //console.log("my distance!!!!!!!!!!!!!!!!"+mydistance)
    
        if( document.getElementById("consent_checkbox").checked == false){
            alert("Please check the checkbox!")
        }
      

        if( document.getElementById("consent_checkbox").checked == true){
            var feeback = "The minimum steps required for finishing current maze is "+minsteps+". "+"You have used "+ maze_step+" steps."
            alert(feeback)
            updateSection()

            checkProbability();
           
            



            if(MazeNum == 1 && mydistance==0&& globalProbability ==0){

            
            alert("HAHA, you did it! Notice that in the real experiment, moving global probability slider to 0% means your estimation of the exit location is never at the bottom left.")

            alert("You have successfully finished the practice trial! Remember in real experiment the location of exit will be be either at bottom left or top right!")

            MazeNum++;
            


            fixed= false;
      
            MazeGenerate()

            }

            else if(MazeNum == 1 && mydistance==0 &&globalProbability !=0){
            alert("You did not move the global silder to the 0%, please do the maze practice again!")
            MazeNum=1;
            MazeGenerate();


            }
        
        
            
            mazeStop()
            switchGlobalPro()
            document.getElementById("demo").innerHTML =""
    
        
        }
   
}

  


function downloadResults(){
    var data, filename, link;
        var csv =results;
        if (csv == null) return;

        //filename = args.filename || 'export.csv';

        if (!csv.match(/^data:text\/csv/i)) {
        csv = 'data:text/csv;charset=utf-8,' + csv;
        }
        data = encodeURI(csv);

        link = document.createElement('a');
        link.setAttribute('href', data);
        link.setAttribute('download', 'exportMaze.csv');
        link.click();
}

function buildMaze(maze) {    
        
    var d_start = new Date();
    start_time = d_start.getTime();
        var lines = maze.split('\n');

    console.log("mazeLines"+lines.length) 
    maze_size =lines.length-2;
    console.log("mazeSize"+maze_size)  
       
    for(var line = 0; line < lines.length; line++){
       
       var curr_line = lines[line];
       var curr_array =[];
       for(i = 0; i < curr_line.length; i++){
        var letter = curr_line.substr(i,1);
        curr_array.push(letter);
       }
      
       
       maze_Matrix.push(curr_array)
    }
    var d = new Date();
    var n = d.getTime();


  
    reaction_time.push(n);
  


}
  

function startMaze(){
   
    x_cor=0;
    y_cor=0;
    x_cor_array=[]
    y_cor_array=[]
    mydistance = undefined;
    // maze_size=0;
    maze_step =0;
  
    triple = 0;
    truefalse = "";
    player_direction="N";
    correct_direction ="";
    blue_direction ="";
    blue_Total = 0;
    blue_True=0;
    if (MazeNum ==1){
        localpro = 75
        globalpro = 0
       
        //console.log("practice")
        document.getElementById("maze").innerHTML = 'Maze '+'Practice';
    }
    if (MazeNum >1){
        var maze_num = MazeNum-1
        localpro = localpro_list[localCount];
        globalpro =globalpro_list[globalCount]
       // console.log("practice+1!!")
        document.getElementById("maze").innerHTML = 'Section '+ sectionNum+ ' Maze '+ maze_num ;
    }
  
  

    //bottom right
	//var distance_char = maze_Matrix[maze_size-maze_size+1][maze_size];
    //top right
    //var distance_char = maze_Matrix[maze_size][maze_size];
     //bottom left
    //var distance_char = maze_Matrix[1][1];

   
    buildMaze(maze_array[MazeNum-1])

    if ( exit_direction_list[MazeNum-1]=="BL"){
        distance_char = maze_Matrix[1][1];
    }

    else if ( exit_direction_list[MazeNum-1]=="TR"){
        distance_char = maze_Matrix[maze_size][maze_size];
    }
    else if ( exit_direction_list[MazeNum-1]=="BR"){
        distance_char = maze_Matrix[maze_size-maze_size+1][maze_size];
    }

    minsteps = checkMinStep()
            
    // var curre = maze_Matrix[8][2]
    //alert('checkMin step '+ minsteps);
}

function checkExit() {


    if ( exit_direction_list[MazeNum-1]=="BL"){
        //bottom left
        if (x_cor ==(0) && y_cor == (-(maze_size-1)) ) {
            
            document.getElementById("demo").innerHTML = 'You found the exit!';
        }
    }

    else if ( exit_direction_list[MazeNum-1]=="TR"){
         //top right
        if (x_cor ==(maze_size-1) && y_cor == (0) ) {	
            document.getElementById("demo").innerHTML = 'You found the exit!';
        }
    }
    else if ( exit_direction_list[MazeNum-1]=="BR"){
        //bottom right
        if (x_cor ==(maze_size-1) && y_cor == (-(maze_size-1)) ) {
		
		document.getElementById("demo").innerHTML = 'You found the exit!';

	    }
    }
    

    

   
    console.log("fasle no exit")
    return false
}


function checkDistance() {
    


    var cur_integer_char  = maze_Matrix[maze_size+y_cor][x_cor+1];
    var cur_integer= cur_integer_char.charCodeAt(0);
    console.log("cur_integer+char"+cur_integer_char);	
  
    var distance = distance_char.charCodeAt(0);
    console.log("distance_char"+distance_char);
	var current_step =cur_integer-distance;
    console.log("current_step"+ current_step);
    var steps = current_step.toString();
    mydistance = steps;

    var stepcontent = "You have used " +maze_step+ " steps.";
    document.getElementById("steps").innerHTML = stepcontent;

}

function checkMinStep(){
    var int_integer_char  = maze_Matrix[maze_size+0][0+1];
    var int_integer= int_integer_char.charCodeAt(0);
    console.log("int_integer+char"+int_integer_char);
    console.log(int_integer);	
	//var distance_char = maze_Matrix[maze_size-maze_size+1][maze_size];
    //bottom left
    //var distance_char = maze_Matrix[1][1];
    var distance = distance_char.charCodeAt(0);
    console.log("distance_char"+distance_char);
    console.log(distance);

	var min_step =int_integer-distance;
    console.log("min_step_away"+ min_step);
    var steps = min_step.toString();
    var min_distance = steps;
    return min_distance

}





function resetArror() {   
    
   setUp = false;	
   setDown = false;
   setLeft = false;
   setRight = false;

   isUp = false;
    isDown = false;
    isLeft = false;
    isRight = false;

}
function checkArror() {   
    resetArror();
    var cur_up = maze_Matrix[maze_size+y_cor+1][x_cor+1];	
	var cur_down = maze_Matrix[maze_size+y_cor-1][x_cor+1];
	var cur_left = maze_Matrix[maze_size+y_cor][x_cor+1-1];
	var cur_right = maze_Matrix[maze_size+y_cor][x_cor+1+1];
    // alert('Cur_up is'+cur_up);
    // alert('Cur_down is'+cur_down);
    // alert('Cur_left is'+cur_left);
    // alert('Cur_right is'+cur_right);
   
    if (cur_up!='#') {		 
		 setUp = true;		  
	 }
     if (cur_down!='#') {	 
		 setDown = true;	  
	 }

     if (cur_left!='#') {	 
		 setLeft = true;	  
	 }
     if (cur_right!='#') {
		 
		 setRight = true;	  
	 }     
}

function makeWall(c_){
    if(c_==-30){
        c_ =10000
    }
    else{
        c_= c_
        
    }
    return c_
}
function Guide() {
    checkDistance();
    checkExit();
	
   // var distance_char = maze_Matrix[1][1];
    var distance = distance_char.charCodeAt(0);

    //console.log('distance'+distance);
    
    var cur_up_char  = maze_Matrix[maze_size+y_cor+1][x_cor+1];
    var cur_up = cur_up_char.charCodeAt(0);
    var cur_down_char  = maze_Matrix[maze_size+y_cor-1][x_cor+1];
    var cur_down = cur_down_char.charCodeAt(0);
    var cur_left_char  = maze_Matrix[maze_size+y_cor][x_cor+1-1];
    var cur_left = cur_left_char.charCodeAt(0);
    var cur_right_char  = maze_Matrix[maze_size+y_cor][x_cor+1+1];
    var cur_right = cur_right_char.charCodeAt(0);

    var C_up = cur_up-distance;
    var C_down =cur_down-distance;
    var C_left =cur_left-distance;
    var C_right =cur_right-distance;
    C_up =makeWall(C_up)
    C_down =makeWall(C_down)
    C_left =makeWall(C_left)
    C_right =makeWall(C_right)
    var all_dis =[];

    all_dis.push(C_up);
    all_dis.push(C_down);
    all_dis.push(C_left);
    all_dis.push(C_right);

    all_dis.sort(function(a, b){return a - b});
    var minNum = all_dis[0];
    // console.log('up'+C_up);
    // console.log('down'+C_down);
    // console.log('left'+C_left);
    // console.log('right'+C_right);
    // console.log('Min'+minNum);

    if (C_up==minNum) {
		 isUp = true; 
		
		 return "U";
	 }
	 if (C_down==minNum) {
        isDown = true; 
		 return "D";
	 }
	 if (C_left==minNum) {
        isLeft = true; 
		 return "L";
	 }
	 if (C_right==minNum) {
        isRight = true; 
		 return "R";
	 }
    
}


function randPro(pro){
    var randNum = Math.floor(Math.random() * 100);  // returns a random integer from 0 to 99
    if (randNum<pro){
        return true;
    }
    return false
}

function arrayRemove(arr, value) { 
    
    return arr.filter(function(ele){ 
        return ele != value; 
    });
}
// var array1 = ["2","3","ee5","aa8"];
// console.log( arrayRemove(array1,"ee5") ); 
function drawPrevious(){
    var ctx = c.getContext("2d");
        ctx.fillStyle = "#abccb2";

        x_cor_old= x_cor_array[x_cor_array.length-1]
        y_cor_old= y_cor_array[y_cor_array.length-1]
        // console.log("preX"+  x_cor_old)
        // console.log("preY"+  y_cor_old)
        ctx.fillRect(player_size*x_cor_old, player_size*(-y_cor_old), player_size*1, player_size*1);
}

function drawArror() {   
    
    //var curre = maze_Matrix[maze_size+y_cor][x_cor+1]
    // alert(x_cor);
    // alert(y_cor);
    var haveRed =[];
    var haveBlue =[];
    var shortIs ="";
   
    correct_direction =Guide();
    // if(MazeNum>=2){
    //     localpro =100;
    // }
    // console.log("LocalPro "+localpro)
    var randP = randPro(localpro);
    //console.log("randP: "+randP)
    if (randP){
       // console.log('Truefalse1 ###' +randP);
        truefalse ="T";
        blue_Total = blue_Total+1;
        blue_True = blue_True+1;
        
    }
    if(randP == false){
       // console.log('Truefalse1 #####' +randP);
        truefalse ="F";
        blue_Total = blue_Total+1;
    }

           
    if (setUp == true) {

        var ctx = c.getContext("2d");
        ctx.fillStyle = "#eb0909";

        ctx.beginPath();
        ctx.arc(player_size*x_cor+10, player_size*(-(y_cor+1))+10, 9,0,2*Math.PI,false);
        ctx.fill();
        haveRed.push("U");
       // ctx.fillRect(player_size*x_cor, player_size*(-(y_cor+1)), player_size*1, player_size*1);
	 } 


     if (setDown == true) {		 	 
        var ctx = c.getContext("2d");
        ctx.fillStyle = "#eb0909";
        ctx.beginPath();
        ctx.arc(player_size*x_cor+10, player_size*(-(y_cor-1))+10, 9,0,2*Math.PI,false);
        ctx.fill();
        haveRed.push("D");
        //ctx.fillRect(player_size*x_cor, player_size*(-(y_cor-1)), player_size*1, player_size*1);
	 } 
     
     if (setLeft == true) {		 	 
        var ctx = c.getContext("2d");
        ctx.fillStyle = "#eb0909";

        ctx.beginPath();
        ctx.arc(player_size*(x_cor-1)+10, player_size*(-(y_cor))+10, 9,0,2*Math.PI,false);
        ctx.fill();
        haveRed.push("L");
       // ctx.fillRect(player_size*(x_cor-1), player_size*(-y_cor), player_size*1, player_size*1);
	 } 

     if (setRight == true) {		 	 
        var ctx = c.getContext("2d");
        ctx.fillStyle = "#eb0909";
        ctx.beginPath();
        ctx.arc(player_size*(x_cor+1)+10, player_size*(-(y_cor))+10, 9,0,2*Math.PI,false);
        ctx.fill();
        haveRed.push("R");
        //ctx.fillRect(player_size*(x_cor+1), player_size*(-y_cor), player_size*1, player_size*1);
	 } 

  
   //console.log("haveRED"+ haveRed.length)
   NumDirection = haveRed.length 
     if(x_cor==0&&y_cor==0&&haveRed.length==2){
        isDecisionPoint = true;
     }


     if (haveRed.length >= 3){
        isDecisionPoint = true;

        //console.log("have decision:***"+  isDecisionPoint )
            if (haveRed.length == 4){
            var isTriple = true;
            triple ++ ;
            
        }
         
     }

    //  if (haveRed.length <= 3){
    //     isDecisionPoint = false;
    //     console.log("have decision:***"+  isDecisionPoint )
       
    //  }
        
     

     if( isUp == true){
        shortIs = "U";
        var ctx = c.getContext("2d");

        if (randP==true){
            ctx.fillStyle = "#194981";
            haveBlue.push("U");
            blue_direction = "U";
            haveRed = arrayRemove(haveRed,"U");
            ctx.beginPath();
            ctx.arc(player_size*(x_cor)+10, player_size*(-(y_cor+1))+10, 9,0,2*Math.PI,false);
            ctx.fill();
        }
        else{
            ctx.fillStyle = "#eb0909";
            ctx.beginPath();
            ctx.arc(player_size*(x_cor)+10, player_size*(-(y_cor+1))+10, 9,0,2*Math.PI,false);
            ctx.fill();
        }
       
      
     }
     if( isDown == true){
        shortIs = "D";
        var ctx = c.getContext("2d");
        if (randP==true){
            ctx.fillStyle = "#194981";
            haveBlue.push("D");
            blue_direction = "D";
            haveRed = arrayRemove(haveRed,"D");
            ctx.beginPath();
        ctx.arc(player_size*(x_cor)+10, player_size*(-(y_cor-1))+10, 9,0,2*Math.PI,false);
        ctx.fill();
        }
        else{
            ctx.fillStyle = "#eb0909";
            ctx.beginPath();
            ctx.arc(player_size*(x_cor)+10, player_size*(-(y_cor-1))+10, 9,0,2*Math.PI,false);
         ctx.fill();
        }
       
     }
     if( isLeft == true){
        shortIs = "L";
        var ctx = c.getContext("2d");
        if (randP==true){
            ctx.fillStyle = "#194981";
            haveBlue.push("L");
            blue_direction = "L";
            haveRed = arrayRemove(haveRed,"L");
            ctx.beginPath();
        ctx.arc(player_size*(x_cor-1)+10, player_size*(-(y_cor))+10, 9,0,2*Math.PI,false);
        ctx.fill();
        }
        else{
            ctx.fillStyle = "#eb0909";
            ctx.beginPath();
            ctx.arc(player_size*(x_cor-1)+10, player_size*(-(y_cor))+10, 9,0,2*Math.PI,false);
         ctx.fill();
        }
       
     }
     if( isRight == true){
        shortIs = "R";
        var ctx = c.getContext("2d");
        if (randP==true){
            ctx.fillStyle = "#194981";
            haveBlue.push("R");
            blue_direction = "R";
            haveRed = arrayRemove(haveRed,"R");
            ctx.beginPath();
        ctx.arc(player_size*(x_cor+1)+10, player_size*(-(y_cor))+10, 9,0,2*Math.PI,false);
        ctx.fill();
        }
        else{
            ctx.fillStyle = "#eb0909";
            ctx.beginPath();
            ctx.arc(player_size*(x_cor+1)+10, player_size*(-(y_cor))+10, 9,0,2*Math.PI,false);
         ctx.fill();
        }
       
     }

     //console.log('haveRed  is ' + haveRed.length);
     //console.log('haveBlue  is ' +haveBlue.length);
     if (haveBlue.length !=0){
        //console.log('Blue 1 is ' +haveBlue[0]);
     }
    
     if(haveBlue.length ==0){

        // console.log('shortIS ' +shortIs);
        // console.log('Before move haveRed is now!! ' +haveRed.length);
        // console.log(haveRed);
        var ccshort = shortIs;
        haveRed = arrayRemove(haveRed,ccshort);
        // console.log('After move haveRed is now!! ' +haveRed.length);
        // console.log(haveRed);
        var randNum = Math.floor(Math.random() * haveRed.length);  // returns a random integer from 0 to 99
       

        var drawDirection = haveRed[randNum];
       // console.log('drawDirecition ' +drawDirection);
        if (drawDirection == "U"){
            blue_direction ="U";
            var ctx = c.getContext("2d");
            ctx.fillStyle = "#194981";
            ctx.beginPath();
            ctx.arc(player_size*x_cor+10, player_size*(-(y_cor+1))+10, 9,0,2*Math.PI,false);
            ctx.fill();
        }
        if (drawDirection == "D"){
            blue_direction ="D";
            var ctx = c.getContext("2d");
            ctx.fillStyle = "#194981";
            ctx.beginPath();
            ctx.arc(player_size*x_cor+10, player_size*(-(y_cor-1))+10, 9,0,2*Math.PI,false);
            ctx.fill();
        }
 
        if (drawDirection == "L"){
            blue_direction ="L";
            var ctx = c.getContext("2d");
            ctx.fillStyle = "#194981";
            ctx.beginPath();
            ctx.arc(player_size*(x_cor-1)+10, player_size*(-y_cor)+10, 9,0,2*Math.PI,false);
            ctx.fill();
        }
 
        if (drawDirection == "R"){
            blue_direction ="R";
            var ctx = c.getContext("2d");
            ctx.fillStyle = "#194981";
            ctx.beginPath();
            ctx.arc(player_size*(x_cor+1)+10, player_size*(-y_cor)+10, 9,0,2*Math.PI,false);
            ctx.fill();
        }

     }
    // console.log('true false' +truefalse);
   //  console.log('correct' +correct_direction);
    // console.log('Blue current is ' +blue_direction);
     if (haveBlue.length !=0){
       // console.log('Blue 2 is ' +haveBlue[0]);
     }
}


function checkCloser(){

console.log("player_direction: "+player_direction)
console.log("correct_direction: "+correct_direction)



        if (player_direction == correct_direction){
            if(checkExit()==true){
                var content  = "You found the exit!";
                document.getElementById("demo").innerHTML =content;
            }
            else{
                var content  = "You are closer to the exit.";
                document.getElementById("demo").innerHTML =content;
            }
       
        }
        else{
        var content  = "You are further away from the exit.";
        document.getElementById("demo").innerHTML =content;
        }




}

function confirmDirection(){
    var d = new Date();
    var current_time = d.getTime();
    var current = current_time-start_time;
    var totalTime = current_time- init_time;

    var previous_rt =  reaction_time[reaction_time.length-1];
    reaction_time.push(current_time);
    var current_rt = current_time-previous_rt;

    //console.log("current reaction time"+current_rt);
    var bluepercentage = blue_True /blue_Total;
    x_cor_array.push(x_cor)
    y_cor_array.push(y_cor)
   console.log("Pushed "+x_cor+","+y_cor)
   Instruction();

  // console.log("my decision point is "+isDecisionPoint)
   var isDP =0;
       if (isDecisionPoint ==true){
        isDP =1;
       }
       //console.log("isDP:"+isDP)

//    results = results+age+","+year+","+concen+","+gender+","+hand+","+sonaid+","+MazeNum+ ","+localpro+","+globalpro+","+player_step+","+ x_cor+","+y_cor+","+ player_direction+","+totalTime+","+current+","+current_rt+","+localProbability+","+globalProbability+","
//     +correct_direction +","+blue_direction+","+"N"+","+bluepercentage+","+mydistance +","+triple+ "\n";
   if(MazeNum==1){
    results = results+age+","+year+","+concen+","+gender+","+hand+","+sonaid+","+"P"+ ","+
    "1"+","+localpro+","+globalpro+","+player_step+","+ x_cor+","+y_cor+","+ player_direction+
    ","+totalTime+","+current+","+current_rt+","+localProbability+","+globalProbability+","
    +correct_direction +","+blue_direction+","+truefalse+","+bluepercentage+","+mydistance+
    ","+triple+","+isDP+ ","+local_condition +","+global_condition+","+NumDirection+","+ closeGroundTruth +","+closeGlobalGroundTruth
    +","+maze_step+","+localCount+","+globalCount+","+0+","+minsteps+"\n";
   }
   else {
    var mazenum1= MazeNum-1
           results = results+age+","+year+","+concen+","+gender+","+hand+","+ sonaid+","
           +mazenum1+ ","+"0"+","+localpro+","+globalpro+","+player_step+","+ x_cor+","+y_cor+","
           + player_direction+","+totalTime+","+current+","+current_rt+","+localProbability+","+globalProbability+","
    +correct_direction +","+blue_direction+","+truefalse+","+bluepercentage+","+mydistance +","
    +triple+","+isDP+ ","+local_condition +","+global_condition+","+NumDirection+","+ closeGroundTruth +","+closeGlobalGroundTruth +
    ","+maze_step+","+localCount+","+globalCount+","+sectionNum+","+minsteps+"\n";
   }

   isDecisionPoint = false;

}

function TallyUpPPT(fileUpdates) {
            xml = new XMLHttpRequest()
            xml.open('POST', 'UpdatePptTally.php', true);
            xml.setRequestHeader('Accept', 'application/json')
            xml.send(JSON.stringify(fileUpdates))
          }

startMaze();

TallyUpPPT({track: {condition: jscondition}, filename: tallyfilename})

function Instruction(){
    if(MazeNum==1){
      
        if(x_cor==5&&y_cor==-4){
            alert("Now you know how to move in the maze, please move the local probability slider to 75% and keep moving.")
        }
        else if (localProbability==75 &&x_cor==6&&y_cor==-4){
            alert("Great, you did it! In the real experiment, moving slider to 75% means your estimation of the blue circle is pointing to the correct direction 3/4 of the time.")
        }
        else if(mydistance== 10&&localProbability!=75 ){
            alert("Did you move the local probability slider to 75%? Let us do this maze again to make sure you understand the instruction!")
           
            startMaze();

        }

        else if (mydistance ==0){
            if(globalProbability !=0){
                alert("You found the exit! Please move the global probability slider to 0%.")
            }
            
        }
       
       
    }
}

var d = new Date();
init_time = d.getTime();


 var c = document.getElementById("myCanvas");
    var ctx = c.getContext("2d");
    ctx.fillStyle = "#EEEEEE";
    ctx.fillRect(0, 0, player_size*15, player_size*15);
    ctx.fillStyle = "#448544";
    ctx.fillRect(player_size*x_cor, player_size*y_cor, player_size*1, player_size*1);
    Guide();
    checkArror();
    drawArror(); 
    confirmDirection();


  
document.addEventListener('keydown', (event) => {
    if (event.key == 'ArrowRight') {
        player_direction ="R";
        player_step= player_step +1;
        maze_step =maze_step+1;
        //console.log(player_step);
        x_cor=x_cor+1;
       
        var cur_ = maze_Matrix[maze_size+y_cor][x_cor+1];
        exit = checkExit()
        if(exit==false){
            checkCloser()
        }
        
        if(cur_ =='#'){
            x_cor=x_cor-1;
           
        }
  
        drawPrevious()
        var ctx = c.getContext("2d");
        ctx.fillStyle = "#EEEEEE";
        ctx.fillRect(0, 0, player_size*15, player_size*15);
        ctx.fillStyle = "#448544";
        ctx.fillRect(player_size*x_cor, player_size*(-y_cor), player_size*1, player_size*1);
        drawPrevious()
        Guide();
        checkArror();
        drawArror(); 
        confirmDirection();
        
    }
});


document.addEventListener('keydown', (event) => {
    if (event.key == 'ArrowLeft') {
        player_direction ="L";
       
        player_step= player_step +1;
        maze_step =maze_step+1;
        //console.log(player_step);
       
        x_cor=x_cor-1;

        var cur_ = maze_Matrix[maze_size+y_cor][x_cor+1];
        exit = checkExit()
        if(exit==false){
            checkCloser()
        }
        
        if(cur_ =='#'){
            x_cor=x_cor+1;
           
        }
     
        drawPrevious()
        var ctx = c.getContext("2d");
        ctx.fillStyle = "#EEEEEE";
        ctx.fillRect(0, 0, player_size*15, player_size*15);
        ctx.fillStyle = "#448544";
        ctx.fillRect(player_size*x_cor, player_size*(-y_cor), player_size*1, player_size*1);
        ctx.fillStyle = "#118514";
        drawPrevious()
        Guide();
        checkArror();
        drawArror(); 
        confirmDirection();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key == 'ArrowUp') {
        player_direction ="U";
      
        player_step= player_step +1;
        maze_step =maze_step+1;
        //console.log(player_step);
       

        y_cor=y_cor+1;
        var cur_ = maze_Matrix[maze_size+y_cor][x_cor+1];
        exit = checkExit()
        if(exit==false){
            checkCloser()
        }
        
        
        if(cur_ =='#'){
            y_cor=y_cor-1;
           
        }
        drawPrevious()
      
        var ctx = c.getContext("2d");
        ctx.fillStyle = "#EEEEEE";
        ctx.fillRect(0, 0, player_size*15, player_size*15);
        ctx.fillStyle = "#448544";
        ctx.fillRect(player_size*x_cor, player_size*(-y_cor), player_size*1, player_size*1);
        drawPrevious()
        Guide();
        checkArror();
        drawArror(); 
        confirmDirection();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key == 'ArrowDown') {
        player_direction ="D";
  
        player_step= player_step +1;
        maze_step =maze_step+1;
       
  
        y_cor=y_cor-1;
        var cur_ = maze_Matrix[maze_size+y_cor][x_cor+1];
        exit = checkExit()
        if(exit==false){
            checkCloser()
        }
        
        if(cur_ =='#'){
            y_cor=y_cor+1;
        
        }
     
        var ctx = c.getContext("2d");
        ctx.fillStyle = "#EEEEEE";
        ctx.fillRect(0, 0, player_size*15, player_size*15);
        ctx.fillStyle = "#448544";
        ctx.fillRect(player_size*x_cor, player_size*(-y_cor), player_size*1, player_size*1);
       
        drawPrevious()
        Guide();
        checkArror();
        drawArror(); 
        confirmDirection();

    }
});

    var Glo_slider = document.getElementById("GlobalRange");
    var Glo_output = document.getElementById("GlobalPro");

    var Loc_slider = document.getElementById("LocalRange");
    var Loc_output = document.getElementById("LocalPro");
    Glo_output.innerHTML = Glo_slider.value;

    Loc_output.innerHTML = Loc_slider.value;
   
    
    Glo_slider.oninput = function() {
        if(fixed ==false){

            Glo_output.innerHTML = this.value;

        globalProbability =this.value;
            //console.log("global changed"+ globalProbability)

        }
        else{
            Glo_output.innerHTML = 50;
            globalProbability =50;
            Glo_slider.value =50;

        }
    
   
    }

    Loc_slider.oninput = function() {
      Loc_output.innerHTML = this.value;
      localProbability = this.value;

     // console.log("local changed"+ localProbability)
    }

  
    

    document.addEventListener('keydown', (event) => {
    if (event.keyCode == 27) {
        
        saveData (sonaid+"_Results_withdraw", results)
        window.location.href = "https://uwaterloo.sona-systems.com/webstudy_credit.aspx?experiment_id=5220&credit_token=602580927052460b8d538ec2137492d7&survey_code="+sonaid;

    }
});


    function saveData (name, data) {
      

          return new Promise((resolve,reject) => {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {

                //alert("Ready state before: "+ this.readyState)
              if (this.readyState == 4 && this.status == 200) {
                resolve('file successfully saved')
                alert("Saved")
              } else if (this.status > 400) {
                alert("Reject")
                reject(this.status)
              }
              else{
                //alert("Ready state else: "+ this.readyState)
              }


            };
            //alert("Ready state after: "+ this.readyState)

    //         sleep(28000).then(() => {
    // // Do something after the sleep!

          
    //         });

           // setTimeout(() => {  console.log("hello World!"); }, 4000);
            xhr.open('POST', "./write_data_sixuan.php");
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify({filename: name, filedata: data}));
         
            
            alert("Trying to save the results! Ready state:" + this.readyState+" Status:"+this.status)

            
          })
        }

        function sleep (time) {
        return new Promise((resolve) => setTimeout(resolve, time));
        }
        function creditWeb () {
          return new Promise((resolve,reject) => {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                resolve('file successfully saved')
              } else if (this.status > 400) {
                reject(this.status)
              }
            };
            xhr.open('POST', "./write_data_sixuan.php");
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify({filename: name, filedata: data}));
          })
        }
        function submitclick(){
            
            if(MazeNum <(maze80grouplength+maze73grouplength+maze60grouplength+2)){
              response=  confirm("You only have a few more mazes to go. Are you sure you want to leave the experiment?");
              if(response== true){
                alert("Submit button clicked!");
                saveData(sonaid+'_exportResults',results); 
                return true;

              }
              else if (response== false){
                return false;
              }
            }
            if(MazeNum >=(maze80grouplength+maze73grouplength+maze60grouplength+2)){
                saveData(sonaid+'_exportResults',results); 
                return true;
            }
            else{
                saveData(sonaid+'_exportResults',results); 
                return true;
            }
              
            

        }
               
    </script>

</p>If you have finished the whole experiment, please hit the submit button</p>
<input type="submit" value="Submit" onclick="return submitclick();">
</form>
</body>
</html>