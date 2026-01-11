# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.5.4] - 2025-03-27

### Added
- 支持 PHP 8.4

### Fixed
- 修复 BSC 函数传 null 报错："bcxxx(): Passing null to parameter #2 ($num2) of type string is deprecated" (#13)
- 修复 tests 分支

## [2.5.3] - 2024-07-19

### Fixed
- 修复 number_format 精度问题 (#12)

## [2.5.2] - 2024-06-26

### Changed
- 对于非科学计数法的数字，不转 number_format (#10)

## [2.5.1] - 2024-06-25

### Added
- 支持使用科学计数法比较 (1e3)

## [2.5.0] - 2024-06-05

### Added
- 添加 GitHub Actions CI
- 补充测试用例提高测试覆盖率

### Changed
- 测试框架迁移至 Pest
- Parser 使用 BC 类

## [2.4.0] - 2024-06-05

### Fixed
- 使用 number_format 解决科学计数法问题

## [2.3] - 2021-11-18

### Fixed
- 修复精度为 0 时显示多个 0 的 bug

## [2.2] - 2021-11-18

### Fixed
- 修复末位为 0 的时候显示带 `.` 的 bug

## [2.1] - 2021-11-17

### Changed
- 重新处理进位和舍位的逻辑

## [2.0] - 2020-10-23

### Added
- 增加自动处理舍位和向上
- 添加 config round / ceil / floor

### Changed
- 移除 `rounded` 方法
- 默认使用 rounded 行为

## [1.1] - 2019-11-21

### Added
- 添加 BCSummary 类

## [1.0] - 2018-08-15

### Added
- 初始版本发布
