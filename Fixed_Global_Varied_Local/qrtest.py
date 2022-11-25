from MyQR import myqr

import os
version, level, qr_name = myqr.run(

    words= "https://artsresearch.uwaterloo.ca/~brittlab/protocols/Maze/Maze_consent.php",
    version=1,
    level='H',
    picture=None,
    colorized=False,
    contrast=1.0,
    brightness=1.0,
    save_name="testQR.png",
    save_dir=os.getcwd()

)
