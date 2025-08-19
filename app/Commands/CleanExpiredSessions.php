<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\model_login_details;

class CleanExpiredSessions extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'session:clean-db';
    protected $description = 'Deletes expired file-based sessions and related DB records.';

    public function run(array $params)
    {
        helper("db_helper");
        $sessionDir   = WRITEPATH . 'session';
        $expiration   = config('App')->sessionExpiration; // in seconds
        $now          = time();
        $deletedCount = 0;

        $model = new model_login_details(db_connect('db_system'));

        $files = glob($sessionDir . '/ci_session*');
        $i=0;
        while($i<1){
            echo"\n$i";$i++;
        }
         echo"\n expiration==>$expiration";

        foreach ($files as $filePath) {
            $lastModified = filemtime($filePath);
            echo"\n filePath==>$filePath";

            if ($lastModified !== false && ($now - $lastModified) > $expiration) {
                $fileName = basename($filePath); // e.g., ci_sessionabcd123
                echo"\n file==>$fileName";
                // Delete from DB
                archive_login_session($fileName);
                // $model->where('session_id', $fileName)->delete();

                // Delete session file (optional)
                // @unlink($filePath);

                $deletedCount++;
            }
        }

        $logingDtl = $model->get()->getResultArray();
        foreach ($logingDtl as $login) {
            $sessionFilePath = $sessionDir . "/" . $login["session_id"];
    
            // Check if the session file exists
            if (!file_exists($sessionFilePath)) {
                // If file doesn't exist, delete the record from the database
                archive_login_session($login["session_id"]);
                // $model->where('session_id', $login["session_id"])->delete();
                echo "Deleted record for session_id: " . $login["session_id"] . "\n";
            }

        }

        CLI::write("✅ Expired sessions cleaned: $deletedCount", 'green');
    }
}
