<?php

function gravatar_url($email)
{
    return 'https://www.gravatar.com/avatar/' . md5($email) . http_build_query([
            's' => '60'
        ]);
}
