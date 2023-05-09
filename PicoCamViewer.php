<?php

class PicoCamViewer extends AbstractPicoPlugin
{
    const API_VERSION = 3;

    protected $enabled = true;//povoleni načtení pluginu
    protected $dependsOn = array();//zavislosti


    private $p_keyword = 'camViewer';
    private $p_load_dir;
    private $p_load_url;
    



    public function onPluginsLoaded(array $plugins){
        $this->p_load_dir=$this->getRootDir()."/assets/cam/";
        $this->p_load_url='%assets_url%/cam/';
        if(!is_dir($this->p_load_dir)){
            if (!mkdir($this->p_load_dir, 0777, true)) {
                die('PicoCamVierwer Failed to create directories...'.$this->p_load_dir);
            }
        }
    }

    public function onContentLoaded(&$rawContent) {
        $rawContent = preg_replace_callback( '/\(%\s+' . $this->p_keyword  .'\s*\(\s*(.*?)\s*\)\s+%\)/', function($match) {
            if ($match[1]) {
                $urls=explode(' ',$match[1]);
                
                $output="";
                $i=0;
                foreach ($urls as $url) {
                    $img = 'cam'.$i.'.jpg'; 
                    if(!file_put_contents($this->p_load_dir.$img, file_get_contents($url))){
                        echo "ERROR download  ".$url."  to ".$this->p_tmpCamDir;
                    }else{
                        $output.=$this->generateOutput($this->p_load_url.$img);
                    }
                    $i++;
                } 

                return $output;

            }
        }, $rawContent);
    }




    private function generateOutput($file){
        $out='<a href="'.$file.'"><img src="'.$file.'" alt="'.$file.'" width="500"> </a>';
        return $out;
    }
}
