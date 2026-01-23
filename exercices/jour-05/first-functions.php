<?php

function greet()
{
    echo 'Welcome to the shop';
};

function greetClient(string $name)
{
    echo 'Hello'.$name;
};

for ($i = 0; $i < 5; $i++) {
    greet();
    greetClient('Cloud');
}
