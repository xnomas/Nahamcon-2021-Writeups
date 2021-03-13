 <?php
 $to_echo = $_REQUEST['echo'];
 $cmd = "bash -c 'echo " . $to_echo . "'";
 if (isset($to_echo)) {
     if ($to_echo == "") {
         print "Please don't be lame, I can't just say nothing.";
     } elseif (
         preg_match('/[#!@%^&*()$_+=\-\[\]\';,{}|":>?~\\\\]/', $to_echo)
     ) {
         print "Hey mate, you seem to be using some characters that makes me wanna throw it back in your face >:(";
     } elseif ($to_echo == "cat") {
         print "Meowwww... Well you asked for a cat didn't you? That's the best impression you're gonna get :/";
     } elseif (strlen($to_echo) > 15) {
         print "Man that's a mouthful to echo, what even?";
     } else {
         system($cmd);
     }
 } else {
     print "Alright, what would you have me say?";
 }
  ?>
