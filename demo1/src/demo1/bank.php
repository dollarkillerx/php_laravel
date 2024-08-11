<?php

// 模拟内部存储
$accounts = [];

// 读取命令行输入
function readInput($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
}

// 注册功能
function register(&$accounts) {
    $username = readInput("请输入用户名: ");
    if (isset($accounts[$username])) {
        echo "用户已存在。\n";
        return;
    }
    $password = readInput("请输入密码: ");
    $accounts[$username] = ['balance' => 0, 'password' => $password];
    echo "注册成功。\n";
}

// 登录功能
function login(&$accounts) {
    $username = readInput("请输入用户名: ");
    if (!isset($accounts[$username])) {
        echo "用户不存在。\n";
        return false;
    }
    $password = readInput("请输入密码: ");
    if ($accounts[$username]['password'] !== $password) {
        echo "密码错误。\n";
        return false;
    }
    echo "登录成功。\n";
    return $username;
}

// 查询余额功能
function checkBalance($username, $accounts) {
    if (isset($accounts[$username])) {
        echo "账户余额: " . $accounts[$username]['balance'] . "\n";
    } else {
        echo "账户不存在。\n";
    }
}

// 转账功能
function transfer($username, &$accounts) {
    $recipient = readInput("请输入收款人用户名: ");
    if (!isset($accounts[$recipient])) {
        echo "收款人账户不存在。\n";
        return;
    }
    $amount = (float) readInput("请输入转账金额: ");
    if ($amount <= 0) {
        echo "转账金额必须大于0。\n";
        return;
    }
    if ($accounts[$username]['balance'] < $amount) {
        echo "余额不足。\n";
        return;
    }
    $accounts[$username]['balance'] -= $amount;
    $accounts[$recipient]['balance'] += $amount;
    echo "转账成功。\n";
}

// 存款功能
function deposit($username, &$accounts) {
    $amount = (float) readInput("请输入存款金额: ");
    if ($amount <= 0) {
        echo "存款金额必须大于0。\n";
        return;
    }
    $accounts[$username]['balance'] += $amount;
    echo "存款成功。\n";
}

// 查询所有用户功能
function listUsers($accounts) {
    if (empty($accounts)) {
        echo "没有用户。\n";
        return;
    }
    foreach ($accounts as $username => $info) {
        echo "用户名: $username, 余额: " . $info['balance'] . "\n";
    }
}

// 主程序
function main() {
    global $accounts;
    while (true) {
        $action = readInput("请选择操作: 1. 注册 2. 登录 3. 查询所有用户 4. 退出: ");
        switch ($action) {
            case '1':
                register($accounts);
                break;
            case '2':
                $username = login($accounts);
                if ($username) {
                    while (true) {
                        $operation = readInput("选择操作: 1. 查询余额 2. 存款 3. 转账 4. 退出: ");
                        switch ($operation) {
                            case '1':
                                checkBalance($username, $accounts);
                                break;
                            case '2':
                                deposit($username, $accounts);
                                break;
                            case '3':
                                transfer($username, $accounts);
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
                listUsers($accounts);
                break;
            case '4':
                echo "退出系统。\n";
                exit;
            default:
                echo "无效的选项。\n";
        }
    }
}

// 运行主程序
main();
?>
