<?php

namespace App\Ryder;

use App\Custom\Misc;
use Illuminate\Database\Eloquent\Model as Eloquent;

class TaskLog extends Eloquent {

    protected $table = 'taskLog';
    public $timestamps = false;

    public $fillable = array(
        'name',
        'start',
        'end',
        'sapi',
        'username',
        'exception',
        'exceptionInfo',
        'runTimeSeconds',
        'maximumRunTimeExpectationSeconds',
        'runTimeFlag',
        'runTimeFlagNotificationSent'
    );

    protected $guarded = array(
        'id'
    );

    public function start($name, $maximumRunTimeExpectationSeconds)
    {
        $this->name = (string) $name;
        $this->start = date('Y-m-d H:i:s');
        $this->maximumRunTimeExpectationSeconds = (int) $maximumRunTimeExpectationSeconds;
        $this->sapi = PHP_SAPI;
        $this->save();
    }

    public function end()
    {
        $this->end = date('Y-m-d H:i:s');
        $this->generateRunTimeSeconds();
        $this->checkRunTime();
        $this->save();
    }

    public function exception(\Exception $e)
    {
        $this->exception = 1;
        $this->exceptionInfo = $e->getCode().PHP_EOL.$e->getMessage();
        $this->generateRunTimeSeconds();
        $this->checkRunTime();
        $this->save();
    }

    public function generateRunTimeSeconds()
    {
        if (!$this->end) {
            return FALSE;
        }

        $this->runTimeSeconds = strtotime($this->end) - strtotime($this->start);

        return TRUE;
    }

    public function checkRunTime()
    {
        if (!$this->runTimeSeconds || !$this->maximumRunTimeExpectationSeconds) {
            return false;
        }
        if ($this->runTimeSeconds > $this->maximumRunTimeExpectationSeconds) {
            $this->runTimeFlag = 1;
            $this->sendRunTimeFlagNotification();
        }
        return true;
    }

    protected function sendRunTimeFlagNotification()
    {
        // If notification already sent, don't send again
        if ($this->runTimeFlagNotificationSent) {
            return;
        }

        $this->runTimeFlagNotificationSent = 1;

        $appName = Misc::determineAppName();
        $serverName = gethostname();

        mail(
            'UKDevTeam@ryder.com',
            $appName.' '.$serverName.' Task Run Time Flag Notification',
            $this->name.'  took '.$this->runTimeSeconds.' seconds to run. '.PHP_EOL.
            'The maximum expected run time is '.$this->maximumRunTimeExpectationSeconds.'.',
            'FROM:UKDevTeam@ryder.com'
        );

        $this->save();

    }

}
