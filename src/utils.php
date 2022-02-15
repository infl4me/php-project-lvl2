<?php

namespace utils;

function unreachable()
{
    print_r("TRIED TO ACCESS UNREACHABLE CODE\n");
    exit(-1);
}
