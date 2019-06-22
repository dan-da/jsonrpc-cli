<?php

namespace App\Utils;

use texttable;
use texttable_markdown;

/* A class that generates reports in various formats.
 */
class Report
{

    /* prints out single report in one of several possible formats,
     * or multiple reports, one for each possible format.
     */
    static public function printResults($params, $results, $fixedCols=false)
    {
        $format = $params['format'];
        $outfile = @$params['outfile'];

        $summary = [];  // placeholder
        
        if( $outfile && $format == 'all' ) {
            $formats = array( 'json', 'jsonpretty', 'printr', 'vardump', 'serialize', 'raw' );

            foreach( $formats as $format ) {

                $outfile = sprintf( '%s/%s.%s',
                    pathinfo($outfile, PATHINFO_DIRNAME),
                    pathinfo($outfile, PATHINFO_FILENAME),
                    $format );

                self::print_results_worker( $summary, $results, $outfile, $format );
            }
        }
        else {
            self::print_results_worker( $summary, $results, $outfile, $format );
        }
    }

    /* prints out single report in specified format, either to stdout or file.
     */
    static public function print_results_worker( $summary, $results, $outfile, $format ) {

        $fname = $outfile ?: 'php://stdout';
        $fh = fopen( $fname, 'w' );

        switch( $format ) {
            case 'json':       self::write_results_json( $fh, $results );        break;
            case 'jsonpretty': self::write_results_jsonpretty( $fh, $results );  break;
            case 'printr':     self::write_results_print_r( $fh, $results );  break;
            case 'vardump':    self::write_results_var_dump( $fh, $results );  break;
            case 'serialize':  self::write_results_serialize( $fh, $results );  break;
            case 'raw':        self::write_results_raw( $fh, $results); break;
        }

        fclose( $fh );

//        if( $outfile ) {
//            mylogger()->log( "Report was written to $fname", mylogger::specialinfo );
//        }
    }

    /* writes out results in json (raw) format
     */
    static public function write_results_json( $fh, $results ) {
        fwrite( $fh, json_encode( $results ) . "\n" );
    }

    /* writes out results in jsonpretty format
     */
    static public function write_results_jsonpretty( $fh, $results ) {
        fwrite( $fh, json_encode( $results,  JSON_PRETTY_PRINT ) . "\n" );
    }


    /* writes out results as php print_r format
     */
    static protected function write_results_print_r( $fh, $results ) {

        $buf = print_r($results, true );
        fwrite( $fh, $buf . "\n" );
    }
    

    /* writes out results as php var_dump format
     */
    static protected function write_results_var_dump( $fh, $results ) {

        $buf = var_export(results, true );
        fwrite( $fh, $buf . "\n" );
    }

    /* writes out results as php var_dump format
     */
    static protected function write_results_serialize( $fh, $results ) {
        $buf = serialize($results);
        fwrite( $fh, $buf . "\n" );
    }


    /* writes out results as php var_dump format
     */
    static protected function write_results_raw( $fh, $results ) {
        fwrite( $fh, $results . "\n" );
    }
    
    
}
