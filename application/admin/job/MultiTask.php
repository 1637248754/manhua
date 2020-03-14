<?php

namespace app\admin\job;

use think\queue\Job;

class MultiTask
{
    public function taskA(Job $job, $data)
    {
        $isJobDone = $this->_doTaskA($data);
        if ($isJobDone) {
            $job->delete();
            print("Info: TaskA of Job MultiTask has been done and deleted" . "\n");
        } else {
            if ($job->attempts() > 3) {
                $job->delete();
            }
        }
    }

    public function taskB(Job $job, $data)
    {
        $isJobDone = $this->_doTaskB($data);
        if ($isJobDone) {
            $job->delete();
            print("Info: TaskB of Job MultiTask has been done and deleted" . "\n");
        } else {
            if ($job->attempts() > 2) {
                $job->release();
            }
        }
    }

    private function _doTaskA($data)
    {
        Db('admin_count_user')->insert($data);
        print("Info: doing TaskA of Job MultiTask " . "\n");
        return true;
    }

    private function _doTaskB($data)
    {
        print("Info: doing TaskB of Job MultiTask " . "\n");
        return true;
    }

    public function addp(Job $job,$data){
        $insert = $this->add($data);
        if($insert){
            $job->delete();
            return true;
        }else{
            $job->delete();
        }
    }

    public function add($data){
        Db('admin_count_user')->insert($data);
        return true;
    }


}
