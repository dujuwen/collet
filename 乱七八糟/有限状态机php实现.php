https://www.cnblogs.com/nnn123/p/6723729.html

//定义电梯的状态
class LiftState {
    const OPEN = 1;
    const CLOSE = 2;
    const RUN = 3;
    const STOP = 4;
    const WEIHU = 5;
    const WEIHUJIESHU = 6;
}

class LiftEvent {
    const EVENT1 = 1;
    const EVENT2 = 2;
    const EVENT3 = 3;
    const EVENT4 = 4;
}

class Lift {
    public static function open()
    {
        echo __METHOD__ . PHP_EOL;
    }

    public static function close()
    {
        echo __METHOD__ . PHP_EOL;
    }

    public static function run()
    {
        echo __METHOD__ . PHP_EOL;
    }

    public static function stop()
    {
        echo __METHOD__ . PHP_EOL;
    }

    //维护
    public static function weihu()
    {
        echo __METHOD__ . PHP_EOL;
    }

    //维护结束
    public static function weihuJieshu()
    {
        echo __METHOD__ . PHP_EOL;
    }
}

//新添加只需添加状态、执行函数、映射
$stateEventArr = [
    //打开-关闭-运行-暂停-维护-维护结束-关闭
    //[到来的事件，当前的状态，将要要执行的函数，下一个状态]
    [LiftEvent::EVENT1, LiftState::CLOSE, 'open', LiftState::OPEN],
    [LiftEvent::EVENT2, LiftState::OPEN, 'close', LiftState::CLOSE],
    [LiftEvent::EVENT3, LiftState::CLOSE, 'run', LiftState::RUN],
    [LiftEvent::EVENT4, LiftState::RUN, 'stop', LiftState::STOP],
    [LiftEvent::EVENT1, LiftState::STOP, 'weihu', LiftState::WEIHU],
    [LiftEvent::EVENT2, LiftState::WEIHU, 'weihuJieshu', LiftState::WEIHUJIESHU],
    [LiftEvent::EVENT3, LiftState::WEIHUJIESHU, 'close', LiftState::CLOSE]
];

class FSM {
    public $event;
    public $currentState;
    public $action;
    public $nextState;
}

function fsmHandle (FSM &$fsm, &$stateEventArr)
{
    $nextStateEvent = [];
    foreach ($stateEventArr as $index => $v) {
        if ($v[0] == $fsm->event && $fsm->currentState == $v[1]) {
            $index += 1;
            if ($index > (count($stateEventArr)) -1) {
                $index = 0;
            }
            $nextStateEvent = $stateEventArr[$index];
            break;
        }
    }

    if (count($nextStateEvent) > 0) {
        $action = $fsm->action;
        Lift::$action();
        $fsm->event = $nextStateEvent[0];
        $fsm->currentState = $nextStateEvent[1];
        $fsm->action = $nextStateEvent[2];
        $fsm->nextState = $nextStateEvent[3];
    }
}

//初始化
$fsm = new FSM();
$fsm->event = LiftEvent::EVENT1;
$fsm->currentState = LiftState::CLOSE;
$fsm->action = 'open';
$fsm->nextState = LiftState::OPEN;

while (true) {
    //电梯一直运行下去吧
    fsmHandle($fsm, $stateEventArr);
    sleep(1);
}
