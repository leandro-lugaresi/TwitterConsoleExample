#!/bin/bash
composer self-update

/usr/bin/supervisord -n
