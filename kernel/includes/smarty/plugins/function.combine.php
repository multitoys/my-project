<?php
    /**
     * Smarty plugin
     * @package Smarty
     * @subpackage plugins
     */
    
    function smarty_function_combine($params, &$smarty)
    {
//	require dirname(__FILE__) . '/minify/JSmin.php';
//	require dirname(__FILE__) . '/minify/CSSmin.php';
        $start = microtime(true);
        if (isset($params['input'])) {
            if (is_array($params['input']) && count($params['input']) > 0) {
                $ext = pathinfo($params['input'][0], PATHINFO_EXTENSION);
                if (in_array($ext, array('js', 'css'))) {
                    $params['type'] = $ext;
                    if (!isset($params['output'])) $params['output'] = dirname($params['input'][0]).'/combined.'.$ext;
                    if (!isset($params['age'])) $params['age'] = 3600;
                    if (!isset($params['cache_file_name'])) $params['cache_file_name'] = $params['output'].'.cache';
                    if (!isset($params['debug'])) $params['debug'] = false;
                    $cache_file_name = $params['cache_file_name'];
                    
                    if ($params['debug'] == true) {
                        build_combine($params);
                        debugging($start);
                        return;
                    }
                    
                    if (file_exists($_SERVER['DOCUMENT_ROOT'].$cache_file_name)) {
                        $cache_mtime = filemtime($_SERVER['DOCUMENT_ROOT'].$cache_file_name);
                        if ($cache_mtime + $params['age'] < time()) {
                            build_combine($params);
                        } else {
                            print_out($params);
                        }
                    } else {
                        build_combine($params);
                    }
                } else {
                    trigger_error("input file must have js or css extension", E_USER_NOTICE);
                    debugging($start);
                    return;
                }
            } else {
                trigger_error("input must be array and have one item at least", E_USER_NOTICE);
                debugging($start);
                return;
            }
        } else {
            trigger_error("input cannot be empty", E_USER_NOTICE);
            debugging($start);
            return;
        }
    }
    
    /**
     * Print filename
     *
     * @param string $params
     */
    function print_out($params)
    {
        $last_mtime = 0;
        
        if (file_exists($_SERVER['DOCUMENT_ROOT'].$params['cache_file_name'])) {
            $last_mtime = file_get_contents($_SERVER['DOCUMENT_ROOT'].$params['cache_file_name']);
        }
        
        $output_filename = preg_replace("/\.(js|css)$/i", date("_YmdHis.", $last_mtime)."$1", $params['output']);
        
        if ($params['type'] == 'js') {
            echo '<script '.$params['defer'].' src="'.$output_filename.'"></script>';
        } elseif ($params['type'] == 'css') {
            echo '<link type="text/css" rel="stylesheet" href="'.$output_filename.'" />';
        } else {
            echo $output_filename;
        }
    }
    
    /**
     * Build combined file
     *
     * @param array $params
     */
    function build_combine($params)
    {
        $filelist = array();
        $lastest_mtime = 0;
        
        foreach ($params['input'] as $item) {
            if (file_exists($_SERVER['DOCUMENT_ROOT'].$item)) {
                $mtime = filemtime($_SERVER['DOCUMENT_ROOT'].$item);
                $lastest_mtime = max($lastest_mtime, $mtime);
                $filelist[] = array('name' => $item, 'time' => $mtime);
            } else {
                trigger_error('File '.$_SERVER['DOCUMENT_ROOT'].$item.' does not exists!', E_USER_WARNING);
            }
        }
        
        if ($params['debug'] == true) {
            $output_filename = '';
            foreach ($filelist as $file) {
                if ($params['type'] == 'js') {
                    $output_filename .= '<script '.$params['defer'].' src="'.$file['name'].'"></script>';
                } elseif ($params['type'] == 'css') {
                    $output_filename .= '<link type="text/css" rel="stylesheet" href="'.$file['name'].' /">';
                }
            }
            
            echo $output_filename;
            
            return;
        }
        
        $last_cmtime = 0;
        
        if (file_exists($_SERVER['DOCUMENT_ROOT'].$params['cache_file_name'])) {
            $last_cmtime = file_get_contents($_SERVER['DOCUMENT_ROOT'].$params['cache_file_name']);
        }
        
        if ($lastest_mtime > $last_cmtime) {
            $glob_mask = preg_replace("/\.(js|css)$/i", "_*.$1", $params['output']);
            $files_to_cleanup = glob($_SERVER['DOCUMENT_ROOT'].$glob_mask);
            
            foreach ($files_to_cleanup as $cfile) {
                if (is_file($_SERVER['DOCUMENT_ROOT'].$cfile) && file_exists($_SERVER['DOCUMENT_ROOT'].$cfile)) {
                    unlink($_SERVER['DOCUMENT_ROOT'].$cfile);
                }
            }
            
            $output_filename = preg_replace("/\.(js|css)$/i", date("_YmdHis.", $lastest_mtime)."$1", $params['output']);
            $fh = fopen($_SERVER['DOCUMENT_ROOT'].$output_filename, "a+");
            
            if (flock($fh, LOCK_EX)) {
                foreach ($filelist as $file) {
                    $min = '';
                    
                    if ($params['type'] == 'js') {
                        $min = JSMin::minify(file_get_contents($_SERVER['DOCUMENT_ROOT'].$file['name']));
                    } elseif ($params['type'] == 'css') {
                        $input_css = file_get_contents($_SERVER['DOCUMENT_ROOT'].$file['name']);
                        $compressor = new CSSmin();
                        $min = $compressor->run($input_css);
                    } else {
                        fputs($fh, PHP_EOL.PHP_EOL."/* ".$file['name']." @ ".date("c", $file['time'])." */".PHP_EOL.PHP_EOL);
                        $min = file_get_contents($_SERVER['DOCUMENT_ROOT'].$file['name']);
                    }
                    
                    fputs($fh, $min);
                }
                
                flock($fh, LOCK_UN);
                file_put_contents($_SERVER['DOCUMENT_ROOT'].$params['cache_file_name'], $lastest_mtime, LOCK_EX);
            }
            
            fclose($fh);
            clearstatcache();
        }
        
        touch($_SERVER['DOCUMENT_ROOT'].$params['cache_file_name']);
        print_out($params);
    }
    
    function debugging($start)
    {
        $memoscript_peak = round(memory_get_peak_usage() / 1048576, 2, PHP_ROUND_HALF_UP);
        $time = round((microtime(true) - $start)*1000, 2, PHP_ROUND_HALF_UP);
        $echo = '<span style="
                        top: 5px;
                        left: 5px;
                        background: #008DD5;
                        color: #fff;
                        font-family: monospace;
                        font-size: 12px;
                        opacity: 0.8;
                        padding: 5px 10px;
                        border-radius: 4px;
                        position: fixed;
                        z-index: 2147483647;
                    ">Time: '.$time.' msec<br>Memory: '.$memoscript_peak.' MB</span>';
        
        echo $echo;
    }