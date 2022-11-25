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
  
  <p>[Global] The probability of whether the exit is at the bottom right: <span id="GlobalPro"></span></p>
  <input type="range" min="1" max="100" value="50" class="slider" id="GlobalRange">

  <!-- <p>Local probability:</p> -->
  
  <p>[Local] The probability of whether the blue circle is pointing to the shortest path: <span id="LocalPro"></span></p>
  <input type="range" min="1" max="100" value="50" class="slider" id="LocalRange">
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

//  var jscondition = {}
//  var local_condition = ""
//       //console.log(tallyinfo.length)

// if (Number(tallyinfo.condition.LowToHigh) > Number(tallyinfo.condition.HighToLow)) {
//             jscondition = "HighToLow"
//             local_condition ="HL"
       
// } 
// else if (Number(tallyinfo.condition.LowToHigh) < Number(tallyinfo.condition.HighToLow)) {
// jscondition = "LowToHigh"
// local_condition ="LH"   


// } 
// else { //in case the saving system isnt working properly, you dont want it to default to either value if manual+automatic are equal (like if theyre both zero)
//             jscondition = "LowToHigh"
//             local_condition ="LH"
//             if (Math.random() < 0.5) {
//               jscondition = "HighToLow"
//               local_condition ="HL"
//             }
// }

jscondition = "LowToHigh"
local_condition ="LH"
      
var x_cor=0;
var y_cor=0;
var x_cor_array=[]
var y_cor_array=[]
var x_cor_old=0;
var y_cor_old=0;
var maze_size=11; 
var maze
var player_size = 20;
var localCount =0
var localpro_list_LH =[50,60,70,80,90,100,100,90,80,70,60,50]
var localpro_list_HL =[100,90,80,70,60,50,50,60,70,80,90,100]
var localpro_list =[]
if (local_condition == "HL"){
    localpro_list =localpro_list_HL 
  
}
else if(local_condition == "LH") {
    localpro_list =localpro_list_LH 

}

else{
    alert("No local condition assigned, please contact the researcher at s743chen@uwaterloo.ca")
}


var localmax = localpro_list.length
//console.log("localmax"+localmax)
var localpro = localpro_list[localCount];
var globalpro = 100;
var mydistance;
var localProbability = 50;
var globalProbability =50;
var MazeNum = 1;
var triple = 0;
var NumDirection =0;
var trialNum =1;

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

var sonaid = 111111

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


var results ="Age,Year,Concentration,Gender,Handness,SONAID,Maze,PracticeOrNot,Prior_Local,Prior_Global,Steps,CorX,CorY,Player_Direction,Total_Time(millsecond),Maze_Time(millsecond),Step_Time(millsecond),Local_Probability,Global_Probability,Correct_Direction,Blue,BlueIsCorrect,Blue_Correctness,Distance,Triple,Decision_Point,Condition,NumDirection,Trial,MazeStep,LocalCount\n";
	
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
"#W#M#GFEDCBA#\n" +
"#V#L#H###D###\n" +
"#U#KJI#GFE#g#\n" +
"#T#L###H#F#f#\n" +
"#S#MNO#I#G#e#\n" +
"#R#N#######d#\n" +
"#QPO#]^_#abc#\n" +
"#R###\\###`###\n" +
"#STU#[\\]^_#a#\n" +
"#T###Z#####`#\n" +
"#UVWXYZ[\\]^_#\n" +
"#############" 
var maze2 =
"#############\n" +
"#[\\]^_#Y#EDA#\n" +
"#Z#####X###B#\n" +
"#YZ[#YXW#EDC#\n" +
"#X#####V#F###\n" +
"#W#Y#STU#G#e#\n" +
"#V#X#R###H#d#\n" +
"#UVW#Q#O#I#c#\n" +
"#T###P#N#J#b#\n" +
"#SRQPONMLK#a#\n" +
"#T#########`#\n" +
"#UVWXYZ[\\]^_#\n" +
"#############"

var maze3=
"#############\n" +
"#_#a#IHGFEDA#\n" +
"#^#`#######B#\n" +
"#]#_#a#S#U#C#\n" +
"#\\#^#`#R#T#D#\n" +
"#[\\]^_#Q#S#E#\n" +
"#Z#####P#R#F#\n" +
"#YZ[\\]#OPQ#G#\n" +
"#X#####N###H#\n" +
"#WXY#S#MLKJI#\n" +
"#V###R#N#####\n" +
"#UTSRQPOPQRS#\n" +
"#############" 
var maze4 =
"#############\n" +
"#W#MNO#EDCBA#\n" +
"#V#L###F#####\n" +
"#U#KJIHGHIJK#\n" +
"#T###J#######\n" +
"#S#MLKLM#WXY#\n" +
"#R#N#####V###\n" +
"#QPOPQRSTUVW#\n" +
"#R###########\n" +
"#S#Y#[\\]^_`a#\n" +
"#T#X#Z#######\n" +
"#UVWXYZ[\\]^_#\n" +
"#############" 

var maze5 =
"#############\n" +
"#WVUVW#M#CBA#\n" +
"###T###L#D###\n" +
"#U#STU#K#E#K#\n" +
"#T#R###J#F#J#\n" +
"#SRQPO#IHGHI#\n" +
"#####N#J#####\n" +
"#Y#ONMLK#]#_#\n" +
"#X###N###\\#^#\n" +
"#W#QPO#YZ[\\]#\n" +
"#V#R###X#####\n" +
"#UTSTUVWXYZ[#\n" +
"#############" 
var maze6 =
"#############\n" +
"#[#UTSTU#K#A#\n" +
"#Z###R###J#B#\n" +
"#YXW#QRS#I#C#\n" +
"###V#P###H#D#\n" +
"#WVU#O#M#GFE#\n" +
"###T#N#L#H###\n" +
"#Y#S#MLKJIJK#\n" +
"#X#R#N#######\n" +
"#W#QPOPQRS#]#\n" +
"#V#R#######\\#\n" +
"#UTSTUVWXYZ[#\n" +
"#############" 
var maze7 =
"#############\n" +
"#W#YZ[#EDCBA#\n" +
"#V#X###F#####\n" +
"#UVW#M#G#Y#g#\n" +
"#T###L#H#X#f#\n" +
"#S#MLKJI#W#e#\n" +
"#R#N#####V#d#\n" +
"#QPOPQRSTU#c#\n" +
"###P#######b#\n" +
"#SRQRSTU#_#a#\n" +
"#T#######^#`#\n" +
"#UVWXYZ[\\]^_#\n" +
"#############" 
var maze8 =
"#############\n" +
"#_`abc#U#EDA#\n" +
"#^#####T###B#\n" +
"#]#SRQRS#EDC#\n" +
"#\\###P#####D#\n" +
"#[#QPO#M#GFE#\n" +
"#Z###N#L#H###\n" +
"#YZ[#MLKJIJK#\n" +
"#X###N#L#####\n" +
"#WXY#O#MNOPQ#\n" +
"#V###P###P###\n" +
"#UTSRQRS#QRS#\n" +
"#############" 
var maze9 =
"#############\n" +
"#[\\]#GFEDCBA#\n" +
"#Z###H#######\n" +
"#Y#KJIJKLM#_#\n" +
"#X#L#######^#\n" +
"#W#M#S#UVW#]#\n" +
"#V#N#R#T###\\#\n" +
"#U#OPQRS#YZ[#\n" +
"#T#P#####X#\\#\n" +
"#SRQRSTUVW#]#\n" +
"#T###########\n" +
"#UVWXYZ[\\]^_#\n" +
"#############" 
var maze10 =
"#############\n" +
"#S#UTS#EDCBA#\n" +
"#R###R###D###\n" +
"#QPOPQRS#E#g#\n" +
"###N#####F#f#\n" +
"#ONMLKJIHG#e#\n" +
"#P#########d#\n" +
"#QRS#]^_`a#c#\n" +
"#R###\\#####b#\n" +
"#S#YZ[\\]^_#a#\n" +
"#T#X#######`#\n" +
"#UVWXYZ[\\]^_#\n" +
"#############" 


var maze11 =
"#############\n" +
"#WVU#STU#EDA#\n" +
"###T#R#####B#\n" +
"#U#S#Q#WVU#C#\n" +
"#T#R#P###T#D#\n" +
"#S#Q#OPQRS#E#\n" +
"#R#P#N#####F#\n" +
"#QPONMLKJIHG#\n" +
"#R###N#######\n" +
"#S#Y#O#]^_`a#\n" +
"#T#X###\\###b#\n" +
"#UVWXYZ[\\]#c#\n" +
"#############" 
var maze12 =
"#############\n" +
"#[\\]^_`abc#A#\n" +
"#Z#####b###B#\n" +
"#YZ[#M#c#EDC#\n" +
"#X###L###F###\n" +
"#W#MLKJIHG#e#\n" +
"#V#N#######d#\n" +
"#U#OPQ#_`abc#\n" +
"#T#P###^#####\n" +
"#SRQRS#]#_`a#\n" +
"#T#####\\#^###\n" +
"#UVWXYZ[\\]^_#\n" +
"#############" 
var maze13 =
"#############\n" +
"#_`a#cba#CBA#\n" +
"#^#####`#D###\n" +
"#]#_`a#_#E#K#\n" +
"#\\#^###^#F#J#\n" +
"#[\\]#[#]#GHI#\n" +
"#Z###Z#\\#H###\n" +
"#Y#W#YZ[#IJK#\n" +
"#X#V#X###J###\n" +
"#WVUVWXY#K#Q#\n" +
"#X#T#####L#P#\n" +
"#Y#SRQPONMNO#\n" +
"#############" 

var maze14 =
"#############\n" +
"#STUVW#M#EDA#\n" +
"#R#####L###B#\n" +
"#QPOPQ#K#EDC#\n" +
"###N###J###D#\n" +
"#ONMLKJIHGFE#\n" +
"###N#########\n" +
"#QPOPQRSTUVW#\n" +
"#R###########\n" +
"#STUVWXYZ[#a#\n" +
"#T###X#####`#\n" +
"#UVW#YZ[\\]^_#\n" +
"#############" 
var maze15 =
"#############\n" +
"#c#e#O#IHG#A#\n" +
"#b#d#N#J#F#B#\n" +
"#abc#MLK#EDC#\n" +
"#`###N#######\n" +
"#_#U#O#U#W#a#\n" +
"#^#T#P#T#V#`#\n" +
"#]#SRQRSTU#_#\n" +
"#\\###R#####^#\n" +
"#[#Y#S#]\\[\\]#\n" +
"#Z#X#T###Z###\n" +
"#YXWVUVWXYZ[#\n" +
"#############" 
var maze16 =
"#############\n" +
"#cbabc#I#CBA#\n" +
"###`###H#D###\n" +
"#]^_#Y#GFEFG#\n" +
"#\\###X#H###H#\n" +
"#[#UVW#I#O#I#\n" +
"#Z#T###J#N###\n" +
"#Y#S#MLKLM#_#\n" +
"#X#R#N#####^#\n" +
"#W#QPOPQRS#]#\n" +
"#V#R#######\\#\n" +
"#UTSTUVWXYZ[#\n" +
"#############" 
var maze17 =
"#############\n" +
"#cdefg#U#G#A#\n" +
"#b#####T#F#B#\n" +
"#a#_#U#S#EDC#\n" +
"#`#^#T#R###D#\n" +
"#_^]#S#Q#O#E#\n" +
"###\\#R#P#N#F#\n" +
"#YZ[#Q#O#M#G#\n" +
"#X###P#N#L#H#\n" +
"#W#U#ONMLKJI#\n" +
"#V#T#P#######\n" +
"#UTSRQRSTUVW#\n" +
"#############" 
var maze18 =
"#############\n" +
"#SRQ#GFEDCBA#\n" +
"###P###F#####\n" +
"#Q#O#IHG#ijk#\n" +
"#P#N#J###h###\n" +
"#ONMLK#]#g#e#\n" +
"#P#####\\#f#d#\n" +
"#QRSTU#[#e#c#\n" +
"#R#T###Z#d#b#\n" +
"#S#UVWXY#cba#\n" +
"#T#########`#\n" +
"#UVWXYZ[\\]^_#\n" +
"#############" 
var maze19 =
"#############\n" +
"#_#QPO#M#K#A#\n" +
"#^###N#L#J#B#\n" +
"#]#_#MLK#I#C#\n" +
"#\\#^###J#H#D#\n" +
"#[#]#O#IHGFE#\n" +
"#Z#\\#N#J#####\n" +
"#Y#[#MLKLM#S#\n" +
"#X#Z###L###R#\n" +
"#WXY#ONMNOPQ#\n" +
"#V###P#####R#\n" +
"#UTSRQRSTU#S#\n" +
"#############" 
var maze20 =
"#############\n" +
"#c#]^_#Q#CBA#\n" +
"#b#\\###P#D###\n" +
"#a#[\\]#O#EFG#\n" +
"#`#Z###N###H#\n" +
"#_#YZ[#MLKJI#\n" +
"#^#X###N#####\n" +
"#]#W#U#O#UVW#\n" +
"#\\#V#T#P#T###\n" +
"#[#UTSRQRSTU#\n" +
"#Z#V#####T#V#\n" +
"#YXWXYZ[#U#W#\n" +
"#############" 
var maze21 =
"#############\n" +
"#S#QRSTU#CBA#\n" +
"#R#P#####D###\n" +
"#Q#OPQRS#E#S#\n" +
"#P#N#####F#R#\n" +
"#ONMLKJIHG#Q#\n" +
"#P#####J###P#\n" +
"#QRS#]#KLMNO#\n" +
"#R###\\#L#N#P#\n" +
"#STU#[#M#O#Q#\n" +
"#T###Z#####R#\n" +
"#UVWXYZ[\\]#S#\n" +
"#############" 
var maze22 =
"#############\n" +
"#_#abc#IHG#A#\n" +
"#^#`#####F#B#\n" +
"#]#_#]^_#EDC#\n" +
"#\\#^#\\#`#F###\n" +
"#[#]\\[#a#GHI#\n" +
"#Z###Z###H###\n" +
"#YZ[#Y#KJIJK#\n" +
"#X###X#L#####\n" +
"#W#UVW#M#STU#\n" +
"#V#T###N#R###\n" +
"#UTSRQPOPQRS#\n" +
"#############" 
var maze23 =
"#############\n" +
"#ONMLKJI#G#A#\n" +
"#######H#F#B#\n" +
"#MLKJIHGFEDC#\n" +
"#####J#######\n" +
"#[ZY#K#Q#S#U#\n" +
"###X#L#P#R#T#\n" +
"#YXW#MNOPQRS#\n" +
"###V#N###R###\n" +
"#WVU#OPQ#STU#\n" +
"###T#P###T###\n" +
"#UTSRQRS#UVW#\n" +
"#############" 
var maze24 =
"#############\n" +
"#W#UVW#M#G#A#\n" +
"#V#T###L#F#B#\n" +
"#U#STU#K#EDC#\n" +
"#T#R###J#F###\n" +
"#S#Q#KJIHG#U#\n" +
"#R#P#L#####T#\n" +
"#QPONMNOPQRS#\n" +
"#R###########\n" +
"#STUVWXY#_#a#\n" +
"#T#######^#`#\n" +
"#UVWXYZ[\\]^_#\n" +
"#############" 
var maze25 =
"#############\n" +
"#_`abc#ihg#A#\n" +
"#^#######f#B#\n" +
"#]#_#edcde#C#\n" +
"#\\#^###b###D#\n" +
"#[\\]#_`a#c#E#\n" +
"#Z###^###b#F#\n" +
"#Y#[#]^_`a#G#\n" +
"#X#Z#\\#####H#\n" +
"#WXYZ[\\]#KJI#\n" +
"#V#########J#\n" +
"#UTSRQPONMLK#\n" +
"#############" 
var maze26 =
"#############\n" +
"#_#abc#Q#G#A#\n" +
"#^#`###P#F#B#\n" +
"#]#_#MNO#EDC#\n" +
"#\\#^#L###F###\n" +
"#[#]#KJIHG#Y#\n" +
"#Z#\\#L#####X#\n" +
"#YZ[#MNO#UVW#\n" +
"#X###N###T###\n" +
"#W#QPOPQRSTU#\n" +
"#V#R#######V#\n" +
"#UTSTUVWXY#W#\n" +
"#############" 
var maze27 =
"#############\n" +
"#cba#KJI#G#A#\n" +
"###`###H#F#B#\n" +
"#]#_#a#GFEDC#\n" +
"#\\#^#`#H#F###\n" +
"#[#]^_#I#GHI#\n" +
"#Z#\\#####H#J#\n" +
"#Y#[#QRS#I#K#\n" +
"#X#Z#P###J###\n" +
"#WXY#ONMLKLM#\n" +
"#V###P#####N#\n" +
"#UTSRQRSTU#O#\n" +
"#############" 
var maze28 =
"#############\n" +
"#_#a#O#M#CBA#\n" +
"#^#`#N#L#D###\n" +
"#]#_#M#K#EFG#\n" +
"#\\#^#L#J#F###\n" +
"#[\\]#KJIHGHI#\n" +
"#Z###L#####J#\n" +
"#YZ[#MNO#]#K#\n" +
"#X###N###\\###\n" +
"#W#QPOPQ#[\\]#\n" +
"#V#R#####Z###\n" +
"#UTSTUVWXYZ[#\n" +
"#############" 
var maze29 =
"#############\n" +
"#O#IHGFEDCBA#\n" +
"#N#J###F#####\n" +
"#MLK#Y#G#]^_#\n" +
"###L#X###\\###\n" +
"#ONM#W#Y#[#]#\n" +
"###N#V#X#Z#\\#\n" +
"#QPO#U#WXYZ[#\n" +
"###P#T#V#Z#\\#\n" +
"#SRQRSTU#[#]#\n" +
"###R###V#\\#^#\n" +
"#UTSTU#W#]#_#\n" +
"#############" 
var maze30 =
"#############\n" +
"#[#U#O#M#O#A#\n" +
"#Z#T#N#L#N#B#\n" +
"#Y#S#M#KLM#C#\n" +
"#X#R#L#J###D#\n" +
"#W#Q#KJIHGFE#\n" +
"#V#P#L#######\n" +
"#U#ONMNOPQRS#\n" +
"#T#P#######T#\n" +
"#SRQRSTUVW#U#\n" +
"#T#R#T#V###V#\n" +
"#U#S#U#WXY#W#\n" +
"#############"
var maze31 =
"#############\n" +
"#KJIHGFEDCBA#\n" +
"#L###########\n" +
"#M#[\\]^_`a#g#\n" +
"#N#Z#######f#\n" +
"#O#YZ[\\]#cde#\n" +
"#P#X#####b###\n" +
"#Q#W#]#_#abc#\n" +
"#R#V#\\#^#`###\n" +
"#STU#[\\]#_#a#\n" +
"###V#Z###^#`#\n" +
"#YXWXYZ[\\]^_#\n" +
"#############" 
var maze32 =
"#############\n" +
"#SRQRSTU#CBA#\n" +
"###P#####D###\n" +
"#QPO#IHGFEFG#\n" +
"###N#J#####H#\n" +
"#S#MLK#UVW#I#\n" +
"#R#N###T#####\n" +
"#QPOPQRSTU#_#\n" +
"#R#########^#\n" +
"#STUVWXYZ[\\]#\n" +
"#T#########^#\n" +
"#UVWXYZ[\\]#_#\n" +
"#############" 
var maze33 =
"#############\n" +
"#WXYZ[\\]#S#A#\n" +
"#V#######R#B#\n" +
"#U#SRQ#OPQ#C#\n" +
"#T###P#N###D#\n" +
"#S#Q#O#M#K#E#\n" +
"#R#P#N#L#J#F#\n" +
"#QPONMLKJIHG#\n" +
"#R###########\n" +
"#STUVWXYZ[\\]#\n" +
"#T###X#Z###^#\n" +
"#UVW#Y#[\\]#_#\n" +
"#############" 
var maze34 =
"#############\n" +
"#SRQPOPQRS#A#\n" +
"#####N#####B#\n" +
"#]#ONMNOPQ#C#\n" +
"#\\###L#####D#\n" +
"#[ZY#KJIHGFE#\n" +
"###X#L#######\n" +
"#Y#W#MNOPQ#[#\n" +
"#X#V#N#####Z#\n" +
"#W#U#OPQRS#Y#\n" +
"#V#T#P#####X#\n" +
"#UTSRQRSTUVW#\n" +
"#############" 
var maze35 =
"#############\n" +
"#WVUVWXYZ[#A#\n" +
"###T#######B#\n" +
"#U#STU#GFEDC#\n" +
"#T#R###H#####\n" +
"#S#QRS#IJKLM#\n" +
"#R#P###J#####\n" +
"#QPONMLKLMNO#\n" +
"#R###########\n" +
"#S#Y#[\\]#_`a#\n" +
"#T#X#Z###^###\n" +
"#UVWXYZ[\\]^_#\n" +
"#############" 
var maze36 =
"#############\n" +
"#_`abcde#EDA#\n" +
"#^#########B#\n" +
"#]#_`a#c#U#C#\n" +
"#\\#^###b#T#D#\n" +
"#[\\]^_`a#S#E#\n" +
"#Z#######R#F#\n" +
"#Y#S#Q#OPQ#G#\n" +
"#X#R#P#N###H#\n" +
"#W#QPONMLKJI#\n" +
"#V#R#####L###\n" +
"#UTSTUVW#MNO#\n" +
"#############" 
var maze37 =
"#############\n" +
"#W#Y#KLM#CBA#\n" +
"#V#X#J###D###\n" +
"#UVW#IHGFEFG#\n" +
"#T###J#####H#\n" +
"#S#Q#KLMNO#I#\n" +
"#R#P#L#######\n" +
"#QPONMNOPQ#[#\n" +
"###P#######Z#\n" +
"#SRQRSTUVWXY#\n" +
"#T#R#T#####Z#\n" +
"#U#S#UVW#]\\[#\n" +
"#############" 
var maze38 =
"#############\n" +
"#ONMLK#I#G#A#\n" +
"#####J#H#F#B#\n" +
"#Q#O#IHGFEDC#\n" +
"#P#N#J###F###\n" +
"#ONMLKLM#GHI#\n" +
"#P###########\n" +
"#QRSTUVWXYZ[#\n" +
"#R###########\n" +
"#STU#[\\]^_`a#\n" +
"#T###Z#####b#\n" +
"#UVWXYZ[\\]#c#\n" +
"#############" 
var maze39 =
"#############\n" +
"#ONMNO#I#G#A#\n" +
"###L###H#F#B#\n" +
"#MLKJIHGFEDC#\n" +
"#N###########\n" +
"#O#Y#_#a#cde#\n" +
"#P#X#^#`#b###\n" +
"#Q#W#]#_#abc#\n" +
"#R#V#\\#^#`###\n" +
"#STU#[#]^_#a#\n" +
"#T###Z#\\###`#\n" +
"#UVWXYZ[\\]^_#\n" +
"#############" 
var maze40 =
"#############\n" +
"#_#]#_`a#CBA#\n" +
"#^#\\#^###D###\n" +
"#]#[\\]#_#E#K#\n" +
"#\\#Z###^#F#J#\n" +
"#[#YZ[\\]#GHI#\n" +
"#Z#X#####H###\n" +
"#Y#W#UVW#I#S#\n" +
"#X#V#T###J#R#\n" +
"#W#U#S#Q#K#Q#\n" +
"#V#T#R#P#L#P#\n" +
"#UTSRQPONMNO#\n" +
"#############" 
var maze41 =
"#############\n" +
"#cde#c#e#EDA#\n" +
"#b###b#d###B#\n" +
"#a#cbabc#I#C#\n" +
"#`###`###H#D#\n" +
"#_^]^_#IHGFE#\n" +
"###\\###J###F#\n" +
"#Y#[#Q#K#Q#G#\n" +
"#X#Z#P#L#P###\n" +
"#WXY#ONMNO#U#\n" +
"#V#####N###T#\n" +
"#UTSRQPOPQRS#\n" +
"#############" 
var maze42 =
"#############\n" +
"#W#Q#ONM#O#A#\n" +
"#V#P###L#N#B#\n" +
"#U#O#M#KLM#C#\n" +
"#T#N#L#J###D#\n" +
"#S#MLKJIHGFE#\n" +
"#R#N#########\n" +
"#QPOPQRSTUVW#\n" +
"#R###########\n" +
"#STUVWXYZ[\\]#\n" +
"#T###########\n" +
"#UVWXYZ[\\]^_#\n" +
"#############" 
var maze43 =
"#############\n" +
"#_^]^_`abc#A#\n" +
"###\\#####d#B#\n" +
"#YZ[\\]#O#e#C#\n" +
"#X#####N###D#\n" +
"#W#U#ONMNO#E#\n" +
"#V#T#P#L###F#\n" +
"#UTSRQ#KJIHG#\n" +
"#V#T#######H#\n" +
"#W#U#_#abc#I#\n" +
"#X###^#`#####\n" +
"#YZ[\\]^_`abc#\n" +
"#############" 
var maze44 =
"#############\n" +
"#SRQPO#EDCBA#\n" +
"#####N#F#####\n" +
"#U#O#M#GHIJK#\n" +
"#T#N#L#H#####\n" +
"#S#MLKJIJK#e#\n" +
"#R#N#######d#\n" +
"#QPO#Y#[#]#c#\n" +
"#R###X#Z#\\#b#\n" +
"#STUVWXYZ[#a#\n" +
"#T#####Z#\\#`#\n" +
"#UVWXY#[#]^_#\n" +
"#############" 
var maze45 =
"#############\n" +
"#cba#c#UVW#A#\n" +
"###`#b#T###B#\n" +
"#a`_#a#S#Q#C#\n" +
"###^#`#R#P#D#\n" +
"#_^]^_#Q#O#E#\n" +
"###\\###P#N#F#\n" +
"#]\\[\\]#O#M#G#\n" +
"###Z###N#L#H#\n" +
"#WXYZ[#MLKJI#\n" +
"#V#####N#####\n" +
"#UTSRQPOPQRS#\n" +
"#############" 
var maze46 =
"#############\n" +
"#WVUVW#EDCBA#\n" +
"###T#####D###\n" +
"#U#STU#GFEFG#\n" +
"#T#R###H#####\n" +
"#S#QRS#I#c#e#\n" +
"#R#P###J#b#d#\n" +
"#QPONMLK#a#c#\n" +
"#R#####L#`#b#\n" +
"#S#Y#[#M#_`a#\n" +
"#T#X#Z###^#b#\n" +
"#UVWXYZ[\\]#c#\n" +
"#############" 
var maze47 =
"#############\n" +
"#W#IHGFEDCBA#\n" +
"#V#J#H#######\n" +
"#U#K#I#STU#[#\n" +
"#T#L###R###Z#\n" +
"#S#MNOPQRS#Y#\n" +
"#R#N###R###X#\n" +
"#QPOPQ#STUVW#\n" +
"#R#########X#\n" +
"#STUVWXYZ[#Y#\n" +
"#T###########\n" +
"#UVWXYZ[\\]^_#\n" +
"#############" 
var maze48 =
"#############\n" +
"#_#IHGFEDCBA#\n" +
"#^#####F#D###\n" +
"#]#_`a#G#E#S#\n" +
"#\\#^###H###R#\n" +
"#[\\]#O#IJK#Q#\n" +
"#Z###N#J###P#\n" +
"#Y#S#MLKLMNO#\n" +
"#X#R#N#####P#\n" +
"#W#QPO#UVW#Q#\n" +
"#V###P#T#####\n" +
"#UTSRQRSTUVW#\n" +
"#############" 
var maze49 =
"#############\n" +
"#cba#[#]^_#A#\n" +
"###`#Z#\\###B#\n" +
"#a`_#Y#[#]#C#\n" +
"###^#X#Z#\\#D#\n" +
"#[#]#W#Y#[#E#\n" +
"#Z#\\#V#X#Z#F#\n" +
"#Y#[#U#W#Y#G#\n" +
"#X#Z#T#V#X#H#\n" +
"#WXY#STUVW#I#\n" +
"#V###R#####J#\n" +
"#UTSRQPONMLK#\n" +
"#############" 
var maze50 =
"#############\n" +
"#KJIHGFEDCBA#\n" +
"#L###########\n" +
"#MNOPQ#[\\]^_#\n" +
"#N#####Z#####\n" +
"#O#UVWXYZ[\\]#\n" +
"#P#T#########\n" +
"#QRSTU#_`abc#\n" +
"#R#####^#####\n" +
"#STU#[\\]^_`a#\n" +
"#T###Z#####b#\n" +
"#UVWXYZ[\\]#c#\n" +
"#############" 





var maze_array = [mazePractice,maze1,maze2,maze3,maze4,maze5,maze6,maze7,maze8,maze9,maze10,maze11,maze12,maze13,maze14,maze15,maze16,maze17,maze18,maze19,maze20,maze21,maze22,maze23,maze24,maze25,maze26,maze27,maze28,maze29,maze30,maze31,maze32,maze33,maze34,maze35,maze36,maze37,maze38,maze39,maze40,maze41,maze42,maze43,maze44,maze45,maze46,maze47,maze48,maze49,maze50]

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
    alert("New Maze has been generated!")
    //console.log("MazeNum!!@@"+MazeNum)
    document.getElementById("consent_checkbox").checked = false;
}

function mazeStop(){
    if(MazeNum >= 51|| localCount >=localmax){       
    //if(MazeNum >= 3){
        alert("Congratulation! You have finished the experiment! Please hit the submit button!")
     
                   // downloadResults();
                    saveData(sonaid+'_exportResults',results); 
    }
}

function checkProbability(){
    if(MazeNum>1){
       
        
        if(mydistance==0){

          
            if (localProbability >=(localpro-5) &&localProbability <=(localpro+5)){

            alert("You estimation of the local probability is close to the ground truth！Within the +/-5% range.")
                    localCount ++;
                    localpro = localpro_list[localCount]
                    //console.log("update localpro"+localpro)
                    MazeNum++;
                    trialNum =1;
                  
                    MazeGenerate()  
                

            }
            else{
            alert("You estimation of the local probability is too far from the ground truth. Not within the +/-5% range.Please try again in the next maze.")
            MazeNum++;   
            trialNum++;   
            MazeGenerate() 

            }

        }
        else if(mydistance!=0){
            alert("You have not finished the current maze navigation.")
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
            alert(feeback )

            checkProbability();



            if(MazeNum == 1 && mydistance==0&&globalProbability ==100){

            
            alert("HAHA, you did it! In the real experiment, moving global probability slider to 100% means your estimation of the exit location is always at the bottom right!")

            alert("You have successfully finished the practice trial! Remember since for current experiment the exit location is always at the bottom right, the global probability will be fixed at 100%")

            MazeNum++;
            


            Glo_slider.value =100;
            Loc_slider.value =50;

            Glo_output.innerHTML = 100;
            Loc_output.innerHTML  = 50;
            localProbability =50;
            globalProbability =100;
            fixed= true;
      
            MazeGenerate()

            }

            else if(MazeNum == 1 && mydistance==0 &&globalProbability !=100){
            alert("You did not move the global silder to the 100%, please do the maze practice again!")
            MazeNum=1;
            MazeGenerate();


            }
        
        
            // else if (MazeNum == 1 &&localProbability ==50 &&globalProbability ==50&& mydistance==0){
            //     if(confirm("Did you move the sliders? Are you sure to keep the both probabilities as 50%")){
            //         MazeNum++;
            //     MazeGenerate()   
                
            //     }
            
            // }
            // else if(MazeNum == 1 &&localProbability ==50&& mydistance==0){
            //     if(confirm("Did you move the local slider? Are you sure to keep the local probability as 50%?")){
            //         MazeNum++;
            //         MazeGenerate()
                
            //     }
            
                
            // }
            // else if(MazeNum == 1 &&globalProbability ==50&& mydistance==0){
            //     if(confirm("Did you move the global slider? Are you sure to keep global probability as 50%?")){
            //         MazeNum++;
            //         MazeGenerate()
            //     }
            // }
         
        
            
            mazeStop()
    
        
        }
   
}

    // else{
    //     alert("Your estimation of local probability is too far from the ground truth, please try again.")
    //     MazeGenerate()
    // }



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
    minsteps = checkMinStep()
            

  

    // var curre = maze_Matrix[8][2]
    // alert('current '+curre);
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
       
        //console.log("practice")
        document.getElementById("maze").innerHTML = 'Maze '+'Practice';
    }
    if (MazeNum >1){
        var maze_num = MazeNum-1
        localpro = localpro_list[localCount];
       // console.log("practice+1!!")
        document.getElementById("maze").innerHTML = 'Maze '+ maze_num;
    }
  
  
    buildMaze(maze_array[MazeNum-1])


}

function checkExit() {
	// if (x_cor ==(maze_size-1) && y_cor == (-(maze_size-1)) ) {
    if (x_cor ==(1) && y_cor == (-(maze_size-1)) ) {
		
		document.getElementById("demo").innerHTML = 'You found the exit!';
      
       
	}
}

// function checkDistance() {
//     var cur_integer_char  = maze_Matrix[maze_size+y_cor][x_cor+1];
//     var cur_integer= cur_integer_char.charCodeAt(0);
//     console.log("cur_integer+char"+cur_integer_char);	
// 	var distance_char = maze_Matrix[maze_size-maze_size+1][maze_size];
//     var distance = distance_char.charCodeAt(0);
//     console.log("distance_char"+distance_char);
// 	var current_step =cur_integer-distance;
//     console.log("current_step"+ current_step);
//     var steps = current_step.toString();
//     mydistance = steps;
//     var content  = "You are " +steps+ " steps away from the exit.";
//     document.getElementById("demo").innerHTML =content;
// }
function checkDistance() {
    var cur_integer_char  = maze_Matrix[maze_size+y_cor][x_cor+1];
    var cur_integer= cur_integer_char.charCodeAt(0);
    console.log("cur_integer+char"+cur_integer_char);
    console.log(cur_integer);	
	var distance_char = maze_Matrix[maze_size-maze_size+1][maze_size];
    var distance = distance_char.charCodeAt(0);
    console.log("distance_char"+distance_char);
    console.log(distance);

	var current_step =cur_integer-distance;
    console.log("current_step_away"+ current_step);
    var steps = current_step.toString();
    mydistance = steps;
    // var content  = "You are " +steps+ " steps away from the exit.";
    // document.getElementById("demo").innerHTML =content;
    var stepcontent = "You have used " +maze_step+ " steps.";
    document.getElementById("steps").innerHTML = stepcontent;
}

function checkMinStep(){
    var int_integer_char  = maze_Matrix[maze_size+0][0+1];
    var int_integer= int_integer_char.charCodeAt(0);
    console.log("int_integer+char"+int_integer_char);
    console.log(int_integer);	
	var distance_char = maze_Matrix[maze_size-maze_size+1][maze_size];
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
function Guide() {
    checkDistance();
    checkExit();
	
    var distance_char = maze_Matrix[maze_size-maze_size+1][maze_size];
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

    var C_up = Math.abs(cur_up-distance);
    var C_down =Math.abs(cur_down-distance);
    var C_left =Math.abs(cur_left-distance);
    var C_right =Math.abs(cur_right-distance);
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

function confirmFuc(){
    var d = new Date();
    var current_time = d.getTime();
    var current = current_time-start_time;
    var totalTime = current_time- init_time;
   
    var previous_rt =  reaction_time[reaction_time.length-1]
    reaction_time.push(current_time);
    var current_rt = current_time-previous_rt;

    //console.log("Pushed X and Y "+x_cor+","+y_cor)

    Instruction();

    //console.log("current reaction time"+current_rt);
    if (  localProbability ==undefined || globalProbability == undefined){
        alert("Before confirming, you need to move the slider")
    }
    var bluepercentage = blue_True /blue_Total;

    var isDP =0;
       if ( isDecisionPoint ==true){
        isDP =1;
       }

   // console.log("my decision point is "+isDecisionPoint)
   // console.log("isDP:"+isDP)
//     results = results+age+","+year+","+concen+","+gender+","+hand+","+sonaid+","+MazeNum+ ","+localpro+","+globalpro+","+player_step+","+ x_cor+","+y_cor+","+ player_direction+","+totalTime+","+current+","+current_rt+","+localProbability+","+globalProbability+","
//     +correct_direction +","+blue_direction+","+"N"+","+bluepercentage+","+mydistance +","+triple+ "\n";
   if(MazeNum==1){
     

    results = results+age+","+year+","+concen+","+gender+","+hand+","+sonaid+","+"P"+ ","+"1"+","+localpro+","+globalpro+","+player_step+","+ x_cor+","+y_cor+","+ player_direction+","+totalTime+","+current+","+current_rt+","+localProbability+","+globalProbability+","
    +correct_direction +","+blue_direction+","+truefalse+","+bluepercentage+","+mydistance +","+triple+","+isDP+","+local_condition+","+NumDirection+","+ trialNum +","+maze_step+","+localCount+"\n";
   }
   else {
       var mazenum1= MazeNum-1

           results = results+age+","+year+","+concen+","+gender+","+hand+","+ sonaid+","+mazenum1+ ","+"0"+","+localpro+","+globalpro+","+player_step+","+ x_cor+","+y_cor+","+ player_direction+","+totalTime+","+current+","+current_rt+","+localProbability+","+globalProbability+","
    +correct_direction +","+blue_direction+","+truefalse+","+bluepercentage+","+mydistance +","+triple+","+isDP+","+local_condition +","+NumDirection+","+ trialNum +","+maze_step+","+localCount+"\n";
   }


   isDecisionPoint = false;
//    console.log("@@recorded");
//     results = results+age+","+year+","+concen+","+gender+","+hand+","+sonaid+","+MazeNum-1+ ","+localpro+","+globalpro+","+player_step+","+ x_cor+","+y_cor+","+ player_direction+","+totalTime+","+current+","+current_rt+","+localProbability+","+globalProbability+","
//     +correct_direction +","+blue_direction+","+"N"+","+bluepercentage+","+mydistance +","+triple+ "\n";
}
function checkCloser(){

console.log("player_direction: "+player_direction)
console.log("correct_direction: "+correct_direction)

if (player_direction == correct_direction){
    var content  = "You are closer to the exit.";
    document.getElementById("demo").innerHTML =content;
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
    results = results+age+","+year+","+concen+","+gender+","+hand+","+sonaid+","+"P"+ ","+"1"+","+localpro+","+globalpro+","+player_step+","+ x_cor+","+y_cor+","+ player_direction+","+totalTime+","+current+","+current_rt+","+localProbability+","+globalProbability+","
    +correct_direction +","+blue_direction+","+truefalse+","+bluepercentage+","+mydistance+","+triple+","+isDP+ ","+local_condition +","+NumDirection+","+ trialNum +","+maze_step+","+localCount+"\n";
   }
   else {
    var mazenum1= MazeNum-1
           results = results+age+","+year+","+concen+","+gender+","+hand+","+ sonaid+","+mazenum1+ ","+"0"+","+localpro+","+globalpro+","+player_step+","+ x_cor+","+y_cor+","+ player_direction+","+totalTime+","+current+","+current_rt+","+localProbability+","+globalProbability+","
    +correct_direction +","+blue_direction+","+truefalse+","+bluepercentage+","+mydistance +","+triple+","+isDP+ ","+local_condition +","+NumDirection+","+ trialNum +","+maze_step+","+localCount+"\n";
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
            if(globalProbability !=100){
                alert("You found the exit! Please move the global probability slider to 100%.")
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
        checkCloser()
        player_step= player_step +1;
        maze_step =maze_step+1;
        //console.log(player_step);
        x_cor=x_cor+1;
        player_direction ="R";
        var cur_ = maze_Matrix[maze_size+y_cor][x_cor+1];
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
        checkCloser()
        player_step= player_step +1;
        maze_step =maze_step+1;
        //console.log(player_step);
        player_direction ="L";
        x_cor=x_cor-1;

        var cur_ = maze_Matrix[maze_size+y_cor][x_cor+1];
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
        checkCloser()
        player_step= player_step +1;
        maze_step =maze_step+1;
        //console.log(player_step);
        player_direction ="U";

        y_cor=y_cor+1;
        var cur_ = maze_Matrix[maze_size+y_cor][x_cor+1];
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
        checkCloser()
        player_step= player_step +1;
        maze_step =maze_step+1;
       
        player_direction ="D";
        y_cor=y_cor-1;
        var cur_ = maze_Matrix[maze_size+y_cor][x_cor+1];
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
            Glo_output.innerHTML = 100;
            globalProbability =100;
            Glo_slider.value =100;

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
        window.location.href = "https://uwaterloo.sona-systems.com/webstudy_credit.aspx?experiment_id=5128&credit_token=6cf36c8967f94980bfab2d5f6320980d&survey_code="+sonaid;

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
        function foo(){
            alert("Submit button clicked!");
            saveData(sonaid+'_exportResults',results); 
            return true;

        }
               
    </script>

</p>If you have finished the whole experiment, please hit the submit button</p>
<input type="submit" value="Submit" onclick="return foo();">
</form>
</body>
</html>