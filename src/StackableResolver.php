<?php

namespace yswery\DNS;

class StackableResolver
{

    /**
     * @var array
     */
    protected $resolvers;

    public function __construct(array $resolvers = array())
    {
        $this->resolvers = $resolvers;
    }

    public function get_answer($question, $wildcard = false)
    {
        foreach ($this->resolvers as $resolver) {
            $answer = $resolver->get_answer($question, $wildcard);
            if ($answer) {
                return $answer;
            }
        }

        return array();
    }

    /**
     * Check if any of the resoolvers supports recursion
     *
     * @return boolean true if any resolver supports recursion
     */
    public function allows_recursion() {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->allows_recursion()) {
              return true;
            }
        }
    }

    /*
     * Check if any resolver knows about a domain
     *
     * @param  string  $domain the domain to check for
     * @return boolean         true if some resolver holds info about $domain
     */
    public function is_authority($domain) {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->is_authority($domain)) {
                return true;
            }
        }
        return false;
    }
}
