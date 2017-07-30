<?php

function guxy_check_auth($code)
{
    if (\Auth::guest()) {
        throw new Illuminate\Auth\AuthenticationException();
    } else {
        abort($code);
    }
}
