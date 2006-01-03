<?php

my_mkdir("path/to/your/dir");

function my_mkdir($dir,$dirmode=700)
{
     if (!empty($dir))
     {
         if (!file_exists($dir))
         {
             preg_match_all('/([^\/]*)\/?/i', $dir,$atmp);
             $base="";
             foreach ($atmp[0] as $key=>$val)
             {
                 $base=$base.$val;
                 if(!file_exists($base))
                     if (!mkdir($base,$dirmode))
                     {
                             echo "Error: Cannot create ".$base;
                         return -1;
                     }
              }
       	}else
               if (!is_dir($dir))
               {
                       echo "Error: ".$dir." exists and is not a directory";
                   return -2;
               }
       }
       return 0;
}


?>