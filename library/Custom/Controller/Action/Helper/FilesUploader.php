<?php
class Custom_Controller_Action_Helper_FilesUploader extends Zend_Controller_Action_Helper_Abstract
{    
    private $aHeaders = array(
                            'application/zip',
                            'application/x-zip',
                            'application/octet-stream',
                            'application/x-zip-compressed'
                        );

    public function upload($sDestPath, $aFiles)
    {
        $aErrorMessages = array();
        
        //Сheck that we have a file
        if((!empty($aFiles)) && ($aFiles['error'] == 0))
        {
            $filename = basename($aFiles['name']);

            $ext = substr($filename, strrpos($filename, '.') + 1);

            if (($ext == "zip") && (in_array($aFiles["type"] , $this->aHeaders)))
            {
                if(($aFiles["size"] < 100000000))
                {
                    //Determine the path to which we want to save this file
                    $newname = $sDestPath;
                    //Check if the file with the same name is already exists on the server
                    if (!file_exists($newname))
                    {
                        //Attempt to move the uploaded file to it's new place
                        if (!(move_uploaded_file($aFiles['tmp_name'],$newname)))
                        {
                           $aErrorMessages[] = "A problem occurred during file upload!";
                        }
                    }
                    else
                    {
                        $aErrorMessages[] = "File ".$aFiles["name"]." already exists";
                    }
                }
                else
                {
                    $aErrorMessages[] = "Only .zip files under 10Mb are accepted for upload";
                }
            }
            else
            {
                $aErrorMessages[] = "The file uploaded should be of .zip file type.";
            }

        } 
        else
        {
            if($aFiles['error'] == 2 || $aFiles['error'] == 1 )
            {
                $aErrorMessages[] = "The uploaded file exceeded the allowable file size of 100MB.";
            }
            else if($aFiles['error'] == 3)
            {
                $aErrorMessages[] = "The file was only partially uploaded. Please upload again.";
            }
            else if($aFiles['error'] == 4)
            {
                $aErrorMessages[] = "No files uploaded.";
            }
            else if($aFiles['error'] == 7)
            {
                $aErrorMessages[] = "Failed to write the uploaded file in the server.";
            }
        }
        
        return $aErrorMessages;
    }



}