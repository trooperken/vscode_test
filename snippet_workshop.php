
https://marketplace.visualstudio.com/items?itemName=aspirantzhang.php-enhanced-snippets
/////////////////////////////////////////////////

Snippet	Output	Language
r / re	return	php
th	$this->	php
se	self::	php
thp	$this->property = $property;	php
pr	print_r()	php
vd	var_dump()	php
dirname-dir	dirname(__DIR__)	php


Snippet	Output	Language
while-block	while ($condition) {}	php
do-while-block	do {} while ($condition);	php
for-i	for ($i = 0; $i < $condition; $i++) {}	php
for-j	for ($j = 0; $j < $condition; $j++) {}	php
foreach-block	foreach ($array as $value) {}	php
foreach-key-value	foreach ($array as $key => $value) {}	php

Snippet	Output	Language
if-block	if ($condition) {}	php
if-else-block	if ($condition) {} else {}	php
if-return-block	if ($condition) { return $foo; } return $bar;	php
else-block	else {}	php
elseif-block	elseif ($condition) {}	php
switch-break	switch(){ case: ... break; }	php
switch-return	switch(){ case: return ...; }	php
if-three / ternary-operator	(condition)?true:false;	php

Snippet	Output	Language
function	function name($param) {}	php
function-return	function name($param): string {}	php
anonymous-function	function ($param) {}	php
anonymous-function-return	function ($param): string {}	php
anonymous-function-use	function ($param) use ($var) {}	php
anonymous-function-use-return	function ($param) use ($var): string {}	php
arrow-function	fn($foo) => $bar;	php
arrow-function-return	fn($foo): string => $bar;	php
arrow-function-nested	fn($foo) => fn($bar) => $baz;	php

Snippet	Output	Language
thr	throw new \Exception()	php
try-catch-block	try{} catch (){}	php
try-catch-message	try{} catch (Exception $e){ $e->getMessage() }	php
try-catch-finally	try{} catch (){} finally {}	php
catch-block	catch (){}	php
finally-block	finally {}	php

