<?php

namespace tester;

require_once __DIR__  . '/tests_common.php';

class usage extends tests_common {
    
    public function runtests() {
        $this->test_usage();
    }

    protected function test_usage() {
        // setup cli params 
        $params = [
            'help' => null,
        ];

        // path not set.
        $output = $this->exec( $this->gen_args($params), 2, 'usage' );
        $needle = 'This script makes a request to a jsonrpc server.';
        $this->contains($output, $needle, 'usage');
        
    }
        
}
