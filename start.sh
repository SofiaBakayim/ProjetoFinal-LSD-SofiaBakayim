#!/bin/bash
php -S localhost:8000 &
sleep 1
open http://localhost:8000/intro.html
wait 