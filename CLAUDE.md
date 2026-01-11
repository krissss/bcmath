# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 项目概述

这是一个基于 PHP BCMath 扩展的高精度数学运算库，提供了面向对象的任意精度算术操作接口，对于浮点数精度误差不可接受的金融计算至关重要。

## 常用命令

```bash
# 运行测试
composer test
# 或直接运行
pest

# 运行特定测试文件
pest tests/BCSTest.php

# 运行特定测试用例
pest --filter testAdd

# 修复代码风格
composer fix
# 或直接运行
php-cs-fixer fix --config .php_cs

# 检查代码风格（干运行）
php-cs-fixer fix --config .php_cs --dry-run --diff
```

## 架构设计

库采用清晰的继承层次结构，通过共享基类提供通用功能：

```
BaseBC (抽象基类)
    ├── BC (静态便捷方法)
    ├── BCS (链式操作)
    ├── BCParser (表达式解析器)
    └── BCSummary (统计计算)
```

### BaseBC - 核心基础

所有类都继承自 `BaseBC`，它提供：
- **配置管理**：`scale`（最终精度）、`operateScale`（中间精度）、舍入模式
- **精度控制**：`getScaleNumber()` 处理不同的舍入策略（四舍五入、向上取整、向下取整）
- **Null 处理**：自动将 null 值转换为 '0'

### 两种使用模式

**1. BC（静态）- 直接返回结果**
```php
$result = BC::create(['scale' => 2])->add(1.2, 1.3, 1.5);
// 返回: 4.0
```
- 使用 `__call()` 动态调用 BCMath 函数
- 内部创建 BCS 实例，执行链式操作，返回最终结果
- 特殊处理：`compare()` 方法直接返回比较结果

**2. BCS（链式）- 有状态操作**
```php
$result = BCS::create(1.5, ['scale' => 2])->add(1.2)->mul(2)->sub(1.5)->getResult();
// 返回: 3.9
```
- 通过链式操作维护内部 `$result` 状态
- 提供比较方法：`isEqual()`、`isLessThan()`、`isLargerThan()`
- 特殊方法：`getSqrt()` 获取平方根
- 通过 `numberFormat()` 处理科学计数法

### BCParser - 表达式求值

解析支持变量替换的数学表达式：
```php
$result = BCParser::create(['scale' => 4])->parse('5*3+3.5-1.8/7');
// 支持: {a} + {b} 传入 ['a' => '1.2', 'b' => '1.3']
```
- 运算符：`+ - * / % ^` 遵循正确的优先级
- 递归处理括号
- 使用 BC 类进行实际计算

### BCSummary - 统计运算

提供常用统计计算：
- **average()**：按比例分配总数，处理精度舍入误差
- **upgrade()**：计算增长率，支持百分比转换

## 关键实现细节

### 精度系统

库使用**双精度系统**：
- `scale`：最终结果精度
- `operateScale`：中间计算精度（默认：2）

这确保多步计算期间的准确性，同时控制最终输出格式。

### 科学计数法处理

库对科学计数法的处理经历了多次演进：
- 最初使用 `number_format()` 转换科学计数法（如 1E-7）
- 当前行为：仅转换实际的科学计数法为十进制格式
- 非科学计数法数字保持不变以提高性能

### Null 参数处理

在 PHP 8.4+ 中，BCMath 函数接收 null 时会抛出弃用警告。库现在：
- 在传递给 BCMath 函数之前将 null 转换为 '0' 字符串
- 应用于 BC 和 BCS 类中的所有操作

### PHP 版本支持

- 支持 PHP 7.3+ 和 8.0+（包括 8.4）
- 需要 BCMath 扩展

## 测试

测试按类组织：
- `BCTest.php`：BC 基础操作
- `BCSTest.php`：链式操作、精度处理、舍入模式
- `BCParserTest.php`：各种运算符的表达式解析
- `BCSummaryTest.php`：统计计算
- `IssueTest.php`：已报告问题的回归测试

**注意**：项目使用 Pest 而非 PHPUnit。Pest 是一个基于 PHPUnit 构建的测试框架，具有更简洁的语法。

## 代码风格

项目使用 PHP-CS-Fixer，基于 PSR-2 规则加上自定义配置：
- 短数组语法 `[]`
- 有序的 import
- 删除未使用的 import
- 小写的静态引用（`self`、`static`、`parent`）
- 原生函数大小写

配置文件：`.php_cs`

## 常见模式

添加新的 BCMath 操作时：
1. 同时添加到 `BC` 和 `BCS` 类
2. 在 `BC` 中：使用 `__call()` 自动委托
3. 在 `BCS` 中：实现返回 `$this` 以支持链式调用的方法
4. 在 `BCTest.php` 和 `BCSTest.php` 中添加测试
5. 考虑精度处理和 null 值转换

修复精度相关问题时：
- 检查 `scale` 和 `operateScale` 设置
- 验证舍入模式行为（round、ceil、floor）
- 测试科学计数法边界情况
- 确保 null 值得到处理
