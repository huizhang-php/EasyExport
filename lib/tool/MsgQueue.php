<?php
/**
 * User: yuzhao
 * CreateTime: 2019/2/26 下午5:18
 * Description: 多进程通讯-消息队列
 * 重新封装一遍是为了使用简单些，并且统一类名称管理代码阅读更直观
 */

class MsgQueue {

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午5:19
     * Description: 创建消息队列
     * @return resource
     */
    public static function createQueue() {
        // 使用ftok创建一个key名称
        $key = ftok( __DIR__, 'a' );
        // 创建消息队列
        $queue = msg_get_queue( $key, 0666 );
        msg_remove_queue($queue);
        $queue = msg_get_queue( $key, 0666 );
        return $queue;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午5:29
     * @param $queue
     * @param $msg
     * Description: 添加消息
     * @return bool
     */
    public static function add($queue, $msg) {
        if (!msg_send ($queue, 1, $msg, true, true, $msg_err)){
            return false;
        }
        return true;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午5:32
     * @param $queue
     * Description: 获取消息
     */
    public static function get($queue, $size=1024) {
        msg_receive( $queue, 0, $msgtype, $size, $message );
        return $message;
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午5:33
     * @param $queue
     * Description: 删除清除消息队列
     */
    public static function delQueue($queue) {
        msg_remove_queue( $queue );
    }

    /**
     * User: yuzhao
     * CreateTime: 2019/2/26 下午5:37
     * @param $queue
     * @param null $type
     * Array structure for msg_stat_queue
        msg_perm.uid	The uid of the owner of the queue.
        msg_perm.gid	The gid of the owner of the queue.
        msg_perm.mode	The file access mode of the queue.
        msg_stime	The time that the last message was sent to the queue.
        msg_rtime	The time that the last message was received from the queue.
        msg_ctime	The time that the queue was last changed.
        msg_qnum	The number of messages waiting to be read from the queue.
        msg_qbytes	The maximum number of bytes allowed in one message queue. On Linux, this value may be read and modified via /proc/sys/kernel/msgmnb.
        msg_lspid	The pid of the process that sent the last message to the queue.
        msg_lrpid	The pid of the process that received the last message from the queue.
     * @return array
     * Description: 获取消息队列信息
     */
    public static function getQueueInfo($queue, $type=null) {
        $queueInfo = msg_stat_queue($queue);
        if (!$type) {
            return $queueInfo;
        }
        return $queueInfo[$type];
    }
}