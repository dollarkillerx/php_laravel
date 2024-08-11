<?php

class BankSystem {
    private $accounts = [];

    // 读取命令行输入
    private function readInput($prompt) {
        echo $prompt;
        return trim(fgets(STDIN));
    }

    // 注册功能
    public function register() {
        $username = $this->readInput("请输入用户名: ");
        if (isset($this->accounts[$username])) {
            echo "用户已存在。\n";
            return;
        }
        $password = $this->readInput("请输入密码: ");
        $this->accounts[$username] = ['balance' => 0, 'password' => $password];
        echo "注册成功。\n";
    }

    // 登录功能
    public function login() {
        $username = $this->readInput("请输入用户名: ");
        if (!isset($this->accounts[$username])) {
            echo "用户不存在。\n";
            return false;
        }
        $password = $this->readInput("请输入密码: ");
        if ($this->accounts[$username]['password'] !== $password) {
            echo "密码错误。\n";
            return false;
        }
        echo "登录成功。\n";
        return $username;
    }

    // 查询余额功能
    public function checkBalance($username) {
        if (isset($this->accounts[$username])) {
            echo "账户余额: " . $this->accounts[$username]['balance'] . "\n";
        } else {
            echo "账户不存在。\n";
        }
    }

    // 转账功能
    public function transfer($username) {
        $recipient = $this->readInput("请输入收款人用户名: ");
        if (!isset($this->accounts[$recipient])) {
            echo "收款人账户不存在。\n";
            return;
        }
        $amount = (float) $this->readInput("请输入转账金额: ");
        if ($amount <= 0) {
            echo "转账金额必须大于0。\n";
            return;
        }
        if ($this->accounts[$username]['balance'] < $amount) {
            echo "余额不足。\n";
            return;
        }
        $this->accounts[$username]['balance'] -= $amount;
        $this->accounts[$recipient]['balance'] += $amount;
        echo "转账成功。\n";
    }

    // 存款功能
    public function deposit($username) {
        $amount = (float) $this->readInput("请输入存款金额: ");
        if ($amount <= 0) {
            echo "存款金额必须大于0。\n";
            return;
        }
        $this->accounts[$username]['balance'] += $amount;
        echo "存款成功。\n";
    }

    // 查询所有用户功能
    public function listUsers() {
        if (empty($this->accounts)) {
            echo "没有用户。\n";
            return;
        }
        foreach ($this->accounts as $username => $info) {
            echo "用户名: $username, 余额: " . $info['balance'] . "\n";
        }
    }

    // 主程序
    public function run() {
        while (true) {
            $action = $this->readInput("请选择操作: 1. 注册 2. 登录 3. 查询所有用户 4. 退出: ");
            switch ($action) {
                case '1':
                    $this->register();
                    break;
                case '2':
                    $username = $this->login();
                    if ($username) {
                        while (true) {
                            $operation = $this->readInput("选择操作: 1. 查询余额 2. 存款 3. 转账 4. 退出: ");
                            switch ($operation) {
                                case '1':
                                    $this->checkBalance($username);
                                    break;
                                case '2':
                                    $this->deposit($username);
                                    break;
                                case '3':
                                    $this->transfer($username);
                                    break;
                                case '4':
                                    echo "退出登录。\n";
                                    break 2; // 退出登录和主程序循环
                                default:
                                    echo "无效的选项。\n";
                            }
                        }
                    }
                    break;
                case '3':
                    $this->listUsers();
                    break;
                case '4':
                    echo "退出系统。\n";
                    exit;
                default:
                    echo "无效的选项。\n";
            }
        }
    }
}

// 运行主程序
$bankSystem = new BankSystem();
$bankSystem->run();

?>
