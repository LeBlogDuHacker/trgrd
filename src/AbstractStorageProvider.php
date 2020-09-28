<?php

namespace yswery\DNS;

abstract class AbstractStorageProvider
{
    abstract public function get_answer($question, $wildcarded = false);

    abstract public function allows_recursion();

    abstract public function is_authority($domain);
}
