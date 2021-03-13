# The Mission

## Briefing

```
Enter the flag you find on The Mission page to open the gates and unlock challenges for The Mission. Please note, your participation in "The Mission" serves as permission for us to share your e-mail address with our sponsors, for potential career opportunities and private invitations to vulnerability disclosure and bug bounty programs.

After solving this challenge, you may need to refresh the page to see the newly unlocked challenges.
```

## Getting the flag

Navigate to the [Mission page](https://ctf.nahamcon.com/mission) (might be a dead link now). And, `Ctrl+U` && `Ctrl + F` and search for `flag`
```
<!-- Thank you for reading the rules! Your flag is: -->
<!--   flag{48e117a1464c3202714dc9a350533a59}       -->
```
OR! Use (webctf)[https://github.com/xnomas/web-ctf-help] like so:
```
webctf https://ctf.nahamcon.com/mission 

=============
COMMENTS
=============

[+] 1 :   Google Font: Source Sans Pro
[+] 2 :   Content Wrapper. Contains page content
[+] 3 :   Thank you for reading the rules! Your flag is:
[+] 4 :     flag{48e117a1464c3202714dc9a350533a59}
[+] 5 :   /.content-wrapper
[+] 6 :   Main Footer
[+] 7 :   To the right
[+] 8 :   Default to the left
[+] 9 :   ./wrapper
[+] 10 :   REQUIRED SCRIPTS

=============
SCRIPTS
=============

[+] 1 : /themes/ctf4hire/static/js/vendor.bundle.min.js?d=f99cb2bc
[+] 2 : /themes/ctf4hire/static/js/core.min.js?d=f99cb2bc
[+] 3 : /themes/ctf4hire/static/js/helpers.min.js?d=f99cb2bc
[+] 4 : /themes/ctf4hire/static/js/pages/main.min.js?d=f99cb2bc

=============
IMAGES
=============

sources:
--------
[+] 1 : /files/1a8a5ff60fea50451ab09535ea36bb24/flag_white.png

alts:
-----
[+] 1 : NahamCon CTF

===================
INTERESTING HEADERS
===================

Server : gunicorn/20.0.4
Set-Cookie : session=dd9b86c5-bd1a-40fb-a81d-58de3b5171e8.a_NA4UpcnABLbZz92Gon1fLAzbk; HttpOnly; Path=/; SameSite=Lax
```
Flag found! Move on.