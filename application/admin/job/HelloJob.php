<?php
namespace app\admin\job;

class Hellojob{
    /**
     * 该方法用于接收任务执行失败的通知，你可以发送邮件给相应的负责人员
     * @param $jobData  string|array|...      //发布任务时传递的 jobData 数据
     */
    public function failed($jobData){
        send_mail_to_somebody() ;
        print("Warning: Job failed after max retries. job data is :".var_export($jobData,true))."\n";
    }
}

