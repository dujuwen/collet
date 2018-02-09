https://www.cnblogs.com/nnn123/p/6723729.html

状态模式和策略模式的比较:
两个模式的实现类图虽然一致，但是实现目的不一样！
首先知道，策略模式是一个接口的应用案例，一个很重要的设计模式，简单易用，策略模式一般用于单个算法的替换，
客户端事先必须知道所有的可替换策略，由客户端去指定环境类需要哪个策略，注意通常都只有一个最恰当的策略（算法）被选择。
其他策略是同级的，可互相动态的在运行中替换原有策略。
而状态模式的每个状态子类中需要包含环境类（Context）中的所有方法的具体实现——条件语句。通过把行为和行为对应的逻辑包装到状态类里，
在环境类里消除大量的逻辑判断，而不同状态的切换由继承（实现）State 的状态子类去实现，当发现修改的当前对象的状态不是自己这个状态所对应的参数，
则各个状态子类自己给 Context 类切换状态（有职责链模式思想）！且客户端不直接和状态类交互，客户端不需要了解状态！
（和策略不一样），策略模式是直接依赖注入到 Context 类的参数进行选择策略，不存在切换状态的操作，客户端需要了解策略！
联系：状态模式和策略模式都是为具有多种可能情形设计的模式，把不同的处理情形抽象为一个相同的接口（抽象类），符合对开闭原则，
且策略模式更具有一般性，在实践中，可以用策略模式来封装几乎任何类型的规则，只要在分析过程中听到需要在不同实践应用不同的业务规则，
就可以考虑使用策略模式处理，在这点上策略模式是包含状态模式的功能的。

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
